<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Filter;

final class Filters
{
    /** @param array <SimpleFilter|CompositeFilter> $filters */
    public function __construct(
        private readonly array $filters = [],
        private readonly FilterConditionEnum $condition = FilterConditionEnum::AND
    ) {
    }

    /** @return array <SimpleFilter|CompositeFilter> */
    public function plainFilters(): array
    {
        return $this->filters;
    }

    public function condition(): FilterConditionEnum
    {
        return $this->condition;
    }
}
