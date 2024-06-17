<?php

declare(strict_types=1);

namespace App\Tests\Unit\PaymentProcessor\Processor;

use App\PaymentProcessor\Processor\PaypalPaymentProcessorAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

/**
 * @covers \App\PaymentProcessor\Processor\PaypalPaymentProcessorAdapter
 */
class PaypalPaymentProcessorAdapterTest extends TestCase
{
    private PaypalPaymentProcessorAdapter $subject;
    private MockObject|PaypalPaymentProcessor $paypalPaymentProcessorMock;
    private MockObject|LoggerInterface $loggerMock;

    public function testProcessPayment(): void
    {
        $this->paypalPaymentProcessorMock
            ->expects(self::once())
            ->method('pay')
            ->with(100);

        $this->loggerMock
            ->expects(self::never())
            ->method('critical');

        $this->subject->processPayment(100);
    }

    public function testProcessPaymentThrowsException(): void
    {
        $this->paypalPaymentProcessorMock
            ->expects(self::once())
            ->method('pay')
            ->with(100)
            ->willThrowException(new \Exception('An error occurred'));

        $this->loggerMock
            ->expects(self::once())
            ->method('critical')
            ->with('Payment processing failed', ['exception' => new \Exception('An error occurred')]);

        $this->expectException(\App\Exception\PaymentProcessingException::class);
        $this->expectExceptionMessage('Payment processing failed');

        $this->subject->processPayment(100);
    }

    protected function setUp(): void
    {
        $this->paypalPaymentProcessorMock = $this->createMock(PaypalPaymentProcessor::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->subject = new PaypalPaymentProcessorAdapter($this->paypalPaymentProcessorMock, $this->loggerMock);
    }
}