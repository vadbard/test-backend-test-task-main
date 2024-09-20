<?php

namespace Raketa\BackendTestTask\Serializer;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Domain\Customer;
use Raketa\BackendTestTask\Repository\CustomerRepository;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CartNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function __construct(
        private CustomerRepository $customerRepository,
        private ProductRepository $productRepository,
        private Customer $customer,
    )
    {
    }

    public function normalize(Cart $cart): array
    {
        $items = [];
        foreach ($cart->getItems() as $cartItem) {
            $items['uuid'] = $cartItem->getUuid();
            $items['price'] = $cartItem->getPrice();
            $items['quantity'] = $cartItem->getQuantity();
            $items['productId'] = $cartItem->getProduct()->getId();
        }

        return [
            'uuid' => $cart->getUuid(),
            'paymentMethod' => $cart->getPaymentMethod(),
            'items' => $items,
        ];
    }

    public function denormalize($data, $class, $format = null, array $context = []): Cart
    {
        $productIds = array_column($data['items'], 'productId');
        $products = $this->getProducts($productIds);

        $cartItems = [];
        foreach ($data['items'] as $item) {
            $cartItems[] = new CartItem(
                uuid: $item['uuid'],
                product: $products[$item['productId']],
                price: $item['price'],
                quantity: $item['quantity'],
            );
        }

        return new Cart(
            uuid: $data['uuid'],
            customer: $this->customer,
            paymentMethod: $data['paymentMethod'],
            items: $cartItems
        );
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Cart;
    }

    public function supportsDenormalization($data, $class, $format = null)
    {
        return $class === Cart::class;
    }

    private function getProducts(array $productIds): array
    {
        $products = $this->productRepository->getByIds($productIds);

        return array_reduce($products, function($carry, $product) {
            $carry[$product->getId()] = $product;
            return $carry;
        }, []);
    }
}