<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

final class Criteria
{
    /**
     * @param Filter[] $filters
     * @param Order[] $orderBy
     */
    public function __construct(
        private readonly array $filters,
        private readonly ?array $orderBy,
        private readonly ?int $limit,
        private readonly ?int $offset
    ) {}

    /** @return Filter[] */
    public function filters(): array
    {
        return $this->filters;
    }

    /** @return Order[] */
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

    public function hasFilters(): bool
    {
        return !empty($this->filters());
    }

    public function hasOrder(): bool
    {
        return !empty($this->orderBy());
    }
}
