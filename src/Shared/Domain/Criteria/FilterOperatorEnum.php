<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

use App\Shared\Domain\Trait\EnumValuesProviderTrait;

enum FilterOperatorEnum: string
{
    use EnumValuesProviderTrait;

    case AND = 'and';
    case OR = 'or';
}
