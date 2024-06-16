<?php

declare(strict_types=1);

namespace App\Tests\Unit\PriceProcessor\Processor;

use App\Entity\Coupon;
use App\Enum\Coupon\Type;
use App\Exception\PriceCalculationException;
use App\PriceProcessor\PriceCalculationContext;
use App\PriceProcessor\Processor\CouponProcessor;
use App\Repository\CouponRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\PriceProcessor\Processor\CouponProcessor
 */
class CouponProcessorTest extends TestCase
{
    private CouponProcessor $subject;
    private MockObject|CouponRepository $couponRepositoryMock;
    private MockObject|Coupon $couponMock;

    public function testProcessWithFixedCouponType(): void
    {
        $price = 100.0;
        $contextStub = new PriceCalculationContext(
            productId: 1,
            taxNumber: 'tax-number',
            couponCode: 'coupon-code',
        );

        $this->couponMock
            ->expects(self::once())
            ->method('getType')
            ->willReturn(Type::Fixed);

        $this->couponMock
            ->expects(self::once())
            ->method('getValue')
            ->willReturn(10.0);

        $this->couponRepositoryMock
            ->expects(self::once())
            ->method('findByCode')
            ->with('coupon-code')
            ->willReturn($this->couponMock);

        $this->subject->process($price, $contextStub);

        self::assertSame(90.0, $price);
    }

    public function testProcessWithPercentCouponType(): void
    {
        $price = 100.0;
        $contextStub = new PriceCalculationContext(
            productId: 1,
            taxNumber: 'tax-number',
            couponCode: 'coupon-code',
        );

        $this->couponMock
            ->expects(self::once())
            ->method('getType')
            ->willReturn(Type::Percent);

        $this->couponMock
            ->expects(self::once())
            ->method('getValue')
            ->willReturn(10.0);

        $this->couponRepositoryMock
            ->expects(self::once())
            ->method('findByCode')
            ->with('coupon-code')
            ->willReturn($this->couponMock);

        $this->subject->process($price, $contextStub);

        self::assertSame(90.0, $price);
    }

    public function testProcessWithoutCouponCode(): void
    {
        $price = 100.0;
        $contextStub = new PriceCalculationContext(1, 'tax-number');

        $this->couponRepositoryMock
            ->expects(self::never())
            ->method('findByCode');

        $this->subject->process($price, $contextStub);

        self::assertSame(100.0, $price);
    }

    public function testProcessWithUnknownCoupon(): void
    {
        $price = 100;
        $contextStub = new PriceCalculationContext(
            productId: 1,
            taxNumber: 'tax-number',
            couponCode: 'coupon-code',
        );

        $this->couponRepositoryMock
            ->expects(self::once())
            ->method('findByCode')
            ->with('coupon-code')
            ->willReturn(null);

        $this->expectException(PriceCalculationException::class);
        $this->expectExceptionMessage('Coupon not found');

        $this->subject->process($price, $contextStub);
    }

    protected function setUp(): void
    {
        $this->couponRepositoryMock = $this->createMock(CouponRepository::class);
        $this->couponMock = $this->createMock(Coupon::class);

        $this->subject = new CouponProcessor($this->couponRepositoryMock);
    }
}