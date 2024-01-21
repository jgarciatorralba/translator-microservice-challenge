<?php

declare(strict_types=1);

namespace App\Translations\Domain\ValueObject;

enum ProcessingStatusEnum: string
{
    case QUEUED = 'queued';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case ERROR = 'error';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
