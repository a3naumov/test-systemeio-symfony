<?php

declare(strict_types=1);

namespace App\Exception;

class PriceCalculationException extends \Exception
{
    public function __construct(string $message = 'Price calculation error', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
