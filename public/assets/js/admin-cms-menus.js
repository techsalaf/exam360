/**
 * CMS Menu Management Script
 * Handles drag-and-drop sorting, adding, and removing menu items.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        initSortables();
        initEventListeners();
    });

    /**
     * Initialize SortableJS for all menu containers
     */
    function initSortables() {
        const containers = ['header-list', 'footer-col1-list', 'footer-col2-list'];

        containers.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                new Sortable(el, {
                    animation: 150,
                    handle: '.cms-drag-handle',
                    ghostClass: 'cms-sortable-ghost',
                    onEnd: function (evt) {
                        reindexInputs(evt.to);
                    }
                });
            }
        });
    }

    /**
     * Initialize click events using delegation
     */
    function initEventListeners() {
        document.body.addEventListener('click', function (e) {
            // Handle Add Item Click
            const addBtn = e.target.closest('.js-add-menu-item');
            if (addBtn) {
                e.preventDefault();
                const targetId = addBtn.getAttribute('data-target');
                addMenuItem(targetId);
            }

            // Handle Remove Item Click
            const removeBtn = e.target.closest('.js-remove-menu-item');
            if (removeBtn) {
                e.preventDefault();
                removeMenuItem(removeBtn);
            }
        });
    }

    /**
     * Add a new menu item to the container
     */
    function addMenuItem(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;

        // Remove placeholder if it exists
        const placeholder = container.querySelector('.cms-placeholder');
        if (placeholder) placeholder.remove();

        // Get template and replace ID
        const template = document.getElementById('menu-item-template').innerHTML;
        const uniqueId = Date.now();
        const html = template.replace(/INDEX/g, uniqueId);

        // Append and focus
        container.insertAdjacentHTML('beforeend', html);
        const newRow = container.lastElementChild;
        const input = newRow.querySelector('input');
        if (input) input.focus();
    }

    /**
     * Remove a menu item and check if container is empty
     */
    function removeMenuItem(btn) {
        const row = btn.closest('.cms-menu-item');
        const container = row.parentElement;
        
        row.remove();

        // Re-index remaining inputs to ensure no gaps in array keys
        reindexInputs(container);

        // Show placeholder if empty
        if (container.children.length === 0) {
            const emptyMsg = document.getElementById('cms-menu-config').getAttribute('data-empty-msg');
            container.innerHTML = `<div class="cms-placeholder">${emptyMsg}</div>`;
        }
    }

    /**
     * Re-index input names for PHP array processing
     * items[original_id][field] -> items[0][field]
     */
    function reindexInputs(container) {
        Array.from(container.children).forEach((row, index) => {
            row.querySelectorAll('input').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.name = name.replace(/items\[\d+\]/, `items[${index}]`);
                }
            });
        });
    }

})();