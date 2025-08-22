// Image Optimization Script
document.addEventListener('DOMContentLoaded', function() {
    // Lazy loading for images
    const images = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));

    // Preload critical images
    const criticalImages = [
        '/images/logo.jpg',
        '/images/logo2.jpg'
    ];

    criticalImages.forEach(src => {
        const link = document.createElement('link');
        link.rel = 'preload';
        link.as = 'image';
        link.href = src;
        document.head.appendChild(link);
    });

        // Optimize image loading
    const allImages = document.querySelectorAll('img');
    allImages.forEach(img => {
        // Add loading="lazy" to non-critical images
        if (!img.classList.contains('critical')) {
            img.loading = 'lazy';
        }
        
        // Add error handling
        img.onerror = function() {
            this.style.display = 'none';
        };
        
        // Auto-add dimensions if missing to prevent layout shift
        if (!img.hasAttribute('width') && !img.hasAttribute('height')) {
            img.onload = function() {
                if (!this.hasAttribute('width')) {
                    this.setAttribute('width', this.naturalWidth);
                }
                if (!this.hasAttribute('height')) {
                    this.setAttribute('height', this.naturalHeight);
                }
                // Add aspect-ratio CSS property for better responsive behavior
                this.style.aspectRatio = `${this.naturalWidth} / ${this.naturalHeight}`;
            };
        }
    });
});

// WebP support detection
function checkWebPSupport() {
    const webP = new Image();
    webP.onload = webP.onerror = function () {
        const isSupported = webP.height === 2;
        if (isSupported) {
            document.documentElement.classList.add('webp');
        } else {
            document.documentElement.classList.add('no-webp');
        }
    };
    webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
}

// Run WebP detection
checkWebPSupport();
