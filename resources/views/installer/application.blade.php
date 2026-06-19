@extends('installer.layout')

@section('content')
    <div class="mb-4">
        <h2>4. Application & Admin Setup</h2>
        <p class="text-muted mb-0">Configure your global settings and create the Super Admin account.</p>
    </div>
    
    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-start mb-4">
            <i class="fas fa-exclamation-circle fs-4 me-3 mt-1"></i>
            <div>
                <h6 class="fw-bold mb-1">Configuration Error</h6>
                <p class="mb-0 small">{{ $errors->first() }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('install.application') }}" class="row g-4">
        @csrf

        @if(session()->has('db_config'))
            @foreach(session('db_config') as $key => $value)
                <input type="hidden" name="db_{{ $key }}" value="{{ $value }}">
            @endforeach
        @endif

        <div class="col-12">
            <h6 class="text-uppercase text-success fw-bold border-bottom pb-2 mb-3" style="letter-spacing: 1px;">
                <i class="fas fa-cog me-1"></i> General Settings
            </h6>
        </div>

        <div class="col-md-6">
            <label class="form-label">App Name</label>
            <input type="text" name="app_name" class="form-control" value="{{ old('app_name', 'Ziexam AI') }}" placeholder="e.g. Ziexam AI" required>
        </div>
        
        <div class="col-md-6">
            <label class="form-label">App URL</label>
            <input type="url" name="app_url" class="form-control" value="{{ old('app_url', $defaultAppUrl) }}" placeholder="https://domain.com" required>
            <div class="form-text text-muted small">
                <i class="fas fa-info-circle me-1"></i> Include <code>http://</code> or <code>https://</code>.
            </div>
        </div>

        <div class="col-md-6">
            <label class="form-label">Default Timezone</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="far fa-clock"></i></span>
                <select name="timezone" class="form-select" required>
                    <option value="UTC">UTC (Universal)</option>
                    <option value="America/New_York" selected>New York (EST)</option>
                    <option value="America/Los_Angeles">Los Angeles (PST)</option>
                    <option value="Europe/London">London (GMT)</option>
                    <option value="Europe/Berlin">Berlin (CET)</option>
                    <option value="Asia/Dubai">Dubai (GST)</option>
                    <option value="Asia/Kolkata">Kolkata (IST)</option>
                    <option value="Asia/Singapore">Singapore (SGT)</option>
                    <option value="Australia/Sydney">Sydney (AEST)</option>
                    <option value="Pacific/Auckland">Auckland (NZST)</option>
                </select>
            </div>
        </div>
        
        <div class="col-md-6">
            <label class="form-label">Default Currency</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-dollar-sign"></i></span>
                <select name="currency" class="form-select" required>
                    {{-- Value is the Currency Code, Display is Symbol & Code --}}
                    <option value="USD" selected>USD ($)</option>
                    <option value="EUR">EUR (€)</option>
                    <option value="GBP">GBP (£)</option>
                    <option value="INR">INR (₹)</option>
                    <option value="CAD">CAD (C$)</option>
                    <option value="AUD">AUD (A$)</option>
                    <option value="JPY">JPY (¥)</option>
                    <option value="CNY">CNY (¥)</option>
                    <option value="BRL">BRL (R$)</option>
                    <option value="TRY">TRY (₺)</option>
                </select>
            </div>
        </div>

        <div class="col-12 mt-5">
            <h6 class="text-uppercase text-success fw-bold border-bottom pb-2 mb-3" style="letter-spacing: 1px;">
                <i class="fas fa-user-circle me-1"></i> Super Admin Account
            </h6>
        </div>
        
        <div class="col-md-6">
            <label class="form-label">Admin Email</label>
            <input type="email" name="admin_email" class="form-control" value="{{ old('admin_email') }}" placeholder="admin@domain.com" required>
        </div>
        
        <div class="col-md-6">
            <label class="form-label">Confirm Email</label>
            <input type="email" name="admin_email_confirmation" class="form-control" placeholder="Confirm email address" required>
        </div>

        <div class="col-md-12">
            <label class="form-label">Admin Password</label>
            <div class="input-group">
                <input type="password" name="admin_password" id="adminPassword" class="form-control" placeholder="••••••••" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility()" title="Toggle Password">
                    <i class="fas fa-eye" id="passwordIcon"></i>
                </button>
            </div>
            <div class="form-text small text-muted">Minimum 6 characters required.</div>
        </div>

        <div class="col-12 mt-5">
            <div class="demo-data-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="demo-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-1 fw-bold text-dark">Install Demo Data?</h6>
                            <p class="mb-0 text-muted small">
                                Populate the database with sample exams, users, and questions. 
                                <br><span class="text-warning fw-bold"><i class="fas fa-exclamation-triangle me-1"></i> Recommended for testing.</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="form-check form-switch ms-3">
                        <input class="form-check-input custom-switch" type="checkbox" role="switch" id="seedDemoData" name="seed_demo_data" value="1">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('install.database') }}" class="btn btn-link text-decoration-none text-muted fw-bold">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
            
            <button type="submit" class="btn btn-primary btn-lg fw-bold px-4">
                Finalize Installation
                <i class="fas fa-rocket ms-2"></i>
            </button>
        </div>
    </form>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('adminPassword');
            const icon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endsection