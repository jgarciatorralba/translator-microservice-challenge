<?php

declare(strict_types=1);

namespace App\Translations\Domain\Exception;

use App\Shared\Domain\DomainException;
use App\Shared\Utils;
use App\Translations\Domain\Contract\TranslationProvider;

class MissingProviderException extends DomainException
{
    public function __construct(
        private readonly int $missingImplementations
    ) {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'missing_provider';
    }

    public function errorMessage(): string
    {
        return sprintf(
            "At least %s more implementation%s of class '%s' required.",
            $this->missingImplementations,
            $this->missingImplementations === 1 ? '' : 's',
            Utils::extractClassName(TranslationProvider::class)
        );
    }
}
