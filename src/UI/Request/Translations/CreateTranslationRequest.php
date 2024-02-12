<?php

declare(strict_types=1);

namespace App\UI\Request\Translations;

use App\Translations\Domain\ValueObject\LanguageEnum;
use App\UI\Request\AbstractRequest;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateTranslationRequest extends AbstractRequest
{
    protected function validationRules(): Assert\Collection
    {
        return new Assert\Collection([
            'sourceLanguage' => new Assert\Optional([
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Length(min: 2),
                new Assert\Choice(LanguageEnum::supportedValues())
            ]),
            'originalText' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Length(max: 255)
            ]),
            'targetLanguage' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Length(min: 2),
                new Assert\Choice(LanguageEnum::supportedValues())
            ]),
        ]);
    }
}
