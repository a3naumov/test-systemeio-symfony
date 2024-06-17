<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Exception\PaymentProcessingException;
use App\PaymentProcessor\PaymentProcessorInterface;
use App\PaymentProcessor\PaymentProcessorType;
use App\Service\PaymentService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \App\Service\PaymentService
 */
class PaymentServiceTest extends TestCase
{
    private PaymentService $subject;
    private MockObject|ContainerInterface $containerMock;
    private MockObject|PaymentProcessorInterface $paypalPaymentProcessorMock;
    private MockObject|PaymentProcessorInterface $stripePaymentProcessorMock;

    public function testProcessPaymentWithPaypal(): void
    {
        $this->containerMock
            ->method('has')
            ->with('paypal')
            ->willReturn(true);

        $this->containerMock
            ->method('get')
            ->willReturn($this->paypalPaymentProcessorMock);

        $this->paypalPaymentProcessorMock
            ->expects(self::once())
            ->method('processPayment')
            ->with(100.0);

        $this->subject->processPayment(100.0, PaymentProcessorType::PayPal);
    }

    public function testProcessPaymentWithException(): void
    {
        $this->containerMock
            ->method('has')
            ->with('paypal')
            ->willReturn(true);

        $this->containerMock
            ->expects(self::once())
            ->method('get')
            ->willReturn($this->paypalPaymentProcessorMock);

        $this->paypalPaymentProcessorMock
            ->method('processPayment')
            ->willThrowException(new PaymentProcessingException('Payment failed', 0));

        $this->expectException(PaymentProcessingException::class);
        $this->expectExceptionMessage('Payment failed');

        $this->subject->processPayment(100.0, PaymentProcessorType::PayPal);
    }

    public function testProcessPaymentWithInvalidPaymentProcessor(): void
    {
        $this->containerMock
            ->method('has')
            ->with('paypal')
            ->willReturn(false);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid payment processor');

        $this->paypalPaymentProcessorMock
            ->expects(self::never())
            ->method('processPayment');

        $this->stripePaymentProcessorMock
            ->expects(self::never())
            ->method('processPayment');

        $this->subject->processPayment(100.0, PaymentProcessorType::PayPal);
    }

    protected function setUp(): void
    {
        $this->containerMock = $this->createMock(ContainerInterface::class);
        $this->paypalPaymentProcessorMock = $this->createMock(PaymentProcessorInterface::class);
        $this->stripePaymentProcessorMock = $this->createMock(PaymentProcessorInterface::class);

        $this->subject = new PaymentService($this->containerMock);
    }
}