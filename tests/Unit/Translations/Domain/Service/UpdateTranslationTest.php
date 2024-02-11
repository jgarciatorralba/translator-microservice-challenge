<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\Domain\Service;

use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use App\Tests\Unit\Translations\Domain\TranslationFactory;
use App\Tests\Unit\Translations\TestCase\TranslationRepositoryMock;
use App\Translations\Domain\Service\UpdateTranslation;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use PHPUnit\Framework\TestCase;

final class UpdateTranslationTest extends TestCase
{
    private ?TranslationRepositoryMock $translationRepositoryMock;

    protected function setUp(): void
    {
        $this->translationRepositoryMock = new TranslationRepositoryMock($this);
    }

    protected function tearDown(): void
    {
        $this->translationRepositoryMock = null;
    }

    public function testUpdateTranslation(): void
    {
        $translation = TranslationFactory::create();

        $this->translationRepositoryMock->shouldUpdateTranslation($translation);

        $service = new UpdateTranslation(
            translationRepository: $this->translationRepositoryMock->getMock()
        );
        $result = $service->__invoke($translation, [
            'translatedText' => FakeValueGenerator::text(),
            'sourceLanguage' => SupportedLanguageEnum::from(
                FakeValueGenerator::randomElement(
                    SupportedLanguageEnum::supportedValues()
                )
            ),
            'updatedAt' => FakeValueGenerator::dateTime()
        ]);

        $this->assertNull($result);
    }
}
