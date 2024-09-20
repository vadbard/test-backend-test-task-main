<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\UseCase\GetProducts\GetProductsUseCase;
use Raketa\BackendTestTask\View\ProductListView;

final readonly class GetProductsController extends AbstractJsonController
{
    public function __construct(
        private GetProductsUseCase $getProductsUseCase,
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $rawRequest = $this->jsonRequestBody($request);

        $arrayOfDtos = $this->getProductsUseCase->execute($rawRequest['category']);

        $view = new ProductListView($arrayOfDtos);

        return $this->jsonResponseOk($view->toArray());
    }
}
