<div class="login-card" style="max-width:560px">
    <h1>Something went wrong</h1>
    <p class="text-sm mt-2" style="color:var(--cui-danger,#f44747)"><?= e($message ?? 'Unexpected error') ?></p>
    <p class="text-sm mt-4">
        If this mentions a missing table, import in phpMyAdmin (select <code>ozermanl_MAIN</code>):
        <code>database/schema.sql</code>, then <code>database/seed.sql</code>, then <code>database/analytics_schema.sql</code>.
    </p>
    <p class="mt-6">
        <a href="<?= admin_url('login') ?>" class="btn btn-primary">Back to sign in</a>
    </p>
</div>
