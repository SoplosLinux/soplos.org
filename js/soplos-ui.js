/**
 * Soplos UI Components
 * Consolidated logic for Carousels, Modals, and Scroll effects.
 */

/* =========================================
   Scroll Controller
   ========================================= */
class ScrollController {
    constructor() {
        this.backToTopButton = document.querySelector('.back-to-top');
        this.header = document.querySelector('header');
        this.init();
    }

    init() {
        // Back to top button logic
        if (!this.backToTopButton) {
            this.backToTopButton = document.createElement('a');
            this.backToTopButton.className = 'back-to-top';
            this.backToTopButton.href = '#inicio';
            this.backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
            document.body.appendChild(this.backToTopButton);
        }

        window.addEventListener('scroll', () => {
            // Back to top visibility
            if (window.pageYOffset > 300) {
                this.backToTopButton.classList.add('show');
            } else {
                this.backToTopButton.classList.remove('show');
            }

            // Header scroll effect
            if (this.header) {
                if (window.scrollY > 40) {
                    this.header.classList.add('scrolled');
                } else {
                    this.header.classList.remove('scrolled');
                }
            }
        });

        this.backToTopButton.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Smooth scroll for internal links
        this.initSmoothScroll();
    }

    initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            // Skip back-to-top as it's handled separately
            if (anchor.classList.contains('back-to-top')) return;
            // Skip if it's a tab/modal trigger (usually these have specific handlers, but we can check role or class)
            // For now, we apply to all, but specific handlers might preventDefault first.
            anchor.addEventListener('click', e => {
                // If it's a menu link, it might be handled by menu logic, but smooth scroll is universal
                this.smoothScroll(e, anchor);
            });
        });
    }

    smoothScroll(e, anchor) {
        const targetId = anchor.getAttribute('href');
        if (!targetId || targetId === '#') return;

        // If target is a modal, don't scroll
        if (targetId.startsWith('#modal')) return;

        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            e.preventDefault();
            // Use scrollIntoView which respects scroll-padding-top in CSS
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    smoothScroll(e, anchor) {
        const targetId = anchor.getAttribute('href');
        if (!targetId || targetId === '#') return;

        // If target is a modal, don't scroll
        if (targetId.startsWith('#modal')) return;

        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            e.preventDefault();
            const headerHeight = this.header ? this.header.offsetHeight : 0;
            const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight;

            if (Math.abs(window.pageYOffset - targetPosition) > 2) {
                window.scrollTo({ top: targetPosition, behavior: 'smooth' });
            }
        }
    }
}

/* =========================================
   Soplos Carousel (Mobile)
   ========================================= */
