@extends('layouts.admin')

@section('title', __('extra.cache_title'))

@push('styles')
    <link href="{{ asset('assets/css/admin-system-info.css') }}" rel="stylesheet">
@endpush

@section('content')

    <div class="mb-4">
        <h1 class="h3 fw-bold text-dark mb-1">{{ __('extra.cache_title') }}</h1>
        <p class="text-muted small mb-0">{{ __('extra.cache_subtitle') }}</p>
    </div>

    <div class="row g-4">
        {{-- Drivers Info --}}
        <div class="col-lg-4 order-lg-2">
            <div class="sys-card">
                <div class="sys-card-header">
                    <div class="sys-title">
                        <div class="sys-icon"><i class="fa-solid fa-hard-drive"></i></div>
                        {{ __('extra.active_drivers') }}
                    </div>
                </div>
                <div class="sys-list">
                    <div class="sys-item">
                        <span class="sys-label">{{ __('extra.sys_cache') }}</span>
                        <span class="sys-badge bg-light text-dark border">{{ ucfirst($drivers['cache']) }}</span>
                    </div>
                    <div class="sys-item">
                        <span class="sys-label">{{ __('extra.session_store') }}</span>
                        <span class="sys-badge bg-light text-dark border">{{ ucfirst($drivers['session']) }}</span>
                    </div>
                    <div class="sys-item">
                        <span class="sys-label">{{ __('extra.queue_worker') }}</span>
                        <span class="sys-badge bg-light text-dark border">{{ ucfirst($drivers['queue']) }}</span>
                    </div>
                    <div class="sys-item">
                        <span class="sys-label">{{ __('extra.mail_system') }}</span>
                        <span class="sys-badge bg-light text-dark border">{{ ucfirst($drivers['mail']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="col-lg-8 order-lg-1">
            <div class="sys-card">
                <div class="sys-card-header">
                    <div class="sys-title">
                        <div class="sys-icon"><i class="fa-solid fa-bolt"></i></div>
                        {{ __('extra.quick_actions') }}
                    </div>
                    <form action="{{ route('admin.extra.cache.clear') }}" method="POST">
                        @csrf <input type="hidden" name="type" value="optimize">
                        <button type="submit" class="btn btn-dark rounded-pill px-4 fw-bold">
                            <i class="fa-solid fa-rocket me-2"></i> {{ __('extra.btn_optimize') }}
                        </button>
                    </form>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <form action="{{ route('admin.extra.cache.clear') }}" method="POST" class="h-100">
                            @csrf <input type="hidden" name="type" value="app">
                            <button type="submit" class="btn-cache text-start h-100 d-flex align-items-center gap-3">
                                <div class="sys-icon bg-blue-subtle text-blue"><i class="fa-solid fa-server mb-0"></i></div>
                                <div>
                                    <span>{{ __('extra.app_cache') }}</span>
                                    <small class="text-muted fw-normal d-block mt-1">{{ __('extra.app_cache_desc') }}</small>
                                </div>
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.extra.cache.clear') }}" method="POST" class="h-100">
                            @csrf <input type="hidden" name="type" value="route">
                            <button type="submit" class="btn-cache text-start h-100 d-flex align-items-center gap-3">
                                <div class="sys-icon bg-green-subtle text-green"><i class="fa-solid fa-route mb-0"></i></div>
                                <div>
                                    <span>{{ __('extra.route_cache') }}</span>
                                    <small class="text-muted fw-normal d-block mt-1">{{ __('extra.route_cache_desc') }}</small>
                                </div>
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.extra.cache.clear') }}" method="POST" class="h-100">
                            @csrf <input type="hidden" name="type" value="config">
                            <button type="submit" class="btn-cache text-start h-100 d-flex align-items-center gap-3">
                                <div class="sys-icon bg-orange-subtle text-orange"><i class="fa-solid fa-gears mb-0"></i></div>
                                <div>
                                    <span>{{ __('extra.config_cache') }}</span>
                                    <small class="text-muted fw-normal d-block mt-1">{{ __('extra.config_cache_desc') }}</small>
                                </div>
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.extra.cache.clear') }}" method="POST" class="h-100">
                            @csrf <input type="hidden" name="type" value="view">
                            <button type="submit" class="btn-cache text-start h-100 d-flex align-items-center gap-3">
                                <div class="sys-icon bg-purple-subtle text-purple"><i class="fa-regular fa-eye mb-0"></i></div>
                                <div>
                                    <span>{{ __('extra.view_cache') }}</span>
                                    <small class="text-muted fw-normal d-block mt-1">{{ __('extra.view_cache_desc') }}</small>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection