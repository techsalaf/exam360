<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_id',
        'subject',
        'category',
        'priority',
        'status',
    ];

    // Automatically generate a unique Ticket ID (e.g., TKT-123456) on creation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ticket->ticket_id = 'TKT-' . strtoupper(Str::random(6));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

    // Helper to get the last reply for the admin index list
    public function latestReply()
    {
        return $this->hasOne(TicketReply::class)->latestOfMany();
    }
}