<?php

declare(strict_types=1);

/**
 * Admin CRUD smoke test (cookie session via curl).
 * Usage: php bin/smoke-admin.php
 */

$base = getenv('SMOKE_BASE') ?: 'http://127.0.0.1:8080';
$cookieFile = tempnam(sys_get_temp_dir(), 'ozsmoke');
$log = [];

function req(string $method, string $url, ?array $post, string $cookieFile): array
{
    $ch = curl_init($url);
    $opts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_COOKIEJAR => $cookieFile,
        CURLOPT_COOKIEFILE => $cookieFile,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CUSTOMREQUEST => $method,
    ];
    if ($post !== null) {
        $opts[CURLOPT_POSTFIELDS] = http_build_query($post);
    }
    curl_setopt_array($ch, $opts);
    $raw = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    $headerSize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr((string) $raw, 0, $headerSize);
    $body = substr((string) $raw, $headerSize);

    return ['code' => $code, 'headers' => $headers, 'body' => $body, 'err' => $err];
}

function csrf(string $html): string
{
    if (preg_match('/name="_csrf"\s+value="([^"]+)"/', $html, $m)) {
        return $m[1];
    }

    return '';
}

function loc(array $r): string
{
    return preg_match('/^Location:\s*(.+)$/mi', $r['headers'], $m) ? trim($m[1]) : '';
}

function failSnippet(array $r): string
{
    if (preg_match('/(Fatal error|Uncaught|SQLSTATE|Parse error)[^<\n]{0,200}/', $r['body'], $m)) {
        return $m[0];
    }

    return '';
}

$r = req('GET', "$base/admin/login", null, $cookieFile);
$token = csrf($r['body']);
$log[] = "login_page={$r['code']} csrf=" . ($token !== '' ? 'yes' : 'no') . ($r['err'] ? " err={$r['err']}" : '');

$r = req('POST', "$base/admin/login", [
    '_csrf' => $token,
    'email' => 'admin@ozermanltd.com',
    'password' => 'admin123',
], $cookieFile);
$log[] = "login_post={$r['code']} loc=" . loc($r);

$r = req('GET', "$base/admin", null, $cookieFile);
$log[] = "dashboard={$r['code']}";

$resources = [
    'pages', 'news', 'projects', 'sectors', 'hero-slides', 'stats', 'gallery', 'menus',
    'team', 'offices', 'stores', 'partnerships', 'site-sections', 'media', 'users',
    'settings', 'seo', 'activity', 'logs', 'contacts', 'analytics', 'categories',
];
foreach ($resources as $res) {
    $r = req('GET', "$base/admin/$res", null, $cookieFile);
    $snip = failSnippet($r);
    $log[] = "{$res}={$r['code']}" . ($snip !== '' ? " ERR:$snip" : '');
}

$creates = ['news', 'projects', 'sectors', 'hero-slides', 'stats', 'gallery', 'stores', 'partnerships', 'site-sections', 'team', 'offices', 'categories'];
foreach ($creates as $res) {
    $r = req('GET', "$base/admin/$res/create", null, $cookieFile);
    $snip = failSnippet($r);
    $log[] = "{$res}_create={$r['code']}" . ($snip !== '' ? " ERR:$snip" : '');
}

// --- News create + update ---
$r = req('GET', "$base/admin/news/create", null, $cookieFile);
$token = csrf($r['body']);
$slug = 'smoke-' . time();
$r = req('POST', "$base/admin/news", [
    '_csrf' => $token,
    'category_id' => '1',
    'status' => 'draft',
    'publish_date' => date('Y-m-d\TH:i'),
    'translations_en_title' => 'Smoke Test Article',
    'translations_en_slug' => $slug,
    'translations_en_excerpt' => 'ex',
    'translations_en_content' => '<p>Hi</p>',
    'translations_tr_title' => 'Duman',
    'translations_tr_slug' => $slug . '-tr',
    'translations_tr_content' => '<p>Merhaba</p>',
    'translations_ar_title' => 'TestAR',
    'translations_ar_slug' => $slug . '-ar',
    'translations_ar_content' => '<p>Hi</p>',
], $cookieFile);
$log[] = "news_store={$r['code']} loc=" . loc($r) . (($s = failSnippet($r)) !== '' ? " ERR:$s" : '');

