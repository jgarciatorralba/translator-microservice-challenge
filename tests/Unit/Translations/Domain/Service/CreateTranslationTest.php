<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\Domain\Service;

use App\Tests\Unit\Translations\Domain\TranslationFactory;
use App\Tests\Unit\Translations\TestCase\TranslationRepositoryMock;
use App\Translations\Domain\Service\CreateTranslation;
use PHPUnit\Framework\TestCase;

final class CreateTranslationTest extends TestCase
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

    public function testCreateTranslation(): void
    {
        $translation = TranslationFactory::create();

        $this->translationRepository->shouldCreateTranslation($translation);

        $service = new CreateTranslation(
            translationRepository: $this->translationRepository->getMock()
        );
        $result = $service->__invoke($translation);

        $this->assertNull($result);
    }
}
