<?php
$nav = content()->nav(app_locale());
$locales = config('locales', []);
$currentLocale = app_locale();
?>
<header class="site-header fixed top-0 left-0 right-0 z-50 transition-all duration-300"
        :class="scrolled ? 'bg-brand-50/95 backdrop-blur-md border-b border-brand-200 shadow-sm' : 'bg-brand-50/90 backdrop-blur-sm border-b border-transparent'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

            <nav class="hidden lg:flex items-center gap-0.5">
                <?php foreach ($nav as $item): ?>
                <a href="<?= url($item['url']) ?>"
                   class="px-3.5 py-2 text-sm font-medium rounded-sm transition-colors duration-200 <?= is_active($item['url']) ? 'text-brand-800 border-b-2 border-orange-500' : 'text-brand-500 hover:text-brand-800' ?>">
                    <?= e(t($item['label'])) ?>
                </a>
                <?php endforeach; ?>
            </nav>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-1 bg-brand-100 rounded-sm p-1">
                    <?php foreach ($locales as $code => $info): ?>
                    <a href="<?= e(locale_path_for($code)) ?>"
                       class="px-2.5 py-1 text-xs font-semibold rounded-sm transition-colors duration-200 <?= $code === $currentLocale ? 'bg-brand-800 text-brand-50' : 'text-brand-500 hover:text-brand-800' ?>">
                        <?= strtoupper(e($code)) ?>
                    </a>
                    <?php endforeach; ?>
                </div>

                <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 rounded-sm text-brand-500 hover:bg-brand-100 transition-colors" aria-label="Toggle menu">
                    <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileMenu" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden border-t border-brand-200 bg-brand-50">
        <nav class="max-w-7xl mx-auto px-4 py-4 space-y-1">
            <?php foreach ($nav as $item): ?>
            <a href="<?= url($item['url']) ?>"
               @click="mobileMenu = false"
               class="block px-4 py-3 text-sm font-medium rounded-sm transition-colors <?= is_active($item['url']) ? 'text-brand-800 bg-brand-100 border-l-2 border-orange-500' : 'text-brand-500 hover:bg-brand-100' ?>">
                <?= e(t($item['label'])) ?>
            </a>
            <?php endforeach; ?>
            <div class="flex gap-2 px-4 pt-3 border-t border-brand-200 mt-3">
                <?php foreach ($locales as $code => $info): ?>
                <a href="/<?= e($code) ?>"
                   class="px-3 py-1.5 text-xs font-semibold rounded-sm <?= $code === $currentLocale ? 'bg-brand-800 text-brand-50' : 'bg-brand-100 text-brand-500' ?>">
                    <?= strtoupper(e($code)) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </nav>
    </div>
</header>
<div class="h-20"></div>
