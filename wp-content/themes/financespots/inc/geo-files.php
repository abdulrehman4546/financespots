<?php
/**
 * llms.txt for AI assistants (GEO), built from the live site so it never goes stale, and written to the web root
 * whenever a post or tool is published. If the file cannot be written, /llms.txt is served dynamically.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_build_llms_txt() {
    $site = home_url( '/' );
    $o  = "# FinanceSpots\n\n";
    $o .= "> Free calculators and plain-English guides on loans, taxes, saving, investing and retirement. Every tool shows its formula, a worked example and its sources. Figures are checked against official sources (IRS, SSA, CFPB, Federal Student Aid) and each guide shows the date it was last updated. Educational content, not personal financial advice.\n\n";
    $o .= "## About\n\n- Site: {$site}\n- Publisher: Abdul Rahman\n- How content is written and corrected: " . home_url( '/editorial-policy/' ) . "\n- Contact form: " . home_url( '/contact/' ) . "\n- Content is drafted with AI writing tools and checked against primary sources; see the editorial policy.\n\n";
    $guides = get_posts( [ 'post_type' => 'post', 'post_status' => 'publish', 'numberposts' => 200, 'orderby' => 'modified', 'order' => 'DESC' ] );
    if ( $guides ) {
        $o .= "## Guides\n\n";
        foreach ( $guides as $g ) {
            $ex = trim( wp_strip_all_tags( $g->post_excerpt ) );
            $o .= '- [' . wp_strip_all_tags( $g->post_title ) . '](' . get_permalink( $g ) . ')' . ( $ex ? ': ' . $ex : '' ) . ' (updated ' . get_the_modified_date( 'Y-m-d', $g ) . ")\n";
        }
        $o .= "\n";
    }
    $terms = get_terms( [ 'taxonomy' => 'fs_tool_cat', 'hide_empty' => true ] );
    if ( ! is_wp_error( $terms ) && $terms ) {
        $o .= "## Calculators\n\n";
        foreach ( $terms as $t ) {
            $tools = get_posts( [ 'post_type' => 'fs_tool', 'post_status' => 'publish', 'numberposts' => 100, 'orderby' => 'title', 'order' => 'ASC', 'fs_tool_cat' => $t->slug ] );
            if ( function_exists( 'fs_redirected_tool_ids' ) ) {
                $skip  = fs_redirected_tool_ids();
                $tools = array_values( array_filter( $tools, function ( $p ) use ( $skip ) { return ! in_array( $p->ID, $skip, true ); } ) );
            }
            if ( ! $tools ) continue;
            $o .= '### ' . $t->name . "\n";
            foreach ( $tools as $p ) {
                $ex = trim( wp_strip_all_tags( $p->post_excerpt ) );
                $o .= '- [' . wp_strip_all_tags( $p->post_title ) . '](' . get_permalink( $p ) . ')' . ( $ex ? ': ' . $ex : '' ) . "\n";
            }
            $o .= "\n";
        }
    }
    $o .= "## Key pages\n\n- [All tools](" . home_url( '/tools/' ) . ")\n- [Blog](" . home_url( '/blog/' ) . ")\n- [About](" . home_url( '/about/' ) . ")\n- [Editorial policy](" . home_url( '/editorial-policy/' ) . ")\n- [Privacy policy](" . home_url( '/privacy-policy/' ) . ")\n";
    return $o;
}

function fs_write_llms_txt() {
    $file = ABSPATH . 'llms.txt';
    if ( ( file_exists( $file ) && ! is_writable( $file ) ) || ( ! file_exists( $file ) && ! is_writable( ABSPATH ) ) ) return false;
    return false !== file_put_contents( $file, fs_build_llms_txt() );
}

/* Rewrite the file when a post or tool changes status (throttled to once a minute) */
add_action( 'transition_post_status', function ( $new, $old, $post ) {
    if ( $new === $old || ! in_array( $post->post_type, [ 'post', 'fs_tool' ], true ) ) return;
    if ( 'publish' !== $new && 'publish' !== $old ) return;
    if ( get_transient( 'fs_llms_recent' ) ) return;
    set_transient( 'fs_llms_recent', 1, MINUTE_IN_SECONDS );
    add_action( 'shutdown', 'fs_write_llms_txt' );
}, 20, 3 );

/* Regenerate once per deploy version, and serve dynamically if the file could not be written */
add_action( 'init', function () {
    $v = '2026-10-19-a';
    if ( get_option( 'fs_llms_v' ) !== $v ) { update_option( 'fs_llms_v', $v ); add_action( 'shutdown', 'fs_write_llms_txt' ); }
    $path = isset( $_SERVER['REQUEST_URI'] ) ? parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
    if ( '/llms.txt' === $path ) {
        status_header( 200 );
        header( 'Content-Type: text/plain; charset=utf-8' );
        header( 'Cache-Control: public, max-age=3600' );
        echo fs_build_llms_txt();
        exit;
    }
}, 50 );

/* ── Google Analytics 4 (G-H0MTYBC4FT) with consent mode: analytics cookies stay off until the cookie banner is accepted ── */
define( 'FS_GA4_ID', 'G-H0MTYBC4FT' );
add_action( 'wp_head', function () {
    if ( is_admin() || is_user_logged_in() ) return;   // do not count the owner's own visits
    $id = esc_attr( FS_GA4_ID );
    echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $id . '"></script>' . "\n";
    echo '<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}'
       . 'var fsOk=false;try{fsOk=localStorage.getItem("fs_cookie_consent")==="accepted";}catch(e){}'
       . 'gtag("consent","default",{analytics_storage:fsOk?"granted":"denied",ad_storage:fsOk?"granted":"denied",ad_user_data:fsOk?"granted":"denied",ad_personalization:fsOk?"granted":"denied"});'
       . 'gtag("js",new Date());gtag("config","' . $id . '");'
       . 'document.addEventListener("click",function(e){var b=e.target.closest&&e.target.closest("#fs-cookie-accept");if(b){gtag("consent","update",{analytics_storage:"granted",ad_storage:"granted",ad_user_data:"granted",ad_personalization:"granted"});}});'
       . '</script>' . "\n";
}, 2 );
