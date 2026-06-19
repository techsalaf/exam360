<link rel="stylesheet" href="{{ asset('assets/css/components/settings-notifications.css') }}">

@php
    $sections = array(
        array('key' => 'hero', 'icon' => 'fa-image', 'color' => 'text-primary', 'title' => __('branding.frontend.sections.hero.title'), 'desc' => __('branding.frontend.sections.hero.desc')),
        array('key' => 'features', 'icon' => 'fa-star', 'color' => 'text-warning', 'title' => __('branding.frontend.sections.features.title'), 'desc' => __('branding.frontend.sections.features.desc')),
        array('key' => 'categories', 'icon' => 'fa-layer-group', 'color' => 'text-info', 'title' => __('branding.frontend.sections.categories.title'), 'desc' => __('branding.frontend.sections.categories.desc')),
        array('key' => 'exams', 'icon' => 'fa-file-signature', 'color' => 'text-danger', 'title' => __('branding.frontend.sections.exams.title'), 'desc' => __('branding.frontend.sections.exams.desc')),
        array('key' => 'how_it_works', 'icon' => 'fa-signs-post', 'color' => 'text-success', 'title' => __('branding.frontend.sections.how_it_works.title'), 'desc' => __('branding.frontend.sections.how_it_works.desc')),
        array('key' => 'audience', 'icon' => 'fa-users', 'color' => 'text-secondary', 'title' => __('branding.frontend.sections.audience.title'), 'desc' => __('branding.frontend.sections.audience.desc')),
        array('key' => 'pricing', 'icon' => 'fa-tags', 'color' => 'text-success', 'title' => __('branding.frontend.sections.pricing.title'), 'desc' => __('branding.frontend.sections.pricing.desc')),
        array('key' => 'testimonials', 'icon' => 'fa-comment-dots', 'color' => 'text-primary', 'title' => __('branding.frontend.sections.testimonials.title'), 'desc' => __('branding.frontend.sections.testimonials.desc')),
        array('key' => 'faq', 'icon' => 'fa-circle-question', 'color' => 'text-warning', 'title' => __('branding.frontend.sections.faq.title'), 'desc' => __('branding.frontend.sections.faq.desc')),
        array('key' => 'cta', 'icon' => 'fa-bullhorn', 'color' => 'text-danger', 'title' => __('branding.frontend.sections.cta.title'), 'desc' => __('branding.frontend.sections.cta.desc')),
        
        // Final Fix: Using vivid colors and explicit array syntax
        array('key' => 'admin_preview', 'icon' => 'fa-desktop', 'color' => 'text-info', 'title' => __('branding.frontend.sections.admin_preview.title'), 'desc' => __('branding.frontend.sections.admin_preview.desc')),
        array('key' => 'cms_features', 'icon' => 'fa-pen-to-square', 'color' => 'text-success', 'title' => __('branding.frontend.sections.cms_features.title'), 'desc' => __('branding.frontend.sections.cms_features.desc')),
    );
@endphp

<div class="settings-content">
    <form action="{{ route('admin.settings.branding.update') }}" method="POST">
        @csrf
        <input type="hidden" name="setting_group_key" value="branding_frontend">

        <div class="setting-card">
            <div class="setting-header">
                <h3 class="setting-title">{{ __('branding.frontend.title') }}</h3>
                <p class="setting-desc">{{ __('branding.frontend.desc') }}</p>
            </div>

            <div class="border rounded-3 p-4">
                @foreach($sections as $section)
                    <div class="d-flex align-items-center justify-content-between mb-4 {{ $loop->last ? '' : 'border-bottom pb-4' }}">
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center justify-content-center border rounded-3 p-2 me-3 shadow-sm bg-light {{ $section['color'] }} frontend-section-icon-box">
                                <i class="fa-solid {{ $section['icon'] }} frontend-section-icon"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-main-color m-0">{{ $section['title'] }}</h6>
                                <span class="text-muted small">{{ $section['desc'] }}</span>
                            </div>
                        </div>
                        <div class="form-check form-switch">
                            <input type="hidden" name="frontend_show_{{ $section['key'] }}" value="0">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="frontend_show_{{ $section['key'] }}" 
                                   value="1" 
                                   id="switch_{{ $section['key'] }}"
                                   {{ ($settings['frontend_show_' . $section['key']] ?? '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label visually-hidden" for="switch_{{ $section['key'] }}">
                                {{ $section['title'] }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 pt-3 border-top text-end">
                <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                    <i class="fa-solid fa-check me-2"></i> {{ __('branding.save') }}
                </button>
            </div>
        </div>
    </form>
</div>