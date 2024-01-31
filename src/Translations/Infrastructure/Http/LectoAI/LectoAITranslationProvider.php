<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Http\LectoAI;

use App\Translations\Domain\Contract\TranslationProvider;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\TranslationProviderResponse;
use App\Translations\Infrastructure\Http\AbstractTranslationProvider;
use Exception;

class LectoAITranslationProvider extends AbstractTranslationProvider implements TranslationProvider
{
    public function translate(Translation $translation): TranslationProviderResponse
    {
        try {
            $request = $this->httpClient->submit('translate/text', [
                'base_uri' => $this->baseUri,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-API-Key' => $this->apiKey
                ],
                'json' => $this->generateRequestBody($translation)
            ]);

            $statusCode = $request->getStatusCode();
            $content = $request->toArray();
            $translation = isset($content['translations']['translated'])
                && is_array($content['translations']['translated'])
                    ? $content['translations']['translated'][0]
                    : null;

            return new TranslationProviderResponse(
                $statusCode,
                null,
                $translation,
                $content['from']
            );
        } catch (Exception $e) {
            return new TranslationProviderResponse(
                $statusCode ?? null,
                $e->getMessage()
            );
        }
    }

    /** @return array<string, string|string[]> */
    public function generateRequestBody(Translation $translation): array
    {
        $body = [
            'texts' => [$translation->originalText()],
            'to' => [$translation->targetLanguage()]
        ];
        if (!empty($translation->sourceLanguage())) {
            $body['from'] = $translation->sourceLanguage();
        }

        return $body;
    }
}
