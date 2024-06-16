<?php

declare(strict_types=1);

namespace App\PriceProcessor\Processor;

use App\Enum\Tax\CountryCode;
use App\Exception\PriceCalculationException;
use App\PriceProcessor\PriceCalculationContext;
use App\PriceProcessor\PriceProcessorInterface;
use App\Repository\TaxRateRepository;

final class TaxRateProcessor implements PriceProcessorInterface
{
    public function __construct(private TaxRateRepository $taxRateRepository)
    {
    }

    public function process(float &$price, PriceCalculationContext $context): void
    {
        $countryCode = CountryCode::tryFromTaxNumber($context->taxNumber);

        if (!$countryCode) {
            throw new PriceCalculationException('Invalid tax number');
        }

        $taxRate = $this->taxRateRepository->findByCountryCode($countryCode);

        if (!$taxRate) {
            throw new PriceCalculationException('Tax rate not found');
        }

        $price += $price * $taxRate->getRate() / 100;
    }
}
