/**
 * Lightweight GSAP scroll-snap between fullscreen sections.
 * Alternative to CSS scroll-snap when GSAP control is needed.
 *
 * Usage:
 *   import { snapScroll } from '../lib/scroll-scenes/snap-scroll';
 *   const scene = snapScroll('.snap-section');
 *   // later: scene.kill();
 */
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion } from './scene-utils';

/**
 * @param {string|HTMLElement[]} sections — CSS selector or array of elements
 * @param {Object} [opts]                 — merged into each ScrollTrigger config
 * @returns {{ kill: Function } | null}
 */
export function snapScroll(sections, opts = {}) {
    if (prefersReducedMotion()) return null;

    const els = typeof sections === 'string'
        ? [...document.querySelectorAll(sections)]
        : [...sections];

    if (!els.length) return null;

    const triggers = els.map((el) =>
        ScrollTrigger.create({
            trigger : el,
            start   : 'top top',
            snap    : {
                snapTo  : 'labels',
                duration: { min: 0.3, max: 0.5 },
                delay   : 0.1,
                ease    : 'power2.inOut',
            },
            invalidateOnRefresh: true,
            ...opts,
        })
    );

    return { kill: () => triggers.forEach((t) => t.kill(true)) };
}
