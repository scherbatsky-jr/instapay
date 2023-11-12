<?php

namespace Api\Products\Services;

use Api\Products\Repositories\ProductRepository;
use App\AbstractEntityService;

class ProductService extends AbstractEntityService
{
    public function __construct(ProductRepository $repository) {
        $this->repository = $repository;
    }
}
