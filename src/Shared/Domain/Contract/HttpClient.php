<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contract;

use App\Shared\Domain\ValueObject\HttpResponse;

interface HttpClient
{
    /** @param array<string, string|array<string, string>> $httpOptions */
    public function submit(string $url, array $httpOptions): HttpResponse;
}
