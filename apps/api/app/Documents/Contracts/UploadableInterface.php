<?php

namespace App\Documents\Contracts;

use App\Documents\Models\Document;
use Illuminate\Http\UploadedFile;

interface UploadableInterface
{
    public function getDocument(): ?Document;

    public function getDocumentFilename(): ?string;

    public function getDocumentRootPath(): string;

    public function getUploadedFile(): ?UploadedFile;

    public function isPublic(): bool;

    public function unsetUploadedFile(): void;
}
