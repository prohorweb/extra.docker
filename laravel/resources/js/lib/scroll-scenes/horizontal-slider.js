/**
 * Horizontal scroll slider driven by GSAP ScrollTrigger pin + scrub + snap.
 *
 * Usage:
 *   import { createHorizontalSlider } from '../lib/scroll-scenes/horizontal-slider';
 *   const scene = createHorizontalSlider(sliderEl);
 *   // later: scene.kill();
 */
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion, debounce, killScene } from './scene-utils';

/** Sync active state to dot buttons. */
function updateDots(dots, index) {
    dots.forEach((dot, i) => {
        const active = i === index;
        dot.setAttribute('aria-selected', active);
        dot.classList.toggle('scale-150',  active);
        dot.classList.toggle('opacity-100', active);
        dot.classList.toggle('opacity-40',  !active);
    });
}

/**
 * @param {HTMLElement} slider  — root .hero-slider element
 * @param {Object}      [opts]
 * @param {number}      [opts.scrub=1.5]
 * @returns {{ kill: Function } | null}
 */
export function createHorizontalSlider(slider, opts = {}) {
    if (!slider || prefersReducedMotion()) return null;

    const track  = slider.querySelector('.hero-slider__track');
    const slides = slider.querySelectorAll('.hero-slider__slide');
    const dots   = [...slider.querySelectorAll('.hero-slider__dot')];
    const count  = slides.length;

    if (!track || count < 2) return null;

    const { scrub = 1.5 } = opts;

    // Distance to travel = total width of all slides minus one viewport
    const getDistance = () => track.scrollWidth - window.innerWidth;

    updateDots(dots, 0);

    // GPU-hint: set will-change only while the animation is active
    const enableGPU  = () => { track.style.willChange = 'transform'; };
    const disableGPU = () => { track.style.willChange = 'auto'; };

    const tween = gsap.to(track, {
        x   : () => -getDistance(),
        ease: 'none',
        scrollTrigger: {
            trigger     : slider,
            pin         : true,
            anticipatePin: 1,          // avoids jump on pin start
            scrub       : scrub,
            snap        : {
                snapTo  : 1 / (count - 1),
                duration: { min: 0.25, max: 0.6 },
                ease    : 'power2.inOut',
                delay   : 0.05,
                inertia : false,
            },
            end: () => `+=${getDistance()}`,
            invalidateOnRefresh: true,
            onEnter       : enableGPU,
            onLeave       : disableGPU,
            onEnterBack   : enableGPU,
            onLeaveBack   : disableGPU,
            onUpdate(self) {
                updateDots(dots, Math.round(self.progress * (count - 1)));
            },
        },
    });

    // Dot click — smooth-scroll to the corresponding snap position
    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            const st     = tween.scrollTrigger;
            const target = st.start + (st.end - st.start) * (i / (count - 1));
            window.scrollTo({ top: target, behavior: 'smooth' });
        });
    });

    // --- resize / orientation handling ---
    const refresh = debounce(() => ScrollTrigger.refresh(), 200);
    const onOrientation = () => setTimeout(() => ScrollTrigger.refresh(), 300);
    const mq = window.matchMedia('(orientation: landscape)');

    window.addEventListener('resize', refresh);
    mq.addEventListener('change', onOrientation);

    const kill = () => {
        killScene(tween);
        disableGPU();
        window.removeEventListener('resize', refresh);
        mq.removeEventListener('change', onOrientation);
    };

    window.addEventListener('pagehide', kill, { once: true });

    return { kill };
}
