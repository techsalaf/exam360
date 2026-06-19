<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'transaction_id',
        'amount',
        'currency',
        'gateway',
        'status',
        'type',
        'start_date',
        'end_date',
        'gateway_response',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'amount'           => 'decimal:2',
        'start_date'       => 'datetime',
        'end_date'         => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function scopeSubscription(Builder $query): void
    {
        $query->where('type', 'subscription');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('type', 'subscription')
              ->whereIn('status', ['success', 'approved', 'paid'])
              ->where('end_date', '>', now());
    }
}