$r = req('GET', "$base/admin/news", null, $cookieFile);
$log[] = 'news_list_has=' . (str_contains($r['body'], 'Smoke Test Article') ? 'yes' : 'no');
preg_match_all('/\/admin\/news\/(\d+)\/edit/', $r['body'], $mm);
$newsId = 0;
foreach (array_unique($mm[1] ?? []) as $cand) {
    $er = req('GET', "$base/admin/news/$cand/edit", null, $cookieFile);
    if (str_contains($er['body'], 'Smoke Test Article') || str_contains($er['body'], $slug)) {
        $newsId = (int) $cand;
        break;
    }
}
$log[] = "news_id=$newsId";
if ($newsId > 0) {
    $r = req('GET', "$base/admin/news/$newsId/edit", null, $cookieFile);
    $token = csrf($r['body']);
    $r = req('POST', "$base/admin/news/$newsId", [
        '_csrf' => $token,
        'category_id' => '1',
        'status' => 'published',
        'publish_date' => date('Y-m-d\TH:i'),
        'translations_en_title' => 'Smoke Test Article Updated',
        'translations_en_slug' => $slug,
        'translations_en_content' => '<p>Updated</p>',
        'translations_tr_title' => 'Duman',
        'translations_tr_slug' => $slug . '-tr',
        'translations_tr_content' => '<p>x</p>',
        'translations_ar_title' => 'TestAR',
        'translations_ar_slug' => $slug . '-ar',
        'translations_ar_content' => '<p>x</p>',
    ], $cookieFile);
    $log[] = "news_update={$r['code']} loc=" . loc($r) . (($s = failSnippet($r)) !== '' ? " ERR:$s" : '');
}

// Hero
$r = req('GET', "$base/admin/hero-slides/create", null, $cookieFile);
$token = csrf($r['body']);
$r = req('POST', "$base/admin/hero-slides", [
    '_csrf' => $token,
    'sort_order' => '97',
    'is_active' => '1',
    'cta_url' => 'about-us',
    'translations_en_title' => 'Smoke Hero',
    'translations_en_subtitle' => 'Sub',
    'translations_en_cta_text' => 'Go',
    'translations_tr_title' => 'Hero TR',
    'translations_tr_subtitle' => 'S',
    'translations_tr_cta_text' => 'G',
    'translations_ar_title' => 'Hero AR',
    'translations_ar_subtitle' => 'S',
    'translations_ar_cta_text' => 'G',
], $cookieFile);
$log[] = "hero_store={$r['code']} loc=" . loc($r) . (($s = failSnippet($r)) !== '' ? " ERR:$s" : '');

// Hero edit (first id)
$r = req('GET', "$base/admin/hero-slides", null, $cookieFile);
if (preg_match('/\/admin\/hero-slides\/(\d+)\/edit/', $r['body'], $m)) {
    $hid = (int) $m[1];
    $r = req('GET', "$base/admin/hero-slides/$hid/edit", null, $cookieFile);
    $token = csrf($r['body']);
    $log[] = "hero_edit_form={$r['code']}" . (($s = failSnippet($r)) !== '' ? " ERR:$s" : '');
    if ($token !== '') {
        $r = req('POST', "$base/admin/hero-slides/$hid", [
            '_csrf' => $token,
            'sort_order' => '0',
            'is_active' => '1',
            'cta_url' => 'about-us',
            'translations_en_title' => 'Smoke Hero Edited',
            'translations_en_subtitle' => 'Sub',
            'translations_en_cta_text' => 'Go',
            'translations_tr_title' => 'Hero TR',
            'translations_tr_subtitle' => 'S',
            'translations_tr_cta_text' => 'G',
            'translations_ar_title' => 'Hero AR',
            'translations_ar_subtitle' => 'S',
            'translations_ar_cta_text' => 'G',
        ], $cookieFile);
        $log[] = "hero_update={$r['code']} loc=" . loc($r) . (($s = failSnippet($r)) !== '' ? " ERR:$s" : '');
        $r = req('GET', "$base/admin/hero-slides/$hid/edit", null, $cookieFile);
        $log[] = "hero_reedit={$r['code']} found=" . (str_contains($r['body'], 'Smoke Hero Edited') || str_contains($r['body'], 'Hero') ? 'yes' : 'no') . (($s = failSnippet($r)) !== '' ? " ERR:$s" : '');
    }
}

