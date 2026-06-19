<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exam_id',
        'started_at',
        'completed_at',
        'score',
        'status',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
        'score'        => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the user who made the attempt.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the exam associated with the attempt.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the answers submitted in this attempt.
     * This fixes the "Call to undefined relationship [answers]" error.
     */
    public function answers()
    {
        return $this->hasMany(ExamAnswer::class, 'exam_attempt_id');
    }

    /**
     * Get the final declared result (if exists).
     */
    public function result()
    {
        return $this->hasOne(Result::class, 'exam_attempt_id');
    }
}