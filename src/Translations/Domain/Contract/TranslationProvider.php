<?php

declare(strict_types=1);

namespace App\Translations\Domain\Contract;

use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use App\Translations\Domain\ValueObject\TranslationProviderResponse;

interface TranslationProvider
{
    public function translate(Translation $translation): TranslationProviderResponse;

    public function convertLanguageCode(SupportedLanguageEnum $languageCode): string;

    public function revertLanguageCode(string $languageCode): SupportedLanguageEnum;
}
