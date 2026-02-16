<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (function () {
            try {
                var mode = localStorage.getItem('site-theme') || localStorage.getItem('landing-theme') || localStorage.getItem('admin-theme');
                if (mode === 'dark') {
                    document.documentElement.setAttribute('data-theme', 'dark');
                }
            } catch (e) {}
        })();
    </script>
    <?php
    $seoTitle = trim((string) ($post['seo_title'] ?? '')) !== '' ? (string) $post['seo_title'] : (string) $post['title'];
    $seoDescription = trim((string) ($post['seo_description'] ?? '')) !== '' ? (string) $post['seo_description'] : (string) ($post['excerpt'] ?? mb_strimwidth(strip_tags((string) $post['content']), 0, 160, '...'));
    $seoKeywords = (string) ($post['seo_keywords'] ?? '');
    $postUrl = site_url('program/' . $post['slug']);
    $postImage = base_url(! empty($post['image_path']) ? $post['image_path'] : 'assets/images/card-image.png');
    ?>
    <title><?= esc($seoTitle) ?> - Portal Desa</title>
    <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>">
    <link rel="alternate icon" href="<?= base_url('assets/images/logo@2x.png') ?>">
    <meta name="description" content="<?= esc($seoDescription) ?>">
    <?php if ($seoKeywords !== '') : ?><meta name="keywords" content="<?= esc($seoKeywords) ?>"><?php endif; ?>
    <link rel="canonical" href="<?= esc($postUrl) ?>">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="Portal Desa">
    <meta property="og:title" content="<?= esc($seoTitle) ?>">
    <meta property="og:description" content="<?= esc($seoDescription) ?>">
    <meta property="og:url" content="<?= esc($postUrl) ?>">
    <meta property="og:image" content="<?= esc($postImage) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($seoTitle) ?>">
    <meta name="twitter:description" content="<?= esc($seoDescription) ?>">
    <meta name="twitter:image" content="<?= esc($postImage) ?>">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/main.min.css') ?>" rel="stylesheet">
    <style>
        :root {
            --bg: #f3f6f9;
            --surface: #ffffff;
            --text: #2c3447;
            --muted: #6b7385;
            --line: #dde4f0;
            --primary: #7888fc;
            --primary-2: #4f64d9;
        }
        html[data-theme="dark"] {
            --bg: #131926;
            --surface: #1b2435;
            --text: #e6edf9;
            --muted: #9eabc4;
            --line: #2d3a54;
            --primary: #95a4ff;
            --primary-2: #7a89ff;
        }
        body { font-family: 'Poppins', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; flex-direction: column; padding-top: 74px; }
        body > .container { flex: 1; }
        .guest-navbar {
            background: color-mix(in srgb, var(--surface) 92%, transparent);
            border-bottom: 1px solid var(--line);
            backdrop-filter: blur(8px);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1100;
        }
        .guest-navbar .navbar-brand { color: var(--primary-2); font-weight: 800; letter-spacing: .3px; }
        .guest-navbar .nav-link { color: var(--text); font-weight: 500; }
        .theme-toggle {
            border: 1px solid var(--line);
            background: var(--surface);
            color: var(--text);
            border-radius: 10px;
            padding: 7px 12px;
            font-size: 13px;
            line-height: 1;
        }
        .article-wrap { max-width: 900px; margin: 32px auto; background: var(--surface); border-radius: 16px; padding: 28px; border: 1px solid var(--line); }
        .article-image { width: 100%; max-height: 420px; object-fit: cover; border-radius: 12px; margin-bottom: 20px; }
        .footer { background: #1f2937; color: #dbe4f5; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <?= view('shared/layout/navbar', ['homePage' => false]) ?>

    <div class="container">
        <div class="article-wrap shadow-sm">
            <a href="<?= site_url('/') ?>" class="btn btn-light btn-sm mb-3">Kembali</a>
            <img src="<?= base_url(! empty($post['image_path']) ? $post['image_path'] : 'assets/images/card-image.png') ?>" alt="<?= esc($post['title']) ?>" class="article-image">
            <h1><?= esc($post['title']) ?></h1>
            <p class="text-muted mb-4">Dipublikasikan: <?= esc(date('d M Y H:i', strtotime((string) $post['published_at']))) ?></p>
            <div><?= nl2br(esc((string) $post['content'])) ?></div>
        </div>
    </div>

    <?= view('shared/layout/footer') ?>

    <script>
        (function () {
            var root = document.documentElement;
            var btn = document.getElementById('themeToggle');
            var stored = localStorage.getItem('site-theme') || localStorage.getItem('landing-theme') || localStorage.getItem('admin-theme');
            var mode = stored === 'dark' ? 'dark' : 'light';

            function applyTheme(value) {
                root.setAttribute('data-theme', value);
                if (btn) {
                    btn.textContent = value === 'dark' ? 'Light Mode' : 'Dark Mode';
                }
                localStorage.setItem('site-theme', value);
                localStorage.setItem('landing-theme', value);
                localStorage.setItem('admin-theme', value);
            }

            applyTheme(mode);

            if (btn) {
                btn.addEventListener('click', function () {
                    mode = mode === 'dark' ? 'light' : 'dark';
                    applyTheme(mode);
                });
            }
        })();
    </script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>" defer></script>
    <script src="<?= base_url('assets/js/app-lite.js') ?>" defer></script>
    <script type="application/ld+json">
    <?= json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $seoTitle,
        'description' => $seoDescription,
        'image' => [$postImage],
        'datePublished' => $post['published_at'] ?? null,
        'dateModified' => $post['updated_at'] ?? ($post['published_at'] ?? null),
        'mainEntityOfPage' => $postUrl,
        'author' => ['@type' => 'Organization', 'name' => 'Pemerintah Desa'],
        'publisher' => ['@type' => 'Organization', 'name' => 'Portal Desa'],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
    </script>
</body>
</html>

