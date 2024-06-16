<?php

declare(strict_types=1);

namespace App\PriceProcessor;

/**
 * @codeCoverageIgnore
 */
final readonly class PriceCalculationContext
{
    public function __construct(
        public int $productId,
        public string $taxNumber,
        public ?string $couponCode = null,
    ) {
    }
}
