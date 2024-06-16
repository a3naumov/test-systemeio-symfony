<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Exception\PaymentProcessingException;
use App\PaymentProcessor\PaymentProcessorInterface;
use App\PaymentProcessor\PaymentProcessorType;
use App\Service\PaymentService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\PaymentService
 */
class PaymentServiceTest extends TestCase
{
    private PaymentService $subject;
    private MockObject|PaymentProcessorInterface $paypalPaymentProcessorMock;
    private MockObject|PaymentProcessorInterface $stripePaymentProcessorMock;

    public function testProcessPaymentWithPaypal(): void
    {
        $this->paypalPaymentProcessorMock
            ->expects(self::once())
            ->method('processPayment')
            ->with(100.0);

        $this->stripePaymentProcessorMock
            ->expects(self::never())
            ->method('processPayment');

        $this->subject->processPayment(100.0, PaymentProcessorType::PayPal);
    }

    public function testProcessPaymentWithException(): void
    {
        $this->paypalPaymentProcessorMock
            ->expects(self::once())
            ->method('processPayment')
            ->with(100.0)
            ->willThrowException(new PaymentProcessingException('Payment processing failed'));

        $this->stripePaymentProcessorMock
            ->expects(self::never())
            ->method('processPayment');

        $this->expectExceptionMessage('Payment processing failed');
        $this->expectException(PaymentProcessingException::class);

        $this->subject->processPayment(100.0, PaymentProcessorType::PayPal);
    }

    public function testProcessPaymentWithInvalidPaymentProcessor(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid payment processor');

        $this->paypalPaymentProcessorMock
            ->expects(self::never())
            ->method('processPayment');

        $this->stripePaymentProcessorMock
            ->expects(self::never())
            ->method('processPayment');

        $this->subject->processPayment(100.0, PaymentProcessorType::GlobalPayments);
    }

    protected function setUp(): void
    {
        $this->paypalPaymentProcessorMock = $this->createMock(PaymentProcessorInterface::class);
        $this->stripePaymentProcessorMock = $this->createMock(PaymentProcessorInterface::class);

        $this->subject = new PaymentService([
            'paypal' => $this->paypalPaymentProcessorMock,
            'stripe' => $this->stripePaymentProcessorMock,
        ]);
    }
}