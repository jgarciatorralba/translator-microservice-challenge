<?php

declare(strict_types=1);

namespace App\Translations\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Trait\TimestampableTrait;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Utils;
use App\Translations\Domain\Event\TranslationRequestedEvent;
use App\Translations\Domain\ValueObject\StatusEnum;
use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
use DateTimeImmutable;

class Translation extends AggregateRoot
{
    use TimestampableTrait;

    public function __construct(
        private Uuid $id,
        private ?string $sourceLanguage,
        private string $originalText,
        private string $targetLanguage,
        private StatusEnum $status,
        private ?string $translatedText,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ) {
        $this->updateCreatedAt($createdAt);
        $this->updateUpdatedAt($updatedAt);
    }

    public static function create(
        Uuid $id,
        ?SupportedLanguageEnum $sourceLanguage,
        string $originalText,
        SupportedLanguageEnum $targetLanguage,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ): self {
        return new self(
            id: $id,
            sourceLanguage: $sourceLanguage ? $sourceLanguage->value : null,
            originalText: $originalText,
            targetLanguage: $targetLanguage->value,
            status: StatusEnum::QUEUED,
            translatedText: null,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function sourceLanguage(): ?string
    {
        return $this->sourceLanguage;
    }

    public function updateSourceLanguage(string $sourceLanguage): void
    {
        $detectedSourceLanguage = SupportedLanguageEnum::tryFrom($sourceLanguage);
        $this->sourceLanguage = $detectedSourceLanguage->value ?? 'unfit';
    }

    public function originalText(): string
    {
        return $this->originalText;
    }

    public function targetLanguage(): string
    {
        return $this->targetLanguage;
    }

    public function status(): StatusEnum
    {
        return $this->status;
    }

    public function updateStatus(StatusEnum $status): void
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

    public function request(Uuid $id): void
    {
        $this->recordEvent(new TranslationRequestedEvent(
            aggregateId: $id->value()
        ));
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'sourceLang' => $this->sourceLanguage,
            'originalText' => $this->originalText,
            'targetLang' => $this->targetLanguage,
            'status' => $this->status->value,
            'translatedText' => $this->translatedText,
            'createdAt' => Utils::dateToString($this->createdAt),
            'updatedAt' => Utils::dateToString($this->updatedAt)
        ];
    }
}
