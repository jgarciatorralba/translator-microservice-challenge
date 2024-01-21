<?php

declare(strict_types=1);

namespace App\Translations\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Trait\TimestampableTrait;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Utils;
use DateTimeImmutable;

class Translation extends AggregateRoot
{
    use TimestampableTrait;

    public function __construct(
        private Uuid $id,
        private string $sourceLanguage,
        private string $originalText,
        private string $targetLanguage,
        private ?string $translatedText,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ) {
        $this->updateCreatedAt($createdAt);
        $this->updateUpdatedAt($updatedAt);
    }

    public static function create(
        Uuid $id,
        string $sourceLanguage,
        string $originalText,
        string $targetLanguage,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ): self {
        return new self(
            id: $id,
            sourceLanguage: $sourceLanguage,
            originalText: $originalText,
            targetLanguage: $targetLanguage,
            translatedText: null,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function sourceLanguage(): string
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

    public function translatedText(): ?string
    {
        return $this->translatedText;
    }

    public function updateTranslatedText(?string $translatedText): void
    {
        $this->translatedText = $translatedText;
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'source_lang' => $this->sourceLanguage,
            'original_text' => $this->originalText,
            'target_lang' => $this->targetLanguage,
            'translated_text' => $this->translatedText,
            'created_at' => Utils::dateToString($this->createdAt),
            'updated_at' => Utils::dateToString($this->updatedAt)
        ];
    }
}
