<?php

namespace Api\Products\Repositories;

use Api\Products\Models\Image;
use Api\Products\Services\ProductService;
use App\AbstractEntityRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ImageRepository extends AbstractEntityRepository
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function create(array $data, array $fields = []): Model
    {
        $user = $this->getUser();

        $entity = $this->getModel();

        $entity->fill(array_merge($data, [
            'uploaded_by' => $user->id,
            'uploaded_at' => Carbon::now(),
        ]));

        $entity->save();

        return $entity;
    }

    public function getModelClass(): string
    {
        return Image::class;
    }

    protected function getLeadService()
    {
        return $this->productService;
    }
}
