@extends('installer.layout')

@section('content')
    <div class="mb-4">
        <h2>3. Database Configuration</h2>
        <p class="text-muted mb-0">Enter your MySQL connection details below. We will test the connection before proceeding.</p>
    </div>

    {{-- 
        Only show this big alert if there is a specific 'db_name' error (Connection Failed).
        Generic errors like "Session expired" will be handled by your global layout or inline.
    --}}
    @if($errors->has('db_name'))
        <div class="alert alert-danger d-flex align-items-start mb-4 shadow-sm border-danger-subtle" role="alert">
            <i class="fas fa-exclamation-triangle fs-4 me-3 mt-1"></i>
            <div>
                <h6 class="fw-bold mb-1">Connection Failed</h6>
                <p class="mb-0 small">{{ $errors->first('db_name') }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('install.database') }}" class="row g-4">
        @csrf

        <div class="col-md-6">
            <label class="form-label fw-bold">Database Host</label>
            <input type="text" 
                   name="db_host" 
                   class="form-control @error('db_host') is-invalid @enderror" 
                   value="{{ old('db_host', '127.0.0.1') }}" 
                   placeholder="127.0.0.1" 
                   required>
            @error('db_host')
                <div class="invalid-feedback">{{ $message }}</div>
            @else
                <div class="form-text text-muted">Usually <code>127.0.0.1</code> or <code>localhost</code>.</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">Database Name</label>
            <input type="text" 
                   name="db_name" 
                   class="form-control @error('db_name') is-invalid @enderror" 
                   value="{{ old('db_name') }}" 
                   placeholder="e.g. ziexam_ai" 
                   required>
            @error('db_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @else
                <div class="form-text text-muted">
                    <i class="fas fa-info-circle me-1"></i> Ensure this database exists first.
                </div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">Database Username</label>
            <input type="text" 
                   name="db_username" 
                   class="form-control @error('db_username') is-invalid @enderror" 
                   value="{{ old('db_username') }}" 
                   placeholder="e.g. root" 
                   required>
            @error('db_username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">Database Password</label>
            <input type="password" 
                   name="db_password" 
                   class="form-control @error('db_password') is-invalid @enderror" 
                   value="{{ old('db_password') }}" 
                   placeholder="••••••••">
            @error('db_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">Port</label>
            <input type="number" 
                   name="db_port" 
                   class="form-control @error('db_port') is-invalid @enderror" 
                   value="{{ old('db_port', 3306) }}" 
                   placeholder="3306">
            @error('db_port')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <div class="bg-light border rounded p-3 d-flex align-items-start">
                <i class="fas fa-lightbulb text-warning fs-4 me-3 mt-1"></i>
                <div>
                    <h6 class="fw-bold text-dark mb-1">Shared Hosting Tip (cPanel)</h6>
                    <p class="small text-muted mb-0">
                        If you are using cPanel, database names often include a prefix.<br>
                        Example: <code>username_ziexam</code> instead of just <code>ziexam</code>.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary fw-bold px-4">
                Test Connection & Continue
                <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </div>
    </form>
@endsection