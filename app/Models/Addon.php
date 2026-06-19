<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $table = 'addons';

    protected $fillable = [
        'name',
        'slug',
        'version',
        'route_name',
        'icon',
        'menu_location',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function isEnabled(): bool
    {
        return $this->is_active === true;
    }

    public function isDisabled(): bool
    {
        return !$this->is_active;
    }

    public function getIcon(): string
    {
        return $this->icon ?: 'fa-solid fa-puzzle-piece';
    }

    public function hasRoute(): bool
    {
        return !empty($this->route_name);
    }
}
