<?php

declare(strict_types=1);

namespace App\Translations\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Translations\Domain\Contract\TranslationRepository;
use App\Translations\Domain\Translation;

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
        $this->remove($translation);
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

    /** @return Translation[] */
    public function matching(Criteria $criteria): array
    {
        return $this->repository()
            ->matching($this->convertToDoctrineCriteria($criteria))
            ->toArray();
    }
}
