<?php

declare(strict_types=1);

namespace App\Translations\Domain\Contract;

use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\TranslationProviderResponse;

interface TranslationProvider
{
    public function translate(Translation $translation): TranslationProviderResponse;
}
