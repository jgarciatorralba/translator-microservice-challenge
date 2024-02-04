<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Log;

use App\Shared\Infrastructure\Log\Monolog\AbstractLogger;
use Psr\Log\LoggerInterface;

final class TranslationLogger extends AbstractLogger
{
    public function __construct(LoggerInterface $translationLogger)
    {
        parent::__construct($translationLogger);
    }
}
