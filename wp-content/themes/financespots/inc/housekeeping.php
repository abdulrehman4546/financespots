<?php
/**
 * Root housekeeping, delivered through the theme.
 *
 * The deploy workflow only uploads the theme folder. Three files live in the site root and cannot be
 * changed that way: the retired maintenance scripts (fix-uploads.php, create-blogs.php) that the old
 * deploys left behind, and llms.txt. They are shipped in housekeeping/ and copied to the web root
 * once per $version. The list is a fixed whitelist of file names; nothing else is ever written.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
    $version = '2026-10-08-a';
    if ( get_option( 'fs_housekeeping_version' ) === $version ) return;
    update_option( 'fs_housekeeping_version', $version, false );   // set first: never retry in a loop
    $src_dir = get_template_directory() . '/housekeeping/';
    foreach ( [ 'fix-uploads.php', 'create-blogs.php', 'llms.txt' ] as $name ) {
        $src = $src_dir . $name;
        $dst = ABSPATH . $name;
        if ( ! is_readable( $src ) ) continue;
        if ( is_file( $dst ) && md5_file( $src ) === md5_file( $dst ) ) continue;
        if ( ! is_file( $dst ) && 'llms.txt' !== $name ) continue;   // never create the retired scripts
        @copy( $src, $dst );
    }
}, 98 );
