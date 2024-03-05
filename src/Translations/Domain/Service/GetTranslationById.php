<?php

declare(strict_types=1);

namespace App\Translations\Domain\Service;

use App\Shared\Domain\ValueObject\Uuid;
use App\Translations\Domain\Contract\TranslationRepository;
use App\Translations\Domain\Exception\TranslationNotFoundException;
use App\Translations\Domain\Translation;

final class GetTranslationById
{
    public function __construct(
        private readonly TranslationRepository $translationRepository
    ) {
    }

    public function __invoke(Uuid $id): Translation
    {
        $translation = $this->translationRepository->findById($id);
        if ($translation === null) {
            throw new TranslationNotFoundException($id);
        }

        return $translation;
    }
}
