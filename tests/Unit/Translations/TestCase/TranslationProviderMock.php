<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\TestCase;

use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use App\Translations\Domain\ValueObject\TranslationProviderResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

final class TranslationProviderMock extends AbstractMock
{
    /**
     * @param class-string $className
     */
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
        $response = new TranslationProviderResponse(
            statusCode: Response::HTTP_OK,
            detectedLanguage: SupportedLanguageEnum::from(
                FakeValueGenerator::randomElement(
                    SupportedLanguageEnum::supportedValues()
                )
            ),
            content: FakeValueGenerator::text(),
            translatedText: FakeValueGenerator::text()
        );

        $this->mock
            ->expects($this->once())
            ->method('translate')
            ->with($translation)
            ->willReturn($response);
    }

    public function shouldReturnErrorResponse(Translation $translation): void
    {
        $response = new TranslationProviderResponse(
            statusCode: Response::HTTP_BAD_REQUEST,
            error: FakeValueGenerator::text()
        );

        $this->mock
            ->expects($this->once())
            ->method('translate')
            ->with($translation)
            ->willReturn($response);
    }

    public function shouldNotHaveBeenCalled(Translation $translation): void
    {
        $this->mock
            ->expects($this->never())
            ->method('translate')
            ->with($translation);
    }
}
