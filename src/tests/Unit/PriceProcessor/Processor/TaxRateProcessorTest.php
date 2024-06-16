<?php

declare(strict_types=1);

namespace App\Tests\Unit\PriceProcessor\Processor;

use App\Entity\TaxRate;
use App\Enum\Tax\CountryCode;
use App\Exception\PriceCalculationException;
use App\PriceProcessor\PriceCalculationContext;
use App\PriceProcessor\Processor\TaxRateProcessor;
use App\Repository\TaxRateRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\PriceProcessor\Processor\TaxRateProcessor
 */
class TaxRateProcessorTest extends TestCase
{
    private const string VALID_TAX_NUMBER = 'IT12345678900';
    private const string INVALID_TAX_NUMBER = 'invalid-tax-number';

    private TaxRateProcessor $subject;
    private MockObject|TaxRateRepository $taxRateRepositoryMock;
    private MockObject|TaxRate $taxRateMock;

    public function testProcessWithValidTaxRate(): void
    {
        $price = 100.0;
        $contextStub = new PriceCalculationContext(1, self::VALID_TAX_NUMBER);

        $this->taxRateMock
            ->expects(self::once())
            ->method('getRate')
            ->willReturn(10.0);

        $this->taxRateRepositoryMock
            ->expects(self::once())
            ->method('findByCountryCode')
            ->with(CountryCode::tryFromTaxNumber(self::VALID_TAX_NUMBER))
            ->willReturn($this->taxRateMock);

        $this->subject->process($price, $contextStub);

        self::assertSame(110.0, $price);
    }

    public function testWithUnknownTaxRate(): void
    {
        $price = 100.0;
        $contextStub = new PriceCalculationContext(1, self::INVALID_TAX_NUMBER);

        $this->taxRateRepositoryMock
            ->expects(self::never())
            ->method('findByCountryCode');

        $this->expectExceptionMessage('Invalid tax number');
        $this->expectException(PriceCalculationException::class);

        $this->subject->process($price, $contextStub);
    }

    public function testWithNoTaxRate(): void
    {
        $price = 100.0;
        $contextStub = new PriceCalculationContext(1, self::VALID_TAX_NUMBER);

        $this->taxRateRepositoryMock
            ->expects(self::once())
            ->method('findByCountryCode')
            ->with(CountryCode::tryFromTaxNumber(self::VALID_TAX_NUMBER))
            ->willReturn(null);

        $this->expectExceptionMessage('Tax rate not found');
        $this->expectException(PriceCalculationException::class);

        $this->subject->process($price, $contextStub);
    }

    protected function setUp(): void
    {
        $this->taxRateRepositoryMock = $this->createMock(TaxRateRepository::class);
        $this->taxRateMock = $this->createMock(TaxRate::class);

        $this->subject = new TaxRateProcessor($this->taxRateRepositoryMock);
    }
}