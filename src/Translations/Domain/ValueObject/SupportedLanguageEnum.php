<?php

declare(strict_types=1);

namespace App\Translations\Domain\ValueObject;

use App\Shared\Domain\Trait\EnumValuesProviderTrait;

enum SupportedLanguageEnum: string
{
    use EnumValuesProviderTrait;

    case ENGLISH = 'en';
    case SPANISH_SPAIN = 'es';
    case FRENCH_STANDARD = 'fr';
    case GERMAN_STANDARD = 'de';
    case PORTUGUESE_PORTUGAL = 'pt';
}
