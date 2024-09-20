<?php

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Exception\Infrastructure\DataStorageException;

interface DataStorageInterface
{
    public function set(string $key, string $value, int $seconds): void;

    /**
     * @param string $key
     * @return string|false
     * @throws DataStorageException
     */
    public function get(string $key): string|false;

    public function has(string $key): bool;
}