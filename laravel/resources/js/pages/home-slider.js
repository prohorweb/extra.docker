/**
 * Homepage hero-slider entrypoint.
 * Thin orchestrator — all logic lives in lib/.
 */
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { initLenis, destroyLenis } from '../lib/lenis';
import { preloadImages }           from '../lib/preload-images';
import { createHorizontalSlider }  from '../lib/scroll-scenes/horizontal-slider';
import { prefersReducedMotion }    from '../lib/scroll-scenes/scene-utils';

gsap.registerPlugin(ScrollTrigger);

async function boot() {
    const slider = document.querySelector('.hero-slider');
    if (!slider) return;

    // --- 1. Accessibility bail-out ----------------------------------------
    // When reduced-motion is requested, the component's CSS fallback
    // (motion-reduce:flex-col on the track) already stacks slides vertically.
    // Nothing further to do.
    if (prefersReducedMotion()) return;

    // --- 2. Smooth scroll (desktop-only; skipped on touch) -----------------
    initLenis();

    // --- 3. Preload banner images before animating -------------------------
    // Prevents white-flash and layout shift on the first snap.
    await preloadImages('.hero-slider__slide img', slider);

    // --- 4. Build the horizontal scroll scene ------------------------------
    createHorizontalSlider(slider, { scrub: 1.5 });
}

// Clean up when the user navigates away (bfcache-safe)
window.addEventListener('pagehide', destroyLenis, { once: true });

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
