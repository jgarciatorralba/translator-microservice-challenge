<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\Application\EventSubscriber;

use App\Tests\Unit\Translations\Domain\TranslationFactory;
use App\Tests\Unit\Translations\TestCase\GetTranslationByIdMock;
use App\Tests\Unit\Translations\TestCase\UpdateTranslationMock;
use App\Tests\Unit\Translations\TestCase\RequestExternalTranslationMock;
use App\Translations\Application\EventSubscriber\RequestTranslationSubscriber;
use App\Translations\Domain\ValueObject\StatusEnum;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class RequestTranslationSubscriberTest extends TestCase
{
    private ?GetTranslationByIdMock $getTranslationById;
    private ?UpdateTranslationMock $updateTranslation;
    private ?RequestExternalTranslationMock $requestExternalTranslation;

    protected function setUp(): void
    {
        $this->getTranslationById = new GetTranslationByIdMock($this);
        $this->updateTranslation = new UpdateTranslationMock($this);
        $this->requestExternalTranslation = new RequestExternalTranslationMock($this);
    }

    protected function tearDown(): void
    {
        $this->getTranslationById = null;
        $this->updateTranslation = null;
        $this->requestExternalTranslation = null;
    }

    public function testTranslationRequested(): void
    {
        $translation = TranslationFactory::create();

        $this->getTranslationById->shouldReturnTranslation($translation->id(), $translation);

        $result = $this->requestExternalTranslation->shouldProvideTranslationResponse($translation);

        $this->updateTranslation->shouldUpdateTranslation($translation, [
            'status' => !empty($result->error())
                ? StatusEnum::ERROR
                : StatusEnum::COMPLETED,
            'translatedText' => $result->translatedText(),
            'sourceLanguage' => $result->detectedLanguage(),
            'updatedAt' => $result->translatedAt()
        ]);

        $subscriber = new RequestTranslationSubscriber(
            getTranslationById: $this->getTranslationById->getMock(),
            requestExternalTranslation: $this->requestExternalTranslation->getMock(),
            updateTranslation: $this->updateTranslation->getMock()
        );

        $event = TranslationRequestedEventFactory::createFromTranslation($translation);
        $subscriber->__invoke($event);
    }
}
