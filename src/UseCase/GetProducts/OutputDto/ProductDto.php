<?php

namespace Raketa\BackendTestTask\UseCase\GetProducts\OutputDto;

readonly class ProductDto
{
    public function __construct(
        public int $id,
        public string $uuid,
        public string $category,
        public string $description,
        public string $thumbnail,
        public float $price,
    )
    {
    }
}