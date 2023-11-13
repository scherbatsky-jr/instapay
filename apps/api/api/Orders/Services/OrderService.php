<?php

namespace Api\Orders\Services;

use Api\Orders\Repositories\OrderRepository;
use App\AbstractEntityService;

class OrderService extends AbstractEntityService
{
    public function __construct(OrderRepository $repository) {
        $this->repository = $repository;
    }

    public function create($data, $fields = []) {
        $data['status'] = 0;

        $items = $data['items'];

        unset($data['items']);

        info($data);

        $order = $this->getRepository()->create($data, $fields);

        $order->items()->createMany($items);

        $order->save();

        return $order;
    }
}
