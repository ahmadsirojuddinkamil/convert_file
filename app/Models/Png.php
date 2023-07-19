<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Png extends Model
{
    use HasFactory;

    protected $fillable = [
        'jpg_id',
        'uuid',
        'unique_id',
        'file',
        'name',
    ];

    // Relationships
    public function jpgs()
    {
        return $this->belongsToMany(Jpg::class, 'jpg_id');
    }

    // Query
    public function scopeFindPngByUuid($query, $saveUuid)
    {
        return $query->where('uuid', $saveUuid);
    }

    public function scopeFindPngByUniqueId($query, $saveUuid)
    {
        return $query->where('unique_id', $saveUuid);
    }
}
