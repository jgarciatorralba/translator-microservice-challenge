<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\Domain;

use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use DateTimeImmutable;

final class TranslationFactory
{
    public static function create(
        Uuid $id = null,
        ?SupportedLanguageEnum $sourceLanguage = null,
        string $originalText = null,
        SupportedLanguageEnum $targetLanguage = null,
        DateTimeImmutable $createdAt = null,
        DateTimeImmutable $updatedAt = null
    ): Translation {
        return Translation::create(
            id: $id ?? FakeValueGenerator::uuid(),
            sourceLanguage: $sourceLanguage ?? SupportedLanguageEnum::tryFrom(
                FakeValueGenerator::randomElement([
                    'null',
                    ...SupportedLanguageEnum::supportedValues()
                ])
            ),
            originalText: $originalText ?? FakeValueGenerator::text(),
            targetLanguage: $targetLanguage ?? SupportedLanguageEnum::from(
                FakeValueGenerator::randomElement(SupportedLanguageEnum::supportedValues())
            ),
            createdAt: $createdAt ?? FakeValueGenerator::dateTime(),
            updatedAt: $updatedAt ?? FakeValueGenerator::dateTime()
        );
    }
}
