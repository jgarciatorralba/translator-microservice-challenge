<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Log\Monolog;

use Psr\Log\LoggerInterface;

final class TranslationLogger extends AbstractLogger
{
    public function __construct(LoggerInterface $translationLogger)
    {
        parent::__construct($translationLogger);
    }
}
