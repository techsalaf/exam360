<!DOCTYPE html>
<html>
<head>
    <title>Student Results Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .status-passed { color: green; font-weight: bold; }
        .status-failed { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Student Results Report</h2>
        <p>Generated on: {{ now()->format('M d, Y H:i A') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Email</th>
                <th>Exam</th>
                <th>Score</th>
                <th>Stats</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $r)
            <tr>
                <td>{{ $r->user->name }}</td>
                <td>{{ $r->user->email }}</td>
                <td>{{ $r->exam->title }}</td>
                <td>{{ $r->progress_percentage }}%</td>
                <td>{{ $r->correct_answers }}/{{ $r->total_questions }}</td>
                <td class="{{ $r->is_passed ? 'status-passed' : 'status-failed' }}">
                    {{ $r->is_passed ? 'PASSED' : 'FAILED' }}
                </td>
                <td>{{ $r->updated_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>