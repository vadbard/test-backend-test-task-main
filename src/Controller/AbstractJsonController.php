<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Http\Message\ResponseInterface;

abstract readonly class AbstractJsonController
{
    protected function jsonResponseOk(array $data = [], array $headers = []): ResponseInterface
    {
        $response = new JsonResponse();
        $response->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->withStatus(200);

        foreach ($headers as $headerName => $headerValue) {
            $response->withHeader($headerName, $headerValue);
        }

        $output = [
            'data' => $data,
        ];

        return $response->getBody()->write($this->makeJson($output));
    }

    protected function jsonResponseError(string $message, array $errorData = [], $headers = []): ResponseInterface
    {
        $response = new JsonResponse();
        $response->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->withStatus(200);

        foreach ($headers as $headerName => $headerValue) {
            $response->withHeader($headerName, $headerValue);
        }

        $output = [
            'message' => $message,
            'data' => $errorData,
        ];

        return $response->getBody()->write($this->makeJson($output));
    }

    protected function jsonRequestBody(RequestInterface $request): array
    {
        return json_decode($request->getBody()->getContents(), true);
    }

    private function makeJson(array $array): string
    {
        return json_encode(
            $array,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }
}
