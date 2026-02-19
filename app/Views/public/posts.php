<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    $titleText = 'Semua Postingan Desa';
    if ($type === 'program') {
        $titleText = 'Semua Program Desa';
    } elseif ($type === 'artikel') {
        $titleText = 'Semua Artikel Desa';
    } elseif ($type === 'kegiatan') {
        $titleText = 'Semua Kegiatan Desa';
    } elseif ($type === 'pengumuman') {
        $titleText = 'Semua Pengumuman Desa';
    }

    $isType = static function (string $current, string $value): bool {
        if ($value === '') {
            return $current === '';
        }
        return $current === $value;
    };
    ?>
    <title><?= esc($titleText) ?> - Portal Desa</title>
    <link rel="icon" type="image/svg+xml" href="<?= asset_url('favicon.svg') ?>">
    <link rel="alternate icon" href="<?= asset_url('assets/images/logo@2x.png') ?>">
    <meta name="description" content="<?= esc($titleText) ?> terbaru dari Portal Desa.">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="<?= esc(site_url('postingan' . ($type ? '?type=' . $type : ''))) ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="Portal Desa">
    <meta property="og:title" content="<?= esc($titleText) ?> - Portal Desa">
    <meta property="og:description" content="<?= esc($titleText) ?> terbaru dari Portal Desa.">
    <meta property="og:url" content="<?= esc(site_url('postingan' . ($type ? '?type=' . $type : ''))) ?>">
    <meta property="og:image" content="<?= esc(asset_url('assets/images/card-image.png')) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($titleText) ?> - Portal Desa">
    <meta name="twitter:description" content="<?= esc($titleText) ?> terbaru dari Portal Desa.">
    <meta name="twitter:image" content="<?= esc(asset_url('assets/images/card-image.png')) ?>">
    <link rel="preload" href="<?= asset_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" as="style">
    <link rel="preload" href="<?= asset_url('assets/css/public-posts.css') ?>" as="style">
    <link href="<?= asset_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset_url('assets/css/public-posts.css') ?>" rel="stylesheet">
    <style>body{font-family:system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;}</style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top main-nav">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= site_url('/') ?>">Portal Desa</a>
        <div class="d-flex gap-2">
            <a href="<?= site_url('postingan') ?>" class="btn btn-sm <?= $isType($type, '') ? 'btn-secondary' : 'btn-outline-secondary' ?>">Semua</a>
            <a href="<?= site_url('postingan?type=program') ?>" class="btn btn-sm <?= $isType($type, 'program') ? 'btn-primary' : 'btn-outline-primary' ?>">Program</a>
            <a href="<?= site_url('postingan?type=artikel') ?>" class="btn btn-sm <?= $isType($type, 'artikel') ? 'btn-primary' : 'btn-outline-primary' ?>">Artikel</a>
            <a href="<?= site_url('postingan?type=kegiatan') ?>" class="btn btn-sm <?= $isType($type, 'kegiatan') ? 'btn-info' : 'btn-outline-info' ?>">Kegiatan</a>
            <a href="<?= site_url('postingan?type=pengumuman') ?>" class="btn btn-sm <?= $isType($type, 'pengumuman') ? 'btn-warning' : 'btn-outline-warning' ?>">Pengumuman</a>
        </div>
    </div>
</nav>

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0"><?= esc($titleText) ?></h1>
        <a href="<?= site_url('/') ?>" class="btn btn-sm btn-light">Kembali</a>
    </div>
    <?php if ($posts === []) : ?>
        <div class="alert alert-light border">Belum ada postingan untuk kategori ini.</div>
    <?php endif; ?>
    <div class="row g-3">
        <?php foreach ($posts as $post) : ?>
            <div class="col-md-6 col-lg-4">
                <article class="post-card h-100">
                    <img class="post-thumb" src="<?= ! empty($post['image_path']) ? base_url($post['image_path']) : asset_url('assets/images/card-image.png') ?>" alt="<?= esc($post['title']) ?>">
                    <div class="p-3 d-flex flex-column h-100">
                        <h2 class="h6"><a href="<?= site_url('program/' . $post['slug']) ?>" class="text-decoration-none"><?= esc($post['title']) ?></a></h2>
                        <p class="mb-2"><span class="badge bg-primary"><?= esc(ucfirst((string) ($post['post_type'] ?? 'artikel'))) ?></span></p>
                        <p class="text-muted small mb-2"><?= esc(date('d M Y H:i', strtotime((string) $post['published_at']))) ?></p>
                        <p class="small mb-3"><?= esc($post['excerpt'] ?: mb_strimwidth(strip_tags((string) $post['content']), 0, 120, '...')) ?></p>
                        <a href="<?= site_url('program/' . $post['slug']) ?>" class="btn btn-sm btn-outline-primary mt-auto">Baca selengkapnya</a>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
</main>
<?= view('partials/site_footer') ?>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => $titleText,
    'url' => site_url('postingan' . ($type ? '?type=' . $type : '')),
    'numberOfItems' => count($posts),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
</script>
</body>
</html>
