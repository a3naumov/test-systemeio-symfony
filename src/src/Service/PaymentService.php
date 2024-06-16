<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\PaymentProcessingException;
use App\PaymentProcessor\PaymentProcessorInterface;
use App\PaymentProcessor\PaymentProcessorType;

class PaymentService
{
    /**
     * @param PaymentProcessorInterface[] $paymentProcessors
     */
    public function __construct(private array $paymentProcessors)
    {
    }

    /**
     * @throws \InvalidArgumentException
     * @throws PaymentProcessingException
     */
    public function processPayment(float $amount, PaymentProcessorType $paymentProcessor): void
    {
        if (!array_key_exists($paymentProcessor->value, $this->paymentProcessors)) {
            throw new \InvalidArgumentException('Invalid payment processor');
        }

        $this->paymentProcessors[$paymentProcessor->value]->processPayment($amount);
    }
}
