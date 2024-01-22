<?php

declare(strict_types=1);

namespace App\Translations\Application\Command\CreateTranslation;

use App\Translations\Domain\Translation;
use App\Translations\Domain\Service\CreateTranslation;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\ValueObject\Uuid;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;

final class CreateTranslationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CreateTranslation $createTranslation
    ) {
    }

    public function __invoke(CreateTranslationCommand $command): void
    {
        $translation = Translation::create(
            id: Uuid::fromString($command->id()),
            sourceLanguage: SupportedLanguageEnum::from($command->sourceLanguage()),
            originalText: $command->originalText(),
            targetLanguage: SupportedLanguageEnum::from($command->targetLanguage()),
            createdAt: $command->createdAt(),
            updatedAt: $command->updatedAt()
        );

        $this->createTranslation->__invoke($translation);
    }
}