class SoplosCarousel {
    constructor(containerSelector, itemSelector, options = {}) {
        this.container = document.querySelector(containerSelector);
        this.itemSelector = itemSelector;
        this.options = Object.assign({
            mobileBreak: 768,
            dotsClass: 'carousel-dots',
            prevClass: 'carousel-arrow prev',
            nextClass: 'carousel-arrow next',
            activeClass: 'active',
            activeClass: 'active',
            scaleInactive: 0.92,
            minHeight: '320px',
            itemWidth: '90%'
        }, options);

        this.items = [];
        this.prevBtn = null;
        this.nextBtn = null;
        this.dots = null;
        this.dotEls = [];
        this.activeIdx = 0;
        this.initialized = false;
        this.handlers = {};

        this.init();

        // Handle resize
        let prevIsMobile = window.innerWidth <= this.options.mobileBreak;
        let timeout = null;
        window.addEventListener('resize', () => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const isMobile = window.innerWidth <= this.options.mobileBreak;
                if (isMobile !== prevIsMobile) {
                    if (isMobile) this.init();
                    else this.destroy();
                }
                prevIsMobile = isMobile;
            }, 200);
        });
    }

    init() {
        if (this.initialized) return;
        if (!this.container) return;
        if (window.innerWidth > this.options.mobileBreak) return;

        // Find items
        // If container is the direct parent (like features-grid), use children
        // If container is a wrapper (like requirements-cards), use querySelectorAll
        this.items = Array.from(this.container.querySelectorAll(this.itemSelector));
        // Fallback: if querySelectorAll returns nothing, maybe they are direct children
        if (this.items.length === 0) {
            this.items = Array.from(this.container.children).filter(c => c.matches(this.itemSelector));
        }

        if (this.items.length === 0) return;

        this.applyStyles();
        this.createControls();
        this.setupEvents();
        this.showItem(0);
        this.initialized = true;
    }

    applyStyles() {
        // Apply container styles
        Object.assign(this.container.style, {
            display: 'flex',
            flexDirection: 'row',
            justifyContent: 'center',
            alignItems: 'center',
            overflow: 'hidden',
            width: '100%',
            width: '100%',
            minHeight: this.options.minHeight,
            position: 'relative'
        });

        // Apply item styles
        this.items.forEach(item => {
            Object.assign(item.style, {
                display: 'block',
                opacity: '0',
                pointerEvents: 'none',
                position: 'absolute',
                top: '50%',
                left: '50%',
                width: this.options.itemWidth,
                height: 'auto',
                transition: 'transform 0.35s ease, opacity 0.35s ease',
                zIndex: '0',
                margin: '0 auto'
            });
            // Specific overrides if needed based on previous scripts
            if (item.classList.contains('feature-card')) {
                item.style.top = '0'; // Features used top:0
                item.style.height = '100%';
            }
        });
    }

    createControls() {
        // Arrows
        if (!this.prevBtn) {
            this.prevBtn = document.createElement('button');
            this.prevBtn.className = this.options.prevClass;
            this.prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
            this.prevBtn.setAttribute('aria-label', 'Previous');
            this.container.appendChild(this.prevBtn);
            this.container._createdPrev = true;
        }
        if (!this.nextBtn) {
            this.nextBtn = document.createElement('button');
            this.nextBtn.className = this.options.nextClass;
            this.nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
            this.nextBtn.setAttribute('aria-label', 'Next');
            this.container.appendChild(this.nextBtn);
            this.container._createdNext = true;
        }

        // Dots
        if (!this.dots) {
            this.dots = document.createElement('div');
            this.dots.className = this.options.dotsClass;
            // Basic dots styles
            Object.assign(this.dots.style, {
                position: 'relative',
                display: 'flex',
                justifyContent: 'center',
                alignItems: 'center',
                marginTop: '18px'
            });

            this.items.forEach((_, idx) => {
                const dot = document.createElement('span');
                // Use carousel-dot as the base class for all dots
                dot.className = 'carousel-dot';
                // Add specific class if provided (e.g. features-dot)
                if (this.options.dotClass) dot.classList.add(this.options.dotClass);

                dot.setAttribute('role', 'button');
                dot.setAttribute('aria-label', `Go to item ${idx + 1}`);
                this.dots.appendChild(dot);
            });

            this.container.parentNode.insertBefore(this.dots, this.container.nextSibling);
            this.container._createdDots = true;
            this.dotEls = Array.from(this.dots.children);
        }
    }

    setupEvents() {
        // Arrow clicks
        this.handlers.prevClick = (e) => {
            if (e) { e.preventDefault(); e.stopPropagation(); }
            if (this.activeIdx > 0) this.showItem(this.activeIdx - 1);
        };
        this.handlers.nextClick = (e) => {
            if (e) { e.preventDefault(); e.stopPropagation(); }
            if (this.activeIdx < this.items.length - 1) this.showItem(this.activeIdx + 1);
        };

        this.prevBtn.addEventListener('click', this.handlers.prevClick);
        this.nextBtn.addEventListener('click', this.handlers.nextClick);

        // Dot clicks
        this.dotEls.forEach((dot, idx) => {
            this.handlers['dotClick' + idx] = (e) => {
                if (e) e.preventDefault();
                this.showItem(idx);
            };
            dot.addEventListener('click', this.handlers['dotClick' + idx]);
        });

        // Touch Swipe
        let startX = null;
        let startY = null;
        let isSwiping = false;

        this.handlers.touchStart = (e) => {
            if (e.touches.length === 1) {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                isSwiping = false;
            }
        };

        this.handlers.touchMove = (e) => {
            if (startX !== null && startY !== null && e.touches.length === 1) {
                const currentX = e.touches[0].clientX;
                const currentY = e.touches[0].clientY;
                const deltaX = Math.abs(currentX - startX);
                const deltaY = Math.abs(currentY - startY);

                // Solo bloquear scroll si el movimiento es más horizontal que vertical
                if (deltaX > deltaY && deltaX > 10) {
                    isSwiping = true;
                    e.preventDefault(); // Lock scroll when swiping horizontally
                }
            }
        };

        this.handlers.touchEnd = (e) => {
            if (startX === null) return;
            const endX = e.changedTouches[0].clientX;
            const delta = endX - startX;
            if (Math.abs(delta) > 40 && isSwiping) {
                if (delta < 0 && this.activeIdx < this.items.length - 1) this.showItem(this.activeIdx + 1);
                else if (delta > 0 && this.activeIdx > 0) this.showItem(this.activeIdx - 1);
            }
            startX = null;
            startY = null;
            isSwiping = false;
        };

        this.container.addEventListener('touchstart', this.handlers.touchStart, { passive: false });
        this.container.addEventListener('touchmove', this.handlers.touchMove, { passive: false });
        this.container.addEventListener('touchend', this.handlers.touchEnd, { passive: false });
    }

    showItem(idx) {
        this.items.forEach((item, i) => {
            const offset = (i - idx);
            const scale = i === idx ? 1 : this.options.scaleInactive;

            // Standard transform for all carousels
            let translateY = '-50%';
            if (item.classList.contains('feature-card')) {
                translateY = '0';
            }
            item.style.transform = `translate(-50%, ${translateY}) translateX(${offset * 100}%) scale(${scale})`;

            // Features carousel had a specific transform (translateX(-50%) scale(...)) because it was top:0
            // We can normalize this. If we set top:50% left:50% for all, the above formula works.
            // But if 'feature-card' needs top:0, we might need adjustment.
            // Let's stick to the generic one which centers everything. 
            // If feature-card looks weird, we might need to adjust its CSS or this logic.
            // Actually, features-carousel.js used: translateX(calc(${(i - idx) * 100}% - 50%))
            // which is equivalent to translate(-50%) translateX(...) if we ignore the second translate(-50%) for Y.

            if (i === idx) {
                item.style.opacity = '1';
                item.style.pointerEvents = 'auto';
                item.style.zIndex = '2';
                item.classList.add(this.options.activeClass);
            } else {
                item.style.opacity = '0';
                item.style.pointerEvents = 'none';
                item.style.zIndex = '0';
                item.classList.remove(this.options.activeClass);
            }
        });

        // Update dots
        this.dotEls.forEach((dot, i) => {
            if (i === idx) dot.classList.add('active');
            else dot.classList.remove('active');
        });

        // Update arrows
        if (this.prevBtn) this.prevBtn.disabled = idx === 0;
        if (this.nextBtn) this.nextBtn.disabled = idx === this.items.length - 1;

        this.activeIdx = idx;
    }

    destroy() {
        if (!this.initialized) return;

        // Remove listeners
        if (this.prevBtn) {
            this.prevBtn.removeEventListener('click', this.handlers.prevClick);
            this.prevBtn.remove();
        }
        if (this.nextBtn) {
            this.nextBtn.removeEventListener('click', this.handlers.nextClick);
            this.nextBtn.remove();
        }
        if (this.dots) {
            this.dots.remove();
        }

        this.container.removeEventListener('touchstart', this.handlers.touchStart);
        this.container.removeEventListener('touchmove', this.handlers.touchMove);
        this.container.removeEventListener('touchend', this.handlers.touchEnd);

        // Reset styles
        this.container.removeAttribute('style');
        this.items.forEach(item => {
            item.removeAttribute('style');
            item.classList.remove(this.options.activeClass);
        });

        this.items = [];
        this.prevBtn = null;
        this.nextBtn = null;
        this.dots = null;
        this.dotEls = [];
        this.handlers = {};
        this.initialized = false;
    }
}

