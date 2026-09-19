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

  // Arriving from an in-site navigation: loader already covering via html.oz-loading
  if (sessionStorage.getItem('oz-nav-loading') === '1') {
    sessionStorage.removeItem('oz-nav-loading');
    showLoader();
    const reveal = () => {
      window.setTimeout(hideLoader, reduceMotion ? 0 : 180);
    };
    if (document.readyState === 'complete') {
      reveal();
    } else {
      window.addEventListener('load', reveal, { once: true });
    }
  }

  window.OzermanLoader = { show: showLoader, hide: hideLoader };

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

  if (reduceMotion) {
    return;
  }

  const isInternalNavLink = (anchor) => {
    if (!anchor || anchor.target === '_blank' || anchor.hasAttribute('download')) {
      return false;
    }
    const href = anchor.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) {
      return false;
    }
    let url;
    try {
      url = new URL(anchor.href, window.location.href);
    } catch {
      return false;
    }
    if (url.origin !== window.location.origin) {
      return false;
    }
    if (url.pathname === window.location.pathname && url.search === window.location.search) {
      return false;
    }
    return true;
  };

  document.addEventListener('click', (event) => {
    if (event.defaultPrevented || event.button !== 0) {
      return;
    }
    if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
      return;
    }

    const anchor = event.target.closest('a[href]');
    if (!isInternalNavLink(anchor)) {
      return;
    }

    event.preventDefault();
    sessionStorage.setItem('oz-nav-loading', '1');
    showLoader();
    window.setTimeout(() => {
      window.location.assign(anchor.href);
    }, 100);
  });
})();
