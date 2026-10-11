<?php
/**
 * Security hardening: rate limiting for public AJAX handlers, user-enumeration blocks,
 * version/technology disclosure, CSP, file-editor lock and web-server rules.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

/* Theme/plugin file editor off in wp-admin (code is deployed from git). */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) define( 'DISALLOW_FILE_EDIT', true );

/* ── Rate limiting (transients; works without a plugin) ── */
function fs_client_ip() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    foreach ( [ 'HTTP_CF_CONNECTING_IP', 'HTTP_X_REAL_IP', 'HTTP_X_FORWARDED_FOR' ] as $h ) {
        if ( empty( $_SERVER[ $h ] ) ) continue;
        $cand = trim( explode( ',', (string) $_SERVER[ $h ] )[0] );
        if ( filter_var( $cand, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) return $cand;
    }
    return $ip;
}
function fs_rate_key( $bucket ) { return 'fsrl_' . md5( $bucket ); }
/** Current count in a bucket without changing it. */
function fs_rate_count( $bucket ) {
    $d = get_transient( fs_rate_key( $bucket ) );
    return is_array( $d ) ? (int) $d[0] : 0;
}
/** Add one hit to a bucket; the window starts at the first hit. */
function fs_rate_hit( $bucket, $window ) {
    $k = fs_rate_key( $bucket ); $d = get_transient( $k ); $now = time();
    if ( ! is_array( $d ) || $d[1] <= $now ) $d = [ 0, $now + $window ];
    $d[0]++;
    set_transient( $k, $d, max( 1, $d[1] - $now ) );
    return $d[0];
}
function fs_rate_clear( $bucket ) { delete_transient( fs_rate_key( $bucket ) ); }
/** True if the action is allowed; counts the attempt. */
function fs_rate_allow( $bucket, $limit, $window ) { return fs_rate_hit( $bucket, $window ) <= $limit; }

/* ── Do not advertise technology and versions ── */
add_action( 'send_headers', function () { header_remove( 'X-Powered-By' ); }, 1 );
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );
add_filter( 'xmlrpc_enabled', '__return_false' );

/* Content-Security-Policy: framing, base tag, plugins and mixed content (a full script policy would break inline calculators). */
add_action( 'send_headers', function () {
    if ( is_admin() ) return;
    $ancestors = apply_filters( 'fs_csp_frame_ancestors', "'self'" );
    header( "Content-Security-Policy: frame-ancestors {$ancestors}; base-uri 'self'; object-src 'none'; upgrade-insecure-requests" );
}, 21 );

/* ── User enumeration ── */
add_filter( 'rest_endpoints', function ( $endpoints ) {
    if ( ! current_user_can( 'list_users' ) ) {
        foreach ( array_keys( $endpoints ) as $route ) {
            if ( 0 === strpos( $route, '/wp/v2/users' ) ) unset( $endpoints[ $route ] );
        }
    }
    return $endpoints;
} );
add_action( 'template_redirect', function () {
    if ( ! is_user_logged_in() && isset( $_GET['author'] ) ) {
        wp_safe_redirect( home_url( '/' ), 301 );
        exit;
    }
}, 0 );
/* Same message whether the user or the password is wrong on wp-login.php */
add_filter( 'login_errors', function () { return 'The username or password is incorrect.'; } );

/* ── Web-server rules (.htaccess), added once, checked, and rolled back if the site stops answering ── */
add_action( 'init', function () {
    $version = '2026-10-11-a';
    if ( get_option( 'fs_security_htaccess_v' ) === $version ) return;
    update_option( 'fs_security_htaccess_v', $version );

    $file = ABSPATH . '.htaccess';
    if ( ! file_exists( $file ) || ! is_writable( $file ) ) { update_option( 'fs_security_htaccess', 'not-writable' ); return; }
    require_once ABSPATH . 'wp-admin/includes/misc.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';

    $before = file_get_contents( $file );
    $rules = [
        '# Turn off directory listings',
        '<IfModule mod_autoindex.c>',
        'Options -Indexes',
        '</IfModule>',
        '# Do not serve files that reveal the WordPress version or leftovers',
        '<IfModule mod_authz_core.c>',
        '<FilesMatch "^(readme\.html|license\.txt|wp-config-sample\.php)$">',
        'Require all denied',
        '</FilesMatch>',
        '<FilesMatch "\.(log|sql|bak|old|orig|swp|env)$">',
        'Require all denied',
        '</FilesMatch>',
        '</IfModule>',
    ];
    insert_with_markers( $file, 'FinanceSpots Security', $rules );

    $probe = wp_remote_get( home_url( '/robots.txt?fs_chk=' . time() ), [ 'timeout' => 15, 'redirection' => 0, 'headers' => [ 'Cache-Control' => 'no-cache' ] ] );
    $code  = is_wp_error( $probe ) ? 0 : (int) wp_remote_retrieve_response_code( $probe );
    if ( $code < 200 || $code >= 400 ) {
        file_put_contents( $file, $before );
        update_option( 'fs_security_htaccess', 'rolled-back:' . $code );
        return;
    }
    update_option( 'fs_security_htaccess', 'ok:' . $code );

    /* No PHP execution inside uploads (a common malware foothold) */
    $up = wp_upload_dir();
    $uf = trailingslashit( $up['basedir'] ) . '.htaccess';
    if ( ! file_exists( $uf ) && is_dir( $up['basedir'] ) && is_writable( $up['basedir'] ) ) {
        file_put_contents( $uf, "<IfModule mod_authz_core.c>\n<FilesMatch \"\\.(php|phtml|php[0-9]|phar)$\">\nRequire all denied\n</FilesMatch>\n</IfModule>\n" );
        $att = get_posts( [ 'post_type' => 'attachment', 'numberposts' => 1, 'post_status' => 'inherit' ] );
        if ( $att ) {
            $r = wp_remote_head( wp_get_attachment_url( $att[0]->ID ), [ 'timeout' => 15 ] );
            $c = is_wp_error( $r ) ? 0 : (int) wp_remote_retrieve_response_code( $r );
            if ( $c >= 500 || 0 === $c ) { @unlink( $uf ); update_option( 'fs_security_uploads_htaccess', 'rolled-back:' . $c ); return; }
        }
        update_option( 'fs_security_uploads_htaccess', 'ok' );
    }
}, 99 );
