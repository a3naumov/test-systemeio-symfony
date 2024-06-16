<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequest
{
    private Request $request;

    private array $requestData = [];

    public function __construct(protected ValidatorInterface $validator)
    {
        $this->request = Request::createFromGlobals();
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function validate(): ConstraintViolationListInterface
    {
        return $this->validator->validate($this->getRequestData(), $this->getConstraints());
    }

    public function getErrorsMessages(ConstraintViolationListInterface $errors): array
    {
        return array_map(
            fn ($error) => [
                'property' => $error->getPropertyPath(),
                'value' => $error->getInvalidValue(),
                'message' => $error->getMessage(),
            ],
            iterator_to_array($errors),
        );
    }

    public function __get(string $name): mixed
    {
        $requestData = $this->getRequestData();

        return $requestData[$name] ?? null;
    }

    protected function getConstraints(): Collection
    {
        return new Collection([]);
    }

    protected function getRequestData(): array
    {
        if (empty($this->requestData)) {
            $this->requestData = $this->request->isMethod(Request::METHOD_GET)
                ? $this->request->query->all()
                : $this->request->toArray();
        }

        return $this->requestData;
    }
}
