// Theme Switcher JavaScript
class ThemeSwitcher {
    constructor() {
        this.currentTheme = this.getStoredTheme() || 'light';
        this.init();
    }

    init() {
        // Apply stored theme on page load
        this.applyTheme(this.currentTheme);
        
        // Create theme toggle button
        this.createToggleButton();
        
        // Add event listeners
        this.addEventListeners();
        
        // Add theme transition class to body
        document.body.classList.add('theme-transition');
    }

    getStoredTheme() {
        return localStorage.getItem('theme') || 'light';
    }

    setStoredTheme(theme) {
        localStorage.setItem('theme', theme);
    }

    applyTheme(theme) {
        // Remove existing theme classes
        document.body.classList.remove('light-theme', 'dark-theme');
        
        // Add new theme class
        document.body.classList.add(`${theme}-theme`);
        
        // Update meta theme-color for mobile browsers
        this.updateMetaThemeColor(theme);
        
        // Store theme preference
        this.setStoredTheme(theme);
        
        // Update current theme
        this.currentTheme = theme;
        
        // Trigger custom event for other scripts
        document.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
    }

    updateMetaThemeColor(theme) {
        let metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (!metaThemeColor) {
            metaThemeColor = document.createElement('meta');
            metaThemeColor.name = 'theme-color';
            document.head.appendChild(metaThemeColor);
        }
        
        const colors = {
            light: '#ffffff',
            dark: '#1a1a1a'
        };
        
        metaThemeColor.content = colors[theme] || colors.light;
    }

    createToggleButton() {
        // Check if button already exists
        if (document.getElementById('theme-toggle')) {
            return;
        }

        const toggleButton = document.createElement('button');
        toggleButton.id = 'theme-toggle';
        toggleButton.className = 'theme-toggle';
        toggleButton.setAttribute('aria-label', 'Toggle theme');
        toggleButton.innerHTML = this.getToggleIcon(this.currentTheme);
        
        document.body.appendChild(toggleButton);
    }

    getToggleIcon(theme) {
        return theme === 'dark' 
            ? '<i class="fas fa-sun"></i>' 
            : '<i class="fas fa-moon"></i>';
    }

    addEventListeners() {
        // Theme toggle button click
        document.addEventListener('click', (e) => {
            if (e.target.closest('#theme-toggle')) {
                this.toggleTheme();
            }
        });

        // Keyboard shortcut (Ctrl/Cmd + T)
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 't') {
                e.preventDefault();
                this.toggleTheme();
            }
        });

        // Listen for theme change events from other scripts
        document.addEventListener('themeChanged', (e) => {
            this.updateToggleIcon(e.detail.theme);
        });
    }

    toggleTheme() {
        const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.applyTheme(newTheme);
        this.updateToggleIcon(newTheme);
        
        // Show notification
        this.showThemeNotification(newTheme);
    }

    updateToggleIcon(theme) {
        const toggleButton = document.getElementById('theme-toggle');
        if (toggleButton) {
            toggleButton.innerHTML = this.getToggleIcon(theme);
        }
    }

    showThemeNotification(theme) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'theme-notification';
        notification.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            background: ${theme === 'dark' ? '#2d2d2d' : '#ffffff'};
            color: ${theme === 'dark' ? '#ffffff' : '#212529'};
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            border: 1px solid ${theme === 'dark' ? '#404040' : '#dee2e6'};
        `;
        
        const icon = theme === 'dark' ? '🌙' : '☀️';
        const text = theme === 'dark' ? 'تم تفعيل الوضع المظلم' : 'تم تفعيل الوضع الفاتح';
        
        notification.innerHTML = `
            <span style="margin-right: 8px;">${icon}</span>
            <span>${text}</span>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Public method to get current theme
    getCurrentTheme() {
        return this.currentTheme;
    }

    // Public method to set theme programmatically
    setTheme(theme) {
        if (['light', 'dark'].includes(theme)) {
            this.applyTheme(theme);
            this.updateToggleIcon(theme);
        }
    }
}

// Initialize theme switcher when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.themeSwitcher = new ThemeSwitcher();
});

// Also initialize if DOM is already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.themeSwitcher = new ThemeSwitcher();
    });
} else {
    window.themeSwitcher = new ThemeSwitcher();
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ThemeSwitcher;
}
