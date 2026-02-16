<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $metaTitle = 'Reset Password - Portal Desa';
    $metaDescription = 'Atur password baru untuk akun Portal Desa Anda.';
    $canonicalUrl = site_url('reset-password/' . $token);
    $metaImage = base_url('assets/images/card-image.png');
    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= esc($metaDescription) ?>">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <link rel="canonical" href="<?= esc($canonicalUrl) ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Portal Desa">
    <meta property="og:title" content="<?= esc($metaTitle) ?>">
    <meta property="og:description" content="<?= esc($metaDescription) ?>">
    <meta property="og:url" content="<?= esc($canonicalUrl) ?>">
    <meta property="og:image" content="<?= esc($metaImage) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($metaTitle) ?>">
    <meta name="twitter:description" content="<?= esc($metaDescription) ?>">
    <meta name="twitter:image" content="<?= esc($metaImage) ?>">
    <title><?= esc($metaTitle) ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>">
    <link rel="alternate icon" href="<?= base_url('assets/images/logo@2x.png') ?>">

    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/font-awesome/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/perfectscroll/perfect-scrollbar.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/main.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">
    <style>
        body.login-page { min-height: 100vh; display: flex; flex-direction: column; }
        body.login-page > .container { flex: 1; display: flex; align-items: center; justify-content: center; }
        body.login-page > .container > .row { width: 100%; margin-left: 0; margin-right: 0; }
    </style>
</head>
<body class="login-page">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-12 col-lg-4">
                <div class="card login-box-container">
                    <div class="card-body">
                        <div class="authent-logo">
                            <img src="<?= base_url('assets/images/logo@2x.png') ?>" alt="Logo">
                        </div>
                        <div class="authent-text">
                            <p>Reset Password</p>
                            <p>Masukkan password baru Anda.</p>
                        </div>
                        <div class="mb-3">
                            <a href="<?= site_url('/') ?>" class="btn btn-sm btn-light">Kembali ke Halaman Utama</a>
                        </div>

                        <?php if (session()->getFlashdata('error')) : ?>
                            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('errors')) : ?>
                            <div class="alert alert-danger mb-3">
                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                    <div><?= esc($error) ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= site_url('reset-password/' . $token) ?>" autocomplete="off">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Password Baru" autocomplete="new-password" required>
                                    <label for="password">Password Baru</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="password" name="password_confirm" class="form-control" id="password_confirm" placeholder="Konfirmasi Password" autocomplete="new-password" required>
                                    <label for="password_confirm">Konfirmasi Password</label>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary m-b-xs">Simpan Password Baru</button>
                            </div>
                        </form>

                        <div class="authent-reg mt-3">
                            <p><a href="<?= site_url('login') ?>">Kembali ke Login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>" defer></script>
    <script src="<?= base_url('assets/js/app-lite.js') ?>" defer></script>
</body>
</html>


