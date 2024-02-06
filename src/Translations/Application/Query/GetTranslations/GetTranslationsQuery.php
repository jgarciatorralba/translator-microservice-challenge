<?php

declare(strict_types=1);

namespace App\Translations\Application\Query\GetTranslations;

use App\Shared\Domain\Bus\Query\Query;

final class GetTranslationsQuery implements Query
{
    public function __construct(
        private readonly ?int $page,
        private readonly ?int $size
    ) {
    }

    public function page(): ?int
    {
        return $this->page;
    }

    public function size(): ?int
    {
        return $this->size;
    }
}
