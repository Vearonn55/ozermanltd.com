<?php
/** @var array<string, mixed> $page */
$title = (string) ($page['title'] ?? 'Page');
$html = (string) ($page['html_embed'] ?? '');
$css = (string) ($page['css_embed'] ?? '');
$js = (string) ($page['js_embed'] ?? '');
$content = (string) ($page['content'] ?? '');
$phpOutput = (string) ($page['php_output'] ?? '');
?>
<article class="oz-cms-page max-w-6xl mx-auto px-4 py-12">
    <?php if ($html === '' && $phpOutput === '' && $content === ''): ?>
    <h1 class="text-3xl font-semibold mb-4"><?= e($title) ?></h1>
    <?php endif; ?>

    <?php if ($css !== ''): ?>
    <style><?= $css ?></style>
    <?php endif; ?>

    <?php if ($phpOutput !== ''): ?>
    <?= $phpOutput ?>
    <?php endif; ?>

    <?php if ($html !== ''): ?>
    <?= $html ?>
    <?php endif; ?>

    <?php if ($content !== '' && $html === ''): ?>
    <div class="prose max-w-none"><?= $content ?></div>
    <?php endif; ?>

    <?php if ($js !== ''): ?>
    <script><?= $js ?></script>
    <?php endif; ?>
</article>