/* =========================================
   Soplos Modal (Base)
   ========================================= */
class SoplosModal {
    constructor(modalId, options = {}) {
        this.modal = document.getElementById(modalId);
        this.options = options;

        if (!this.modal) return;

        this.closeBtn = this.modal.querySelector(options.closeSelector || '.close-modal');
        this.content = this.modal.querySelector(options.contentSelector || '.modal-content');

        this.init();
    }

    init() {
        // Close button
        if (this.closeBtn) {
            this.closeBtn.addEventListener('click', () => this.close());
        }

        // Click outside to close
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) this.close();
        });

        // Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.classList.contains('show')) {
                this.close();
            }
        });
    }

    open(triggerElement) {
        this.lastFocused = triggerElement || document.activeElement;
        this.modal.classList.add('show');
        document.body.style.overflow = 'hidden'; // Prevent background scroll
        document.documentElement.style.overflow = 'hidden'; // Prevent mobile background scroll

        // Focus trap
        this.modal.setAttribute('tabindex', '-1');
        this.modal.focus();
    }

    close() {
        if (!this.modal.classList.contains('show')) return;

        this.modal.classList.add('closing');

        const onAnimEnd = () => {
            this.modal.classList.remove('show');
            this.modal.classList.remove('closing');
            document.body.style.removeProperty('overflow');
            document.documentElement.style.removeProperty('overflow');
            this.modal.removeEventListener('animationend', onAnimEnd);

            if (this.lastFocused) {
                this.lastFocused.focus();
            }
        };

        this.modal.addEventListener('animationend', onAnimEnd);
        // Fallback
        setTimeout(() => {
            if (this.modal.classList.contains('closing')) onAnimEnd();
        }, 400);
    }
}

