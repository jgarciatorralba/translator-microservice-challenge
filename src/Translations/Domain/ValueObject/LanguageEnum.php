<?php

declare(strict_types=1);

namespace App\Translations\Domain\ValueObject;

use App\Shared\Domain\Trait\EnumValuesProviderTrait;

enum LanguageEnum: string
{
    use EnumValuesProviderTrait;

    case ENGLISH = 'en';
    case SPANISH = 'es';
    case FRENCH = 'fr';
    case GERMAN = 'de';
    case PORTUGUESE = 'pt';
    case ITALIAN = 'it';
    case NOT_RECOGNIZED = 'not_recognized';

    /** @return string[] */
    public static function supportedValues(): array
    {
        return array_filter(
            array_column(self::cases(), 'value'),
            fn(string $case): bool => $case !== self::NOT_RECOGNIZED->value
        );
    }
}
