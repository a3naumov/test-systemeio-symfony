<?php

declare(strict_types=1);

namespace App\Tests\Unit\PriceProcessor\Processor;

use App\Entity\Product;
use App\Exception\PriceCalculationException;
use App\PriceProcessor\PriceCalculationContext;
use App\PriceProcessor\Processor\ProductPriceProcessor;
use App\Repository\ProductRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\PriceProcessor\Processor\ProductPriceProcessor
 */
class ProductPriceProcessorTest extends TestCase
{
    private ProductPriceProcessor $subject;
    private MockObject|ProductRepository $productRepositoryMock;
    private MockObject|Product $productMock;

    public function testProcessWithValidProduct(): void
    {
        $price = 0.0;
        $contextStub = new PriceCalculationContext(productId: 1, taxNumber: 'tax-number');

        $this->productMock
            ->expects(self::once())
            ->method('getPrice')
            ->willReturn(100.0);

        $this->productRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(1)
            ->willReturn($this->productMock);

        $this->subject->process($price, $contextStub);

        self::assertSame(100.0, $price);
    }

    public function testProcessWithInvalidProduct(): void
    {
        $price = 0.0;
        $contextStub = new PriceCalculationContext(1, 'tax-number');

        $this->productRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(PriceCalculationException::class);
        $this->expectExceptionMessage('Product not found');

        $this->subject->process($price, $contextStub);
    }

    protected function setUp(): void
    {
        $this->productRepositoryMock = $this->createMock(ProductRepository::class);
        $this->productMock = $this->createMock(Product::class);

        $this->subject = new ProductPriceProcessor($this->productRepositoryMock);
    }
}