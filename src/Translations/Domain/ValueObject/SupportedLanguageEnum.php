<?php

declare(strict_types=1);

namespace App\Translations\Domain\ValueObject;

use App\Shared\Domain\Trait\EnumValuesProviderTrait;

enum SupportedLanguageEnum: string
{
    use EnumValuesProviderTrait;

    case ENGLISH = 'en';
    case SPANISH = 'es';
    case FRENCH = 'fr';
    case GERMAN = 'de';
    case PORTUGUESE = 'pt';
}
