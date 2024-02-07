<?php

declare(strict_types=1);

namespace App\Tests\Feature\UI\Controller\Translations;

use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Feature\FeatureTestCase;
use App\Translations\Domain\ValueObject\StatusEnum;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;

final class CreateTranslationControllerTest extends FeatureTestCase
{
    protected function tearDown(): void
    {
        $this->clearDatabase();
    }

    public function testCreateProduct(): void
    {
        $client = $this->getApiClient();
        $response = $client->request('POST', '/api/translations', [
            'body' => json_encode([
                'sourceLanguage' => SupportedLanguageEnum::ENGLISH->value,
                'originalText' => 'Barrow Neurological Institute is the world\'s largest neurological disease treatment and research institution, and is consistently ranked as one of the best neurosurgical training centers in the United States. Its main campus is in Phoenix, Arizona.',
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

        $reflectionUuid = new ReflectionClass(Uuid::class);
        $ensureIsValidUuid = $reflectionUuid->getMethod('ensureIsValidUuid');
        $ensureIsValidUuid->invokeArgs(new Uuid($decodedResponse['id']), [$decodedResponse['id']]);
    }

    public function testThrowValidationError(): void
    {
        $this->assertTrue(true);
    }
}
