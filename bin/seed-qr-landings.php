<?php

declare(strict_types=1);

/**
 * Seed / update CMS pages for /qr and /catalogues so staff can edit HTML
 * and insert Media Library PDFs from Admin → Pages.
 *
 * Usage (cPanel Terminal, from app home — NOT public_html):
 *   php bin/seed-qr-landings.php
 */

require dirname(__DIR__) . '/bootstrap.php';

use Admin\Repositories\PageRepository;
use App\Infrastructure\Database;

$pdo = Database::connection(true);
if ($pdo === null) {
    fwrite(STDERR, "Cannot connect to MySQL. Set DB_* in .env (home directory .env on cPanel).\n");
    exit(1);
}

$repo = new PageRepository($pdo);

$qrCss = <<<'CSS'
:root{--blue:#0080C8;--orange:#F5A017;--red:#E84446;--bg:#ffffff;--card:#F6FAFD;--ink:#0C2431;--muted:#5E7C8F;--stroke:#E3EEF6;--hover-stroke:#C9E2F2;--chip:#EAF4FB;--accent:var(--blue);}
*{box-sizing:border-box}html,body{height:100%}
body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:var(--bg);color:var(--ink);text-align:center}
.wrap{max-width:720px;margin:0 auto;padding:20px 16px 48px}
.logo{width:100%;max-width:360px;height:auto;display:block;margin:0 auto 12px}
.brand-sub{margin:0 0 24px;font-size:14px;color:var(--muted)}
.accent-underline{width:96px;height:4px;background:linear-gradient(90deg,var(--blue),var(--orange),var(--red));border-radius:999px;margin:10px auto 20px}
.grid{display:grid;gap:14px;grid-template-columns:1fr 1fr}
@media(max-width:460px){.grid{grid-template-columns:1fr}}
.card{display:flex;align-items:center;gap:12px;background:var(--card);border:1px solid var(--stroke);border-radius:14px;padding:14px;text-decoration:none;color:var(--ink);transition:transform .1s ease,border-color .15s ease,box-shadow .15s ease}
.card:hover{transform:translateY(-2px);border-color:var(--hover-stroke);box-shadow:0 4px 16px rgba(0,0,0,.06)}
.ico{width:42px;height:42px;display:flex;align-items:center;justify-content:center;background:var(--chip);border-radius:10px;border:1px solid var(--stroke);position:relative;flex-shrink:0}
.ico::after{content:'';width:6px;height:6px;border-radius:999px;background:var(--orange);position:absolute;top:6px;right:6px;opacity:.8}
.ico img{width:22px;height:22px;display:block;object-fit:contain}
.row{display:flex;flex-direction:column;justify-content:center;align-items:flex-start;text-align:left;line-height:1.25;min-width:0}
.title{font-size:15px;font-weight:800}.desc{font-size:12px;color:var(--muted)}
.footer{margin-top:28px;font-size:12px;color:var(--muted)}
.card:hover .title{color:var(--blue)}
CSS;

$qrHtml = <<<'HTML'
<main class="wrap">
  <img src="/assets/images/lajivert/logo.png" alt="LAJIVERT Logo" class="logo" />
  <div class="accent-underline" aria-hidden="true"></div>
  <p class="brand-sub">Quick links from your phone.</p>
  <section class="grid">
    <a class="card" href="/catalogues">
      <span class="ico"><img src="/assets/images/lajivert/catalogue.png" alt=""></span>
      <span class="row"><span class="title">Catalogues</span><span class="desc">Browse current catalogues</span></span>
    </a>
    <a class="card" href="https://maps.app.goo.gl/iZEr8kWajpMuGKsD8?g_st=iwb" target="_blank" rel="noopener">
      <span class="ico"><img src="/assets/images/lajivert/location.png" alt=""></span>
      <span class="row"><span class="title">Locations</span><span class="desc">Find us on the map</span></span>
    </a>
    <a class="card" href="https://www.instagram.com/lajivertkibris/" target="_blank" rel="noopener">
      <span class="ico"><img src="/assets/images/lajivert/instagram.png" alt=""></span>
      <span class="row"><span class="title">Instagram</span><span class="desc">Follow our latest posts</span></span>
    </a>
    <a class="card" href="https://wa.me/+905428502229" target="_blank" rel="noopener">
      <span class="ico"><img src="/assets/images/lajivert/whatsapp.png" alt=""></span>
      <span class="row"><span class="title">WhatsApp</span><span class="desc">Chat with us now</span></span>
    </a>
    <a class="card" href="https://www.facebook.com/lajivertkibris" target="_blank" rel="noopener">
      <span class="ico"><img src="/assets/images/lajivert/facebook.png" alt=""></span>
      <span class="row"><span class="title">Facebook</span><span class="desc">Message us on Facebook</span></span>
    </a>
    <a class="card" href="mailto:lajivertcy@gmail.com?subject=QR%20Landing%20Inquiry">
      <span class="ico"><img src="/assets/images/lajivert/mail.png" alt=""></span>
      <span class="row"><span class="title">Email</span><span class="desc">Send us an email</span></span>
    </a>
  </section>
  <p class="footer">Tip: Add this page to your Home Screen for quick access.</p>
