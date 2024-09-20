<?php

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\Customer;
use Raketa\BackendTestTask\UseCase\AddToCart\AddToCartUseCase;
use Raketa\BackendTestTask\View\CartView;
use Ramsey\Uuid\Uuid;

final readonly class AddToCartController extends AbstractJsonController
{
    public function __construct(
        private AddToCartUseCase $addToCartUseCase,
        private Customer $customer,
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $rawRequest = $this->jsonRequestBody($request);

        $dto = $this->addToCartUseCase->execute($rawRequest['productUuid'], $rawRequest['productUuid'], $this->customer);

        $view = new CartView($dto);

        return $this->jsonResponseOk($view->toArray());
    }
}
