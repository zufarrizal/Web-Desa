<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Pengaturan Kop Surat Desa</h5>

                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <div><?= esc($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= site_url('documents/settings') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Kabupaten</label>
                        <input type="text" name="regency_name" class="form-control" value="<?= old('regency_name', $setting['regency_name'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kecamatan</label>
                        <input type="text" name="subdistrict_name" class="form-control" value="<?= old('subdistrict_name', $setting['subdistrict_name'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Desa</label>
                        <input type="text" name="village_name" class="form-control" value="<?= old('village_name', $setting['village_name'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Kantor (contoh: Jl. [Nama Jalan/Alamat Lengkap Kantor Desa])</label>
                        <textarea name="office_address" rows="4" class="form-control" required><?= old('office_address', $setting['office_address'] ?? ($setting['letterhead_address'] ?? '')) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon Aplikasi Header (feather icon name, contoh: home, shield, users, file-text)</label>
                        <input type="text" name="app_icon" class="form-control" value="<?= old('app_icon', $setting['app_icon'] ?? 'home') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan Penandatangan Surat</label>
                        <input type="text" name="signer_title" class="form-control" value="<?= old('signer_title', $setting['signer_title'] ?? 'Kepala Desa') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Penandatangan Surat</label>
                        <input type="text" name="signer_name" class="form-control" value="<?= old('signer_name', $setting['signer_name'] ?? 'Nama Kepala Desa') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Tanda Tangan (PNG/JPG, maks 2MB)</label>
                        <input type="file" name="signer_signature" class="form-control" accept=".png,.jpg,.jpeg,image/png,image/jpeg">
                        <?php if (! empty($setting['signer_signature'])) : ?>
                            <div class="mt-2">
                                <img src="<?= base_url($setting['signer_signature']) ?>" alt="Tanda tangan" style="max-height: 80px; width: auto;">
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" value="1" id="removeSignature" name="remove_signer_signature">
                                <label class="form-check-label" for="removeSignature">Hapus tanda tangan saat ini</label>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
