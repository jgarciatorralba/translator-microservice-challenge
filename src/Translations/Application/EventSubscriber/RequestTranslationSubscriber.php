<?php

declare(strict_types=1);

namespace App\Translations\Application\EventSubscriber;

use App\Shared\Domain\Bus\Event\EventSubscriber;
use App\Shared\Domain\ValueObject\Uuid;
use App\Translations\Domain\Event\TranslationRequestedEvent;
use App\Translations\Domain\Service\GetTranslationById;
use App\Translations\Domain\Service\RequestExternalTranslation;
use App\Translations\Domain\Service\UpdateTranslation;
use App\Translations\Domain\ValueObject\StatusEnum;
use DateTimeImmutable;

final class RequestTranslationSubscriber implements EventSubscriber
{
    public function __construct(
        private readonly GetTranslationById $getTranslationById,
        private readonly UpdateTranslation $updateTranslation,
        private readonly RequestExternalTranslation $requestExternalTranslation
    ) {
    }

    public function __invoke(TranslationRequestedEvent $event): void
    {
        $translation = $this->getTranslationById->__invoke(
            Uuid::fromString($event->aggregateId())
        );

        $result = $this->requestExternalTranslation->__invoke($translation);

        $this->updateTranslation->__invoke($translation, [
            'status' => !empty($result->error())
                ? StatusEnum::ERROR
                : StatusEnum::COMPLETED,
            'translatedText' => $result->translatedText(),
            'sourceLanguage' => $result->detectedLanguage(),
            'updatedAt' => $event->occurredOn()
        ]);
    }
}
