<?php

namespace Modules\Pdf\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Jpg\App\Models\Jpg;
use Modules\Pdf\Database\factories\PdfFactory;
use Modules\Pdf\Database\Factories\PdfOwnerFactory;
use Modules\Png\App\Models\Png;

class Pdf extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'jpg_uuid',
        'png_uuid',
        'uuid',
        'owner',
        'file',
        'name',
        'preview',
    ];

    protected static function pdfOwnerFactory(): PdfOwnerFactory
    {
        return PdfOwnerFactory::new();
    }

    // Relationships
    public function jpgs()
    {
        return $this->hasMany(Jpg::class, 'pdf_uuid', 'uuid');
    }

    public function pngs()
    {
        return $this->hasMany(Png::class, 'pdf_uuid', 'uuid');
    }
}
