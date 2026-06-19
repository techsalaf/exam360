@extends('layouts.admin')

@section('title', __('categories.page_title'))

@push('styles')
    <link href="{{ asset('assets/css/admin-categories.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components/admin-kpi.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components/admin-pagination.css') }}" rel="stylesheet">
@endpush

@section('content')

    <!-- MOBILE FILTER BAR -->
    <div class="mobile-filter-bar">
        <button class="btn w-100 d-flex align-items-center justify-content-between px-3 py-2 rounded-3 shadow-sm bg-white border" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#categoryFilterOffcanvas">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-dark text-white rounded-2 d-flex align-items-center justify-content-center mobile-icon-box">
                    <i class="fa-solid fa-sliders mobile-icon-size"></i>
                </div>
                <span class="fw-bold text-dark mobile-text-label">{{ __('categories.filter_action') }}</span>
            </div>
            <i class="fa-solid fa-chevron-right text-muted mobile-chevron-size"></i>
        </button>
    </div>

    <!-- DESKTOP HEADER -->
    <div class="desktop-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('categories.header_title') }}</h1>
            <p class="text-muted small mb-0">{{ __('categories.header_desc') }}</p>
        </div>
        
        <div class="page-header-actions">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="search-filter-box">
                <input type="text" name="search" value="{{ request('search') }}" class="search-filter-input" placeholder="{{ __('categories.search_placeholder') }}" autocomplete="off">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-filter-icon">
                    <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </form>
            <button class="btn-premium rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                {{ __('categories.add_category') }}
            </button>
        </div>
    </div>

    <!-- KPI STATISTICS -->
    <x-kpi-grid :stats="[
        ['value' => $kpi['total'], 'label' => __('categories.kpi_total'), 'icon' => 'fa-solid fa-layer-group', 'color' => 'primary'],
        ['value' => $kpi['active'], 'label' => __('categories.kpi_active'), 'icon' => 'fa-regular fa-circle-check', 'color' => 'success'],
        ['value' => $kpi['disabled'], 'label' => __('categories.kpi_disabled'), 'icon' => 'fa-solid fa-ban', 'color' => 'danger']
    ]" />

    <!-- DATA LIST -->
    <div class="category-list-container mt-4">
        <div class="list-header">
            <div class="col-header col-checkbox"><input type="checkbox" id="selectAll" class="form-check-input-premium"></div>
            <div class="col-header col-identity">{{ __('categories.th_identity') }}</div>
            <div class="col-header col-status">{{ __('categories.th_status') }}</div>
            <div class="col-header col-count">{{ __('categories.th_usage') }}</div>
            <div class="col-header col-actions">{{ __('categories.th_actions') }}</div>
        </div>

        @forelse($categories as $category)
            <div class="list-item">
                <div class="col-checkbox">
                    <input type="checkbox" name="ids[]" value="{{ $category->id }}" form="bulkDeleteForm" class="form-check-input-premium bulk-item">
                </div>

                <div class="col-identity item-identity">
                    <div class="item-icon">
                        @if($category->image)
                            {{-- FIX 1: Added 'storage/' prefix to asset path --}}
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10v6"/><path d="M20 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="item-details">
                        <h5>{{ $category->name }}</h5>
                        <p class="text-muted">{{ __('categories.label_slug') }}: {{ $category->slug }}</p>
                    </div>
                </div>
                
                <div class="col-status">
                    <x-status-badge :status="$category->is_active" />
                </div>
                
                <div class="col-count">
                    <span class="item-count">{{ $category->exams_count ?? 0 }} {{ __('categories.exams_count') }}</span>
                </div>
                
                <div class="col-actions">
                    <div class="action-group">
                        <button type="button" 
                                class="btn-circle edit btn-edit-category" 
                                title="{{ __('categories.btn_edit') }}" 
                                data-id="{{ $category->id }}" 
                                data-name="{{ $category->name }}" 
                                {{-- FIX 2: Added 'storage/' prefix to asset path for data attribute --}}
                                data-image="{{ $category->image ? asset('storage/' . $category->image) : '' }}"
                                data-description="{{ $category->description }}" 
                                data-meta1="{{ $category->meta_text_1 }}" 
                                data-meta2="{{ $category->meta_text_2 }}" 
                                data-action="{{ route('admin.categories.update', $category->id) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>

                        <form action="{{ route('admin.categories.toggle', $category->id) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-circle toggle btn-toggle-status" title="{{ __('categories.btn_toggle') }}" data-active="{{ $category->is_active ? '1' : '0' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line>
                                </svg>
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-circle danger confirm-delete" title="{{ __('categories.btn_delete') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <x-empty-state title="{{ __('categories.empty_title') }}" subtitle="{{ __('categories.empty_desc') }}" icon="fa-regular fa-folder-open">
                <button class="btn btn-premium btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fa-solid fa-plus me-1"></i> {{ __('categories.btn_create_new') }}
                </button>
            </x-empty-state>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
            {{ __('categories.showing_results', ['first' => $categories->firstItem() ?? 0, 'last' => $categories->lastItem() ?? 0, 'total' => $categories->total()]) }}
        </div>
        @include('components.app-pagination', ['paginator' => $categories])
    </div>

    <!-- MOBILE OFFCANVAS -->
    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="categoryFilterOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold text-dark">{{ __('categories.offcanvas_title') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form action="{{ route('admin.categories.index') }}" method="GET">
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold text-uppercase mb-2">{{ __('categories.offcanvas_search_label') }}</label>
                    <input type="text" name="search" class="form-control" placeholder="{{ __('categories.search_placeholder') }}" value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-premium w-100 rounded-pill fw-bold py-2 mb-3">{{ __('categories.btn_apply') }}</button>
            </form>
            <button class="btn btn-outline-success w-100 rounded-pill fw-bold py-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">{{ __('categories.btn_add_new') }}</button>
        </div>
    </div>

    <!-- BULK DELETE BAR -->
    <form id="bulkDeleteForm" action="{{ route('admin.categories.bulk-destroy') }}" method="POST">
        @csrf @method('DELETE')
        <div id="floatingBulkBar" class="bulk-floating-bar">
            <span class="bulk-text"><span id="selectedCount">0</span> {{ __('categories.bulk_selected') }}</span>
            <div class="bulk-divider"></div>
            <button type="submit" class="btn btn-danger rounded-pill btn-sm px-3 fw-bold confirm-delete">{{ __('categories.bulk_delete') }}</button>
        </div>
    </form>

    <!-- MODALS -->
    @include('admin.categories.partials.create-modal')
    @include('admin.categories.partials.edit-modal')

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-categories.js') }}"></script>
@endpush