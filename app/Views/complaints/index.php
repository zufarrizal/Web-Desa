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
                <div class="mb-3 d-flex align-items-center gap-2">
                    <label for="statusFilter" class="mb-0">Filter Status:</label>
                    <select id="statusFilter" class="form-select form-select-sm" style="max-width: 240px;">
                        <option value="all">Semua</option>
                        <option value="baru">Baru</option>
                        <option value="ditindaklanjuti">Ditindaklanjuti</option>
                        <option value="selesai">Selesai</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="table-responsive">
                    <table class="table" id="complaintsTable">
                        <thead>
                        <tr>
                            <th>No</th>
                            <?php if ($role === 'admin') : ?><th>Akun</th><?php endif; ?>
                            <th>Judul</th>
                            <th>Lokasi</th>
                            <th>Tanggal Pengaduan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($complaints as $index => $row) : ?>
                            <tr data-status="<?= esc((string) ($row['status'] ?? '')) ?>">
                                <td data-row-no><?= esc((string) ($index + 1)) ?></td>
                                <?php if ($role === 'admin') : ?><td><?= esc($row['user_name'] ?? '-') ?></td><?php endif; ?>
                                <td><?= esc($row['title']) ?></td>
                                <td><?= esc($row['location'] ?: '-') ?></td>
                                <td>
                                    <?= ! empty($row['created_at']) ? esc(date('d-m-Y H:i', strtotime((string) $row['created_at']))) : '-' ?>
                                </td>
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
<script>
    (function () {
        var filter = document.getElementById('statusFilter');
        var table = document.getElementById('complaintsTable');
        if (!filter || !table) {
            return;
        }

        var rows = Array.prototype.slice.call(table.querySelectorAll('tbody tr'));

        function applyFilter() {
            var selected = (filter.value || 'all').toLowerCase();
            var no = 0;

            rows.forEach(function (row) {
                var status = (row.getAttribute('data-status') || '').toLowerCase();
                var visible = selected === 'all' || status === selected;
                row.style.display = visible ? '' : 'none';

                if (visible) {
                    no += 1;
                    var noCell = row.querySelector('[data-row-no]');
                    if (noCell) {
                        noCell.textContent = String(no);
                    }
                }
            });
        }

        filter.addEventListener('change', applyFilter);
        applyFilter();
    })();
</script>
<?= $this->endSection() ?>
