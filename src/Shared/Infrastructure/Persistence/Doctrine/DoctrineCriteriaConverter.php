<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Filter\CompositeFilter;
use App\Shared\Domain\Criteria\Filter\FilterConditionEnum;
use App\Shared\Domain\Criteria\Filter\FilterOperatorEnum;
use App\Shared\Domain\Criteria\Filter\SimpleFilter;
use App\Shared\Domain\Criteria\Order\Order;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\Common\Collections\ExpressionBuilder;

final class DoctrineCriteriaConverter
{
    public function __construct(
        private readonly Criteria $criteria,
        private readonly ExpressionBuilder $eb
    ) {
    }

    public static function convert(Criteria $criteria): DoctrineCriteria
    {
        $converter = new self($criteria, DoctrineCriteria::expr());

        return $converter->convertToDoctrineCriteria();
    }

    private function convertToDoctrineCriteria(): DoctrineCriteria
    {
        $doctrineCriteria = new DoctrineCriteria();

        $this->buildFilterExpressions($doctrineCriteria, $this->criteria);
        $doctrineCriteria->orderBy($this->formatOrder($this->criteria));
        $doctrineCriteria->setFirstResult($this->criteria->offset() ?? 0);
        $doctrineCriteria->setMaxResults($this->criteria->limit());

        return $doctrineCriteria;
    }

    private function buildFilterExpressions(
        DoctrineCriteria &$doctrineCriteria,
        Criteria $criteria
    ): void {
        if (!$criteria->hasFilters()) {
            return;
        }

        /** @param SimpleFilter|CompositeFilter $filter */
        foreach ($criteria->filters()->plainFilters() as $filter) {
            $expression = $this->buildExpression($filter);
            if ($expression) {
                if ($criteria->filters()->condition() === FilterConditionEnum::AND) {
                    $doctrineCriteria->andWhere($expression);
                } elseif ($criteria->filters()->condition() === FilterConditionEnum::OR) {
                    $doctrineCriteria->orWhere($expression);
                }
            }
        }
    }

    private function buildExpression(SimpleFilter|CompositeFilter $filter): ?Expression
    {
        if ($filter instanceof SimpleFilter) {
            return $this->buildComparison($filter);
        } elseif ($filter instanceof CompositeFilter) {
            $comparisons = [];
            /** @var SimpleFilter $simpleFilter */
            foreach ($filter->filters() as $simpleFilter) {
                $comparison = $this->buildComparison($simpleFilter);
                if (!empty($comparison)) {
                    $comparisons[] = $comparison;
                }
            }

            return $this->buildCompositeExpression($comparisons, $filter->condition());
        }
    }

    private function buildComparison(SimpleFilter $filter): ?Comparison
    {
        switch ($filter->operator()) {
            case FilterOperatorEnum::EQUAL:
                return $this->eb->eq($filter->field(), $filter->value());
            case FilterOperatorEnum::LOWER_THAN:
                return $this->eb->lt($filter->field(), $filter->value());
            default:
                return null;
        }
    }

    /** @param Comparison[] $comparisons */
    private function buildCompositeExpression(
        array $comparisons,
        FilterConditionEnum $condition
    ): ?CompositeExpression {
        if (empty($comparisons)) {
            return null;
        }

        switch ($condition) {
            case FilterConditionEnum::AND:
                return $this->eb->andX(...$comparisons);
            case FilterConditionEnum::OR:
                return $this->eb->orX(...$comparisons);
        }
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
