<?php

namespace App\Translations\Infrastructure\Http\DeepL;

use App\Shared\Infrastructure\Http\Symfony\SymfonyHttpClient;
use App\Translations\Domain\Contract\TranslationProvider;
use App\Translations\Domain\ValueObject\TranslationRequest;
use App\Translations\Domain\ValueObject\TranslationResponse;
use Exception;

class DeepLTranslationProvider implements TranslationProvider
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUri,
        private readonly SymfonyHttpClient $httpClient
    ) {}

    public function translate(TranslationRequest $translation): TranslationResponse
    {
        $request = $this->httpClient->submit(
            'translate',
            [
                'base_uri' => $this->baseUri,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "DeepL-Auth-Key $this->apiKey"
                ],
                'json' => $this->getRequestBody($translation)
            ]
        );

        $statusCode = $request->getStatusCode();

        try {
            $content = $request->toArray();
            $translation = isset($content['translations'])
                && is_array($content['translations'])
                    ? $content['translations'][0]['text']
                    : null;
            return new TranslationResponse(
                $statusCode,
                null,
                $translation,
                strtolower($content['translations'][0]['detected_source_language'])
            );
        } catch (Exception $e) {
            return new TranslationResponse(
                $statusCode,
                $e->getMessage()
            );
        }
    }

    /** @return array<string, string|string[]> */
    private function getRequestBody(TranslationRequest $translation): array
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