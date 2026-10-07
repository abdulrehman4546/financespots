<?php
// Retired one-time maintenance script. Overwrites the old public copy so it can no longer be run.
http_response_code( 410 );
header( 'Content-Type: text/plain; charset=utf-8' );
echo 'Gone';
