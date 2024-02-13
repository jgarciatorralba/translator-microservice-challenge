<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;

class HttpResponse
{
    public function __construct(
        private readonly ?int $statusCode = null,
        private readonly ?string $error = null,
        private readonly ?string $content = null,
        private readonly ?DateTimeImmutable $timestamp = new DateTimeImmutable(),
    ) {
    }

    public function statusCode(): ?int
    {
        return $this->statusCode;
    }

    public function error(): ?string
    {
        return $this->error;
    }

    public function content(): ?string
    {
        return $this->content;
    }

    public function timestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function isFromBadRequest(): bool
    {
        return $this->statusCode === Response::HTTP_BAD_REQUEST;
    }
}
