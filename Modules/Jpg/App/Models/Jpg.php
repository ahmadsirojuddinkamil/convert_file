<?php

namespace Modules\Jpg\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Jpg\Database\factories\JpgFactory;
use Modules\Pdf\App\Models\Pdf;
use Modules\Png\App\Models\Png;

class Jpg extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'png_uuid',
        'pdf_uuid',
        'uuid',
        'owner',
        'file',
        'name',
        'created_at',
    ];

    // protected static function newFactory(): JpgFactory
    // {
    //     return JpgFactory::new();
    // }

    // Relationships
    public function pngs()
    {
        return $this->hasMany(Png::class, 'jpg_uuid', 'uuid');
    }

    public function pdfs()
    {
        return $this->hasMany(Pdf::class, 'jpg_uuid', 'uuid');
    }

    // Query
    public function scopeFindJpgByUuid($query, $saveUuid)
    {
        return $query->where('uuid', $saveUuid);
    }
}
