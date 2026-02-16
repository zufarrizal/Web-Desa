<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
    $birthDateRaw = (string) ($user['birth_date'] ?? '');
    $birthTs = $birthDateRaw !== '' ? strtotime($birthDateRaw) : false;
    $birthDateDisplay = $birthTs ? date('d/m/Y', $birthTs) : $birthDateRaw;
?>
<div class="row">
    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Profil Warga</h5>
                <p class="text-muted">Lengkapi data berikut agar surat bisa dibuat otomatis.</p>

                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <div><?= esc($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= site_url('profile') ?>">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="<?= old('name', $user['name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= old('email', $user['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control" value="<?= old('nik', $user['nik'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pekerjaan</label>
                            <input type="text" name="occupation" class="form-control" value="<?= old('occupation', $user['occupation'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="birth_place" class="form-control" value="<?= old('birth_place', $user['birth_place'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="text" name="birth_date" class="form-control" value="<?= old('birth_date', $birthDateDisplay) ?>" placeholder="dd/mm/yyyy" inputmode="numeric" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <?php $gender = old('gender', $user['gender'] ?? ''); ?>
                            <select name="gender" class="form-control" required>
                                <option value="">Pilih</option>
                                <option value="Laki-laki" <?= $gender === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= $gender === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Agama</label>
                            <input type="text" name="religion" class="form-control" value="<?= old('religion', $user['religion'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Perkawinan</label>
                            <input type="text" name="marital_status" class="form-control" value="<?= old('marital_status', $user['marital_status'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kewarganegaraan</label>
                            <input type="text" name="citizenship" class="form-control" value="<?= old('citizenship', $user['citizenship'] ?? 'WNI') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">RT</label>
                            <input type="text" name="rt" class="form-control" value="<?= old('rt', $user['rt'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">RW</label>
                            <input type="text" name="rw" class="form-control" value="<?= old('rw', $user['rw'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Desa/Kelurahan</label>
                            <input type="text" name="village" class="form-control" value="<?= old('village', $user['village'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="district" class="form-control" value="<?= old('district', $user['district'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kabupaten/Kota</label>
                            <input type="text" name="city" class="form-control" value="<?= old('city', $user['city'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="province" class="form-control" value="<?= old('province', $user['province'] ?? '') ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="3" required><?= old('address', $user['address'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Profil</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
