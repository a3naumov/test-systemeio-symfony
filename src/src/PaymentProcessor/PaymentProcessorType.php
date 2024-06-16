<?php

declare(strict_types=1);

namespace App\PaymentProcessor;

enum PaymentProcessorType: string
{
    case PayPal = 'paypal';
    case Stripe = 'stripe';
    case GlobalPayments = 'global_payments';
}
