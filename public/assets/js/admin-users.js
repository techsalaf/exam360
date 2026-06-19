document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const UserManagement = {
        init() {
            this.handleTooltips();
            this.handleConfirmations();
            this.handleEditModal();
            this.handleAssignPlanModal();
        },

        handleTooltips() {
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(el => new bootstrap.Tooltip(el));
        },

        handleConfirmations() {
            document.querySelectorAll('.confirm-action').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    const title = this.dataset.title || 'Are you sure?';

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: title,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#059669',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Confirm',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) form.submit();
                        });
                    } else if (confirm(title)) {
                        form.submit();
                    }
                });
            });
        },

        handleEditModal() {
            const editModal = document.getElementById('editUserModal');
            if (!editModal) return;

            editModal.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;
                const data = trigger.getAttribute('data-user');
                if (!data) return;

                const user = JSON.parse(data);
                const form = editModal.querySelector('form');
                form.action = `/admin/users/${user.id}`;

                editModal.querySelector('#edit_name').value = user.name || '';
                editModal.querySelector('#edit_email').value = user.email || '';
                editModal.querySelector('#edit_mobile').value = user.mobile || '';
            });
        },

        handleAssignPlanModal() {
            const planModal = document.getElementById('assignUserPlanModal');
            if (!planModal) return;

            planModal.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;
                const data = trigger.getAttribute('data-user');
                if (!data) return;

                const user = JSON.parse(data);
                const form = planModal.querySelector('#assignPlanForm');
                const nameDisplay = planModal.querySelector('#assign_plan_user_name');
                const planDropdown = planModal.querySelector('#modal_assign_plan_id');
                const cycleDropdown = planModal.querySelector('#modal_assign_billing_cycle');

                nameDisplay.textContent = user.name;
                form.action = `/admin/users/${user.id}/assign-plan`;

                if (planDropdown) {
                    planDropdown.value = user.plan_id || "";
                }

                if (cycleDropdown) {
                    cycleDropdown.value = user.plan_type || "monthly";
                }
            });
        }
    };

    UserManagement.init();
});