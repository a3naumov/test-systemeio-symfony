<?php

declare(strict_types=1);

namespace App\PriceProcessor;

use App\Exception\PriceCalculationException;

interface PriceProcessorInterface
{
    /**
     * @throws PriceCalculationException
     */
    public function process(float &$price, PriceCalculationContext $context): void;
}
