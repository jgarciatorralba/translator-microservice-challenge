<?php

declare(strict_types=1);

namespace App\Translations\Domain\Service;

use App\Translations\Domain\Contract\TranslationRepository;
use App\Translations\Domain\Translation;

final class GetTranslations
{
    public function __construct(
        private readonly TranslationRepository $translationRepository
    ) {
    }

    /**
     * @param array <string, mixed> $criteria
     * @param array <string, string>|null $orderBy
     * @return Translation[]
     * */
    public function __invoke(
        array $criteria,
        ?array $orderBy,
        ?int $limit,
        ?int $offset
    ): array {
        return $this->translationRepository->findByCriteria(
            $criteria,
            $orderBy,
            $limit,
            $offset
        );
    }
}
