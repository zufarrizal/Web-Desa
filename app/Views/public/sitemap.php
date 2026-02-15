<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?= esc(site_url('/')) ?></loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <?php foreach ($posts as $post) : ?>
    <url>
        <loc><?= esc(site_url('program/' . $post['slug'])) ?></loc>
        <lastmod><?= esc(date('c', strtotime((string) ($post['updated_at'] ?? $post['published_at'] ?? 'now')))) ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <?php endforeach; ?>
</urlset>
