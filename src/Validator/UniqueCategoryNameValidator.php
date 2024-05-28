<?php

namespace App\Validator;

use App\Repository\CategoryRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueCategoryNameValidator extends ConstraintValidator
{

    public function __construct(private readonly CategoryRepository $categoryRepository)
    {}

    public function validate($value, Constraint $constraint)
    {
        /* @var UniqueCategoryName $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $existingCategory = $this->categoryRepository->findOneBy(['name' => $value]);

        if(!$existingCategory) {
            return;
        }
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
