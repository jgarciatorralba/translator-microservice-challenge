<?php

declare(strict_types=1);

namespace App\Translations\Application\Query\GetTranslations;

use App\Shared\Domain\Bus\Query\Query;
use DateTimeImmutable;

final class GetTranslationsQuery implements Query
{
    public function __construct(
        private readonly ?int $pageSize,
        private readonly DateTimeImmutable $maxCreatedAt
    ) {
    }

    public function pageSize(): ?int
    {
        return $this->pageSize;
    }

    public function maxCreatedAt(): DateTimeImmutable
    {
        return $this->maxCreatedAt;
    }
}
