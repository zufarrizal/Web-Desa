<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php $safe = static fn($value): string => $value === null || $value === '' ? '-' : (string) $value; ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Kelola User</h5>
                    <a href="<?= site_url('users/create') ?>" class="btn btn-primary">Tambah User</a>
                </div>
                <div class="table-responsive">
                    <table class="display js-zero-conf-table" id="usersTable" style="width:100%">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. KK</th>
                            <th>NIK</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $index => $user) : ?>
                            <tr>
                                <td data-row-no><?= esc((string) ($index + 1)) ?></td>
                                <td><?= esc($safe($user['name'] ?? null)) ?></td>
                                <td><?= esc($safe($user['email'] ?? null)) ?></td>
                                <td><?= esc($safe($user['no_kk'] ?? null)) ?></td>
                                <td><?= esc($safe($user['nik'] ?? null)) ?></td>
                                <td><span class="badge bg-info"><?= esc($safe($user['role'] ?? 'user')) ?></span></td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-info btn-user-detail"
                                        data-bs-toggle="modal"
                                        data-bs-target="#userDetailModal"
                                        data-name="<?= esc($safe($user['name'] ?? null), 'attr') ?>"
                                        data-email="<?= esc($safe($user['email'] ?? null), 'attr') ?>"
                                        data-role="<?= esc($safe($user['role'] ?? null), 'attr') ?>"
                                        data-no-kk="<?= esc($safe($user['no_kk'] ?? null), 'attr') ?>"
                                        data-nik="<?= esc($safe($user['nik'] ?? null), 'attr') ?>"
                                        data-birth-place="<?= esc($safe($user['birth_place'] ?? null), 'attr') ?>"
                                        data-birth-date="<?= esc($safe($user['birth_date'] ?? null), 'attr') ?>"
                                        data-gender="<?= esc($safe($user['gender'] ?? null), 'attr') ?>"
                                        data-religion="<?= esc($safe($user['religion'] ?? null), 'attr') ?>"
                                        data-occupation="<?= esc($safe($user['occupation'] ?? null), 'attr') ?>"
                                        data-marital-status="<?= esc($safe($user['marital_status'] ?? null), 'attr') ?>"
                                        data-citizenship="<?= esc($safe($user['citizenship'] ?? null), 'attr') ?>"
                                        data-rt="<?= esc($safe($user['rt'] ?? null), 'attr') ?>"
                                        data-rw="<?= esc($safe($user['rw'] ?? null), 'attr') ?>"
                                        data-village="<?= esc($safe($user['village'] ?? null), 'attr') ?>"
                                        data-district="<?= esc($safe($user['district'] ?? null), 'attr') ?>"
                                        data-city="<?= esc($safe($user['city'] ?? null), 'attr') ?>"
                                        data-province="<?= esc($safe($user['province'] ?? null), 'attr') ?>"
                                        data-address="<?= esc($safe($user['address'] ?? null), 'attr') ?>"
                                    >Detail</button>
                                    <a href="<?= site_url('users/edit/' . $user['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="<?= site_url('users/delete/' . $user['id']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger" data-confirm="Hapus user ini?">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailLabel">Detail User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><strong>Nama:</strong><br><span data-detail="name">-</span></div>
                    <div class="col-md-6"><strong>Email:</strong><br><span data-detail="email">-</span></div>
                    <div class="col-md-6"><strong>Role:</strong><br><span data-detail="role">-</span></div>
                    <div class="col-md-6"><strong>No. KK:</strong><br><span data-detail="no-kk">-</span></div>
                    <div class="col-md-6"><strong>NIK:</strong><br><span data-detail="nik">-</span></div>
                    <div class="col-md-6"><strong>Tempat Lahir:</strong><br><span data-detail="birth-place">-</span></div>
                    <div class="col-md-6"><strong>Tanggal Lahir:</strong><br><span data-detail="birth-date">-</span></div>
                    <div class="col-md-6"><strong>Jenis Kelamin:</strong><br><span data-detail="gender">-</span></div>
                    <div class="col-md-6"><strong>Agama:</strong><br><span data-detail="religion">-</span></div>
                    <div class="col-md-6"><strong>Pekerjaan:</strong><br><span data-detail="occupation">-</span></div>
                    <div class="col-md-6"><strong>Status Perkawinan:</strong><br><span data-detail="marital-status">-</span></div>
                    <div class="col-md-6"><strong>Kewarganegaraan:</strong><br><span data-detail="citizenship">-</span></div>
                    <div class="col-md-6"><strong>RT:</strong><br><span data-detail="rt">-</span></div>
                    <div class="col-md-6"><strong>RW:</strong><br><span data-detail="rw">-</span></div>
                    <div class="col-md-6"><strong>Desa/Kelurahan:</strong><br><span data-detail="village">-</span></div>
                    <div class="col-md-6"><strong>Kecamatan:</strong><br><span data-detail="district">-</span></div>
                    <div class="col-md-6"><strong>Kabupaten/Kota:</strong><br><span data-detail="city">-</span></div>
                    <div class="col-md-6"><strong>Provinsi:</strong><br><span data-detail="province">-</span></div>
                    <div class="col-md-12"><strong>Alamat:</strong><br><span data-detail="address">-</span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var buttons = document.querySelectorAll('.btn-user-detail');
    if (!buttons.length) {
        return;
    }

    function setDetailValue(key, value) {
        var target = document.querySelector('[data-detail="' + key + '"]');
        if (!target) {
            return;
        }
        target.textContent = value && value !== '' ? value : '-';
    }

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            var data = button.dataset || {};
            setDetailValue('name', data.name);
            setDetailValue('email', data.email);
            setDetailValue('role', data.role);
            setDetailValue('no-kk', data.noKk);
            setDetailValue('nik', data.nik);
            setDetailValue('birth-place', data.birthPlace);
            setDetailValue('birth-date', data.birthDate);
            setDetailValue('gender', data.gender);
            setDetailValue('religion', data.religion);
            setDetailValue('occupation', data.occupation);
            setDetailValue('marital-status', data.maritalStatus);
            setDetailValue('citizenship', data.citizenship);
            setDetailValue('rt', data.rt);
            setDetailValue('rw', data.rw);
            setDetailValue('village', data.village);
            setDetailValue('district', data.district);
            setDetailValue('city', data.city);
            setDetailValue('province', data.province);
            setDetailValue('address', data.address);
        });
    });

});
</script>
<?= $this->endSection() ?>
