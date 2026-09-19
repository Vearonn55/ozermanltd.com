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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/custom.css') ?>">
    <script>
        /* Show branded loader before first paint when navigating between pages */
        (function () {
            try {
                if (sessionStorage.getItem('oz-nav-loading') === '1') {
                    document.documentElement.classList.add('oz-loading');
                }
            } catch (e) {}
        })();
    </script>
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
