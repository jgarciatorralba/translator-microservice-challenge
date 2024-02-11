<?php

declare(strict_types=1);

namespace App\Translations\Domain\ValueObject;

use App\Shared\Domain\ValueObject\HttpResponse;
use DateTimeImmutable;

final class TranslationProviderResponse extends HttpResponse
{
    public function __construct(
        ?int $statusCode = null,
        ?string $error = null,
        ?string $content = null,
        private readonly ?string $translatedText = null,
        private readonly ?SupportedLanguageEnum $detectedLanguage = null,
        private readonly ?DateTimeImmutable $translatedAt = new DateTimeImmutable()
    ) {
        parent::__construct($statusCode, $error, $content);
    }

    public function translatedText(): ?string
    {
        return $this->translatedText;
    }

    public function detectedLanguage(): ?SupportedLanguageEnum
    {
        return $this->detectedLanguage;
    }

    public function translatedAt(): DateTimeImmutable
    {
        return $this->translatedAt;
    }
}
