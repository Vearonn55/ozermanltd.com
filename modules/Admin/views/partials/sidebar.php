<?php
$user = $user ?? null;
$pagesGroup = [
    ['path' => 'pages', 'label' => 'All Pages', 'min' => 'editor', 'feature' => 'pages'],
    ['path' => 'news', 'label' => 'News', 'min' => 'editor', 'feature' => 'news'],
    ['path' => 'projects', 'label' => 'Projects', 'min' => 'editor', 'feature' => 'projects'],
    ['path' => 'sectors', 'label' => 'Sectors', 'min' => 'editor', 'feature' => 'sectors'],
    ['path' => 'hero-slides', 'label' => 'Hero Slides', 'min' => 'editor', 'feature' => 'hero_slides'],
    ['path' => 'banners', 'label' => 'Banners & Images', 'min' => 'editor', 'feature' => 'banners'],
    ['path' => 'stats', 'label' => 'Stat Counters', 'min' => 'editor', 'feature' => 'stats'],
    ['path' => 'site-sections', 'label' => 'Site Sections', 'min' => 'editor', 'feature' => 'site_sections'],
    ['path' => 'gallery', 'label' => 'Gallery', 'min' => 'editor', 'feature' => 'gallery'],
    ['path' => 'stores', 'label' => 'Stores', 'min' => 'editor', 'feature' => 'stores'],
    ['path' => 'partnerships', 'label' => 'Partnerships', 'min' => 'editor', 'feature' => 'partnerships'],
    ['path' => 'team', 'label' => 'Team', 'min' => 'editor', 'feature' => 'team'],
    ['path' => 'offices', 'label' => 'Offices', 'min' => 'editor', 'feature' => 'offices'],
    ['path' => 'menus', 'label' => 'Menus', 'min' => 'content_manager', 'feature' => 'menus'],
    ['path' => 'categories', 'label' => 'Categories', 'min' => 'content_manager', 'feature' => 'categories'],
];
$pagesVisible = array_values(array_filter($pagesGroup, static function (array $link) use ($user): bool {
    if ($link['feature'] !== null && !admin_config('features.' . $link['feature'], true)) {
        return false;
    }
    return admin_can($user, $link['min']);
}));
$pagesOpen = false;
foreach ($pagesVisible as $link) {
    if (admin_active($link['path'])) {
        $pagesOpen = true;
        break;
    }
}

$systemLinks = [
    ['path' => 'media', 'label' => 'Media Library', 'min' => 'editor', 'feature' => 'media'],
    ['path' => 'comments', 'label' => 'News Reviews', 'min' => 'editor', 'feature' => 'comments'],
    ['path' => 'contacts', 'label' => 'Contacts', 'min' => 'editor', 'feature' => 'contacts'],
    ['path' => 'seo', 'label' => 'SEO', 'min' => 'editor', 'feature' => 'seo'],
    ['path' => 'analytics', 'label' => 'Analytics', 'min' => 'editor', 'feature' => 'analytics'],
    ['path' => 'settings', 'label' => 'Settings', 'min' => 'content_manager', 'feature' => 'settings'],
    ['path' => 'users', 'label' => 'Users', 'min' => 'super_admin', 'feature' => 'users'],
    ['path' => 'activity', 'label' => 'Audit Log', 'min' => 'content_manager', 'feature' => 'activity'],
    ['path' => 'logs', 'label' => 'App Logs', 'min' => 'super_admin', 'feature' => 'logs'],
];
?>
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="sidebar-backdrop md:hidden"></div>
<aside class="cui-sidebar"
       :class="sidebarOpen ? 'flex' : 'hidden md:flex'">
    <div class="cui-sidebar-brand">
        <div>
            <a href="<?= admin_url() ?>"><?= e((string) admin_config('brand_name', 'CMS')) ?></a>
            <div class="text-xs" style="color:var(--cui-muted)">CoreUI console</div>
        </div>
        <button type="button" @click="sidebarOpen = false" class="md:hidden btn btn-link" aria-label="Close menu">✕</button>
    </div>

    <nav class="cui-sidebar-nav">
        <?php if (admin_can($user, 'editor')): ?>
        <a href="<?= admin_url() ?>" class="cui-nav-link <?= admin_active('') ? 'is-active' : '' ?>">Dashboard</a>
        <?php endif; ?>

        <?php if ($pagesVisible): ?>
        <div class="cui-nav-title">Content</div>
        <div class="cui-nav-group <?= $pagesOpen ? 'is-open' : '' ?>" x-data="{ open: <?= $pagesOpen ? 'true' : 'false' ?> }" :class="open ? 'is-open' : ''">
            <button type="button" class="cui-nav-link" @click="open = !open">
                Pages
                <span class="cui-caret">▸</span>
            </button>
            <div class="cui-nav-group-items" x-show="open" x-cloak>
                <?php foreach ($pagesVisible as $link): ?>
                <a href="<?= admin_url($link['path']) ?>" class="cui-nav-link <?= admin_active($link['path']) ? 'is-active' : '' ?>">
                    <?= e($link['label']) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="cui-nav-title">System</div>
        <?php foreach ($systemLinks as $link):
            if ($link['feature'] !== null && !admin_config('features.' . $link['feature'], true)) {
                continue;
            }
            if (!admin_can($user, $link['min'])) {
                continue;
            }
        ?>
        <a href="<?= admin_url($link['path']) ?>" class="cui-nav-link <?= admin_active($link['path']) ? 'is-active' : '' ?>">
            <?= e($link['label']) ?>
        </a>
        <?php endforeach; ?>
    </nav>

    <div class="cui-sidebar-footer">
        <a href="<?= e((string) admin_config('view_site_url', '/')) ?>" target="_blank">View site ↗</a>
    </div>
</aside>
