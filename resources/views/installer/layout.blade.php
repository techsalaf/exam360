<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ziexam AI Installer - {{ $title ?? 'Installation' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('installer/images/icon.png') }}">

    <!-- Assets -->
    <link href="{{ asset('assets/fonts/plus-jakarta/stylesheet.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('installer/css/installer.css') }}" rel="stylesheet"> 
</head>
<body>

    <div class="installer-wrapper">
        <div class="installer-card">
            
            <div class="installer-header">
                <div class="brand-section">
                    <!-- Clean HTML: CSS handles the sizing -->
                    <div class="brand-icon">
                        <img src="{{ asset('installer/images/icon.png') }}" alt="Ziexam AI">
                    </div>
                    <div class="brand-info">
                        <h1 class="brand-text">Ziexam AI</h1>
                        <span class="brand-subtitle">Installation Wizard</span>
                    </div>
                </div>
                <span class="version-badge">v1.0.0</span>
            </div>

            <div class="installer-content">
                @if (session('message'))
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('message') }}
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>

            <div class="installer-progress-area">
                <div class="progress-track">
                    @php
                        $currentRoute = request()->route()->getName();
                        
                        $steps = [
                            'install.welcome', 
                            'install.requirements', 
                            'install.permissions', 
                            'install.database', 
                            'install.application', 
                            'install.finish'
                        ];
                        
                        $totalSteps = count($steps);
                        $currentStepIndex = array_search($currentRoute, $steps);
                        
                        if ($currentStepIndex === false) {
                            $currentStepIndex = 0;
                        }
                    @endphp
                    
                    @foreach($steps as $index => $stepName)
                        <div class="progress-step {{ $index <= $currentStepIndex ? 'active' : '' }}"></div>
                    @endforeach
                </div>
                <div class="progress-label">
                    Step {{ $currentStepIndex + 1 }} of {{ $totalSteps }}
                </div>
            </div>
            
        </div>

        <div class="installer-footer">
            <p>Need help? <a href="https://documentations.codezisoft.com/zi-exam/" target="_blank">View Documentation</a></p>
            <p class="copyright">&copy; {{ date('Y') }} Ziexam AI. All rights reserved.</p>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('installer/js/installer.js') }}"></script>
</body>
</html>