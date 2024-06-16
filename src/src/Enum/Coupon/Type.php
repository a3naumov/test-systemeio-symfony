<?php

declare(strict_types=1);

namespace App\Enum\Coupon;

enum Type: string
{
    case Percent = 'percent';
    case Fixed = 'fixed';
}
