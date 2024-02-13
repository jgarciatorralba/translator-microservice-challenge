<?php

declare(strict_types=1);

namespace App\Translations\Domain\ValueObject;

use App\Shared\Domain\ValueObject\HttpResponse;

final class TranslationProviderResponse extends HttpResponse
{
    public function __construct(
        ?int $statusCode = null,
        ?string $error = null,
        ?string $content = null,
        private readonly ?string $translatedText = null,
        private readonly ?LanguageEnum $detectedLanguage = null,
    ) {
        parent::__construct($statusCode, $error, $content);
    }

    public function translatedText(): ?string
    {
        return $this->translatedText;
    }

    public function detectedLanguage(): ?LanguageEnum
    {
        return $this->detectedLanguage;
    }
}
