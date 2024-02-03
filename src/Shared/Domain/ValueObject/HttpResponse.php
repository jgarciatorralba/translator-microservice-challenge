<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Symfony\Component\HttpFoundation\Response;

class HttpResponse
{
    public function __construct(
        private readonly ?int $statusCode = null,
        private readonly ?string $error = null,
        private readonly ?string $content = null,
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

    public function isFromBadRequest(): bool
    {
        return $this->statusCode === Response::HTTP_BAD_REQUEST;
    }
}
