<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\PriceCalculationException;
use App\PriceProcessor\PriceCalculationContext;
use App\PriceProcessor\PriceProcessorInterface;

class PriceCalculator
{
    /**
     * @param PriceProcessorInterface[] $processors
     */
    public function __construct(private array $processors)
    {
    }

    /**
     * @throws PriceCalculationException
     */
    public function calculatePrice(PriceCalculationContext $context): float
    {
        $price = 0.0;

        foreach ($this->processors as $processor) {
            $processor->process($price, $context);
        }

        return max($price, 0);
    }
}
