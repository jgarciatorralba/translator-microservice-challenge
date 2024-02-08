<?php

declare(strict_types=1);

namespace App\Tests\Feature\UI\Controller\Translations;

use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Feature\FeatureTestCase;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\StatusEnum;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use InvalidArgumentException;
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

        new Uuid($decodedResponse['id']);

        $translation = $this->find(Translation::class, $decodedResponse['id']);
        if ($translation) {
            $this->remove($translation);
        }
    }

    public function testThrowValidationError(): void
    {
        $this->assertTrue(true);
    }
}
