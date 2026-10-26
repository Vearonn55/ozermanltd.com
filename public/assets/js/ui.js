(() => {
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const root = document.documentElement;
  const loader = document.getElementById('oz-loader');

  const showLoader = () => {
    if (!loader) {
      return;
    }
    root.classList.add('oz-loading');
    loader.hidden = false;
    loader.setAttribute('aria-hidden', 'false');
    void loader.offsetWidth;
    loader.classList.add('is-active');
  };

  const hideLoader = () => {
    if (!loader) {
      return;
    }
    loader.classList.remove('is-active');
    loader.setAttribute('aria-hidden', 'true');
    root.classList.remove('oz-loading');
    window.setTimeout(() => {
      if (!loader.classList.contains('is-active')) {
        loader.hidden = true;
      }
    }, 180);
  };

  // Drop a leftover flag from the old full-page loader so it cannot hold the next view.
  try {
    sessionStorage.removeItem('oz-nav-loading');
  } catch (e) {}
  root.classList.remove('oz-loading');
  if (loader) {
    loader.classList.remove('is-active');
    loader.hidden = true;
  }

  window.OzermanLoader = { show: showLoader, hide: hideLoader };

  // --- Menu underline (accelerates, then decelerates) ---
  // The bar used to receive its transition in the same frame it was placed.
  // The browser then interpolated from width 0, and a webfont swap or resize
  // mid-flight left the used transform parked between two links.
  const nav = document.querySelector('[data-nav-indicator]');
  const indicator = nav ? nav.querySelector('.nav-indicator') : null;
  if (nav && indicator) {
    const motionMs = 420;
    const activeLink = () => nav.querySelector('a.is-active');
    const px = (n) => Math.round(n * 100) / 100;
    const normPath = (path) => (path.length > 1 ? path.replace(/\/+$/, '') : path);
    const pathOf = (link) => {
      try {
        return normPath(new URL(link.href, window.location.href).pathname);
      } catch (e) {
        return '';
      }
    };
    const metrics = (link) => {
      const navBox = nav.getBoundingClientRect();
      const box = link.getBoundingClientRect();
      return { x: px(box.left - navBox.left), w: px(box.width) };
    };
    const write = (box) => {
      indicator.style.opacity = '1';
      indicator.style.width = box.w + 'px';
      indicator.style.transform = 'translateX(' + box.x + 'px)';
    };
    const enableTransition = () => {
      indicator.style.transition = reduceMotion
        ? 'none'
        : 'transform ' + motionMs + 'ms cubic-bezier(0.45, 0, 0.55, 1), width ' + motionMs + 'ms cubic-bezier(0.45, 0, 0.55, 1)';
    };
    const hide = () => {
      indicator.style.transition = 'none';
      indicator.style.opacity = '0';
      indicator.classList.remove('is-ready');
    };
    const snap = (link) => {
      if (!link || nav.getClientRects().length === 0) {
        hide();
        return null;
      }
      const box = metrics(link);
      indicator.style.transition = 'none';
      indicator.classList.add('is-ready');
      write(box);
      void indicator.offsetWidth;
      return box;
    };
    const restingOffTarget = () => {
      const link = activeLink();
      if (!link || nav.getClientRects().length === 0) {
        return false;
      }
      const box = metrics(link);
      const painted = indicator.getBoundingClientRect();
      const navBox = nav.getBoundingClientRect();
      return Math.abs(px(painted.left - navBox.left) - box.x) > 1
        || Math.abs(px(painted.width) - box.w) > 1;
    };

    let phase = 'init';
    let moveToken = 0;

    const settle = (token) => {
      if (token !== moveToken) {
        return;
      }
      moveToken += 1;
      phase = 'idle';
      snap(activeLink());
      requestAnimationFrame(enableTransition);
    };

    const slideToActive = () => {
      const link = activeLink();
      if (!link) {
        phase = 'idle';
        snap(null);
        requestAnimationFrame(enableTransition);
        return;
      }
      const token = moveToken;
      phase = 'moving';
      enableTransition();
      write(metrics(link));
      const timer = window.setTimeout(() => settle(token), motionMs + 80);
      const onEnd = (event) => {
        if (event.target !== indicator || event.propertyName !== 'transform') {
          return;
        }
        indicator.removeEventListener('transitionend', onEnd);
        window.clearTimeout(timer);
        settle(token);
      };
      indicator.addEventListener('transitionend', onEnd);
    };

    let fromPath = '';
    try {
      fromPath = normPath(sessionStorage.getItem('oz-nav-from') || '');
      sessionStorage.removeItem('oz-nav-from');
    } catch (e) {}
    const current = activeLink();
    const fromLink = fromPath
      ? [...nav.querySelectorAll('a')].find((link) => pathOf(link) === fromPath)
      : null;
    const animate = !reduceMotion && fromLink && current && fromLink !== current;

    snap(animate ? fromLink : current);
    requestAnimationFrame(() => {
      // Fonts may have reflowed the labels since the first measurement.
      snap(animate ? fromLink : current);
      requestAnimationFrame(() => {
        if (!animate) {
          phase = 'idle';
          enableTransition();
          return;
        }
        moveToken += 1;
        slideToActive();
      });
    });

    const realign = () => {
      if (phase === 'init' || nav.getClientRects().length === 0) {
        return;
      }
      const link = activeLink();
      if (!link) {
        hide();
        return;
      }
      if (phase === 'moving') {
        write(metrics(link));
        return;
      }
      if (!restingOffTarget()) {
        return;
      }
      snap(link);
      requestAnimationFrame(enableTransition);
    };

    nav.addEventListener('click', (event) => {
      const link = event.target.closest('a');
      if (!link || !nav.contains(link)) {
        return;
      }
      try {
        sessionStorage.setItem('oz-nav-from', normPath(window.location.pathname));
      } catch (e) {}
    });
    window.addEventListener('resize', realign);
    window.addEventListener('pageshow', (event) => {
      if (!event.persisted) {
        return;
      }
      moveToken += 1;
      phase = 'idle';
      snap(activeLink());
      requestAnimationFrame(enableTransition);
    });
    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(realign);
    }
    if ('ResizeObserver' in window) {
      const observer = new ResizeObserver(realign);
      observer.observe(nav);
    }
  }

  // --- Lazy images ---
  document.querySelectorAll('img[loading="lazy"]').forEach((img) => {
    if (img.complete) {
      img.classList.add('loaded');
      return;
    }
    img.addEventListener('load', () => img.classList.add('loaded'), { once: true });
  });

  // --- Scroll reveals ---
  const reveals = document.querySelectorAll('.reveal');
  if (reduceMotion) {
    reveals.forEach((el) => el.classList.add('is-visible'));
  } else if (!('IntersectionObserver' in window) || reveals.length === 0) {
    reveals.forEach((el) => el.classList.add('is-visible'));
  } else {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      },
      { rootMargin: '0px 0px -6% 0px', threshold: 0.1 }
    );
    reveals.forEach((el) => observer.observe(el));
  }

})();
