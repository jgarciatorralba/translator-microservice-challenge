<?php

declare(strict_types=1);

namespace App\Translations\Application\Command\CreateTranslation;

use App\Shared\Domain\Bus\Command\Command;
use DateTimeImmutable;

final class CreateTranslationCommand implements Command
{
    public function __construct(
        private readonly string $id,
        private readonly ?string $sourceLanguage,
        private readonly string $originalText,
        private readonly string $targetLanguage,
        private readonly DateTimeImmutable $createdAt,
        private readonly DateTimeImmutable $updatedAt
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function sourceLanguage(): ?string
    {
        return $this->sourceLanguage;
    }

    public function originalText(): string
    {
        return $this->originalText;
    }

    public function targetLanguage(): string
    {
        return $this->targetLanguage;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
