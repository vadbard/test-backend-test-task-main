<?php

namespace Raketa\BackendTestTask\UseCase\GetCart\OutputDto;

readonly class ProductDto
{
    public function __construct(
        public int $id,
        public string $uuid,
        public string $name,
        public string $category,
        public string $description,
        public string $thumbnail,
        public int $price,
    )
    {
    }
}