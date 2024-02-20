<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

final class Filter
{
    public function __construct(
        private string $columnName,
        private mixed $value,
        private readonly FilterOperatorEnum $operator = FilterOperatorEnum::AND,
        private readonly FilterConditionEnum $condition = FilterConditionEnum::EQUAL
    ) {
    }

    public function operator(): FilterOperatorEnum
    {
        return $this->operator;
    }

    public function condition(): FilterConditionEnum
    {
        return $this->condition;
    }

    public function columnName(): string
    {
        return $this->columnName;
    }

    public function value(): mixed
    {
        return $this->value;
    }
}
