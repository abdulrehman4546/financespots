<?php
// Manual deploy script — DELETE after use!
define('DEPLOY_PASS', 'fs2026deploy');
define('REPO_ZIP', 'https://github.com/abdulrehman4546/financespots/archive/refs/heads/main.zip');
define('DEPLOY_DIR', __DIR__);

if (($_GET['pass'] ?? '') !== DEPLOY_PASS) {
    die('Access denied. Use: ?pass=fs2026deploy');
}

echo "<pre>\n";
echo "Starting manual deploy...\n"; flush();

$zipPath = sys_get_temp_dir() . '/fs_deploy.zip';
echo "Downloading repo ZIP from GitHub...\n"; flush();
$zip = file_get_contents(REPO_ZIP);
if (!$zip) die("ERROR: Could not download ZIP");
file_put_contents($zipPath, $zip);
echo "Downloaded (" . round(strlen($zip)/1024/1024, 1) . " MB)\n"; flush();

$extractTo = sys_get_temp_dir() . '/fs_deploy_extract';
if (is_dir($extractTo)) exec("rm -rf " . escapeshellarg($extractTo));

$za = new ZipArchive();
if ($za->open($zipPath) !== true) die("ERROR: Could not open ZIP");
$za->extractTo($extractTo);
$za->close();
echo "Extracted ZIP\n"; flush();

$srcDir = $extractTo . '/financespots-main';
if (!is_dir($srcDir)) die("ERROR: Source folder not found in ZIP");

// Copy all files recursively, skipping sensitive files
$skip = ['wp-config.php', 'deploy.php', 'manual-deploy.php'];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($srcDir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

$count = 0;
foreach ($iterator as $item) {
    $relative = substr($item->getPathname(), strlen($srcDir) + 1);
    if (in_array(basename($relative), $skip)) continue;
    $dest = DEPLOY_DIR . '/' . $relative;
    if ($item->isDir()) {
        if (!is_dir($dest)) mkdir($dest, 0755, true);
    } else {
        copy($item->getPathname(), $dest);
        $count++;
    }
}

exec("rm -rf " . escapeshellarg($extractTo));
unlink($zipPath);

echo "Done! $count files deployed.\n";
echo "\n*** DELETE this file now! ***\n";
echo "</pre>";
