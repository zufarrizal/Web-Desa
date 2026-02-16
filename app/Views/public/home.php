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
    <title>Portal Desa - Pelayanan Administrasi</title>
    <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>">
    <link rel="alternate icon" href="<?= base_url('assets/images/logo@2x.png') ?>">
    <meta name="description" content="Portal Desa untuk layanan administrasi warga, pengaduan masyarakat, artikel, dan kegiatan desa.">
    <meta name="keywords" content="portal desa, pelayanan desa, surat desa, kegiatan desa, artikel desa">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="<?= esc(site_url('/')) ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
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
    <link href="<?= base_url('assets/css/public-theme.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/public-home.css') ?>" rel="stylesheet">
</head>
<body>
<?php
$isLoggedIn = (bool) session()->get('logged_in');
$lt = (string) session()->get('link_token');
$setting = $setting ?? [];
$withToken = static function (string $path) use ($lt): string {
    $url = site_url($path);
    if ($lt === '') {
        return $url;
    }
    return $url . (str_contains($url, '?') ? '&' : '?') . '_lt=' . rawurlencode($lt);
};

$profileTitle = trim((string) ($setting['village_profile_title'] ?? '')) !== '' ? (string) $setting['village_profile_title'] : 'Profil Desa';
$profileContent = trim((string) ($setting['village_profile_content'] ?? '')) !== '' ? (string) $setting['village_profile_content'] : 'Profil desa belum diatur oleh admin.';
$contactPerson = (string) ($setting['contact_person'] ?? '-');
$contactPhone = (string) ($setting['contact_phone'] ?? '-');
$contactEmail = (string) ($setting['contact_email'] ?? '-');
$contactWhatsapp = (string) ($setting['contact_whatsapp'] ?? '-');
$complaintInfo = trim((string) ($setting['complaint_info'] ?? '')) !== '' ? (string) $setting['complaint_info'] : 'Sampaikan pengaduan warga dengan jelas agar tim desa dapat menindaklanjuti dengan cepat.';
$announcements = $announcements ?? [];
$officeAddressRaw = trim((string) ($setting['office_address'] ?? ''));
$officeAddress = $officeAddressRaw !== '' ? $officeAddressRaw : '-';
$officeMapPlusCodeRaw = trim((string) ($setting['office_map_plus_code'] ?? ''));
$officeMapPlusCode = trim((string) preg_replace('/\s+/', ' ', strip_tags($officeMapPlusCodeRaw)));
$hasPlusCodeMap = $officeMapPlusCode !== '' && $officeMapPlusCode !== '-';
$officeMapName = '';
if ($hasPlusCodeMap && str_contains($officeMapPlusCode, ',')) {
    $plusCodeParts = explode(',', $officeMapPlusCode, 2);
    $officeMapName = trim((string) ($plusCodeParts[1] ?? ''));
}
$officeLocationTitle = $officeMapName !== '' ? $officeMapName : $officeAddress;
$officeAddressForMap = trim((string) preg_replace('/\s+/', ' ', strip_tags($officeAddressRaw)));
$hasAddressMap = $officeAddressForMap !== '' && $officeAddressForMap !== '-';
$hasOfficeMap = $hasPlusCodeMap || $hasAddressMap;
$officeMapQuery = $hasPlusCodeMap ? $officeMapPlusCode : $officeAddressForMap;
$officeMapEmbedUrl = $hasOfficeMap ? 'https://www.google.com/maps?q=' . rawurlencode($officeMapQuery) . '&output=embed' : '';
$officeMapOpenUrl = $hasOfficeMap ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($officeMapQuery) : '';
?>
<?= view('shared/layout/navbar', ['homePage' => true]) ?>

