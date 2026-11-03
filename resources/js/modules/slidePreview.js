/**
 * Visual Carousel & Slide Preview Interactive Module
 */
export function initSlidePreview() {
    const carouselContainer = document.getElementById('slide-carousel');
    if (!carouselContainer) return;

    const slides = carouselContainer.querySelectorAll('.slide-item');
    const prevBtn = document.getElementById('slide-prev-btn');
    const nextBtn = document.getElementById('slide-next-btn');
    const indicatorContainer = document.getElementById('slide-indicators');
    const currentSlideNumEl = document.getElementById('current-slide-num');
    const totalSlideNumEl = document.getElementById('total-slide-num');

    if (!slides.length) return;

    let currentSlide = 0;
    const totalSlides = slides.length;

    if (totalSlideNumEl) {
        totalSlideNumEl.textContent = totalSlides;
    }

    // Build indicator dots
    if (indicatorContainer) {
        indicatorContainer.innerHTML = '';
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('button');
            dot.className = `w-2.5 h-2.5 rounded-full transition-all duration-300 ${i === 0 ? 'bg-amber-400 w-8' : 'bg-slate-600 hover:bg-slate-400'}`;
            dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
            dot.addEventListener('click', () => goToSlide(i));
            indicatorContainer.appendChild(dot);
        }
    }

    function updateSlideVisibility() {
        slides.forEach((slide, idx) => {
            if (idx === currentSlide) {
                slide.classList.remove('hidden');
                slide.classList.add('flex');
            } else {
                slide.classList.remove('flex');
                slide.classList.add('hidden');
            }
        });

        if (currentSlideNumEl) {
            currentSlideNumEl.textContent = currentSlide + 1;
        }

        // Update dots
        if (indicatorContainer) {
            const dots = indicatorContainer.querySelectorAll('button');
            dots.forEach((dot, idx) => {
                if (idx === currentSlide) {
                    dot.className = 'w-8 h-2.5 rounded-full bg-amber-400 transition-all duration-300';
                } else {
                    dot.className = 'w-2.5 h-2.5 rounded-full bg-slate-600 hover:bg-slate-400 transition-all duration-300';
                }
            });
        }

        if (prevBtn) {
            prevBtn.disabled = currentSlide === 0;
            prevBtn.classList.toggle('opacity-40', currentSlide === 0);
            prevBtn.classList.toggle('cursor-not-allowed', currentSlide === 0);
        }

        if (nextBtn) {
            nextBtn.disabled = currentSlide === totalSlides - 1;
            nextBtn.classList.toggle('opacity-40', currentSlide === totalSlides - 1);
            nextBtn.classList.toggle('cursor-not-allowed', currentSlide === totalSlides - 1);
        }
    }

    function goToSlide(index) {
        if (index >= 0 && index < totalSlides) {
            currentSlide = index;
            updateSlideVisibility();
        }
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            if (currentSlide > 0) {
                currentSlide--;
                updateSlideVisibility();
            }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                updateSlideVisibility();
            }
        });
    }

    // Keyboard navigation (Left / Right Arrow)
    document.addEventListener('keydown', (e) => {
        // Only if not focused in an input/textarea
        if (['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) return;

        if (e.key === 'ArrowLeft' && currentSlide > 0) {
            currentSlide--;
            updateSlideVisibility();
        } else if (e.key === 'ArrowRight' && currentSlide < totalSlides - 1) {
            currentSlide++;
            updateSlideVisibility();
        }
    });

    updateSlideVisibility();
}
