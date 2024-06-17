<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\PaymentProcessingException;
use App\PaymentProcessor\PaymentProcessorType;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

class PaymentService
{
    public function __construct(
        #[AutowireLocator(services: 'app.payment_processor', indexAttribute: 'key')]
        private ContainerInterface $paymentProcessors,
    ) {
    }

    /**
     * @throws \InvalidArgumentException
     * @throws PaymentProcessingException
     */
    public function processPayment(float $amount, PaymentProcessorType $paymentProcessor): void
    {
        if (!$this->paymentProcessors->has($paymentProcessor->value)) {
            throw new \InvalidArgumentException('Invalid payment processor');
        }

        /** @var ContainerInterface $processor */
        $processor = $this->paymentProcessors->get($paymentProcessor->value);
        $processor->processPayment($amount);
    }
}
