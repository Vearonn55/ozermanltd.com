<div class="login-card" style="max-width:560px">
    <h1>Something went wrong</h1>
    <p class="text-sm mt-4">The dashboard could not finish loading. Try again in a moment. If it keeps happening, contact the person who set up the site.</p>
    <?php if ((getenv('APP_ENV') ?: 'development') !== 'production' && !empty($message)): ?>
    <p class="text-sm mt-2" style="color:var(--cui-danger,#f44747)"><?= e($message) ?></p>
    <?php endif; ?>
    <p class="mt-6">
        <a href="<?= admin_url('login') ?>" class="btn btn-primary">Back to sign in</a>
    </p>
</div>
