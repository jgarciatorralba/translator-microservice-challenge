<?php

declare(strict_types=1);

namespace App\Translations\Domain\ValueObject;

use App\Shared\Domain\Trait\EnumValuesProviderTrait;

enum ProcessingStatusEnum: string
{
    use EnumValuesProviderTrait;

    case QUEUED = 'queued';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case ERROR = 'error';
}
