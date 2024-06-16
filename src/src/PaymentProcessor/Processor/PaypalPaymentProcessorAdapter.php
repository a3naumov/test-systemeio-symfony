<?php

declare(strict_types=1);

namespace App\PaymentProcessor\Processor;

use App\Exception\PaymentProcessingException;
use App\PaymentProcessor\PaymentProcessorInterface;
use App\PaymentProcessor\Vendor\PaypalPaymentProcessor;
use Psr\Log\LoggerInterface;

final class PaypalPaymentProcessorAdapter implements PaymentProcessorInterface
{
    public function __construct(
        private PaypalPaymentProcessor $paypalPaymentProcessor,
        private LoggerInterface $logger,
    ) {
    }

    public function processPayment(float $amount): void
    {
        try {
            $this->paypalPaymentProcessor->pay((int) ceil($amount));
        } catch (\Exception $e) {
            $this->logger->critical('Payment processing failed', ['exception' => $e]);
            throw new PaymentProcessingException('Payment processing failed', 0, $e);
        }
    }
}
