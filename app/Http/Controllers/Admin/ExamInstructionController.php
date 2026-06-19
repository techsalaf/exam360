<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamInstructionController extends Controller
{
    public function edit($examId)
    {
        $exam = Exam::findOrFail($examId);
        return view('admin.exams.instructions.edit', compact('exam'));
    }

    public function update(Request $request, $examId)
    {
        $request->validate([
            'instructions' => 'nullable|string'
        ]);

        $exam = Exam::findOrFail($examId);
        
        $exam->update([
            'instructions' => $request->instructions
        ]);

        return redirect()->route('admin.exams.questions', $exam->id)
                         ->with('success', 'Exam instructions updated successfully.');
    }
}