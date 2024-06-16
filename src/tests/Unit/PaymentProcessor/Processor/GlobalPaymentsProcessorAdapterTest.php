<?php

declare(strict_types=1);

namespace App\Tests\Unit\PaymentProcessor\Processor;

use App\Exception\PaymentProcessingException;
use App\PaymentProcessor\Processor\GlobalPaymentsProcessorAdapter;
use App\PaymentProcessor\Vendor\GlobalPaymentsProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \App\PaymentProcessor\Processor\GlobalPaymentsProcessorAdapter
 */
class GlobalPaymentsProcessorAdapterTest extends TestCase
{
    private GlobalPaymentsProcessorAdapter $subject;
    private MockObject|GlobalPaymentsProcessor $globalPaymentProcessorMock;
    private MockObject|LoggerInterface $loggerMock;

    public function testProcessPayment(): void
    {
        $this->globalPaymentProcessorMock
            ->expects(self::once())
            ->method('purchase')
            ->with(100);

        $this->loggerMock
            ->expects(self::never())
            ->method('critical');

        $this->subject->processPayment(100);
    }

    public function testProcessPaymentThrowsException(): void
    {
        $this->globalPaymentProcessorMock
            ->expects(self::once())
            ->method('purchase')
            ->with(100)
            ->willThrowException(new \Exception('An error occurred'));

        $this->loggerMock
            ->expects(self::once())
            ->method('critical')
            ->with('Payment processing failed', ['exception' => new \Exception('An error occurred')]);

        $this->expectException(PaymentProcessingException::class);
        $this->expectExceptionMessage('Payment processing failed');

        $this->subject->processPayment(100);
    }

    protected function setUp(): void
    {
        $this->globalPaymentProcessorMock = $this->createMock(GlobalPaymentsProcessor::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->subject = new GlobalPaymentsProcessorAdapter($this->globalPaymentProcessorMock, $this->loggerMock);
    }
}