/* =========================================
   Soplos Image Modal (Gallery & Apps)
   ========================================= */
class SoplosImageModal extends SoplosModal {
    constructor(modalId, options = {}) {
        super(modalId, options);

        if (!this.modal) return;

        this.track = this.modal.querySelector(options.trackSelector || '.modal-track');
        this.caption = this.modal.querySelector(options.captionSelector || '.modal-caption');
        this.prevBtn = this.modal.querySelector(options.prevSelector || '.modal-nav.prev');
        this.nextBtn = this.modal.querySelector(options.nextSelector || '.modal-nav.next');
        this.dotsContainer = this.modal.querySelector(options.dotsSelector || '.modal-dots');

        this.images = [];
        this.currentIndex = 0;

        this.setupNavigation();
    }

    setupNavigation() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.prev();
            });
        }
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.next();
            });
        }

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!this.modal.classList.contains('show')) return;
            if (e.key === 'ArrowLeft') this.prev();
            if (e.key === 'ArrowRight') this.next();
        });

        // Touch swipe
        let startX = null;
        let startY = null;
        let isSwiping = false;

        this.modal.addEventListener('touchstart', (e) => {
            if (e.touches.length === 1) {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                isSwiping = false;
            }
        }, { passive: false });

        this.modal.addEventListener('touchmove', (e) => {
            if (startX !== null && startY !== null && e.touches.length === 1) {
                const currentX = e.touches[0].clientX;
                const currentY = e.touches[0].clientY;
                const deltaX = Math.abs(currentX - startX);
                const deltaY = Math.abs(currentY - startY);

                // Solo bloquear scroll si el movimiento es más horizontal que vertical
                if (deltaX > deltaY && deltaX > 10) {
                    isSwiping = true;
                    e.preventDefault();
                }
            }
        }, { passive: false });

        this.modal.addEventListener('touchend', (e) => {
            if (startX === null) return;
            const endX = e.changedTouches[0].clientX;
            const delta = endX - startX;
            if (Math.abs(delta) > 50 && isSwiping) {
                if (delta < 0) this.next();
                else this.prev();
            }
            startX = null;
            startY = null;
            isSwiping = false;
        }, { passive: false });
    }

    setImages(images) {
        this.images = images;
        this.currentIndex = 0;
        this.render();
    }

    render() {
        if (!this.track) return;
        this.track.innerHTML = '';
        if (this.dotsContainer) this.dotsContainer.innerHTML = '';

        this.images.forEach((imgData, idx) => {
            const img = document.createElement('img');
            img.src = imgData.src;
            img.alt = imgData.caption || '';

            // Styles
            Object.assign(img.style, {
                position: 'absolute',
                top: '50%',
                left: '50%',
                transform: `translate(-50%, -50%) translateX(${(idx - this.currentIndex) * 100}%)`,
                maxWidth: '85vw',
                maxHeight: '75vh',
                opacity: idx === this.currentIndex ? '1' : '0',
                transition: 'transform 0.3s ease, opacity 0.3s ease',
                pointerEvents: idx === this.currentIndex ? 'auto' : 'none'
            });

            this.track.appendChild(img);

            // Dots
            if (this.dotsContainer) {
                const dot = document.createElement('button');
                dot.className = 'carousel-dot' + (idx === this.currentIndex ? ' active' : '');
                dot.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.goTo(idx);
                });
                this.dotsContainer.appendChild(dot);
            }
        });

        this.updateUI();
    }

    updateUI() {
        // Update images transform
        Array.from(this.track.children).forEach((img, idx) => {
            img.style.transform = `translate(-50%, -50%) translateX(${(idx - this.currentIndex) * 100}%)`;
            img.style.opacity = idx === this.currentIndex ? '1' : '0';
            img.style.pointerEvents = idx === this.currentIndex ? 'auto' : 'none';
        });

        // Update dots
        if (this.dotsContainer) {
            Array.from(this.dotsContainer.children).forEach((dot, idx) => {
                if (idx === this.currentIndex) dot.classList.add('active');
                else dot.classList.remove('active');
            });
        }

        // Update caption
        if (this.caption && this.images[this.currentIndex]) {
            const currentImg = this.images[this.currentIndex];
            // Try translation if key exists
            if (currentImg.captionKey && window.getTranslatedText) {
                this.caption.textContent = window.getTranslatedText(currentImg.captionKey);
            } else {
                this.caption.textContent = currentImg.caption || '';
            }
        }
    }

    prev() {
        if (this.images.length < 2) return;
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        this.updateUI();
    }

    next() {
        if (this.images.length < 2) return;
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
        this.updateUI();
    }

    goTo(idx) {
        this.currentIndex = idx;
        this.updateUI();
    }
}

