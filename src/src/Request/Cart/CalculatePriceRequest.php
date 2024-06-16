<?php

declare(strict_types=1);

namespace App\Request\Cart;

use App\Request\BaseRequest;
use App\Validator\Constraint\TaxNumber;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * @property int $product
 * @property string $taxNumber
 * @property string|null $couponCode
 */
final class CalculatePriceRequest extends BaseRequest
{
    #[\Override]
    protected function getConstraints(): Collection
    {
        return new Collection([
            'product' => [
                new Assert\NotBlank(),
                new Assert\Type('int'),
                new Assert\Positive(),
            ],
            'taxNumber' => [
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new TaxNumber(),
            ],
            'couponCode' => [
                new Assert\NotBlank(allowNull: true),
                new Assert\Type('string'),
            ],
        ]);
    }
}
