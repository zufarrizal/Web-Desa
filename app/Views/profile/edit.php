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
                            <input type="text" name="name" class="form-control js-capitalize-case" value="<?= old('name', $user['name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= old('email', $user['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. KK</label>
                            <input type="text" name="no_kk" class="form-control" value="<?= old('no_kk', $user['no_kk'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control" value="<?= old('nik', $user['nik'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pekerjaan</label>
                            <input type="text" name="occupation" class="form-control js-capitalize-case" value="<?= old('occupation', $user['occupation'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="birth_place" class="form-control js-capitalize-case" value="<?= old('birth_place', $user['birth_place'] ?? '') ?>" required>
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
                            <input type="text" name="religion" class="form-control js-capitalize-case" value="<?= old('religion', $user['religion'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Perkawinan</label>
                            <?php $maritalStatus = old('marital_status', $user['marital_status'] ?? ''); ?>
                            <select name="marital_status" class="form-control js-capitalize-case">
                                <option value="">Pilih</option>
                                <option value="Belum Kawin" <?= $maritalStatus === 'Belum Kawin' ? 'selected' : '' ?>>Belum Kawin</option>
                                <option value="Kawin" <?= $maritalStatus === 'Kawin' ? 'selected' : '' ?>>Kawin</option>
                                <option value="Cerai Hidup" <?= $maritalStatus === 'Cerai Hidup' ? 'selected' : '' ?>>Cerai Hidup</option>
                                <option value="Cerai Mati" <?= $maritalStatus === 'Cerai Mati' ? 'selected' : '' ?>>Cerai Mati</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kewarganegaraan</label>
                            <?php $citizenship = old('citizenship', $user['citizenship'] ?? 'WNI'); ?>
                            <select name="citizenship" class="form-control">
                                <option value="WNI" <?= $citizenship === 'WNI' ? 'selected' : '' ?>>WNI</option>
                                <option value="WNA" <?= $citizenship === 'WNA' ? 'selected' : '' ?>>WNA</option>
                            </select>
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
                            <input type="text" name="village" class="form-control js-capitalize-case" value="<?= old('village', $user['village'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="district" class="form-control js-capitalize-case" value="<?= old('district', $user['district'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kabupaten/Kota</label>
                            <input type="text" name="city" class="form-control js-capitalize-case" value="<?= old('city', $user['city'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="province" class="form-control js-capitalize-case" value="<?= old('province', $user['province'] ?? '') ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="address" class="form-control js-capitalize-case" rows="3" required><?= old('address', $user['address'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Profil</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function toCapitalizedCase(value) {
        return value.replace(/\w\S*/g, function (txt) {
            return txt.charAt(0).toUpperCase() + txt.slice(1).toLowerCase();
        });
    }

    var fields = document.querySelectorAll('.js-capitalize-case');
    fields.forEach(function (field) {
        var handler = function () {
            if (field.tagName === 'SELECT') {
                return;
            }
            field.value = toCapitalizedCase(field.value);
        };
        field.addEventListener('blur', handler);
        field.addEventListener('change', handler);
    });
});
</script>
<?= $this->endSection() ?>
