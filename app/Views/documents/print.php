<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Halaman cetak dokumen administrasi desa.">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title>Print <?= esc($request['document_type']) ?></title>
    <link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 10mm 12mm;
        }
        html, body {
            margin: 0;
            padding: 0;
        }
        body {
            background: #fff;
            color: #000;
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }
        .sheet { max-width: 900px; margin: 20px auto; }
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
        @media print {
            .no-print { display: none !important; }
            .sheet { margin: 0 !important; max-width: 100% !important; }
            .sheet > .border {
                border: 0 !important;
                padding: 0 !important;
            }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <?php
        $birthDateRaw = (string) ($citizen['birth_date'] ?? '');
        $birthTs = $birthDateRaw !== '' ? strtotime($birthDateRaw) : false;
        $birthDateFormatted = $birthTs ? date('d/m/Y', $birthTs) : ($birthDateRaw !== '' ? $birthDateRaw : '-');
    ?>
    <div class="container sheet">
        <div class="no-print mb-3">
            <button onclick="window.print()" class="btn btn-primary btn-sm">Print Sekarang</button>
            <button onclick="window.close()" class="btn btn-secondary btn-sm">Tutup</button>
        </div>

        <div class="border p-4" style="background:#fff;">
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
                <div class="col-6"></div>
                <div class="col-6 text-center">
                    <p><?= esc($setting['village_name'] ?? 'Desa') ?>, <?= date('d-m-Y') ?></p>
                    <p><?= esc($setting['signer_title'] ?? 'Kepala Desa') ?></p>
                    <?php if (! empty($setting['signer_signature'])) : ?>
                        <p class="mb-1"><img src="<?= base_url($setting['signer_signature']) ?>" alt="Tanda Tangan" style="max-height: 90px; width: auto;"></p>
                    <?php else : ?>
                        <br><br>
                    <?php endif; ?>
                    <p><strong><u><?= strtoupper(esc($setting['signer_name'] ?? 'Nama Kepala Desa')) ?></u></strong></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
