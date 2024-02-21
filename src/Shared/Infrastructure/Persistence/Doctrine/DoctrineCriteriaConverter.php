<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Filter;
use App\Shared\Domain\Criteria\FilterConditionEnum;
use App\Shared\Domain\Criteria\Order;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\Common\Collections\Expr\CompositeExpression;

final class DoctrineCriteriaConverter
{
    public function __construct(
        private readonly Criteria $criteria
    ) {}

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
        if ($criteria->hasFilters()) {
            $expression = null;

            /** @var Filter $filter */
            foreach ($criteria->filters() as $filter) {
                $expression = $this->addFilterToExpression($filter, $expression);
            }

            return $expression;
        }

        return null;
    }

    private function addFilterToExpression(
        Filter $filter,
        ?CompositeExpression $expression
    ): CompositeExpression {
        $expressions = [];
        if ($expression !== null) {
            $expressions[] = $expression;
        }

        return new CompositeExpression(
            $this->mapConditionTypeConstant($filter->condition()),
            $expressions
        );
    }

    private function mapConditionTypeConstant(FilterConditionEnum $conditionType): string
    {
        if ($conditionType === FilterConditionEnum::AND) {
            return CompositeExpression::TYPE_AND;
        } else {
            return CompositeExpression::TYPE_OR;
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
