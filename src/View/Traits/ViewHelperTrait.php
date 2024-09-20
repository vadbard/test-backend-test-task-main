<?php

namespace Raketa\BackendTestTask\View\Traits;

trait ViewHelperTrait
{
    private function makePriceFloat(int $price): float
    {
        return  number_format($price / 100, 2, '.', '');
    }
}