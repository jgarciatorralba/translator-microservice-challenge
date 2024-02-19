<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject\SearchCriteria;

use App\Shared\Domain\Trait\EnumValuesProviderTrait;

enum OrderEnum: string
{
    use EnumValuesProviderTrait;

    case ASCENDING = 'ASC';
    case DESCENDING = 'DESC';
}
