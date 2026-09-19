<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin') ?> | <?= e((string) admin_config('brand_name', 'CMS')) ?></title>
    <link rel="stylesheet" href="/assets/css/tailwind.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="stylesheet" href="/assets/vendor/quill/quill.snow.css">
    <script defer src="/assets/vendor/alpinejs/alpine.min.js"></script>
    <script src="/assets/vendor/quill/quill.js"></script>
    <script src="/assets/js/admin.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="admin-app antialiased" x-data="{ sidebarOpen: false }">
    <div class="cui-wrapper">
        <?php admin_partial('sidebar', ['user' => $user ?? null]); ?>

        <div class="cui-main">
            <header class="cui-header">
                <div class="flex items-center gap-3 min-w-0">
                    <button type="button" @click="sidebarOpen = true" class="md:hidden btn btn-secondary" aria-label="Open menu">☰</button>
                    <h1 class="truncate"><?= e($pageTitle ?? 'Admin') ?></h1>
                </div>
                <?php if (!empty($user)): ?>
                <div class="cui-header-meta flex items-center gap-4 shrink-0">
                    <span class="hidden sm:inline"><?= e($user['name']) ?> · <?= e($user['role']) ?></span>
                    <a href="<?= admin_url('logout') ?>">Sign out</a>
                </div>
                <?php endif; ?>
            </header>

            <main class="cui-body">
                <?php admin_partial('flash'); ?>
                <?php require $viewFile; ?>
            </main>
        </div>
    </div>
</body>
</html>
