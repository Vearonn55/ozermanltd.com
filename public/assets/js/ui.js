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
  const nav = document.querySelector('[data-nav-indicator]');
  const indicator = nav ? nav.querySelector('.nav-indicator') : null;
  if (nav && indicator) {
    const activeLink = () => nav.querySelector('a.is-active');
    const place = (link, animate) => {
      if (!link || nav.getClientRects().length === 0) {
        indicator.style.opacity = '0';
        return;
      }
      const navBox = nav.getBoundingClientRect();
      const box = link.getBoundingClientRect();
      if (!animate) {
        indicator.style.transition = 'none';
      }
      indicator.style.opacity = '1';
      indicator.style.width = box.width + 'px';
      indicator.style.transform = 'translateX(' + (box.left - navBox.left) + 'px)';
      if (!animate) {
        void indicator.offsetWidth;
        indicator.style.transition = '';
      }
    };

    const pathOf = (link) => {
      try {
        return new URL(link.href, window.location.href).pathname;
      } catch (e) {
        return '';
      }
    };
    let fromPath = '';
    try {
      fromPath = sessionStorage.getItem('oz-nav-from') || '';
      sessionStorage.removeItem('oz-nav-from');
    } catch (e) {}
    const current = activeLink();
    const fromLink = fromPath
      ? [...nav.querySelectorAll('a')].find((link) => pathOf(link) === fromPath)
      : null;

    if (fromLink && current && fromLink !== current) {
      place(fromLink, false);
      indicator.classList.add('is-ready');
      requestAnimationFrame(() => {
        requestAnimationFrame(() => place(current, true));
      });
    } else {
      place(current, false);
      indicator.classList.add('is-ready');
    }

    nav.addEventListener('click', (event) => {
      const link = event.target.closest('a');
      if (!link || !nav.contains(link)) {
        return;
      }
      try {
        sessionStorage.setItem('oz-nav-from', window.location.pathname);
      } catch (e) {}
    });
    window.addEventListener('resize', () => place(activeLink(), false));
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
