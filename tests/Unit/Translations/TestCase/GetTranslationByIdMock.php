<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\TestCase;

use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use App\Translations\Domain\Exception\TranslationNotFoundException;
use App\Translations\Domain\Translation;
use App\Translations\Domain\Service\GetTranslationById;

final class GetTranslationByIdMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return GetTranslationById::class;
    }

    public function shouldReturnTranslation(Uuid $id, Translation $translation): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($id)
            ->willReturn($translation);
    }

    public function shouldThrowException(Uuid $id): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($id)
            ->willThrowException(new TranslationNotFoundException($id));
    }
}
