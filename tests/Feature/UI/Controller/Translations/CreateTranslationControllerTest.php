<?php

declare(strict_types=1);

namespace App\Tests\Feature\UI\Controller\Translations;

use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Feature\FeatureTestCase;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\StatusEnum;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use App\UI\Exception\ValidationException;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;

final class CreateTranslationControllerTest extends FeatureTestCase
{
    /**
     * @throws InvalidArgumentException
     */
    public function testCreateTranslation(): void
    {
        $this->client->request('POST', 'api/translations', content: json_encode([
            'sourceLanguage' => SupportedLanguageEnum::ENGLISH->value,
            'originalText' => "The target of an HTTP request is called a 'resource', which nature isn't defined further; it can be a document, a photo, or anything else. Each resource is identified by a Uniform Resource Identifier (URI) used throughout HTTP for identifying resources.",
            'targetLanguage' => SupportedLanguageEnum::SPANISH->value
        ]));

        $response = $this->client->getResponse();
        $this->assertEquals($response->getStatusCode(), Response::HTTP_CREATED);
        $this->assertIsString($response->getContent());

        $decodedResponse = json_decode($response->getContent(), true);
        $this->assertIsArray($decodedResponse);
        $this->assertArrayHasKey('status', $decodedResponse);
        $this->assertEquals($decodedResponse['status'], StatusEnum::QUEUED->value);
        $this->assertArrayHasKey('id', $decodedResponse);

        new Uuid($decodedResponse['id']);

        $translation = $this->find(Translation::class, $decodedResponse['id']);
        if ($translation) {
            $this->remove($translation);
        }
    }

    #[DataProvider('dataValidation')]
    public function testThrowValidationException(
        ?string $sourceLanguageValue,
        ?string $originalText,
        ?string $targetLanguageValue
    ): void {
        $this->client->catchExceptions(false);
        $this->expectException(ValidationException::class);

        $this->client->request('POST', '/api/translations', content: json_encode([
            'sourceLanguage' => $sourceLanguageValue,
            'originalText' => $originalText,
            'targetLanguage' => $targetLanguageValue
        ]));
    }

    #[DataProvider('dataValidation')]
    public function testReturnValidationError(
        ?string $sourceLanguageValue,
        ?string $originalText,
        ?string $targetLanguageValue
    ): void {
        $this->client->request('POST', '/api/translations', content: json_encode([
            'sourceLanguage' => $sourceLanguageValue,
            'originalText' => $originalText,
            'targetLanguage' => $targetLanguageValue
        ]));

        $response = $this->client->getResponse();
        $this->assertEquals($response->getStatusCode(), Response::HTTP_BAD_REQUEST);

        $decodedResponse = json_decode($response->getContent(), true);
        $this->assertIsArray($decodedResponse);
        $this->assertArrayHasKey('code', $decodedResponse);
        $this->assertEquals('validation_exception', $decodedResponse['code']);
        $this->assertArrayHasKey('errorMessage', $decodedResponse);
        $this->assertArrayHasKey('errors', $decodedResponse);
    }

    /**
     * @return array<string, array<string|null>>
     */
    public static function dataValidation(): array
    {
        $tooLongText = '';
        for ($i = 0; $i < 5; $i++) {
            $tooLongText .= FakeValueGenerator::text();
        }

        return [
            'blank source language' => [
                '',
                FakeValueGenerator::text(),
                SupportedLanguageEnum::SPANISH->value
            ],
            'missing original text' => [
                SupportedLanguageEnum::ENGLISH->value,
                null,
                SupportedLanguageEnum::PORTUGUESE->value
            ],
            'original text too long' => [
                SupportedLanguageEnum::FRENCH->value,
                $tooLongText,
                SupportedLanguageEnum::ITALIAN->value
            ],
            'invalid target language' => [
                null,
                FakeValueGenerator::text(),
                FakeValueGenerator::string()
            ]
        ];
    }
}
