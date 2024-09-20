<?php

namespace Raketa\BackendTestTask\UseCase\GetCart;

use Raketa\BackendTestTask\Domain\Customer;
use Raketa\BackendTestTask\Exception\UseCase\UseCaseException;
use Raketa\BackendTestTask\Repository\CartRepository;
use Raketa\BackendTestTask\UseCase\GetCart\OutputDto\CartDto;
use Raketa\BackendTestTask\UseCase\Traits\CartMakeDtoTrait;

class GetCartUseCase
{
    use CartMakeDtoTrait;

    public function __construct(
        private CartRepository $cartRepository,
    )
    {
    }

    public function execute(Customer $customer): CartDto
    {
        $cart = $this->cartRepository->findCartByCustomerId($customer->getId());

        if (is_null($cart)) {
            throw new UseCaseException('Cart not found', 404, null, [], false);
        }

        return $this->makeCartDto($cart);
    }
}