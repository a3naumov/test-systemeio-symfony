<?php

declare(strict_types=1);

namespace App\PaymentProcessor;

use App\Exception\PaymentProcessingException;

interface PaymentProcessorInterface
{
    /**
     * @throws PaymentProcessingException
     */
    public function processPayment(float $amount): void;
}
