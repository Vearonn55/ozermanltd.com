<?php
use App\Data\DummyData;
$nav = DummyData::nav();
$locales = config('locales', []);
$currentLocale = app_locale();
?>
<header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-brand-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <a href="<?= url('') ?>" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-brand-900 rounded-sm flex items-center justify-center">
                    <span class="text-gold-500 font-display font-bold text-lg">O</span>
                </div>
                <div>
                    <span class="font-display font-bold text-xl text-brand-900 group-hover:text-brand-700 transition-colors">Ozerman</span>
                    <span class="block text-xs text-brand-500 tracking-widest uppercase -mt-0.5">Ltd</span>
                </div>
            </a>

            <!-- Desktop Nav -->
            <nav class="hidden lg:flex items-center gap-1">
                <?php foreach ($nav as $item): ?>
                <a href="<?= url($item['url']) ?>"
                   class="px-4 py-2 text-sm font-medium rounded-md transition-colors <?= is_active($item['url']) ? 'text-brand-900 bg-brand-50' : 'text-brand-600 hover:text-brand-900 hover:bg-brand-50' ?>">
                    <?= e(t($item['label'])) ?>
                </a>
                <?php endforeach; ?>
            </nav>

            <!-- Language Switcher + Mobile Toggle -->
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-1 bg-brand-50 rounded-md p-1">
                    <?php foreach ($locales as $code => $info): ?>
                    <a href="<?= e(locale_path_for($code)) ?>"
                       class="px-2.5 py-1 text-xs font-semibold rounded transition-colors <?= $code === $currentLocale ? 'bg-brand-900 text-white' : 'text-brand-600 hover:text-brand-900' ?>">
                        <?= strtoupper(e($code)) ?>
                    </a>
                    <?php endforeach; ?>
                </div>

                <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 rounded-md text-brand-600 hover:bg-brand-50" aria-label="Toggle menu">
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

    <!-- Mobile Menu -->
    <div x-show="mobileMenu" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="lg:hidden border-t border-brand-100 bg-white">
        <nav class="max-w-7xl mx-auto px-4 py-4 space-y-1">
            <?php foreach ($nav as $item): ?>
            <a href="<?= url($item['url']) ?>"
               @click="mobileMenu = false"
               class="block px-4 py-3 text-sm font-medium rounded-md <?= is_active($item['url']) ? 'text-brand-900 bg-brand-50' : 'text-brand-600 hover:bg-brand-50' ?>">
                <?= e(t($item['label'])) ?>
            </a>
            <?php endforeach; ?>
            <div class="flex gap-2 px-4 pt-3 border-t border-brand-100 mt-3">
                <?php foreach ($locales as $code => $info): ?>
                <a href="/<?= e($code) ?>"
                   class="px-3 py-1.5 text-xs font-semibold rounded <?= $code === $currentLocale ? 'bg-brand-900 text-white' : 'bg-brand-50 text-brand-600' ?>">
                    <?= strtoupper(e($code)) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </nav>
    </div>
</header>
<div class="h-20"></div>
