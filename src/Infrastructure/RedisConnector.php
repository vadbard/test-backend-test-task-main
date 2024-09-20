<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Exception\Infrastructure\ConnectorException;
use Redis;

class RedisConnector
{
    private static ?Redis $instance = null;

    private function __construct(
        string $host,
        int $port = 6379,
        ?string $password = null,
        ?int $dbindex = null,
    )
    {
        if ($dbindex > 15) {
            throw new ConnectorException('Invalid dbindex');
        }

        self::$instance = new Redis();
        self::$instance->connect(
            host: $host,
            port: $port,
            persistent_id: 'cart',
        );

        self::$instance->auth([$password]);

        $config = self::$instance->config('GET', 'readonly');
        if ($config[1] !== '0') {
            throw new ConnectorException('Redis is in readonly mode');
        }

        if ($dbindex !== null) {
            self::$instance->select($dbindex);
        }
    }

    public static function getInstance() : Redis
    {
        if (self::$instance === null) {
            new self(
                host: get_env('REDIS_HOST'),
                port: get_env('REDIS_HOST'),
                password: get_env('REDIS_PASSWORD'),
                dbindex: get_env('REDIS_DBINDEX'),
            );
        }
        return self::$instance;
    }
}
