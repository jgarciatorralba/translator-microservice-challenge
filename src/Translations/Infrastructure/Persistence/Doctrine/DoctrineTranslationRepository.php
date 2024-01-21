<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Translations\Domain\Contract\TranslationRepository;
use App\Translations\Domain\Translation;
use DateTimeImmutable;

class DoctrineTranslationRepository extends DoctrineRepository implements TranslationRepository
{
    protected function entityClass(): string
    {
        return Translation::class;
    }

    public function create(Translation $translation): void
    {
        $this->persist($translation);
    }

    public function update(Translation $translation): void
    {
        $this->updateEntity();
    }

    public function delete(Translation $translation): void
    {
        $translation->updateDeletedAt(new DateTimeImmutable());
        $this->updateEntity();
    }

    /**
     * @return Translation[]
     */
    public function findAll(): array
    {
        return $this->repository()->findAll();
    }

    public function findOneById(Uuid $id): Translation|null
    {
        return $this->repository()->findOneBy(['id' => $id->value()]);
    }

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, string>|null $orderBy
     * @return Translation[]
     */
    public function findByCriteria(
        array $criteria = [],
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        return $this->repository()->findBy($criteria, $orderBy, $limit, $offset);
    }
}
