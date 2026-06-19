<!-- Load Premium Preview CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/frontend/design2/design2-preview.css') }}">

<section class="d2-preview-section">
    <div class="container">
        
        <div class="d2-preview-showcase">
            <div class="d2-preview-row">
                
                <!-- Left Side: Large Visual Mockup -->
                <div class="d2-preview-visual-col">
                    <div class="d2-preview-mockup-wrap">
                        @if(!empty($rawSettings['admin_preview_image']))
                            <img src="{{ asset('storage/' . $rawSettings['admin_preview_image']) }}" 
                                 class="d2-preview-img" 
                                 alt="Admin Dashboard Preview">
                        @else
                            <!-- Placeholder if no image exists -->
                            <div class="d2-preview-img bg-light d-flex align-items-center justify-content-center" style="height: 500px;">
                                <i class="fa-solid fa-desktop fa-5x opacity-10"></i>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Side: Content & Feature Items -->
                <div class="d2-preview-content-col">
                    
                    <h2 class="d2-preview-title">
                        {{ get_trans($settings['admin_preview_title'] ?? 'Control Everything from One Place') }}
                    </h2>
                    <p class="d2-preview-subtitle">
                        {{ get_trans($settings['admin_preview_subtitle'] ?? 'Our powerful admin dashboard gives you complete control over your exam platform.') }}
                    </p>

                    <!-- Feature Items Loop -->
                    @php
                        // Icons for the two feature highlights
                        $previewIcons = [1 => 'fa-chart-pie', 2 => 'fa-users-gear'];
                    @endphp

                    @for($i=1; $i<=2; $i++)
                        @php
                            $featTitle = get_trans($settings["admin_feat_{$i}_title"] ?? '');
                            if(empty($featTitle)) continue;
                        @endphp
                        
                        <div class="d2-preview-feat-item">
                            <div class="d2-preview-feat-icon">
                                <i class="fa-solid {{ $previewIcons[$i] ?? 'fa-check' }}"></i>
                            </div>
                            <div class="d2-preview-feat-content">
                                <h6>{{ $featTitle }}</h6>
                                <p>{{ get_trans($settings["admin_feat_{$i}_desc"] ?? '') }}</p>
                            </div>
                        </div>
                    @endfor

                </div>

            </div>
        </div>

    </div>
</section>