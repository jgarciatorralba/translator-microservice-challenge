<?php

namespace App\Translations\Infrastructure\Http\LectoAI;

use App\Shared\Infrastructure\Http\Symfony\SymfonyHttpClient;
use App\Translations\Domain\Contract\TranslationProvider;
use App\Translations\Domain\ValueObject\TranslationRequest;
use App\Translations\Domain\ValueObject\TranslationResponse;
use Exception;

class LectoAITranslationProvider implements TranslationProvider
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUri,
        private readonly SymfonyHttpClient $httpClient
    ) {
    }

    public function translate(TranslationRequest $translation): TranslationResponse
    {
        $request = $this->httpClient->submit(
            'translate/text',
            [
                'base_uri' => $this->baseUri,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-API-Key' => $this->apiKey
                ],
                'json' => $this->getRequestBody($translation)
            ]
        );

        $statusCode = $request->getStatusCode();

        try {
            $content = $request->toArray();
            $translation = isset($content['translations']['translated'])
                && is_array($content['translations']['translated'])
                    ? $content['translations']['translated'][0]
                    : null;
            return new TranslationResponse(
                $statusCode,
                null,
                $translation,
                $content['from']
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
            'texts' => [$translation->originalText()],
            'to' => [$translation->targetLanguage()]
        ];
        if (!empty($translation->sourceLanguage())) {
            $body['from'] = $translation->sourceLanguage();
        }

        return $body;
    }
}