<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-1">Isi Data Manual</h5>
                <p class="text-muted mb-3"><?= esc($docTypeLabel) ?></p>

                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <div><?= esc($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= site_url('documents/store-manual/' . $docTypeKey) ?>">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control" value="<?= old('nik') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="birth_place" class="form-control" value="<?= old('birth_place') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control" value="<?= old('birth_date') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-select" required>
                                <option value="">Pilih</option>
                                <option value="Laki-laki" <?= old('gender') === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= old('gender') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pekerjaan</label>
                            <input type="text" name="occupation" class="form-control" value="<?= old('occupation') ?>" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" rows="3" required><?= old('address') ?></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Desa</label>
                            <input type="text" name="village" class="form-control" value="<?= old('village') ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="district" class="form-control" value="<?= old('district') ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kabupaten/Kota</label>
                            <input type="text" name="city" class="form-control" value="<?= old('city') ?>" required>
                        </div>
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

