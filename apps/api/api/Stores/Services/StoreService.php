<?php

namespace Api\Stores\Services;

use Api\Stores\Repositories\StoreRepository;
use App\AbstractEntityService;

class StoreService extends AbstractEntityService
{
    public function __construct(StoreRepository $repository) {
        $this->repository = $repository;
    }

    public function create($data, $fields = []) {
        $user = $this->getUser();

        $data['user_id'] = $user->id;

        return $this->getRepository()->create($data);
    }
}
