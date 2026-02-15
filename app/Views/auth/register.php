<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title>Registrasi Warga - Portal Desa</title>

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
                            <p>Registrasi Akun Warga</p>
                            <p>Buat akun untuk mengakses layanan masyarakat desa.</p>
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

                        <form method="post" action="<?= site_url('register') ?>" autocomplete="off">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Nama Lengkap" value="<?= old('name') ?>" autocomplete="off" required>
                                    <label for="name">Nama Lengkap</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" value="" autocomplete="off" autocapitalize="off" spellcheck="false" required>
                                    <label for="email">Email</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" autocomplete="new-password" required>
                                    <label for="password">Password</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="password" name="password_confirm" class="form-control" id="password_confirm" placeholder="Konfirmasi Password" autocomplete="new-password" required>
                                    <label for="password_confirm">Konfirmasi Password</label>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary m-b-xs">Daftar</button>
                            </div>
                        </form>

                        <div class="authent-reg mt-3">
                            <p>Sudah punya akun? <a href="<?= site_url('login') ?>">Login di sini</a></p>
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


