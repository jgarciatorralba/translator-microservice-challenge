<?php

declare(strict_types=1);

namespace App\Translations\Domain\Service;

use App\Translations\Domain\Contract\TranslationRepository;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\StatusEnum;
use App\Translations\Domain\ValueObject\LanguageEnum;
use DateTimeImmutable;

final class UpdateTranslation
{
    public function __construct(
        private readonly TranslationRepository $translationRepository
    ) {
    }

    /**
     * @param array <string, string|StatusEnum|LanguageEnum|DateTimeImmutable> $updatedData
     */
    public function __invoke(Translation $translation, array $updatedData): void
    {
        $hasChanged = false;

        if (
            !empty($updatedData['status'])
            && $updatedData['status'] !== $translation->status()
        ) {
            $translation->updateStatus($updatedData['status']);
            $hasChanged = true;
        }
        if (
            !empty($updatedData['translatedText'])
            && $updatedData['translatedText'] !== $translation->translatedText()
        ) {
            $translation->updateTranslatedText($updatedData['translatedText']);
            $hasChanged = true;
        }
        if (
            !empty($updatedData['sourceLanguage'])
            && empty($translation->sourceLanguage())
        ) {
            $translation->updateSourceLanguage($updatedData['sourceLanguage']);
            $hasChanged = true;
        }

        if ($hasChanged) {
            $translation->updateUpdatedAt($updatedData['updatedAt']);
            $this->translationRepository->update($translation);
        }
    }
}
