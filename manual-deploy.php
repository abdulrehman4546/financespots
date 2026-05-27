<?php
$pass = $_GET['pass'] ?? '';
if ( $pass !== 'fs2026deploy' ) { http_response_code(403); die('Forbidden'); }

$repo     = 'abdulrehman4546/financespots';
$branch   = 'main';
$zip_url  = "https://github.com/{$repo}/archive/refs/heads/{$branch}.zip";
$zip_file = sys_get_temp_dir() . '/fs_deploy.zip';
$extract  = sys_get_temp_dir() . '/fs_deploy_extract';
$root     = $_SERVER['DOCUMENT_ROOT'];

echo "<pre>Starting deploy...\n";
flush();

// Download using cURL (works even when allow_url_fopen is off)
if ( ! function_exists('curl_init') ) { die("ERROR: cURL not available on this server\n"); }

$ch = curl_init($zip_url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT      => 'Mozilla/5.0',
    CURLOPT_TIMEOUT        => 120,
    CURLOPT_MAXREDIRS      => 5,
]);
$zip_data = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ( ! $zip_data || $http_code !== 200 ) {
    die("ERROR: Download failed. HTTP $http_code. cURL: $curl_error\n");
}
file_put_contents( $zip_file, $zip_data );
echo "Downloaded ZIP (" . number_format(strlen($zip_data)) . " bytes)\n";
flush();

if ( is_dir($extract) ) { exec("rm -rf " . escapeshellarg($extract)); }
$zip = new ZipArchive();
if ( $zip->open($zip_file) !== true ) { die("ERROR: Could not open ZIP\n"); }
$zip->extractTo($extract);
$zip->close();
echo "Extracted ZIP\n";
flush();

$folders = glob($extract . '/*', GLOB_ONLYDIR);
if ( empty($folders) ) { die("ERROR: No folder found after extraction\n"); }
$src = $folders[0];
echo "Source: $src\n";

$skip = ['wp-config.php','manual-deploy.php','deploy.php','.htaccess'];

function deploy_copy($s,$d,$skip,&$n){
    foreach(scandir($s) as $i){
        if($i==='.'||$i==='..') continue;
        if(in_array($i,$skip)){ echo "  SKIPPED: $i\n"; continue; }
        $sp=$s.'/'.$i; $dp=$d.'/'.$i;
        if(is_dir($sp)){ if(!is_dir($dp)) mkdir($dp,0755,true); deploy_copy($sp,$dp,$skip,$n); }
        else{ copy($sp,$dp); $n++; }
    }
}

$n = 0;
deploy_copy($src, $root, $skip, $n);
echo "Copied $n files\n";

unlink($zip_file);
exec("rm -rf " . escapeshellarg($extract));
echo "Cleaned up temp files\n";
echo "\nDeploy complete! " . date('Y-m-d H:i:s') . "\n";
echo "Now visit: https://financespots.com/wp-admin/ to activate indexing fix.\n</pre>";
