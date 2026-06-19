@extends('layouts.admin')

@section('title', __('cms.edit_page'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/cms.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">
    
    {{-- Config Data for JS --}}
    <div id="cms-builder-config" 
         data-remove-section-text="{{ __('cms.remove_section_text') }}"
         data-empty-hint="{{ __('cms.builder_empty_hint') }}"
         data-delete-page-text="{{ __('cms.delete_page_text') }}"
         class="d-none"></div>

    <form action="{{ route('admin.cms.pages.update', $page->id) }}" method="POST" id="pageForm">
        @csrf
        @method('PUT')
        
        <!-- Header & Actions -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">{{ __('cms.edit_page') }}</h1>
                <p class="text-muted small mb-0">{{ __('cms.updating') }}: <span class="cms-page-title-display">{{ $page->title }}</span></p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.cms.pages.index') }}" class="btn-cms-light">{{ __('cms.back') }}</a>
                
                {{-- JS Hook: id="deletePageBtn" --}}
                <button type="button" class="btn-cms-light text-danger border-danger" id="deletePageBtn">
                    <i class="fa-solid fa-trash me-2"></i> {{ __('cms.delete') }}
                </button>

                <button type="submit" class="btn-cms-primary">
                    <i class="fa-solid fa-save"></i> {{ __('cms.update_page') }}
                </button>
            </div>
        </div>

        <div class="row g-4">
            
            <!-- Left Column: Content Builder -->
            <div class="col-lg-8">
                <div class="cms-card">
                    <div class="cms-header">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <h5 class="cms-title">
                                <i class="fa-solid fa-layer-group cms-icon-primary"></i> {{ __('cms.content_builder') }}
                            </h5>
                            
                            <div class="dropdown">
                                <button class="btn-cms-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-plus"></i> {{ __('cms.add_section') }}
                                </button>
                                <ul class="dropdown-menu shadow-lg border-0 p-2">
                                    <li><h6 class="dropdown-header text-uppercase small fw-bold">{{ __('cms.layouts') }}</h6></li>
                                    {{-- JS Hooks: js-add-section-btn --}}
                                    <li><a class="dropdown-item rounded py-2 js-add-section-btn" href="#" data-type="hero"><i class="fa-solid fa-image text-primary me-2"></i> {{ __('cms.hero_banner') }}</a></li>
                                    <li><a class="dropdown-item rounded py-2 js-add-section-btn" href="#" data-type="text"><i class="fa-solid fa-align-left text-muted me-2"></i> {{ __('cms.rich_text') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header text-uppercase small fw-bold">{{ __('cms.components') }}</h6></li>
                                    <li><a class="dropdown-item rounded py-2 js-add-section-btn" href="#" data-type="features"><i class="fa-solid fa-list-check text-success me-2"></i> {{ __('cms.feature_grid') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="cms-body bg-subtle">
                        <div class="mb-4">
                            <label class="fw-bold mb-2 cms-label-text">{{ __('cms.page_title') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control-cms" required value="{{ $page->title }}">
                        </div>

                        <!-- Dynamic Sections Container -->
                        <div id="sections-container" class="cms-sortable-list">
                            @forelse($page->sections as $index => $section)
                                @php $unique = $index . time(); @endphp
                                
                                <div class="cms-menu-item section-block">
                                    <input type="hidden" name="sections[{{ $unique }}][type]" value="{{ $section->type }}">
                                    <div class="cms-drag-handle"><i class="fa-solid fa-grip-vertical"></i></div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="badge bg-opacity-10 {{ $section->type == 'hero' ? 'bg-primary text-primary' : ($section->type == 'features' ? 'bg-success text-success' : 'bg-secondary text-secondary') }}">
                                                {{ __('cms.section_' . $section->type) }}
                                            </span>
                                            {{-- JS Hook: js-remove-section-btn --}}
                                            <button type="button" class="btn-cms-danger-icon p-0 js-remove-section-btn"><i class="fa-solid fa-trash"></i></button>
                                        </div>
                                        
                                        @if($section->type == 'hero')
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <input type="text" name="sections[{{ $unique }}][content][heading]" class="form-control-cms" value="{{ $section->content['heading'] ?? '' }}" placeholder="{{ __('cms.main_headline') }}">
                                                </div>
                                                <div class="col-12">
                                                    <textarea name="sections[{{ $unique }}][content][subtext]" class="form-control-cms" rows="2" placeholder="{{ __('cms.sub_headline_text') }}">{{ $section->content['subtext'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        @elseif($section->type == 'text')
                                            <textarea name="sections[{{ $unique }}][content][body]" class="form-control-cms" rows="6" placeholder="{{ __('cms.enter_content') }}">{{ $section->content['body'] ?? '' }}</textarea>
                                        @elseif($section->type == 'features')
                                            <label class="small text-muted mb-1">{{ __('cms.list_items_label') }}</label>
                                            <textarea name="sections[{{ $unique }}][content][items]" class="form-control-cms" rows="4" placeholder="{{ __('cms.list_items_placeholder') }}">{{ $section->content['items'] ?? '' }}</textarea>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div id="empty-state" class="cms-placeholder">
                                    <i class="fa-solid fa-arrow-up-right-from-square mb-2 d-block"></i>
                                    {{ __('cms.builder_empty_hint') }}
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings -->
            <div class="col-lg-4">
                <div class="cms-card">
                    <div class="cms-header">
                        <h5 class="cms-title">{{ __('cms.page_settings') }}</h5>
                    </div>
                    <div class="cms-body">
                        
                        <div class="mb-4">
                            <label class="fw-bold mb-2 cms-label-text">{{ __('cms.publish_status') }}</label>
                            <div class="form-check form-switch p-0 m-0 d-flex align-items-center justify-content-between border rounded p-3 cms-setting-switch">
                                <label class="form-check-label fw-bold cursor-pointer cms-label-text" for="statusSwitch">{{ __('cms.published') }}</label>
                                <input class="form-check-input ms-0" type="checkbox" id="statusSwitch" name="is_published" value="1" {{ $page->is_published ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold mb-2 cms-label-text">{{ __('cms.meta_description') }}</label>
                            <textarea name="meta_description" class="form-control-cms" rows="5" placeholder="{{ __('cms.meta_description_placeholder') }}">{{ $page->meta_description }}</textarea>
                        </div>
                        
                        <div class="mt-3">
                            <label class="fw-bold mb-2 cms-label-text">{{ __('cms.public_url') }}</label>
                            <div class="form-control-cms cms-slug-display">/{{ $page->slug }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
    
    {{-- Hidden Delete Form --}}
    <form id="delete-page-form" action="{{ route('admin.cms.pages.destroy', $page->id) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

</div>

<!-- Use same templates as Create page -->
<template id="tpl-hero">
    <div class="cms-menu-item section-block">
        <input type="hidden" name="sections[INDEX][type]" value="hero">
        <div class="cms-drag-handle"><i class="fa-solid fa-grip-vertical"></i></div>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between mb-2">
                <span class="badge bg-primary bg-opacity-10 text-primary">{{ __('cms.section_hero') }}</span>
                <button type="button" class="btn-cms-danger-icon p-0 js-remove-section-btn"><i class="fa-solid fa-trash"></i></button>
            </div>
            <div class="row g-2">
                <div class="col-12">
                    <input type="text" name="sections[INDEX][content][heading]" class="form-control-cms" placeholder="{{ __('cms.headline_placeholder') }}">
                </div>
                <div class="col-12">
                    <textarea name="sections[INDEX][content][subtext]" class="form-control-cms" rows="2" placeholder="{{ __('cms.subtext_placeholder') }}"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<template id="tpl-text">
    <div class="cms-menu-item section-block">
        <input type="hidden" name="sections[INDEX][type]" value="text">
        <div class="cms-drag-handle"><i class="fa-solid fa-grip-vertical"></i></div>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between mb-2">
                <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ __('cms.section_text') }}</span>
                <button type="button" class="btn-cms-danger-icon p-0 js-remove-section-btn"><i class="fa-solid fa-trash"></i></button>
            </div>
            <textarea name="sections[INDEX][content][body]" class="form-control-cms" rows="6" placeholder="{{ __('cms.content_placeholder') }}"></textarea>
        </div>
    </div>
</template>

<template id="tpl-features">
    <div class="cms-menu-item section-block">
        <input type="hidden" name="sections[INDEX][type]" value="features">
        <div class="cms-drag-handle"><i class="fa-solid fa-grip-vertical"></i></div>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between mb-2">
                <span class="badge bg-success bg-opacity-10 text-success">{{ __('cms.section_features') }}</span>
                <button type="button" class="btn-cms-danger-icon p-0 js-remove-section-btn"><i class="fa-solid fa-trash"></i></button>
            </div>
            <label class="small text-muted mb-1">{{ __('cms.list_items_label') }}</label>
            <textarea name="sections[INDEX][content][items]" class="form-control-cms" rows="4" placeholder="{{ __('cms.features_placeholder') }}"></textarea>
        </div>
    </div>
</template>

@push('scripts')
<script src="{{ asset('assets/vendor/sortablejs/Sortable.min.js') }}"></script>
<script src="{{ asset('assets/js/admin-cms-pages-form.js') }}"></script>
@endpush
@endsection