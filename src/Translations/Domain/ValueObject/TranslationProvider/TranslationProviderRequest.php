<?php

declare(strict_types=1);

namespace App\Translations\Domain\ValueObject\TranslationProvider;

final class TranslationProviderRequest
{
    public function __construct(
        private readonly string $originalText,
        private readonly string $targetLanguage,
        private readonly ?string $sourceLanguage = null
    ) {}

    public function sourceLanguage(): ?string
    {
        return $this->sourceLanguage;
    }

    public function originalText(): string
    {
        return $this->originalText;
    }

    public function targetLanguage(): string
    {
        return $this->targetLanguage;
    }
}
