<?php

namespace Raketa\BackendTestTask\UseCase\GetCart\OutputDto;

use Raketa\BackendTestTask\Enum\PaymentMethodEnum;

readonly class CartDto
{
    public function __construct(
        public string            $uuid,
        public CustomerDto       $customerDto,
        public PaymentMethodEnum $paymentMethod,

        /** @var CartItemDto[] */
        public array             $itemDtos,
        public int               $total,
    )
    {
    }
}