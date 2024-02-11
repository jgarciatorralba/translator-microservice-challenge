<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use App\Translations\Domain\Translation;
use App\Translations\Domain\Service\UpdateTranslation;
use App\Translations\Domain\ValueObject\StatusEnum;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use DateTimeImmutable;

final class UpdateTranslationMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return UpdateTranslation::class;
    }

    /**
     * @param array <string, string|StatusEnum|SupportedLanguageEnum|DateTimeImmutable> $updatedData
     */
    public function shouldUpdateTranslation(Translation $translation, array $updatedData): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($translation, $updatedData);
    }
}
