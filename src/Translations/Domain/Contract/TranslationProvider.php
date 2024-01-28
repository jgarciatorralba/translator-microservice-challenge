<?php

declare(strict_types=1);

namespace App\Translations\Domain\Contract;

use App\Translations\Domain\ValueObject\TranslationRequest;
use App\Translations\Domain\ValueObject\TranslationResponse;

interface TranslationProvider
{
    public function translate(TranslationRequest $translation): TranslationResponse;
}
