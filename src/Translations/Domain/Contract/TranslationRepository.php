<?php

declare(strict_types=1);

namespace App\Translations\Domain\Contract;

use App\Translations\Domain\Translation;
use App\Shared\Domain\ValueObject\Uuid;

interface TranslationRepository
{
    public function create(Translation $translation): void;

    public function update(Translation $translation): void;

    public function delete(Translation $translation): void;

    /** @return Translation[] */
    public function findAll(): array;

    public function findOneById(Uuid $id): Translation|null;

    /**
     * @param array <string, mixed> $criteria
     * @param array <string, string>|null $orderBy
     * @return Translation[]
     */
    public function findByCriteria(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array;
}
