<?php

declare(strict_types=1);

namespace App\Translations\Application\Query\GetTranslations;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Translations\Domain\Service\GetTranslations;

final class GetTranslationsQueryHandler implements QueryHandler
{
    public function __construct(private readonly GetTranslations $getTranslations)
    {
    }

    public function __invoke(GetTranslationsQuery $query): GetTranslationsResponse
    {
        $limit = $query->size() > 0 ? $query->size() : null;
        $offset = $query->page() > 0 ? ($query->page() - 1) * ($limit ?? 0) : 0;

        $translations = $this->getTranslations->__invoke(
            criteria: [],
            orderBy: ['createdAt' => 'DESC'],
            limit: $limit,
            offset: $offset
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
