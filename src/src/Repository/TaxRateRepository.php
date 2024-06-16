<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TaxRate;
use App\Enum\Tax\CountryCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @codeCoverageIgnore
 *
 * @extends ServiceEntityRepository<TaxRate>
 */
class TaxRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaxRate::class);
    }

    public function findByCountryCode(CountryCode $countryCode): ?TaxRate
    {
        return $this->findOneBy(['countryCode' => $countryCode]);
    }
}
