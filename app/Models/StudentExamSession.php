<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; // Import HasOne
use Carbon\Carbon;

class StudentExamSession extends Model
{
    protected $fillable = [
        'user_id', 
        'exam_id', 
        'status', 
        'total_questions',
        'completed_questions', 
        'correct_answers', 
        'score',
        'progress_percentage', 
        'start_time', 
        'end_time',
        'certificate_issued_at',
        'last_activity_at',
        'risk_score',
        'risk_level',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'certificate_issued_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'progress_percentage' => 'float',
        'risk_score' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function result(): HasOne
    {
        return $this->hasOne(Result::class, 'exam_attempt_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(StudentExamAnswer::class);
    }

    public function getIsPublishedAttribute(): bool
    {
        if (!$this->exam) return false;

        if (!$this->exam->is_paid) {
            return true;
        }

        if (is_null($this->exam->result_date)) {
            return false; 
        }

        return Carbon::now()->greaterThanOrEqualTo($this->exam->result_date);
    }

    public function getPercentageAttribute()
    {
        return $this->progress_percentage;
    }

    public function getIsPassedAttribute(): bool
    {
        if (!$this->exam) return false;
        return $this->progress_percentage >= $this->exam->pass_percentage;
    }

    public function getGradeAttribute(): string
    {
        return $this->is_passed ? 'Pass' : 'Fail';
    }
}