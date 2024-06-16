<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Exception\PriceCalculationException;
use App\PriceProcessor\PriceCalculationContext;
use App\PriceProcessor\PriceProcessorInterface;
use App\Service\PriceCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\PriceCalculator
 */
class PriceCalculatorTest extends TestCase
{
    private PriceCalculator $subject;
    private MockObject|PriceProcessorInterface $productPriceProcessorMock;
    private MockObject|PriceProcessorInterface $couponPriceProcessorMock;

    public function testCalculatePrice(): void
    {
        $contextStub = new PriceCalculationContext(
            productId: 1,
            taxNumber: 'tax-number',
            couponCode: 'coupon-code',
        );

        $this->productPriceProcessorMock
            ->expects(self::once())
            ->method('process')
            ->willReturnCallback(function (&$price, $context) {
                $price += 100.0;
            });

        $this->couponPriceProcessorMock
            ->expects(self::once())
            ->method('process')
            ->willReturnCallback(function (&$price, $context) {
                $price -= 10.0;
            });

        $result = $this->subject->calculatePrice($contextStub);

        self::assertSame(90.0, $result);
    }

    public function testCalculatePriceWithNegativePriceCalculation(): void
    {
        $contextStub = new PriceCalculationContext(
            productId: 1,
            taxNumber: 'tax-number',
            couponCode: 'coupon-code',
        );

        $this->productPriceProcessorMock
            ->expects(self::once())
            ->method('process')
            ->willReturnCallback(function (&$price, $context) {
                $price -= 100.0;
            });

        $this->couponPriceProcessorMock
            ->expects(self::once())
            ->method('process')
            ->willReturnCallback(function (&$price, $context) {
                $price -= 10.0;
            });

        $result = $this->subject->calculatePrice($contextStub);

        self::assertSame(0.0, $result);
    }

    public function testCalculatePriceWithException(): void
    {
        $contextStub = new PriceCalculationContext(
            productId: 1,
            taxNumber: 'tax-number',
            couponCode: 'coupon-code',
        );

        $this->productPriceProcessorMock
            ->expects(self::once())
            ->method('process')
            ->willReturnCallback(function (&$price, $context) {
                throw new PriceCalculationException('Price calculation error');
            });

        $this->expectException(PriceCalculationException::class);
        $this->expectExceptionMessage('Price calculation error');

        $this->subject->calculatePrice($contextStub);
    }

    protected function setUp(): void
    {
        $this->productPriceProcessorMock = $this->createMock(PriceProcessorInterface::class);
        $this->couponPriceProcessorMock = $this->createMock(PriceProcessorInterface::class);

        $this->subject = new PriceCalculator([
            $this->productPriceProcessorMock,
            $this->couponPriceProcessorMock,
        ]);
    }
}
