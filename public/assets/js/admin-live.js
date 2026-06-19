(function($) {
    'use strict';

    // State Management
    var refreshTimer;
    var isRequestPending = false;
    var searchDebounce;
    var REFRESH_RATE = 5000;

    // Load Configuration from DOM (No inline scripts)
    var $configEl = $('#liveMonitoringConfig');
    
    if ($configEl.length === 0) {
        console.error('Live Monitoring configuration element missing.');
        return;
    }

    var LIVE_ROUTES = {
        update: $configEl.data('update'),
        action: $configEl.data('action')
    };
    var CSRF_TOKEN = $configEl.data('csrf');

    // DOM Selectors
    var selectors = {
        kpi: {
            active: '#kpi-card-active',
            critical: '#kpi-card-critical',
            paused: '#kpi-card-paused',
            completed: '#kpi-card-completed'
        },
        filters: {
            exam: '#filterExam',
            risk: '#filterRisk',
            status: '#filterStatus',
            search: '#searchInputDesktop',
            mobileSearch: '#searchInputMobile',
            mobileExam: '#mobileFilterExam',
            mobileRisk: '#mobileFilterRisk',
            mobileStatus: '#mobileFilterStatus',
            applyMobile: '#applyMobileFilters'
        },
        table: '#liveSessionsTable',
        indicator: '#statusIndicator',
        lastUpdate: '#lastUpdatedTime',
        toggles: {
            autoRefresh: '#autoRefreshToggle',
            manual: '#manualRefreshBtn'
        }
    };

    // Initialization
    $(document).ready(function() {
        startPolling();
        registerEvents();
    });

    function registerEvents() {
        // Desktop Filter Changes
        $(selectors.filters.exam + ', ' + selectors.filters.risk + ', ' + selectors.filters.status).on('change', function() {
            loadDashboardData();
        });

        // Search Debounce
        $(selectors.filters.search).on('keyup', function() {
            clearTimeout(searchDebounce);
            searchDebounce = setTimeout(loadDashboardData, 300);
        });

        // Mobile Search Sync
        $(selectors.filters.mobileSearch).on('keyup', function() {
            var val = $(this).val();
            $(selectors.filters.search).val(val).trigger('keyup');
        });

        // Mobile Filter Apply
        $(selectors.filters.applyMobile).on('click', function() {
            $('#liveFilterOffcanvas').offcanvas('hide');
            
            // Sync mobile values to main filters
            $(selectors.filters.exam).val($(selectors.filters.mobileExam).val());
            $(selectors.filters.risk).val($(selectors.filters.mobileRisk).val());
            $(selectors.filters.status).val($(selectors.filters.mobileStatus).val());
            
            loadDashboardData();
        });

        // Manual Refresh
        $(selectors.toggles.manual).on('click', function() {
            var $btn = $(this);
            $btn.find('i').addClass('fa-spin');
            loadDashboardData(function() {
                $btn.find('i').removeClass('fa-spin');
            });
        });

        // Expand/Collapse Details (Row Toggle)
        $(document).on('click', '.toggle-details-btn', function() {
            var target = $(this).data('target');
            var $icon = $(this).find('i');
            var $row = $(target);

            $row.toggleClass('show');

            if ($row.hasClass('show')) {
                $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            } else {
                $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            }
        });

        // Admin Actions (Pause/Terminate)
        $(document).on('click', '.action-btn', function(e) {
            e.preventDefault();
            executeAction($(this));
        });
    }

    // Polling System
    function startPolling() {
        if (refreshTimer) clearInterval(refreshTimer);
        loadDashboardData();
        refreshTimer = setInterval(function() {
            if (!isRequestPending) {
                loadDashboardData();
            }
        }, REFRESH_RATE);
    }

    function stopPolling() {
        clearInterval(refreshTimer);
    }

    // Data Fetching
    function loadDashboardData(callback) {
        isRequestPending = true;

        var payload = {
            exam_id: $(selectors.filters.exam).val(),
            risk_level: $(selectors.filters.risk).val(),
            status: $(selectors.filters.status).val(),
            search: $(selectors.filters.search).val()
        };

        $.ajax({
            url: LIVE_ROUTES.update,
            method: 'GET',
            data: payload,
            success: function(res) {
                if (res.status === 'success') {
                    renderDashboard(res);
                    setConnectionState(true, res.timestamp);
                }
            },
            error: function() {
                setConnectionState(false);
            },
            complete: function() {
                isRequestPending = false;
                if (typeof callback === 'function') callback();
            }
        });
    }

    // Dashboard Render
    function renderDashboard(data) {
        // Update KPIs
        updateKpi(selectors.kpi.active, data.kpi.active_users);
        updateKpi(selectors.kpi.critical, data.kpi.critical_risk);
        updateKpi(selectors.kpi.paused, data.kpi.paused);
        updateKpi(selectors.kpi.completed, data.kpi.completed_today);

        // Preserve Expanded Rows State
        var openRows = [];
        $('.detail-row.show').each(function() {
            openRows.push($(this).attr('id'));
        });

        // Update Table content
        $(selectors.table).html(data.html);

        // Restore Expanded State
        if (openRows.length > 0) {
            $.each(openRows, function(i, id) {
                var $row = $('#' + id);
                if ($row.length) {
                    $row.addClass('show');
                    $row.prev().find('.toggle-details-btn i')
                        .removeClass('fa-chevron-down')
                        .addClass('fa-chevron-up');
                }
            });
        }
    }

    function updateKpi(selector, value) {
        var $el = $(selector).find('.zi-kpi-content h3');
        var formatted = parseInt(value) < 10 ? '0' + parseInt(value) : value;
        $el.text(formatted);
    }

    function setConnectionState(isOnline, time) {
        var $ind = $(selectors.indicator);
        
        if (isOnline) {
            $ind.removeClass('bg-danger-subtle text-danger border-danger-subtle')
                .addClass('bg-success-subtle text-success border-success-subtle')
                .html('<span class="live-sync-dot"></span> ' + 'Connected'); // Text is usually localized in HTML, keep simple here
            
            if (time) $(selectors.lastUpdate).text(time);
        } else {
            $ind.removeClass('bg-success-subtle text-success border-success-subtle')
                .addClass('bg-danger-subtle text-danger border-danger-subtle')
                .html('<i class="fa-solid fa-plug me-2"></i> Disconnected');
        }
    }

    // Action Logic
    function executeAction($btn) {
        var action = $btn.data('action');
        var id = $btn.data('id');
        var route = LIVE_ROUTES.action + '/' + id;
        
        var swalConfig = {
            title: 'Confirm Action',
            text: 'Are you sure you want to proceed?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes'
        };

        if (action === 'terminate') {
            swalConfig.title = 'Force Termination?';
            swalConfig.text = 'This will immediately submit the student\'s exam. Action cannot be undone.';
            swalConfig.icon = 'warning';
            swalConfig.confirmButtonColor = '#d33';
            swalConfig.confirmButtonText = 'Terminate Exam';
        } else if (action === 'pause') {
            swalConfig.title = 'Pause Session?';
            swalConfig.text = 'The timer will stop for this student.';
            swalConfig.icon = 'info';
        }

        Swal.fire(swalConfig).then(function(result) {
            if (result.isConfirmed) {
                $btn.prop('disabled', true);
                
                $.ajax({
                    url: route,
                    method: 'POST',
                    data: {
                        action: action,
                        _token: CSRF_TOKEN
                    },
                    success: function(res) {
                        toastr.success(res.message);
                        loadDashboardData();
                    },
                    error: function(xhr) {
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Action failed';
                        toastr.error(msg);
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                    }
                });
            }
        });
    }

})(jQuery);