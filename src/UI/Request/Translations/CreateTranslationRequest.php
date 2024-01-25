<?php

declare(strict_types=1);

namespace App\UI\Request\Translations;

use App\Translations\Domain\ValueObject\SupportedLanguageEnum;
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
                new Assert\Length(min: 2, max: 5),
                new Assert\Choice(SupportedLanguageEnum::values())
            ]),
            'originalText' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Length(max: 255)
            ]),
            'targetLanguage' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Length(min: 2, max: 5),
                new Assert\Choice(SupportedLanguageEnum::values())
            ]),
        ]);
    }
}
