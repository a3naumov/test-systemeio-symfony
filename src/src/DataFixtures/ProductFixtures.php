<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    private const array PRODUCTS = [
        [
            'name' => 'Macbook',
            'price' => 1500,
        ],
        [
            'name' => 'Ipad',
            'price' => 500,
        ],
        [
            'name' => 'Iphone',
            'price' => 100,
        ],
        [
            'name' => 'Headphones',
            'price' => 20,
        ],
        [
            'name' => 'Case',
            'price' => 10,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PRODUCTS as $productData) {
            $product = (new Product())
                ->setName($productData['name'])
                ->setPrice($productData['price']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
