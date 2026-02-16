<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Pengaturan Halaman Utama</h5>

                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <div><?= esc($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= site_url('settings/home') ?>">
                    <?= csrf_field() ?>

                    <h6 class="mb-3">Profil Desa</h6>
                    <div class="mb-3">
                        <label class="form-label">Judul Profil Desa</label>
                        <input type="text" name="village_profile_title" class="form-control" value="<?= old('village_profile_title', $setting['village_profile_title'] ?? 'Profil Desa') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Profil Desa</label>
                        <textarea name="village_profile_content" rows="5" class="form-control"><?= old('village_profile_content', $setting['village_profile_content'] ?? '') ?></textarea>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3">Kontak Desa</h6>
                    <div class="mb-3">
                        <label class="form-label">Nama Kontak PIC</label>
                        <input type="text" name="contact_person" class="form-control" value="<?= old('contact_person', $setting['contact_person'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telepon Kontak</label>
                        <input type="text" name="contact_phone" class="form-control" value="<?= old('contact_phone', $setting['contact_phone'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Kontak</label>
                        <input type="email" name="contact_email" class="form-control" value="<?= old('contact_email', $setting['contact_email'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">WhatsApp Kontak</label>
                        <input type="text" name="contact_whatsapp" class="form-control" value="<?= old('contact_whatsapp', $setting['contact_whatsapp'] ?? '') ?>">
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3">Pengaduan</h6>
                    <div class="mb-3">
                        <label class="form-label">Informasi Pengaduan</label>
                        <textarea name="complaint_info" rows="5" class="form-control"><?= old('complaint_info', $setting['complaint_info'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Pengaturan Halaman Utama</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

