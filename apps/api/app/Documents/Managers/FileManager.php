<?php

namespace App\Documents\Managers;

use App\Services\S3FileManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class FileManager extends S3FileManager
{
    public function __construct()
    {
        $this->linkTtl = config('filesystems.disks.documents.link_ttl');
        $this->root = config('filesystems.disks.documents.root');
    }

    protected function getBucket()
    {
        return config('filesystems.disks.documents.bucket');
    }

    protected function getStorageDisk(): Filesystem
    {
        return Storage::disk('documents');
    }
}
