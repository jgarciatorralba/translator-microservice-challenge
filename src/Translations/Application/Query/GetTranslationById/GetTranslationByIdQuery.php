<?php

declare(strict_types=1);

namespace App\Translations\Application\Query\GetTranslationById;

use App\Shared\Domain\Bus\Query\Query;

final class GetTranslationByIdQuery implements Query
{
    public function __construct(
        private readonly string $id
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }
}
