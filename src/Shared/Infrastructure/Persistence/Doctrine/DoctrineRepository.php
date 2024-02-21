<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\FilterConditionEnum;
use App\Shared\Domain\Criteria\FilterOperatorEnum;
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
            $operator = null;
            if ($filter->operator() === FilterOperatorEnum::EQUAL) {
                $operator = DoctrineCriteria::expr()->eq($filter->columnName(), $filter->value());
            } elseif ($filter->operator() === FilterOperatorEnum::LOWER_THAN) {
                $operator = DoctrineCriteria::expr()->lt($filter->columnName(), $filter->value());
            }

            if ($operator) {
                if ($filter->condition() === FilterConditionEnum::AND) {
                    $doctrineCriteria->andWhere($operator);
                } elseif ($filter->condition() === FilterConditionEnum::OR) {
                    $doctrineCriteria->orWhere($operator);
                }
            }
        }

        if (!empty($criteria->orderBy())) {
            $orderBy = [];
            foreach ($criteria->orderBy() as $order) {
                $orderBy[$order->orderBy()] = $order->orderType()->value;
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