<header id="home" class="hero">
    <div class="container">
        <h2 class="hero-village-title">Pemerintah Desa <?= esc((string) ($villageName ?? 'Desa')) ?></h2>
        <div class="row g-4 align-items-center">
            <div class="col-lg-8">
                <div class="hero-card">
                    <span class="hero-badge">Pelayanan Desa Terintegrasi</span>
                    <h1>Layanan Administrasi Desa yang Cepat, Jelas, dan Transparan</h1>
                    <p class="hero-lead">Ajukan surat, laporkan pengaduan, dan pantau informasi program desa dari satu portal yang mudah digunakan oleh warga.</p>
                    <div class="d-flex flex-wrap gap-2 mb-3 hero-cta">
                        <?php if ($isLoggedIn) : ?>
                            <a class="btn btn-hero-primary" href="<?= $withToken('dashboard') ?>">Lanjut ke Dashboard</a>
                            <a class="btn btn-hero-secondary" href="<?= $withToken('documents') ?>">Buka Pelayanan Dokumen</a>
                        <?php else : ?>
                            <a class="btn btn-hero-primary" href="<?= site_url('login') ?>">Masuk Layanan</a>
                            <a class="btn btn-hero-secondary" href="<?= site_url('register') ?>">Daftar Warga</a>
                        <?php endif; ?>
                    </div>
                    <div class="hero-quick">
                        <p class="hero-note-title">Layanan utama yang tersedia:</p>
                        <div class="hero-service-grid">
                            <span class="hero-service-item">Surat keterangan warga</span>
                            <span class="hero-service-item">Pengantar kependudukan</span>
                            <span class="hero-service-item">Pengaduan masyarakat</span>
                            <span class="hero-service-item">Informasi program & kegiatan</span>
                        </div>
                    </div>
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
    <section id="profil-desa" class="home-section">
        <h3 class="section-title"><?= esc($profileTitle) ?></h3>
        <div class="post-empty">
            <?= nl2br(esc($profileContent)) ?>
        </div>
    </section>

    <section id="pengumuman" class="home-section pt-0">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h3 class="section-title mb-0">Pengumuman</h3>
            <small class="text-muted"><?= count($announcements) ?> posting</small>
        </div>
        <?php if ($announcements === []) : ?>
            <div class="post-empty">Belum ada postingan pengumuman.</div>
        <?php else : ?>
            <div class="row g-3" data-paginated-list="pengumuman" data-page-size="3">
                <?php foreach ($announcements as $post) : ?>
                    <div class="col-12" data-page-item>
                        <article class="card post-card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5><a class="post-title-link" href="<?= site_url('program/' . $post['slug']) ?>"><?= esc($post['title']) ?></a></h5>
                                <p class="post-meta mb-2"><span class="badge bg-primary">Pengumuman</span></p>
                                <p class="post-meta mb-2">Dipublikasikan: <?= esc(date('d M Y H:i', strtotime((string) $post['published_at']))) ?></p>
                                <p class="mb-0"><?= esc($post['excerpt'] ?: mb_strimwidth(strip_tags((string) $post['content']), 0, 140, '...')) ?></p>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pager" data-pager="pengumuman"></div>
        <?php endif; ?>
    </section>

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
                            <p class="post-meta mb-2"><span class="badge bg-primary">Artikel</span></p>
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
                            <p class="post-meta mb-2"><span class="badge bg-primary">Kegiatan</span></p>
                            <p class="post-meta mb-2">Dipublikasikan: <?= esc(date('d M Y H:i', strtotime((string) $post['published_at']))) ?></p>
                            <p class="mb-3"><?= esc($post['excerpt'] ?: mb_strimwidth(strip_tags((string) $post['content']), 0, 140, '...')) ?></p>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="pager" data-pager="kegiatan"></div>
    </section>

    <section id="kontak" class="home-section pt-0">
        <h3 class="section-title">Kontak Desa</h3>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card feature-card h-100">
                    <div class="card-body">
                        <h5>Kontak Resmi</h5>
                        <p class="mb-2"><strong>PIC:</strong> <?= esc($contactPerson) ?></p>
                        <p class="mb-2"><strong>Telepon:</strong> <?= esc($contactPhone) ?></p>
                        <p class="mb-2"><strong>Email:</strong> <?= esc($contactEmail) ?></p>
                        <p class="mb-0"><strong>WhatsApp:</strong> <?= esc($contactWhatsapp) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card feature-card h-100">
                    <div class="card-body">
                        <h5>Alamat Kantor Desa</h5>
                        <?php if ($officeAddress !== '-' && mb_strtolower($officeAddress) !== mb_strtolower($officeLocationTitle)) : ?>
                            <p class="mb-0"><?= esc($officeAddress) ?></p>
                        <?php endif; ?>
                        <?php if ($hasOfficeMap) : ?>
                            <div class="office-map-wrap mt-3">
                                <iframe
                                    src="<?= esc($officeMapEmbedUrl) ?>"
                                    class="office-map-frame"
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Peta Kantor Desa"
                                    allowfullscreen
                                ></iframe>
                            </div>
                            <a href="<?= esc($officeMapOpenUrl) ?>" class="btn btn-sm btn-outline-primary mt-3" target="_blank" rel="noopener noreferrer">Buka di Google Maps</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="pengaduan" class="home-section pt-0">
        <h3 class="section-title">Pengaduan Warga</h3>
        <div class="card feature-card">
            <div class="card-body">
                <p class="mb-3"><?= nl2br(esc($complaintInfo)) ?></p>
                <a href="<?= $isLoggedIn ? $withToken('complaints') : site_url('login') ?>" class="btn btn-sm btn-primary">
                    <?= $isLoggedIn ? 'Buka Pengaduan Saya' : 'Login untuk Mengadu' ?>
                </a>
            </div>
        </div>
    </section>
</main>

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
