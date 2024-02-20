<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Http\DeepL;

use App\Shared\Utils;
use App\Translations\Domain\Contract\TranslationProvider;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\TranslationProviderResponse;
use App\Translations\Infrastructure\Http\AbstractTranslationProvider;

final class DeepLTranslationProvider extends AbstractTranslationProvider implements TranslationProvider
{
    public function translate(Translation $translation): TranslationProviderResponse
    {
        $message = "Requesting translation from class '"
            . Utils::extractClassName(self::class) . "'";
        $this->logger->info($message, [
            'translation' => $translation->toArray()
        ]);

        $response = $this->httpClient->submit('translate', [
            'base_uri' => $this->baseUri,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "DeepL-Auth-Key $this->apiKey"
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
        $translatedText = isset($decodedContent['translations'])
            && is_array($decodedContent['translations'])
                ? $decodedContent['translations'][0]['text']
                : null;
        $sourceLang = isset($decodedContent['translations'][0]['detected_source_language'])
            ? DeepLLanguageCodeConverter::revert($decodedContent['translations'][0]['detected_source_language'])
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
    protected function generateRequestBody(Translation $translation): array
    {
        $body = [
            'text' => [$translation->originalText()],
            'target_lang' =>
                DeepLLanguageCodeConverter::convert($translation->targetLanguage())
        ];
        if (!empty($translation->sourceLanguage())) {
            $body['source_lang'] = strtoupper($translation->sourceLanguage()->value);
        }

        return $body;
    }
}
