<?php
$docLang         = $docLang ?? 'id';
$dataTheme       = $dataTheme ?? 'light';
$pageTitle       = $pageTitle ?? 'Portal Desa';
$metaDescription = $metaDescription ?? '';
$metaKeywords    = $metaKeywords ?? '';
$canonical       = $canonical ?? site_url('/');
$ogType          = $ogType ?? 'website';
$ogSiteName      = $ogSiteName ?? 'Portal Desa';
$ogTitle         = $ogTitle ?? $pageTitle;
$ogDescription   = $ogDescription ?? $metaDescription;
$ogUrl           = $ogUrl ?? $canonical;
$ogImage         = $ogImage ?? base_url('assets/images/card-image.png');
$twitterCard     = $twitterCard ?? 'summary_large_image';
$twitterTitle    = $twitterTitle ?? $ogTitle;
$twitterDesc     = $twitterDesc ?? $ogDescription;
$twitterImage    = $twitterImage ?? $ogImage;
$robots          = $robots ?? null;
$extraMeta       = $extraMeta ?? '';
$pageStyles      = $pageStyles ?? '';
?>
<!DOCTYPE html>
<html lang="<?= esc($docLang) ?>" data-theme="<?= esc($dataTheme) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($pageTitle) ?></title>
    <?php if ($metaDescription !== '') : ?><meta name="description" content="<?= esc($metaDescription) ?>"><?php endif; ?>
    <?php if ($metaKeywords !== '') : ?><meta name="keywords" content="<?= esc($metaKeywords) ?>"><?php endif; ?>
    <link rel="canonical" href="<?= esc($canonical) ?>">
    <meta property="og:type" content="<?= esc($ogType) ?>">
    <meta property="og:site_name" content="<?= esc($ogSiteName) ?>">
    <meta property="og:title" content="<?= esc($ogTitle) ?>">
    <meta property="og:description" content="<?= esc($ogDescription) ?>">
    <meta property="og:url" content="<?= esc($ogUrl) ?>">
    <meta property="og:image" content="<?= esc($ogImage) ?>">
    <meta name="twitter:card" content="<?= esc($twitterCard) ?>">
    <meta name="twitter:title" content="<?= esc($twitterTitle) ?>">
    <meta name="twitter:description" content="<?= esc($twitterDesc) ?>">
    <meta name="twitter:image" content="<?= esc($twitterImage) ?>">
    <?php if ($robots !== null) : ?><meta name="robots" content="<?= esc((string) $robots) ?>"><?php endif; ?>
    <?= $extraMeta ?>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/main.min.css') ?>" rel="stylesheet">
    <?php if ($pageStyles !== '') : ?>
    <style>
<?= $pageStyles ?>
    </style>
    <?php endif; ?>
</head>
<body>
