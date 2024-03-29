<?php

declare(strict_types=1);

namespace App\Translations\Domain\Service;

use App\Shared\Domain\Criteria\Criteria;
use App\Translations\Domain\Contract\TranslationRepository;
use App\Translations\Domain\Translation;

final class GetTranslationsByCriteria
{
    public function __construct(
        private readonly TranslationRepository $translationRepository
    ) {
    }

    /**
     * @param Criteria $criteria
     * @return Translation[]
     * */
    public function __invoke(Criteria $criteria): array
    {
        return $this->translationRepository->matching($criteria);
    }
}
