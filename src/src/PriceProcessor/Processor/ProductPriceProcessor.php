<?php

declare(strict_types=1);

namespace App\PriceProcessor\Processor;

use App\Exception\PriceCalculationException;
use App\PriceProcessor\PriceCalculationContext;
use App\PriceProcessor\PriceProcessorInterface;
use App\Repository\ProductRepository;

final class ProductPriceProcessor implements PriceProcessorInterface
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function process(float &$price, PriceCalculationContext $context): void
    {
        $product = $this->productRepository->find($context->productId);

        if (!$product) {
            throw new PriceCalculationException('Product not found');
        }

        $price += $product->getPrice();
    }
}
