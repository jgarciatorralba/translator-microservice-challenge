<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use App\Tests\Unit\Translations\Domain\ValueObject\TranslationProviderResponseFactory;
use App\Translations\Domain\Translation;
use PHPUnit\Framework\TestCase;

final class TranslationProviderMock extends AbstractMock
{
    /** @param class-string $className */
    public function __construct(
        TestCase $testCase,
        private readonly string $className
    ) {
        parent::__construct($testCase);
    }

    protected function getClassName(): string
    {
        return $this->className;
    }

    public function shouldReturnSuccessfullResponse(Translation $translation): void
    {
        $this->mock
            ->expects($this->once())
            ->method('translate')
            ->with($translation)
            ->willReturn(
                TranslationProviderResponseFactory::createSuccessfull()
            );
    }

    public function shouldReturnErrorResponse(Translation $translation): void
    {
        $this->mock
            ->expects($this->once())
            ->method('translate')
            ->with($translation)
            ->willReturn(
                TranslationProviderResponseFactory::createFromBadRequest()
            );
    }

    public function shouldNotHaveBeenCalled(Translation $translation): void
    {
        $this->mock
            ->expects($this->never())
            ->method('translate')
            ->with($translation);
    }
}
