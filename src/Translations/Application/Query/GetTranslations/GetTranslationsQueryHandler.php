<?php

declare(strict_types=1);

namespace App\Translations\Application\Query\GetTranslations;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Criteria\CreatedBeforeDateTimeCriteria;
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
        $translations = $this->getTranslationsByCriteria->__invoke(
            new CreatedBeforeDateTimeCriteria(
                $query->maxCreatedAt(),
                $limit
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
