<?php

namespace Modules\Monitoring\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Monitoring\Database\factories\UserMonitoringFactory;

class UserMonitoring extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
    ];

    protected static function newFactory(): UserMonitoringFactory
    {
        return UserMonitoringFactory::new();
    }
}
