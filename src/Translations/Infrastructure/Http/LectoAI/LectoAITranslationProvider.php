<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Http\LectoAI;

use App\Shared\Utils;
use App\Translations\Domain\Contract\TranslationProvider;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use App\Translations\Domain\ValueObject\TranslationProviderResponse;
use App\Translations\Infrastructure\Http\AbstractTranslationProvider;

final class LectoAITranslationProvider extends AbstractTranslationProvider implements TranslationProvider
{
    public function translate(Translation $translation): TranslationProviderResponse
    {
        $message = "Requesting translation from class '"
            . Utils::extractClassName(self::class) . "'";
        $this->logger->info($message, [
            'translation' => $translation->toArray()
        ]);

        $response = $this->httpClient->submit('translate/text', [
            'base_uri' => $this->baseUri,
            'headers' => [
                'Content-Type' => 'application/json',
                'X-API-Key' => $this->apiKey
            ],
            'json' => $this->generateRequestBody($translation)
        ]);

        if (!empty($response->error())) {
            $this->logger->error("Error requesting translation.", [
                'translation' => $translation->id()->value(),
                'statusCode' => $response->statusCode(),
                'error' => $response->error()
            ]);

            return new TranslationProviderResponse(
                $response->statusCode(),
                $response->error()
            );
        }

        $decodedContent = json_decode($response->content(), true);
        $translatedText = isset($decodedContent['translations'][0]['translated'])
            && is_array($decodedContent['translations'][0]['translated'])
                ? $decodedContent['translations'][0]['translated'][0]
                : null;
        $sourceLang = isset($decodedContent['from'])
            ? $this->revertLanguageCode($decodedContent['from'])
            : null;

        $this->logger->info('Translation request completed.', [
            'translation' => $translation->id()->value(),
            'statusCode' => $response->statusCode(),
            'content' => $decodedContent
        ]);

        return new TranslationProviderResponse(
            $response->statusCode(),
            null,
            $response->content(),
            $translatedText,
            $sourceLang
        );
    }

    /** @return array<string, string|string[]> */
    public function generateRequestBody(Translation $translation): array
    {
        $body = [
            'texts' => [$translation->originalText()],
            'to' => [
                $this->convertLanguageCode($translation->targetLanguage())
            ]
        ];
        if (!empty($translation->sourceLanguage())) {
            $body['from'] = $this->convertLanguageCode($translation->sourceLanguage());
        }

        return $body;
    }

    public function convertLanguageCode(SupportedLanguageEnum $languageCode): string
    {
        if ($languageCode === SupportedLanguageEnum::PORTUGUESE) {
            return 'pt-PT';
        }

        return $languageCode->value;
    }

    public function revertLanguageCode(string $languageCode): SupportedLanguageEnum
    {
        if ($languageCode === 'pt-PT' || $languageCode === 'pt-BR') {
            return SupportedLanguageEnum::PORTUGUESE;
        }

        return SupportedLanguageEnum::tryFrom($languageCode) ?? SupportedLanguageEnum::NOT_RECOGNIZED;
    }
}
