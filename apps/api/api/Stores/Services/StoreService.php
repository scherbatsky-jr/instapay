<?php

namespace Api\Stores\Services;

use Api\Stores\Repositories\StoreRepository;
use App\AbstractEntityService;

class StoreService extends AbstractEntityService
{
    public function __construct(StoreRepository $repository) {
        $this->repository = $repository;
    }
}
