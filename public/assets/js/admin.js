/* Admin panel behaviours: rich text, slug generation, autosave, bulk selection, copy URL. */
(function () {
    'use strict';

    /* ---------- Rich text (Quill) ---------- */
    function initRichText() {
        if (typeof window.Quill === 'undefined') return;
        document.querySelectorAll('.js-richtext').forEach(function (wrap) {
            if (wrap.dataset.quillReady) return;
            wrap.dataset.quillReady = '1';
            var source = wrap.querySelector('.js-richtext-source');
            var editorEl = wrap.querySelector('.js-richtext-editor');
            if (!source || !editorEl) return;

            var quill = new Quill(editorEl, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ header: [2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link', 'blockquote'],
                        ['clean']
                    ]
                }
            });

            if (source.value.trim() !== '') {
                quill.clipboard.dangerouslyPasteHTML(source.value);
            }

            quill.on('text-change', function () {
                source.value = editorEl.querySelector('.ql-editor').innerHTML;
                markFormDirty(source.form);
            });
        });
    }

    /* ---------- Slug auto-generation ---------- */
    function slugify(text) {
        var map = { 'ç': 'c', 'ğ': 'g', 'ı': 'i', 'i̇': 'i', 'ö': 'o', 'ş': 's', 'ü': 'u', 'Ç': 'c', 'Ğ': 'g', 'İ': 'i', 'Ö': 'o', 'Ş': 's', 'Ü': 'u' };
        return text
            .split('').map(function (ch) { return map[ch] || ch; }).join('')
            .toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/[\s-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    function initSlugs() {
        document.querySelectorAll('[data-slug-source]').forEach(function (source) {
            var form = source.form;
            if (!form || form.hasAttribute('data-editing')) return;
            var lang = source.getAttribute('data-slug-source');
            var target = form.querySelector('[data-slug-target="' + lang + '"]') ||
                (lang === 'en' ? form.querySelector('[data-slug-target="single"]') : null);
            if (!target || target.getAttribute('data-slug-locked') === '1') return;

            if (target.value.trim() !== '') target.dataset.slugTouched = '1';
            target.addEventListener('input', function () { target.dataset.slugTouched = target.value.trim() !== '' ? '1' : ''; });
            source.addEventListener('input', function () {
                if (target.dataset.slugTouched === '1') return;
                target.value = slugify(source.value);
            });
        });
    }

    function initPageEditor() {
        document.querySelectorAll('form[data-page-editor]').forEach(function (form) {
            var pathInput = form.querySelector('.js-page-path');
            var slugInput = form.querySelector('.js-page-slug');
            var preview = form.querySelector('#page-url-preview');
            if (!pathInput || !preview) return;

            var localeFree = (form.getAttribute('data-locale-free-paths') || 'qr,catalogues')
                .split(',')
                .map(function (s) { return s.trim(); })
                .filter(Boolean);

            function normalize(value) {
                return String(value || '').replace(/^\/+|\/+$/g, '');
            }

            function publicUrl(path) {
                path = normalize(path);
                if (!path || path === 'home') return '/en';
                if (localeFree.indexOf(path) !== -1) return '/' + path;
                return '/en/' + path;
            }

            function syncFromPath() {
                var path = normalize(pathInput.value);
                pathInput.value = path;
                if (slugInput && (!form.hasAttribute('data-editing') || localeFree.indexOf(path) !== -1)) {
                    if (!slugInput.dataset.slugTouched || localeFree.indexOf(path) !== -1) {
                        slugInput.value = path;
                    }
                }
                preview.textContent = publicUrl(path || (slugInput ? slugInput.value : ''));
            }

            pathInput.addEventListener('input', syncFromPath);
            pathInput.addEventListener('change', syncFromPath);
            if (slugInput) {
                slugInput.addEventListener('input', function () {
                    slugInput.dataset.slugTouched = '1';
                    if (!pathInput.value.trim()) {
                        preview.textContent = publicUrl(slugInput.value);
                    }
                });
            }
            syncFromPath();
        });
    }

    /* ---------- Autosave ---------- */
    var dirtyForms = new WeakSet();

    function markFormDirty(form) {
        if (form && form.hasAttribute('data-autosave-url')) dirtyForms.add(form);
    }

    function initAutosave() {
        var forms = document.querySelectorAll('form[data-autosave-url]');
        if (!forms.length) return;

        forms.forEach(function (form) {
            form.addEventListener('input', function () { dirtyForms.add(form); });
            form.addEventListener('change', function () { dirtyForms.add(form); });
            form.addEventListener('submit', function () { dirtyForms.delete(form); });
        });

        setInterval(function () {
            forms.forEach(function (form) {
                if (!dirtyForms.has(form)) return;
                dirtyForms.delete(form);

                var status = document.querySelector('[data-autosave-status]');
                var data = new FormData(form);
                data.set('_autosave', '1');

                fetch(form.getAttribute('data-autosave-url'), {
                    method: 'POST',
                    body: data,
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).then(function (res) {
                    if (!res.ok) throw new Error('autosave failed');
                    if (status) {
                        var now = new Date();
                        status.textContent = 'Draft autosaved at ' + now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    }
                }).catch(function () {
                    if (status) status.textContent = 'Autosave failed — check your connection.';
                    dirtyForms.add(form);
                });
            });
        }, 60000);
    }

    /* ---------- Bulk selection (used with Alpine x-data="adminBulk()") ---------- */
    window.adminBulk = function () {
        return {
            selected: [],
            toggle(id) {
                id = String(id);
                var i = this.selected.indexOf(id);
                if (i === -1) this.selected.push(id); else this.selected.splice(i, 1);
            },
            toggleAll(event) {
                var boxes = event.target.closest('table').querySelectorAll('input[type="checkbox"][value]');
                if (event.target.checked) {
                    this.selected = Array.prototype.map.call(boxes, function (b) { return String(b.value); });
                } else {
                    this.selected = [];
                }
            },
            has(id) { return this.selected.indexOf(String(id)) !== -1; }
        };
    };

    /* ---------- Copy to clipboard ---------- */
    function initCopy() {
        document.addEventListener('click', function (event) {
            var btn = event.target.closest('[data-copy], [data-copy-target]');
            if (!btn) return;
            event.preventDefault();
            var text = btn.getAttribute('data-copy');
            if (!text && btn.getAttribute('data-copy-target')) {
                var el = document.getElementById(btn.getAttribute('data-copy-target'));
                text = el ? el.textContent.trim() : '';
            }
            if (!text) return;
            navigator.clipboard.writeText(text).then(function () {
                var original = btn.textContent;
                btn.textContent = 'Copied!';
                setTimeout(function () { btn.textContent = original; }, 1500);
            });
        });
    }

    function insertAtCursor(textarea, text) {
        if (!textarea) return;
        var start = textarea.selectionStart || 0;
        var end = textarea.selectionEnd || 0;
        var value = textarea.value;
        textarea.value = value.slice(0, start) + text + value.slice(end);
        textarea.selectionStart = textarea.selectionEnd = start + text.length;
        textarea.dispatchEvent(new Event('input', { bubbles: true }));
        textarea.focus();
    }

    function snippetFor(lang, url, name) {
        var safe = url.replace(/'/g, "\\'");
        if (lang === 'css') return "url('" + safe + "')";
        if (lang === 'js') return "'" + safe + "'";
        if (lang === 'php') return "echo '" + safe + "';";
        var alt = (name || '').replace(/"/g, '&quot;');
        return '<img src="' + url + '" alt="' + alt + '">';
    }

    function initCodeMediaInsert() {
        var pending = null;
        document.addEventListener('click', function (event) {
            var btn = event.target.closest('.js-insert-media');
            if (!btn) return;
            event.preventDefault();
            pending = {
                target: btn.getAttribute('data-target'),
                lang: btn.getAttribute('data-lang') || 'html'
            };
            var opener = document.querySelector('[x-data] button[type="button"]');
            window.dispatchEvent(new CustomEvent('admin-open-embed-picker'));
        });

        window.addEventListener('admin-media-picked-embed-insert', function (event) {
            if (!pending) return;
            var textarea = document.querySelector('[name="' + pending.target + '"]');
            insertAtCursor(textarea, snippetFor(pending.lang, event.detail.url, event.detail.name));
            pending = null;
        });
    }

    window.pageEditor = function (initial) {
        // Kept for older cached markup; page form no longer depends on Alpine for URL fields.
        return {
            slug: initial.slug || '',
            path: initial.path || '',
            prefix: initial.prefix || '/en',
            localeFreePaths: initial.localeFreePaths || ['qr', 'catalogues'],
            get fullUrl() {
                var path = (this.path || this.slug || '').replace(/^\/+|\/+$/g, '');
                if (path === 'home' || path === '') return this.prefix || '/en';
                if (this.localeFreePaths.indexOf(path) !== -1) return '/' + path;
                var prefix = this.prefix || '';
                if (!prefix) return '/' + path;
                return prefix + '/' + path;
            }
        };
    };

    document.addEventListener('DOMContentLoaded', function () {
        initRichText();
        initSlugs();
        initPageEditor();
        initAutosave();
        initCopy();
        initCodeMediaInsert();
        initTabIndent();
    });

    function initTabIndent() {
        document.querySelectorAll('.js-code-editor').forEach(function (el) {
            el.addEventListener('keydown', function (event) {
                if (event.key !== 'Tab') return;
                event.preventDefault();
                insertAtCursor(el, '  ');
            });
        });
    }
})();
