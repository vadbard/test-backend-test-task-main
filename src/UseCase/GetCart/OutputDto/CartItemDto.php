<?php

namespace Raketa\BackendTestTask\UseCase\GetCart\OutputDto;

readonly class CartItemDto
{
    public function __construct(
        public string $uuid,
        public int $price,
        public int $quantity,
        public int $total,
        public ProductDto $productDto,
    )
    {
    }
}