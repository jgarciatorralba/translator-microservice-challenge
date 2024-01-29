<?php

declare(strict_types=1);

namespace App\Translations\Domain\Contract;

use App\Translations\Domain\ValueObject\TranslationProvider\TranslationProviderRequest;
use App\Translations\Domain\ValueObject\TranslationProvider\TranslationProviderResponse;

interface TranslationProvider
{
    public function translate(TranslationProviderRequest $translation): TranslationProviderResponse;
}
