@extends('layouts.admin')

@section('title', __('cms.page_manager'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/cms.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- Config for JS --}}
    <div id="cms-pages-config" 
         data-delete-text="{{ __('cms.delete_page_text') }}"
         class="d-none"></div>
    
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('cms.page_manager') }}</h1>
            <p class="text-muted small mb-0">{{ __('cms.page_manager_desc') }}</p>
        </div>
        <a href="{{ route('admin.cms.pages.create') }}" class="btn-cms-primary">
            <i class="fa-solid fa-plus"></i> {{ __('cms.create_page') }}
        </a>
    </div>

    <div class="cms-card">
        <div class="table-responsive">
            <table class="table cms-table mb-0 w-100">
                <thead>
                    <tr>
                        <th class="cms-col-identity">{{ __('cms.page_identity') }}</th>
                        <th class="cms-col-url">{{ __('cms.public_url') }}</th>
                        <th class="text-center cms-col-status">{{ __('cms.status') }}</th>
                        <th class="text-end cms-col-actions">{{ __('cms.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                    <tr class="align-middle">
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="cms-empty-icon mb-0 page-icon">
                                    <i class="fa-regular fa-file-lines"></i>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 fw-bold page-title">{{ $page->title }}</h6>
                                    <span class="small text-muted page-updated">
                                        {{ __('cms.updated') }} {{ $page->updated_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <code class="px-2 py-1 rounded small page-slug">/{{ $page->slug }}</code>
                                <a href="{{ url($page->slug) }}" target="_blank" class="text-muted hover-primary" title="{{ __('cms.view_live') }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square small"></i>
                                </a>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($page->is_published)
                                <span class="badge-cms-published">
                                    <i class="fa-solid fa-check"></i> {{ __('cms.published') }}
                                </span>
                            @else
                                <span class="badge-cms-draft">
                                    <i class="fa-solid fa-pen-ruler"></i> {{ __('cms.draft') }}
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <a href="{{ route('admin.cms.pages.edit', $page->id) }}" class="btn-cms-light" title="{{ __('cms.edit_page') }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                
                                {{-- JS Hook: .delete-page-btn --}}
                                <button type="button" class="btn-cms-danger-icon delete-page-btn" data-id="{{ $page->id }}" title="{{ __('cms.delete_page') }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                
                                <form id="delete-form-{{ $page->id }}" action="{{ route('admin.cms.pages.destroy', $page->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center p-0">
                            <div class="cms-empty">
                                <div class="cms-empty-icon">
                                    <i class="fa-solid fa-layer-group"></i>
                                </div>
                                <h5 class="fw-bold page-title-fallback">{{ __('cms.no_pages_found') }}</h5>
                                <p class="text-muted mb-0">{{ __('cms.create_first_page_hint') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pages->hasPages())
        <div class="px-4 py-3 border-top cms-pagination">
            {{ $pages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin-cms-pages-index.js') }}"></script>
@endpush