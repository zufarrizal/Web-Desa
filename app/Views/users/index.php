<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Kelola User</h5>
                    <a href="<?= site_url('users/create') ?>" class="btn btn-primary">Tambah User</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $index => $user) : ?>
                            <tr>
                                <td><?= esc((string) ($index + 1)) ?></td>
                                <td><?= esc($user['name']) ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <td><span class="badge bg-info"><?= esc($user['role'] ?? 'user') ?></span></td>
                                <td>
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
<?= $this->endSection() ?>
