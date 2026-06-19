@extends('layouts.admin')

@section('title', __('extra.app_title'))

@push('styles')
    <link href="{{ asset('assets/css/admin-system-info.css') }}" rel="stylesheet">
@endpush

@section('content')

    <div class="mb-4">
        <h1 class="h3 fw-bold text-dark mb-1">{{ __('extra.app_title') }}</h1>
        <p class="text-muted small mb-0">{{ __('extra.app_subtitle') }}</p>
    </div>

    <div class="row g-4">
        {{-- LEFT COLUMN: Application & Database --}}
        <div class="col-lg-8">
            <div class="sys-card h-100">
                <div class="sys-card-header">
                    <div class="sys-title">
                        <div class="sys-icon"><i class="fa-solid fa-code-branch"></i></div>
                        {{ __('extra.core_config') }}
                    </div>
                    <span class="sys-badge bg-light border">Laravel v{{ $appInfo['laravel'] }}</span>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="sys-list">
                            <div class="sys-item">
                                <span class="sys-label">{{ __('extra.app_name') }}</span>
                                <span class="sys-value">{{ $appInfo['name'] }}</span>
                            </div>
                            <div class="sys-item">
                                <span class="sys-label">{{ __('extra.app_url') }}</span>
                                <span class="sys-value text-truncate mw-200">{{ $appInfo['url'] }}</span>
                            </div>
                            <div class="sys-item">
                                <span class="sys-label">{{ __('extra.environment') }}</span>
                                <span class="sys-badge {{ $appInfo['env'] == 'production' ? 'badge-ok' : 'badge-warn' }}">
                                    {{ ucfirst($appInfo['env']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="sys-list">
                            <div class="sys-item">
                                <span class="sys-label">{{ __('extra.debug_mode') }}</span>
                                <span class="sys-badge {{ $appInfo['debug'] ? 'badge-warn' : 'badge-ok' }}">
                                    {{ $appInfo['debug'] ? __('extra.true') : __('extra.false') }}
                                </span>
                            </div>
                            <div class="sys-item">
                                <span class="sys-label">{{ __('extra.timezone') }}</span>
                                <span class="sys-value">{{ $appInfo['timezone'] }}</span>
                            </div>
                            <div class="sys-item">
                                <span class="sys-label">{{ __('extra.locale') }}</span>
                                <span class="sys-value">{{ $appInfo['locale'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4 opacity-10">

                {{-- Database Section --}}
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="sys-title mb-2 fs-095">
                            <i class="fa-solid fa-database me-2 text-muted"></i> {{ __('extra.db_status') }}
                        </div>
                        <div class="fs-6 fw-bold text-dark">
                            {{ ucfirst($appInfo['db_conn']) }} 
                            <span class="text-muted small fw-normal">({{ __('extra.db_connection') }})</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="sys-badge badge-ok mb-2">{{ __('extra.db_connected') }}</span>
                        <div class="fs-6 fw-bold text-dark">{{ $appInfo['db_size'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Permissions --}}
        <div class="col-lg-4">
            <div class="sys-card h-100">
                <div class="sys-card-header">
                    <div class="sys-title">
                        <div class="sys-icon"><i class="fa-solid fa-folder-tree"></i></div>
                        {{ __('extra.permissions') }}
                    </div>
                </div>
                <div class="sys-list mb-3">
                    @foreach($permissions as $path => $writable)
                        <div class="sys-item">
                            <span class="sys-label text-truncate" title="{{ $path }}">{{ $path }}</span>
                            @if($writable)
                                <span class="sys-badge badge-ok"><i class="fa-solid fa-check"></i></span>
                            @else
                                <span class="sys-badge badge-err"><i class="fa-solid fa-xmark"></i></span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="mt-auto p-3 bg-light rounded-3">
                    <small class="text-muted d-block lh-relax">
                        <i class="fa-solid fa-circle-info me-1"></i> 
                        {{ __('extra.perm_hint') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

@endsection