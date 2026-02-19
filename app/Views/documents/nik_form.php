<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-7 col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-1">Buat Surat Berdasarkan NIK</h5>
                <p class="text-muted mb-3"><?= esc($docTypeLabel) ?></p>

                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <div><?= esc($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger"><?= esc((string) session()->getFlashdata('error')) ?></div>
                <?php endif; ?>

                <form method="post" action="<?= site_url('documents/store-by-nik/' . $docTypeKey) ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">NIK Warga</label>
                        <input type="text" name="nik" class="form-control" value="<?= old('nik') ?>" required>
                        <small class="text-muted">Masukkan NIK warga yang sudah terdaftar, lalu sistem akan mengambil data profil otomatis.</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Buat Surat</button>
                        <a href="<?= site_url('documents') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
