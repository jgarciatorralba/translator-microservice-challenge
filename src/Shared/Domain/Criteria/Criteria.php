<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

final class Criteria
{
    private const MAX_PAGE_SIZE = 50;

    private int $limit;

    /**
     * @param FilterGroup[] $filterGroups
     * @param Order[] $orderBy
     */
    public function __construct(
        private readonly array $filterGroups,
        private readonly ?array $orderBy,
        ?int $limit,
        private readonly ?int $offset
    ) {
        if ($limit === null || $limit > self::MAX_PAGE_SIZE) {
            $this->limit = self::MAX_PAGE_SIZE;
        }
    }

    /** @return FilterGroup[] */
    public function filterGroups(): array
    {
        return $this->filterGroups;
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

    public function hasFilterGroups(): bool
    {
        return !empty($this->filterGroups());
    }

    public function hasOrder(): bool
    {
        return !empty($this->orderBy());
    }
}
