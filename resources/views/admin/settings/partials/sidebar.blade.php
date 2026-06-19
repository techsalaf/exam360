<div class="settings-sidebar-area">
    
    <!-- MOBILE FILTER TOGGLE (Visible < 992px) -->
    <div class="d-lg-none mb-3">
        <button class="btn w-100 d-flex align-items-center justify-content-between px-3 py-2 rounded-3 shadow-sm bg-white border" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#settingsSidebarOffcanvas" 
                aria-controls="settingsSidebarOffcanvas">
            
            <div class="d-flex align-items-center gap-2">
                <div class="bg-dark text-white rounded-2 d-flex align-items-center justify-content-center mobile-icon-box">
                    <i class="fa-solid fa-sliders fs-sm"></i>
                </div>
                <span class="fw-bold text-dark fs-xs">{{ __('settings.mobile_menu') }}</span>
            </div>
            
            <i class="fa-solid fa-chevron-right text-muted fs-xs"></i>
        </button>
    </div>

    <!-- SIDEBAR CONTAINER -->
    <div class="offcanvas-lg offcanvas-start settings-sidebar-wrapper" tabindex="-1" id="settingsSidebarOffcanvas">
        
        <!-- Mobile Offcanvas Header -->
        <div class="offcanvas-header border-bottom p-3 d-lg-none">
            <h5 class="offcanvas-title fw-bold text-dark">{{ __('settings.nav_title') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#settingsSidebarOffcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-0">
            <div class="accordion nav flex-column nav-settings w-100" id="settingsAccordion">

                <!-- System Settings (Blue) -->
                <div class="settings-group">
                    <button class="settings-group-header" type="button" data-bs-toggle="collapse" data-bs-target="#group-system" aria-expanded="true" aria-controls="group-system">
                        <div class="d-flex align-items-center gap-2">
                            <div class="group-icon group-icon-blue">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <span class="group-label">{{ __('settings.groups.system') }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-down group-arrow"></i>
                    </button>
                    <div id="group-system" class="collapse show settings-group-body" data-bs-parent="#settingsAccordion">
                        <button class="nav-link active" id="tab-general" data-bs-toggle="pill" data-bs-target="#pane-general" type="button" role="tab" aria-controls="pane-general" aria-selected="true">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-sliders"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.general.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.general.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-sysconfig" data-bs-toggle="pill" data-bs-target="#pane-sysconfig" type="button" role="tab" aria-controls="pane-sysconfig" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-server"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.config.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.config.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-roles" data-bs-toggle="pill" data-bs-target="#pane-roles" type="button" role="tab" aria-controls="pane-roles" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-user-shield"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.roles.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.roles.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-maintenance" data-bs-toggle="pill" data-bs-target="#pane-maintenance" type="button" role="tab" aria-controls="pane-maintenance" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.maintenance.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.maintenance.sub') }}</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Appearance (Purple) -->
                <div class="settings-group">
                    <button class="settings-group-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#group-branding" aria-expanded="false" aria-controls="group-branding">
                        <div class="d-flex align-items-center gap-2">
                            <div class="group-icon group-icon-purple">
                                <i class="fa-solid fa-paintbrush"></i>
                            </div>
                            <span class="group-label">{{ __('settings.groups.appearance') }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-down group-arrow"></i>
                    </button>
                    <div id="group-branding" class="collapse settings-group-body" data-bs-parent="#settingsAccordion">
                        <button class="nav-link" id="tab-branding-logo" data-bs-toggle="pill" data-bs-target="#pane-branding-logo" type="button" role="tab" aria-controls="pane-branding-logo" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-palette"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.logo.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.logo.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-registration" data-bs-toggle="pill" data-bs-target="#pane-branding-registration" type="button" role="tab" aria-controls="pane-branding-registration" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-user-plus"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.registration.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.registration.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-certificate" data-bs-toggle="pill" data-bs-target="#pane-branding-certificate" type="button" role="tab" aria-controls="pane-branding-certificate" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-certificate"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.certificates.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.certificates.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-frontend" data-bs-toggle="pill" data-bs-target="#pane-branding-frontend" type="button" role="tab" aria-controls="pane-branding-frontend" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-desktop"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.frontend.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.frontend.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-customcss" data-bs-toggle="pill" data-bs-target="#pane-branding-styling" type="button" role="tab" aria-controls="pane-branding-styling" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-brands fa-css3-alt"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.css.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.css.sub') }}</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Communication (Orange) -->
                <div class="settings-group">
                    <button class="settings-group-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#group-notifications" aria-expanded="false" aria-controls="group-notifications">
                        <div class="d-flex align-items-center gap-2">
                            <div class="group-icon group-icon-orange">
                                <i class="fa-solid fa-comments"></i>
                            </div>
                            <span class="group-label">{{ __('settings.groups.communication') }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-down group-arrow"></i>
                    </button>
                    <div id="group-notifications" class="collapse settings-group-body" data-bs-parent="#settingsAccordion">
                        <button class="nav-link" id="tab-notifications" data-bs-toggle="pill" data-bs-target="#pane-notifications" type="button" role="tab" aria-controls="pane-notifications" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-regular fa-bell"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.alerts.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.alerts.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-email" data-bs-toggle="pill" data-bs-target="#pane-email" type="button" role="tab" aria-controls="pane-email" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-envelope"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.email.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.email.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-social" data-bs-toggle="pill" data-bs-target="#pane-social" type="button" role="tab" aria-controls="pane-social" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-share-nodes"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.social.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.social.sub') }}</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Billing (Green) -->
                <div class="settings-group">
                    <button class="settings-group-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#group-payments" aria-expanded="false" aria-controls="group-payments">
                        <div class="d-flex align-items-center gap-2">
                            <div class="group-icon group-icon-green">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                            <span class="group-label">{{ __('settings.groups.billing') }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-down group-arrow"></i>
                    </button>
                    <div id="group-payments" class="collapse settings-group-body" data-bs-parent="#settingsAccordion">
                        <button class="nav-link" id="tab-payment" data-bs-toggle="pill" data-bs-target="#pane-payment" type="button" role="tab" aria-controls="pane-payment" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-wallet"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.gateways.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.gateways.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-currency" data-bs-toggle="pill" data-bs-target="#pane-currency" type="button" role="tab" aria-controls="pane-currency" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-money-bill-transfer"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.currency.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.currency.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-tax" data-bs-toggle="pill" data-bs-target="#pane-tax" type="button" role="tab" aria-controls="pane-tax" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.tax.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.tax.sub') }}</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Regional (Cyan) -->
                <div class="settings-group">
                    <button class="settings-group-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#group-localization" aria-expanded="false" aria-controls="group-localization">
                        <div class="d-flex align-items-center gap-2">
                            <div class="group-icon group-icon-cyan">
                                <i class="fa-solid fa-globe"></i>
                            </div>
                            <span class="group-label">{{ __('settings.groups.regional') }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-down group-arrow"></i>
                    </button>
                    <div id="group-localization" class="collapse settings-group-body" data-bs-parent="#settingsAccordion">
                        <button class="nav-link" id="tab-language" data-bs-toggle="pill" data-bs-target="#pane-language" type="button" role="tab" aria-controls="pane-language" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-earth-americas"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.language.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.language.sub') }}</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Visibility (Indigo) -->
                <div class="settings-group">
                    <button class="settings-group-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#group-seo" aria-expanded="false" aria-controls="group-seo">
                        <div class="d-flex align-items-center gap-2">
                            <div class="group-icon group-icon-indigo">
                                <i class="fa-solid fa-eye"></i>
                            </div>
                            <span class="group-label">{{ __('settings.groups.visibility') }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-down group-arrow"></i>
                    </button>
                    <div id="group-seo" class="collapse settings-group-body" data-bs-parent="#settingsAccordion">
                        <button class="nav-link" id="tab-seo" data-bs-toggle="pill" data-bs-target="#pane-seo" type="button" role="tab" aria-controls="pane-seo" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-magnifying-glass"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.seo.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.seo.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-sitemap" data-bs-toggle="pill" data-bs-target="#pane-sitemap" type="button" role="tab" aria-controls="pane-sitemap" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-sitemap"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.sitemap.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.sitemap.sub') }}</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Automation (Pink) -->
                <div class="settings-group">
                    <button class="settings-group-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#group-automation" aria-expanded="false" aria-controls="group-automation">
                        <div class="d-flex align-items-center gap-2">
                            <div class="group-icon group-icon-pink">
                                <i class="fa-solid fa-robot"></i>
                            </div>
                            <span class="group-label">{{ __('settings.groups.automation') }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-down group-arrow"></i>
                    </button>
                    <div id="group-automation" class="collapse settings-group-body" data-bs-parent="#settingsAccordion">
                        <button class="nav-link" id="tab-ai" data-bs-toggle="pill" data-bs-target="#pane-ai" type="button" role="tab" aria-controls="pane-ai" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-brain"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.ai.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.ai.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-cron" data-bs-toggle="pill" data-bs-target="#pane-cron" type="button" role="tab" aria-controls="pane-cron" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-clock"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.cron.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.cron.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-extensions" data-bs-toggle="pill" data-bs-target="#pane-extensions" type="button" role="tab" aria-controls="pane-extensions" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-puzzle-piece"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.extensions.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.extensions.sub') }}</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Security (Teal) -->
                <div class="settings-group">
                    <button class="settings-group-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#group-security" aria-expanded="false" aria-controls="group-security">
                        <div class="d-flex align-items-center gap-2">
                            <div class="group-icon group-icon-teal">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <span class="group-label">{{ __('settings.groups.security') }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-down group-arrow"></i>
                    </button>
                    <div id="group-security" class="collapse settings-group-body" data-bs-parent="#settingsAccordion">
                        <button class="nav-link" id="tab-gdpr" data-bs-toggle="pill" data-bs-target="#pane-gdpr" type="button" role="tab" aria-controls="pane-gdpr" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-cookie-bite"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.gdpr.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.gdpr.sub') }}</span>
                            </div>
                        </button>
                        <button class="nav-link" id="tab-policy" data-bs-toggle="pill" data-bs-target="#pane-policy" type="button" role="tab" aria-controls="pane-policy" aria-selected="false">
                            <div class="nav-icon-wrapper"><i class="fa-solid fa-file-shield"></i></div>
                            <div class="nav-text">
                                <span class="nav-title">{{ __('settings.links.policy.title') }}</span>
                                <span class="nav-subtitle">{{ __('settings.links.policy.sub') }}</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Footer -->
                <div class="settings-footer">
                    <div class="footer-meta">
                        <i class="fa-solid fa-code-commit"></i>
                        <span>v2.5.0</span>
                    </div>
                    <div class="footer-status">
                        <span class="status-dot"></span>
                        <span>{{ __('settings.status.operational') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>