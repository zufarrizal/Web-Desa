<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3"><?= $mode === 'create' ? 'Buat Pengaduan Masyarakat' : 'Edit Pengaduan' ?></h5>

                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <div><?= esc($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= $mode === 'create' ? site_url('complaints/store') : site_url('complaints/update/' . $complaint['id']) ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Judul Pengaduan</label>
                        <input type="text" class="form-control" name="title" value="<?= old('title', $complaint['title'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi Kejadian</label>
                        <input type="text" class="form-control" name="location" value="<?= old('location', $complaint['location'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Pengaduan</label>
                        <textarea class="form-control" name="content" rows="5" required><?= old('content', $complaint['content'] ?? '') ?></textarea>
                    </div>

                    <?php if ($role === 'admin') : ?>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <?php $status = old('status', $complaint['status'] ?? 'baru'); ?>
                            <select class="form-control" name="status" required>
                                <option value="baru" <?= $status === 'baru' ? 'selected' : '' ?>>Baru</option>
                                <option value="ditindaklanjuti" <?= $status === 'ditindaklanjuti' ? 'selected' : '' ?>>Ditindaklanjuti</option>
                                <option value="selesai" <?= $status === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                <option value="ditolak" <?= $status === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Respon Admin</label>
                            <textarea class="form-control" name="response" rows="4"><?= old('response', $complaint['response'] ?? '') ?></textarea>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= site_url('complaints') ?>" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
