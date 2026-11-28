const desktop = window.matchMedia('(min-width: 45rem)');
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

const lightboxOpen = () => document.querySelector('[data-lightbox][data-open="true"]') !== null;

const syncLock = () => {
    const anyOpen = document.querySelector('[data-detail][data-open="true"], [data-about][data-open="true"]') !== null;
    document.documentElement.classList.toggle('overflow-hidden', anyOpen);
};

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
            if (event.target.closest('[data-detail], [data-about]')) return;
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
            if (event.target.closest('[data-detail], [data-about]')) return;
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

const projectPath = (slug) => `/${encodeURIComponent(slug)}`;
const slugFromPath = () => {
    const path = window.location.pathname.replace(/^\/|\/$/g, '');
    return path ? decodeURIComponent(path) : null;
};

const initDetail = () => {
    const root = document.querySelector('[data-detail]');
    if (!root) return;

    const panel = root.querySelector('[data-detail-panel]');
    const scroller = root.querySelector('[data-detail-scroll]');
    const counter = root.querySelector('[data-detail-counter]');
    const items = [...root.querySelectorAll('[data-detail-item]')];
    const slugs = items.map((item) => item.dataset.detailItem);
    const homeTitle = root.dataset.homeTitle || document.title;
    let index = -1;
    let trigger = null;

    const pad = (number) => String(number).padStart(2, '0');

    const render = (slug) => {
        const next = slugs.indexOf(slug);
        if (next < 0) return false;
        index = next;
        items.forEach((item, i) => {
            item.hidden = i !== index;
        });
        counter.textContent = `(Project ${pad(index + 1)} / ${pad(items.length)})`;
        panel.dataset.full = 'false';
        scroller.scrollTop = 0;
        document.title = items[index].dataset.detailTitle || homeTitle;
        return true;
    };

    const reveal = (slug, source = null) => {
        if (!render(slug)) return false;
        trigger = source;
        root.inert = false;
        root.dataset.open = 'true';
        syncLock();
        panel.focus({ preventScroll: true });
        return true;
    };

    const hide = () => {
        if (root.dataset.open !== 'true') return;
        root.dataset.open = 'false';
        root.inert = true;
        syncLock();
        document.title = homeTitle;
        trigger?.focus({ preventScroll: true });
        trigger = null;
    };

    const open = (slug, source) => {
        if (!reveal(slug, source)) return;
        history.pushState({ project: slug, pushed: true }, '', projectPath(slug));
    };

    const close = () => {
        if (root.dataset.open !== 'true') return;
        if (history.state?.pushed) {
            history.back();
        } else {
            hide();
            history.replaceState(null, '', '/');
        }
    };

    const go = (slug) => {
        if (!render(slug)) return;
        history.replaceState({ project: slug, pushed: history.state?.pushed === true }, '', projectPath(slug));
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
        if (event.target.closest('[data-lightbox]')) return;

        const opener = event.target.closest('[data-project-open]');
        if (opener) {
            if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
            event.preventDefault();
            open(opener.dataset.projectOpen, opener);
            return;
        }
        if (event.target.closest('[data-detail-close]') || event.target.closest('[data-detail-backdrop]')) {
            close();
            return;
        }
        if (event.target.closest('[data-detail-next]')) {
            go(slugs[(index + 1) % slugs.length]);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !lightboxOpen()) close();
    });

    window.addEventListener('popstate', () => {
        const slug = slugFromPath();
        if (slug && slugs.includes(slug)) {
            reveal(slug);
        } else {
            hide();
        }
    });

    const fromPath = slugFromPath();
    const fromHash = window.location.hash.slice(1);

    if (fromPath && slugs.includes(fromPath)) {
        history.replaceState({ project: fromPath, pushed: false }, '', projectPath(fromPath));
        reveal(fromPath);
    } else if (slugs.includes(fromHash)) {
        history.replaceState({ project: fromHash, pushed: false }, '', projectPath(fromHash));
        reveal(fromHash);
    }
};

