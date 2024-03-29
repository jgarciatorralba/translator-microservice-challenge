<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\TestCase;

use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use App\Translations\Domain\Contract\TranslationRepository;
use App\Translations\Domain\Translation;

final class TranslationRepositoryMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return TranslationRepository::class;
    }

    public function shouldFindTranslationById(Uuid $id, Translation $translation): void
    {
        $this->mock
            ->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($translation);
    }

    public function shouldNotFindTranslationById(Uuid $id): void
    {
        $this->mock
            ->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn(null);
    }

    public function shouldCreateTranslation(Translation $translation): void
    {
        $this->mock
            ->expects($this->once())
            ->method('create')
            ->with($translation);
    }

    public function shouldUpdateTranslation(Translation $translation): void
    {
        $this->mock
            ->expects($this->once())
            ->method('update')
            ->with($translation);
    }
}
