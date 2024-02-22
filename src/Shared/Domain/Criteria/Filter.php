<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

final class Filter
{
    public function __construct(
        private readonly string $columnName,
        private readonly mixed $value,
        private readonly FilterOperatorEnum $operator = FilterOperatorEnum::EQUAL
    ) {
    }

    public function operator(): FilterOperatorEnum
    {
        return $this->operator;
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
