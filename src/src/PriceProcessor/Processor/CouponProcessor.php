<?php

declare(strict_types=1);

namespace App\PriceProcessor\Processor;

use App\Enum\Coupon\Type;
use App\Exception\PriceCalculationException;
use App\PriceProcessor\PriceCalculationContext;
use App\PriceProcessor\PriceProcessorInterface;
use App\Repository\CouponRepository;

final class CouponProcessor implements PriceProcessorInterface
{
    public function __construct(private CouponRepository $couponRepository)
    {
    }

    public function process(float &$price, PriceCalculationContext $context): void
    {
        if (!$context->couponCode) {
            return;
        }

        $coupon = $this->couponRepository->findByCode($context->couponCode);

        if ($coupon === null) {
            throw new PriceCalculationException('Coupon not found');
        }

        match ($coupon->getType()) {
            Type::Fixed => $price -= $coupon->getValue(),
            Type::Percent => $price -= $price * $coupon->getValue() / 100,
        };
    }
}
