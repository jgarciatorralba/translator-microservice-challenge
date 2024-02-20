<?php

declare(strict_types=1);

namespace App\Translations\Domain\Contract;

use App\Shared\Domain\Criteria\Criteria;
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

    /** @return Translation[] */
    public function matching(Criteria $criteria): array;
}
