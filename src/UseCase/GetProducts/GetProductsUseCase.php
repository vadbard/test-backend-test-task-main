<?php

namespace Raketa\BackendTestTask\UseCase\GetProducts;

use Raketa\BackendTestTask\Domain\Product;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\UseCase\GetProducts\OutputDto\ProductDto;

class GetProductsUseCase
{
    public function __construct(
        private ProductRepository $productRepository,
    )
    {
    }

    public function execute(string $category): array
    {
        $products = $this->productRepository->findByCategory($category);

        return array_map(
            fn (Product $productDto) => new ProductDto(
                id: $productDto->getId(),
                uuid: $productDto->getUuid(),
                category: $productDto->getCategory(),
                description: $productDto->getDescription(),
                thumbnail: $productDto->getThumbnail(),
                price: $productDto->getPrice(),
            ),
            $products
        );
    }
}