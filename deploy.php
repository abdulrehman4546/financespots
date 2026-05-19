<?php
/**
 * GitHub Auto-Deploy Webhook
 * Upload this file to public_html/ on hosting (one time only)
 * Then GitHub will call it on every push to main branch
 */

define('DEPLOY_SECRET', 'fs_deploy_2026_secret_xK9mP');
define('REPO_ZIP',      'https://github.com/abdulrehman4546/financespots/archive/refs/heads/main.zip');
define('DEPLOY_DIR',    __DIR__);
define('LOG_FILE',      __DIR__ . '/deploy.log');

// Verify this is a real GitHub webhook request
$payload   = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$expected  = 'sha256=' . hash_hmac('sha256', $payload, DEPLOY_SECRET);

if (!hash_equals($expected, $signature)) {
    http_response_code(403);
    die('Forbidden');
}

$data   = json_decode($payload, true);
$branch = $data['ref'] ?? '';

// Only deploy on push to main branch
if ($branch !== 'refs/heads/main') {
    echo 'Not main branch — skipped';
    exit;
}

file_put_contents(LOG_FILE, date('[Y-m-d H:i:s]') . " Deploy started\n", FILE_APPEND);

// Download latest code ZIP from GitHub
$zipPath = sys_get_temp_dir() . '/financespots_deploy.zip';
$zip = file_get_contents(REPO_ZIP);
if (!$zip) {
    http_response_code(500);
    file_put_contents(LOG_FILE, date('[Y-m-d H:i:s]') . " ERROR: Could not download ZIP\n", FILE_APPEND);
    die('Failed to download');
}
file_put_contents($zipPath, $zip);

// Extract ZIP
$za = new ZipArchive();
if ($za->open($zipPath) !== true) {
    http_response_code(500);
    die('Failed to open zip');
}

$extractTo = sys_get_temp_dir() . '/financespots_extract';
if (is_dir($extractTo)) {
    exec("rm -rf " . escapeshellarg($extractTo));
}
$za->extractTo($extractTo);
$za->close();

// GitHub ZIP contains a subfolder like "financespots-main/"
$subdir = $extractTo . '/financespots-main';
if (!is_dir($subdir)) {
    die('Unexpected ZIP structure');
}

// Copy files to public_html — skip protected files
$skip = [
    'wp-config.php',
    'deploy.php',
    'deploy.log',
    'wp-content/uploads',
];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($subdir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

$count = 0;
foreach ($iterator as $item) {
    $relative = substr($item->getPathname(), strlen($subdir) + 1);

    // Skip protected files/dirs
    $skip_this = false;
    foreach ($skip as $s) {
        if (strpos($relative, $s) === 0) { $skip_this = true; break; }
    }
    if ($skip_this) continue;

    $dest = DEPLOY_DIR . '/' . $relative;
    if ($item->isDir()) {
        if (!is_dir($dest)) mkdir($dest, 0755, true);
    } else {
        copy($item->getPathname(), $dest);
        $count++;
    }
}

// Cleanup
exec("rm -rf " . escapeshellarg($extractTo));
unlink($zipPath);

file_put_contents(LOG_FILE, date('[Y-m-d H:i:s]') . " Deploy done — $count files updated\n", FILE_APPEND);

echo "Deploy successful — $count files updated";
