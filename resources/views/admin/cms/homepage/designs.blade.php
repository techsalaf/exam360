@extends('layouts.admin')

@section('title', __('cms.homepage_design_library'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/cms.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('cms.homepage_designs') }}</h1>
            <p class="text-muted small mb-0">{{ __('cms.choose_base_layout_hint') }}</p>
        </div>
    </div>

    <div class="row">
        @foreach($availableDesigns as $design)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="cms-card h-100 {{ $activeDesign == $design['id'] ? 'border-primary-cms' : '' }}" 
                 style="{{ $activeDesign == $design['id'] ? 'border-color: var(--cms-primary); border-width: 2px;' : '' }}">
                
                @if($activeDesign == $design['id'])
                    <div class="active-badge">
                        <span class="badge-cms-published">
                            <i class="fa-solid fa-circle-check"></i> {{ __('cms.active_design') }}
                        </span>
                    </div>
                @endif

                <div class="design-preview-container">
                    @php
                        $customThumb = \App\Models\SystemSetting::where('key', 'thumb_'.$design['id'])->value('value');
                        $displayImg = $customThumb ? asset('storage/'.$customThumb) : asset($design['image']);
                    @endphp
                    
                    <img src="{{ $displayImg }}" class="design-img" alt="{{ $design['name'] }}">
                    
                    <div class="design-overlay">
                        <button type="button" class="update-thumb-btn" onclick="document.getElementById('file-{{ $design['id'] }}').click()">
                            <i class="fa-solid fa-camera me-1"></i> {{ __('cms.update_thumbnail') }}
                        </button>
                    </div>

                    <form action="{{ route('admin.cms.homepage.update-thumbnail') }}" method="POST" enctype="multipart/form-data" id="form-thumb-{{ $design['id'] }}" class="d-none">
                        @csrf
                        <input type="hidden" name="design_id" value="{{ $design['id'] }}">
                        <input type="file" name="thumbnail" id="file-{{ $design['id'] }}" onchange="document.getElementById('form-thumb-{{ $design['id'] }}').submit()" accept="image/*">
                    </form>
                </div>

                <div class="cms-body d-flex flex-column">
                    <h5 class="fw-bold text-dark mb-2">{{ $design['name'] }}</h5>
                    <p class="text-muted small mb-4 flex-grow-1">{{ $design['desc'] }}</p>
                    
                    <form action="{{ route('admin.cms.homepage.set-design') }}" method="POST">
                        @csrf
                        <input type="hidden" name="design_id" value="{{ $design['id'] }}">
                        @if($activeDesign == $design['id'])
                            <a href="{{ route('admin.cms.homepage.index') }}" class="btn-cms-primary w-100">
                                <i class="fa-solid fa-sliders"></i> {{ __('cms.customize_content') }}
                            </a>
                        @else
                            <button type="submit" class="btn-cms-light w-100">
                                {{ __('cms.activate_this_design') }}
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection