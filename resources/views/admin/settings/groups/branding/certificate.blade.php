@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/components/certificate-editor.css') }}">
@endpush

@php
    $title = $settings['cert_title'] ?? __('branding.certificate.default_title');
    // FIX 2: Using translated default body
    $defaultBody = __('branding.certificate.default_body');
    $body = $settings['cert_body'] ?? $defaultBody;
    $dateText = $settings['cert_date_text'] ?? __('branding.certificate.default_date');
    $signName = $settings['cert_signature'] ?? __('branding.certificate.default_sign');
    $orientation = $settings['cert_orientation'] ?? 'landscape';
    $fontFamily = $settings['cert_font_family'] ?? 'font-pinyon';
    
    $bgUrl = !empty($settings['cert_bg_image']) ? asset('storage/' . $settings['cert_bg_image']) : null;
    $sigUrl = !empty($settings['cert_sig_image']) ? asset('storage/' . $settings['cert_sig_image']) : null;
@endphp

<form id="certificateForm" action="{{ route('admin.settings.branding.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="setting_group_key" value="branding_certificate">
    <input type="hidden" name="type" value="certificate_design">
    <input type="hidden" name="cert_template_id" value="default">
    <input type="hidden" name="cert_markup" id="hiddenMarkup">
    <input type="hidden" name="cert_orientation" id="orientationInput" value="{{ $orientation }}">
    <input type="hidden" name="cert_primary_color" value="#000000">
    <input type="hidden" name="cert_page_size" value="A4">
    <input type="hidden" name="cert_watermark_opacity" value="20">
    
    <input type="hidden" name="cert_remove_bg" id="removeBgHidden" value="0">
    <input type="hidden" name="cert_remove_sig" id="removeSigHidden" value="0">

    <!-- Mobile Blocker Message -->
    <div class="zi-mobile-blocker">
        <div class="zi-mobile-icon-container">
            <i class="fa-solid fa-desktop zi-mobile-icon"></i>
        </div>
        <h4 class="zi-mobile-title">{{ __('branding.certificate.desktop_required') }}</h4>
        <p class="zi-mobile-desc">
            {{ __('branding.certificate.desktop_desc') }}
        </p>
    </div>

    <!-- Desktop Studio Wrapper -->
    <div class="zi-studio-wrapper">
        <header class="zi-studio-header">
            <div>
                <span class="zi-brand-title">{{ __('branding.certificate.title') }}</span>
                <span class="zi-brand-sub">{{ __('branding.certificate.sub') }}</span>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn-circle" id="btnReset" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('branding.certificate.reset_tooltip') }}">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
            </div>
        </header>

        <div class="zi-studio-body">
            
            <aside class="zi-controls">
                <div class="zi-controls-scroll">
                    
                    <div class="zi-accordion-item">
                        <button type="button" class="zi-accordion-header active" data-target="accLayout">
                            <span><i class="fa-solid fa-layer-group me-2"></i> {{ __('branding.certificate.layout_tab') }}</span>
                            <i class="fa-solid fa-chevron-down small"></i>
                        </button>
                        <div id="accLayout" class="zi-accordion-body show">
                            <label class="zi-label">{{ __('branding.certificate.orientation') }}</label>
                            <div class="zi-segmented" id="orientationSeg">
                                <div class="zi-seg-opt {{ $orientation == 'landscape' ? 'active' : '' }}" data-val="landscape">{{ __('branding.certificate.landscape') }}</div>
                                <div class="zi-seg-opt {{ $orientation == 'portrait' ? 'active' : '' }}" data-val="portrait">{{ __('branding.certificate.portrait') }}</div>
                            </div>

                            <label class="zi-label mt-3">{{ __('branding.certificate.typography') }}</label>
                            <select class="zi-select" id="fontSelector" name="cert_font_family">
                                <option value="font-pinyon" {{ $fontFamily == 'font-pinyon' ? 'selected' : '' }}>{{ __('branding.certificate.font_pinyon_elegant') }}</option>
                                <option value="font-great-vibes" {{ $fontFamily == 'font-great-vibes' ? 'selected' : '' }}>{{ __('branding.certificate.font_great_vibes_cursive') }}</option>
                                <option value="font-cinzel" {{ $fontFamily == 'font-cinzel' ? 'selected' : '' }}>{{ __('branding.certificate.font_cinzel_formal') }}</option>
                                <option value="font-lato" {{ $fontFamily == 'font-lato' ? 'selected' : '' }}>{{ __('branding.certificate.font_lato_modern') }}</option>
                            </select>

                            <label class="zi-label mt-3">{{ __('branding.certificate.bg_image') }}</label>
                            <div class="zi-file-upload">
                                <input type="file" name="cert_bg_image" id="bgImageInput" class="zi-file-input" accept="image/*">
                                <div class="text-muted small">{{ __('branding.certificate.bg_help') }}</div>
                            </div>
                            
                            <div class="text-muted small mt-2 zi-small-help">
                                <strong>{{ __('branding.certificate.size_help') }}</strong>
                            </div>

                            <div class="text-end">
                                <button type="button" id="removeBgBtn" class="zi-remove-link {{ $bgUrl ? '' : 'd-none' }}">{{ __('branding.certificate.remove_bg') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="zi-accordion-item">
                        <button type="button" class="zi-accordion-header" data-target="accContent">
                            <span><i class="fa-solid fa-pen-nib me-2"></i> {{ __('branding.certificate.content_tab') }}</span>
                            <i class="fa-solid fa-chevron-down small"></i>
                        </button>
                        <div id="accContent" class="zi-accordion-body">
                            <label class="zi-label">{{ __('branding.certificate.heading') }}</label>
                            <input type="text" class="zi-input mb-3" data-model="previewTitle" name="cert_title" value="{{ $title }}">
                            
                            <label class="zi-label">{{ __('branding.certificate.alignment') }}</label>
                            <div class="btn-group w-100 mb-3" role="group" data-target="previewTitleContainer">
                                <button type="button" class="btn btn-sm btn-light border" data-align="left"><i class="fa-solid fa-align-left"></i></button>
                                <button type="button" class="btn btn-sm btn-light border align-active" data-align="center"><i class="fa-solid fa-align-center"></i></button>
                                <button type="button" class="btn btn-sm btn-light border" data-align="right"><i class="fa-solid fa-align-right"></i></button>
                            </div>

                            <label class="zi-label">{{ __('branding.certificate.body_text') }}</label>
                            <textarea class="zi-textarea mb-2" data-model="previewBody" name="cert_body">{{ $body }}</textarea>
                            <button type="button" class="btn btn-sm btn-brand w-100 mb-2" data-bs-toggle="modal" data-bs-target="#varModal">
                                <i class="fa-solid fa-bolt me-1"></i> {{ __('branding.certificate.insert_var') }}
                            </button>
                            <small class="text-muted zi-small-help">{{ __('branding.certificate.var_help') }}</small>
                        </div>
                    </div>

                    <div class="zi-accordion-item">
                        <button type="button" class="zi-accordion-header" data-target="accFooter">
                            <span><i class="fa-solid fa-signature me-2"></i> {{ __('branding.certificate.footer_tab') }}</span>
                            <i class="fa-solid fa-chevron-down small"></i>
                        </button>
                        <div id="accFooter" class="zi-accordion-body">
                            <label class="zi-label">{{ __('branding.certificate.date') }}</label>
                            <input type="text" class="zi-input mb-3" data-model="previewDate" name="cert_date_text" value="{{ $dateText }}">
                            
                            <label class="zi-label">{{ __('branding.certificate.sig_mode') }}</label>
                            <div class="zi-segmented">
                                <label class="zi-seg-opt active"><input type="radio" name="sig_type_ui" value="text" checked hidden> {{ __('branding.certificate.text') }}</label>
                                <label class="zi-seg-opt"><input type="radio" name="sig_type_ui" value="image" hidden> {{ __('branding.certificate.image') }}</label>
                            </div>

                            <div id="sigTextOptions">
                                <label class="zi-label">{{ __('branding.certificate.sig_name') }}</label>
                                <input type="text" class="zi-input" id="sigTextInput" name="cert_signature" value="{{ $signName }}" data-model="previewSign">
                            </div>

                            <div class="mt-3">
                                <label class="zi-label">{{ __('branding.certificate.upload_sig') }}</label>
                                <input type="file" name="cert_sig_image" id="sigImgInput" class="form-control form-control-sm">
                                <div class="text-end">
                                    <button type="button" id="removeSigBtn" class="zi-remove-link d-none">{{ __('branding.certificate.remove_sig') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </aside>

            <main class="zi-canvas-area" id="studioCanvas">
                <div class="zi-zoom-floater">
                    <button type="button" class="btn btn-sm p-0 border-0" data-zoom-act="-0.1"><i class="fa-solid fa-minus"></i></button>
                    <span class="small fw-bold zi-zoom-value" id="zoomVal">75%</span>
                    <button type="button" class="btn btn-sm p-0 border-0" data-zoom-act="0.1"><i class="fa-solid fa-plus"></i></button>
                </div>

                <div id="certificatePaper" class="zi-paper {{ $fontFamily }}" 
                     data-bg="{{ $bgUrl }}" 
                     style="{{ $bgUrl ? 'background-image: url('.$bgUrl.')' : '' }}">
                    <div class="zi-paper-content">
                        
                        <div class="cert-header text-center" id="previewTitleContainer">
                            <h1 id="previewTitle"></h1>
                        </div>

                        <div class="cert-body text-center" id="previewBody"></div>

                        <div class="cert-footer">
                            <div class="text-left">
                                <p id="previewDate" class="mb-0 text-muted"></p>
                            </div>
                            <div class="text-center" id="previewSignContainer" data-img-src="{{ $sigUrl }}">
                                <div class="cert-sig-line"></div>
                                <p id="previewSign" class="fw-bold mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="ziToast">{{ __('branding.certificate.toast_saved') }}</div>
            </main>
        </div>
        
        <div class="zi-footer-bar">
            {{-- FIX 3: Event handled in external JS file --}}
            <button type="button" class="btn btn-glass rounded-pill px-4 shadow-sm" id="btnCancel">{{ __('branding.cancel') }}</button>
            <button type="button" id="btnPublish" class="btn btn-brand rounded-pill px-5 shadow-lg">
                <i class="fa-solid fa-check me-2"></i> {{ __('branding.publish') }}
            </button>
        </div>
    </div>
</form>

<div class="modal fade" id="varModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">{{ __('branding.certificate.variables_title') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="zi-var-grid">
                    <button class="zi-var-card insert-var-btn" data-variable="@{{full_name}}" data-bs-dismiss="modal">
                        <b>@{{full_name}}</b> <br><small class="text-muted">{{ __('branding.certificate.var_name') }}</small>
                    </button>
                    <button class="zi-var-card insert-var-btn" data-variable="@{{exam_title}}" data-bs-dismiss="modal">
                        <b>@{{exam_title}}</b> <br><small class="text-muted">{{ __('branding.certificate.var_exam') }}</small>
                    </button>
                    <button class="zi-var-card insert-var-btn" data-variable="@{{score}}" data-bs-dismiss="modal">
                        <b>@{{score}}</b> <br><small class="text-muted">{{ __('branding.certificate.var_score') }}</small>
                    </button>
                    <button class="zi-var-card insert-var-btn" data-variable="@{{completed_at}}" data-bs-dismiss="modal">
                        <b>@{{completed_at}}</b> <br><small class="text-muted">{{ __('branding.certificate.var_date') }}</small>
                    </button>
                    <button class="zi-var-card insert-var-btn" data-variable="{qrcode}" data-bs-dismiss="modal">
                        <b>{qrcode}</b> <br><small class="text-muted">{{ __('branding.certificate.var_qr') }}</small>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    {{-- FIX 1: Removed all inline <script> blocks --}}
    <script src="{{ asset('assets/js/components/certificate-studio.js') }}"></script>
@endpush