<?php

declare(strict_types=1);

namespace App\Tests\Unit\PaymentProcessor\Processor;

use App\PaymentProcessor\Processor\StripePaymentProcessorAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

/**
 * @covers \App\PaymentProcessor\Processor\StripePaymentProcessorAdapter
 */
class StripePaymentProcessorAdapterTest extends TestCase
{
    private StripePaymentProcessorAdapter $subject;
    private MockObject|StripePaymentProcessor $stripePaymentProcessorMock;
    private MockObject|LoggerInterface $loggerMock;

    public function testProcessPayment(): void
    {
        $this->stripePaymentProcessorMock
            ->expects(self::once())
            ->method('processPayment')
            ->with(100)
            ->willReturn(true);

        $this->loggerMock
            ->expects(self::never())
            ->method('critical');

        $this->subject->processPayment(100);
    }

    public function testProcessPaymentThrowsException(): void
    {
        $this->stripePaymentProcessorMock
            ->expects(self::once())
            ->method('processPayment')
            ->with(100)
            ->willReturn(false);

        $this->loggerMock
            ->expects(self::once())
            ->method('critical')
            ->with('Payment processing failed', ['exception' => 'Payment failed']);

        $this->expectException(\App\Exception\PaymentProcessingException::class);
        $this->expectExceptionMessage('Payment failed');

        $this->subject->processPayment(100);
    }

    protected function setUp(): void
    {
        $this->stripePaymentProcessorMock = $this->createMock(StripePaymentProcessor::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->subject = new StripePaymentProcessorAdapter($this->stripePaymentProcessorMock, $this->loggerMock);
    }
}