<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\TaxRate;
use App\Enum\Tax\CountryCode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaxRateFixtures extends Fixture
{
    private const array TAX_RATES = [
        [
            'country' => CountryCode::Germany,
            'rate' => 19,
        ],
        [
            'country' => CountryCode::Italy,
            'rate' => 22,
        ],
        [
            'country' => CountryCode::France,
            'rate' => 20,
        ],
        [
            'country' => CountryCode::Greece,
            'rate' => 24,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::TAX_RATES as $taxRateData) {
            $taxRate = (new TaxRate())
                ->setCountryCode($taxRateData['country'])
                ->setRate($taxRateData['rate']);

            $manager->persist($taxRate);
        }

        $manager->flush();
    }
}
