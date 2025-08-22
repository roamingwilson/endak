// Modern JavaScript features - ES2020+ compatible
// No polyfills needed for modern browsers

// Modern async/await with better error handling
class ModernAPI {
    static async fetch(url, options = {}) {
        try {
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            };

            const response = await fetch(url, { ...defaultOptions, ...options });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }
}

// Modern notification system using native APIs
class ModernNotifications {
    static show(message, type = 'info', duration = 3000) {
        // Try to use native notifications first
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(message);
            return;
        }

        // Fallback to custom notification
        const notification = document.createElement('div');
        notification.className = `modern-notification modern-notification--${type}`;
        notification.innerHTML = `
            <div class="modern-notification__content">
                <span class="modern-notification__message">${message}</span>
                <button class="modern-notification__close" onclick="this.parentElement.parentElement.remove()">&times;</button>
            </div>
        `;

        // Add styles if not already present
        if (!document.querySelector('#modern-notification-styles')) {
            const styles = document.createElement('style');
            styles.id = 'modern-notification-styles';
            styles.textContent = `
                .modern-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    max-width: 300px;
                    padding: 12px 16px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    z-index: 9999;
                    animation: slideIn 0.3s ease-out;
                }
                .modern-notification--info { background: #17a2b8; color: white; }
                .modern-notification--success { background: #28a745; color: white; }
                .modern-notification--warning { background: #ffc107; color: #212529; }
                .modern-notification--danger { background: #dc3545; color: white; }
                .modern-notification__content {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .modern-notification__close {
                    background: none;
                    border: none;
                    color: inherit;
                    font-size: 20px;
                    cursor: pointer;
                    margin-left: 10px;
                }
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(styles);
        }

        document.body.appendChild(notification);

        // Auto remove
        setTimeout(() => {
            notification.style.animation = 'slideIn 0.3s ease-out reverse';
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }

    static async requestPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            await Notification.requestPermission();
        }
    }
}

// Modern form handling with better validation
class ModernFormHandler {
    constructor(form) {
        this.form = form;
        this.init();
    }

    init() {
        this.form.addEventListener('submit', this.handleSubmit.bind(this));
        this.addRealTimeValidation();
    }

    addRealTimeValidation() {
        const inputs = this.form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearErrors(input));
        });
    }

    validateField(field) {
        const value = field.value.trim();
        const isRequired = field.hasAttribute('required');
        const type = field.type;

        let isValid = true;
        let errorMessage = '';

        if (isRequired && !value) {
            isValid = false;
            errorMessage = 'هذا الحقل مطلوب';
        } else if (value) {
            switch (type) {
                case 'email':
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        isValid = false;
                        errorMessage = 'يرجى إدخال بريد إلكتروني صحيح';
                    }
                    break;
                case 'tel':
                    const phoneRegex = /^[\d\s\-\+\(\)]+$/;
                    if (!phoneRegex.test(value)) {
                        isValid = false;
                        errorMessage = 'يرجى إدخال رقم هاتف صحيح';
                    }
                    break;
            }
        }

        this.showFieldError(field, isValid, errorMessage);
        return isValid;
    }

    showFieldError(field, isValid, message) {
        field.classList.toggle('is-invalid', !isValid);
        field.classList.toggle('is-valid', isValid && field.value.trim());

        let errorElement = field.parentElement.querySelector('.field-error');
        if (!isValid && message) {
            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.className = 'field-error text-danger small mt-1';
                field.parentElement.appendChild(errorElement);
            }
            errorElement.textContent = message;
        } else if (errorElement) {
            errorElement.remove();
        }
    }

    clearErrors(field) {
        field.classList.remove('is-invalid');
        const errorElement = field.parentElement.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    async handleSubmit(e) {
        e.preventDefault();

        // Validate all fields
        const inputs = this.form.querySelectorAll('input, textarea, select');
        let isFormValid = true;

        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            ModernNotifications.show('يرجى تصحيح الأخطاء في النموذج', 'danger');
            return;
        }

        // Submit form
        const formData = new FormData(this.form);
        const submitButton = this.form.querySelector('[type="submit"]');
        const originalText = submitButton.textContent;

        try {
            submitButton.disabled = true;
            submitButton.textContent = 'جاري الإرسال...';

            const response = await ModernAPI.fetch(this.form.action, {
                method: this.form.method || 'POST',
                body: formData
            });

            ModernNotifications.show('تم الإرسال بنجاح', 'success');

            // Handle redirect if provided
            if (response.redirect) {
                window.location.href = response.redirect;
            }

        } catch (error) {
            ModernNotifications.show('حدث خطأ أثناء الإرسال', 'danger');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    }
}

// Initialize modern features
document.addEventListener('DOMContentLoaded', () => {
    // Initialize modern form handlers
    document.querySelectorAll('form[data-modern]').forEach(form => {
        new ModernFormHandler(form);
    });

    // Request notification permission
    ModernNotifications.requestPermission();

    // Add modern loading states to buttons
    document.querySelectorAll('button[data-loading]').forEach(button => {
        button.addEventListener('click', () => {
            const loadingText = button.dataset.loading || 'جاري التحميل...';
            const originalText = button.textContent;

            button.disabled = true;
            button.textContent = loadingText;

            // Re-enable after 3 seconds (fallback)
            setTimeout(() => {
                button.disabled = false;
                button.textContent = originalText;
            }, 3000);
        });
    });
});
