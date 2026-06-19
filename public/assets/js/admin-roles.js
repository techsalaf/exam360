// public/assets/js/admin-roles.js

// Ensure RolesConfig object is available (defined in the Blade file)
if (typeof RolesConfig === 'undefined') {
    console.error("RolesConfig is not defined. Ensure the configuration script runs before loading admin-roles.js");
}

$(document).ready(function() {
    const matrixForm = $('#permissionMatrixForm');
    const rolesGrid = $('#rolesGrid');
    const config = RolesConfig;

    /**
     * Loads permissions for a selected role and updates the UI.
     * @param {number} roleId
     * @param {string} roleName
     */
    function loadPermissions(roleId, roleName) {
        $.get(config.baseUrl + `/${roleId}/permissions`)
            .done(function(response) {
                const permissionsArray = response.permissions;

                $('#matrixRoleName').text(roleName);
                $('#matrixRoleId').text(roleId);
                matrixForm.attr('action', config.baseUrl + `/${roleId}/permissions`);

                // Reset switches and apply new permissions
                matrixForm.find('.permission-switch').prop('checked', false);
                permissionsArray.forEach(function(permissionSlug) {
                    matrixForm.find(`input[value="${permissionSlug}"]`).prop('checked', true);
                });

                // Update card styling
                $('.role-card').css({
                    'border-color': 'var(--st-border)',
                    'outline': 'none',
                    'box-shadow': 'var(--st-shadow)'
                });

                const activeCard = $(`#role-card-${roleId}`).find('.role-card');
                activeCard.css({
                    'border-color': 'var(--st-success)',
                    'outline': '1px solid var(--st-success)',
                    'box-shadow': 'none'
                });

                // Scroll to the matrix
                $('html, body').animate({
                    scrollTop: $('#permissionMatrixCard').offset().top - 100
                }, 500);
            })
            .fail(function() {
                toastr.error(config.messages.loadFail);
            });
    }

    // --- Event Listeners ---

    // 1. Role Selection Click Handler
    rolesGrid.on('click', '.role-selector', function(e) {
        // Prevent action if clicking on control buttons
        if ($(e.target).closest('.delete-role-btn, .edit-role-btn, .btn').length) {
            return;
        }
        e.preventDefault();
        const roleId = $(this).data('role-id');
        const roleName = $(this).data('role-name');
        loadPermissions(roleId, roleName);
    });

    // 2. Permission Matrix Update
    matrixForm.on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: matrixForm.attr('action'),
            method: 'PUT',
            data: matrixForm.serialize(),
            success: function(response) {
                toastr.success(response.message);
            },
            error: function() {
                toastr.error(config.messages.updatePermError);
            }
        });
    });

    // 3. Create Role Form Submission
    $('#createRoleForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: config.createUrl,
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                toastr.success(response.message);
                $('#createRoleModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                const errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                if (errors) {
                    toastr.error(errors[Object.keys(errors)[0]][0]);
                } else {
                    toastr.error(config.messages.createFail);
                }
            }
        });
    });

    // 4. Populate Edit Role Modal
    rolesGrid.on('click', '.edit-role-btn', function(e) {
        e.stopPropagation();
        const roleId = $(this).data('role-id');
        const roleName = $(this).data('role-name');
        const roleDesc = $(this).data('role-desc');

        $('#edit_role_id').val(roleId);
        $('#edit_role_name').val(roleName);
        $('#edit_role_description').val(roleDesc);
        $('#editRoleModal').modal('show');
    });

    // 5. Edit Role Form Submission
    $('#editRoleForm').on('submit', function(e) {
        e.preventDefault();
        const roleId = $('#edit_role_id').val();
        const form = $(this);

        $.ajax({
            url: config.baseUrl + `/${roleId}`,
            method: 'PUT',
            data: form.serialize(),
            success: function(response) {
                toastr.success(response.message);
                $('#editRoleModal').modal('hide');
                setTimeout(() => {
                    location.reload();
                }, 500);
            },
            error: function(xhr) {
                const errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                if (errors) {
                    toastr.error(errors[Object.keys(errors)[0]][0]);
                } else {
                    toastr.error(config.messages.updateFail);
                }
            }
        });
    });

    // 6. Delete Role Confirmation & Execution
    rolesGrid.on('click', '.delete-role-btn', function(e) {
        e.stopPropagation();
        const roleId = $(this).data('role-id');
        const roleName = $(this).data('role-name');

        Swal.fire({
            title: config.messages.deleteTitle,
            // Dynamically constructing the text using the variable passed from PHP/Blade
            text: config.messages.deleteText.replace('"%s"', `"${roleName}"`), 
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: config.messages.deleteConfirmBtn,
            cancelButtonText: config.messages.cancel
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: config.baseUrl + `/${roleId}`,
                    method: 'DELETE',
                    data: {
                        _token: config.csrfToken
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        $('#role-card-' + roleId).fadeOut(300, function() {
                            $(this).remove();
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                });
            }
        });
    });
});