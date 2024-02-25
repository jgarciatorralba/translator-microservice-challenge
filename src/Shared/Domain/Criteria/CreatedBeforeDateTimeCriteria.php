<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\Filter\Filter;
use App\Shared\Domain\Criteria\Filter\FilterOperatorEnum;
use App\Shared\Domain\Criteria\Filter\Filters;
use App\Shared\Domain\Criteria\Order\Order;
use App\Shared\Domain\Criteria\Order\OrderEnum;
use DateTimeImmutable;

class CreatedBeforeDateTimeCriteria extends Criteria
{
    public function __construct(
        DateTimeImmutable $maxCreatedAt = new DateTimeImmutable(),
        ?int $limit = null
    ) {
        parent::__construct(
            filters: new Filters([
                new Filter('createdAt', $maxCreatedAt, FilterOperatorEnum::LOWER_THAN)
            ]),
            orderBy: [new Order('createdAt', OrderEnum::DESCENDING)],
            limit: $limit
        );
    }
}
