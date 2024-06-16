<?php

declare(strict_types=1);

namespace App\Validator;

use App\Enum\Tax\CountryCode;
use App\Validator\Constraint\TaxNumber;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TaxNumberValidator extends ConstraintValidator
{
    /**
     * @param TaxNumber $constraint
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($value === null || $value === '') {
            return;
        }

        $countryCode = substr($value, 0, 2);

        if (!isset(CountryCode::TAX_NUMBER_PATTERNS[$countryCode])
            || !preg_match(CountryCode::TAX_NUMBER_PATTERNS[$countryCode], $value)
        ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
