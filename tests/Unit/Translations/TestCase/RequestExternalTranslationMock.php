<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use App\Tests\Unit\Translations\Domain\ValueObject\TranslationProviderResponseFactory;
use App\Translations\Domain\Service\RequestExternalTranslation;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\TranslationProviderResponse;

final class RequestExternalTranslationMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return RequestExternalTranslation::class;
    }

    public function shouldProvideTranslationResponse(Translation $translation): TranslationProviderResponse
    {
        $response = TranslationProviderResponseFactory::createRandom();

        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($translation)
            ->willReturn($response);

        return $response;
    }
}
