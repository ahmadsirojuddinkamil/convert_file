<?php

namespace Modules\Png\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Png\Database\factories\ExamplePngFactory;

class ExamplePng extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): ExamplePngFactory
    {
        //return ExamplePngFactory::new();
    }
}
