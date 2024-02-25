<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\Filter\Filter;
use App\Shared\Domain\Criteria\Filter\FilterConditionEnum;
use App\Shared\Domain\Criteria\Filter\Filters;
use App\Shared\Domain\Criteria\Order\Order;
use App\Shared\Domain\Criteria\Order\OrderEnum;

class AndCriteria extends Criteria
{
    /** @param Filter[] $filters */
    public function __construct(
        array $filters = [],
        ?int $limit = null,
        ?int $offset = null
    ) {
        parent::__construct(
            filters: new Filters($filters, FilterConditionEnum::AND),
            orderBy: [new Order('createdAt', OrderEnum::DESCENDING)],
            limit: $limit,
            offset: $offset
        );
    }
}
