<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Exception\Infrastructure\DataStorageException;
use Redis;
use RedisException;

class RedisDataStorage implements DataStorageInterface
{
    private Redis $redis;

    public function __construct()
    {
        $this->redis = RedisConnector::getInstance();
    }

    /**
     * @throws DataStorageException
     */
    public function get(string $key): string
    {
        try {
            return $this->redis->get($key);
        } catch (RedisException $e) {
            throw new DataStorageException('Redis error', 0, $e);
        }
    }

    /**
     * @throws DataStorageException
     */
    public function set(string $key, string $value, int $seconds = null): void
    {
        try {
            if ($seconds === null) {
                $this->redis->set($key, $value, ['KEEPTTL' => true]);
            } else {
                $this->redis->set($key, $value, ['EX' => $seconds]);
            }
        } catch (RedisException $e) {
            throw new DataStorageException('Redis error', 0, $e);
        }
    }

    /**
     * @throws DataStorageException
     */
    public function has($key): bool
    {
        try {
            return $this->redis->exists($key);
        } catch (RedisException $e) {
            throw new DataStorageException('Redis error', 0, $e);
        }
    }
}
