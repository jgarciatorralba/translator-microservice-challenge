<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Http\LectoAI;

use App\Translations\Domain\Contract\LanguageCodeConverter;
use App\Translations\Domain\ValueObject\LanguageEnum;

final class LectoAILanguageCodeConverter implements LanguageCodeConverter
{
    public static function convert(LanguageEnum $languageCode): string
    {
        if ($languageCode === LanguageEnum::PORTUGUESE) {
            return 'pt-PT';
        }

        return $languageCode->value;
    }

    public static function revert(string $languageCode): LanguageEnum
    {
        if ($languageCode === 'pt-PT' || $languageCode === 'pt-BR') {
            return LanguageEnum::PORTUGUESE;
        }

        return LanguageEnum::tryFrom($languageCode) ?? LanguageEnum::NOT_RECOGNIZED;
    }
}
