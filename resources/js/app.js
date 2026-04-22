import './bootstrap';

const hasPrecisePointer = window.matchMedia('(hover: hover) and (pointer: fine)');

const initCustomCursor = () => {
    if (!hasPrecisePointer.matches) {
        return;
    }

    const cursor = document.querySelector('.custom-cursor');
    const ring = document.querySelector('.custom-cursor-ring');

    if (!cursor || !ring) {
        return;
    }

    document.body.classList.add('cursor-enhanced');

    const interactiveSelector = 'a, button, [role="button"], input, textarea, select, summary, label';
    let pointerX = window.innerWidth / 2;
    let pointerY = window.innerHeight / 2;
    let ringX = pointerX;
    let ringY = pointerY;
    let rafId = null;

    const render = () => {
        ringX += (pointerX - ringX) * 0.18;
        ringY += (pointerY - ringY) * 0.18;

        cursor.style.transform = `translate(${pointerX}px, ${pointerY}px) translate(-50%, -50%)`;
        ring.style.transform = `translate(${ringX}px, ${ringY}px) translate(-50%, -50%)`;

        rafId = window.requestAnimationFrame(render);
    };

    const showCursor = () => {
        cursor.classList.add('is-visible');
        ring.classList.add('is-visible');

        if (!rafId) {
            rafId = window.requestAnimationFrame(render);
        }
    };

    const hideCursor = () => {
        cursor.classList.remove('is-visible', 'is-active');
        ring.classList.remove('is-visible', 'is-hover', 'is-active');

        if (rafId) {
            window.cancelAnimationFrame(rafId);
            rafId = null;
        }
    };

    document.addEventListener('mousemove', (event) => {
        pointerX = event.clientX;
        pointerY = event.clientY;
        showCursor();

        const target = event.target instanceof Element ? event.target.closest(interactiveSelector) : null;
        ring.classList.toggle('is-hover', Boolean(target));
    });

    document.addEventListener('mousedown', () => {
        cursor.classList.add('is-active');
        ring.classList.add('is-active');
    });

    document.addEventListener('mouseup', () => {
        cursor.classList.remove('is-active');
        ring.classList.remove('is-active');
    });

    document.addEventListener('mouseleave', hideCursor);
    window.addEventListener('blur', hideCursor);
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCustomCursor, { once: true });
} else {
    initCustomCursor();
}
