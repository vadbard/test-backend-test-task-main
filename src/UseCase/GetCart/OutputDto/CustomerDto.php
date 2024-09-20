<?php

namespace Raketa\BackendTestTask\UseCase\GetCart\OutputDto;

readonly class CustomerDto
{
    public function __construct(
        public int $id,
        public string $fullName,
        public string $email,
    )
    {
    }
}