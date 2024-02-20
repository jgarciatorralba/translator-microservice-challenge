<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

final class Criteria
{
    /**
     * @param Filter[] $filters
     * @param array <string, OrderEnum> $orderBy
     */
    public function __construct(
        private readonly array $filters,
        private readonly ?array $orderBy,
        private readonly ?int $limit,
        private readonly ?int $offset
    ) {
    }

    /** @return Filter[] */
    public function filters(): array
    {
        return $this->filters;
    }

    /** @return array <string, OrderEnum> */
    public function orderBy(): ?array
    {
        return $this->orderBy;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function offset(): ?int
    {
        return $this->offset;
    }
}
