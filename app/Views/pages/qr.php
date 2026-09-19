<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>LAJIVERT</title>
<style>
  :root{
    --blue:   #0080C8;
    --orange: #F5A017;
    --red:    #E84446;
    --ltblue: #D3E7F1;
    --midblu: #6DB6DF;
    --bg: #ffffff;
    --card: #F6FAFD;
    --ink: #0C2431;
    --muted: #5E7C8F;
    --stroke: #E3EEF6;
    --hover-stroke: #C9E2F2;
    --chip: #EAF4FB;
    --accent: var(--blue);
  }

  * { box-sizing: border-box; }
  html,body { height: 100%; }

  body {
    margin: 0;
    font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    background: var(--bg);
    color: var(--ink);
    text-align: center;
  }

  .wrap {
    max-width: 720px;
    margin: 0 auto;
    padding: 20px 16px 48px;
  }

  .logo {
    width: 100%;
    max-width: 360px;
    height: auto;
    display: block;
    margin: 0 auto 12px auto;
  }

  .brand-title {
    margin: 0 0 6px;
    font-size: 24px;
    font-weight: 800;
    letter-spacing: .4px;
    color: var(--blue);
  }

  .brand-sub {
    margin: 0 0 24px;
    font-size: 14px;
    color: var(--muted);
  }

  .accent-underline {
    width: 96px;
    height: 4px;
    background: linear-gradient(90deg, var(--blue), var(--orange), var(--red));
    border-radius: 999px;
    margin: 10px auto 20px;
  }

  .grid {
    display: grid;
    gap: 14px;
    grid-template-columns: 1fr 1fr;
  }
  @media (max-width: 460px) { .grid { grid-template-columns: 1fr; } }

  .card {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--card);
    border: 1px solid var(--stroke);
    border-radius: 14px;
    padding: 14px;
    text-decoration: none;
    color: var(--ink);
    transition: transform .1s ease, border-color .15s ease, box-shadow .15s ease;
    outline: none;
  }
  .card:hover {
    transform: translateY(-2px);
    border-color: var(--hover-stroke);
    box-shadow: 0 4px 16px rgba(0,0,0,.06);
  }
  .card:focus-visible {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 25%, transparent);
  }

  .ico {
    width: 42px; height: 42px; aspect-ratio: 1/1;
    display: flex; align-items: center; justify-content: center;
    background: var(--chip);
    border-radius: 10px;
    border: 1px solid var(--stroke);
    position: relative;
    flex-shrink: 0;
  }
  .ico::after {
    content: '';
    width: 6px; height: 6px; border-radius: 999px;
    background: var(--orange);
    position: absolute; top: 6px; right: 6px; opacity: .8;
    pointer-events: none;
  }
  .ico img {
    width: 22px; height: 22px; display: block; object-fit: contain;
  }

  .row {
    display: flex; flex-direction: column;
    justify-content: center; align-items: flex-start;
    text-align: left; line-height: 1.25; min-width: 0;
  }
  .title { font-size: 15px; font-weight: 800; letter-spacing: .2px; }
  .desc  { font-size: 12px; color: var(--muted); }

  .footer {
    margin-top: 28px;
    font-size: 12px;
    color: var(--muted);
  }

  .card:hover .title { color: var(--blue); }
</style>
</head>
<body>
  <main class="wrap">
    <img
      src="<?= e(asset('images/lajivert/logo.png')) ?>"
      alt="LAJIVERT Logo"
      class="logo"
    />

    <h1 class="brand-title"></h1>
    <div class="accent-underline" aria-hidden="true"></div>
    <p class="brand-sub">Quick links from your phone.</p>

    <section class="grid">
      <a class="card" href="/catalogues">
        <span class="ico">
          <img src="<?= e(asset('images/lajivert/catalogue.png')) ?>" alt="Catalogues Icon">
        </span>
        <span class="row">
          <span class="title">Catalogues</span>
          <span class="desc">Browse current catalogues</span>
        </span>
      </a>

      <a class="card" href="https://maps.app.goo.gl/iZEr8kWajpMuGKsD8?g_st=iwb" target="_blank" rel="noopener">
        <span class="ico">
          <img src="<?= e(asset('images/lajivert/location.png')) ?>" alt="Locations Icon">
        </span>
        <span class="row">
          <span class="title">Locations</span>
          <span class="desc">Find us on the map</span>
        </span>
      </a>

      <a class="card" href="https://www.instagram.com/lajivertkibris/" target="_blank" rel="noopener">
        <span class="ico">
          <img src="<?= e(asset('images/lajivert/instagram.png')) ?>" alt="Instagram Icon">
        </span>
        <span class="row">
          <span class="title">Instagram</span>
          <span class="desc">Follow our latest posts</span>
        </span>
      </a>

      <a class="card" href="https://wa.me/+905428502229" target="_blank" rel="noopener">
        <span class="ico">
          <img src="<?= e(asset('images/lajivert/whatsapp.png')) ?>" alt="WhatsApp Icon">
        </span>
        <span class="row">
          <span class="title">WhatsApp</span>
          <span class="desc">Chat with us now</span>
        </span>
      </a>

      <a class="card" href="https://www.facebook.com/lajivertkibris" target="_blank" rel="noopener">
        <span class="ico">
          <img src="<?= e(asset('images/lajivert/facebook.png')) ?>" alt="Facebook Icon">
        </span>
        <span class="row">
          <span class="title">Facebook</span>
          <span class="desc">Message us on Facebook</span>
        </span>
      </a>

      <a class="card" href="mailto:lajivertcy@gmail.com?subject=QR%20Landing%20Inquiry">
        <span class="ico">
          <img src="<?= e(asset('images/lajivert/mail.png')) ?>" alt="Email Icon">
        </span>
        <span class="row">
          <span class="title">Email</span>
          <span class="desc">Send us an email</span>
        </span>
      </a>
    </section>

    <p class="footer">Tip: Add this page to your Home Screen for quick access.</p>
  </main>
</body>
</html>
