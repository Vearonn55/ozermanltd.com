(function () {
    var banner = document.getElementById('cookie-consent');
    if (!banner) return;

    var storageKey = 'ozerman_consent';
    var visitorKey = 'ozerman_visitor_id';
    var visitorCookie = 'ozerman_visitor_id';
    var policyVersion = banner.dataset.policyVersion || (window.OZERMAN_ANALYTICS && window.OZERMAN_ANALYTICS.policyVersion) || '1.0';

    function uuid() {
        if (window.crypto && crypto.randomUUID) return crypto.randomUUID();
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = Math.random() * 16 | 0;
            var v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    function getVisitorId() {
        var id = localStorage.getItem(visitorKey);
        if (!id) {
            id = uuid();
            localStorage.setItem(visitorKey, id);
        }
        document.cookie = visitorCookie + '=' + encodeURIComponent(id) + '; path=/; max-age=31536000; SameSite=Lax';
        var field = document.getElementById('visitor_uuid');
        if (field) field.value = id;
        return id;
    }

    function readConsent() {
        try {
            var stored = localStorage.getItem(storageKey);
            if (stored) return JSON.parse(stored);
        } catch (e) {}
        return null;
    }

    function writeConsent(consent) {
        localStorage.setItem(storageKey, JSON.stringify(consent));
        document.cookie = storageKey + '=' + encodeURIComponent(JSON.stringify(consent)) + '; path=/; max-age=31536000; SameSite=Lax';
    }

    function postConsent(consent) {
        return fetch('/api/analytics/consent', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                visitor_uuid: getVisitorId(),
                policy_version: policyVersion,
                essential: !!consent.essential,
                analytics: !!consent.analytics,
                marketing: !!consent.marketing,
                source: consent.source || 'banner',
                locale: (window.OZERMAN_ANALYTICS && window.OZERMAN_ANALYTICS.locale) || 'en'
            })
        }).catch(function () {});
    }

    function loadPlausible() {
        if (!window.OZERMAN_ANALYTICS || !window.OZERMAN_ANALYTICS.plausibleDomain) return;
        if (document.getElementById('plausible-script')) return;
        var script = document.createElement('script');
        script.id = 'plausible-script';
        script.defer = true;
        script.setAttribute('data-domain', window.OZERMAN_ANALYTICS.plausibleDomain);
        script.src = 'https://plausible.io/js/script.js';
        document.head.appendChild(script);
    }

    function hideBanner() {
        banner.classList.add('hidden');
    }

    function showBanner() {
        banner.classList.remove('hidden');
    }

    function applyConsent(consent) {
        writeConsent(consent);
        postConsent(consent);
        if (window.OzermanAnalytics) {
            window.OzermanAnalytics.setConsent(consent);
        }
        if (consent.analytics) loadPlausible();
        hideBanner();
        syncToggles(consent);
    }

    function syncToggles(consent) {
        var analyticsToggle = document.getElementById('consent-analytics');
        var marketingToggle = document.getElementById('consent-marketing');
        if (analyticsToggle) analyticsToggle.checked = !!consent.analytics;
        if (marketingToggle) marketingToggle.checked = !!consent.marketing;
    }

    function buildConsent(analytics, marketing, source) {
        return {
            essential: true,
            analytics: !!analytics,
            marketing: !!marketing,
            policy_version: policyVersion,
            source: source || 'banner',
            decidedAt: new Date().toISOString()
        };
    }

    function readPanelChoices() {
        return buildConsent(
            document.getElementById('consent-analytics') && document.getElementById('consent-analytics').checked,
            document.getElementById('consent-marketing') && document.getElementById('consent-marketing').checked,
            'banner'
        );
    }

    window.OzermanConsent = {
        getConsent: readConsent,
        getVisitorId: getVisitorId,
        savePreferences: function (choices) {
            var consent = buildConsent(choices.analytics, choices.marketing, choices.source || 'settings_page');
            applyConsent(consent);
        },
        openBanner: showBanner
    };

    banner.addEventListener('click', function (event) {
        var button = event.target.closest('[data-consent-action]');
        if (!button) return;

        var action = button.getAttribute('data-consent-action');
        var panel = document.getElementById('cookie-consent-panel');
        var saveBtn = banner.querySelector('[data-consent-action="save"]');

        if (action === 'manage') {
            panel.classList.remove('hidden');
            saveBtn.classList.remove('hidden');
            if (window.OzermanAnalytics) window.OzermanAnalytics.track('consent_opened', {});
            return;
        }

        if (action === 'save') {
            applyConsent(readPanelChoices());
            return;
        }

        if (action === 'accept-all') {
            applyConsent(buildConsent(true, true, 'banner_accept_all'));
            return;
        }

        if (action === 'reject') {
            applyConsent(buildConsent(false, false, 'banner_reject'));
        }
    });

    getVisitorId();
    var existing = readConsent();
    if (existing) {
        applyConsent(existing);
        return;
    }

    showBanner();
})();
