<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\Domain\Service;

use App\Tests\Unit\Translations\Domain\TranslationFactory;
use App\Tests\Unit\Translations\TestCase\TranslationProviderMock;
use App\Translations\Domain\Exception\MissingProviderException;
use App\Translations\Domain\Service\RequestExternalTranslation;
use App\Translations\Domain\ValueObject\LanguageEnum;
use App\Translations\Infrastructure\Http\DeepL\DeepLTranslationProvider;
use App\Translations\Infrastructure\Http\LectoAI\LectoAITranslationProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

final class RequestExternalTranslationTest extends TestCase
{
    private ?TranslationProviderMock $primaryProvider;
    private ?TranslationProviderMock $fallbackProvider;

    protected function setUp(): void
    {
        $this->primaryProvider = new TranslationProviderMock(
            testCase: $this,
            className: DeepLTranslationProvider::class
        );
        $this->fallbackProvider = new TranslationProviderMock(
            testCase: $this,
            className: LectoAITranslationProvider::class
        );
    }

    protected function tearDown(): void
    {
        $this->primaryProvider = null;
        $this->fallbackProvider = null;
    }

    public function testThrowMissingProviderException(): void
    {
        $translation = TranslationFactory::create();

        $this->primaryProvider->shouldNotHaveBeenCalled($translation);
        $this->fallbackProvider->shouldNotHaveBeenCalled($translation);

        $this->expectException(MissingProviderException::class);
        $service = new RequestExternalTranslation(
            primaryTranslationProvider: $this->primaryProvider->getMock(),
            translationProviders: [$this->primaryProvider->getMock()]
        );

        $service->__invoke($translation);
    }

    public function testReturnSuccessfullResponse(): void
    {
        $translation = TranslationFactory::create();

        $this->primaryProvider->shouldReturnSuccessfullResponse($translation);
        $this->fallbackProvider->shouldNotHaveBeenCalled($translation);

        $service = new RequestExternalTranslation(
            primaryTranslationProvider: $this->primaryProvider->getMock(),
            translationProviders: [
                $this->primaryProvider->getMock(),
                $this->fallbackProvider->getMock()
            ]
        );

        $result = $service->__invoke($translation);

        $this->assertEquals(Response::HTTP_OK, $result->statusCode());
        $this->assertContains(
            $result->detectedLanguage()->value,
            LanguageEnum::supportedValues()
        );
        $this->assertIsString($result->content());
        $this->assertIsString($result->translatedText());
        $this->assertEmpty($result->error());
    }

    public function testReturnErrorResponse(): void
    {
        $translation = TranslationFactory::create();

        $this->primaryProvider->shouldReturnErrorResponse($translation);
        $this->fallbackProvider->shouldNotHaveBeenCalled($translation);

        $service = new RequestExternalTranslation(
            primaryTranslationProvider: $this->primaryProvider->getMock(),
            translationProviders: [
                $this->primaryProvider->getMock(),
                $this->fallbackProvider->getMock()
            ]
        );

        $result = $service->__invoke($translation);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->statusCode());
        $this->assertEmpty($result->content());
        $this->assertEmpty($result->translatedText());
        $this->assertIsString($result->error());
    }
}
