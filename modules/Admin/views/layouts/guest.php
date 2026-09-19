<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin') ?> | <?= e((string) admin_config('brand_name', 'CMS')) ?></title>
    <link rel="stylesheet" href="/assets/css/tailwind.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script defer src="/assets/vendor/alpinejs/alpine.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="admin-guest antialiased min-h-screen <?= !empty($picker) ? 'p-4' : 'flex items-center justify-center p-4' ?>">
    <?php if (!empty($picker)): ?>
    <?php admin_partial('flash'); ?>
    <?php require $viewFile; ?>
    <?php else: ?>
    <div class="w-full flex justify-center">
        <?php admin_partial('flash'); ?>
        <?php require $viewFile; ?>
    </div>
    <?php endif; ?>
</body>
</html>
