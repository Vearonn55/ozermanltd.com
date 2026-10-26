<?php
/**
 * Pinned locales read EN | TR, then + opens the rest.
 *
 * @var array<string, array{name: string, dir: string}> $primary
 * @var array<string, array{name: string, dir: string}> $extra
 * @var string $currentLocale
 * @var bool $extraActive
 * @var bool $openUp
 */
$openUp = $openUp ?? false;
?>
<div class="locale-switch<?= $openUp ? ' locale-switch--up' : '' ?>"
     x-data="{ open: false }"
     @click.outside="open = false"
     @keydown.escape.window="open = false">
    <span class="locale-switch__sep" aria-hidden="true">|</span>
    <?php foreach ($primary as $code => $info): ?>
    <a href="<?= e(locale_path_for($code)) ?>"
       class="locale-switch__link<?= $code === $currentLocale ? ' is-active' : '' ?>"
       hreflang="<?= e($code) ?>"
       lang="<?= e($code) ?>"
       <?= $code === $currentLocale ? 'aria-current="true"' : '' ?>><?= strtoupper(e($code)) ?></a>
    <span class="locale-switch__sep" aria-hidden="true">|</span>
    <?php endforeach; ?>
    <?php if ($extra !== []): ?>
    <span class="locale-switch__more">
        <button type="button"
                class="locale-switch__link locale-switch__plus<?= $extraActive ? ' is-active' : '' ?>"
                @click="open = !open"
                :aria-expanded="open ? 'true' : 'false'"
                aria-haspopup="menu"
                aria-label="<?= e($extraActive ? (string) ($extra[$currentLocale]['name'] ?? '+') : 'More languages') ?>">+</button>
        <div class="locale-switch__menu" x-show="open" x-cloak role="menu">
            <?php foreach ($extra as $code => $info): ?>
            <a href="<?= e(locale_path_for($code)) ?>"
               role="menuitem"
               hreflang="<?= e($code) ?>"
               lang="<?= e($code) ?>"
               dir="<?= e($info['dir'] ?? 'ltr') ?>"
               class="locale-switch__option<?= $code === $currentLocale ? ' is-active' : '' ?>"
               @click="open = false"><?= e($info['name']) ?></a>
            <?php endforeach; ?>
        </div>
    </span>
    <span class="locale-switch__sep" aria-hidden="true">|</span>
    <?php endif; ?>
</div>
