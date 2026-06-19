@extends('layouts.admin')

@section('title', __('cms.homepage_editor'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/cms.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-categories.css') }}">
@endpush

@section('content')

{{-- Helper for JSON Inputs --}}
@php
    function getAdminText($key, $settings) {
        $val = $settings[$key] ?? '';
        $json = json_decode($val, true);
        return (json_last_error() === JSON_ERROR_NONE && is_array($json)) ? ($json['en'] ?? '') : $val;
    }
@endphp

<div class="container-fluid">
    <form action="{{ route('admin.cms.homepage.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">{{ __('cms.homepage_editor') }}</h1>
                <p class="text-muted small mb-0">{{ __('cms.homepage_desc') }}</p>
            </div>
            <button type="submit" class="btn btn-cms-primary px-4 shadow-sm">
                <i class="fa-solid fa-save me-2"></i> {{ __('cms.save_changes') }}
            </button>
        </div>

        <div class="row g-4">
            
            <!-- LEFT COLUMN: Content Sections -->
            <div class="col-lg-8">
                @include('admin.cms.homepage.partials.hero')
                @include('admin.cms.homepage.partials.categories')
                @include('admin.cms.homepage.partials.audience')
                @include('admin.cms.homepage.partials.features')
                @include('admin.cms.homepage.partials.how-it-works')
                @include('admin.cms.homepage.partials.exams')
                @include('admin.cms.homepage.partials.admin-preview')
                @include('admin.cms.homepage.partials.cms') 
                @include('admin.cms.homepage.partials.pricing')
                @include('admin.cms.homepage.partials.testimonials')
                @include('admin.cms.homepage.partials.faq')
                @include('admin.cms.homepage.partials.cta')
            </div>

            <!-- RIGHT COLUMN: Images & Global Stats -->
            <div class="col-lg-4">
                @include('admin.cms.homepage.partials.sidebar')
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const container = input.closest('.image-upload-container');
                const img = container.querySelector('.image-preview');
                const label = container.querySelector('.image-upload-label');
                const deleteInput = container.querySelector('input[type="hidden"]');
                
                if(img) {
                    img.src = e.target.result;
                    img.classList.add('show');
                }
                if(label) label.classList.add('has-image');
                if(deleteInput) deleteInput.value = '0';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage(event) {
        event.preventDefault();
        event.stopPropagation();
        const btn = event.currentTarget;
        const container = btn.closest('.image-upload-container');
        
        const img = container.querySelector('.image-preview');
        const label = container.querySelector('.image-upload-label');
        const fileInput = container.querySelector('input[type="file"]');
        const deleteInput = container.querySelector('input[type="hidden"]');

        if(img) {
            img.src = '';
            img.classList.remove('show');
        }
        if(label) label.classList.remove('has-image');
        if(fileInput) fileInput.value = '';
        if(deleteInput) deleteInput.value = '1';
    }
</script>
@endpush
@endsection