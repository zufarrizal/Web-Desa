<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (function () {
            try {
                if (localStorage.getItem('landing-theme') === 'dark') {
                    document.documentElement.setAttribute('data-theme', 'dark');
                }
            } catch (e) {}
        })();
    </script>
    <title>Portal Desa - Pelayanan Administrasi</title>
    <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>">
    <link rel="alternate icon" href="<?= base_url('assets/images/logo@2x.png') ?>">
    <meta name="description" content="Portal Desa untuk layanan administrasi warga, pengaduan masyarakat, artikel, dan kegiatan desa.">
    <meta name="keywords" content="portal desa, pelayanan desa, surat desa, kegiatan desa, artikel desa">
    <link rel="canonical" href="<?= esc(site_url('/')) ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Portal Desa">
    <meta property="og:title" content="Portal Desa - Pelayanan Administrasi">
    <meta property="og:description" content="Layanan administrasi desa terintegrasi untuk dokumen, pengaduan, artikel, dan kegiatan.">
    <meta property="og:url" content="<?= esc(site_url('/')) ?>">
    <meta property="og:image" content="<?= esc(base_url('assets/images/card-image.png')) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Portal Desa - Pelayanan Administrasi">
    <meta name="twitter:description" content="Layanan administrasi desa terintegrasi untuk dokumen, pengaduan, artikel, dan kegiatan.">
    <meta name="twitter:image" content="<?= esc(base_url('assets/images/card-image.png')) ?>">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/main.min.css') ?>" rel="stylesheet">
    <style>
        :root {
            --bg: #f3f6f9;
            --surface: #ffffff;
            --surface-2: #eef3fb;
            --text: #2c3447;
            --muted: #6b7385;
            --line: #dde4f0;
            --primary: #7888fc;
            --primary-2: #4f64d9;
            --hero-grad: linear-gradient(135deg, #eef3ff 0%, #f2fbf6 100%);
            --footer: #1f2937;
            --footer-text: #dbe4f5;
            --shadow: 0 14px 32px rgba(21, 33, 63, 0.08);
        }

        html[data-theme="dark"] {
            --bg: #131926;
            --surface: #1b2435;
            --surface-2: #222d41;
            --text: #e6edf9;
            --muted: #9eabc4;
            --line: #2d3a54;
            --primary: #95a4ff;
            --primary-2: #7a89ff;
            --hero-grad: linear-gradient(135deg, #1a2235 0%, #1f2a3a 100%);
            --footer: #0f1522;
            --footer-text: #b5c0d8;
            --shadow: 0 14px 32px rgba(0, 0, 0, 0.32);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 74px;
        }
        main.container { flex: 1; }

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

        .guest-navbar .navbar-brand {
            color: var(--primary-2);
            font-weight: 800;
            letter-spacing: .3px;
        }

        .guest-navbar .nav-link {
            color: var(--text);
            font-weight: 500;
        }

        .theme-toggle {
            border: 1px solid var(--line);
            background: var(--surface);
            color: var(--text);
            border-radius: 10px;
            padding: 7px 12px;
            font-size: 13px;
            line-height: 1;
        }

        .hero {
            padding: 76px 0 48px;
            background: var(--hero-grad);
            border-bottom: 1px solid var(--line);
        }

        .hero-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: var(--shadow);
            padding: 24px;
        }

        .hero-badge {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 99px;
            background: color-mix(in srgb, var(--primary) 14%, var(--surface));
            color: var(--primary-2);
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .hero h1 {
            font-size: clamp(30px, 4vw, 42px);
            font-weight: 800;
            line-height: 1.12;
            margin-bottom: 12px;
            color: var(--text);
        }

        .hero p {
            color: var(--muted);
            margin-bottom: 20px;
        }

        .hero-quick {
            background: var(--surface-2);
            border-radius: 14px;
            border: 1px solid var(--line);
            padding: 12px 14px;
            color: var(--muted);
            font-size: 13px;
        }

        .home-section {
            padding: 28px 0;
        }

        .section-title {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 14px;
            color: var(--text);
        }

        .feature-card, .post-card {
            border: 1px solid var(--line);
            border-radius: 14px;
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .feature-card .card-body,
        .post-card .card-body {
            padding: 16px;
        }

        .feature-card h5,
        .post-card h5 {
            color: var(--text);
            margin-bottom: 10px;
        }
        .post-title-link {
            color: inherit;
            text-decoration: none;
        }
        .post-title-link:hover {
            color: var(--primary-2);
            text-decoration: underline;
        }

        .feature-card p,
        .post-card p,
        .post-meta {
            color: var(--muted);
        }

        .footer {
            background: var(--footer);
            color: var(--footer-text);
            border-top: 1px solid var(--line);
        }

        .btn-hero-primary {
            background: var(--primary);
            color: #fff;
            border: 0;
        }

        .btn-hero-primary:hover { color: #fff; opacity: .92; }

        .btn-hero-secondary {
            border: 1px solid var(--line);
            background: var(--surface);
            color: var(--text);
        }

        .post-empty {
            border: 1px dashed var(--line);
            border-radius: 12px;
            background: var(--surface);
            color: var(--muted);
            padding: 14px;
        }
        .post-thumb {
            width: 100%;
            height: 170px;
            object-fit: cover;
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
            border-bottom: 1px solid var(--line);
        }
        .pager {
            margin-top: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .pager-btn {
            border: 1px solid var(--line);
            background: var(--surface);
            color: var(--text);
            border-radius: 8px;
            min-width: 36px;
            height: 34px;
            padding: 0 10px;
            font-size: 13px;
            font-weight: 600;
            transition: .2s ease;
        }
        .pager-btn:hover {
            border-color: var(--primary);
            color: var(--primary-2);
        }
        .pager-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        .pager-btn:disabled {
            opacity: .45;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
<?php
$isLoggedIn = (bool) session()->get('logged_in');
$lt = (string) session()->get('link_token');
$withToken = static function (string $path) use ($lt): string {
    $url = site_url($path);
    if ($lt === '') {
        return $url;
    }
    return $url . (str_contains($url, '?') ? '&' : '?') . '_lt=' . rawurlencode($lt);
};
?>
<?= view('shared/layout/navbar', ['homePage' => true]) ?>

<header id="home" class="hero">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-8">
                <div class="hero-card">
                    <span class="hero-badge">Pelayanan Desa Terintegrasi</span>
                    <h1>Urus Dokumen Warga, Pengaduan, dan Informasi Program dalam Satu Portal</h1>
                    <p>Portal ini memudahkan warga mengakses layanan administrasi desa dengan lebih cepat, terstruktur, dan transparan.</p>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <?php if ($isLoggedIn) : ?>
                            <a class="btn btn-hero-primary" href="<?= $withToken('dashboard') ?>">Lanjut ke Dashboard</a>
                            <a class="btn btn-hero-secondary" href="<?= $withToken('documents') ?>">Buka Pelayanan Dokumen</a>
                        <?php else : ?>
                            <a class="btn btn-hero-primary" href="<?= site_url('login') ?>">Masuk Layanan</a>
                            <a class="btn btn-hero-secondary" href="<?= site_url('register') ?>">Daftar Warga</a>
                        <?php endif; ?>
                    </div>
                    <div class="hero-quick">Layanan utama: surat keterangan, pengantar kependudukan, dokumen pertanahan, perizinan sederhana, dan bantuan sosial.</div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="hero-card">
                    <h6 class="mb-3">Akses Cepat</h6>
                    <div class="d-grid gap-2">
                        <?php if ($isLoggedIn) : ?>
                            <a href="<?= $withToken('dashboard') ?>" class="btn btn-outline-primary">Dashboard Saya</a>
                            <a href="<?= $withToken('documents') ?>" class="btn btn-outline-secondary">Ajukan Dokumen</a>
                        <?php else : ?>
                            <a href="<?= site_url('login') ?>" class="btn btn-outline-primary">Login Warga</a>
                            <a href="<?= site_url('register') ?>" class="btn btn-outline-secondary">Registrasi Akun</a>
                        <?php endif; ?>
                        <a href="#program" class="btn btn-outline-dark">Lihat Program Desa</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="container">
    <section id="fitur" class="home-section">
        <h3 class="section-title">Fitur Pelayanan</h3>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body">
                        <h5>Pelayanan Dokumen</h5>
                        <p class="mb-3">Warga dapat membuat surat berdasarkan profil atau input manual sesuai jenis layanan yang tersedia.</p>
                        <a href="<?= $isLoggedIn ? $withToken('documents') : site_url('login') ?>" class="btn btn-sm btn-primary"><?= $isLoggedIn ? 'Buka Pelayanan Dokumen' : 'Login untuk Mengajukan' ?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body">
                        <h5>Pengaduan Masyarakat</h5>
                        <p class="mb-3">Sampaikan laporan dan aspirasi warga agar bisa ditindaklanjuti secara tertib oleh aparat desa.</p>
                        <a href="<?= $isLoggedIn ? $withToken('complaints') : site_url('login') ?>" class="btn btn-sm btn-primary"><?= $isLoggedIn ? 'Buka Pengaduan Warga' : 'Login untuk Melapor' ?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body">
                        <h5>Informasi Program</h5>
                        <p class="mb-3">Publikasi kegiatan dan agenda pembangunan desa agar warga selalu mendapat informasi terbaru.</p>
                        <a href="#program" class="btn btn-sm btn-outline-primary">Lihat Program</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="program" class="home-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="section-title mb-0">Program Desa</h3>
            <small class="text-muted"><?= count($programs) ?> program</small>
        </div>

        <?php if ($programs === []) : ?>
            <div class="post-empty">Belum ada postingan program desa.</div>
        <?php endif; ?>

        <div class="row g-3" data-paginated-list="program" data-page-size="6">
            <?php foreach ($programs as $post) : ?>
                <div class="col-md-6 col-lg-4" data-page-item>
                    <article class="card post-card h-100">
                        <img
                            src="<?= base_url(! empty($post['image_path']) ? $post['image_path'] : 'assets/images/card-image.png') ?>"
                            alt="<?= esc($post['title']) ?>"
                            class="post-thumb"
                            loading="lazy"
                        >
                        <div class="card-body d-flex flex-column">
                            <h5><a class="post-title-link" href="<?= site_url('program/' . $post['slug']) ?>"><?= esc($post['title']) ?></a></h5>
                            <p class="post-meta mb-2"><span class="badge bg-primary">Program</span></p>
                            <p class="post-meta mb-2">Dipublikasikan: <?= esc(date('d M Y H:i', strtotime((string) $post['published_at']))) ?></p>
                            <p class="mb-3"><?= esc($post['excerpt'] ?: mb_strimwidth(strip_tags((string) $post['content']), 0, 140, '...')) ?></p>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="pager" data-pager="program"></div>
    </section>

    <section id="artikel" class="home-section pt-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="section-title mb-0">Artikel Terbaru</h3>
            <small class="text-muted"><?= count($articles) ?> artikel</small>
        </div>

        <?php if ($articles === []) : ?>
            <div class="post-empty">Belum ada postingan artikel desa.</div>
        <?php endif; ?>

        <div class="row g-3" data-paginated-list="artikel" data-page-size="6">
            <?php foreach ($articles as $post) : ?>
                <div class="col-md-6 col-lg-4" data-page-item>
                    <article class="card post-card h-100">
                        <img
                            src="<?= base_url(! empty($post['image_path']) ? $post['image_path'] : 'assets/images/card-image.png') ?>"
                            alt="<?= esc($post['title']) ?>"
                            class="post-thumb"
                            loading="lazy"
                        >
                        <div class="card-body d-flex flex-column">
                            <h5><a class="post-title-link" href="<?= site_url('program/' . $post['slug']) ?>"><?= esc($post['title']) ?></a></h5>
                            <p class="post-meta mb-2"><span class="badge bg-secondary">Artikel</span></p>
                            <p class="post-meta mb-2">Dipublikasikan: <?= esc(date('d M Y H:i', strtotime((string) $post['published_at']))) ?></p>
                            <p class="mb-3"><?= esc($post['excerpt'] ?: mb_strimwidth(strip_tags((string) $post['content']), 0, 140, '...')) ?></p>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="pager" data-pager="artikel"></div>
    </section>

    <section id="kegiatan" class="home-section pt-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="section-title mb-0">Kegiatan Desa</h3>
            <small class="text-muted"><?= count($activities) ?> kegiatan</small>
        </div>

        <?php if ($activities === []) : ?>
            <div class="post-empty">Belum ada postingan kegiatan desa.</div>
        <?php endif; ?>

        <div class="row g-3" data-paginated-list="kegiatan" data-page-size="6">
            <?php foreach ($activities as $post) : ?>
                <div class="col-md-6 col-lg-4" data-page-item>
                    <article class="card post-card h-100">
                        <img
                            src="<?= base_url(! empty($post['image_path']) ? $post['image_path'] : 'assets/images/card-image.png') ?>"
                            alt="<?= esc($post['title']) ?>"
                            class="post-thumb"
                            loading="lazy"
                        >
                        <div class="card-body d-flex flex-column">
                            <h5><a class="post-title-link" href="<?= site_url('program/' . $post['slug']) ?>"><?= esc($post['title']) ?></a></h5>
                            <p class="post-meta mb-2"><span class="badge bg-info">Kegiatan</span></p>
                            <p class="post-meta mb-2">Dipublikasikan: <?= esc(date('d M Y H:i', strtotime((string) $post['published_at']))) ?></p>
                            <p class="mb-3"><?= esc($post['excerpt'] ?: mb_strimwidth(strip_tags((string) $post['content']), 0, 140, '...')) ?></p>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="pager" data-pager="kegiatan"></div>
    </section>
</main>

<?= view('shared/layout/footer') ?>

<script>
    (function () {
        var root = document.documentElement;
        var btn = document.getElementById('themeToggle');
        var stored = localStorage.getItem('landing-theme');
        var mode = stored === 'dark' ? 'dark' : 'light';

        function applyTheme(value) {
            root.setAttribute('data-theme', value);
            if (btn) {
                btn.textContent = value === 'dark' ? 'Light Mode' : 'Dark Mode';
            }
        }

        applyTheme(mode);

        if (btn) {
            btn.addEventListener('click', function () {
                mode = mode === 'dark' ? 'light' : 'dark';
                localStorage.setItem('landing-theme', mode);
                applyTheme(mode);
            });
        }
    })();

    (function () {
        var lists = document.querySelectorAll('[data-paginated-list]');
        if (!lists.length) {
            return;
        }

        function createButton(label, disabled, active, onClick) {
            var button = document.createElement('button');
            button.type = 'button';
            button.className = 'pager-btn' + (active ? ' active' : '');
            button.textContent = label;
            button.disabled = !!disabled;
            button.addEventListener('click', onClick);
            return button;
        }

        lists.forEach(function (list) {
            var key = list.getAttribute('data-paginated-list');
            var pager = document.querySelector('[data-pager="' + key + '"]');
            var items = Array.prototype.slice.call(list.querySelectorAll('[data-page-item]'));
            var pageSize = parseInt(list.getAttribute('data-page-size') || '6', 10);
            var totalPages = Math.max(1, Math.ceil(items.length / pageSize));
            var currentPage = 1;

            function renderPage() {
                var start = (currentPage - 1) * pageSize;
                var end = start + pageSize;

                items.forEach(function (item, index) {
                    item.style.display = (index >= start && index < end) ? '' : 'none';
                });

                if (!pager) {
                    return;
                }

                pager.innerHTML = '';
                if (totalPages <= 1) {
                    pager.style.display = 'none';
                    return;
                }

                pager.style.display = 'flex';
                pager.appendChild(createButton('Prev', currentPage === 1, false, function () {
                    if (currentPage > 1) {
                        currentPage -= 1;
                        renderPage();
                    }
                }));

                for (var i = 1; i <= totalPages; i += 1) {
                    (function (pageNum) {
                        pager.appendChild(createButton(String(pageNum), false, currentPage === pageNum, function () {
                            currentPage = pageNum;
                            renderPage();
                        }));
                    })(i);
                }

                pager.appendChild(createButton('Next', currentPage === totalPages, false, function () {
                    if (currentPage < totalPages) {
                        currentPage += 1;
                        renderPage();
                    }
                }));
            }

            renderPage();
        });
    })();
</script>
<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>" defer></script>
<script src="<?= base_url('assets/js/app-lite.js') ?>" defer></script>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'Portal Desa',
    'url' => site_url('/'),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
</script>
</body>
</html>
