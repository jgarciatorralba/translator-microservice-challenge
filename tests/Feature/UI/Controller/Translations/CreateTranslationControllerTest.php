<?php

declare(strict_types=1);

namespace App\Tests\Feature\UI\Controller\Translations;

use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Feature\FeatureTestCase;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\StatusEnum;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use InvalidArgumentException;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

final class CreateTranslationControllerTest extends FeatureTestCase
{
    /**
     * @throws InvalidArgumentException
     */
    public function testCreateProduct(): void
    {
        $client = $this->getApiClient();
        $response = $client->request('POST', '/api/translations', [
            'body' => json_encode([
                'sourceLanguage' => SupportedLanguageEnum::ENGLISH->value,
                'originalText' => "The target of an HTTP request is called a 'resource', which nature isn't defined further; it can be a document, a photo, or anything else. Each resource is identified by a Uniform Resource Identifier (URI) used throughout HTTP for identifying resources.",
                'targetLanguage' => SupportedLanguageEnum::SPANISH->value
            ])
        ]);

        $this->assertEquals($response->getStatusCode(), Response::HTTP_CREATED);
        $this->assertIsString($response->getContent());

        $decodedResponse = $response->toArray();
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

    /**
     * @dataProvider dataThrowValidationException
     */
    public function testThrowValidationException(
        ?string $sourceLanguageValue,
        ?string $originalText,
        ?string $targetLanguageValue
    ): void {
        $client = $this->getApiClient();

        $response = $client->request('POST', '/api/translations', [
            'body' => json_encode([
                'sourceLanguage' => $sourceLanguageValue,
                'originalText' => $originalText,
                'targetLanguage' => $targetLanguageValue
            ])
        ]);

        $this->assertEquals($response->getStatusCode(), Response::HTTP_BAD_REQUEST);

        $this->expectException(ClientException::class);
        $response->getContent();
    }

    /**
     * @return array<string, array<string|null>>
     */
    public static function dataThrowValidationException(): array
    {
        $tooLongText = '';
        for ($i = 0; $i < 5; $i++) {
            $tooLongText .= FakeValueGenerator::text();
        }

        return [
            'blank source language' => [
                '',
                FakeValueGenerator::string(),
                SupportedLanguageEnum::SPANISH->value
            ],
            'missing original text' => [
                SupportedLanguageEnum::ENGLISH->value,
                null,
                SupportedLanguageEnum::PORTUGUESE->value
            ],
            'text too long' => [
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
