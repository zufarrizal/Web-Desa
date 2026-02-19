<?php
$metaTitle = (string) ($title ?? 'Dashboard Desa - Portal Desa');
$metaDescription = (string) ($metaDescription ?? 'Dashboard Portal Desa untuk layanan dokumen warga, pengaduan, dan manajemen konten desa.');
$canonicalUrl = (string) ($canonicalUrl ?? current_url(true)->__toString());
$metaRobots = (string) ($metaRobots ?? 'noindex,nofollow,noarchive');
$metaImage = (string) ($metaImage ?? base_url('assets/images/card-image.png'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= esc($metaDescription) ?>">
    <meta name="robots" content="<?= esc($metaRobots) ?>">
    <link rel="canonical" href="<?= esc($canonicalUrl) ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Portal Desa">
    <meta property="og:title" content="<?= esc($metaTitle) ?>">
    <meta property="og:description" content="<?= esc($metaDescription) ?>">
    <meta property="og:url" content="<?= esc($canonicalUrl) ?>">
    <meta property="og:image" content="<?= esc($metaImage) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($metaTitle) ?>">
    <meta name="twitter:description" content="<?= esc($metaDescription) ?>">
    <meta name="twitter:image" content="<?= esc($metaImage) ?>">
    <meta name="app-link-token" content="<?= esc((string) session()->get('link_token')) ?>">
    <title><?= esc($metaTitle) ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>">
    <link rel="alternate icon" href="<?= base_url('assets/images/logo@2x.png') ?>">

    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/font-awesome/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/perfectscroll/perfect-scrollbar.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/DataTables/datatables.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/main.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">
    <link id="adminDarkThemeCss" href="<?= base_url('assets/css/dark-theme.css') ?>" rel="stylesheet" disabled>
    <script>
        (function () {
            var THEME_KEY = 'site-theme';
            var LEGACY_KEYS = ['admin-theme', 'landing-theme'];
            var mode = 'light';
            try {
                var stored = localStorage.getItem(THEME_KEY);
                if (stored !== 'dark' && stored !== 'light') {
                    for (var i = 0; i < LEGACY_KEYS.length; i += 1) {
                        stored = localStorage.getItem(LEGACY_KEYS[i]);
                        if (stored === 'dark' || stored === 'light') {
                            break;
                        }
                    }
                }
                if (stored === 'dark' || stored === 'light') {
                    mode = stored;
                }
                localStorage.setItem(THEME_KEY, mode);
                localStorage.setItem('admin-theme', mode);
                localStorage.setItem('landing-theme', mode);
            } catch (e) {}

            window.__adminThemeMode = mode;
            var darkCss = document.getElementById('adminDarkThemeCss');
            if (darkCss) {
                darkCss.disabled = mode !== 'dark';
            }
        })();
    </script>
    <style>
        .top-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .page-header .navbar .navbar-brand.top-brand {
            width: auto;
            height: auto;
            background: none;
            margin: 0;
            padding: 0;
            display: inline-flex;
            flex: 0 1 auto;
        }
        .top-brand-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: transparent;
            color: #4f64d9;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 24px;
        }
        .top-brand-icon svg {
            width: 18px;
            height: 18px;
            stroke-width: 1.9;
        }
        .top-brand-title {
            display: inline-block;
            font-weight: 700;
            margin: 0;
            line-height: 1.1;
            font-size: 26px;
            letter-spacing: 0;
            color: #4f64d9;
            white-space: nowrap;
        }
        .top-brand-text {
            display: inline-flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.1;
        }
        .top-brand-subtitle {
            margin: 2px 0 0;
            font-size: 13px;
            font-weight: 500;
            color: #6f7487;
            white-space: nowrap;
        }
        .top-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .mobile-sidebar-toggle {
            display: none;
            border: 1px solid #dbe2ef;
            background: #fff;
            color: #5b5b5b;
            border-radius: 10px;
            padding: 7px 10px;
            line-height: 1;
        }
        .mobile-sidebar-toggle svg {
            width: 18px;
            height: 18px;
        }
        .admin-theme-toggle {
            border: 1px solid #dbe2ef;
            background: #fff;
            color: #5b5b5b;
            border-radius: 10px;
            padding: 7px 12px;
            font-size: 12px;
            line-height: 1;
            font-weight: 600;
        }
        .admin-theme-toggle:hover {
            color: #4f64d9;
            border-color: #cfd8ea;
        }
        .top-profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #eef1f8;
            color: #4f64d9;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 40px;
        }
        .top-profile-avatar svg {
            width: 20px;
            height: 20px;
            stroke-width: 2;
        }
        .top-profile-info {
            text-align: right;
            line-height: 1.2;
        }
        .top-profile-name {
            font-weight: 600;
            margin: 0;
            color: #2f3342;
        }
        .top-profile-role {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
            text-transform: uppercase;
        }
        .important-strip {
            min-height: 34px;
            padding-top: 4px !important;
            padding-bottom: 4px !important;
        }
        .important-strip .badge {
            padding: 4px 9px;
            line-height: 1.1;
        }
        .page-content {
            margin-top: 124px !important;
        }
        .main-wrapper {
            min-height: auto;
        }
        .page-sidebar {
            top: 171px !important;
            height: calc(100% - 201px) !important;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(16, 24, 40, 0.35);
            z-index: 998;
        }
        @media (max-width: 1350px) {
            .page-content {
                margin-top: 124px !important;
            }
        }
        @media (max-width: 768px) {
            .page-container {
                padding: 10px;
            }
            .page-header {
                position: relative;
                width: 100%;
            }
            .page-header::before {
                display: none;
            }
            .mobile-sidebar-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .page-header .navbar {
                flex-direction: column;
                align-items: center !important;
                gap: 10px;
                padding-top: 12px;
                padding-bottom: 12px;
            }
            .page-header .navbar .logo {
                width: 100%;
                justify-content: center;
                display: flex !important;
                flex: none;
            }
            #headerNav {
                width: 100%;
                align-items: center !important;
            }
            #headerNav > ul {
                width: 100%;
                justify-content: center;
                gap: 8px;
                flex-wrap: wrap;
            }
            #headerNav > ul > li {
                margin: 0 !important;
            }
            .top-brand {
                justify-content: center;
            }
            .top-brand-text {
                align-items: center;
                text-align: center;
            }
            .top-brand-title {
                font-size: 18px;
            }
            .top-brand-subtitle {
                font-size: 11px;
                margin-top: 1px;
            }
            .admin-theme-toggle {
                padding: 7px 10px;
                font-size: 11px;
            }
            .top-profile-info {
                display: none;
            }
            .top-profile {
                margin: 0 !important;
                padding: 0 !important;
            }
            .page-sidebar {
                left: 0;
                top: 0 !important;
                transform: translateX(-108%) !important;
                transition: transform .22s ease-in-out !important;
                z-index: 1000;
                width: 260px;
                height: 100% !important;
                border-radius: 0;
            }
            body.mobile-sidebar-open .page-sidebar {
                transform: translateX(0) !important;
            }
            body.mobile-sidebar-open .sidebar-overlay {
                display: block;
            }
            .page-content {
                margin-top: 10px !important;
                margin-left: 0 !important;
                transform: none !important;
            }
            body.mobile-sidebar-open .page-content {
                transform: none !important;
            }
            body.mobile-sidebar-open {
                overflow: hidden;
            }
        }
    </style>
