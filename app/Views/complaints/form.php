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
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>

                <form id="complaintForm" method="post" enctype="multipart/form-data" action="<?= $mode === 'create' ? site_url('complaints/store') : site_url('complaints/update/' . $complaint['id']) ?>">
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
                    <div class="mb-3">
                        <label class="form-label">Foto Pendukung (Opsional, maks 1MB)</label>
                        <input id="complaintImage" type="file" class="form-control" name="image" accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted">Maksimal 1MB. Jika lebih besar, sistem akan coba kompres otomatis sebelum upload.</small>
                        <?php if (! empty($complaint['image_path'])) : ?>
                            <div class="mt-2">
                                <a href="<?= base_url($complaint['image_path']) ?>" target="_blank" rel="noopener">
                                    <img src="<?= base_url($complaint['image_path']) ?>" alt="Foto pengaduan" style="max-width: 180px; border-radius: 8px;">
                                </a>
                            </div>
                        <?php endif; ?>
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
                    <?php elseif ($mode === 'edit') : ?>
                        <?php $statusUser = (string) ($complaint['status'] ?? 'baru'); ?>
                        <?php $responseUser = trim((string) ($complaint['response'] ?? '')); ?>
                        <div class="mb-3">
                            <label class="form-label">Status Pengaduan</label>
                            <input type="text" class="form-control" value="<?= esc($statusUser) ?>" readonly>
                        </div>
                        <?php if (in_array($statusUser, ['ditindaklanjuti', 'selesai', 'ditolak'], true)) : ?>
                            <div class="mb-3">
                                <label class="form-label">Jawaban Admin</label>
                                <textarea class="form-control" rows="4" readonly><?= esc($responseUser !== '' ? $responseUser : 'Belum ada jawaban admin.') ?></textarea>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= site_url('complaints') ?>" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    (function () {
        var form = document.getElementById('complaintForm');
        var fileInput = document.getElementById('complaintImage');
        if (!form || !fileInput) {
            return;
        }

        var maxBytes = 1024 * 1024; // 1MB
        var isSubmitting = false;
        var validMime = ['image/jpeg', 'image/png', 'image/webp'];

        function showFloatingError(message) {
            var host = document.querySelector('.floating-alert-container');
            if (!host) {
                host = document.createElement('div');
                host.className = 'floating-alert-container';
                document.body.appendChild(host);
            }

            var box = document.createElement('div');
            box.className = 'alert alert-danger floating-alert';
            box.textContent = message || 'Terjadi kesalahan saat upload gambar.';
            host.appendChild(box);

            setTimeout(function () {
                box.classList.add('opacity-0');
                setTimeout(function () {
                    if (box.parentNode) {
                        box.parentNode.removeChild(box);
                    }
                }, 350);
            }, 5000);
        }

        function compressImageToJpeg(file, maxSize) {
            return new Promise(function (resolve, reject) {
                var url = URL.createObjectURL(file);
                var img = new Image();

                img.onload = function () {
                    URL.revokeObjectURL(url);

                    var maxDim = 1800;
                    var scale = Math.min(1, maxDim / Math.max(img.width, img.height));
                    var width = Math.max(1, Math.round(img.width * scale));
                    var height = Math.max(1, Math.round(img.height * scale));

                    var canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    var ctx = canvas.getContext('2d');
                    if (!ctx) {
                        reject(new Error('Canvas tidak tersedia'));
                        return;
                    }

                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, width, height);
                    ctx.drawImage(img, 0, 0, width, height);

                    var quality = 0.86;

                    function exportStep() {
                        canvas.toBlob(function (blob) {
                            if (!blob) {
                                reject(new Error('Gagal memproses gambar'));
                                return;
                            }

                            if (blob.size <= maxSize || quality <= 0.45) {
                                resolve(blob);
                                return;
                            }

                            quality -= 0.08;
                            exportStep();
                        }, 'image/jpeg', quality);
                    }

                    exportStep();
                };

                img.onerror = function () {
                    URL.revokeObjectURL(url);
                    reject(new Error('Format gambar tidak valid'));
                };

                img.src = url;
            });
        }

        form.addEventListener('submit', function (event) {
            if (isSubmitting) {
                return;
            }

            var file = fileInput.files && fileInput.files[0] ? fileInput.files[0] : null;
            if (file && validMime.indexOf((file.type || '').toLowerCase()) === -1) {
                event.preventDefault();
                showFloatingError('Format gambar tidak valid. Gunakan JPG, PNG, atau WEBP.');
                return;
            }
            if (!file || file.size <= maxBytes) {
                return;
            }

            event.preventDefault();
            isSubmitting = true;

            compressImageToJpeg(file, maxBytes).then(function (blob) {
                if (blob.size > maxBytes) {
                    throw new Error('Gambar tetap lebih dari 1MB setelah kompresi.');
                }

                var nameBase = (file.name || 'pengaduan').replace(/\.[^.]+$/, '');
                var compressedFile = new File([blob], nameBase + '.jpg', {type: 'image/jpeg'});
                var dt = new DataTransfer();
                dt.items.add(compressedFile);
                fileInput.files = dt.files;
                form.submit();
            }).catch(function (error) {
                isSubmitting = false;
                showFloatingError(error && error.message ? error.message : 'Gagal kompres gambar. Gunakan file yang lebih kecil.');
            });
        });
    })();
</script>
<?= $this->endSection() ?>
