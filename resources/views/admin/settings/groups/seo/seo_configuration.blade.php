@php
    $metaTitle = $seoSettings['seo_meta_title'] ?? config('app.name');
    $metaDesc  = $seoSettings['seo_meta_description'] ?? __('seo.defaults.desc');
    $keywords  = $seoSettings['seo_keywords'] ?? __('seo.defaults.keywords');
    $gaId      = $seoSettings['seo_google_analytics_id'] ?? '';
    $bannerUrl = $seoSettings['seo_banner_image'] ?? null;
@endphp

<div class="tab-pane fade show active" id="seo-content" role="tabpanel">
    <div class="settings-content">
        <form action="{{ route('admin.settings.seo.update') }}" method="POST" id="seoConfigForm" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="setting_group_key" value="seo_config">

            <div class="setting-card">
                
                <div class="setting-header">
                    <h3 class="setting-title">{{ __('seo.config.title') }}</h3>
                    <p class="setting-desc">{{ __('seo.config.desc') }}</p>
                </div>

                <div class="border rounded-3 p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="seo-icon-box me-3">
                            <i class="fa-solid fa-magnifying-glass-chart seo-icon"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark m-0">{{ __('seo.config.meta_title') }}</h6>
                            <span class="text-muted small">{{ __('seo.config.meta_desc') }}</span>
                        </div>
                    </div>

                    <div class="row g-5">
                        
                        <div class="col-lg-7">
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label class="form-label-premium">{{ __('seo.config.meta_title_label') }}</label>
                                    <input type="text" 
                                           name="seo_meta_title" 
                                           class="form-control-premium" 
                                           value="{{ $metaTitle }}"
                                           maxlength="100"
                                           placeholder="{{ __('seo.config.meta_title_ph') }}">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label-premium">{{ __('seo.config.meta_desc_label') }}</label>
                                    <textarea name="seo_meta_description" 
                                              class="form-control-premium" 
                                              rows="4" 
                                              maxlength="300"
                                              placeholder="{{ __('seo.config.meta_desc_ph') }}">{{ $metaDesc }}</textarea>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label-premium">{{ __('seo.config.keywords_label') }}</label>
                                    <input type="text" 
                                           name="seo_keywords" 
                                           class="form-control-premium" 
                                           value="{{ $keywords }}"
                                           placeholder="{{ __('seo.config.keywords_ph') }}">
                                </div>
                                
                                <div class="col-md-12 pt-3">
                                    <h6 class="fw-bold mb-3 text-dark">{{ __('seo.config.analytics_title') }}</h6>
                                </div>
                                
                                <div class="col-md-12">
                                    <label class="form-label-premium">{{ __('seo.config.ga_label') }}</label>
                                    <div class="position-relative">
                                        <div class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                                            <i class="fa-brands fa-google"></i>
                                        </div>
                                        <input type="text" 
                                               name="seo_google_analytics_id" 
                                               class="form-control-premium ps-5" 
                                               value="{{ $gaId }}"
                                               placeholder="{{ __('seo.config.ga_ph') }}">
                                    </div>
                                    <div class="form-text text-muted small mt-1">{{ __('seo.config.ga_help') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="p-4 rounded-3 bg-light border">
                                <h6 class="fw-bold mb-3 text-dark">{{ __('seo.config.banner_title') }}</h6>
                                <small class="d-block text-muted mb-3">{{ __('seo.config.banner_help') }}</small>
                                
                                <div class="mb-3">
                                    <input type="file" 
                                           name="seo_banner_image" 
                                           class="form-control form-control-premium" 
                                           id="seoBannerImage" 
                                           accept="image/*">
                                    
                                    <input type="hidden" name="seo_banner_delete" id="seoBannerDeleteFlag" value="0">
                                </div>
                                
                                <div id="imagePreviewContainer" class="d-flex flex-column align-items-center mt-3">
                                    
                                    @if($bannerUrl)
                                        <div id="existingBanner" class="position-relative w-100">
                                            <img src="{{ asset('storage/' . $bannerUrl) }}?v={{ time() }}" alt="SEO Banner Preview" id="currentBannerImage" class="img-fluid rounded shadow-sm seo-banner-preview">
                                            
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle shadow-sm seo-banner-delete-btn"
                                                    id="deleteBannerBtn"
                                                    title="{{ __('seo.config.delete_banner_title') }}">
                                                <i class="fa-solid fa-trash seo-trash-icon"></i>
                                            </button>
                                        </div>
                                    @endif

                                    <div id="emptyBannerState" class="text-center p-5 border rounded w-100 bg-white seo-banner-empty {{ $bannerUrl ? 'd-none' : '' }}">
                                        <i class="fa-solid fa-image text-muted mb-3 seo-empty-icon"></i>
                                        <p class="small text-muted m-0">{{ __('seo.config.no_banner') }}</p>
                                    </div>
                                    
                                    <div id="newBannerPreview" class="w-100 d-none"></div>
                                    
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                        <i class="fa-solid fa-check me-2"></i> {{ __('seo.save') }}
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    window.seoAlerts = {
        title: "{{ __('seo.config.alerts.remove_title') }}",
        text: "{{ __('seo.config.alerts.remove_text') }}",
        icon: "warning",
        yes_remove: "{{ __('seo.config.alerts.yes_remove') }}",
        cancel: "{{ __('seo.config.alerts.cancel') }}"
    };
</script>
<script src="{{ asset('assets/js/admin-seo.js') }}"></script>
@endpush