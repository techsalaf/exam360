@extends('layouts.admin')

@section('title', __('extra.update_title'))

@push('styles')
    <link href="{{ asset('assets/css/admin-system-info.css') }}" rel="stylesheet">
@endpush

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('extra.update_title') }}</h1>
            <p class="text-muted small mb-0">{{ __('extra.update_subtitle') }}</p>
        </div>
        <div>
            <a href="{{ route('admin.extra.update') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fa-solid fa-rotate-right me-2"></i>{{ __('extra.btn_check') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        
        <div class="col-lg-6">
            <div class="sys-card h-100 d-flex flex-column justify-content-center text-center py-5">
                <div class="mb-4">
                    <div class="update-icon-wrapper bg-green-subtle text-green">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </div>
                
                <h2 class="h3 fw-bold text-dark mb-2">{{ __('extra.up_to_date') }}</h2>
                <p class="text-muted mb-4">
                    {{ __('extra.current_ver') }} 
                    <span class="badge bg-dark rounded-pill px-3 py-2 ms-1">v{{ $version }}</span>
                </p>
                
                <div>
                    <div class="d-inline-block bg-light px-4 py-2 rounded-3 border">
                        <small class="text-muted">
                            <i class="fa-regular fa-clock me-1"></i> 
                            {{ __('extra.last_checked') }} {{ $lastChecked->format('M d, Y h:i A') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="sys-card h-100">
                <div class="sys-card-header border-0 pb-0">
                    <h3 class="sys-title">
                        <span class="sys-icon"><i class="fa-solid fa-cloud-arrow-up"></i></span>
                        {{ __('Manual Update') }}
                    </h3>
                </div>

                <div class="p-3">
                    <p class="text-muted small mb-4">
                        Upload the latest update package provided by the developer. The system will automatically extract files and run database migrations.
                    </p>

                    <form id="updateForm" action="{{ route('admin.extra.update.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="update-upload-zone">
                            <input class="form-control form-control-lg" type="file" id="update_file" name="update_file" accept=".zip" required>
                            <div class="mt-2 text-muted small">Supported file: <strong>.zip</strong> (Max: 100MB)</div>
                        </div>

                        <div class="update-warning-box">
                            <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                            <div>
                                <strong>Important:</strong> 
                                This process will overwrite core system files. Please ensure you have a full database and file backup before proceeding.
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="button" id="btnUpdate" class="btn btn-primary btn-lg">
                                <i class="fa-solid fa-bolt me-2"></i> {{ __('Upload & Update System') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        const updateBtn = document.getElementById('btnUpdate');
        const updateForm = document.getElementById('updateForm');

        if(updateBtn && updateForm) {
            updateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (confirm('Are you strictly sure you want to update? This action cannot be undone and will overwrite system files.')) {
                    updateBtn.disabled = true;
                    updateBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Processing...';
                    updateForm.submit();
                }
            });
        }
    });
</script>
@endpush