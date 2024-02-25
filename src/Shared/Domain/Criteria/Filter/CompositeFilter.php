<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Filter;

class CompositeFilter
{
    /** @param SimpleFilter[] $filters */
    public function __construct(
        private readonly array $filters = [],
        private readonly FilterConditionEnum $condition = FilterConditionEnum::AND
    ) {
    }

    /** @return SimpleFilter[] */
    public function filters(): array
    {
        return $this->filters;
    }

    public function condition(): FilterConditionEnum
    {
        return $this->condition;
    }
}
