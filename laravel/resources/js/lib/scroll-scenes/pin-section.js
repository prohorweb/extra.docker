/**
 * Pin a section while its inner timeline plays.
 *
 * Usage:
 *   import { pinSection } from '../lib/scroll-scenes/pin-section';
 *   const { timeline, kill } = pinSection('#about', { end: '+=200%' });
 *   timeline.to('.text', { opacity: 1 });
 */
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion, killScene } from './scene-utils';

/**
 * @param {string|HTMLElement} trigger
 * @param {Object} opts
 * @param {number} [opts.scrub=1]
 * @param {string} [opts.start='top top']
 * @param {string} [opts.end='+=100%']
 * @returns {{ timeline: gsap.core.Timeline, kill: Function } | null}
 */
export function pinSection(trigger, opts = {}) {
    if (prefersReducedMotion()) return null;

    const timeline = gsap.timeline({
        scrollTrigger: {
            trigger,
            pin          : true,
            anticipatePin: 1,
            scrub        : opts.scrub ?? 1,
            start        : opts.start ?? 'top top',
            end          : opts.end   ?? '+=100%',
            invalidateOnRefresh: true,
            ...opts.scrollTrigger,
        },
    });

    const kill = () => killScene(timeline);
    window.addEventListener('pagehide', kill, { once: true });

    return { timeline, kill };
}
