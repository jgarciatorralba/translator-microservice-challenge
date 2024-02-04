<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Log;

use App\Shared\Infrastructure\Log\Monolog\MonologLogger;
use Psr\Log\LoggerInterface;

final class TranslationLogger extends MonologLogger
{
    public function __construct(LoggerInterface $translationLogger)
    {
        parent::__construct($translationLogger);
    }
}
