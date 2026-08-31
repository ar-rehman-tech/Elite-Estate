<?php
/**
 * ELITE ESTATES — BASE URL CONFIG
 *
 * This auto-detects where the project is installed.
 * No manual editing needed in most cases.
 *
 * If CSS still doesn't load, manually set:
 *   define('SITE_ROOT', '/your-folder-name/');
 */

if (!defined('SITE_ROOT')) {
    // __DIR__ = .../htdocs/real_estate/includes
    // We want  .../htdocs/real_estate/
    $projectRoot = str_replace('\\', '/', dirname(__DIR__));
    $docRoot     = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/'));

    // Relative URL to project root, e.g. "/real_estate" or ""
    $relPath = substr($projectRoot, strlen($docRoot));
    define('SITE_ROOT', rtrim($relPath, '/') . '/');
}

function siteUrl($path = '') {
    return SITE_ROOT . ltrim($path, '/');
}

function asset($path) {
    return SITE_ROOT . 'assets/' . ltrim($path, '/');
}