const initLightbox = () => {
    const root = document.querySelector('[data-detail]');
    const box = root?.querySelector('[data-lightbox]');
    if (!root || !box) return;

    const img = box.querySelector('[data-lightbox-img]');
    const counter = box.querySelector('[data-lightbox-counter]');
    const closeBtn = box.querySelector('[data-lightbox-close]');

    let items = [];
    let index = 0;
    let opener = null;

    const show = (i) => {
        index = (i + items.length) % items.length;
        const el = items[index];
        img.src = el.dataset.src;
        img.alt = el.dataset.alt;
        counter.textContent = `${index + 1} / ${items.length}`;
    };

    const open = (list, i, trigger) => {
        items = list;
        opener = trigger;
        show(i);
        box.dataset.open = 'true';
        closeBtn.focus({ preventScroll: true });
    };

    const close = (restoreFocus = true) => {
        if (!lightboxOpen()) return;
        box.dataset.open = 'false';
        img.removeAttribute('src');
        if (restoreFocus) opener?.focus({ preventScroll: true });
    };

    root.addEventListener('click', (event) => {
        const item = event.target.closest('[data-gallery-item]');
        if (!item) return;
        const list = [...item.closest('[data-gallery]').querySelectorAll('[data-gallery-item]')];
        open(list, list.indexOf(item), item);
    });

    box.addEventListener('click', (event) => {
        event.stopPropagation();
        const target = event.target;
        if (target.closest('[data-lightbox-prev]')) show(index - 1);
        else if (target.closest('[data-lightbox-next]')) show(index + 1);
        else if (target.closest('[data-lightbox-close]') || target.closest('[data-lightbox-backdrop]')) close();
    });

    window.addEventListener(
        'keydown',
        (event) => {
            if (!lightboxOpen()) return;
            if (event.key === 'Escape') {
                event.preventDefault();
                event.stopImmediatePropagation();
                close();
            } else if (event.key === 'ArrowLeft') show(index - 1);
            else if (event.key === 'ArrowRight') show(index + 1);
        },
        true,
    );

    new MutationObserver(() => {
        if (root.dataset.open !== 'true') close(false);
    }).observe(root, { attributes: true, attributeFilter: ['data-open'] });
};

const ABOUT_SLUG = 'about-me';
const aboutPath = `/${ABOUT_SLUG}`;
const isAboutPath = () => slugFromPath() === ABOUT_SLUG;

const initAbout = () => {
    const root = document.querySelector('[data-about]');
    const button = document.getElementById('aboutBtn');
    if (!root || !button) return;

    const panel = root.querySelector('[data-about-panel]');
    const scroller = root.querySelector('[data-about-scroll]');
    const homeTitle = root.dataset.homeTitle || document.title;
    const aboutTitle = root.dataset.aboutTitle || homeTitle;

    const reveal = () => {
        root.inert = false;
        root.dataset.open = 'true';
        panel.dataset.full = 'false';
        scroller.scrollTop = 0;
        button.setAttribute('aria-expanded', 'true');
        document.title = aboutTitle;
        syncLock();
        panel.focus({ preventScroll: true });
    };

    const hide = () => {
        if (root.dataset.open !== 'true') return;
        root.dataset.open = 'false';
        root.inert = true;
        button.setAttribute('aria-expanded', 'false');
        document.title = homeTitle;
        syncLock();
        button.focus({ preventScroll: true });
    };

    const open = () => {
        reveal();
        history.pushState({ about: true, pushed: true }, '', aboutPath);
    };

    const close = () => {
        if (root.dataset.open !== 'true') return;
        if (history.state?.pushed) {
            history.back();
        } else {
            hide();
            history.replaceState(null, '', '/');
        }
    };

    button.setAttribute('aria-expanded', 'false');

    button.addEventListener('click', (event) => {
        if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
        event.preventDefault();
        if (root.dataset.open === 'true') close();
        else open();
    });

    root.addEventListener('click', (event) => {
        if (event.target.closest('[data-about-close]') || event.target.closest('[data-about-backdrop]')) close();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !lightboxOpen()) close();
    });

    window.addEventListener('popstate', () => {
        if (isAboutPath()) {
            if (root.dataset.open !== 'true') reveal();
        } else {
            hide();
        }
    });

    scroller.addEventListener(
        'scroll',
        () => {
            if (scroller.scrollTop > 24) panel.dataset.full = 'true';
            else if (scroller.scrollTop < 4) panel.dataset.full = 'false';
        },
        { passive: true },
    );

    if (isAboutPath() || window.location.hash === '#about') {
        history.replaceState({ about: true, pushed: false }, '', aboutPath);
        reveal();
    }
};

initAbout();
initPortfolio();
initLightbox();
initDetail();