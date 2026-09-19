<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Under Construction | <?= e((string) config('name', 'Özerman Ticaret')) ?></title>
    <style>
        :root {
            --bg: #181818;
            --card: #1e1e1e;
            --text: #cccccc;
            --muted: #8b8b8b;
            --heading: #e8e8e8;
            --accent: #3794ff;
            --border: #2b2b2b;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
            background: var(--bg);
            color: var(--text);
            padding: 24px;
        }
        .card {
            max-width: 440px;
            width: 100%;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 2.5rem 2rem;
            text-align: center;
        }
        h1 {
            margin: 0 0 0.75rem;
            font-size: 1.5rem;
            color: var(--heading);
            font-weight: 700;
        }
        p {
            margin: 0 0 1.5rem;
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.5;
        }
        a {
            display: inline-block;
            color: #fff;
            background: var(--accent);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.65rem 1.25rem;
            border-radius: 0.4rem;
        }
        a:hover { filter: brightness(1.08); }
        .brand {
            font-size: 0.75rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand"><?= e((string) config('name', 'Özerman Ticaret')) ?></div>
        <h1>Under construction</h1>
        <p>Our full website is being prepared. Please check back soon.</p>
        <a href="/qr">Open Lajivert QR menu</a>
    </div>
</body>
</html>
