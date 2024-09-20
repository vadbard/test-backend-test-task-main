<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Exception\Infrastructure\DataStorageException;
use Raketa\BackendTestTask\Exception\Repository\RepositoryException;
use Raketa\BackendTestTask\Infrastructure\DataStorageInterface;

class CartRepository
{
    public function __construct(
        private DataStorageInterface $storage,
        private SerializerInterface $serializer,
    )
    {
    }

    public function saveCart(Cart $cart, int $lifetime): void
    {
        $cartJson = $this->serializer->serialize($cart, 'json');

        $customerId = (string) $cart->getCustomer()->getId();

        try {
            $this->storage->set($customerId, $cartJson, $lifetime);
        } catch (Exception $e) {
            throw new RepositoryException('Storage error while saving cart. Cart: ' . $cartJson, RepositoryException::NOT_SAVED, $e);
        }
    }

    public function updateCart(Cart $cart): void
    {
        $cartJson = $this->serializer->serialize($cart, 'json');

        $customerId = (string) $cart->getCustomer()->getId();

        try {
           $this->storage->set($customerId, $cartJson);
        } catch (Exception $e) {
            throw new RepositoryException('Storage error while saving cart. Cart: ' . $cartJson, RepositoryException::NOT_SAVED, $e);
        }
    }

    public function findCartByCustomerId(int $customerId): ?Cart
    {
        $customerId = (string) $customerId;

        try {
            $cartJson = $this->storage->get($customerId);
        } catch (DataStorageException $e) {
            throw new RepositoryException('Storage error while finding cart by uuid. Uuid: ' . $customerId, RepositoryException::NOT_FOUND, $e);
        }

        if ($cartJson === false) {
            return null;
        }

        return $this->serializer->unserialize($cartJson, Cart::class, 'json');
    }
}
