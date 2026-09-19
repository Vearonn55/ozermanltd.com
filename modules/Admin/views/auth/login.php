<div class="login-card">
    <div class="text-center mb-8">
        <h1><?= e((string) admin_config('brand_name', 'CMS')) ?></h1>
        <p class="text-sm mt-1">Sign in to manage content</p>
    </div>

    <form method="POST" action="<?= admin_url('login') ?>" class="space-y-5">
        <?= csrf_field() ?>
        <div>
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" required autofocus class="form-control"
                   placeholder="<?= e((string) admin_config('default_login_hint', 'admin@example.com')) ?>">
        </div>
        <div>
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" required class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-full" style="width:100%;padding:0.65rem">Sign in</button>
    </form>
</div>