</main>
HTML;

$catCss = <<<'CSS'
:root{--blue:#0080C8;--bg:#ffffff;--card:#F6FAFD;--text:#0C2431;--muted:#5E7C8F;--stroke:#E3EEF6;--hover-stroke:#C9E2F2;--chip:#EAF4FB;--accent:var(--blue)}
html,body{margin:0;padding:0}
body{background:var(--bg);color:var(--text);font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;text-align:center}
.wrap{max-width:720px;margin:0 auto;padding:20px 16px 40px}
.logo{width:100%;max-width:320px;height:auto;display:block;margin:0 auto 10px}
h1{margin:6px 0 16px;font-size:24px;font-weight:800;letter-spacing:.3px;color:var(--accent)}
p.sub{margin:0 0 16px;color:var(--muted);font-size:13px}
.list{display:grid;gap:12px}
.card{display:flex;align-items:center;gap:10px;background:var(--card);border:1px solid var(--stroke);border-radius:12px;padding:10px 14px;min-height:56px;text-decoration:none;color:var(--text)}
.card:hover{transform:translateY(-2px);border-color:var(--hover-stroke);box-shadow:0 4px 12px rgba(0,0,0,.06)}
.ico{width:32px;height:32px;display:grid;place-items:center;background:var(--chip);border-radius:8px;flex:0 0 32px;position:relative;border:1px solid var(--stroke)}
.ico::after{content:'';position:absolute;top:5px;right:5px;width:6px;height:6px;border-radius:999px;background:var(--accent);opacity:.8}
.ico img{width:18px;height:18px;object-fit:contain;display:block}
.row{display:flex;flex-direction:column;align-items:flex-start;text-align:left;line-height:1.2}
.title{font-size:14px;font-weight:800}.desc{font-size:11px;color:var(--muted);margin-top:1px}
.footer{margin-top:20px;color:var(--muted);font-size:11px}
.back{display:inline-block;margin-top:16px;font-size:13px;color:var(--accent);text-decoration:none}
CSS;

$catHtml = <<<'HTML'
<main class="wrap">
  <img class="logo" src="/assets/images/lajivert/logo.png" alt="LAJIVERT Logo" />
  <h1>OUR CATALOG</h1>
  <p class="sub">Quick access to our latest catalogues. Upload a PDF in Media Library, then edit this page and replace the link below.</p>
  <section class="list">
    <a class="card" href="https://www.lajivertkibris.com/wp-content/uploads/2025/10/lajivert-compressed-linerized.pdf" target="_blank" rel="noopener">
      <span class="ico"><img src="/assets/images/lajivert/catalogue.png" alt=""></span>
      <div class="row">
        <span class="title">Lajivert Insert</span>
        <span class="desc">PDF catalog — replace this href with your Media Library URL</span>
      </div>
    </a>
  </section>
  <p class="footer">Catalog PDFs may be large; if they don’t open, please download them to your device.</p>
  <a class="back" href="/qr">← Back to QR menu</a>
</main>
HTML;

$pages = [
    [
        'slug' => 'qr',
        'custom_path' => 'qr',
        'title' => 'LAJIVERT QR',
        'html' => $qrHtml,
        'css' => $qrCss,
    ],
    [
        'slug' => 'catalogues',
        'custom_path' => 'catalogues',
        'title' => 'Lajivert Catalogues',
        'html' => $catHtml,
        'css' => $catCss,
    ],
];

foreach ($pages as $def) {
    $existing = $pdo->prepare('SELECT id FROM pages WHERE slug = :slug OR custom_path = :path LIMIT 1');
    $existing->execute(['slug' => $def['slug'], 'path' => $def['custom_path']]);
    $id = $existing->fetchColumn();

    $payload = [
        'slug' => $def['slug'],
        'custom_path' => $def['custom_path'],
        'template' => 'custom',
        'embed_mode' => 'blank',
        'status' => 'published',
        'show_in_nav' => 0,
        'sort_order' => 0,
        'translations' => [
            'en' => [
                'title' => $def['title'],
                'excerpt' => 'Editable QR landing — update HTML/CSS in Admin → Pages.',
                'content' => '',
                'html_embed' => $def['html'],
                'css_embed' => $def['css'],
                'js_embed' => '',
                'php_embed' => '',
                'meta_title' => $def['title'],
                'meta_description' => 'Lajivert quick links',
            ],
        ],
    ];

    if ($id) {
        $repo->update((int) $id, $payload);
        echo "Updated page #{$id}: /{$def['custom_path']}\n";
    } else {
        $id = $repo->create($payload);
        echo "Created page #{$id}: /{$def['custom_path']}\n";
    }
}

echo "\nDone. Edit in Admin → Pages → All Pages.\n";
echo "Upload PDFs in Media Library, then Insert media into the catalogues HTML embed.\n";
