<?php

declare(strict_types=1);

namespace App\Translations\Domain\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class TranslationRequestedEvent extends DomainEvent
{
    public static function eventType(): string
    {
        return 'translations.domain.translation_requested';
    }
}
