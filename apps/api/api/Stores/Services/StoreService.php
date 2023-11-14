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

    public function stats($id)
    {
        $store = $this->getById($id);

        return [
            'total_orders' => $store->orders()->count(),
            'open' => $store->orders()->where('status', 0)->count(),
            'payment_pending' => $store->orders()->where('status', 1)->count(),
            'payment_success' => $store->orders()->where('status', 2)->count(),
            'shipped' => $store->orders()->where('status', 3)->count(),
            'delivered' => $store->orders()->where('status', 4)->count(),
            'payment_failed' => $store->orders()->where('status', 10)->count(),
        ];
    }
}
