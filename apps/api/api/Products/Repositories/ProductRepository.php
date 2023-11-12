<?php

namespace Api\Products\Repositories;

use Api\Products\Models\Product;
use App\AbstractEntityRepository;

class ProductRepository extends AbstractEntityRepository
{
    public function getModelClass(): string
    {
        return Product::class;
    }
}
