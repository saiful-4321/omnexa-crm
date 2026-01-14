<?php

namespace App\Modules\Tenant\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Tenant extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'subdomain',
        'custom_domain',
        'status',
        'trial_ends_at',
        'is_active',
        'logo',
        'favicon',
        'primary_color',
        'secondary_color',
        'branding_config',
        'locale',
        'timezone',
        'feature_flags',
        'storage_limit',
        'storage_usage',
        'user_limit',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'branding_config' => 'array',
        'feature_flags' => 'array',
        'trial_ends_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
