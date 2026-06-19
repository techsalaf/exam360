@extends('installer.layout')

@section('content')

    {{-- =========================
        INSTALLATION FAILED
    ========================== --}}
    @if (isset($error))
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle mb-3"
                 style="width: 80px; height: 80px;">
                <i class="fas fa-times-circle" style="font-size: 40px;"></i>
            </div>
            <h2 class="text-danger">Installation Failed</h2>
            <p class="text-muted">
                Something went wrong during the installation process.
            </p>
        </div>

        <div class="alert alert-danger border-danger-subtle d-flex align-items-start p-3 rounded-3">
            <i class="fas fa-exclamation-triangle fs-4 me-3 mt-1"></i>
            <div class="w-100 overflow-hidden">
                <h6 class="fw-bold mb-1">Error Details</h6>
                <div class="bg-white p-2 rounded border border-danger-subtle mt-2">
                    <code class="d-block text-danger small"
                          style="white-space: pre-wrap; word-break: break-word;">
                        {{ $error }}
                    </code>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="{{ route('install.database') }}"
               class="btn btn-outline-secondary fw-bold px-4">
                <i class="fas fa-arrow-left me-2"></i> Back to Database
            </a>

            <a href="{{ request()->fullUrl() }}"
               class="btn btn-danger fw-bold px-4">
                <i class="fas fa-redo me-2"></i> Retry Installation
            </a>
        </div>

    {{-- =========================
        INSTALLATION SUCCESS
    ========================== --}}
    @else
        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mb-3"
                 style="width: 80px; height: 80px;">
                <i class="fas fa-check-circle" style="font-size: 42px;"></i>
            </div>
            <h2 class="text-dark mb-2">Installation Complete!</h2>
            <p class="text-muted">
                <strong>Ziexam AI</strong> has been successfully installed and configured.
            </p>
        </div>

        {{-- =========================
            ADMIN CREDENTIALS
        ========================== --}}
        <div class="bg-light border rounded-3 p-4 mb-4 position-relative overflow-hidden">

            <div class="position-absolute end-0 bottom-0 text-success"
                 style="font-size: 110px; opacity: 0.05; transform: rotate(-15deg) translate(20px, 10px); pointer-events: none;">
                <i class="fas fa-shield-alt"></i>
            </div>

            <div class="position-relative">
                <h6 class="text-uppercase text-success fw-bold border-bottom pb-2 mb-3"
                    style="letter-spacing: 1px;">
                    <i class="fas fa-user-shield me-1"></i> Admin Credentials
                </h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold text-uppercase">Login Email</label>
                        <div class="d-flex align-items-center mt-1">
                            <i class="fas fa-envelope me-2 text-dark"></i>
                            <span class="fs-5 fw-bold text-dark me-2"
                                  id="adminEmailText">
                                {{ $appConfig['admin_email'] }}
                            </span>

                            <button class="btn btn-sm btn-light border shadow-sm text-success"
                                    onclick="copyToClipboard('{{ $appConfig['admin_email'] }}', this)"
                                    title="Copy Email">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="small text-muted fw-bold text-uppercase">Password</label>
                        <div class="d-flex align-items-center mt-1">
                            <i class="fas fa-lock me-2 text-dark"></i>
                            <span class="fs-5 fw-bold text-dark">••••••••••••</span>
                        </div>
                        <small class="text-muted">
                            (The password set during installation)
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- =========================
            STORAGE NOTICE (ENVATO SAFE)
        ========================== --}}
        @if (isset($symlinkSuccess) && $symlinkSuccess !== true)
            <div class="alert alert-warning border-warning-subtle d-flex align-items-start small mb-4">
                <i class="fas fa-info-circle me-2 fs-5 mt-1"></i>
                <div>
                    <strong>Storage Notice:</strong><br>
                    Your server does not support symbolic links. The installer automatically copied
                    required files instead.
                    <br>
                    <span class="text-muted">
                        Image uploads will work normally on shared hosting.
                        No action is required.
                    </span>
                </div>
            </div>
        @endif

        {{-- =========================
            FINAL ACTION
        ========================== --}}
        <div class="d-flex flex-column align-items-center mt-4">
            <a href="{{ url('/login') }}"
               class="btn btn-primary btn-lg fw-bold px-5 py-3 shadow-sm">
                Go to Dashboard
                <i class="fas fa-rocket ms-2"></i>
            </a>

            <p class="small text-muted mt-3 mb-0">
                <i class="fas fa-lock me-1"></i>
                For security reasons, the installer has been locked.
            </p>
        </div>
    @endif

    {{-- =========================
        COPY TO CLIPBOARD SCRIPT
    ========================== --}}
    <script>
        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const icon = btn.querySelector('i');
                const originalClass = icon.className;

                icon.className = 'fas fa-check text-success';

                setTimeout(() => {
                    icon.className = originalClass;
                }, 2000);
            }).catch(err => {
                console.error('Clipboard copy failed:', err);
            });
        }
    </script>

@endsection
