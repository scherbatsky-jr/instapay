<?php

namespace Api\Stores\Repositories;

use Api\Stores\Models\Store;
use App\AbstractEntityRepository;

class StoreRepository extends AbstractEntityRepository
{
    public function getModelClass(): string
    {
        return Store::class;
    }
}
