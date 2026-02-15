<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3"><?= $mode === 'create' ? 'Tambah ' . ($typeLabel ?? 'Postingan') : 'Edit ' . ($typeLabel ?? 'Postingan') ?></h5>

                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <div><?= esc($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" action="<?= $mode === 'create' ? site_url('programs/store/' . ($type ?? 'program')) : site_url('programs/update/' . $post['id']) ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" class="form-control" name="title" value="<?= old('title', $post['title'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe</label>
                        <input type="text" class="form-control" value="<?= esc($typeLabel ?? 'Postingan') ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ringkasan</label>
                        <textarea class="form-control" name="excerpt" rows="2"><?= old('excerpt', $post['excerpt'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Program (JPG/JPEG/PNG/WEBP, max 1 MB)</label>
                        <input type="file" class="form-control" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        <?php if (! empty($post['image_path'])) : ?>
                            <div class="mt-2">
                                <img src="<?= base_url($post['image_path']) ?>" alt="Gambar Program" style="max-height: 120px; width: auto;">
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="removeImage">
                                <label class="form-check-label" for="removeImage">Hapus gambar saat ini</label>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konten</label>
                        <textarea class="form-control" name="content" rows="8" required><?= old('content', $post['content'] ?? '') ?></textarea>
                    </div>
                    <hr>
                    <h6 class="mb-3">SEO</h6>
                    <div class="mb-3">
                        <label class="form-label">SEO Title</label>
                        <input type="text" class="form-control" name="seo_title" maxlength="191" value="<?= old('seo_title', $post['seo_title'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SEO Description</label>
                        <textarea class="form-control" name="seo_description" rows="3" maxlength="320"><?= old('seo_description', $post['seo_description'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SEO Keywords (pisahkan dengan koma)</label>
                        <input type="text" class="form-control" name="seo_keywords" maxlength="255" value="<?= old('seo_keywords', $post['seo_keywords'] ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= site_url('programs') ?>" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
