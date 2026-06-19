@php
    // Data passed from SettingsController
    $robotsMeta = $seoSettings['sitemap_robots_meta'] ?? 'index, follow';
    $lastGenerated = $seoSettings['sitemap_last_generated'] ?? __('seo.sitemap.never');
    // $sitemapExists is also passed from controller
@endphp

<div class="tab-pane fade show active" id="sitemap-content" role="tabpanel">
    <div class="settings-content">
        <form action="{{ route('admin.settings.seo.update') }}" method="POST">
            @csrf

            <input type="hidden" name="setting_group_key" value="sitemap">

            <div class="setting-card">
                
                <div class="setting-header">
                    <h3 class="setting-title">{{ __('seo.sitemap.title') }}</h3>
                    <p class="setting-desc">{{ __('seo.sitemap.desc') }}</p>
                </div>

                <div class="border rounded-3 p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="seo-sitemap-icon-box me-3">
                            <i class="fa-solid fa-sitemap seo-sitemap-icon"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark m-0">{{ __('seo.sitemap.crawling_title') }}</h6>
                            <span class="text-muted small">{{ __('seo.sitemap.crawling_desc') }}</span>
                        </div>
                    </div>

                    <div class="row g-4">
                        
                        <div class="col-md-12">
                            <label class="form-label-premium">{{ __('seo.sitemap.robots_label') }}</label>
                            <select name="sitemap_robots_meta" class="form-control-premium form-select">
                                <option value="index, follow" {{ $robotsMeta == 'index, follow' ? 'selected' : '' }}>{{ __('seo.sitemap.robots_options.index_follow') }}</option>
                                <option value="noindex, follow" {{ $robotsMeta == 'noindex, follow' ? 'selected' : '' }}>{{ __('seo.sitemap.robots_options.noindex_follow') }}</option>
                                <option value="index, nofollow" {{ $robotsMeta == 'index, nofollow' ? 'selected' : '' }}>{{ __('seo.sitemap.robots_options.index_nofollow') }}</option>
                                <option value="noindex, nofollow" {{ $robotsMeta == 'noindex, nofollow' ? 'selected' : '' }}>{{ __('seo.sitemap.robots_options.noindex_nofollow') }}</option>
                            </select>
                            <div class="form-text text-muted small mt-1">{{ __('seo.sitemap.robots_help') }}</div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="p-4 rounded-3 bg-light border">
                                <h6 class="fw-bold text-dark mb-3">{{ __('seo.sitemap.status_title') }}</h6>
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-file-code text-muted me-2 seo-sitemap-small-icon"></i>
                                            <span class="text-muted small me-2">{{ __('seo.sitemap.file_url') }}</span>
                                            <a href="{{ url('sitemap.xml') }}" target="_blank" class="text-primary fw-bold text-decoration-none">
                                                {{ url('sitemap.xml') }} <i class="fa-solid fa-external-link-alt ms-1 small"></i>
                                            </a>
                                        </div>
                                        
                                        @if($sitemapExists)
                                            <a href="{{ route('admin.settings.seo.download-sitemap') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="fa-solid fa-download me-1"></i> {{ __('seo.sitemap.download_xml') }}
                                            </a>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-clock text-muted me-2 seo-sitemap-small-icon"></i>
                                        <span class="text-muted small me-2">{{ __('seo.sitemap.last_gen') }}</span>
                                        <span class="text-dark fw-bold small">{{ $lastGenerated }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <p class="small text-muted mb-0">
                                <i class="fa-solid fa-circle-info me-1"></i> 
                                {!! __('seo.sitemap.info_text') !!}
                            </p>
                        </div>

                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                    
                    <button type="submit" formaction="{{ route('admin.settings.seo.generate-sitemap') }}" class="btn btn-white border text-muted fw-bold shadow-sm rounded-pill px-4">
                        <i class="fa-solid fa-arrows-rotate me-2"></i> {{ __('seo.generate_now') }}
                    </button>

                    <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                        <i class="fa-solid fa-check me-2"></i> {{ __('seo.save_config') }}
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>