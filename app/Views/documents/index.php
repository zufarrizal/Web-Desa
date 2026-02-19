<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<style>
    .docs-compact .card-body {
        padding: 14px;
    }
    .docs-group {
        border-top: 1px solid #e9edf5;
        padding-top: 12px;
        margin-top: 12px;
    }
    .docs-group:first-of-type {
        border-top: 0;
        padding-top: 0;
        margin-top: 0;
    }
    .docs-group-title {
        font-size: 13px;
        font-weight: 700;
        color: #59607a;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: .3px;
    }
    .docs-intro {
        margin-bottom: 14px !important;
    }
    .docs-groups {
        margin-top: 6px;
    }
    .doc-item .card-body {
        padding: 12px;
    }
    .doc-item h6 {
        font-size: 13px;
        line-height: 1.35;
        margin-bottom: 10px;
    }
    .doc-item .btn {
        padding: 5px 10px;
        font-size: 12px;
    }
    .docs-row {
        --bs-gutter-x: 10px;
        --bs-gutter-y: 10px;
    }
    .doc-history-table {
        min-width: 1080px;
    }
    .doc-history-table td,
    .doc-history-table th {
        vertical-align: middle;
    }
    .doc-history-table .doc-actions {
        white-space: nowrap;
        min-width: 240px;
    }
    #riwayat-surat {
        scroll-margin-top: 130px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card docs-compact">
            <div class="card-body">
                <h5 class="card-title mb-1">Pelayanan Pengurusan Dokumen</h5>
                <p class="text-muted docs-intro">Pilih jenis surat, lalu pilih sumber data: sesuai profil atau manual.</p>

                <?php if (! $hasProfile) : ?>
                    <div class="alert alert-warning">
                        Profil Anda belum lengkap. <a href="<?= site_url('profile') ?>">Lengkapi profil sekarang</a> agar bisa membuat surat otomatis.
                    </div>
                <?php endif; ?>

                <div class="docs-groups">
                <?php foreach ($docGroups as $groupName => $items) : ?>
                    <div class="docs-group">
                        <div class="docs-group-title"><?= esc($groupName) ?></div>
                        <div class="row docs-row">
                            <?php foreach ($items as $key => $label) : ?>
                                <div class="col-md-6 col-lg-4 doc-item">
                                    <div class="card h-100 mb-0">
                                        <div class="card-body d-flex flex-column">
                                            <h6><?= esc($label) ?></h6>
                                            <div class="mt-auto">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Buat Surat
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item <?= $hasProfile ? '' : 'disabled' ?>" href="<?= $hasProfile ? site_url('documents/generate/' . $key) : '#' ?>" <?= $hasProfile ? '' : 'tabindex="-1" aria-disabled="true"' ?>>
                                                                Sesuai Profil
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= site_url('documents/create-manual/' . $key) ?>">
                                                                Isi Data Manual
                                                            </a>
                                                        </li>
                                                        <?php if ($role === 'admin') : ?>
                                                            <li>
                                                                <a class="dropdown-item" href="<?= site_url('documents/create-by-nik/' . $key) ?>">
                                                                    Berdasarkan NIK
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3" id="riwayat-surat">Riwayat Surat</h5>
                <div class="table-responsive">
                    <table class="display js-zero-conf-table doc-history-table" id="documentsHistoryTable" style="width:100%">
                        <thead>
                        <tr>
                            <th>No</th>
                            <?php if ($role === 'admin') : ?><th>Akun</th><?php endif; ?>
                            <th>Nama Warga</th>
                            <th>Jenis Surat</th>
                            <th>Tanggal Dibuat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requests as $index => $row) : ?>
                            <tr>
                                <td data-row-no><?= esc((string) ($index + 1)) ?></td>
                                <?php if ($role === 'admin') : ?><td><?= esc($row['user_name'] ?? '-') ?></td><?php endif; ?>
                                <td><?= esc($row['citizen_name']) ?></td>
                                <td><?= esc($row['document_type']) ?></td>
                                <td><?= ! empty($row['created_at']) ? esc(date('d/m/Y H:i', strtotime((string) $row['created_at']))) : '-' ?></td>
                                <td><span class="badge bg-info"><?= esc($row['status']) ?></span></td>
                                <td class="doc-actions">
                                    <a href="<?= site_url('documents/preview/' . $row['id']) ?>" class="btn btn-sm btn-secondary">Lihat Surat</a>
                                    <?php if ($role === 'admin') : ?>
                                        <?php if (($row['status'] ?? '') !== 'selesai') : ?>
                                            <form action="<?= site_url('documents/status/' . $row['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="status" value="selesai">
                                                <input type="hidden" name="admin_notes" value="Disetujui admin">
                                                <button class="btn btn-sm btn-success" type="submit">Setujui Surat</button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <form action="<?= site_url('documents/delete/' . $row['id']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-danger" type="submit" data-confirm="Hapus data surat ini?">Hapus</button>
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
