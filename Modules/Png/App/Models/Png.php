<?php

namespace Modules\Png\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Jpg\App\Models\Jpg;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\Database\Factories\PngOwnerFactory;

class Png extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'jpg_uuid',
        'pdf_uuid',
        'uuid',
        'owner',
        'file',
        'name',
        'created_at',
    ];

    protected static function pngOwnerFactory(): PngOwnerFactory
    {
        return PngOwnerFactory::new();
    }

    // Relationships
    public function jpgs()
    {
        return $this->hasMany(Jpg::class, 'png_uuid', 'uuid');
    }

    public function pdfs()
    {
        return $this->hasMany(Pdf::class, 'png_uuid', 'uuid');
    }

    // Query
    public function scopeFindPngByUuid($query, $saveUuidFromCall)
    {
        return $query->where('uuid', $saveUuidFromCall);
    }
}
