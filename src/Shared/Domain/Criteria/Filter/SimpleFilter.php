<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Filter;

final class SimpleFilter implements Filter
{
    public function __construct(
        private readonly string $field,
        private readonly mixed $value,
        private readonly FilterOperatorEnum $operator = FilterOperatorEnum::EQUAL
    ) {
    }

    public function field(): string
    {
        return $this->field;
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function operator(): FilterOperatorEnum
    {
        return $this->operator;
    }
}
