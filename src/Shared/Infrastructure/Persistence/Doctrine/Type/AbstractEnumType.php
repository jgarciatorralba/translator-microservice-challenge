<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

abstract class AbstractEnumType extends Type
{
    protected string $name;

    /** @var array<mixed> $values */
    protected array $values = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $this->getName();
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if (isset($value->value) && !in_array($value->value, $this->values)) {
            throw new InvalidArgumentException("Invalid '" . $this->name . "' value.");
        }

        if (is_null($value) || is_scalar($value)) {
            return $value;
        }

        return $value->value;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
