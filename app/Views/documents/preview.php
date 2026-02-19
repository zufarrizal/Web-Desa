<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
    $birthDateRaw = (string) ($citizen['birth_date'] ?? '');
    $birthTs = $birthDateRaw !== '' ? strtotime($birthDateRaw) : false;
    if ($birthTs) {
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $day = date('d', $birthTs);
        $month = $monthNames[(int) date('n', $birthTs)] ?? date('m', $birthTs);
        $year = date('Y', $birthTs);
        $birthDateFormatted = $day . ' ' . $month . ' ' . $year;
    } else {
        $birthDateFormatted = $birthDateRaw !== '' ? $birthDateRaw : '-';
    }
?>
<style>
    .preview-canvas {
        display: flex;
        justify-content: center;
        padding: 8px 0 16px;
        overflow-x: auto;
    }
    .letter-paper {
        width: 210mm;
        min-height: 297mm;
        background: #fff;
        border: 1px solid #d9d9d9;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        padding: 14mm 14mm 16mm;
    }
    .letter-sheet {
        font-family: "Times New Roman", Times, serif;
        font-size: 12pt;
        line-height: 1.5;
        color: #000 !important;
        background: #fff !important;
    }
    .letter-sheet,
    .letter-sheet * {
        color: #000 !important;
    }
    .letter-sheet .table,
    .letter-sheet .table td,
    .letter-sheet .table th {
        color: #000 !important;
        background: transparent !important;
        border-color: transparent !important;
    }
    .letter-head-line-1 {
        font-size: 14pt;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 0;
    }
    .letter-head-line-2 {
        font-size: 13pt;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 0;
    }
    .letter-title {
        font-size: 12pt;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    .letter-meta {
        font-size: 12pt;
        margin-bottom: 0;
    }
    @media (max-width: 992px) {
        .letter-paper {
            width: 100%;
            min-height: auto;
            padding: 20px;
        }
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <?php $isAdmin = (string) session()->get('user_role') === 'admin'; ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Preview Surat</h5>
                    <div class="d-flex gap-2">
                        <?php if ($isAdmin && ($request['status'] ?? '') !== 'selesai') : ?>
                            <form action="<?= site_url('documents/status/' . $request['id']) ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="status" value="selesai">
                                <input type="hidden" name="admin_notes" value="Disetujui admin dari preview">
                                <input type="hidden" name="redirect_to" value="<?= '/documents/preview/' . (int) $request['id'] ?>">
                                <button class="btn btn-sm btn-success" type="submit">Setujui Surat</button>
                            </form>
                        <?php endif; ?>
                        <?php if (! empty($canPrint)) : ?>
                            <a href="<?= site_url('documents/print/' . $request['id']) ?>" class="btn btn-primary btn-sm" target="_blank">Print Dokumen</a>
                        <?php else : ?>
                            <button type="button" class="btn btn-secondary btn-sm" disabled title="Surat menunggu persetujuan admin">Print Menunggu Persetujuan</button>
                        <?php endif; ?>
                        <a href="<?= site_url('documents') ?>" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                </div>
                <?php if (empty($canPrint)) : ?>
                    <div class="alert alert-warning py-2">
                        Surat ini belum disetujui admin. Tombol print akan aktif setelah status surat <strong>selesai</strong>.
                    </div>
                <?php endif; ?>
                <p class="text-muted mb-3">Ukuran dokumen: A4 (Lebar 210 mm x Tinggi 297 mm).</p>

                <div class="preview-canvas">
                    <div class="letter-paper">
                        <div class="letter-sheet">
                            <div class="text-center mb-3">
                                <p class="letter-head-line-1">PEMERINTAH KABUPATEN <?= strtoupper(esc($setting['regency_name'] ?? 'NAMA KABUPATEN')) ?></p>
                                <p class="letter-head-line-2">KECAMATAN <?= strtoupper(esc($setting['subdistrict_name'] ?? 'NAMA KECAMATAN')) ?></p>
                                <p class="letter-head-line-1">DESA <?= strtoupper(esc($setting['village_name'] ?? 'NAMA DESA')) ?></p>
                                <p class="letter-meta"><?= esc($setting['office_address'] ?? ($setting['letterhead_address'] ?? '[Nama Jalan/Alamat Lengkap Kantor Desa]')) ?></p>
                                <hr>
                                <p class="letter-title mt-3 text-decoration-underline"><?= esc($request['document_type']) ?></p>
                                <p class="letter-meta">Nomor: <?= esc($letterNumber ?? '-') ?></p>
                            </div>

                            <p>Yang bertanda tangan di bawah ini, menerangkan bahwa:</p>
                            <table class="table table-borderless table-sm">
                                <tr><td width="180">Nama</td><td>: <?= esc($citizen['name'] ?? '-') ?></td></tr>
                                <tr><td>NIK</td><td>: <?= esc($citizen['nik'] ?? '-') ?></td></tr>
                                <tr><td>Tempat/Tanggal Lahir</td><td>: <?= esc(($citizen['birth_place'] ?? '-') . ', ' . $birthDateFormatted) ?></td></tr>
                                <tr><td>Jenis Kelamin</td><td>: <?= esc($citizen['gender'] ?? '-') ?></td></tr>
                                <tr><td>Pekerjaan</td><td>: <?= esc($citizen['occupation'] ?? '-') ?></td></tr>
                                <tr><td>Alamat</td><td>: <?= esc($citizen['address'] ?? '-') ?></td></tr>
                            </table>

                            <p style="white-space: pre-line;"><?= esc($letterBody) ?></p>

                            <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>

                            <div class="row mt-4">
                                <div class="col-md-6"></div>
                                <div class="col-md-6 text-center">
                                    <p><?= esc($setting['village_name'] ?? 'Desa') ?>, <?= date('d-m-Y') ?></p>
                                    <p><?= esc($setting['signer_title'] ?? 'Kepala Desa') ?></p>
                                    <br><br>
                                    <p><strong><u><?= strtoupper(esc($setting['signer_name'] ?? 'Nama Kepala Desa')) ?></u></strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
