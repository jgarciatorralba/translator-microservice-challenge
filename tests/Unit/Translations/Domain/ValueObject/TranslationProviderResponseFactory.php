<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\Domain\ValueObject;

use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use App\Translations\Domain\ValueObject\LanguageEnum;
use App\Translations\Domain\ValueObject\TranslationProviderResponse;
use Symfony\Component\HttpFoundation\Response;

final class TranslationProviderResponseFactory
{
    public static function createSuccessfull(
        ?string $content = null,
        ?string $translatedText = null,
        ?LanguageEnum $detectedLanguage = null
    ): TranslationProviderResponse {
        return new TranslationProviderResponse(
            statusCode: Response::HTTP_OK,
            error: null,
            content: $content ?? FakeValueGenerator::text(),
            translatedText: $translatedText ?? FakeValueGenerator::text(),
            detectedLanguage: $detectedLanguage ?? LanguageEnum::from(
                FakeValueGenerator::randomElement(
                    LanguageEnum::supportedValues()
                )
            )
        );
    }

    public static function createFromBadRequest(
        ?string $error = null
    ): TranslationProviderResponse {
        return new TranslationProviderResponse(
            statusCode: Response::HTTP_BAD_REQUEST,
            error: $error ?? FakeValueGenerator::text(),
            content: null,
            translatedText: null,
            detectedLanguage: null
        );
    }

    public static function createRandom(): TranslationProviderResponse
    {
        return FakeValueGenerator::randomElement([
            self::createSuccessfull(),
            self::createFromBadRequest()
        ]);
    }
}
