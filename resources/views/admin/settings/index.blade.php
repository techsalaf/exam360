@extends('layouts.admin')

@section('title', 'System Settings')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-settings.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/certificate-editor.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/settings-notifications.css') }}">
@endpush

@section('content')
<div class="container-fluid p-0">
    
    <div class="settings-container">
        
        <!-- SIDEBAR -->
        @include('admin.settings.partials.sidebar')

        <!-- CONTENT AREA -->
        <div class="settings-content">
            <div class="tab-content" id="settingsTabContent">
                
                <!-- 1. SYSTEM GROUPS -->
                <div class="tab-pane fade show active" id="pane-general" role="tabpanel">
                    @include('admin.settings.groups.system.general')
                </div>
                
                <div class="tab-pane fade" id="pane-sysconfig" role="tabpanel">
                    @include('admin.settings.groups.system.core')
                </div>
                
                <div class="tab-pane fade" id="pane-roles" role="tabpanel">
                     @include('admin.settings.groups.system.roles', compact('roles', 'permissionsStructure', 'defaultRoleId'))
                </div>

                <div class="tab-pane fade" id="pane-maintenance" role="tabpanel">
                    @include('admin.settings.groups.system.maintenance')
                </div>

                <!-- 2. BRANDING GROUPS -->
                <div class="tab-pane fade" id="pane-branding-logo" role="tabpanel">
                    @include('admin.settings.groups.branding.logo')
                </div>

                <div class="tab-pane fade" id="pane-branding-registration" role="tabpanel">
                    @include('admin.settings.groups.branding.registration')
                </div>

                <div class="tab-pane fade" id="pane-branding-certificate" role="tabpanel">
                    @include('admin.settings.groups.branding.certificate')
                </div>
                <div class="tab-pane fade" id="pane-branding-frontend" role="tabpanel">
                    @include('admin.settings.groups.branding.frontend')
                </div>
                <div class="tab-pane fade" id="pane-branding-styling" role="tabpanel">
                    @include('admin.settings.groups.branding.styling')
                </div>
                
                <!-- 3. NOTIFICATIONS GROUPS -->
                
                <div class="tab-pane fade" id="pane-notifications" role="tabpanel">
                    @include('admin.settings.groups.notifications.general')
                </div>

                <div class="tab-pane fade" id="pane-email" role="tabpanel">
                    @include('admin.settings.groups.notifications.email')
                </div>

                <div class="tab-pane fade" id="pane-social" role="tabpanel">
                    @include('admin.settings.groups.notifications.social')
                </div>

                <!-- 4. PAYMENTS GROUPS -->
                <div class="tab-pane fade" id="pane-payment" role="tabpanel">
                    @include('admin.settings.groups.payments.gateways', compact('settings')) 
                </div>
                
                <div class="tab-pane fade" id="pane-currency" role="tabpanel">
                    @include('admin.settings.groups.payments.currency', compact('settings', 'currencies'))
                </div>
                
                <div class="tab-pane fade" id="pane-tax" role="tabpanel">
                    @include('admin.settings.groups.payments.tax', compact('settings'))
                </div>
                
                <!-- 5. LOCALIZATION GROUPS -->
                <div class="tab-pane fade" id="pane-language" role="tabpanel">
                    @include('admin.settings.groups.localization.localization_defaults') 
                </div>

                <!-- 6. SEO GROUPS -->
                <div class="tab-pane fade" id="pane-seo" role="tabpanel">
                    @include('admin.settings.groups.seo.seo_configuration')
                </div>
                <div class="tab-pane fade" id="pane-sitemap" role="tabpanel">
                    @include('admin.settings.groups.seo.sitemap_xml')
                </div>

                <!-- 7. AUTOMATION GROUPS -->
                <div class="tab-pane fade" id="pane-ai" role="tabpanel">
                    @include('admin.settings.groups.automation.ai_integrations')
                </div>
                <div class="tab-pane fade" id="pane-cron" role="tabpanel">
                    @include('admin.settings.groups.automation.cron_jobs')
                </div>
                <div class="tab-pane fade" id="pane-extensions" role="tabpanel">
                    @include('admin.settings.groups.automation.extensions')
                </div>

                <!-- 8. SECURITY GROUPS -->
                <div class="tab-pane fade" id="pane-gdpr" role="tabpanel">
                    @include('admin.settings.groups.security.gdpr_cookie')
                </div>
                <div class="tab-pane fade" id="pane-policy" role="tabpanel">
                    @include('admin.settings.groups.security.policy_pages')
                </div>
                
                <!-- 9. CONTENT GROUPS -->
                <div class="tab-pane fade" id="pane-pages" role="tabpanel">
                </div>
                
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script>
    function activateTabFromHash() {
        const hash = window.location.hash;
        if (!hash) return; 

        const $targetLink = $(`[data-bs-target="${hash}"]`);

        if ($targetLink.length) {
            $targetLink.tab('show'); 
            
            const $parentCollapse = $targetLink.closest('.collapse.settings-group-body');
            if ($parentCollapse.length && !$parentCollapse.hasClass('show')) {
                const collapseInstance = new bootstrap.Collapse($parentCollapse[0]);
                collapseInstance.show();
            }
            $targetLink[0].scrollIntoView({ behavior: 'instant', block: 'center' });
        }
    }

    $(document).ready(function() {
        activateTabFromHash();
        window.addEventListener('hashchange', activateTabFromHash);
        
        $('.settings-sidebar-wrapper button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
            const newHash = $(e.target).data('bs-target');
            if (newHash && newHash !== window.location.hash) {
                window.history.replaceState(null, null, newHash);
            }
        });
    });
</script>
@endpush