<?php
$setting = $setting ?? ((new \App\Models\LetterSettingModel())->first() ?: []);
$villageName = trim((string) ($setting['village_name'] ?? 'Nama Desa'));
?>
<footer id="kontak-footer" class="footer py-4 mt-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <strong>Pemerintah Desa <?= esc($villageName) ?></strong><br>
            <small>Jam layanan: Senin - Jumat, 08.00 - 15.00 WIB</small>
        </div>
        <small class="mt-2 mt-md-0">Portal Desa &copy; <?= date('Y') ?></small>
    </div>
</footer>
