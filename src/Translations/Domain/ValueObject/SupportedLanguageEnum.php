<?php

declare(strict_types=1);

namespace App\Translations\Domain\ValueObject;

use App\Shared\Domain\Trait\EnumValuesProviderTrait;

enum SupportedLanguageEnum: string
{
    use EnumValuesProviderTrait;

    case ENGLISH_GREAT_BRITAIN = 'en';
    case SPANISH_SPAIN = 'es';
    case FRENCH_STANDARD = 'fr';
    case GERMAN_STANDARD = 'de';
    case PORTUGUESE_PORTUGAL = 'pt';
    case ITALIAN = 'it';
    case NOT_RECOGNIZED = 'not_recognized';

    /** @return string[] */
    public static function supportedValues(): array
    {
        return array_filter(
            array_column(self::cases(), 'value'),
            fn(string $case) => $case !== self::NOT_RECOGNIZED->value
        );
    }
}
