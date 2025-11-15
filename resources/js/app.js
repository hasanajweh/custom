import './bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import Tooltip from '@ryangjchandler/alpine-tooltip';
import persist from '@alpinejs/persist';

Alpine.plugin(focus);
Alpine.plugin(Tooltip);
Alpine.plugin(persist);

document.addEventListener('alpine:init', () => {
    Alpine.store('auth', {
        status: '',
        message: '',
        setMessage(type, text) {
            this.status = type;
            this.message = text;
            setTimeout(() => this.message = '', 5000);
        }
    });

    Alpine.data('loginForm', () => ({
        showPassword: false,
        loading: false,
        email: '',
        password: '',
        remember: false,
        errors: {
            email: false,
            password: false
        },

        init() {
            // Initialize with old input values
            this.email = this.$el.querySelector('[name="email"]').value || '';

            // Focus email field if empty
            if (!this.email) {
                this.$refs.email.focus();
            }
        },

        togglePassword() {
            this.showPassword = !this.showPassword;
            this.$nextTick(() => {
                this.$refs.password.focus();
            });
        },

        validateForm() {
            this.errors.email = !this.email.trim();
            this.errors.password = !this.password.trim();

            return !this.errors.email && !this.errors.password;
        },

        submitForm() {
            if (!this.validateForm()) {
                Alpine.store('auth').setMessage('error', 'Please fill in all required fields');

                // Focus first error field
                if (this.errors.email) {
                    this.$refs.email.focus();
                } else if (this.errors.password) {
                    this.$refs.password.focus();
                }

                return;
            }

            this.loading = true;
            this.$refs.form.submit();
        }
    }));
});

window.Alpine = Alpine;
Alpine.start();
