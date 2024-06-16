<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use App\Validator\PaymentProcessorValidator;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PaymentProcessor extends Constraint
{
    public string $message = 'The payment processor {{ value }} is not valid.';

    public function __construct(
        ?string $message = null,
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
    }

    public function validatedBy(): string
    {
        return PaymentProcessorValidator::class;
    }
}
