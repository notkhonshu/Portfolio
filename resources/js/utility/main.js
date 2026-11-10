const desktop = window.matchMedia('(min-width: 45rem)');
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

const initPortfolio = () => {
    const section = document.querySelector('[data-portfolio]');
    const track = section?.querySelector('[data-portfolio-track]');
    if (!section || !track) return;

    const originals = [...track.children];
    if (!originals.length) return;

    if (reduceMotion.matches) {
        section.classList.replace('overflow-hidden', 'overflow-auto');
        track.classList.replace('absolute', 'relative');
        track.classList.add('min-h-full');
        return;
    }

    const periodCount = originals.length * (originals.length % 2 ? 2 : 1);

    const appendClones = (count) => {
        for (let i = 0; i < count; i += 1) {
            const clone = originals[i % originals.length].cloneNode(true);
            clone.setAttribute('aria-hidden', 'true');
            clone.tabIndex = -1;
            track.append(clone);
        }
    };

    appendClones(periodCount * 2 - originals.length);

    const size = () => (desktop.matches ? window.innerWidth : window.innerHeight);
    const length = () => (desktop.matches ? track.scrollWidth : track.scrollHeight);
    const period = () => {
        const first = track.children[0];
        const next = track.children[periodCount];
        return desktop.matches ? next.offsetLeft - first.offsetLeft : next.offsetTop - first.offsetTop;
    };

    const BASE_SPEED = 70;
    let distance = 0;
    let offset = 0;
    let factor = 1;
    let hoverScale = 1;
    let hovering = false;
    let frame = 0;
    let last = 0;
    let touchY = 0;

    const measure = () => {
        let guard = 0;
        while (length() < period() + size() && guard < 6) {
            appendClones(periodCount);
            guard += 1;
        }
        distance = period();
    };

    const paint = () => {
        track.style.transform = desktop.matches
            ? `translate3d(${-offset}px, 0, 0)`
            : `translate3d(0, ${-offset}px, 0)`;
    };

    const tick = (now) => {
        const dt = Math.min(0.05, (now - last) / 1000);
        last = now;
        factor += (1 - factor) * Math.min(1, dt * 2.5);
        hoverScale += ((hovering ? 0.25 : 1) - hoverScale) * Math.min(1, dt * 4);
        if (distance > 0) {
            offset += BASE_SPEED * factor * hoverScale * dt;
            offset = ((offset % distance) + distance) % distance;
            paint();
        }
        frame = requestAnimationFrame(tick);
    };

    const start = () => {
        if (frame) return;
        last = performance.now();
        frame = requestAnimationFrame(tick);
    };

    const stop = () => {
        cancelAnimationFrame(frame);
        frame = 0;
    };

    const nudge = (delta) => {
        factor = Math.max(-8, Math.min(8, factor + delta * 0.02));
    };

    window.addEventListener(
        'wheel',
        (event) => {
            if (event.target.closest('[data-detail]')) return;
            nudge(event.deltaY);
        },
        { passive: true },
    );

    window.addEventListener(
        'touchstart',
        (event) => {
            touchY = event.touches[0].clientY;
        },
        { passive: true },
    );

    window.addEventListener(
        'touchmove',
        (event) => {
            if (event.target.closest('[data-detail]')) return;
            const y = event.touches[0].clientY;
            nudge(touchY - y);
            touchY = y;
        },
        { passive: true },
    );

    track.addEventListener('pointerover', (event) => {
        hovering = event.pointerType === 'mouse' && Boolean(event.target.closest('a'));
    });
    track.addEventListener('pointerleave', () => {
        hovering = false;
    });

    window.addEventListener('resize', measure);
    desktop.addEventListener('change', () => {
        offset = 0;
        track.style.transform = '';
        measure();
    });

    new IntersectionObserver(([entry]) => (entry.isIntersecting ? start() : stop())).observe(section);

    measure();
    document.fonts?.ready.then(measure);
};

