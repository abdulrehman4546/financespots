<?php
/**
 * SEO hardening: security headers, caching headers, social images,
 * meta length limits, sitemap hygiene, homepage schema cleanup.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

/* ── Security + cache headers (PHP-level so they work on any host) ── */
add_action( 'send_headers', function () {
    if ( is_admin() ) return;
    header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains' );
    header( 'X-Content-Type-Options: nosniff' );
    header( 'X-Frame-Options: SAMEORIGIN' );
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
    header( 'Permissions-Policy: camera=(), microphone=(), geolocation=()' );

    // Let the CDN / browser cache anonymous page views.
    $has_session = ! empty( $_COOKIE ) && (bool) preg_grep( '/^(wordpress_logged_in|wp-postpass|comment_author|fs_)/', array_keys( $_COOKIE ) );
    if ( 'GET' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) && ! is_user_logged_in() && ! $has_session && ! is_404() && ! is_search() && ! is_preview() ) {
        header_remove( 'Cache-Control' );
        header( 'Cache-Control: public, max-age=60, s-maxage=300, stale-while-revalidate=30' );
    }
}, 20 );

/* ── Default social image (og:image / twitter:image) ── */
function fs_default_social_image( $image ) {
    return $image ? $image : get_template_directory_uri() . '/assets/images/og-default.png';
}
add_filter( 'rank_math/opengraph/facebook/image', 'fs_default_social_image' );
add_filter( 'rank_math/opengraph/twitter/image',  'fs_default_social_image' );

/* ── Title / meta description length limits ── */
add_filter( 'rank_math/frontend/title', function ( $title ) {
    $title = html_entity_decode( $title, ENT_QUOTES, 'UTF-8' );
    if ( mb_strlen( $title ) <= 60 ) return $title;
    $brand = '';
    if ( preg_match( '/\s\|\s[^|]+$/u', $title, $m ) ) {
        $brand = $m[0];
        $title = substr( $title, 0, -strlen( $brand ) );
    }
    $parts = preg_split( '/\s(?:--|—|–)\s/u', $title );
    $title = trim( $parts[0] ) . $brand;
    if ( mb_strlen( $title ) > 60 ) {
        $title = rtrim( mb_substr( $title, 0, 57 ) ) . '...';
    }
    return $title;
} );

add_filter( 'rank_math/frontend/description', function ( $desc ) {
    $desc = trim( wp_strip_all_tags( html_entity_decode( (string) $desc, ENT_QUOTES, 'UTF-8' ) ) );

    if ( '' === $desc ) {
        if ( is_category() || is_tax() ) {
            $term = get_queried_object();
            $desc = term_description( $term->term_id, $term->taxonomy );
            $desc = trim( wp_strip_all_tags( $desc ) );
            if ( '' === $desc ) {
                $desc = sprintf( 'Free guides and calculators about %s. Practical, up-to-date personal finance advice from FinanceSpots.', $term->name );
            }
        } elseif ( is_singular() ) {
            $desc = trim( wp_strip_all_tags( get_the_excerpt() ) );
            if ( '' === $desc ) {
                $desc = wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', get_queried_object_id() ) ), 28, '' );
            }
        }
    }

    if ( '' === $desc && is_singular() ) {
        $desc = sprintf( '%s at FinanceSpots: free financial calculators, guides and tools with instant results and no signup.', wp_strip_all_tags( get_the_title() ) );
    }

    if ( mb_strlen( $desc ) > 158 ) {
        $cut  = mb_substr( $desc, 0, 157 );
        $last = max( mb_strrpos( $cut, ' ' ), 0 );
        $desc = rtrim( mb_substr( $cut, 0, $last ?: 157 ), " ,;:-" ) . '…';
    }
    return $desc;
} );

/* ── Sitemap hygiene: no redirected guides, no empty/noindex terms ── */
add_filter( 'rank_math/sitemap/entry', function ( $url, $type, $object ) {
    static $redirected = [
        'mortgage-calculator-guide', 'compound-interest-guide', 'investment-calculator-guide',
        'emergency-fund-guide', 'tax-calculator-guide-2026', 'crypto-profit-guide',
        'personal-loan-guide', 'budget-planner-guide', 'retirement-planning-guide',
        'pay-off-debt-fast-guide',
    ];
    if ( ( 'post' === $type || 'page' === $type ) && isset( $object->post_name ) && ( in_array( $object->post_name, $redirected, true ) || 'pro-success' === $object->post_name ) ) {
        return false;
    }
    if ( 'term' === $type && isset( $object->count ) && (int) $object->count === 0 ) {
        return false;
    }
    return $url;
}, 20, 3 );