// Store / project / sector / partner / section / gallery / stats
$r = req('GET', "$base/admin/stores/create", null, $cookieFile);
$token = csrf($r['body']);
$r = req('POST', "$base/admin/stores", [
    '_csrf' => $token, 'phone' => '1', 'email' => 'a@b.c', 'sort_order' => '11', 'is_active' => '1',
    'translations_en_name' => 'Smoke Store', 'translations_en_slug' => 'smoke-store-' . time(),
    'translations_en_city' => 'City', 'translations_en_address' => 'Addr', 'translations_en_working_hours' => '9-5',
    'translations_tr_name' => 'Magaza', 'translations_tr_slug' => 'smoke-store-tr', 'translations_tr_city' => 'C', 'translations_tr_address' => 'A', 'translations_tr_working_hours' => '9',
    'translations_ar_name' => 'StoreAR', 'translations_ar_slug' => 'smoke-store-ar', 'translations_ar_city' => 'C', 'translations_ar_address' => 'A', 'translations_ar_working_hours' => '9',
], $cookieFile);
$log[] = "store_post={$r['code']} loc=" . loc($r) . (($s = failSnippet($r)) !== '' ? " ERR:$s" : '');

$r = req('GET', "$base/admin/projects/create", null, $cookieFile);
$token = csrf($r['body']);
$ps = 'smoke-proj-' . time();
$r = req('POST', "$base/admin/projects", [
    '_csrf' => $token, 'category_id' => '1', 'status' => 'planning', 'location' => 'X', 'is_active' => '1',
    'translations_en_title' => 'Smoke Project', 'translations_en_slug' => $ps, 'translations_en_description' => '<p>D</p>',
    'translations_tr_title' => 'Proje', 'translations_tr_slug' => $ps . '-tr', 'translations_tr_description' => '<p>D</p>',
    'translations_ar_title' => 'ProjAR', 'translations_ar_slug' => $ps . '-ar', 'translations_ar_description' => '<p>D</p>',
], $cookieFile);
$log[] = "project_post={$r['code']} loc=" . loc($r) . (($s = failSnippet($r)) !== '' ? " ERR:$s" : '');

$r = req('GET', "$base/admin/sectors/create", null, $cookieFile);
$token = csrf($r['body']);
$ss = 'smoke-sec-' . time();
$r = req('POST', "$base/admin/sectors", [
    '_csrf' => $token, 'icon' => 'globe', 'color' => '#111', 'sort_order' => '20', 'is_active' => '1',
    'translations_en_name' => 'Smoke Sector', 'translations_en_slug' => $ss, 'translations_en_overview' => '<p>O</p>', 'translations_en_services_text' => "• A",
    'translations_tr_name' => 'Sektor', 'translations_tr_slug' => $ss . '-tr', 'translations_tr_overview' => '<p>O</p>', 'translations_tr_services_text' => "• A",
    'translations_ar_name' => 'SecAR', 'translations_ar_slug' => $ss . '-ar', 'translations_ar_overview' => '<p>O</p>', 'translations_ar_services_text' => "• A",
], $cookieFile);
$log[] = "sector_post={$r['code']} loc=" . loc($r) . (($s = failSnippet($r)) !== '' ? " ERR:$s" : '');