</head>
<body>
    <?php
    $path      = service('uri')->getPath();
    $role      = (string) session()->get('user_role');
    $isAdmin   = $role === 'admin';
    $userId    = (int) session()->get('user_id');
    $todayText = date('d-m-Y');
    $setting   = (new \App\Models\LetterSettingModel())->first() ?: [];
    $appIcon   = (string) ($setting['app_icon'] ?? 'home');
    $brandName = (string) ($setting['village_name'] ?? 'Portal Desa');

    if ($isAdmin) {
        $docPending = (new \App\Models\DocumentRequestModel())->where('status', 'diajukan')->countAllResults();
        $complaintNew = (new \App\Models\ComplaintModel())->where('status', 'baru')->countAllResults();
        $todayStart = date('Y-m-d 00:00:00');
        $notifSeenAt = (string) session()->get('admin_notifications_seen_at');
        $notifSince = $notifSeenAt !== '' ? $notifSeenAt : $todayStart;
        $newUserCount = (new \App\Models\UserModel())
            ->where('role', 'user')
            ->where('created_at >', $notifSince)
            ->countAllResults();
        $newUsers = (new \App\Models\UserModel())
            ->select('id,name,created_at')
            ->where('role', 'user')
            ->where('created_at >', $notifSince)
            ->orderBy('created_at', 'DESC')
            ->findAll(3);
        $newDocumentCount = (new \App\Models\DocumentRequestModel())
            ->where('status', 'diajukan')
            ->where('created_at >', $notifSince)
            ->countAllResults();
        $newDocuments = (new \App\Models\DocumentRequestModel())
            ->select('id,citizen_name,document_type,created_at')
            ->where('status', 'diajukan')
            ->where('created_at >', $notifSince)
            ->orderBy('created_at', 'DESC')
            ->findAll(3);
        $newComplaintCount = (new \App\Models\ComplaintModel())
            ->where('status', 'baru')
            ->where('created_at >', $notifSince)
            ->countAllResults();
        $newComplaints = (new \App\Models\ComplaintModel())
            ->select('id,title,location,created_at')
            ->where('status', 'baru')
            ->where('created_at >', $notifSince)
            ->orderBy('created_at', 'DESC')
            ->findAll(3);
        $adminNotifTotal = $newUserCount + $newDocumentCount + $newComplaintCount;
    } else {
        $docPending = (new \App\Models\DocumentRequestModel())->where('user_id', $userId)->whereIn('status', ['diajukan', 'diproses'])->countAllResults();
        $complaintNew = (new \App\Models\ComplaintModel())->where('user_id', $userId)->whereIn('status', ['baru', 'ditindaklanjuti'])->countAllResults();
        $newUserCount = 0;
        $newDocumentCount = 0;
        $newComplaintCount = 0;
        $newUsers = [];
        $newDocuments = [];
        $newComplaints = [];
        $adminNotifTotal = 0;
    }
    ?>
    <div class="page-container">
        <div class="page-header">
            <nav class="navbar navbar-expand-lg d-flex justify-content-between">
                <div class="logo">
                    <a class="navbar-brand top-brand" href="<?= site_url('dashboard') ?>">
                        <span class="top-brand-icon"><i data-feather="<?= esc($appIcon) ?>"></i></span>
                        <span class="top-brand-text">
                            <span class="top-brand-title"><?= esc($brandName) ?></span>
                            <span class="top-brand-subtitle">Pelayanan Administrasi Desa</span>
                        </span>
                    </a>
                </div>
                <div id="headerNav">
                    <ul class="navbar-nav">
                        <li class="nav-item d-flex align-items-center me-2">
                            <button id="mobileSidebarToggle" type="button" class="mobile-sidebar-toggle" aria-label="Toggle menu">
                                <i data-feather="menu"></i>
                            </button>
                        </li>
                        <?php if ($isAdmin) : ?>
                            <li class="nav-item dropdown d-flex align-items-center me-2">
                                <a class="nav-link position-relative px-2" href="#" id="adminNotifDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi Admin">
                                    <i data-feather="bell"></i>
                                    <?php if ($adminNotifTotal > 0) : ?>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            <?= esc((string) $adminNotifTotal) ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end profile-drop-menu" aria-labelledby="adminNotifDropDown" style="min-width: 320px;">
                                    <h6 class="dropdown-header">Notifikasi Admin</h6>
                                    <a class="dropdown-item" href="<?= site_url('users') ?>">
                                        Registrasi User Baru
                                        <span class="badge bg-primary float-end"><?= esc((string) $newUserCount) ?></span>
                                    </a>
                                    <?php foreach ($newUsers as $item) : ?>
                                        <div class="dropdown-item-text py-1 text-muted">
                                            <small><?= esc((string) ($item['name'] ?? '-')) ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                    <a class="dropdown-item" href="<?= site_url('documents') ?>">
                                        Dokumen Baru
                                        <span class="badge bg-warning text-dark float-end"><?= esc((string) $newDocumentCount) ?></span>
                                    </a>
                                    <?php foreach ($newDocuments as $item) : ?>
                                        <div class="dropdown-item-text py-1 text-muted">
                                            <small><?= esc((string) ($item['citizen_name'] ?? '-')) ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                    <a class="dropdown-item" href="<?= site_url('complaints') ?>">
                                        Pengaduan Baru
                                        <span class="badge bg-danger float-end"><?= esc((string) $newComplaintCount) ?></span>
                                    </a>
                                    <?php foreach ($newComplaints as $item) : ?>
                                        <div class="dropdown-item-text py-1 text-muted">
                                            <small><?= esc((string) ($item['title'] ?? '-')) ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if ($adminNotifTotal > 0) : ?>
                                        <div class="dropdown-divider"></div>
                                        <form action="<?= site_url('admin/notifications/clear') ?>" method="post" class="px-3 py-2">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">Hapus Notifikasi</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item d-flex align-items-center me-2">
                            <button id="adminThemeToggle" type="button" class="admin-theme-toggle">Dark Mode</button>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link profile-dropdown top-profile" href="#" id="profileDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="top-profile-info">
                                    <p class="top-profile-name"><?= esc(session()->get('user_name')) ?></p>
                                    <p class="top-profile-role"><?= esc(session()->get('user_role')) ?></p>
                                </span>
                                <span class="top-profile-avatar" aria-hidden="true"><i data-feather="user"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end profile-drop-menu" aria-labelledby="profileDropDown">
                                <span class="dropdown-item-text">
                                    <strong><?= esc(session()->get('user_name')) ?></strong><br>
                                    <small><?= esc(session()->get('user_email')) ?> (<?= esc(session()->get('user_role')) ?>)</small>
                                </span>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= site_url('logout') ?>" data-no-js-nav data-confirm="Yakin ingin logout?"><i data-feather="log-out"></i>Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="important-strip px-4 py-0 border-top bg-light">
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <span><strong>Informasi Penting</strong></span>
                    <span class="badge bg-primary">Tanggal: <?= esc($todayText) ?></span>
                    <span class="badge bg-secondary">Role: <?= esc(strtoupper($role)) ?></span>
                    <span class="badge bg-warning text-dark">Dokumen Aktif: <?= esc((string) $docPending) ?></span>
                    <span class="badge bg-danger">Pengaduan Aktif: <?= esc((string) $complaintNew) ?></span>
                </div>
            </div>
        </div>
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <div class="page-sidebar">
            <ul class="list-unstyled accordion-menu">
                <?php if (session()->get('user_role') === 'admin') : ?>
                    <li class="sidebar-title">Fitur Pengguna</li>
                    <li class="<?= $path === 'dashboard' ? 'active-page' : '' ?>">
                        <a href="<?= site_url('dashboard') ?>"><i data-feather="home"></i>Dashboard</a>
                    </li>
                    <li class="<?= $path === 'profile' ? 'active-page' : '' ?>">
                        <a href="<?= site_url('profile') ?>"><i data-feather="user"></i>Profil Saya</a>
                    </li>
                    <li class="<?= str_starts_with($path, 'documents') ? 'active-page' : '' ?>">
                        <a href="<?= site_url('documents') ?>"><i data-feather="file-text"></i>Pelayanan Dokumen</a>
                    </li>
                    <li class="<?= str_starts_with($path, 'complaints') ? 'active-page' : '' ?>">
                        <a href="<?= site_url('complaints') ?>"><i data-feather="message-square"></i>Pengaduan Saya</a>
                    </li>
                    <li class="sidebar-title mt-2">Fitur Admin</li>
                    <li class="<?= str_starts_with($path, 'users') ? 'active-page' : '' ?>">
                        <a href="<?= site_url('users') ?>"><i data-feather="users"></i>Managemen Pengguna</a>
                    </li>
                    <li class="<?= $path === 'programs/program' || $path === 'programs' ? 'active-page' : '' ?>">
                        <a href="<?= site_url('programs/program') ?>"><i data-feather="clipboard"></i>Posting Program Desa</a>
                    </li>
                    <li class="<?= $path === 'programs/artikel' ? 'active-page' : '' ?>">
                        <a href="<?= site_url('programs/artikel') ?>"><i data-feather="file-text"></i>Posting Artikel</a>
                    </li>
                    <li class="<?= $path === 'programs/kegiatan' ? 'active-page' : '' ?>">
                        <a href="<?= site_url('programs/kegiatan') ?>"><i data-feather="calendar"></i>Posting Kegiatan</a>
                    </li>
                    <li class="<?= $path === 'programs/pengumuman' ? 'active-page' : '' ?>">
                        <a href="<?= site_url('programs/pengumuman') ?>"><i data-feather="bell"></i>Posting Pengumuman</a>
                    </li>
                    <li class="<?= $path === 'documents/settings' ? 'active-page' : '' ?>">
                        <a href="<?= site_url('documents/settings') ?>"><i data-feather="settings"></i>Kop Surat Desa</a>
                    </li>
                    <li class="<?= $path === 'settings/home' ? 'active-page' : '' ?>">
                        <a href="<?= site_url('settings/home') ?>"><i data-feather="layout"></i>Pengaturan Halaman Utama</a>
                    </li>
                    <li>
                        <a href="<?= site_url('/') ?>"><i data-feather="external-link"></i>Lihat Halaman Utama</a>
                    </li>
                <?php else : ?>
                    <li class="sidebar-title">User Panel</li>
                    <li class="<?= $path === 'dashboard' ? 'active-page' : '' ?>">
                        <a href="<?= site_url('dashboard') ?>"><i data-feather="home"></i>Dashboard</a>
                    </li>
                    <li class="<?= $path === 'profile' ? 'active-page' : '' ?>">
                        <a href="<?= site_url('profile') ?>"><i data-feather="user"></i>Profil Saya</a>
                    </li>
                    <li class="<?= str_starts_with($path, 'documents') ? 'active-page' : '' ?>">
                        <a href="<?= site_url('documents') ?>"><i data-feather="file-text"></i>Pelayanan Dokumen</a>
                    </li>
                    <li class="<?= str_starts_with($path, 'complaints') ? 'active-page' : '' ?>">
                        <a href="<?= site_url('complaints') ?>"><i data-feather="message-square"></i>Pengaduan Saya</a>
                    </li>
                    <li>
                        <a href="<?= site_url('/') ?>"><i data-feather="external-link"></i>Lihat Halaman Utama</a>
                    </li>
                <?php endif; ?>
                <li class="sidebar-title mt-2">Akun</li>
                <li>
                    <a href="<?= site_url('logout') ?>" data-no-js-nav data-confirm="Yakin ingin logout?"><i data-feather="log-out"></i>Logout</a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="main-wrapper">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/plugins/jquery/jquery-3.4.1.min.js') ?>" defer></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="https://unpkg.com/feather-icons" defer></script>
    <script src="<?= base_url('assets/plugins/perfectscroll/perfect-scrollbar.min.js') ?>" defer></script>
    <script src="<?= base_url('assets/plugins/DataTables/datatables.min.js') ?>" defer></script>
    <script src="<?= base_url('assets/js/main.min.js') ?>" defer></script>
    <script src="<?= base_url('assets/js/app-lite.js') ?>" defer></script>
    <script>
        (function () {
            var darkCss = document.getElementById('adminDarkThemeCss');
            var toggle = document.getElementById('adminThemeToggle');
            if (!darkCss || !toggle) {
                return;
            }

            var THEME_KEY = 'site-theme';
            var mode = window.__adminThemeMode || localStorage.getItem(THEME_KEY) || localStorage.getItem('admin-theme') || localStorage.getItem('landing-theme');
            if (mode !== 'dark' && mode !== 'light') {
                mode = 'light';
            }

            function applyTheme(nextMode) {
                darkCss.disabled = nextMode !== 'dark';
                toggle.textContent = nextMode === 'dark' ? 'Light Mode' : 'Dark Mode';
                localStorage.setItem(THEME_KEY, nextMode);
                localStorage.setItem('admin-theme', nextMode);
                localStorage.setItem('landing-theme', nextMode);
            }

            applyTheme(mode);

            toggle.addEventListener('click', function () {
                mode = (darkCss.disabled ? 'dark' : 'light');
                applyTheme(mode);
            });
        })();

        (function () {
            var toggleBtn = document.getElementById('mobileSidebarToggle');
            var overlay = document.getElementById('sidebarOverlay');
            var sidebar = document.querySelector('.page-sidebar');
            if (!toggleBtn || !overlay || !sidebar) {
                return;
            }

            function closeSidebar() {
                document.body.classList.remove('mobile-sidebar-open');
            }

            function toggleSidebar() {
                document.body.classList.toggle('mobile-sidebar-open');
            }

            toggleBtn.addEventListener('click', function (event) {
                event.preventDefault();
                toggleSidebar();
            });

            overlay.addEventListener('click', closeSidebar);

            sidebar.addEventListener('click', function (event) {
                var link = event.target.closest('a[href]');
                if (!link) {
                    return;
                }
                if (window.matchMedia('(max-width: 768px)').matches) {
                    closeSidebar();
                }
            });

            window.addEventListener('resize', function () {
                if (!window.matchMedia('(max-width: 768px)').matches) {
                    closeSidebar();
                }
            });
        })();
    </script>
</body>
</html>
