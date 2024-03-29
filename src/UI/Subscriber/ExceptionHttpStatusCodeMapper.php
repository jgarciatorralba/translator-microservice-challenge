<?php

declare(strict_types=1);

namespace App\UI\Subscriber;

use App\Translations\Domain\Exception\MissingProviderException;
use App\Translations\Domain\Exception\TranslationNotFoundException;
use Symfony\Component\HttpFoundation\Response;

final class ExceptionHttpStatusCodeMapper
{
    private const EXCEPTIONS = [
        TranslationNotFoundException::class => Response::HTTP_NOT_FOUND,
        MissingProviderException::class => Response::HTTP_INTERNAL_SERVER_ERROR
    ];

    public function getStatusCodeFor(string $exceptionClass): ?int
    {
        return self::EXCEPTIONS[$exceptionClass] ?? null;
    }
}
