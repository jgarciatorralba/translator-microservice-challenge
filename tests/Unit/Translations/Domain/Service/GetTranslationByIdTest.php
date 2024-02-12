<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\Domain\Service;

use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Translations\Domain\TranslationFactory;
use App\Tests\Unit\Translations\TestCase\TranslationRepositoryMock;
use App\Translations\Domain\Exception\TranslationNotFoundException;
use App\Translations\Domain\Service\GetTranslationById;
use PHPUnit\Framework\TestCase;

final class GetTranslationByIdTest extends TestCase
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

    public function testReturnTranslation(): void
    {
        $translation = TranslationFactory::create();

        $this->translationRepository->shouldFindTranslationById($translation->id(), $translation);

        $service = new GetTranslationById(
            translationRepository: $this->translationRepository->getMock()
        );
        $result = $service->__invoke($translation->id());

        $this->assertEquals($result, $translation);
    }

    public function testThrowTranslationNotFoundException(): void
    {
        $id = Uuid::random();

        $this->translationRepository->shouldNotFindTranslationById($id);

        $service = new GetTranslationById(
            translationRepository: $this->translationRepository->getMock()
        );

        $this->expectException(TranslationNotFoundException::class);
        $service->__invoke($id);
    }
}
