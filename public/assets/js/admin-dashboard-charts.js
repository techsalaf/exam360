document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    // Tooltip Initialization
    const initTooltips = () => {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    };

    // Render Bar Charts (Performance Analytics)
    const renderBarCharts = () => {
        const bars = document.querySelectorAll('.bar-fill');
        bars.forEach(bar => {
            const height = bar.getAttribute('data-height');
            if (height) {
                // Apply height style dynamically
                // Small delay ensures DOM is fully painted before animations start
                setTimeout(() => {
                    bar.style.height = height + '%';
                }, 100); 
            }
        });
    };

    // Render Donut Chart (Subscription Growth)
    const renderDonutCharts = () => {
        const donuts = document.querySelectorAll('.donut-chart');
        donuts.forEach(donut => {
            const percent = donut.getAttribute('data-percent');
            if (percent) {
                // Apply conic-gradient style dynamically
                donut.style.background = `conic-gradient(var(--zi-purple) ${percent}%, var(--zi-bg-hover) 0)`;
            }
        });
    };

    // Init All dynamic components
    initTooltips();
    renderBarCharts();
    renderDonutCharts();
});