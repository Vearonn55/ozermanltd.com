<?php
$loaderText = t([
    'en' => 'Preparing…',
    'tr' => 'Hazırlanıyor…',
    'ar' => 'جارٍ التحضير…',
]);
?>
<div id="oz-loader" class="oz-loader" hidden aria-hidden="true" role="status" aria-live="polite">
    <div class="oz-loader__panel">
        <div class="oz-loader__stage" aria-hidden="true">
            <svg class="oz-loader__hex" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <polygon
                    points="50,4 93,27.5 93,72.5 50,96 7,72.5 7,27.5"
                    stroke="currentColor"
                    stroke-width="2.5"
                    stroke-linejoin="round"/>
            </svg>
            <img
                class="oz-loader__mark"
                src="<?= asset('images/brand/logo-mark.png') ?>"
                alt=""
                width="72"
                height="84"
                decoding="async">
        </div>
        <p class="oz-loader__text"><?= e($loaderText) ?></p>
    </div>
</div>
