<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\Filter\SimpleFilter;
use App\Shared\Domain\Criteria\Filter\FilterConditionEnum;
use App\Shared\Domain\Criteria\Filter\FilterOperatorEnum;
use App\Shared\Domain\Criteria\Filter\Filters;
use App\Shared\Domain\Criteria\Order\Order;
use App\Shared\Domain\Criteria\Order\OrderEnum;
use DateTimeImmutable;

final class AndCriteria extends Criteria
{
    /** @param SimpleFilter[] $filters */
    public function __construct(
        DateTimeImmutable $maxCreatedAt = new DateTimeImmutable(),
        array $filters = [],
        ?int $limit = null
    ) {
        parent::__construct(
            filters: new Filters([
                ...$filters,
                new SimpleFilter('createdAt', $maxCreatedAt, FilterOperatorEnum::LOWER_THAN)
            ], FilterConditionEnum::AND),
            orderBy: [new Order('createdAt', OrderEnum::DESCENDING)],
            limit: $limit
        );
    }
}
