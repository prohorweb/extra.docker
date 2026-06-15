/**
 * Lenis smooth-scroll initializer with GSAP ScrollTrigger sync.
 *
 * Rules:
 *  - Skipped entirely when prefers-reduced-motion is set
 *  - Smooth wheel only; native touch on coarse-pointer devices (iOS/Android)
 *  - Driven by the GSAP ticker (single RAF loop, no double rAF overhead)
 *  - ScrollTrigger.update() called on every Lenis scroll event
 */
import Lenis from 'lenis';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion, isTouchPrimary } from './scroll-scenes/scene-utils';

/** @type {Lenis|null} */
let instance = null;

/** @type {Function|null} GSAP ticker callback reference for cleanup */
let tickerFn = null;

/**
 * Initialise Lenis and wire it into GSAP ScrollTrigger.
 * Safe to call multiple times — returns existing instance if already running.
 *
 * @returns {Lenis|null}
 */
export function initLenis() {
    if (instance) return instance;
    if (prefersReducedMotion()) return null;

    const touch = isTouchPrimary();

    instance = new Lenis({
        // Inertia duration in seconds
        duration     : 1.2,
        // Smooth exponential decay — feels natural without overshooting
        easing       : (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        orientation  : 'vertical',
        // Enable smooth wheel on desktop; keep native momentum on touch
        smoothWheel  : !touch,
        smoothTouch  : false,
        touchMultiplier: 2,
        infinite     : false,
        // Prevent over-scroll bounce from interfering with pinned sections
        overscroll   : false,
    });

    // Let GSAP own the RAF loop — Lenis piggybacks on it
    tickerFn = (time) => instance.raf(time * 1000);
    gsap.ticker.add(tickerFn);

    // Zero lag-smoothing so ScrollTrigger reacts immediately
    gsap.ticker.lagSmoothing(0);

    // Keep ScrollTrigger positions in sync with Lenis's virtual scroll
    instance.on('scroll', ScrollTrigger.update);

    return instance;
}

/** @returns {Lenis|null} */
export function getLenis() {
    return instance;
}

/**
 * Destroy Lenis and clean up all side-effects.
 * Called automatically on `pagehide`; can also be called manually.
 */
export function destroyLenis() {
    if (!instance) return;
    if (tickerFn) {
        gsap.ticker.remove(tickerFn);
        tickerFn = null;
    }
    instance.destroy();
    instance = null;
}
