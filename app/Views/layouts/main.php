<?php
/** @var \App\Services\Seo\SeoDto $seo */
$locale = app_locale();
$dir = locale_dir();
?>
<!DOCTYPE html>
<html lang="<?= e($locale) ?>" dir="<?= e($dir) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php partial('seo-head', ['seo' => $seo]); ?>

    <link rel="stylesheet" href="<?= asset('css/tailwind.css') ?>">
    <?php if (isset($slides[0]['image']) && $slides[0]['image'] !== ''): ?>
    <link rel="preload" as="image" href="<?= e($slides[0]['image']) ?>" fetchpriority="high">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"></noscript>
    <link rel="stylesheet" href="<?= asset('css/custom.css') ?>">
    <script defer src="<?= asset('vendor/alpinejs/alpine.min.js') ?>"></script>
    <script defer src="<?= asset('js/ui.js') ?>"></script>
</head>
<body class="font-sans text-brand-600 bg-brand-50 antialiased" x-data="{ mobileMenu: false, scrolled: false }"
      @scroll.window="scrolled = window.scrollY > 12">
    <?php partial('loader'); ?>
    <?php partial('header'); ?>
    <main id="site-main">
        <?php require $viewFile; ?>
    </main>
    <?php partial('footer'); ?>
    <?php partial('cookie-consent'); ?>
</body>
</html>
