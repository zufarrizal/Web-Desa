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
    }
    ?>
    <title><?= esc($titleText) ?> - Portal Desa</title>
    <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>">
    <link rel="alternate icon" href="<?= base_url('assets/images/logo@2x.png') ?>">
    <meta name="description" content="<?= esc($titleText) ?> terbaru dari Portal Desa.">
    <link rel="canonical" href="<?= esc(site_url('postingan' . ($type ? '?type=' . $type : ''))) ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Portal Desa">
    <meta property="og:title" content="<?= esc($titleText) ?> - Portal Desa">
    <meta property="og:description" content="<?= esc($titleText) ?> terbaru dari Portal Desa.">
    <meta property="og:url" content="<?= esc(site_url('postingan' . ($type ? '?type=' . $type : ''))) ?>">
    <meta property="og:image" content="<?= esc(base_url('assets/images/card-image.png')) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($titleText) ?> - Portal Desa">
    <meta name="twitter:description" content="<?= esc($titleText) ?> terbaru dari Portal Desa.">
    <meta name="twitter:image" content="<?= esc(base_url('assets/images/card-image.png')) ?>">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f6f9; color: #111827; min-height: 100vh; display: flex; flex-direction: column; }
        main.container { flex: 1; }
        .main-nav { background: rgba(255,255,255,.95); border-bottom: 1px solid #e5e7eb; }
        .post-card { border: 1px solid #e7ebf3; border-radius: 14px; background: #fff; }
        .post-thumb { width: 100%; height: 180px; object-fit: cover; border-top-left-radius: 14px; border-top-right-radius: 14px; }
        .footer { background: #1f2937; color: #dbe4f5; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top main-nav">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= site_url('/') ?>">Portal Desa</a>
        <div class="d-flex gap-2">
            <a href="<?= site_url('postingan') ?>" class="btn btn-sm btn-outline-secondary">Semua</a>
            <a href="<?= site_url('postingan?type=program') ?>" class="btn btn-sm btn-outline-primary">Program</a>
            <a href="<?= site_url('postingan?type=artikel') ?>" class="btn btn-sm btn-outline-primary">Artikel</a>
            <a href="<?= site_url('postingan?type=kegiatan') ?>" class="btn btn-sm btn-outline-info">Kegiatan</a>
        </div>
    </div>
</nav>

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0"><?= esc($titleText) ?></h1>
        <a href="<?= site_url('/') ?>" class="btn btn-sm btn-light">Kembali</a>
    </div>
    <div class="row g-3">
        <?php foreach ($posts as $post) : ?>
            <div class="col-md-6 col-lg-4">
                <article class="post-card h-100">
                    <img class="post-thumb" src="<?= base_url(! empty($post['image_path']) ? $post['image_path'] : 'assets/images/card-image.png') ?>" alt="<?= esc($post['title']) ?>">
                    <div class="p-3 d-flex flex-column h-100">
                        <h2 class="h6"><?= esc($post['title']) ?></h2>
                        <p class="mb-2"><span class="badge bg-secondary"><?= esc(ucfirst((string) ($post['post_type'] ?? 'artikel'))) ?></span></p>
                        <p class="text-muted small mb-2"><?= esc(date('d M Y H:i', strtotime((string) $post['published_at']))) ?></p>
                        <p class="small mb-3"><?= esc($post['excerpt'] ?: mb_strimwidth(strip_tags((string) $post['content']), 0, 120, '...')) ?></p>
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
