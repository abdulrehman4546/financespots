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
        header( 'Cache-Control: public, max-age=300, s-maxage=3600, stale-while-revalidate=86400' );
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
    if ( 'post' === $type && isset( $object->post_name ) && in_array( $object->post_name, $redirected, true ) ) {
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
