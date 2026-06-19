document.addEventListener('DOMContentLoaded', function() { 
    'use strict';

    const NotificationManager = {
        
        elements: {
            form: document.getElementById('notificationForm'),
            config: document.getElementById('notification-config'),
            audienceRadios: document.querySelectorAll('input[name="audience"]'),
            audiencePanel: document.getElementById('specific-users-panel'),
            audienceCardAll: document.getElementById('opt-all'),
            audienceCardSpecific: document.getElementById('opt-specific'),
            searchInput: document.getElementById('user-search'),
            filterSelect: document.getElementById('user-filter'),
            resultsContainer: document.getElementById('user-results'),
            selectedCount: document.getElementById('selected-count'),
            chanEmail: document.getElementById('chan_email'),
            chanSms: document.getElementById('chan_sms'),
            groupEmail: document.getElementById('email-group'),
            groupSms: document.getElementById('sms-group'),
            inputSubject: document.getElementById('inp_subject'),
            inputBody: document.getElementById('inp_body'),
            inputSms: document.getElementById('inp_sms'),
            smsCharCount: document.getElementById('sms-char-count'),
            previewEmail: document.getElementById('preview-email-container'),
            previewSms: document.getElementById('preview-sms-container'),
            prevSubject: document.getElementById('prev-subject'),
            prevBody: document.getElementById('prev-body'),
            prevSmsText: document.getElementById('prev-sms'),
            
            // Buttons
            btnSubmit: document.getElementById('btn-submit'),
            btnCancel: document.getElementById('btn-cancel'),
            btnConfirmFinal: document.getElementById('btn-confirm-final'),
            varBadges: document.querySelectorAll('.btn-insert-var'),
            
            // Modal Elements
            modal: document.getElementById('confirmModal'),
            confAudience: document.getElementById('conf-audience'),
            confChannels: document.getElementById('conf-channels')
        },

        state: {
            selectedUsers: new Set(),
            audience: 'all',
            channels: { email: false, sms: false },
            searchTimer: null
        },

        init() {
            if(!this.elements.form) return;

            // Load initial state
            this.state.audience = document.querySelector('input[name="audience"]:checked')?.value || 'all';
            this.state.channels.email = this.elements.chanEmail?.checked || false;
            this.state.channels.sms = this.elements.chanSms?.checked || false;

            this.bindEvents();
            this.updateAudienceUI();
            this.updateChannelUI();
        },

        bindEvents() {
            const els = this.elements;

            // Audience Radio Change
            els.audienceRadios.forEach(radio => {
                radio.addEventListener('change', (e) => {
                    this.state.audience = e.target.value;
                    this.updateAudienceUI();
                });
            });

            // Search Logic
            if (els.searchInput) {
                els.searchInput.addEventListener('input', (e) => {
                    clearTimeout(this.state.searchTimer);
                    this.state.searchTimer = setTimeout(() => this.performSearch(e.target.value), 400);
                });
            }
            if (els.filterSelect) {
                els.filterSelect.addEventListener('change', () => this.performSearch(els.searchInput.value));
            }

            // Channel Toggles (Exclusive Logic)
            if (els.chanEmail) {
                els.chanEmail.addEventListener('change', (e) => {
                    this.state.channels.email = e.target.checked;
                    if (e.target.checked && els.chanSms) {
                        els.chanSms.checked = false;
                        this.state.channels.sms = false;
                    }
                    this.updateChannelUI();
                });
            }

            if (els.chanSms) {
                els.chanSms.addEventListener('change', (e) => {
                    this.state.channels.sms = e.target.checked;
                    if (e.target.checked && els.chanEmail) {
                        els.chanEmail.checked = false;
                        this.state.channels.email = false;
                    }
                    this.updateChannelUI();
                });
            }

            // Variable Badges Click
            els.varBadges.forEach(badge => {
                badge.addEventListener('click', (e) => {
                    const variable = e.target.getAttribute('data-var');
                    if(variable) this.insertVariable(variable);
                });
            });

            // Live Previews
            if (els.inputSubject) els.inputSubject.addEventListener('input', () => this.updateEmailPreview());
            if (els.inputBody) els.inputBody.addEventListener('input', () => this.updateEmailPreview());
            if (els.inputSms) els.inputSms.addEventListener('input', () => this.updateSmsPreview());

            // Buttons
            if (els.btnSubmit) els.btnSubmit.addEventListener('click', () => this.handleSubmitClick());
            if (els.btnCancel) els.btnCancel.addEventListener('click', () => window.history.back());
            if (els.btnConfirmFinal) els.btnConfirmFinal.addEventListener('click', () => els.form.submit());
        },

        updateAudienceUI() {
            const els = this.elements;
            const isAll = this.state.audience === 'all';

            if (isAll) {
                els.audienceCardAll?.classList.add('active');
                els.audienceCardSpecific?.classList.remove('active');
                els.audiencePanel?.classList.add('d-none');
            } else {
                els.audienceCardSpecific?.classList.add('active');
                els.audienceCardAll?.classList.remove('active');
                els.audiencePanel?.classList.remove('d-none');
                
                // Trigger initial search if empty
                if (els.resultsContainer.children.length <= 1) this.performSearch('');
            }
        },

        updateChannelUI() {
            const els = this.elements;
            const { email, sms } = this.state.channels;

            // Form Visibility - using d-none class
            if (els.groupEmail) {
                email ? els.groupEmail.classList.remove('d-none') : els.groupEmail.classList.add('d-none');
            }
            if (els.groupSms) {
                sms ? els.groupSms.classList.remove('d-none') : els.groupSms.classList.add('d-none');
            }

            // Preview Visibility
            if (email) {
                els.previewEmail.classList.remove('d-none');
                els.previewSms.classList.add('d-none');
            } else if (sms) {
                els.previewEmail.classList.add('d-none');
                els.previewSms.classList.remove('d-none');
            } else {
                els.previewEmail.classList.add('d-none');
                els.previewSms.classList.add('d-none');
            }
        },

        performSearch(query) {
            const url = this.elements.config?.dataset.searchUrl;
            if (!url) return;

            const filter = this.elements.filterSelect ? this.elements.filterSelect.value : 'active';
            const container = this.elements.resultsContainer;

            container.innerHTML = '<div class="p-4 text-center text-muted"><div class="spinner-border spinner-border-sm me-2"></div>Loading...</div>';

            fetch(`${url}?q=${encodeURIComponent(query)}&filter=${filter}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(users => {
                container.innerHTML = '';
                if (!users || users.length === 0) {
                    container.innerHTML = '<div class="p-4 text-center text-muted">No users found.</div>';
                    return;
                }
                this.renderUsers(users);
            })
            .catch(() => {
                container.innerHTML = '<div class="p-4 text-center text-danger">Error loading users.</div>';
            });
        },

        renderUsers(users) {
            users.forEach(user => {
                const isChecked = this.state.selectedUsers.has(String(user.id));
                const initials = (user.first_name?.[0] || '') + (user.last_name?.[0] || 'U');
                const displayName = user.name || user.username || 'User';

                const div = document.createElement('div');
                div.className = 'user-item';
                div.innerHTML = `
                    <div class="d-flex align-items-center w-100">
                        <input class="user-check form-check-input mt-0" type="checkbox" value="${user.id}" ${isChecked ? 'checked' : ''}>
                        <div class="user-avatar">${initials.toUpperCase()}</div>
                        <div class="flex-grow-1">
                            <div class="fw-bold zi-text-main" style="font-size:0.9rem">${displayName}</div>
                            <div class="small zi-text-muted">${user.email}</div>
                        </div>
                    </div>`;

                const checkbox = div.querySelector('input');
                
                div.addEventListener('click', (e) => {
                    if (e.target !== checkbox) {
                        checkbox.checked = !checkbox.checked;
                        this.toggleUser(checkbox.value, checkbox.checked);
                    }
                });
                
                checkbox.addEventListener('change', (e) => this.toggleUser(e.target.value, e.target.checked));
                this.elements.resultsContainer.appendChild(div);
            });
        },

        toggleUser(id, isSelected) {
            id = String(id);
            if (isSelected) this.state.selectedUsers.add(id);
            else this.state.selectedUsers.delete(id);

            if (this.elements.selectedCount) {
                this.elements.selectedCount.textContent = this.state.selectedUsers.size;
            }
            this.updateHiddenInputs();
        },

        updateHiddenInputs() {
            const form = this.elements.form;
            form.querySelectorAll('input.hidden-user-input').forEach(el => el.remove());

            this.state.selectedUsers.forEach(userId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'users[]';
                input.value = userId;
                input.className = 'hidden-user-input';
                form.appendChild(input);
            });
        },

        updateEmailPreview() {
            const els = this.elements;
            if (els.prevSubject) els.prevSubject.textContent = els.inputSubject.value || 'No Subject';
            if (els.prevBody) els.prevBody.innerHTML = els.inputBody.value ? els.inputBody.value.replace(/\n/g, '<br>') : 'Start typing...';
        },

        updateSmsPreview() {
            const els = this.elements;
            const text = els.inputSms.value;
            if (els.prevSmsText) els.prevSmsText.textContent = text || 'Message...';
            if (els.smsCharCount) els.smsCharCount.textContent = `${text.length} / 160`;
        },

        insertVariable(variable) {
            let target = this.elements.inputBody;
            
            // Smart target selection
            if (!this.state.channels.email && this.state.channels.sms) target = this.elements.inputSms;
            
            if ([this.elements.inputSubject, this.elements.inputBody, this.elements.inputSms].includes(document.activeElement)) {
                target = document.activeElement;
            }

            if (!target) return;

            const start = target.selectionStart;
            const end = target.selectionEnd;
            const val = target.value;
            
            target.value = val.substring(0, start) + variable + val.substring(end);
            target.selectionStart = target.selectionEnd = start + variable.length;
            target.focus();
            target.dispatchEvent(new Event('input'));
        },

        showError(title, message) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: title,
                    text: message,
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'Got it'
                });
            } else {
                alert(`${title}: ${message}`);
            }
        },

        handleSubmitClick() {
            const { audience, selectedUsers, channels } = this.state;
            const els = this.elements;

            if (audience === 'specific' && selectedUsers.size === 0) {
                return this.showError('Audience Error', 'Please select at least one user.');
            }

            if (!channels.email && !channels.sms) {
                return this.showError('Channel Error', 'Please enable either Email or SMS.');
            }

            if (channels.email) {
                if (!els.inputSubject.value.trim()) return this.showError('Missing Subject', 'Please enter an email subject.');
                if (!els.inputBody.value.trim()) return this.showError('Missing Content', 'Please enter the email message body.');
            }

            if (channels.sms && !els.inputSms.value.trim()) {
                return this.showError('Missing Content', 'Please enter the SMS message.');
            }

            // Populate Modal
            if (els.confAudience) els.confAudience.textContent = (audience === 'all') ? 'All Users' : `${selectedUsers.size} Users`;
            
            const channelNames = [];
            if (channels.email) channelNames.push('Email');
            if (channels.sms) channelNames.push('SMS');
            if (els.confChannels) els.confChannels.textContent = channelNames.join(' & ');

            // Show Modal
            if (els.modal && window.bootstrap) {
                new bootstrap.Modal(els.modal).show();
            } else if(confirm("Confirm send notification?")) {
                els.form.submit();
            }
        }
    };

    NotificationManager.init();
});