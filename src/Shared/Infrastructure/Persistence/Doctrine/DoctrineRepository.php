<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\ValueObject\SearchCriteria\Criteria;
use App\Shared\Domain\ValueObject\SearchCriteria\FilterConditionEnum;
use App\Shared\Domain\ValueObject\SearchCriteria\FilterOperatorEnum;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class DoctrineRepository
{
    protected const MAX_PAGE_SIZE = 50;

    /**
     * @var EntityRepository<object> $repository
     */
    private EntityRepository $repository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        /** @var class-string $className */
        $className = $this->entityClass();
        $this->repository = $entityManager->getRepository($className);
    }

    protected function entityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @return EntityRepository<object>
     */
    protected function repository(): EntityRepository
    {
        return $this->repository;
    }

    protected function persist(AggregateRoot $entity): void
    {
        $this->entityManager()->persist($entity);
        $this->entityManager()->flush();
    }

    protected function remove(AggregateRoot $entity): void
    {
        $this->entityManager()->remove($entity);
        $this->entityManager()->flush();
    }

    protected function updateEntity(): void
    {
        $this->entityManager()->flush();
    }

    protected function convertToDoctrineCriteria(Criteria $criteria): DoctrineCriteria
    {
        $doctrineCriteria = new DoctrineCriteria();

        foreach ($criteria->filters() as $filter) {
            $condition = null;
            if ($filter->condition() === FilterConditionEnum::EQUAL) {
                $condition = DoctrineCriteria::expr()->eq($filter->columnName(), $filter->value());
            } elseif ($filter->condition() === FilterConditionEnum::LOWER_THAN) {
                $condition = DoctrineCriteria::expr()->lt($filter->columnName(), $filter->value());
            }

            if ($condition) {
                if ($filter->operator() === FilterOperatorEnum::AND) {
                    $doctrineCriteria->andWhere($condition);
                } elseif ($filter->operator() === FilterOperatorEnum::OR) {
                    $doctrineCriteria->orWhere($condition);
                }
            }
        }

        if (!empty($criteria->orderBy())) {
            $orderBy = [];
            foreach ($criteria->orderBy() as $columnName => $order) {
                $orderBy[$columnName] = $order->value;
            }

            $doctrineCriteria->orderBy($orderBy);
        }
        $doctrineCriteria->setFirstResult($criteria->offset());

        if ($criteria->limit() === null || $criteria->limit() > self::MAX_PAGE_SIZE) {
            $limit = self::MAX_PAGE_SIZE;
        } else {
            $limit = $criteria->limit();
        }
        $doctrineCriteria->setMaxResults($limit);

        return $doctrineCriteria;
    }

    abstract protected function entityClass(): string;
}
