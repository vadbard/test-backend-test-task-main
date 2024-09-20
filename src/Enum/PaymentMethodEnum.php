<?php

namespace Raketa\BackendTestTask\Enum;

enum PaymentMethodEnum: string
{
    case PayPal = 'paypal';

    case Stripe = 'stripe';
}
