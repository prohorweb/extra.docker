/**
 * Returns true if the OS/browser has "reduce motion" enabled.
 * Cached after first call — the setting never changes mid-session.
 */
let _reduced = null;
export function prefersReducedMotion() {
    if (_reduced === null) {
        _reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }
    return _reduced;
}

/**
 * Returns true when the primary pointer is coarse (touch device).
 * Used to decide whether to enable smooth-scroll on mobile.
 */
export function isTouchPrimary() {
    return window.matchMedia('(pointer: coarse)').matches;
}

/**
 * Trailing debounce.
 */
export function debounce(fn, delay = 150) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

/**
 * Kill a GSAP tween and its attached ScrollTrigger without throwing.
 */
export function killScene(tween) {
    if (!tween) return;
    if (tween.scrollTrigger) tween.scrollTrigger.kill(true);
    tween.kill();
}
