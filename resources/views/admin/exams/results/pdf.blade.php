<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Exam Result #{{ $result->id }}</title>
    <style>
        /* General Layout */
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1e293b; font-size: 14px; margin: 0; padding: 0; }
        .container { width: 100%; padding: 20px; }
        
        /* Header */
        .header { width: 100%; padding-bottom: 20px; border-bottom: 2px solid #059669; margin-bottom: 30px; }
        .company-name { font-size: 24px; font-weight: bold; color: #059669; text-transform: uppercase; }
        .report-title { font-size: 18px; color: #64748b; text-align: right; }
        
        /* Logo Styling */
        .logo-box { display: block; height: 30px; text-align: left; }
        .logo-box img { max-height: 30px; width: auto; }
        
        /* Info Grid */
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-table td { padding: 5px 0; vertical-align: top; }
        .label { color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; display: block; }
        .value { font-weight: bold; font-size: 15px; color: #0f172a; }
        
        /* Score Card */
        .score-container { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 30px; text-align: center; }
        .score-badge { display: inline-block; padding: 8px 16px; border-radius: 50px; font-weight: bold; font-size: 14px; margin-bottom: 10px; }
        .pass { background-color: #ecfdf5; color: #059669; border: 1px solid #059669; }
        .fail { background-color: #fef2f2; color: #dc2626; border: 1px solid #dc2626; }
        .big-score { font-size: 48px; font-weight: 800; color: #1e293b; line-height: 1; }
        
        /* Stats Grid */
        .stats-table { width: 100%; text-align: center; margin-bottom: 40px; border-collapse: separate; border-spacing: 10px; }
        .stats-table td { width: 25%; }
        .stat-box { background: #fff; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; }
        .stat-title { font-size: 11px; text-transform: uppercase; color: #64748b; margin-bottom: 5px; }
        .stat-val { font-size: 18px; font-weight: bold; color: #0f172a; }

        /* Questions Table */
        .section-title { font-size: 16px; font-weight: bold; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 15px; }
        .q-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .q-table th { text-align: left; background: #f1f5f9; padding: 8px; color: #64748b; font-size: 11px; text-transform: uppercase; }
        .q-table td { padding: 10px 8px; border-bottom: 1px solid #f1f5f9; }
        
        .correct { color: #059669; font-weight: bold; }
        .incorrect { color: #dc2626; font-weight: bold; }
        .neutral { color: #64748b; }

        /* Footer */
        .footer { margin-top: 50px; text-align: center; color: #94a3b8; font-size: 11px; border-top: 1px solid #e2e8f0; padding-top: 20px; }
    </style>
</head>
<body>

    <div class="container">
        
        <!-- Header -->
        <table class="header">
            <tr>
                <td width="50%" style="vertical-align: top;">
                    @if($logoBase64)
                        <div class="logo-box">
                            <img src="{{ $logoBase64 }}" alt="{{ $settings['app_name'] ?? 'ZiExam AI' }}">
                        </div>
                    @else
                        <span class="company-name">{{ $settings['app_name'] ?? 'ZIEXAM AI' }}</span>
                    @endif
                </td>
                <td width="50%" class="report-title">
                    OFFICIAL PERFORMANCE REPORT<br>
                    <span style="font-size: 12px; font-weight: normal;">ID: #{{ $result->id }}</span>
                </td>
            </tr>
        </table>

        <!-- Student & Exam Info -->
        <table class="info-table">
            <tr>
                <td width="33%">
                    <span class="label">Student Name</span>
                    <span class="value">{{ $result->user->name }}</span>
                    <br><br>
                    <span class="label">Email</span>
                    <span class="value">{{ $result->user->email }}</span>
                </td>
                <td width="33%">
                    <span class="label">Exam Title</span>
                    <span class="value">{{ $result->exam->title }}</span>
                    <br><br>
                    <span class="label">Category</span>
                    <span class="value">{{ $result->exam->category->name ?? 'General' }}</span>
                </td>
                <td width="33%" style="text-align: right;">
                    <span class="label">Attempt Date</span>
                    <span class="value">{{ $result->created_at->format('M d, Y') }}</span>
                    <br><br>
                    <span class="label">Duration</span>
                    <span class="value">
                        {{ \Carbon\Carbon::parse($result->start_time)->diffInMinutes(\Carbon\Carbon::parse($result->end_time)) }} Mins
                    </span>
                </td>
            </tr>
        </table>

        <!-- Main Score -->
        <div class="score-container">
            @if($result->is_passed)
                <span class="score-badge pass">PASS</span>
            @else
                <span class="score-badge fail">FAIL</span>
            @endif
            
            <div class="big-score">{{ number_format($result->percentage, 1) }}%</div>
            
            @if(isset($result->grade))
                <div style="margin-top: 5px; color: #64748b; font-weight: bold;">Grade: {{ $result->grade }}</div>
            @endif
        </div>

        <!-- Statistics -->
        <table class="stats-table">
            <tr>
                <td><div class="stat-box"><div class="stat-title">Total Marks</div><div class="stat-val">{{ floatval($result->total_score) }}</div></div></td>
                <td><div class="stat-box"><div class="stat-title">Obtained</div><div class="stat-val">{{ floatval($result->score) }}</div></div></td>
                <td><div class="stat-box"><div class="stat-title">Correct</div><div class="stat-val" style="color: #059669;">{{ floatval($result->correct_answers) }}</div></div></td>
                <td><div class="stat-box"><div class="stat-title">Total Questions</div><div class="stat-val">{{ floatval($result->total_questions) }}</div></div></td>
            </tr>
        </table>

        <!-- Detailed Questions -->
        <div class="section-title">Question Breakdown</div>
        <table class="q-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="45%">Question</th>
                    <th width="25%">Your Answer</th>
                    <th width="20%">Correct Answer</th>
                    <th width="5%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result->answers as $ans)
                    @php
                        $isCorrect = $ans->is_correct;
                        $question = $ans->question;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $question->question_text }}</td>
                        <td class="{{ $isCorrect ? 'correct' : 'incorrect' }}">
                            {{ Str::limit($ans->selected_option_id ?? 'Skipped', 20) }}
                        </td>
                        <td class="correct">{{ Str::limit($question->correct_answer, 20) }}</td>
                        <td>
                            @if($isCorrect)
                                <span style="font-size: 1.1em; color: #059669;">&#10003;</span>
                            @else
                                <span style="font-size: 1.1em; color: #dc2626;">&#10006;</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            Generated on {{ now()->format('M d, Y h:i A') }} • ZiExam AI Assessment Platform
        </div>

    </div>
</body>
</html>