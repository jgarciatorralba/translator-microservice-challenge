<?php

declare(strict_types=1);

namespace App\Translations\Domain\Service;

use App\Shared\Domain\ValueObject\SearchCriteria\Criteria;
use App\Translations\Domain\Contract\TranslationRepository;
use App\Translations\Domain\Translation;

final class GetTranslations
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
