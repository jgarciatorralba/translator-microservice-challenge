<?php

declare(strict_types=1);

namespace App\Translations\Domain\Service;

use App\Translations\Domain\Contract\TranslationRepository;
use App\Translations\Domain\Translation;

final class CreateTranslation
{
    public function __construct(
        private readonly TranslationRepository $translationRepository
    ) {
    }

    public function __invoke(Translation $translation): void
    {
        $this->translationRepository->create($translation);
    }
}
