<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractEnumType;

class ProcessingStatusType extends AbstractEnumType
{
    protected string $name = 'processing_status';
    /** @var string[] $values */
    protected array $values = ['queued', 'processing', 'completed', 'error'];
}
