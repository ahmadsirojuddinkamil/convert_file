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
        'file',
        'name',
    ];

    // Relationships
    public function pngs()
    {
        return $this->belongsToMany(Png::class, 'png_id');
    }
}
