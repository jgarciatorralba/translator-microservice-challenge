<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Http\DeepL;

use App\Translations\Domain\Contract\LanguageCodeConverter;
use App\Translations\Domain\ValueObject\LanguageEnum;

final class DeepLLanguageCodeConverter implements LanguageCodeConverter
{
    public static function convert(LanguageEnum $languageCode): string
    {
        if ($languageCode === LanguageEnum::ENGLISH) {
            return 'EN-GB';
        } elseif ($languageCode === LanguageEnum::PORTUGUESE) {
            return 'PT-PT';
        }

        return strtoupper($languageCode->value);
    }

    public static function revert(string $languageCode): LanguageEnum
    {
        return LanguageEnum::tryFrom(strtolower($languageCode)) ?? LanguageEnum::NOT_RECOGNIZED;
    }
}
