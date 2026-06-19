"use strict";

/* 
    -------------------------------------------------------------------------
    ZIEXAM AI - MAIN APPLICATION SCRIPT
    Version:     2.0.0
    -------------------------------------------------------------------------
*/

// GLOBAL NOTIFICATION UTILS (Exposed to window)
const ZiNotify = {
    config: {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right", // Top Right
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    },
    
    success: function(message) {
        toastr.options = this.config;
        toastr.success(message, 'Success');
    },
    
    error: function(message) {
        toastr.options = this.config;
        toastr.error(message, 'Error');
    },
    
    warning: function(message) {
        toastr.options = this.config;
        toastr.warning(message, 'Warning');
    },
    
    info: function(message) {
        toastr.options = this.config;
        toastr.info(message, 'Info');
    }
};

document.addEventListener("DOMContentLoaded", function () {

    /* --- 1. BOOTSTRAP TOOLTIPS --- */
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    /* --- 2. DESKTOP SIDEBAR COLLAPSE --- */
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const body = document.body;

    // Load saved state
    if (localStorage.getItem('zi_sidebar_state') === 'collapsed') {
        body.classList.add('sidebar-collapsed');
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function (e) {
            e.preventDefault();
            body.classList.toggle('sidebar-collapsed');
            
            // Save preference
            const state = body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded';
            localStorage.setItem('zi_sidebar_state', state);
            
            // Trigger Resize for Charts (if any)
            window.dispatchEvent(new Event('resize'));
        });
    }

    /* --- 3. MOBILE SIDEBAR TOGGLE --- */
    const mobileToggle = document.getElementById('mobile-toggle');
    const mobileClose = document.getElementById('mobile-close');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');

    // Open Logic
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation(); 
            sidebar.classList.add('show');
            if (overlay) overlay.classList.add('show');
        });
    }

    // Close Logic
    function closeMobileSidebar() {
        sidebar.classList.remove('show');
        if (overlay) overlay.classList.remove('show');
    }

    if (mobileClose) {
        mobileClose.addEventListener('click', closeMobileSidebar);
    }

    if (overlay) {
        overlay.addEventListener('click', closeMobileSidebar);
    }

    /* --- 4. DROPDOWNS --- */
    const dropdownToggles = document.querySelectorAll('.nav-dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            
            // Close other open dropdowns (Optional - removes accordian style if unwanted)
            // dropdownToggles.forEach(other => {
            //     if(other !== this) {
            //         other.setAttribute('aria-expanded', 'false');
            //         other.nextElementSibling.classList.remove('show');
            //     }
            // });

            const subMenu = this.nextElementSibling;
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            this.setAttribute('aria-expanded', !isExpanded);
            if (subMenu) subMenu.classList.toggle('show');
        });
    });

    /* --- 5. DARK MODE --- */
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        // Load saved theme
        const savedTheme = localStorage.getItem('zi_theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);

        themeToggle.addEventListener('click', function () {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('zi_theme', newTheme);
        });
    }

    /* --- 6. GLOBAL SWEETALERT CONFIRMATION --- */
    /* 
       Usage: Add class 'confirm-action' or data attribute 'data-confirm="true"' 
       to any form button or link.
       For Forms: It will pause submission, ask, then submit.
    */
    const confirmButtons = document.querySelectorAll('[data-confirm="true"], .confirm-action');
    
    confirmButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const link = this.getAttribute('href');
            
            // Custom Text from attributes
            const title = this.getAttribute('data-title') || 'Are you sure?';
            const text = this.getAttribute('data-text') || "You won't be able to revert this!";
            const confirmBtnText = this.getAttribute('data-btn-text') || 'Yes, proceed';
            const iconType = this.getAttribute('data-icon') || 'warning';

            Swal.fire({
                title: title,
                text: text,
                icon: iconType,
                showCancelButton: true,
                confirmButtonColor: '#059669', // Emerald 600 (Matches Theme)
                cancelButtonColor: '#d33',
                confirmButtonText: confirmBtnText,
                customClass: {
                    popup: 'zi-swal-popup', // For custom CSS if needed
                    confirmButton: 'btn btn-success px-4',
                    cancelButton: 'btn btn-danger px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (form) {
                        form.submit();
                    } else if (link && link !== '#') {
                        window.location.href = link;
                    }
                }
            });
        });
    });

});