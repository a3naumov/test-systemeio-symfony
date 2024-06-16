<?php

declare(strict_types=1);

namespace App\Validator;

use App\PaymentProcessor\PaymentProcessorType;
use App\Validator\Constraint\PaymentProcessor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @codeCoverageIgnore
 */
class PaymentProcessorValidator extends ConstraintValidator
{
    /**
     * @param PaymentProcessor $constraint
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        $processor = PaymentProcessorType::tryFrom($value);

        if (!$processor) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
