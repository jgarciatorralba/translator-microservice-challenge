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

    /** @return Translation[] */
    public function __invoke(): array
    {
        return $this->translationRepository->findAll();
    }
}
