<?php

namespace Api\Products\Models;

use App\Documents\Contracts\UploadableInterface;
use App\Documents\Traits\Uploadable;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Image extends Model implements UploadableInterface
{
    use SingleTableInheritanceTrait;
    use Uploadable;

    protected $fillable = [
        'product_id',
        'name',
        'path'
    ];

    protected $table = 'product_images';

    public function getDocumentFilename(): ?string
    {
        $now = Carbon::now();

        return sprintf('%s-%s-%s',
            $now->format('Ymd'),
            $now->valueOf(),
            $this->lead->id
        );
    }

    public function getDocumentRootPath(): string
    {
        return sprintf('leads/%s', $this->lead->id);
    }

    public function isPublic(): bool
    {
        return false;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
