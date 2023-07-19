<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jpg extends Model
{
    use HasFactory;

    protected $fillable = [
        'png_id',
        'uuid',
        'unique_id',
        'file',
        'name',
    ];

    // Relationships
    public function pngs()
    {
        return $this->belongsToMany(Png::class, 'png_id');
    }

    // Query
    public function scopeFindJpgByUuid($query, $saveUuid)
    {
        return $query->where('uuid', $saveUuid);
    }

    public function scopeFindJpgByUniqueId($query, $saveUuid)
    {
        return $query->where('unique_id', $saveUuid);
    }
}
