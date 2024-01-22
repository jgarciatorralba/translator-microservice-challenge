<?php

declare(strict_types=1);

namespace App\UI\Subscriber;

final class ExceptionHttpStatusCodeMapper
{
    private const EXCEPTIONS = [
        // Translations

    ];

    public function getStatusCodeFor(string $exceptionClass): ?int
    {
        return self::EXCEPTIONS[$exceptionClass] ?? null;
    }
}
