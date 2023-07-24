<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    use HasFactory;

    protected $fillable = [
        'jpg_id',
        'uuid',
        'unique_id',
        'file',
        'name',
    ];

    // Relasi
    public function jpgs()
    {
        return $this->belongsToMany(Jpg::class, 'jpg_id');
    }

    // Query
    public function scopeFindPdfByUuid($query, $saveUuid)
    {
        return $query->where('uuid', $saveUuid);
    }

    public function scopeFindPdfByUniqueId($query, $saveUuid)
    {
        return $query->where('unique_id', $saveUuid);
    }
}
