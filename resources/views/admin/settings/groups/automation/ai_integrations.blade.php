@php
    // Data passed from SettingsController
    $driver        = $automationSettings['ai_driver'] ?? 'disabled';
    $geminiKey     = $automationSettings['ai_gemini_api_key'] ?? '';
    $geminiModel   = $automationSettings['ai_gemini_model'] ?? 'gemini-1.5-flash';
    $openaiKey     = $automationSettings['ai_openai_api_key'] ?? '';
    $openaiModel   = $automationSettings['ai_openai_model'] ?? 'gpt-3.5-turbo';
@endphp

<div class="tab-pane fade show active" id="ai-content" role="tabpanel">
    <div class="settings-content">
        <form action="{{ route('admin.settings.automation.update') }}" method="POST">
            @csrf
            <input type="hidden" name="setting_group_key" value="ai_integrations">

            <div class="setting-card">
                
                <div class="setting-header">
                    <h3 class="setting-title">{{ __('automation.ai.title') }}</h3>
                    <p class="setting-desc">{{ __('automation.ai.desc') }}</p>
                </div>

                <!-- Driver Selection -->
                <div class="border rounded-3 p-4 mb-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="ai-icon-box me-3">
                            <i class="fa-solid fa-robot ai-icon"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark m-0">{{ __('automation.ai.primary_driver') }}</h6>
                            <span class="text-muted small">{{ __('automation.ai.driver_desc') }}</span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <select name="ai_driver" id="aiDriverSelect" class="form-control-premium form-select">
                            <option value="disabled" {{ $driver == 'disabled' ? 'selected' : '' }}>{{ __('automation.ai.disabled') }}</option>
                            <option value="gemini" {{ $driver == 'gemini' ? 'selected' : '' }}>{{ __('automation.ai.driver_gemini') }}</option>
                            <option value="openai" {{ $driver == 'openai' ? 'selected' : '' }}>{{ __('automation.ai.driver_openai') }}</option>
                        </select>
                    </div>
                </div>
                
                <!-- 1. Gemini Configuration Box -->
                <div id="geminiSettings" class="border rounded-3 p-4 ai-settings-box {{ $driver == 'gemini' ? 'active' : '' }}">
                    <div class="d-flex align-items-center mb-4">
                        <div class="ai-icon-box me-3 text-info">
                            <i class="fa-brands fa-google ai-icon"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark m-0">{{ __('automation.ai.gemini.title') }}</h6>
                            <span class="text-muted small">{{ __('automation.ai.gemini.desc') }}</span>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label-premium">{{ __('automation.ai.gemini.api_key') }}</label>
                            <div class="position-relative">
                                <input type="password" 
                                       name="ai_gemini_api_key" 
                                       class="form-control-premium pe-5" 
                                       value="{{ $geminiKey }}"
                                       placeholder="{{ __('automation.ai.gemini.key_ph') }}">
                                <button type="button" class="btn border-0 bg-transparent text-muted position-absolute top-50 end-0 translate-middle-y me-2 ai-pass-toggle ai-toggle-btn">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            @error('ai_gemini_api_key') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">{{ __('automation.ai.gemini.model') }}</label>
                            <input type="text" 
                                   name="ai_gemini_model" 
                                   class="form-control-premium" 
                                   value="{{ $geminiModel }}"
                                   placeholder="gemini-1.5-flash">
                            <div class="form-text text-muted small mt-1">{{ __('automation.recommended') }} <code>gemini-1.5-flash</code></div>
                        </div>
                    </div>
                </div>

                <!-- 2. OpenAI Configuration Box -->
                <div id="openaiSettings" class="border rounded-3 p-4 ai-settings-box {{ $driver == 'openai' ? 'active' : '' }}">
                    <div class="d-flex align-items-center mb-4">
                        <div class="ai-icon-box me-3 text-success">
                            <i class="fa-solid fa-bolt ai-icon"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark m-0">{{ __('automation.ai.openai.title') }}</h6>
                            <span class="text-muted small">{{ __('automation.ai.openai.desc') }}</span>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label-premium">{{ __('automation.ai.openai.api_key') }}</label>
                            <div class="position-relative">
                                <input type="password" 
                                       name="ai_openai_api_key" 
                                       class="form-control-premium pe-5" 
                                       value="{{ $openaiKey }}"
                                       placeholder="{{ __('automation.ai.openai.key_ph') }}">
                                <button type="button" class="btn border-0 bg-transparent text-muted position-absolute top-50 end-0 translate-middle-y me-2 ai-pass-toggle ai-toggle-btn">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            @error('ai_openai_api_key') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">{{ __('automation.ai.openai.model') }}</label>
                            <input type="text" 
                                   name="ai_openai_model" 
                                   class="form-control-premium" 
                                   value="{{ $openaiModel }}"
                                   placeholder="gpt-3.5-turbo">
                            <div class="form-text text-muted small mt-1">{{ __('automation.recommended') }} <code>gpt-3.5-turbo</code> {{ __('automation.or') }} <code>gpt-4o</code></div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                        <i class="fa-solid fa-check me-2"></i> {{ __('automation.save') }}
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

@push('scripts')
{{-- Envato Fix: External JS only --}}
<script src="{{ asset('assets/js/admin-ai-integrations.js') }}"></script>
@endpush