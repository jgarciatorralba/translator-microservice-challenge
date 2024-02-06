<?php

declare(strict_types=1);

namespace App\Translations\Application\Query\GetTranslations;

use App\Translations\Domain\Service\GetTranslations;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Bus\Query\QueryHandler;

final class GetTranslationsQueryHandler implements QueryHandler
{
    public function __construct(private readonly GetTranslations $getTranslations)
    {
    }

    public function __invoke(GetTranslationsQuery $query): GetTranslationsResponse
    {
        $limit = ($query->size() !== null && $query->size() > 0)
            ? $query->size()
            : null;
        $offset = ($limit !== null && $query->page() !== null && $query->page() > 0)
            ? ($query->page() - 1) * $limit
            : 0;

        $translations = $this->getTranslations->__invoke(
            criteria: [],
            orderBy: ['createdAt' => 'DESC'],
            limit: $limit,
            offset: $offset
        );
        $translations = array_map(fn(AggregateRoot $translation) => $translation->toArray(), $translations);

        return new GetTranslationsResponse([
            'items' => $translations,
            'count' => count($translations)
        ]);
    }
}
