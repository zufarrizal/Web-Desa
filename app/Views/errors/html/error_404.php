<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Halaman yang Anda cari tidak ditemukan.">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title>404 - Halaman Tidak Ditemukan | Portal Desa</title>
    <style>
        :root {
            --bg: #f3f6fb;
            --surface: #ffffff;
            --text: #1f2a44;
            --muted: #67748e;
            --primary: #4f64d9;
            --line: #dde4f0;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: radial-gradient(circle at 20% 20%, #ebf0ff 0, transparent 45%),
                        radial-gradient(circle at 80% 80%, #eaf8f0 0, transparent 45%),
                        var(--bg);
            color: var(--text);
            font-family: "Poppins", Arial, sans-serif;
        }

        .error-card {
            width: min(760px, 100%);
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 16px 40px rgba(24, 40, 79, 0.09);
        }

        .error-code {
            margin: 0;
            font-size: clamp(56px, 12vw, 94px);
            line-height: 1;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: 1px;
        }

        .error-title {
            margin: 8px 0 12px;
            font-size: clamp(24px, 4vw, 34px);
            line-height: 1.2;
            font-weight: 700;
        }

        .error-text {
            margin: 0 0 18px;
            color: var(--muted);
            line-height: 1.7;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border-radius: 10px;
            min-height: 42px;
            padding: 0 16px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-secondary {
            background: #fff;
            color: var(--text);
            border-color: var(--line);
        }

        .dev-note {
            margin-top: 16px;
            padding: 12px 14px;
            border: 1px dashed var(--line);
            border-radius: 12px;
            color: #42506b;
            background: #f8fafe;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <main class="error-card" role="main">
        <p class="error-code">404</p>
        <h1 class="error-title">Halaman tidak ditemukan</h1>
        <p class="error-text">
            Halaman yang Anda buka tidak tersedia atau alamat URL tidak valid.
            Silakan kembali ke halaman utama atau kembali ke halaman sebelumnya.
        </p>
        <div class="actions">
            <a class="btn btn-primary" href="<?= site_url('/') ?>">Ke Halaman Utama</a>
            <a class="btn btn-secondary" href="javascript:history.back()">Kembali</a>
        </div>

        <?php if (ENVIRONMENT !== 'production' && ! empty($message)) : ?>
            <div class="dev-note"><?= nl2br(esc((string) $message)) ?></div>
        <?php endif; ?>
    </main>
</body>
</html>
