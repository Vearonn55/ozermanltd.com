(function () {
    var queue = [];
    var flushTimer = null;
    var consent = null;
    var scrollMarks = {};
    var formStarted = {};

    function visitorId() {
        return window.OzermanConsent ? window.OzermanConsent.getVisitorId() : null;
    }

    function currentConsent() {
        return consent || (window.OzermanConsent ? window.OzermanConsent.getConsent() : null);
    }

    function track(name, properties) {
        if (!currentConsent() || !currentConsent().analytics) return;
        queue.push({
            name: name,
            page_path: window.location.pathname,
            page_url: window.location.href,
            referrer: document.referrer || null,
            locale: (window.OZERMAN_ANALYTICS && window.OZERMAN_ANALYTICS.locale) || 'en',
            properties: properties || {},
            timestamp: new Date().toISOString()
        });
        scheduleFlush();
    }

    function scheduleFlush() {
        if (flushTimer) return;
        flushTimer = setTimeout(flush, 1500);
    }

    function flush(useBeacon) {
        flushTimer = null;
        if (!queue.length || !currentConsent() || !currentConsent().analytics) {
            queue = [];
            return;
        }

        var payload = {
            visitor_uuid: visitorId(),
            locale: (window.OZERMAN_ANALYTICS && window.OZERMAN_ANALYTICS.locale) || 'en',
            consent: currentConsent(),
            events: queue.splice(0, queue.length)
        };

        var body = JSON.stringify(payload);
        if (useBeacon && navigator.sendBeacon) {
            navigator.sendBeacon('/api/analytics/events', new Blob([body], { type: 'application/json' }));
            return;
        }

        fetch('/api/analytics/events', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: body,
            keepalive: true
        }).catch(function () {});
    }

    function bindClicks() {
        document.addEventListener('click', function (event) {
            var target = event.target.closest('a, button, [data-track-click]');
            if (!target) return;

            var href = target.getAttribute('href') || '';
            var isOutbound = href.indexOf('http') === 0 && href.indexOf(window.location.origin) !== 0;

            track(isOutbound ? 'outbound_link' : 'click', {
                tag: target.tagName.toLowerCase(),
                text: (target.textContent || '').trim().slice(0, 120),
                href: href || null,
                id: target.id || null,
                classes: target.className || null
            });
        }, true);
    }

    function bindScroll() {
        window.addEventListener('scroll', function () {
            var doc = document.documentElement;
            var max = doc.scrollHeight - window.innerHeight;
            if (max <= 0) return;
            var percent = Math.round((window.scrollY / max) * 100);
            [25, 50, 75, 100].forEach(function (mark) {
                if (percent >= mark && !scrollMarks[mark]) {
                    scrollMarks[mark] = true;
                    track('scroll_depth', { depth: mark });
                }
            });
        }, { passive: true });
    }

    function bindForms() {
        document.querySelectorAll('form[data-track-form]').forEach(function (form) {
            var formName = form.getAttribute('data-track-form') || 'form';
            form.addEventListener('focusin', function () {
                if (formStarted[formName]) return;
                formStarted[formName] = true;
                track('form_start', { form: formName });
            }, true);
        });
    }

    window.OzermanAnalytics = {
        setConsent: function (value) {
            consent = value;
            if (value && value.analytics) {
                track('consent_updated', {
                    analytics: !!value.analytics,
                    marketing: !!value.marketing,
                    source: value.source || null
                });
                track('page_view', {
                    title: document.title,
                    path: window.location.pathname
                });
            }
        },
        track: track,
        flush: flush
    };

    document.addEventListener('DOMContentLoaded', function () {
        bindClicks();
        bindScroll();
        bindForms();

        consent = window.OzermanConsent ? window.OzermanConsent.getConsent() : null;
        if (consent && consent.analytics) {
            track('page_view', {
                title: document.title,
                path: window.location.pathname
            });
        }
    });

    window.addEventListener('beforeunload', function () {
        track('page_leave', { path: window.location.pathname });
        flush(true);
    });
})();
