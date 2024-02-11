<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Event;

use App\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;

abstract class DomainEvent
{
    private readonly string $eventId;
    private readonly DateTimeImmutable $occurredOn;

    public function __construct(
        private readonly string $aggregateId,
        string $eventId = null,
        DateTimeImmutable $occurredOn = null
    ) {
        $this->eventId = $eventId ?: Uuid::random()->value();
        $this->occurredOn = $occurredOn ?: new DateTimeImmutable();
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    /**
     * @return array<string, string|DateTimeImmutable|array<string, string>>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->eventId,
            'eventType' => static::eventType(),
            'occurredOn' => $this->occurredOn,
            'attributes' => array_merge(
                $this->toPrimitives(),
                ['id' => $this->aggregateId]
            )
        ];
    }

    /**
     * @return array<empty>
     */
    protected function toPrimitives(): array
    {
        return [];
    }

    abstract public static function eventType(): string;
}
