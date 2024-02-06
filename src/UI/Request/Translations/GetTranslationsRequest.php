<?php

declare(strict_types=1);

namespace App\UI\Request\Translations;

use App\UI\Request\AbstractRequest;
use Symfony\Component\Validator\Constraints as Assert;

final class GetTranslationsRequest extends AbstractRequest
{
    protected function validationRules(): Assert\Collection
    {
        return new Assert\Collection([]);
    }
}
