<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Filter;

final class FilterGroup
{
    /** @param Filter[] $filters */
    public function __construct(
        private readonly array $filters = [],
        private readonly FilterConditionEnum $condition = FilterConditionEnum::AND
    ) {
    }

    /** @return Filter[] */
    public function filters(): array
    {
        return $this->filters;
    }

    public function condition(): FilterConditionEnum
    {
        return $this->condition;
    }
}
