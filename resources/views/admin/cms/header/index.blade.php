@extends('layouts.admin')

@section('title', __('cms.header_editor'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/cms.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('cms.header_editor') }}</h1>
            <p class="text-muted small mb-0">{{ __('cms.header_desc') }}</p>
        </div>
        <button type="submit" form="headerForm" class="btn btn-cms-primary shadow-sm px-4">
            <i class="fa-solid fa-save me-2"></i> {{ __('cms.save_changes') }}
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form id="headerForm" action="{{ route('admin.cms.header.update') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="cms-card mb-4">
                    <div class="cms-header">
                        <h5 class="cms-title"><i class="fa-solid fa-brush me-2"></i> {{ __('cms.appearance_layout') }}</h5>
                    </div>
                    <div class="cms-body">
                        <div class="mb-4">
                            <label class="form-label-premium">{{ __('cms.logo_height') }}</label>
                            <div class="position-relative">
                                <input type="number" 
                                       name="header_logo_height" 
                                       class="form-control-cms pe-5" 
                                       value="{{ $settings['header_logo_height'] ?? '34' }}" 
                                       min="20" max="150">
                                <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted small fw-bold">PX</span>
                            </div>
                            <div class="form-text small text-muted mt-2">{{ __('cms.logo_height_desc') }}</div>
                        </div>

                        <div class="alert alert-light border small text-muted mb-0">
                            <i class="fa-solid fa-compass me-1"></i> 
                            {{ __('cms.menu_manager_note_header') }} 
                            <a href="{{ route('admin.cms.menus.index') }}" class="fw-bold text-primary-cms text-decoration-none">{{ __('cms.menu_manager') }}</a>.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="cms-card h-100">
                    <div class="cms-header">
                        <h5 class="cms-title"><i class="fa-solid fa-bullhorn me-2"></i> {{ __('cms.primary_action_button') }}</h5>
                        <p class="cms-desc">{{ __('cms.action_button_desc') }}</p>
                    </div>
                    <div class="cms-body">
                        <div class="mb-3">
                            <label class="form-label-premium">{{ __('cms.button_text') }}</label>
                            <input type="text" name="header_cta_text" class="form-control-cms" value="{{ cms_admin_text($settings, 'header_cta_text') }}">
                        </div>

                        <div class="mb-4">
                            <label class="form-label-premium">{{ __('cms.button_link') }}</label>
                            <input type="text" name="header_cta_link" class="form-control-cms" value="{{ $settings['header_cta_link'] ?? '/register' }}" placeholder="/register">
                            <div class="form-text small text-muted mt-2">{{ __('cms.button_link_help') }}</div>
                        </div>

                        <div class="p-4 bg-light-cms rounded border text-center mt-auto">
                            <label class="small text-muted fw-bold mb-3 d-block text-uppercase">{{ __('cms.button_preview') }}</label>
                            <button type="button" class="btn btn-primary rounded-pill px-4 py-2 fw-bold cta-preview-btn">
                                {{ cms_admin_text($settings, 'header_cta_text') ?: __('cms.default_cta_text') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection