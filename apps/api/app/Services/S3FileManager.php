<?php

namespace App\Services;

use Illuminate\Contracts\Filesystem\Filesystem;

abstract class S3FileManager
{
    protected $linkTtl;
    protected $root = null;

    public function delete($path)
    {
        if ($path && $this->exists($path)) {
            $this->getStorageDisk()->delete($path);
        }
    }

    public function downloadUrl($path, $fileName): ?string
    {
        return $this->getStorageDisk()->temporaryUrl(
            $path,
            now()->addSeconds($this->linkTtl),
            [
                'ResponseContentDisposition' => 'attachment; filename ="'.$fileName.'"',
            ]
        );
    }

    public function exists($path)
    {
        return $this->getStorageDisk()->exists($path);
    }

    public function temporaryUrl($path): ?string
    {
        return $this->getStorageDisk()->temporaryUrl(
            $path, now()->addSeconds($this->linkTtl)
        );
    }

    public function upload($path, $file, $public = false)
    {
        if ($public) {
            $this->getStorageDisk()->put($path, $file, 'public');
        } else {
            $this->getStorageDisk()->put($path, $file);
        }
    }

    public function url($path): ?string
    {
        return $this->getStorageDisk()->url($path);
    }

    abstract protected function getBucket();

    abstract protected function getStorageDisk(): Filesystem;
}
