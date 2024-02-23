<?php

declare(strict_types=1);

namespace App\Translations\Application\Query\GetTranslations;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Filter;
use App\Shared\Domain\Criteria\FilterGroup;
use App\Shared\Domain\Criteria\FilterOperatorEnum;
use App\Shared\Domain\Criteria\Order;
use App\Shared\Domain\Criteria\OrderEnum;
use App\Translations\Domain\Service\GetTranslations;

final class GetTranslationsQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly GetTranslations $getTranslations
    ) {
    }

    public function __invoke(GetTranslationsQuery $query): GetTranslationsResponse
    {
        $limit = $query->pageSize() > 0 ? $query->pageSize() : null;
        $maxCreatedAt = $query->maxCreatedAt();

        $filter = new Filter(
            field: 'createdAt',
            operator: FilterOperatorEnum::LOWER_THAN,
            value: $maxCreatedAt
        );
        $filterGroup = new FilterGroup([$filter]);
        $order = new Order('createdAt', OrderEnum::DESCENDING);

        $translations = $this->getTranslations->__invoke(
            new Criteria(
                filterGroups: [$filterGroup],
                orderBy: [$order],
                limit: $limit
            )
        );

        $translations = array_map(
            fn (AggregateRoot $translation) => $translation->toArray(),
            $translations
        );

        return new GetTranslationsResponse([
            'translations' => $translations,
            'count' => count($translations)
        ]);
    }
}
