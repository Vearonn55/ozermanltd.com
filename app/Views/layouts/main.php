<?php
/** @var string $title */
/** @var string $meta_description */
$locale = app_locale();
$dir = locale_dir();
?>
<!DOCTYPE html>
<html lang="<?= e($locale) ?>" dir="<?= e($dir) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? config('name')) ?></title>
    <meta name="description" content="<?= e($meta_description ?? config('tagline')) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= e(config('url') . url(relative_path())) ?>">

    <meta property="og:title" content="<?= e($title ?? config('name')) ?>">
    <meta property="og:description" content="<?= e($meta_description ?? config('tagline')) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e(config('url')) ?>">
    <meta property="og:site_name" content="<?= e(config('name')) ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f4f8',
                            100: '#d9e2ec',
                            200: '#bcccdc',
                            300: '#9fb3c8',
                            400: '#829ab1',
                            500: '#627d98',
                            600: '#486581',
                            700: '#334e68',
                            800: '#243b53',
                            900: '#102a43',
                            950: '#0a1929',
                        },
                        gold: {
                            400: '#d4af37',
                            500: '#c9a227',
                            600: '#b8941f',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        display: ['Playfair Display', 'Georgia', 'serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/custom.css') ?>">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Ozerman Ltd",
        "url": "https://ozermanltd.com",
        "logo": "https://ozermanltd.com/assets/images/logo.svg",
        "description": "A diversified international business group operating across trading, construction, real estate, manufacturing, logistics, and energy.",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "25 Canary Wharf",
            "addressLocality": "London",
            "postalCode": "E14 5AB",
            "addressCountry": "GB"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+44-20-7946-0958",
            "contactType": "customer service",
            "email": "info@ozermanltd.com"
        }
    }
    </script>
</head>
<body class="font-sans text-brand-800 bg-white antialiased" x-data="{ mobileMenu: false }">
    <?php partial('header'); ?>
    <main>
        <?php require $viewFile; ?>
    </main>
    <?php partial('footer'); ?>
</body>
</html>