const initDetail = () => {
    const root = document.querySelector('[data-detail]');
    if (!root) return;

    const panel = root.querySelector('[data-detail-panel]');
    const scroller = root.querySelector('[data-detail-scroll]');
    const counter = root.querySelector('[data-detail-counter]');
    const items = [...root.querySelectorAll('[data-detail-item]')];
    const slugs = items.map((item) => item.dataset.detailItem);
    let index = -1;
    let trigger = null;

    const pad = (number) => String(number).padStart(2, '0');

    const show = (slug) => {
        const next = slugs.indexOf(slug);
        if (next < 0) return false;
        index = next;
        items.forEach((item, i) => {
            item.hidden = i !== index;
        });
        counter.textContent = `(Project ${pad(index + 1)} / ${pad(items.length)})`;
        panel.dataset.full = 'false';
        scroller.scrollTop = 0;
        history.replaceState(null, '', `#${slugs[index]}`);
        return true;
    };

    const open = (slug, source = null) => {
        if (!show(slug)) return;
        trigger = source;
        root.inert = false;
        root.dataset.open = 'true';
        document.documentElement.classList.add('overflow-hidden');
        panel.focus({ preventScroll: true });
    };

    const close = () => {
        if (root.dataset.open !== 'true') return;
        root.dataset.open = 'false';
        root.inert = true;
        document.documentElement.classList.remove('overflow-hidden');
        history.replaceState(null, '', window.location.pathname + window.location.search);
        trigger?.focus({ preventScroll: true });
    };

    scroller.addEventListener(
        'scroll',
        () => {
            if (scroller.scrollTop > 24) panel.dataset.full = 'true';
            else if (scroller.scrollTop < 4) panel.dataset.full = 'false';
        },
        { passive: true },
    );

    document.addEventListener('click', (event) => {
        const opener = event.target.closest('[data-project-open]');
        if (opener) {
            event.preventDefault();
            open(opener.dataset.projectOpen, opener);
            return;
        }
        if (event.target.closest('[data-detail-close]') || event.target.closest('[data-detail-backdrop]')) {
            close();
            return;
        }
        if (event.target.closest('[data-detail-next]')) {
            show(slugs[(index + 1) % slugs.length]);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') close();
    });

    const initial = window.location.hash.slice(1);
    if (slugs.includes(initial)) open(initial);
};

const initAbout = () => {
    const root = document.querySelector('[data-about]');
    const button = document.getElementById('aboutBtn');
    if (!root || !button) return;

    const panel = root.querySelector('[data-about-panel]');
    const scroller = root.querySelector('[data-about-scroll]');

    const unlock = () => {
        if (!document.querySelector('[data-detail][data-open="true"], [data-about][data-open="true"]')) {
            document.documentElement.classList.remove('overflow-hidden');
        }
    };

    const open = () => {
        root.inert = false;
        root.dataset.open = 'true';
        panel.dataset.full = 'false';
        scroller.scrollTop = 0;
        button.setAttribute('aria-expanded', 'true');
        document.documentElement.classList.add('overflow-hidden');
        panel.focus({ preventScroll: true });
    };

    const close = () => {
        if (root.dataset.open !== 'true') return;
        root.dataset.open = 'false';
        root.inert = true;
        button.setAttribute('aria-expanded', 'false');
        unlock();
        button.focus({ preventScroll: true });
    };

    button.setAttribute('aria-expanded', 'false');
    button.addEventListener('click', () => (root.dataset.open === 'true' ? close() : open()));

    root.addEventListener('click', (event) => {
        if (event.target.closest('[data-about-close]') || event.target.closest('[data-about-backdrop]')) close();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') close();
    });

    scroller.addEventListener(
        'scroll',
        () => {
            if (scroller.scrollTop > 24) panel.dataset.full = 'true';
            else if (scroller.scrollTop < 4) panel.dataset.full = 'false';
        },
        { passive: true },
    );

    if (window.location.hash === '#about') open();
};

initAbout();
initPortfolio();
initDetail();