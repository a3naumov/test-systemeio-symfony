<?php

declare(strict_types=1);

namespace App\Controller;

use App\PriceProcessor\PriceCalculationContext;
use App\Request\Cart\CalculatePriceRequest;
use App\Request\Cart\MakePurchaseRequest;
use App\Service\PaymentService;
use App\Service\PriceCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/calculate-price', name: 'app.cart.calculate_price', methods: [Request::METHOD_POST])]
    public function calculatePrice(CalculatePriceRequest $request, PriceCalculator $priceCalculator): JsonResponse
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            return $this->json(['errors' => $request->getErrorsMessages($errors)], Response::HTTP_BAD_REQUEST);
        }

        try {
            $priceCalculationContext = new PriceCalculationContext(
                productId: (int) $request->product,
                taxNumber: $request->taxNumber,
                couponCode: $request->couponCode,
            );

            $price = $priceCalculator->calculatePrice($priceCalculationContext);

            return $this->json(['price' => $price], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['errors' => [['message' => $e->getMessage()]]], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/purchase', name: 'app.cart.make_purchase', methods: [Request::METHOD_POST])]
    public function makePurchase(
        MakePurchaseRequest $request,
        PriceCalculator $priceCalculator,
        PaymentService $paymentService,
    ): JsonResponse {
        $errors = $request->validate();

        if (count($errors) > 0) {
            return $this->json(['errors' => $request->getErrorsMessages($errors)], Response::HTTP_BAD_REQUEST);
        }

        try {
            $priceCalculationContext = new PriceCalculationContext(
                productId: (int) $request->product,
                taxNumber: $request->taxNumber,
                couponCode: $request->couponCode,
            );

            $price = $priceCalculator->calculatePrice($priceCalculationContext);
            $paymentService->processPayment($price, $request->paymentProcessor);

            return $this->json(['status' => 'success', 'amount' => $price], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['errors' => [['message' => $e->getMessage()]]], Response::HTTP_BAD_REQUEST);
        }
    }
}
