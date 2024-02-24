<?php

declare(strict_types=1);

namespace App\Translations\Application\Query\GetTranslations;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Filter\Filter;
use App\Shared\Domain\Criteria\Filter\FilterGroup;
use App\Shared\Domain\Criteria\Filter\FilterOperatorEnum;
use App\Shared\Domain\Criteria\Order\Order;
use App\Shared\Domain\Criteria\Order\OrderEnum;
use App\Translations\Domain\Service\GetTranslationsByCriteria;

final class GetTranslationsQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly GetTranslationsByCriteria $getTranslationsByCriteria
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

        $translations = $this->getTranslationsByCriteria->__invoke(
            new Criteria(
                filterGroup: $filterGroup,
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
