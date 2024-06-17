<?php

declare(strict_types=1);

namespace App\PaymentProcessor\Processor;

use App\Exception\PaymentProcessingException;
use App\PaymentProcessor\PaymentProcessorInterface;
use Psr\Log\LoggerInterface;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

final class StripePaymentProcessorAdapter implements PaymentProcessorInterface
{
    public function __construct(
        private StripePaymentProcessor $stripePaymentProcessor,
        private LoggerInterface $logger,
    ) {
    }

    public function processPayment(float $amount): void
    {
        if (!$this->stripePaymentProcessor->processPayment($amount)) {
            $this->logger->critical('Payment processing failed', ['exception' => 'Payment failed']);
            throw new PaymentProcessingException('Payment failed', 0);
        }
    }
}
