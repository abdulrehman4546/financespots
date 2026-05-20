<?php
// One-time script to download uploads from GitHub
// DELETE this file after running!

define('REPO_ZIP', 'https://github.com/abdulrehman4546/financespots/archive/refs/heads/main.zip');
define('PUBLIC_HTML', __DIR__);

echo "<pre>\n";
echo "Downloading repo ZIP...\n";
flush();

$zipPath = sys_get_temp_dir() . '/fs_fix_uploads.zip';
$zip = file_get_contents(REPO_ZIP);
if (!$zip) { die("ERROR: Could not download ZIP"); }
file_put_contents($zipPath, $zip);
echo "Downloaded (" . round(strlen($zip)/1024) . " KB)\n";

$extractTo = sys_get_temp_dir() . '/fs_fix_extract';
if (is_dir($extractTo)) {
    exec("rm -rf " . escapeshellarg($extractTo));
}

$za = new ZipArchive();
if ($za->open($zipPath) !== true) { die("ERROR: Could not open zip"); }
$za->extractTo($extractTo);
$za->close();
echo "Extracted\n";

$subdir = $extractTo . '/financespots-main/wp-content/uploads';
if (!is_dir($subdir)) { die("ERROR: uploads folder not found in ZIP"); }

$destUploads = PUBLIC_HTML . '/wp-content/uploads';
if (!is_dir($destUploads)) { mkdir($destUploads, 0755, true); }

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($subdir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

$count = 0;
foreach ($iterator as $item) {
    $relative = substr($item->getPathname(), strlen($subdir) + 1);
    $dest = $destUploads . '/' . $relative;
    if ($item->isDir()) {
        if (!is_dir($dest)) mkdir($dest, 0755, true);
    } else {
        copy($item->getPathname(), $dest);
        $count++;
    }
}

exec("rm -rf " . escapeshellarg($extractTo));
unlink($zipPath);

echo "Done! $count files copied to wp-content/uploads/\n";
echo "\n*** DELETE this file now! Visit: " . dirname($_SERVER['SCRIPT_NAME']) . "/fix-uploads.php ***\n";
echo "</pre>";
