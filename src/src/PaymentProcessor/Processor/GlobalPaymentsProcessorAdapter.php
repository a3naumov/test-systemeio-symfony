<?php

declare(strict_types=1);

namespace App\PaymentProcessor\Processor;

use App\Exception\PaymentProcessingException;
use App\PaymentProcessor\PaymentProcessorInterface;
use App\PaymentProcessor\Vendor\GlobalPaymentsProcessor;
use Psr\Log\LoggerInterface;

final class GlobalPaymentsProcessorAdapter implements PaymentProcessorInterface
{
    public function __construct(
        private GlobalPaymentsProcessor $globalPaymentProcessor,
        private LoggerInterface $logger,
    ) {
    }

    public function processPayment(float $amount): void
    {
        try {
            $this->globalPaymentProcessor->purchase((int) ceil($amount));
        } catch (\Exception $e) {
            $this->logger->critical('Payment processing failed', ['exception' => $e]);
            throw new PaymentProcessingException('Payment processing failed', 0, $e);
        }
    }
}
