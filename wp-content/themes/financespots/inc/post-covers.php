<?php
/**
 * Featured images for blog posts. Covers are generated offline (docs/tools/make-covers.py) and shipped in
 * assets/images/posts/<slug>.jpg; any post without a featured image gets its cover copied into the media library.
 * Runs once per version, a few posts per request so image resizing never hits the PHP time limit.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_attach_post_cover( $post_id ) {
    if ( has_post_thumbnail( $post_id ) ) return false;
    $slug = get_post_field( 'post_name', $post_id );
    $src  = get_template_directory() . '/assets/images/posts/' . $slug . '.jpg';
    if ( ! $slug || ! file_exists( $src ) ) return false;
    $up = wp_upload_bits( $slug . '-cover.jpg', null, file_get_contents( $src ) );
    if ( ! empty( $up['error'] ) ) return false;
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $title = html_entity_decode( get_the_title( $post_id ), ENT_QUOTES, 'UTF-8' );
    $att   = wp_insert_attachment( [ 'post_mime_type' => 'image/jpeg', 'post_title' => $title, 'post_status' => 'inherit' ], $up['file'], $post_id );
    if ( ! $att || is_wp_error( $att ) ) return false;
    wp_update_attachment_metadata( $att, wp_generate_attachment_metadata( $att, $up['file'] ) );
    update_post_meta( $att, '_wp_attachment_image_alt', $title );
    return set_post_thumbnail( $post_id, $att );
}

add_action( 'init', function () {
    $v = '2026-10-24-a';
    if ( get_option( 'fs_post_covers_v' ) === $v || get_transient( 'fs_post_covers_lock' ) ) return;
    set_transient( 'fs_post_covers_lock', 1, 2 * MINUTE_IN_SECONDS );
    $ids  = get_posts( [ 'post_type' => 'post', 'post_status' => [ 'publish', 'future' ], 'numberposts' => 300, 'fields' => 'ids' ] );
    $todo = array_values( array_filter( $ids, function ( $id ) {
        return ! has_post_thumbnail( $id ) && file_exists( get_template_directory() . '/assets/images/posts/' . get_post_field( 'post_name', $id ) . '.jpg' );
    } ) );
    foreach ( array_slice( $todo, 0, 6 ) as $id ) fs_attach_post_cover( $id );
    if ( count( $todo ) <= 6 ) update_option( 'fs_post_covers_v', $v );
    delete_transient( 'fs_post_covers_lock' );
}, 40 );   // after new-content.php creates the posts (priority 30)
