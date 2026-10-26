<?php
/**
 * Same chip row as before: EN and TR stay visible, + opens the other locales.
 *
 * @var array<string, array{name: string, dir: string}> $primary
 * @var array<string, array{name: string, dir: string}> $extra
 * @var string $currentLocale
 * @var bool $extraActive
 * @var bool $overlay  Drop the menu below the fixed bar. The bar's blur would clip it otherwise.
 */
$overlay = $overlay ?? false;
$chip = 'px-2.5 py-1 text-xs font-semibold rounded-sm transition-colors duration-200';
$chipOn = 'bg-brand-800 text-brand-50';
$chipOff = 'text-brand-500 hover:text-brand-800';
?>
<div class="relative"
     x-data="{ open: false, close() { this.open = false }, toggle() { this.open = !this.open } }"
     @click.outside="close()"
     @keydown.escape.window="open && close()">
    <div class="flex items-center gap-1 bg-brand-100 rounded-sm p-1">
        <?php foreach ($primary as $code => $info): ?>
        <a href="<?= e(locale_path_for($code)) ?>"
           hreflang="<?= e($code) ?>"
           lang="<?= e($code) ?>"
           class="<?= $chip ?> <?= $code === $currentLocale ? $chipOn : $chipOff ?>"
           <?= $code === $currentLocale ? 'aria-current="true"' : '' ?>><?= strtoupper(e($code)) ?></a>
        <?php endforeach; ?>
        <?php if ($extra !== []): ?>
        <button type="button"
                class="<?= $chip ?> <?= $extraActive ? $chipOn : $chipOff ?>"
                @click.stop="toggle()"
                :aria-expanded="open ? 'true' : 'false'"
                aria-haspopup="menu"
                aria-label="<?= e($extraActive ? (string) ($extra[$currentLocale]['name'] ?? 'More languages') : 'More languages') ?>">+</button>
        <?php endif; ?>
    </div>
    <?php if ($extra !== []): ?>
    <div x-show="open"
         x-cloak
         class="<?= $overlay ? 'absolute end-0 z-50 min-w-full top-[calc(100%+2.25rem)]' : 'mt-1' ?>"
         role="menu">
        <div class="flex flex-col gap-1 bg-brand-100 rounded-sm p-1 shadow-sm">
            <?php foreach ($extra as $code => $info): ?>
            <a href="<?= e(locale_path_for($code)) ?>"
               role="menuitem"
               hreflang="<?= e($code) ?>"
               lang="<?= e($code) ?>"
               dir="<?= e($info['dir'] ?? 'ltr') ?>"
               class="<?= $chip ?> text-center whitespace-nowrap <?= $code === $currentLocale ? $chipOn : $chipOff ?>"
               aria-label="<?= e($info['name']) ?>"><?= strtoupper(e($code)) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
