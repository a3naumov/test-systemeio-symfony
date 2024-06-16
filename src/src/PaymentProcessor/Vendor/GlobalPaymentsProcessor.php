<?php

declare(strict_types=1);

namespace App\PaymentProcessor\Vendor;

class GlobalPaymentsProcessor
{
    public function purchase(int $amount): void
    {
        if ($amount > 100) {
            throw new \InvalidArgumentException('Amount is too high');
        }
    }
}