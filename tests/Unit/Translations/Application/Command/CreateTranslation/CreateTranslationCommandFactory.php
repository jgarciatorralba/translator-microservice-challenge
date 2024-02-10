<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\Application\Command\CreateTranslation;

use App\Translations\Application\Command\CreateTranslation\CreateTranslationCommand;
use App\Translations\Domain\Translation;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use DateTimeImmutable;

final class CreateTranslationCommandFactory
{
    public static function create(
        ?string $id = null,
        ?SupportedLanguageEnum $sourceLanguage = null,
        ?string $originalText = null,
        ?SupportedLanguageEnum $targetLanguage = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null
    ): CreateTranslationCommand {
        return new CreateTranslationCommand(
            id: $id ?? FakeValueGenerator::uuid()->value(),
            sourceLanguage: $sourceLanguage
                ? $sourceLanguage->value
                : FakeValueGenerator::randomElement([
                    null,
                    ...SupportedLanguageEnum::supportedValues()
                ]),
            originalText: $originalText ?? FakeValueGenerator::text(),
            targetLanguage: $targetLanguage
                ? $targetLanguage->value
                : FakeValueGenerator::randomElement(
                    SupportedLanguageEnum::supportedValues()
                ),
            createdAt: $createdAt ?? FakeValueGenerator::dateTime(),
            updatedAt: $updatedAt ?? FakeValueGenerator::dateTime()
        );
    }

    public static function createFromTranslation(Translation $translation): CreateTranslationCommand
    {
        return self::create(
            id: $translation->id()->value(),
            sourceLanguage: $translation->sourceLanguage(),
            originalText: $translation->originalText(),
            targetLanguage: $translation->targetLanguage(),
            createdAt: $translation->createdAt(),
            updatedAt: $translation->updatedAt()
        );
    }
}
