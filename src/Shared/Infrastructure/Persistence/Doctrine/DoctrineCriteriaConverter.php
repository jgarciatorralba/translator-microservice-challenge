<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Criteria\Criteria;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;

final class DoctrineCriteriaConverter
{
    public function __construct(
        private readonly Criteria $criteria
    ) {
    }

    public static function convert(Criteria $criteria): DoctrineCriteria
    {
        $converter = new self($criteria);

        return $converter->convertToDoctrineCriteria();
    }

    private function convertToDoctrineCriteria(): DoctrineCriteria
    {
        return new DoctrineCriteria();
    }
}
