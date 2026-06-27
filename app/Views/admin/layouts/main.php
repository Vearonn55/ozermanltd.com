<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin') ?> | Ozerman CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <div class="flex min-h-screen">
        <?php admin_partial('sidebar', ['user' => $user ?? null]); ?>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold text-slate-900"><?= e($pageTitle ?? 'Admin') ?></h1>
                <?php if (!empty($user)): ?>
                <div class="flex items-center gap-4 text-sm">
                    <span class="text-slate-500"><?= e($user['name']) ?> <span class="text-slate-400">(<?= e($user['role']) ?>)</span></span>
                    <a href="/admin/logout" class="text-red-600 hover:text-red-700 font-medium">Sign out</a>
                </div>
                <?php endif; ?>
            </header>

            <main class="flex-1 p-6 overflow-auto">
                <?php admin_partial('flash'); ?>
                <?php require $viewFile; ?>
            </main>
        </div>
    </div>
</body>
</html>
