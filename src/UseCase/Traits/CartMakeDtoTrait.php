<?php

namespace Raketa\BackendTestTask\UseCase\Traits;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\UseCase\GetCart\OutputDto\CartDto;
use Raketa\BackendTestTask\UseCase\GetCart\OutputDto\CartItemDto;
use Raketa\BackendTestTask\UseCase\GetCart\OutputDto\CustomerDto;
use Raketa\BackendTestTask\UseCase\GetCart\OutputDto\ProductDto;

trait CartMakeDtoTrait
{
    protected function makeCartDto(Cart $cart): CartDto
    {
        $cartItemDtos = [];
        $cartTotal = 0;
        foreach ($cart->getItems() as $item) {
            $lineTotal = $item->getPrice() * $item->getQuantity();
            $cartTotal += $lineTotal;

            $cartItemDtos[] = new CartItemDto(
                uuid: $item->getUuid(),
                price: $item->getPrice(),
                quantity: $item->getQuantity(),
                total: $lineTotal,
                productDto: new ProductDto(
                    id: $item->getProduct()->getId(),
                    uuid: $item->getProduct()->getUuid(),
                    name: $item->getProduct()->getName(),
                    category: $item->getProduct()->getCategory(),
                    description: $item->getProduct()->getDescription(),
                    thumbnail: $item->getProduct()->getThumbnail(),
                    price: $item->getProduct()->getPrice(),
                )
            );
        }

        return new CartDto(
            uuid: $cart->getUuid(),
            customerDto: new CustomerDto(
                id: $cart->getCustomer()->getId(),
                fullName: $cart->getCustomer()->getFullName(),
                email: $cart->getCustomer()->getEmail(),
            ),
            paymentMethod: $cart->getPaymentMethod(),
            itemDtos: $cartItemDtos,
            total: $cartTotal,
        );
    }
}