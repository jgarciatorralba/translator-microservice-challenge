<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\Application\EventSubscriber;

use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use App\Translations\Domain\Event\TranslationRequestedEvent;
use DateTimeImmutable;

final class TranslationRequestedEventFactory
{
    public static function create(
        ?string $aggregateId = null,
        ?DateTimeImmutable $occurredOn = null
    ): TranslationRequestedEvent {
        return new TranslationRequestedEvent(
            aggregateId: $aggregateId ?? FakeValueGenerator::uuid()->value(),
            occurredOn: $occurredOn ?? FakeValueGenerator::dateTime()
        );
    }
}
