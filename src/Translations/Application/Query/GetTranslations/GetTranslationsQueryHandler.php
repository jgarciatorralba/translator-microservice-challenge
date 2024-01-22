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
        $translations = $this->getTranslations->__invoke();
        $translations = array_map(fn(AggregateRoot $translation) => $translation->toArray(), $translations);

        return new GetTranslationsResponse($translations);
    }
}