/* ── Homepage / global Rank Math schema cleanup ── */
add_filter( 'rank_math/json_ld', function ( $data, $jsonld ) {
    // No empty author entity (author archives are disallowed in robots.txt).
    foreach ( $data as $key => $entity ) {
        if ( is_array( $entity ) && ( $entity['@type'] ?? '' ) === 'Person' && isset( $entity['url'] ) && rtrim( $entity['url'], '/' ) === rtrim( home_url( '/author' ), '/' ) ) {
            unset( $data[ $key ] );
        }
    }
    if ( is_front_page() ) {
        // Homepage is a WebSite, not an Article.
        unset( $data['richSnippet'] );
        if ( isset( $data['webpage']['isPartOf'] ) ) {
            $data['webpage']['@type'] = 'WebPage';
        }
        // Real Organization with logo; drop the nameless Person/Organization hybrid.
        $data['publisher'] = [
            '@type'  => 'Organization',
            '@id'    => home_url( '/#organization' ),
            'name'   => 'FinanceSpots',
            'url'    => home_url( '/' ),
            'logo'   => [
                '@type'  => 'ImageObject',
                'url'    => get_template_directory_uri() . '/assets/images/logo.png',
                'width'  => 512,
                'height' => 512,
            ],
        ];
        foreach ( [ 'webpage', 'website' ] as $k ) {
            if ( isset( $data[ $k ] ) ) {
                $data[ $k ]['publisher'] = [ '@id' => home_url( '/#organization' ) ];
                unset( $data[ $k ]['about'] );
            }
        }
    }
    return $data;
}, 1000, 2 );

/* ── Category/tag/archive pages need an H1 (index.php fallback) ── */
add_action( 'fs_archive_heading', function () {
    if ( is_archive() && ! is_post_type_archive( 'fs_tool' ) ) {
        echo '<header class="fs-archive-header"><h1 class="fs-archive-title">' . esc_html( wp_strip_all_tags( get_the_archive_title() ) ) . '</h1>';
        $d = term_description();
        if ( $d ) echo '<div class="fs-archive-desc">' . wp_kses_post( $d ) . '</div>';
        echo '</header>';
    }
} );

/* ── Images inside post content: guarantee alt + lazy loading ── */
add_filter( 'the_content', function ( $content ) {
    if ( false === stripos( $content, '<img' ) ) return $content;
    $title = esc_attr( wp_strip_all_tags( get_the_title() ) );
    $n     = 0;
    return preg_replace_callback( '/<img\b[^>]*>/i', function ( $m ) use ( $title, &$n ) {
        $tag = $m[0];
        $n++;
        if ( ! preg_match( '/\salt=(["\'])(.+?)\1/i', $tag ) ) {
            $tag = preg_match( '/\salt=(["\'])\1/i', $tag )
                ? preg_replace( '/\salt=(["\'])\1/i', ' alt="' . $title . '"', $tag )
                : preg_replace( '/<img\b/i', '<img alt="' . $title . '"', $tag, 1 );
        }
        if ( $n > 1 && ! preg_match( '/\sloading=/i', $tag ) ) {
            $tag = preg_replace( '/<img\b/i', '<img loading="lazy"', $tag, 1 );
        }
        if ( ! preg_match( '/\sdecoding=/i', $tag ) ) {
            $tag = preg_replace( '/<img\b/i', '<img decoding="async"', $tag, 1 );
        }
        return $tag;
    }, $content );
}, 30 );

/**
 * Rank Math caches sitemap XML on disk and in transients. After slugs or
 * exclusions change, the cached files can list stale URLs (old redirected
 * guide slugs) and omit the real posts. Clear the cache once per version.
 * Change $version below whenever the sitemap rules above change.
 *
 * Deliberately avoids WP_Filesystem (it can fatal on hosts that fall back to
 * the FTP method) and records the version first, so it can never retry in a loop.
 */
add_action( 'init', function () {
    $version = '2026-10-10-a';
    if ( get_option( 'fs_sitemap_flush_version' ) === $version ) return;
    update_option( 'fs_sitemap_flush_version', $version, false );
    try {
        $dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'rank-math/';
        if ( class_exists( '\\RankMath\\Sitemap\\Cache' ) ) {
            $dir = \RankMath\Sitemap\Cache::get_cache_directory();
        }
        foreach ( (array) glob( $dir . '*.xml' ) as $file ) {
            if ( is_file( $file ) ) @unlink( $file );
        }
        global $wpdb;
        $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '\_transient\_sitemap\_%' OR option_name LIKE '\_transient\_timeout\_sitemap\_%'" );
    } catch ( \Throwable $e ) {
        // Cache will expire on its own; never block a page load over this.
    }
}, 99 );

/**
 * The site no longer claims "110+" tools: duplicate tools were redirected
 * (see inc/tool-redirects.php), leaving about 55 distinct ones. Rewrite the
 * stored Rank Math titles/descriptions once. Options are updated through the
 * API (not raw SQL) because they are serialized.
 */
add_action( 'init', function () {
    $version = '2026-10-06-count';
    if ( get_option( 'fs_toolcount_fix' ) === $version ) return;
    update_option( 'fs_toolcount_fix', $version, false );
    global $wpdb;
    $wpdb->query( "UPDATE {$wpdb->postmeta} SET meta_value = REPLACE( meta_value, '110+', '55+' ) WHERE meta_key LIKE 'rank\\_math\\_%' AND meta_value LIKE '%110+%'" );
    foreach ( [ 'rank_math_title_fs_tool_archive', 'rank_math_description_fs_tool_archive' ] as $name ) {
        $val = get_option( $name );
        if ( is_string( $val ) && false !== strpos( $val, '110+' ) ) update_option( $name, str_replace( '110+', '55+', $val ) );
    }
    $titles = get_option( 'rank-math-options-titles' );
    if ( is_array( $titles ) ) {
        array_walk_recursive( $titles, function ( &$v ) { if ( is_string( $v ) ) $v = str_replace( '110+', '55+', $v ); } );
        update_option( 'rank-math-options-titles', $titles );
    }
}, 95 );
