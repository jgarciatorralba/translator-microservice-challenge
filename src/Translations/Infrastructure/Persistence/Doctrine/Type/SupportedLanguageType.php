<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractEnumType;

class SupportedLanguageType extends AbstractEnumType
{
    protected string $name = 'supported_language';
    /** @var string[] $values */
    protected array $values = ['en', 'es', 'fr', 'de', 'pt'];
}
