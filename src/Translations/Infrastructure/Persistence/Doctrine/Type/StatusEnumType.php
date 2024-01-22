<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractEnumType;

class StatusEnumType extends AbstractEnumType
{
    protected string $name = 'status_enum';
    /** @var string[] $values */
    protected array $values = ['queued', 'processing', 'completed', 'error'];
}
