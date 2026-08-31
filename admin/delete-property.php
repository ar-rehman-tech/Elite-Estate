<?php
require_once '../includes/config.php';
include '../includes/db.php';
include '../includes/auth.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: properties.php');
    exit;
}

$db   = DB::connect();
$stmt = $db->prepare("SELECT id, title, img FROM properties WHERE id = ?");
$stmt->execute([$id]);
$prop = $stmt->fetch();

if (!$prop) {
    header('Location: properties.php?err=notfound');
    exit;
}

// ── Confirm token check (CSRF-lite) ──────────────────────────────────────
$token    = $_GET['token'] ?? '';
$expected = hash_hmac('sha256', 'delete-' . $id, session_id());
if (!hash_equals($expected, $token)) {
    header('Location: properties.php?err=csrf');
    exit;
}

// Delete uploaded image file if stored locally.
// The DB stores img as a browser-relative path such as "../assets/uploads/prop_xxx.jpg"
// (relative to /admin/, so <img> tags resolve correctly). Naively prepending the
// project root to that string (dirname(__DIR__) . '/' . $prop['img']) doubles up the
// "../" and resolves to a path ONE LEVEL ABOVE the project root, so file_exists()
// always failed and the file was silently never deleted. Resolve against the known
// uploads directory using basename() instead — correct regardless of how the stored
// path is prefixed, and safe against path traversal since only the filename is used.
if (!empty($prop['img']) && strpos($prop['img'], 'assets/uploads/') !== false) {
    $filename  = basename(parse_url($prop['img'], PHP_URL_PATH));
    $localPath = dirname(__DIR__) . '/assets/uploads/' . $filename;
    if ($filename && file_exists($localPath)) {
        @unlink($localPath);
    }
}

$db->prepare("DELETE FROM properties WHERE id = ?")->execute([$id]);
header('Location: properties.php?msg=' . urlencode('Property "' . $prop['title'] . '" deleted.'));
exit;
