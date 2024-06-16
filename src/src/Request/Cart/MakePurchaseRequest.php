<?php

declare(strict_types=1);

namespace App\Request\Cart;

use App\PaymentProcessor\PaymentProcessorType;
use App\Request\BaseRequest;
use App\Validator\Constraint\PaymentProcessor;
use App\Validator\Constraint\TaxNumber;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * @property int $product
 * @property string $taxNumber
 * @property string|null $couponCode
 * @property PaymentProcessorType $paymentProcessor
 */
final class MakePurchaseRequest extends BaseRequest
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
            'paymentProcessor' => [
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new PaymentProcessor(),
            ],
        ]);
    }

    #[\Override]
    public function __get(string $name): mixed
    {
        $requestData = $this->getRequestData();

        return match ($name) {
            'paymentProcessor' => PaymentProcessorType::tryFrom((string) $requestData[$name] ?? ''),
            default => parent::__get($name),
        };
    }
}
