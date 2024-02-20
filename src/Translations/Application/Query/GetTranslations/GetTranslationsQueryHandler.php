<?php

declare(strict_types=1);

namespace App\Translations\Application\Query\GetTranslations;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Filter;
use App\Shared\Domain\Criteria\FilterConditionEnum;
use App\Shared\Domain\Criteria\OrderEnum;
use App\Translations\Domain\Service\GetTranslations;

final class GetTranslationsQueryHandler implements QueryHandler
{
    public function __construct(private readonly GetTranslations $getTranslations)
    {
    }

    public function __invoke(GetTranslationsQuery $query): GetTranslationsResponse
    {
        $limit = $query->pageSize() > 0 ? $query->pageSize() : null;
        $maxCreatedAt = $query->maxCreatedAt();

        $translations = $this->getTranslations->__invoke(
            new Criteria(
                filters: [
                    new Filter(
                        columnName: 'createdAt',
                        condition: FilterConditionEnum::LOWER_THAN,
                        value: $maxCreatedAt
                    )
                ],
                orderBy: [
                    'createdAt' => OrderEnum::DESCENDING
                ],
                limit: $limit,
                offset: null
            )
        );
        $translations = array_map(
            fn(AggregateRoot $translation) => $translation->toArray(),
            $translations
        );

        return new GetTranslationsResponse([
            'translations' => $translations,
            'count' => count($translations)
        ]);
    }
}
