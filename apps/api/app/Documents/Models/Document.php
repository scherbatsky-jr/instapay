<?php

namespace App\Documents\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public $timestamps = false;

    protected $attributes = [
        'public' => false,
    ];

    protected $casts = [
        'public' => 'bool',
    ];

    protected $fillable = [
        'mime',
        'path',
        'public',
    ];

    protected $table = 'documents';

    public function getFileName(): ?string
    {
        $fileName = null;

        if ($this->path) {
            $fileName = basename($this->getPath());
        }

        return $fileName;
    }

    public function getMime(): ?string
    {
        return $this->mime;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function isPublic()
    {
        return $this->public;
    }
}
