<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Pengaduan Masyarakat</h5>
                    <a href="<?= site_url('complaints/create') ?>" class="btn btn-primary">Buat Pengaduan</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <?php if ($role === 'admin') : ?><th>Akun</th><?php endif; ?>
                            <th>Judul</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($complaints as $row) : ?>
                            <tr>
                                <td><?= esc((string) $row['id']) ?></td>
                                <?php if ($role === 'admin') : ?><td><?= esc($row['user_name'] ?? '-') ?></td><?php endif; ?>
                                <td><?= esc($row['title']) ?></td>
                                <td><?= esc($row['location'] ?: '-') ?></td>
                                <td><span class="badge bg-warning text-dark"><?= esc($row['status']) ?></span></td>
                                <td>
                                    <a href="<?= site_url('complaints/edit/' . $row['id']) ?>" class="btn btn-sm btn-warning">Tindak Lanjut</a>
                                    <form action="<?= site_url('complaints/delete/' . $row['id']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-danger" type="submit" data-confirm="Hapus pengaduan ini?">Hapus</button>
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
<?= $this->endSection() ?>
