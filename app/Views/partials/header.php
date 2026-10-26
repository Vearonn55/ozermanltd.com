<?php
$nav = content()->nav(app_locale());
$locales = config('locales', []);
$currentLocale = app_locale();
$primary = [];
foreach (['en', 'tr'] as $code) {
    if (isset($locales[$code])) {
        $primary[$code] = $locales[$code];
    }
}
foreach ($locales as $code => $info) {
    if (count($primary) >= 2) {
        break;
    }
    if (!isset($primary[$code])) {
        $primary[$code] = $info;
    }
}
$extra = array_diff_key($locales, $primary);
$extraActive = isset($extra[$currentLocale]);
$localeSwitch = [
    'primary' => $primary,
    'extra' => $extra,
    'currentLocale' => $currentLocale,
    'extraActive' => $extraActive,
];
?>
<header class="site-header fixed top-0 left-0 right-0 z-50">
    <div class="pointer-events-none absolute inset-0 -z-10 transition-all duration-300"
         :class="scrolled ? 'bg-brand-50/95 backdrop-blur-md border-b border-brand-200 shadow-sm' : 'bg-brand-50/90 backdrop-blur-sm border-b border-transparent'"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <a href="<?= url('') ?>" class="flex items-center shrink-0 group" aria-label="Özerman Ticaret">
                <img src="<?= asset('images/brand/logo-horizontal-light.png') ?>"
                     alt="Özerman Ticaret"
                     width="180"
                     height="44"
                     decoding="async"
                     fetchpriority="high"
                     class="h-10 sm:h-11 w-auto object-contain transition-opacity group-hover:opacity-90">
            </a>

            <nav class="nav-main hidden lg:flex items-center gap-0.5" data-nav-indicator>
                <span class="nav-indicator" aria-hidden="true"></span>
                <?php foreach ($nav as $item): ?>
                <a href="<?= url($item['url']) ?>"
                   class="nav-link px-3.5 py-2 text-sm font-medium rounded-sm <?= is_active($item['url']) ? 'is-active text-brand-800' : 'text-brand-500 hover:text-brand-800' ?>">
                    <?= e(t($item['label'])) ?>
                </a>
                <?php endforeach; ?>
            </nav>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center">
                    <?php partial('locale-switch', $localeSwitch + ['overlay' => true]); ?>
                </div>

                <button type="button" @click.stop="mobileMenu = !mobileMenu" class="lg:hidden relative z-20 p-2 rounded-sm text-brand-500 hover:bg-brand-100" :aria-expanded="mobileMenu ? 'true' : 'false'" aria-controls="mobile-menu" aria-label="Toggle menu">
                    <span x-show="!mobileMenu" class="block">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </span>
                    <span x-show="mobileMenu" x-cloak class="block">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    </div>
</header>
<div id="mobile-menu"
     x-show="mobileMenu"
     x-cloak
     @keydown.escape.window="mobileMenu = false"
     class="lg:hidden fixed inset-x-0 top-20 z-[60] max-h-[calc(100dvh-5rem)] overflow-y-auto border-t border-brand-200 bg-brand-50 shadow-lg">
    <nav class="max-w-7xl mx-auto px-4 py-4 space-y-1">
        <?php foreach ($nav as $item): ?>
        <a href="<?= url($item['url']) ?>"
           @click="mobileMenu = false"
           class="block px-4 py-3 text-sm font-medium rounded-sm transition-colors <?= is_active($item['url']) ? 'text-brand-800 bg-brand-100 border-l-2 border-orange-500' : 'text-brand-500 hover:bg-brand-100' ?>">
            <?= e(t($item['label'])) ?>
        </a>
        <?php endforeach; ?>
        <div class="px-4 pt-3 border-t border-brand-200 mt-3">
            <?php partial('locale-switch', $localeSwitch); ?>
        </div>
    </nav>
</div>
<div class="h-20"></div>
