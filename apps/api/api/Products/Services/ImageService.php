<?php

namespace Api\Products\Services;

use Api\Products\Repositories\ImageRepository;
use App\AbstractEntityService;

class ImageService extends AbstractEntityService
{
    protected $leadService;

    public function __construct(ImageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function deleteImages($ids)
    {
        try {
            foreach ($ids as $id) {
                $file = $this->getById($id);

                $file->delete();
            }

            return [
                'success' => true,
            ];
        } catch (\Throwable $error) {
            return [
                'success' => false,
            ];
        }
    }

    public function uploadImages($uploadFiles)
    {
        $files = [];

        foreach ($uploadFiles as $data) {
            $file = $this->getRepository()->create($data);

            $file->setUploadedFile($data['file']);

            $file->save();

            $files[] = $file;
        }

        return $files;
    }
}
