<?php

namespace App\Documents\Traits;

use App\Documents\Contracts\UploadableInterface;
use App\Documents\Managers\FileManager;
use App\Documents\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;

trait Uploadable
{
    protected $uploadedFile;

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function delete()
    {
        /* @var  UploadableInterface $this */
        $this->checkInstance();

        $deleted = parent::delete();

        if ($deleted) {
            if ($document = $this->getDocument()) {
                try {
                    $document->delete();
                    $this->getFileManager()->delete($document->getPath());
                } catch (\Throwable $error) {
                }
            }
        }

        return $deleted;
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function getDocumentFilename(): ?string
    {
        return null;
    }

    public function getDownloadUrlAttribute(): ?string
    {
        return $this->getDocument()
            ? $this->getFileManager()->downloadUrl(
                $this->getDocument()->getPath(),
                $this->getDocument()->getFileName()
            )
            : null;
    }

    public function getMimeAttribute(): ?string
    {
        return $this->getDocument() ? $this->getDocument()->getMime() : null;
    }

    public function getUploadedFile(): ?UploadedFile
    {
        return $this->uploadedFile;
    }

    public function getUrlAttribute(): ?string
    {
        return $this->getDocument()
            ? $this->getDocument()->isPublic()
                ? $this->getFileManager()->url($this->getDocument()->getPath())
                : $this->getFileManager()->temporaryUrl($this->getDocument()->getPath())
            : null;
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function save(array $options = [])
    {
        /* @var  UploadableInterface $this */
        $this->checkInstance();

        $saved = parent::save($options);

        if ($saved) {
            if ($this->getUploadedFile() instanceof UploadedFile) {
                $filename = $this->getUploadedFile()->getClientOriginalName();

                if ($customFilename = $this->getDocumentFileName()) {
                    $filename = $customFilename;

                    if ($extension = $this->getUploadedFile()->getClientOriginalExtension()) {
                        $filename .= '.'.$extension;
                    }
                }

                $documentData = [
                    'path' => $this->getDocumentRootPath().'/'.$filename,
                    'mime' => $this->getUploadedFile()->getClientMimeType(),
                    'public' => $this->isPublic(),
                ];

                if ($document = $this->getDocument()) {
                    $document->save($documentData);
                } else {
                    $document = Document::query()->create($documentData);
                }

                $this->getFileManager()->upload($document->getPath(), file_get_contents($this->getUploadedFile()), $this->isPublic());

                $this->unsetUploadedFile();

                $this->document_id = $document->id;

                $this->save();

                $this->refresh();
            }
        }

        return $saved;
    }

    public function setUploadedFile(UploadedFile $file): void
    {
        $this->uploadedFile = $file;
    }

    public function unsetUploadedFile(): void
    {
        $this->uploadedFile = null;
    }

    /**
     * @throws \Exception
     */
    protected function checkInstance(): void
    {
        if (!($this instanceof UploadableInterface && $this instanceof Model)) {
            throw new \Exception('Please implement Uploadable contract to: '.get_class($this));
        }
    }

    protected function getFileManager(): FileManager
    {
        return app()->make(FileManager::class);
    }
}
