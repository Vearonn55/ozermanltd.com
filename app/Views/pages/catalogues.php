<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Lajivert Insert • LAJIVERT</title>
<style>
  :root{
    --blue:#0080C8;
    --orange:#F5A017;
    --red:#E84446;
    --bg:#ffffff;
    --card:#F6FAFD;
    --text:#0C2431;
    --muted:#5E7C8F;
    --stroke:#E3EEF6;
    --hover-stroke:#C9E2F2;
    --chip:#EAF4FB;
    --accent:var(--blue);
  }

  html,body{margin:0;padding:0}
  body{
    background:var(--bg);
    color:var(--text);
    font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
    text-align:center;
  }

  .wrap{max-width:720px;margin:0 auto;padding:20px 16px 40px}
  .logo{width:100%;max-width:320px;height:auto;display:block;margin:0 auto 10px}
  h1{margin:6px 0 16px;font-size:24px;font-weight:800;letter-spacing:.3px;color:var(--accent)}
  p.sub{margin:0 0 16px;color:var(--muted);font-size:13px}

  .list{display:grid;gap:12px;grid-template-columns:1fr}
  .card{
    display:flex;
    align-items:center;
    gap:10px;
    background:var(--card);
    border:1px solid var(--stroke);
    border-radius:12px;
    padding:10px 14px;
    min-height:56px;
    text-decoration:none;
    color:var(--text);
    transition:transform .1s ease,border-color .15s ease,box-shadow .15s ease;
  }
  .card:hover{
    transform:translateY(-2px);
    border-color:var(--hover-stroke);
    box-shadow:0 4px 12px rgba(0,0,0,.06);
  }
  .card:focus-visible{
    outline:none;
    border-color:var(--accent);
    box-shadow:0 0 0 3px color-mix(in srgb, var(--accent) 25%, transparent);
  }

  .ico{
    width:32px;height:32px;
    display:grid;place-items:center;
    background:var(--chip);
    border-radius:8px;
    flex:0 0 32px;
    position:relative;
    border:1px solid var(--stroke);
  }
  .ico::after{
    content:'';
    position:absolute;
    top:5px;right:5px;
    width:6px;height:6px;
    border-radius:999px;
    background:var(--accent);
    opacity:.8;
  }
  .ico img{
    width:18px;height:18px;
    object-fit:contain;
    display:block;
  }

  .row{
    display:flex;flex-direction:column;
    align-items:flex-start;
    text-align:left;
    line-height:1.2;
    justify-content:center;
  }
  .title{font-size:14px;font-weight:800}
  .desc{font-size:11px;color:var(--muted);margin-top:1px}

  .footer{margin-top:20px;color:var(--muted);font-size:11px}
  .back{display:inline-block;margin-top:16px;font-size:13px;color:var(--accent);text-decoration:none}
  .back:hover{text-decoration:underline}
</style>
</head>
<body>
  <main class="wrap">
    <img class="logo" src="<?= e(asset('images/lajivert/logo.png')) ?>" alt="LAJIVERT Logo" />
    <h1>OUR CATALOG</h1>
    <p class="sub">Quick access to our latest catalogues.</p>

    <section class="list">
      <a class="card" href="https://www.lajivertkibris.com/wp-content/uploads/2025/10/lajivert-compressed-linerized.pdf" target="_blank" rel="noopener">
        <span class="ico">
          <img src="<?= e(asset('images/lajivert/catalogue.png')) ?>" alt="Lajivert Insert Icon">
        </span>
        <div class="row">
          <span class="title">Lajivert Insert</span>
          <span class="desc">PDF catalog</span>
        </div>
      </a>
    </section>

    <p class="footer">Catalog PDFs may be large; if they don’t open, please download them to your device.</p>
    <a class="back" href="/qr">← Back to QR menu</a>
  </main>
</body>
</html>
