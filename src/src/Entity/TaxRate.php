<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Tax\CountryCode;
use App\Repository\TaxRateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaxRateRepository::class)]
#[ORM\Table(name: 'tax_rate', schema: 'public')]
class TaxRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\Column(
        name: 'country_code',
        type: Types::STRING,
        length: 2,
        unique: true,
        nullable: false,
        enumType: CountryCode::class,
    )]
    private CountryCode $countryCode;

    #[ORM\Column(
        name: 'rate',
        type: Types::FLOAT,
        precision: 5,
        scale: 2,
        nullable: false,
    )]
    private float $rate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountryCode(): CountryCode
    {
        return $this->countryCode;
    }

    public function setCountryCode(CountryCode $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
