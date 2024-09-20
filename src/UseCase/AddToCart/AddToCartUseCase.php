<?php

namespace Raketa\BackendTestTask\UseCase\AddToCart;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Domain\Customer;
use Raketa\BackendTestTask\Exception\Repository\RepositoryException;
use Raketa\BackendTestTask\Exception\UseCase\UseCaseException;
use Raketa\BackendTestTask\Repository\CartRepository;
use Raketa\BackendTestTask\Repository\CustomerRepository;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\UseCase\GetCart\OutputDto\CartDto;
use Raketa\BackendTestTask\UseCase\Traits\CartMakeDtoTrait;

final readonly class AddToCartUseCase
{
    use CartMakeDtoTrait;

    private const CART_LIFETIME = 86400; // 24 hours

    public function __construct(
        private ProductRepository   $productRepository,
        private CartRepository      $cartRepository,
        private CustomerRepository  $customerRepository,
    )
    {
    }

    public function execute(string $productUuid, int $quantity, Customer $customer): CartDto
    {
        try {
            $product = $this->productRepository->getOneByUuid($productUuid);
        } catch (RepositoryException $exception) {
            throw new UseCaseException("Wrong product uuid", 422, $exception);
        }

        $cart = $this->cartRepository->findCartByCustomerId($customer->getId());

        $cartIsNew = false;
        if (is_null($cart)) {
            $cartIsNew = true;

            $cart = new Cart(
                uuid: Uuid::uuid4()->toString(),
                customer: $customer,
            );
        }

        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $product,
            $product->getPrice(),
            $quantity,
        ));

        try {
            if ($cartIsNew) {
                $this->cartRepository->saveCart($cart, self::CART_LIFETIME);
            } else {
                $this->cartRepository->updateCart($cart);
            }
        } catch (RepositoryException $exception) {
            throw new UseCaseException("Cart not saved", 500, $exception);
        }

        return $this->makeCartDto($cart);
    }
}