<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Coupon\Type;
use App\Repository\CouponRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
#[ORM\Table(name: 'coupon', schema: 'public')]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\Column(
        name: 'code',
        type: Types::STRING,
        length: 255,
        unique: true,
        nullable: false,
    )]
    private string $code;

    #[ORM\Column(
        name: 'type',
        type: Types::STRING,
        length: 255,
        nullable: false,
        enumType: Type::class,
    )]
    private Type $type;

    #[ORM\Column(
        name: 'value',
        type: Types::FLOAT,
        precision: 10,
        scale: 2,
        nullable: false,
    )]
    private float $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }
}
