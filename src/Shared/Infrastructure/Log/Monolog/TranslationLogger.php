<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Log\Monolog;

use App\Shared\Domain\Contract\Logger as LoggerContract;
use Psr\Log\LoggerInterface;

final class TranslationLogger implements LoggerContract
{
    public function __construct(
        private readonly LoggerInterface $translationLogger
    ) {
    }

    public function info(string $message, array $context = []): void
    {
        $this->translationLogger->info($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->translationLogger->warning($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->translationLogger->error($message, $context);
    }
}
