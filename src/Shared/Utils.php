<?php

declare(strict_types=1);

namespace App\Shared;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;

final class Utils
{
    public static function dateToString(DateTimeInterface $date): string
    {
        return $date->format(DateTimeInterface::ATOM);
    }

    public static function stringToDate(string $date): DateTimeImmutable
    {
        try {
            return new DateTimeImmutable($date);
        } catch (Exception) {
            return new DateTimeImmutable();
        }
    }

    public static function extractClassName(string $className): string
    {
        $array = explode('\\', $className);
        return array_pop($array);
    }

    public static function toSnakeCase(string $value): string
    {
        return strtolower(preg_replace(['/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'], '\1_\2', $value));
    }
}
