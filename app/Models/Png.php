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
        'file',
        'name',
    ];

    // Relationships
    public function jpgs()
    {
        return $this->belongsToMany(Jpg::class, 'jpg_id');
    }
}
