<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Selamat Datang, <?= esc($name) ?></h5>
                <p class="mb-1">Email: <strong><?= esc($email) ?></strong></p>
                <p class="mb-0">Role: <span class="badge bg-primary"><?= esc($role) ?></span></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card stat-widget">
            <div class="card-body">
                <h5 class="card-title">Pelayanan Dokumen</h5>
                <h2><?= esc((string) $documentCount) ?></h2>
                <p><?= $role === 'admin' ? 'Semua pengajuan dokumen' : 'Pengajuan dokumen Anda' ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-widget">
            <div class="card-body">
                <h5 class="card-title">Pengaduan Warga</h5>
                <h2><?= esc((string) $complaintCount) ?></h2>
                <p><?= $role === 'admin' ? 'Semua pengaduan warga' : 'Pengaduan Anda' ?></p>
            </div>
        </div>
    </div>
    <?php if ($role === 'admin') : ?>
        <div class="col-md-4">
            <div class="card stat-widget">
                <div class="card-body">
                    <h5 class="card-title">Total User</h5>
                    <h2><?= esc((string) $userCount) ?></h2>
                    <p>Anda bisa kelola semua user</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
