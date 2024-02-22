<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Filter;
use App\Shared\Domain\Criteria\FilterGroup;
use App\Shared\Domain\Criteria\Order;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\CompositeExpression;

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
        return new DoctrineCriteria(
            $this->buildExpression($this->criteria),
            $this->formatOrder($this->criteria),
            $this->criteria->offset(),
            $this->criteria->limit()
        );
    }

    private function buildExpression(Criteria $criteria): ?CompositeExpression
    {
        if ($criteria->hasFilterGroups()) {
            $expression = null;

            /** @var FilterGroup $filterGroup */
            foreach ($criteria->filterGroups() as $filterGroup) {
                $expression = $this->addGroupExpressions($filterGroup, $expression);
            }

            return $expression;
        }

        return null;
    }

    private function addGroupExpressions(
        FilterGroup $filterGroup,
        ?CompositeExpression $expression
    ): CompositeExpression {
        $expressions = [];
        if ($expression !== null) {
            $expressions[] = $expression;
        }

        return new CompositeExpression(
            $filterGroup->condition()->value,
            array_merge(
                $expressions,
                array_map(
                    $this->buildComparison(),
                    $filterGroup->filters()
                )
            )
        );
    }

    private function buildComparison(): callable
    {
        return function (Filter $filter): Comparison {
            return new Comparison(
                $filter->field(),
                $filter->operator()->value,
                $filter->value()
            );
        };
    }

    /** @return array <string, string> */
    private function formatOrder(Criteria $criteria): ?array
    {
        if ($criteria->hasOrder()) {
            $order = [];

            /** @var Order $orderItem */
            foreach ($criteria->orderBy() as $orderItem) {
                $order[$orderItem->orderBy()] = $orderItem->orderType()->value;
            }

            return $order;
        }

        return null;
    }
}
