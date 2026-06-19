@extends('layouts.admin')

@section('title', __('extra.server_title'))

@push('styles')
    <link href="{{ asset('assets/css/admin-system-info.css') }}" rel="stylesheet">
@endpush

@section('content')

    <div class="mb-4">
        <h1 class="h3 fw-bold text-dark mb-1">{{ __('extra.server_title') }}</h1>
        <p class="text-muted small mb-0">{{ __('extra.server_subtitle') }}</p>
    </div>

    <div class="row g-4">
        
        {{-- PHP Config (Left) --}}
        <div class="col-lg-8">
            <div class="sys-card h-100">
                <div class="sys-card-header">
                    <div class="sys-title">
                        <div class="sys-icon"><i class="fa-brands fa-php"></i></div>
                        {{ __('extra.php_config') }}
                    </div>
                    <span class="sys-badge bg-primary-subtle text-primary">v{{ $serverInfo['php'] }}</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3 text-center border">
                            <div class="text-muted small fw-bold text-uppercase mb-1 fs-07">{{ __('extra.memory_limit') }}</div>
                            <div class="fw-bold text-dark h5 mb-0">{{ $serverInfo['memory_limit'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3 text-center border">
                            <div class="text-muted small fw-bold text-uppercase mb-1 fs-07">{{ __('extra.max_execution') }}</div>
                            <div class="fw-bold text-dark h5 mb-0">{{ $serverInfo['max_execution'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3 text-center border">
                            <div class="text-muted small fw-bold text-uppercase mb-1 fs-07">{{ __('extra.upload_max') }}</div>
                            <div class="fw-bold text-dark h5 mb-0">{{ $serverInfo['upload_max'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3 text-center border">
                            <div class="text-muted small fw-bold text-uppercase mb-1 fs-07">{{ __('extra.post_max') }}</div>
                            <div class="fw-bold text-dark h5 mb-0">{{ $serverInfo['post_max'] }}</div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4 opacity-10">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="sys-title fs-095">
                        <i class="fa-solid fa-puzzle-piece me-2 text-muted"></i> {{ __('extra.loaded_ext') }}
                    </div>
                    <span class="text-muted small">{{ count($extensions) }} {{ __('extra.installed') }}</span>
                </div>
                
                <div class="ext-container">
                    @foreach($extensions as $ext)
                        <div class="ext-chip active">
                            <i class="fa-solid fa-circle icon-dot"></i> {{ $ext }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Host Info (Right) --}}
        <div class="col-lg-4">
            <div class="sys-card h-100">
                <div class="sys-card-header">
                    <div class="sys-title">
                        <div class="sys-icon"><i class="fa-solid fa-server"></i></div>
                        {{ __('extra.host_info') }}
                    </div>
                </div>
                <div class="sys-list">
                    <div class="sys-item">
                        <span class="sys-label">{{ __('extra.ip_address') }}</span>
                        <span class="sys-value">{{ $serverInfo['ip'] }}</span>
                    </div>
                    <div class="sys-item">
                        <span class="sys-label">{{ __('extra.protocol') }}</span>
                        <span class="sys-value">{{ $serverInfo['protocol'] }}</span>
                    </div>
                    <div class="sys-item pt-3 mt-2 border-top">
                        <span class="sys-label d-block w-100 mb-1">{{ __('extra.software') }}</span>
                        <span class="sys-value d-block w-100 small text-muted text-start font-inherit">
                            {{ $serverInfo['software'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection