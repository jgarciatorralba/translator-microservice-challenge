<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\Domain;

use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\LanguageEnum;
use DateTimeImmutable;

final class TranslationFactory
{
    public static function create(
        Uuid $id = null,
        ?LanguageEnum $sourceLanguage = null,
        string $originalText = null,
        LanguageEnum $targetLanguage = null,
        DateTimeImmutable $createdAt = null,
        DateTimeImmutable $updatedAt = null
    ): Translation {
        return Translation::create(
            id: $id ?? FakeValueGenerator::uuid(),
            sourceLanguage: $sourceLanguage ?? LanguageEnum::tryFrom(
                FakeValueGenerator::randomElement([
                    'null',
                    ...LanguageEnum::supportedValues()
                ])
            ),
            originalText: $originalText ?? FakeValueGenerator::text(),
            targetLanguage: $targetLanguage ?? LanguageEnum::from(
                FakeValueGenerator::randomElement(LanguageEnum::supportedValues())
            ),
            createdAt: $createdAt ?? FakeValueGenerator::dateTime(),
            updatedAt: $updatedAt ?? FakeValueGenerator::dateTime()
        );
    }
}
