<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exam_id',
        'exam_attempt_id',
        'total_questions',
        'correct_answers',
        'total_score',
        'obtained_score',
        'percentage',
        'is_passed',
        'grade',
        'certificate_issued_at'
    ];

    protected $casts = [
        'is_passed'       => 'boolean',
        'certificate_issued_at' => 'datetime',
        'percentage'      => 'float',
        'obtained_score'  => 'float',
        'total_score'     => 'float',
        'total_questions' => 'integer',
        'correct_answers' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function attempt()
    {
        return $this->belongsTo(StudentExamSession::class, 'exam_attempt_id');
    }
}