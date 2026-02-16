<?php
$homePage = (bool) ($homePage ?? false);
$isLoggedIn = (bool) session()->get('logged_in');
$lt = (string) session()->get('link_token');
$withToken = static function (string $path) use ($lt): string {
    $url = site_url($path);
    if ($lt === '') {
        return $url;
    }
    return $url . (str_contains($url, '?') ? '&' : '?') . '_lt=' . rawurlencode($lt);
};

$homeUrl = site_url('/');
$berandaUrl = $homePage ? '#home' : $homeUrl;
$layananUrl = $homePage ? '#fitur' : $homeUrl . '#fitur';
$programUrl = $homePage ? '#program' : $homeUrl . '#program';
$kontakUrl = $homePage ? '#kontak' : $homeUrl . '#kontak';
?>
<nav class="navbar navbar-expand-lg guest-navbar">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('/') ?>">Portal Desa</a>
        <button class="navbar-toggler border-0 shadow-none p-1" style="color: inherit;" type="button" data-bs-toggle="collapse" data-bs-target="#guestNavbar" aria-controls="guestNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
                <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        <div class="collapse navbar-collapse" id="guestNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= esc($berandaUrl) ?>">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= esc($layananUrl) ?>">Layanan</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= esc($programUrl) ?>">Program Desa</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= esc($kontakUrl) ?>">Kontak</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <button id="themeToggle" class="theme-toggle" type="button" aria-label="Toggle theme">Dark Mode</button>
                <?php if ($isLoggedIn) : ?>
                    <a href="<?= $withToken('dashboard') ?>" class="btn btn-sm btn-outline-primary">Ke Dashboard</a>
                    <a href="<?= $withToken('logout') ?>" class="btn btn-sm btn-primary">Logout</a>
                <?php else : ?>
                    <a href="<?= site_url('register') ?>" class="btn btn-sm btn-outline-secondary">Registrasi</a>
                    <a href="<?= site_url('login') ?>" class="btn btn-sm btn-primary">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
