// Optimized Core JavaScript - Only essential functions

// Essential DOM ready function
function domReady(fn) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fn);
    } else {
        fn();
    }
}

// Essential AJAX function for forms
function sendAjaxRequest(url, data, method = 'POST') {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    return new Promise((resolve, reject) => {
        xhr.onload = () => {
            if (xhr.status === 200) {
                resolve(JSON.parse(xhr.responseText));
            } else {
                reject(new Error(`HTTP ${xhr.status}: ${xhr.statusText}`));
            }
        };
        xhr.onerror = () => reject(new Error('Network error'));
        xhr.send(JSON.stringify(data));
    });
}

// Essential notification function (replaces SweetAlert for basic alerts)
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Essential form validation
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

// Essential loading indicator
function showLoading(show = true) {
    const loading = document.getElementById('loading');
    if (loading) {
        loading.classList.toggle('hidden', !show);
    }
}

// Initialize essential functions
domReady(() => {
    // Hide loading indicator
    showLoading(false);
    
    // Add form validation to all forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!validateForm(form)) {
                e.preventDefault();
                showNotification('يرجى ملء جميع الحقول المطلوبة', 'danger');
            }
        });
    });
    
    // Add click handlers for notifications/messages badges
    const notificationLinks = document.querySelectorAll('a[href*="notifications"]');
    notificationLinks.forEach(link => {
        link.addEventListener('click', () => {
            document.querySelectorAll('[id^="notificationsBadge"]').forEach(badge => {
                badge.style.display = 'none';
            });
        });
    });
    
    const messageLinks = document.querySelectorAll('a[href*="send_message"]');
    messageLinks.forEach(link => {
        link.addEventListener('click', () => {
            document.querySelectorAll('[id^="messagesBadge"]').forEach(badge => {
                badge.style.display = 'none';
            });
        });
    });
});
