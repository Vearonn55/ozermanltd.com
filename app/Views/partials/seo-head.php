<?php

/** @var \App\Services\Seo\SeoDto $seo */
?>
    <title><?= e($seo->title) ?></title>
    <meta name="description" content="<?= e($seo->description) ?>">
    <meta name="robots" content="<?= e($seo->robots) ?>">
    <link rel="canonical" href="<?= e($seo->canonicalUrl) ?>">

    <?php foreach ($seo->hreflang as $alternate): ?>
    <link rel="alternate" hreflang="<?= e($alternate['locale']) ?>" href="<?= e($alternate['url']) ?>">
    <?php endforeach; ?>

    <meta property="og:title" content="<?= e($seo->ogTitle) ?>">
    <meta property="og:description" content="<?= e($seo->ogDescription) ?>">
    <meta property="og:type" content="<?= e($seo->ogType) ?>">
    <meta property="og:url" content="<?= e($seo->ogUrl) ?>">
    <meta property="og:site_name" content="<?= e(config('name')) ?>">
    <?php if ($seo->ogImage): ?>
    <meta property="og:image" content="<?= e($seo->ogImage) ?>">
    <?php endif; ?>

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($seo->ogTitle) ?>">
    <meta name="twitter:description" content="<?= e($seo->ogDescription) ?>">
    <?php if ($seo->ogImage): ?>
    <meta name="twitter:image" content="<?= e($seo->ogImage) ?>">
    <?php endif; ?>

    <script type="application/ld+json"><?= json_encode($seo->structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