/* =========================================
   Breadcrumb Controller
   ========================================= */
class BreadcrumbController {
    constructor() {
        this.init();
    }

    init() {
        const breadcrumbs = document.querySelector('.breadcrumb');
        if (!breadcrumbs) return;

        const referrer = document.referrer;
        if (!referrer) return;

        let parentName = '';
        let parentUrl = '';

        if (referrer.includes('/tyron/')) {
            parentName = 'Tyron Apps';
            parentUrl = '../tyron/index.html';
        } else if (referrer.includes('/tyson/')) {
            parentName = 'Tyson Apps';
            parentUrl = '../tyson/index.html';
        } else if (referrer.includes('/boro/')) {
            parentName = 'Boro Apps';
            parentUrl = '../boro/index.html';
        }

        if (parentName && parentUrl) {
            // Find the "Wiki" link. It should be the second link usually.
            // We look for a link with text "Wiki" to be robust against path variations (../index.html, ../../index.html)
            const wikiLink = Array.from(breadcrumbs.querySelectorAll('a')).find(a => a.textContent.trim() === 'Wiki');

            if (wikiLink) {
                // Create separator
                const separator = document.createElement('span');
                separator.className = 'breadcrumb-separator';
                separator.textContent = '›';

                // Create new link
                const newLink = document.createElement('a');
                newLink.href = parentUrl;
                newLink.textContent = parentName;

                // Insert after Wiki link: Wiki > Parent > App
                wikiLink.after(separator, newLink);
            }
        }
    }
}

// Helper for translations (needs to be globally available or passed in)
window.getTranslatedText = function (key) {
    // Try to find current language from selector
    const langSelector = document.querySelector('.selected-language span');
    const lang = langSelector ? langSelector.textContent.toLowerCase() : 'en';

    const dict = window['LANG_' + lang.toUpperCase()] || window.LANG_EN;
    return dict && dict[key] ? dict[key] : key;
};

// Export classes globally
window.SoplosUI = {
    ScrollController,
    SoplosCarousel,
    SoplosModal,
    SoplosImageModal,
    BreadcrumbController
};
