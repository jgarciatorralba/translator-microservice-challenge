<?php

declare(strict_types=1);

namespace App\Translations\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Trait\TimestampableTrait;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Utils;
use App\Translations\Domain\ValueObject\ProcessingStatusEnum;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use DateTimeImmutable;

class Translation extends AggregateRoot
{
    use TimestampableTrait;

    public function __construct(
        private Uuid $id,
        private SupportedLanguageEnum $sourceLanguage,
        private string $originalText,
        private SupportedLanguageEnum $targetLanguage,
        private ProcessingStatusEnum $status,
        private ?string $translatedText,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ) {
        $this->updateCreatedAt($createdAt);
        $this->updateUpdatedAt($updatedAt);
    }

    public static function create(
        Uuid $id,
        SupportedLanguageEnum $sourceLanguage,
        string $originalText,
        SupportedLanguageEnum $targetLanguage,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ): self {
        return new self(
            id: $id,
            sourceLanguage: $sourceLanguage,
            originalText: $originalText,
            targetLanguage: $targetLanguage,
            status: ProcessingStatusEnum::QUEUED,
            translatedText: null,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function sourceLanguage(): SupportedLanguageEnum
    {
        return $this->sourceLanguage;
    }

    public function originalText(): string
    {
        return $this->originalText;
    }

    public function targetLanguage(): SupportedLanguageEnum
    {
        return $this->targetLanguage;
    }

    public function status(): ProcessingStatusEnum
    {
        return $this->status;
    }

    public function updateStatus(ProcessingStatusEnum $status): void
    {
        $this->status = $status;
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
            'source_lang' => $this->sourceLanguage->value,
            'original_text' => $this->originalText,
            'target_lang' => $this->targetLanguage->value,
            'status' => $this->status->value,
            'translated_text' => $this->translatedText,
            'created_at' => Utils::dateToString($this->createdAt),
            'updated_at' => Utils::dateToString($this->updatedAt)
        ];
    }
}
