<?php

declare(strict_types=1);

namespace App\Tests\Unit\Translations\Application\EventSubscriber;

use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use App\Translations\Domain\Event\TranslationRequestedEvent;
use App\Translations\Domain\Translation;

final class TranslationRequestedEventFactory
{
    public static function create(
        ?string $aggregateId = null
    ): TranslationRequestedEvent {
        return new TranslationRequestedEvent(
            aggregateId: $aggregateId ?? FakeValueGenerator::uuid()->value()
        );
    }

    public static function createFromTranslation(
        Translation $translation
    ): TranslationRequestedEvent {
        return self::create($translation->id()->value());
    }
}
