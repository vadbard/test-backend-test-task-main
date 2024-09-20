<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Exception\UseCase;

class UseCaseException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null,
                                public array $context = [],
                                public bool $writeLog = true)
    {
        parent::__construct($message, $code, $previous);
    }
}
