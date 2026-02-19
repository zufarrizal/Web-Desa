<?php

if (! function_exists('asset_url')) {
    function asset_url(string $path): string
    {
        $cleanPath = ltrim($path, '/\\');
        $url = base_url($cleanPath);
        $fullPath = FCPATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $cleanPath);
        if (! is_file($fullPath)) {
            return $url;
        }

        $mtime = @filemtime($fullPath);
        if (! is_int($mtime) || $mtime <= 0) {
            return $url;
        }

        return $url . (str_contains($url, '?') ? '&' : '?') . 'v=' . $mtime;
    }
}

