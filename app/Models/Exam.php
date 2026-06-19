<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'plan_id',
        'duration_minutes',
        'pass_percentage',
        'total_marks',
        'start_date',
        'end_date',
        'result_date',
        'description',
        'instructions',
        'banner',
        'is_active',
        'is_paid',
        'price',
        'allow_retake',
        'has_negative_marking',
        'negative_mark_value'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_paid' => 'boolean',
        'allow_retake' => 'boolean',
        'has_negative_marking' => 'boolean',
        'price' => 'decimal:2',
        'negative_mark_value' => 'decimal:2',
        'pass_percentage' => 'decimal:2',
        'total_marks' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'result_date' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(StudentExamSession::class);
    }
    
    public function getBannerUrlAttribute()
    {
        return $this->banner
            ? asset('storage/' . $this->banner)
            : null;
    }
}