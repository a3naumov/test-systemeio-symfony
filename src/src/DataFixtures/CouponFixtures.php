<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Enum\Coupon\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CouponFixtures extends Fixture
{
    private const array COUPONS = [
        [
            'code' => 'P10',
            'type' => Type::Percent,
            'value' => 10,
        ],
        [
            'code' => 'P30',
            'type' => Type::Percent,
            'value' => 30,
        ],
        [
            'code' => 'P50',
            'type' => Type::Percent,
            'value' => 50,
        ],
        [
            'code' => 'F10',
            'type' => Type::Fixed,
            'value' => 10,
        ],
        [
            'code' => 'F100',
            'type' => Type::Fixed,
            'value' => 100,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::COUPONS as $couponData) {
            $coupon = (new Coupon())
                ->setCode($couponData['code'])
                ->setType($couponData['type'])
                ->setValue($couponData['value']);

            $manager->persist($coupon);
        }

        $manager->flush();
    }
}
