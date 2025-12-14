/**
 * Parallax zoom effect for featured image on scroll
 */
(function() {
    'use strict';
    
    function initParallaxZoom() {
        const featuredImage = document.querySelector('.post-featured-image-hero .featured-image-hero');
        
        if (!featuredImage) {
            return;
        }
        
        const imageContainer = featuredImage.closest('.post-featured-image-hero');
        if (!imageContainer) {
            return;
        }
        
        const imageHeight = imageContainer.offsetHeight;
        const maxScroll = imageHeight * 0.5; // Zoom effect over half the image height
        const maxScale = 1.15; // Maximum zoom scale (15% larger)
        
        function handleScroll() {
            const rect = imageContainer.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            
            // Calculate how much of the image is visible
            const imageTop = rect.top;
            const imageBottom = rect.bottom;
            
            // Only apply effect when image is in viewport
            if (imageBottom < 0 || imageTop > windowHeight) {
                featuredImage.style.transform = 'scale(1)';
                return;
            }
            
            // Calculate scroll progress (0 to 1)
            // Effect is strongest when image is at top of viewport
            const scrollProgress = Math.max(0, Math.min(1, (windowHeight - imageTop) / maxScroll));
            
            // Apply zoom scale
            const scale = 1 + (scrollProgress * (maxScale - 1));
            featuredImage.style.transform = `scale(${scale})`;
        }
        
        // Throttle scroll events for performance
        let ticking = false;
        function onScroll() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        }
        
        // Initial calculation
        handleScroll();
        
        // Listen to scroll events
        window.addEventListener('scroll', onScroll, { passive: true });
        
        // Recalculate on resize
        window.addEventListener('resize', function() {
            handleScroll();
        }, { passive: true });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initParallaxZoom);
    } else {
        initParallaxZoom();
    }
})();

