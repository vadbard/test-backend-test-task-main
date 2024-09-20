<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\Customer;
use Raketa\BackendTestTask\Exception\UseCase\UseCaseException;
use Raketa\BackendTestTask\UseCase\GetCart\GetCartUseCase;
use Raketa\BackendTestTask\View\CartView;

final readonly class GetCartController extends AbstractJsonController
{
    public function __construct(
        public GetCartUseCase $getCartUseCase,
        public Customer $customer,
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        try {
            $cartDto = $this->getCartUseCase->execute($this->customer);
        } catch (UseCaseException $e) {
            return $this->jsonResponseError($e->getMessage());
        }

        $view = new CartView($cartDto);

        return $this->jsonResponseOk($view->toArray());
    }
}
