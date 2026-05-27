<?php
$pass = $_GET['pass'] ?? '';
if ( $pass !== 'fs2026deploy' ) { http_response_code(403); die('Forbidden'); }

$repo     = 'Abdul-Rahman-dev/financespots';
$branch   = 'main';
$zip_url  = "https://github.com/{$repo}/archive/refs/heads/{$branch}.zip";
$zip_file = sys_get_temp_dir() . '/fs_deploy.zip';
$extract  = sys_get_temp_dir() . '/fs_deploy_extract';
$root     = $_SERVER['DOCUMENT_ROOT'];

echo "<pre>Starting deploy...\n";
$zip_data = file_get_contents( $zip_url );
if ( ! $zip_data ) { die("ERROR: Could not download ZIP\n"); }
file_put_contents( $zip_file, $zip_data );
echo "Downloaded ZIP (" . number_format(strlen($zip_data)) . " bytes)\n";

if ( is_dir($extract) ) exec("rm -rf " . escapeshellarg($extract));
$zip = new ZipArchive();
if ( $zip->open($zip_file) !== true ) { die("ERROR: Could not open ZIP\n"); }
$zip->extractTo($extract);
$zip->close();
echo "Extracted ZIP\n";

$folders = glob($extract . '/*', GLOB_ONLYDIR);
if ( empty($folders) ) { die("ERROR: No folder found\n"); }
$src = $folders[0];

$skip = ['wp-config.php','manual-deploy.php','deploy.php','.htaccess'];

function deploy_copy($s,$d,$skip,&$n){
    foreach(scandir($s) as $i){
        if($i==='.'||$i==='..') continue;
        if(in_array($i,$skip)){echo "  SKIPPED: $i\n";continue;}
        $sp=$s.'/'.$i; $dp=$d.'/'.$i;
        if(is_dir($sp)){if(!is_dir($dp))mkdir($dp,0755,true);deploy_copy($sp,$dp,$skip,$n);}
        else{copy($sp,$dp);$n++;}
    }
}
$n=0; deploy_copy($src,$root,$skip,$n);
echo "Copied $n files\n";
unlink($zip_file); exec("rm -rf ".escapeshellarg($extract));
echo "Done! ".date('Y-m-d H:i:s')."\nVisit wp-admin to activate the indexing fix.\n</pre>";
