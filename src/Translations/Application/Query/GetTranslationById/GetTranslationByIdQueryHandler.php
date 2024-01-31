<?php

declare(strict_types=1);

namespace App\Translations\Application\Query\GetTranslationById;

use App\Translations\Domain\Service\GetTranslationById;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\ValueObject\Uuid;

final class GetTranslationByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly GetTranslationById $getTranslationById
    ) {
    }

    public function __invoke(GetTranslationByIdQuery $query): GetTranslationByIdResponse
    {
        $translation = $this->getTranslationById->__invoke(
            Uuid::fromString($query->id())
        );

        return new GetTranslationByIdResponse($translation->toArray());
    }
}
