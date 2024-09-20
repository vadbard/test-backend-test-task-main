<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\UseCase\GetCart\OutputDto\CartDto;
use Raketa\BackendTestTask\View\Traits\ViewHelperTrait;

final readonly class CartView
{
    use ViewHelperTrait;

    public function __construct(private CartDto $dto)
    {
    }

    public function toArray(): array
    {
        $items = [];
        foreach ($this->dto->itemDtos as $item) {
            $items[] = [
                'uuid' => $item->uuid,
                'price' => $this->makePriceFloat($item->price),
                'quantity' => $item->quantity,
                'total' => $this->makePriceFloat($item->total),
                'product' => [
                    'id' => $item->productDto->id,
                    'uuid' => $item->productDto->uuid,
                    'name' => $item->productDto->name,
                    'thumbnail' => $item->productDto->thumbnail,
                    'price' => $this->makePriceFloat($item->productDto->price),
                ],
            ];
        }

        return [
            'uuid' => $this->dto->uuid,
            'customer' => [
                'id' => $this->dto->customerDto->id,
                'name' => $this->dto->customerDto->fullName,
                'email' => $this->dto->customerDto->email,
            ],
            'payment_method' => $this->dto->paymentMethod->value,
            'items' => $items,
            'total' => $this->makePriceFloat($this->dto->total),
        ];
    }
}
