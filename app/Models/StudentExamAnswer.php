<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentExamAnswer extends Model
{
    protected $fillable = [
        'student_exam_session_id',
        'user_id',
        'exam_id',
        'question_id',
        'selected_option_id',
        'is_correct',
        'marks_awarded',
        'is_marked_for_review'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'is_marked_for_review' => 'boolean',
        'marks_awarded' => 'decimal:2',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(StudentExamSession::class, 'student_exam_session_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}