$r = req('GET', "$base/admin/partnerships/create", null, $cookieFile);
$token = csrf($r['body']);
$r = req('POST', "$base/admin/partnerships", [
    '_csrf' => $token, 'slug' => 'smoke-brand-' . time(), 'website_url' => 'https://example.com', 'sort_order' => '9', 'is_active' => '1',
    'translations_en_name' => 'Smoke Brand', 'translations_en_tagline' => 'T', 'translations_en_description' => 'D',
    'translations_tr_name' => 'Marka', 'translations_tr_tagline' => 'T', 'translations_tr_description' => 'D',
    'translations_ar_name' => 'BrandAR', 'translations_ar_tagline' => 'T', 'translations_ar_description' => 'D',
], $cookieFile);
$log[] = "partner_post={$r['code']} loc=" . loc($r) . (($s = failSnippet($r)) !== '' ? " ERR:$s" : '');

$r = req('GET', "$base/admin/site-sections/create", null, $cookieFile);
$token = csrf($r['body']);
$r = req('POST', "$base/admin/site-sections", [
    '_csrf' => $token, 'area' => 'home_operations', 'icon' => 'x', 'sort_order' => '40', 'is_active' => '1',
    'translations_en_title' => 'Smoke Op', 'translations_en_body' => 'B',
    'translations_tr_title' => 'Op TR', 'translations_tr_body' => 'B',
    'translations_ar_title' => 'Op AR', 'translations_ar_body' => 'B',
], $cookieFile);
$log[] = "section_post={$r['code']} loc=" . loc($r) . (($s = failSnippet($r)) !== '' ? " ERR:$s" : '');

$r = req('GET', "$base/admin/stats/create", null, $cookieFile);
$token = csrf($r['body']);
$r = req('POST', "$base/admin/stats", [
    '_csrf' => $token, 'sort_order' => '50', 'value' => '99+',
    'translations_en_label' => 'Smoke Stat', 'translations_tr_label' => 'StatTR', 'translations_ar_label' => 'StatAR',
], $cookieFile);
$log[] = "stats_post={$r['code']} loc=" . loc($r) . (($s = failSnippet($r)) !== '' ? " ERR:$s" : '');

$r = req('GET', "$base/admin/gallery/create", null, $cookieFile);
$token = csrf($r['body']);
$gs = 'smoke-gal-' . time();
$r = req('POST', "$base/admin/gallery", [
    '_csrf' => $token, 'sort_order' => '5', 'is_active' => '1',
    'translations_en_title' => 'Smoke Gallery', 'translations_en_slug' => $gs, 'translations_en_description' => 'D',
    'translations_tr_title' => 'Galeri', 'translations_tr_slug' => $gs . '-tr', 'translations_tr_description' => 'D',
    'translations_ar_title' => 'GalAR', 'translations_ar_slug' => $gs . '-ar', 'translations_ar_description' => 'D',
], $cookieFile);
$log[] = "gallery_post={$r['code']} loc=" . loc($r) . (($s = failSnippet($r)) !== '' ? " ERR:$s" : '');

// Page update
$r = req('GET', "$base/admin/pages", null, $cookieFile);
if (preg_match('/\/admin\/pages\/(\d+)\/edit/', $r['body'], $m)) {
    $pid = (int) $m[1];
    $r = req('GET', "$base/admin/pages/$pid/edit", null, $cookieFile);
    $token = csrf($r['body']);
    preg_match('/name="slug"\s+value="([^"]*)"/', $r['body'], $sm);
    $pslug = $sm[1] ?? 'home';
    $r = req('POST', "$base/admin/pages/$pid", [
        '_csrf' => $token, 'slug' => $pslug, 'template' => 'default', 'status' => 'published', 'show_in_nav' => '1', 'sort_order' => '0',
        'translations_en_title' => 'Page EN', 'translations_en_excerpt' => 'ex', 'translations_en_content' => '<p>c</p>',
        'translations_tr_title' => 'Page TR', 'translations_tr_content' => '<p>c</p>',
        'translations_ar_title' => 'Page AR', 'translations_ar_content' => '<p>c</p>',
    ], $cookieFile);
    $log[] = "page_update={$r['code']} loc=" . loc($r) . (($s = failSnippet($r)) !== '' ? " ERR:$s" : '');
}

echo implode("\n", $log), "\n";
@unlink($cookieFile);
