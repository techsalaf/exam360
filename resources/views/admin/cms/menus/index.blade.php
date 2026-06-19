@extends('layouts.admin')

@section('title', __('cms.menu_manager'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/cms.css') }}">
@endpush

@section('content')

@php
    function getMenuTitle($menu, $default) {
        if (!$menu) return $default;
        $val = $menu->name ?? '';
        if (str_contains($val, 'Footer_column')) return $default;
        
        $json = json_decode($val, true);
        return (json_last_error() === JSON_ERROR_NONE && is_array($json)) ? ($json['en'] ?? '') : $val;
    }
@endphp

<div class="container-fluid py-4">
    
    {{-- Config Data for JS --}}
    <div id="cms-menu-config" data-empty-msg="{{ __('cms.list_empty') }}" class="d-none"></div>

    <div class="mb-4">
        <h1 class="h3 fw-bold text-dark mb-1">{{ __('cms.menu_management') }}</h1>
        <p class="text-muted small">{{ __('cms.menu_desc') }}</p>
    </div>

    <!-- Header Menu Section -->
    <div class="row mb-4">
        <div class="col-12">
            <form action="{{ route('admin.cms.menus.store') }}" method="POST">
                @csrf
                <input type="hidden" name="location" value="header">
                
                <div class="cms-card">
                    <div class="cms-header">
                        <div>
                            <h5 class="cms-title">
                                <i class="fa-solid fa-compass text-primary-cms"></i> {{ __('cms.header_menu') }}
                            </h5>
                            <p class="cms-desc">{{ __('cms.header_menu_desc') }}</p>
                        </div>
                        <button type="submit" class="btn-cms-primary">
                            <i class="fa-solid fa-save"></i> {{ __('cms.save_header') }}
                        </button>
                    </div>
                    
                    <div class="cms-body">
                        <div id="header-list" class="cms-sortable-list">
                            @forelse($header->items ?? [] as $index => $item)
                                @include('admin.cms.menus._menu_item', ['index' => $index, 'item' => $item])
                            @empty
                                <div class="cms-placeholder">{{ __('cms.menu_empty') }}</div>
                            @endforelse
                        </div>

                        <div class="mt-3">
                            {{-- Updated Button: No onclick, added class and data-target --}}
                            <button type="button" class="btn-cms-light js-add-menu-item" data-target="header-list">
                                <i class="fa-solid fa-plus"></i> {{ __('cms.add_menu_item') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer Columns Section -->
    <div class="row g-4">
        
        <!-- Footer Column 1 -->
        <div class="col-lg-6">
            <form action="{{ route('admin.cms.menus.store') }}" method="POST" class="h-100">
                @csrf
                <input type="hidden" name="location" value="footer_column_1">
                
                <div class="cms-card h-100">
                    <div class="cms-header">
                        <div class="w-100">
                            <h5 class="cms-title mb-2">
                                <i class="fa-solid fa-link text-primary-cms"></i> {{ __('cms.footer_col_1') }}
                            </h5>
                            <input type="text" name="title" class="form-control-cms" 
                                   value="{{ getMenuTitle($footerCol1 ?? null, 'Useful Links') }}" 
                                   placeholder="{{ __('cms.col_title_placeholder') }}">
                        </div>
                    </div>
                    
                    <div class="cms-body">
                        <div id="footer-col1-list" class="cms-sortable-list flex-grow-1">
                            @forelse($footerCol1->items ?? [] as $index => $item)
                                @include('admin.cms.menus._menu_item', ['index' => $index, 'item' => $item])
                            @empty
                                <div class="cms-placeholder">{{ __('cms.list_empty') }}</div>
                            @endforelse
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn-cms-light js-add-menu-item" data-target="footer-col1-list">
                                <i class="fa-solid fa-plus"></i> {{ __('cms.add_link') }}
                            </button>
                        </div>
                    </div>
                    <div class="p-3 border-top text-end">
                        <button type="submit" class="btn-cms-primary">
                            {{ __('cms.save_column') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer Column 2 -->
        <div class="col-lg-6">
            <form action="{{ route('admin.cms.menus.store') }}" method="POST" class="h-100">
                @csrf
                <input type="hidden" name="location" value="footer_column_2">
                
                <div class="cms-card h-100">
                    <div class="cms-header">
                        <div class="w-100">
                            <h5 class="cms-title mb-2">
                                <i class="fa-solid fa-file-contract text-primary-cms"></i> {{ __('cms.footer_col_2') }}
                            </h5>
                            <input type="text" name="title" class="form-control-cms" 
                                   value="{{ getMenuTitle($footerCol2 ?? null, 'Legal') }}" 
                                   placeholder="{{ __('cms.col_title_placeholder') }}">
                        </div>
                    </div>
                    
                    <div class="cms-body">
                        <div id="footer-col2-list" class="cms-sortable-list flex-grow-1">
                            @forelse($footerCol2->items ?? [] as $index => $item)
                                @include('admin.cms.menus._menu_item', ['index' => $index, 'item' => $item])
                            @empty
                                <div class="cms-placeholder">{{ __('cms.list_empty') }}</div>
                            @endforelse
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn-cms-light js-add-menu-item" data-target="footer-col2-list">
                                <i class="fa-solid fa-plus"></i> {{ __('cms.add_link') }}
                            </button>
                        </div>
                    </div>
                    <div class="p-3 border-top text-end">
                        <button type="submit" class="btn-cms-primary">
                            {{ __('cms.save_column') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Template for New Items -->
<template id="menu-item-template">
    <div class="cms-menu-item">
        <div class="cms-drag-handle"><i class="fa-solid fa-grip-vertical"></i></div>
        <div class="flex-grow-1 row g-2">
            <div class="col-5">
                <input type="text" name="items[INDEX][label]" class="form-control-cms" placeholder="{{ __('cms.menu_label') }}" required>
            </div>
            <div class="col-7">
                <input type="text" name="items[INDEX][url]" class="form-control-cms" placeholder="{{ __('cms.menu_url') }}" required>
            </div>
        </div>
        {{-- Updated Delete Button: No onclick --}}
        <button type="button" class="btn-cms-danger-icon js-remove-menu-item">
            <i class="fa-solid fa-trash-can"></i>
        </button>
    </div>
</template>

@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/sortablejs/Sortable.min.js') }}"></script>
{{-- Include the new separate JS file --}}
<script src="{{ asset('assets/js/admin-cms-menus.js') }}"></script>
@endpush