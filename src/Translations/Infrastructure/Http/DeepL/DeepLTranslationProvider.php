<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Http\DeepL;

use App\Translations\Domain\Contract\TranslationProvider;
use App\Translations\Domain\ValueObject\TranslationProvider\TranslationProviderRequest;
use App\Translations\Domain\ValueObject\TranslationProvider\TranslationProviderResponse;
use App\Translations\Infrastructure\Http\AbstractTranslationProvider;
use Exception;

class DeepLTranslationProvider extends AbstractTranslationProvider implements TranslationProvider
{
    public function translate(TranslationProviderRequest $translation): TranslationProviderResponse
    {
        try {
            $request = $this->httpClient->submit('translate', [
                'base_uri' => $this->baseUri,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "DeepL-Auth-Key $this->apiKey"
                ],
                'json' => $this->generateRequestBody($translation)
            ]);

            $statusCode = $request->getStatusCode();
            $content = $request->toArray();
            $translation = isset($content['translations'])
                && is_array($content['translations'])
                    ? $content['translations'][0]['text']
                    : null;

            return new TranslationProviderResponse(
                $statusCode,
                null,
                $translation,
                strtolower($content['translations'][0]['detected_source_language'])
            );
        } catch (Exception $e) {
            return new TranslationProviderResponse(
                $statusCode ?? null,
                $e->getMessage()
            );
        }
    }

    /** @return array<string, string|string[]> */
    public function generateRequestBody(TranslationProviderRequest $translation): array
    {
        $body = [
            'text' => [$translation->originalText()],
            'target_lang' => strtoupper($translation->targetLanguage())
        ];
        if (!empty($translation->sourceLanguage())) {
            $body['source_lang'] = strtoupper($translation->sourceLanguage());
        }

        return $body;
    }
}
