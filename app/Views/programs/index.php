<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Kelola <?= esc($typeLabel ?? 'Program Desa') ?></h5>
                    <a href="<?= site_url('programs/create/' . ($type ?? 'program')) ?>" class="btn btn-primary">Tambah <?= esc($typeLabel ?? 'Postingan') ?></a>
                </div>
                <div class="table-responsive">
                    <table class="display js-zero-conf-table" id="programsTable" style="width:100%">
                        <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Slug</th>
                            <th>Publish</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($posts as $post) : ?>
                            <tr>
                                <td><?= esc($post['title']) ?></td>
                                <td><?= esc($post['slug']) ?></td>
                                <td><?= esc($post['published_at'] ?? '-') ?></td>
                                <td>
                                    <a class="btn btn-sm btn-info" target="_blank" href="<?= site_url('program/' . $post['slug']) ?>">Lihat</a>
                                    <a class="btn btn-sm btn-warning" href="<?= site_url('programs/' . ($type ?? 'program') . '/edit/' . $post['id']) ?>">Edit</a>
                                    <form action="<?= site_url('programs/' . ($type ?? 'program') . '/delete/' . $post['id']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-danger" type="submit" data-confirm="Hapus postingan ini?">Hapus</button>
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
