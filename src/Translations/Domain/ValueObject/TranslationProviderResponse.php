<?php

declare(strict_types=1);

namespace App\Translations\Domain\ValueObject;

final class TranslationProviderResponse
{
    private const HTTP_BAD_REQUEST = 400;

    public function __construct(
        private readonly ?int $statusCode = null,
        private readonly ?string $error = null,
        private readonly ?string $translatedText = null,
        private readonly ?string $detectedLanguage = null
    ) {
    }

    public function statusCode(): ?int
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

    public function isFromBadRequest(): bool
    {
        return $this->statusCode === self::HTTP_BAD_REQUEST;
    }
}
