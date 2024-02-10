<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use App\Translations\Domain\Translation;
use App\Translations\Domain\Service\CreateTranslation;

final class CreateTranslationMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return CreateTranslation::class;
    }

    public function shouldCreateTranslation(Translation $translation): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($translation);
    }
}
