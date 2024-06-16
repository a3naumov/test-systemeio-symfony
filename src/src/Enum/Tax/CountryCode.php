<?php

declare(strict_types=1);

namespace App\Enum\Tax;

enum CountryCode: string
{
    public const array TAX_NUMBER_PATTERNS = [
        self::Germany->value => '/^DE\d{9}$/',
        self::Italy->value => '/^IT\d{11}$/',
        self::France->value => '/^FR[A-Z]{2}\d{9}$/',
        self::Greece->value => '/^GR\d{9}$/',
    ];

    case Germany = 'DE';
    case Italy = 'IT';
    case France = 'FR';
    case Greece = 'GR';

    public static function tryFromTaxNumber(string $taxNumber): ?self
    {
        foreach (self::TAX_NUMBER_PATTERNS as $countryCode => $pattern) {
            if (preg_match($pattern, $taxNumber)) {
                return self::from($countryCode);
            }
        }

        return null;
    }
}
