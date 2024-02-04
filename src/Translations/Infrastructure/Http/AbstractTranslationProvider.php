<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Http;

use App\Shared\Infrastructure\Http\Symfony\SymfonyHttpClient;
use App\Translations\Domain\Translation;
use App\Translations\Infrastructure\Log\TranslationLogger;

abstract class AbstractTranslationProvider
{
    public function __construct(
        protected readonly string $apiKey,
        protected readonly string $baseUri,
        protected readonly SymfonyHttpClient $httpClient,
        protected readonly TranslationLogger $logger
    ) {
    }

    /** @return array<string, mixed> */
    abstract public function generateRequestBody(Translation $translation): array;
}
