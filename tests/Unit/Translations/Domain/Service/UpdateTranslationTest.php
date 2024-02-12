<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\Domain\Service;

use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use App\Tests\Unit\Translations\Domain\TranslationFactory;
use App\Tests\Unit\Translations\TestCase\TranslationRepositoryMock;
use App\Translations\Domain\Service\UpdateTranslation;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\LanguageEnum;
use PHPUnit\Framework\TestCase;

final class UpdateTranslationTest extends TestCase
{
    private ?TranslationRepositoryMock $translationRepository;

    protected function setUp(): void
    {
        $this->translationRepository = new TranslationRepositoryMock($this);
    }

    protected function tearDown(): void
    {
        $this->translationRepository = null;
    }

    public function testUpdateTranslation(): void
    {
        $translation = TranslationFactory::create();

        $this->translationRepository->shouldUpdateTranslation($translation);

        $service = new UpdateTranslation(
            translationRepository: $this->translationRepository->getMock()
        );
        $result = $service->__invoke($translation, [
            'translatedText' => FakeValueGenerator::text(),
            'sourceLanguage' => LanguageEnum::from(
                FakeValueGenerator::randomElement(
                    LanguageEnum::supportedValues()
                )
            ),
            'updatedAt' => FakeValueGenerator::dateTime()
        ]);

        $this->assertNull($result);
    }
}
