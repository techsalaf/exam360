/**
 * CMS Pages Form Script (Create & Edit)
 * Handles the drag-and-drop content builder and section management.
 */
(function () {
    'use strict';

    const SELECTORS = {
        container: '#sections-container',
        emptyState: '#empty-state',
        addBtn: '.js-add-section-btn',
        removeBtn: '.js-remove-section-btn',
        deletePageBtn: '#deletePageBtn',
        deletePageForm: '#delete-page-form',
        config: '#cms-builder-config'
    };

    document.addEventListener('DOMContentLoaded', function () {
        initSortable();
        initEventListeners();
    });

    /**
     * Initialize SortableJS
     */
    function initSortable() {
        const el = document.querySelector(SELECTORS.container);
        if (el && typeof Sortable !== 'undefined') {
            new Sortable(el, {
                animation: 150,
                handle: '.cms-drag-handle',
                ghostClass: 'cms-sortable-ghost',
                onEnd: reindexSections
            });
        }
    }

    /**
     * Initialize all click listeners
     */
    function initEventListeners() {
        // Add Section Dropdown Items
        document.querySelectorAll(SELECTORS.addBtn).forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                addSection(this.dataset.type);
            });
        });

        // Event Delegation for Dynamic Elements (Remove Section)
        const container = document.querySelector(SELECTORS.container);
        if (container) {
            container.addEventListener('click', function (e) {
                const btn = e.target.closest(SELECTORS.removeBtn);
                if (btn) {
                    e.preventDefault();
                    removeSection(btn);
                }
            });
        }

        // Delete Page Button (Edit View)
        const deleteBtn = document.querySelector(SELECTORS.deletePageBtn);
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function () {
                const config = document.querySelector(SELECTORS.config);
                const confirmText = config ? config.getAttribute('data-delete-page-text') : 'Delete this page?';
                
                if (confirm(confirmText)) {
                    const form = document.querySelector(SELECTORS.deletePageForm);
                    if (form) form.submit();
                }
            });
        }
    }

    /**
     * Add a new section to the builder
     */
    function addSection(type) {
        const container = document.querySelector(SELECTORS.container);
        const emptyState = document.querySelector(SELECTORS.emptyState);
        
        // Remove empty state placeholder if present
        if (emptyState) emptyState.remove();

        const templateId = `tpl-${type}`;
        const template = document.getElementById(templateId);

        if (!template) {
            console.error(`Template ${templateId} not found.`);
            return;
        }

        // Generate unique index and insert HTML
        const uniqueIndex = Date.now() + Math.floor(Math.random() * 1000);
        const html = template.innerHTML.replace(/INDEX/g, uniqueIndex);
        
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html;
        const newElement = wrapper.firstElementChild;
        
        container.appendChild(newElement);
        reindexSections();
        
        // Focus first input of new section
        const firstInput = newElement.querySelector('input, textarea');
        if(firstInput) firstInput.focus();
    }

    /**
     * Remove a section
     */
    function removeSection(btn) {
        const config = document.querySelector(SELECTORS.config);
        const confirmText = config ? config.getAttribute('data-remove-section-text') : 'Remove section?';

        if (confirm(confirmText)) {
            const block = btn.closest('.section-block');
            const container = document.querySelector(SELECTORS.container);
            
            block.remove();

            // Restore empty state if no sections left
            if (container.children.length === 0) {
                const emptyMsg = config ? config.getAttribute('data-empty-hint') : 'Builder is empty';
                const emptyHtml = `
                    <div id="empty-state" class="cms-placeholder">
                        <i class="fa-solid fa-arrow-up-right-from-square mb-2 d-block"></i>
                        ${emptyMsg}
                    </div>`;
                container.insertAdjacentHTML('beforeend', emptyHtml);
            }
            
            reindexSections();
        }
    }

    function reindexSections() {
        const rows = document.querySelectorAll('.section-block');
        rows.forEach((row, index) => {
            row.querySelectorAll('input, textarea').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.name = name.replace(/sections\[\d+\]/, `sections[${index}]`);
                }
            });
        });
    }

})();