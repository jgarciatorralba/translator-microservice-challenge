<?php

declare(strict_types=1);

namespace App\Translations\Domain\ValueObject\TranslationProvider;

final class TranslationProviderResponse
{
    public function __construct(
        private readonly int $statusCode = 200,
        private readonly ?string $error = null,
        private readonly ?string $translatedText = null,
        private readonly ?string $detectedLanguage = null
    ) {}

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function error(): ?string
    {
        return $this->error;
    }

    public function translatedText(): ?string
    {
        return $this->translatedText;
    }

    public function detectedLanguage(): ?string
    {
        return $this->detectedLanguage;
    }
}
