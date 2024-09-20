<?php

namespace Raketa\BackendTestTask;

use Raketa\BackendTestTask\Exception\UseCase\UseCaseException;

class ExceptionHandler
{
    public function __construct(private LoggerInterface $logger) {
        set_exception_handler([$this, 'handle']);
    }

    public function handle(\Throwable $exception): void
    {
        if (! $exception instanceof UseCaseException || $exception->writeLog) {
            $this->log($exception);
        }
    }

    private function log(\Throwable $exception): void
    {
        $result = [
            'message' => $exception->getCode(),
            'class' => get_class($exception),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];

        if ($exception->getPrevious()) {
            $result['previous'] = [
                'message' => $exception->getPrevious()->getCode(),
                'class' => get_class($exception->getPrevious()),
                'code' => $exception->getPrevious()->getCode(),
                'file' => $exception->getPrevious()->getFile(),
                'line' => $exception->getPrevious()->getLine(),
            ];
        }

        $this->logger->error(json_encode($result));
    }
}