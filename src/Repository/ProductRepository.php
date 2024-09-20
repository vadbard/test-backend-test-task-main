<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Domain\Product;
use Raketa\BackendTestTask\Exception\Repository\RepositoryException;

class ProductRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getOneByUuid(string $uuid): Product
    {
        $sql = '
            SELECT id, uuid, is_active, category, name, description, thumbnail, price
            FROM products
            WHERE uuid = ?
        ';

        $row = $this->connection->fetchAllAssociative($sql, [$uuid]);

        if (empty($row)) {
            throw new RepositoryException("Product not found by uuid $uuid", RepositoryException::NOT_FOUND);
        }

        return $this->make($row[0]);
    }

    /**
     * @param string $category
     * @return Product[]
     */
    public function findByCategory(string $category): array
    {
        $sql = '
            SELECT id, uuid, is_active, category, name, description, thumbnail, price
            FROM products
            WHERE is_active = 1
              AND category = ?
        ';

        $rows = $this->connection->fetchAllAssociative($sql, [$category]);

        return array_map(
            static fn (array $row): Product => $this->make($row),
            $rows,
        );
    }

    /**
     * @param array $ids
     * @return Product[]
     * @throws RepositoryException
     */
    public function getByIds(array $ids): array
    {
        $sql = '
            SELECT id, uuid, is_active, category, name, description, thumbnail, price
            FROM products
            WHERE is_active = 1
              AND id IN (?)
        ';

        $rows = $this->connection->fetchAllAssociative($sql, [$ids]);

        $products = array_map(
            static fn (array $row): Product => $this->make($row),
            $rows,
        );

        $foundProductIds = array_map(function($product) {
            return $product->getId();
        }, $products);

        $diff = array_diff($ids, $foundProductIds);

        if (count($diff) > 0) {
            throw new RepositoryException("Product(s) not found by ids: ". implode(', ', $diff), RepositoryException::NOT_FOUND);
        }

        return $products;
    }

    private function make(array $row): Product
    {
        return new Product(
            $row['id'],
            $row['uuid'],
            $row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            $row['price'],
        );
    }
}
