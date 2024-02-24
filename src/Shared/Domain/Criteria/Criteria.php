<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\Filter\FilterGroup;
use App\Shared\Domain\Criteria\Order\Order;

final class Criteria
{
    private const MAX_PAGE_SIZE = 50;

    private int $limit;

    /** @param Order[] $orderBy */
    public function __construct(
        private readonly ?FilterGroup $filterGroup = null,
        private readonly ?array $orderBy = null,
        ?int $limit = null,
        private readonly ?int $offset = null
    ) {
        if ($limit === null || $limit > self::MAX_PAGE_SIZE) {
            $this->limit = self::MAX_PAGE_SIZE;
        } else {
            $this->limit = $limit;
        }
    }

    public function filterGroup(): ?FilterGroup
    {
        return $this->filterGroup;
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

    public function hasFilterGroup(): bool
    {
        return !empty($this->filterGroup());
    }

    public function hasOrder(): bool
    {
        return !empty($this->orderBy());
    }
}
