<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name',
        'code',
        'flag',
        'is_rtl',
        'is_active_front',
        'is_active_admin',
        'is_default',
    ];

    protected $casts = [
        'is_rtl' => 'boolean',
        'is_active_front' => 'boolean',
        'is_active_admin' => 'boolean',
        'is_default' => 'boolean',
    ];
}