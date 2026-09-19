<?php
/** @var array<string, mixed> $page */
/** @var \App\Services\Seo\SeoDto $seo */
$title = (string) ($page['title'] ?? 'Page');
$html = (string) ($page['html_embed'] ?? '');
$css = (string) ($page['css_embed'] ?? '');
$js = (string) ($page['js_embed'] ?? '');
$content = (string) ($page['content'] ?? '');
$phpOutput = (string) ($page['php_output'] ?? '');
$locale = app_locale();
?>
<!DOCTYPE html>
<html lang="<?= e($locale) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($seo->title ?? $title) ?></title>
    <?php if (!empty($seo->description)): ?>
    <meta name="description" content="<?= e($seo->description) ?>">
    <?php endif; ?>
    <?php if ($css !== ''): ?>
    <style><?= $css ?></style>
    <?php endif; ?>
</head>
<body>
    <?= $phpOutput ?>
    <?= $html ?>
    <?php if ($content !== '' && $html === ''): ?>
    <?= $content ?>
    <?php endif; ?>
    <?php if ($js !== ''): ?>
    <script><?= $js ?></script>
    <?php endif; ?>
</body>
</html>
