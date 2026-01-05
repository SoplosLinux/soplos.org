/**
 * Soplos Theme - Main Script
 * 
 * @package Soplos
 */

(function($) {
    'use strict';

    // DOM Ready
    $(document).ready(function() {
        initBackToTop();
        initSmoothScroll();
        initExternalLinks();
    });

    /**
     * Back to Top Button
     */
    function initBackToTop() {
        const backToTop = document.getElementById('backToTop');
        
        if (!backToTop) return;

        // Show/hide button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        // Scroll to top on click
        backToTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    /**
     * Smooth Scroll for Anchor Links
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                
                if (targetId === '#') return;
                
                const target = document.querySelector(targetId);
                
                if (target) {
                    e.preventDefault();
                    
                    const headerOffset = 100;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * Open External Links in New Tab
     */
    function initExternalLinks() {
        const links = document.querySelectorAll('a[href^="http"]');
        const hostname = window.location.hostname;

        links.forEach(function(link) {
            if (!link.hostname.includes(hostname)) {
                link.setAttribute('target', '_blank');
                link.setAttribute('rel', 'noopener noreferrer');
            }
        });
    }

    /**
     * Add loading class to body while images load
     */
    window.addEventListener('load', function() {
        document.body.classList.add('loaded');
    });

})(jQuery);
