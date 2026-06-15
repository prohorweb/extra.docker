/**
 * Resolves when every <img> matching `selector` has finished loading (or errored).
 * Always resolves — never rejects — so one broken image can't block the slider.
 *
 * @param {string} [selector='img']
 * @param {HTMLElement|Document} [root=document]
 * @returns {Promise<void>}
 */
export function preloadImages(selector = 'img', root = document) {
    const images = [...root.querySelectorAll(selector)];
    if (!images.length) return Promise.resolve();

    return Promise.all(
        images.map((img) => {
            // Already loaded (e.g. cached or inline SVG)
            if (img.complete && img.naturalWidth > 0) return Promise.resolve();

            return new Promise((resolve) => {
                img.addEventListener('load',  resolve, { once: true });
                img.addEventListener('error', resolve, { once: true });
            });
        })
    );
}
