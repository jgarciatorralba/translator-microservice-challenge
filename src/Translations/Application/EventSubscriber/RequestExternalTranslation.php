<?php

declare(strict_types=1);

namespace App\Translations\Application\EventSubscriber;

use App\Shared\Domain\Bus\Event\EventSubscriber;
use App\Shared\Domain\ValueObject\Uuid;
use App\Translations\Domain\Event\TranslationRequestedEvent;
use App\Translations\Domain\Service\GetTranslationById;
use App\Translations\Domain\Service\UpdateTranslation;
use App\Translations\Domain\ValueObject\StatusEnum;
use DateTimeImmutable;

final class RequestExternalTranslation implements EventSubscriber
{
    public function __construct(
        private readonly GetTranslationById $getTranslationById,
        private readonly UpdateTranslation $updateTranslation
    ) {
    }

    public function __invoke(TranslationRequestedEvent $event): void
    {
        $translation = $this->getTranslationById->__invoke(
            Uuid::fromString($event->aggregateId())
        );

        sleep(10);

        $this->updateTranslation->__invoke($translation, [
            'status' => StatusEnum::COMPLETED,
            'updatedAt' => new DateTimeImmutable()
        ]);
    }
}
