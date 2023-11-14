<?php

namespace Api\Products\Models;

use App\Documents\Contracts\UploadableInterface;
use App\Documents\Traits\Uploadable;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Image extends Model implements UploadableInterface
{
    use Uploadable;

    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'product_id',
        'uploaded_at',
        'uploaded_by',
    ];

    protected $hidden = [
        'document',
    ];

    protected $table = 'product_images';

    public function getDocumentFilename(): ?string
    {
        $now = Carbon::now();

        return sprintf('%s-%s-%s',
            $now->format('Ymd'),
            $now->valueOf(),
            $this->product->id
        );
    }

    public function getDocumentRootPath(): string
    {
        return sprintf('products/%s', $this->product->id);
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
