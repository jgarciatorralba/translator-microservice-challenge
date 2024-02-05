<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractEnumType;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class SupportedLanguageEnumType extends AbstractEnumType
{
    protected string $name = 'supported_language_enum';

    /** @var string[] $values */
    protected array $values = ['en', 'es', 'fr', 'de', 'pt', 'it', 'not_recognized'];

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value ? SupportedLanguageEnum::from($value) : null;
    }
}
