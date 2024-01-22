<?php

declare(strict_types=1);

namespace App\UI\Validation;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class Validator
{
    private ValidatorInterface $validator;

    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    /**
     * @param array <mixed> $data
     * @return array <string, string>
     */
    public function validate(array $data, Assert\Collection $rules): array
    {
        $errors = [];
        $violations = $this->validator->validate($data, $rules);
        if ($violations->count()) {
            /** @var ConstraintViolationInterface $violation */
            foreach ($violations as $violation) {
                $fieldName = $this->parsePropertyPath($violation->getPropertyPath());
                $errors[$fieldName] = $violation->getMessage();
            }
        }

        return $errors;
    }

    private function parsePropertyPath(string $propertyPath): string
    {
        preg_match('/\[([^\[\]]+)\](?!.*\[)/', $propertyPath, $matches);

        return $matches[1] ?? substr($propertyPath, 1, -1);
    }
}
