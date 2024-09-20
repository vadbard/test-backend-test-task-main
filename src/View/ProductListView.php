<?php

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\UseCase\GetProducts\OutputDto\ProductDto;

final readonly class ProductListView
{
    /**
     * @param ProductDto[] $productDtos
     */
    public function __construct(private array $productDtos)
    {
    }

    public function toArray(): array
    {
        return array_map(
            fn (ProductDto $productDto) => [
                'id' => $productDto->id,
                'uuid' => $productDto->uuid,
                'category' => $productDto->category,
                'description' => $productDto->description,
                'thumbnail' => $productDto->thumbnail,
                'price' => $productDto->price,
            ],
            $this->productDtos
        );
    }
}
