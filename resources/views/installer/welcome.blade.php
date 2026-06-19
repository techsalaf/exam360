@extends('installer.layout')

@section('content')
    <div class="welcome-container text-center">
        
        <div class="mb-5">
            <div class="hero-icon-wrapper">
                <i class="fas fa-rocket"></i>
            </div>
            <h2 class="mt-4 mb-2">Welcome to Ziexam AI</h2>
            <p class="text-muted fs-6">
                Thank you for choosing Ziexam AI. This wizard will guide you through the installation process. 
                <br class="d-none d-md-block">
                The setup is automated and typically takes less than <strong>2 minutes</strong>.
            </p>
        </div>

        <div class="text-start mb-5">
            <p class="fw-bold text-dark small text-uppercase mb-3" style="letter-spacing: 1px;">
                <i class="fas fa-tasks me-1 text-success"></i> 
                Prerequisites
            </p>
            
            <div class="prerequisite-card">
                <div class="prereq-item">
                    <div class="icon-box success">
                        <i class="fas fa-database"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Database Details</h6>
                        <span class="text-muted small">Host, Database Name, User & Password</span>
                    </div>
                </div>

                <div class="prereq-item">
                    <div class="icon-box warning">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Admin Account</h6>
                        <span class="text-muted small">Email & Password for Super Admin</span>
                    </div>
                </div>

                <div class="prereq-item">
                    <div class="icon-box info">
                        <i class="fas fa-server"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Server Compatibility</h6>
                        <span class="text-muted small">PHP 8.1+, BCMath, Ctype, JSON</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3">
            <a href="https://documentations.codezisoft.com/zi-exam/" target="_blank" class="btn btn-outline-secondary fw-bold">
                <i class="fas fa-book me-2"></i> Documentation
            </a>
            
            <a href="{{ route('install.requirements') }}" class="btn btn-primary btn-lg fw-bold px-5">
                Start Setup
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

    </div>
@endsection