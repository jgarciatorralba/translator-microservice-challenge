<?php

declare(strict_types=1);

namespace App\Translations\Domain\Contract;

use App\Translations\Domain\ValueObject\LanguageEnum;

interface LanguageCodeAdapter
{
    public static function convert(LanguageEnum $languageCode): string;

    public static function revert(string $languageCode): LanguageEnum;
}
