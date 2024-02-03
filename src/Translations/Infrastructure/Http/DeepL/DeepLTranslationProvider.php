<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Http\DeepL;

use App\Translations\Domain\Contract\TranslationProvider;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use App\Translations\Domain\ValueObject\TranslationProviderResponse;
use App\Translations\Infrastructure\Http\AbstractTranslationProvider;

final class DeepLTranslationProvider extends AbstractTranslationProvider implements TranslationProvider
{
    public function translate(Translation $translation): TranslationProviderResponse
    {
        $response = $this->httpClient->submit('translate', [
            'base_uri' => $this->baseUri,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "DeepL-Auth-Key $this->apiKey"
            ],
            'json' => $this->generateRequestBody($translation)
        ]);

        if (!empty($response->error())) {
            return new TranslationProviderResponse(
                $response->statusCode(),
                $response->error()
            );
        }

        $decodedContent = json_decode($response->content(), true);
        $translation = isset($decodedContent['translations'])
            && is_array($decodedContent['translations'])
                ? $decodedContent['translations'][0]['text']
                : null;
        $sourceLang = isset($decodedContent['translations'][0]['detected_source_language'])
            ? strtolower($decodedContent['translations'][0]['detected_source_language'])
            : null;

        return new TranslationProviderResponse(
            $response->statusCode(),
            null,
            $response->content(),
            $translation,
            $sourceLang
        );
    }

    /** @return array<string, string|string[]> */
    public function generateRequestBody(Translation $translation): array
    {
        $body = [
            'text' => [$translation->originalText()],
            'target_lang' =>
                $this->mapLanguageCode(SupportedLanguageEnum::from($translation->targetLanguage()))
        ];
        if (!empty($translation->sourceLanguage())) {
            $body['source_lang'] = strtoupper($translation->sourceLanguage());
        }

        return $body;
    }

    public function mapLanguageCode(SupportedLanguageEnum $languageCode): string
    {
        if ($languageCode === SupportedLanguageEnum::ENGLISH) {
            return 'EN-GB';
        } elseif ($languageCode === SupportedLanguageEnum::PORTUGUESE_PORTUGAL) {
            return 'PT-PT';
        }

        return strtoupper($languageCode->value);
    }
}
