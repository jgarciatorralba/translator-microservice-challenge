<?php

declare(strict_types=1);

namespace App\Translations\Application\Query\GetTranslations;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\ValueObject\SearchCriteria\Criteria;
use App\Shared\Domain\ValueObject\SearchCriteria\Filter;
use App\Shared\Domain\ValueObject\SearchCriteria\FilterConditionEnum;
use App\Shared\Domain\ValueObject\SearchCriteria\OrderEnum;
use App\Translations\Domain\Service\GetTranslations;

final class GetTranslationsQueryHandler implements QueryHandler
{
    public function __construct(private readonly GetTranslations $getTranslations)
    {
    }

    public function __invoke(GetTranslationsQuery $query): GetTranslationsResponse
    {
        $limit = $query->pageSize() > 0 ? $query->pageSize() : null;
        $createdAt = $query->createdAt();

        $translations = $this->getTranslations->__invoke(
            new Criteria(
                filters: [
                    new Filter(
                        columnName: 'createdAt',
                        condition: FilterConditionEnum::LOWER_THAN,
                        value: $createdAt
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
