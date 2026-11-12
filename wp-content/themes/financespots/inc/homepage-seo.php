<?php
/**
 * Fix the homepage's Rank Math title/description: drop the year-stamped
 * title and update the stale "55+" tool count to the current ~70+.
 * Rank Math meta isn't REST-exposed, so this updates it directly once.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
    $version = '2026-11-12-a';
    if ( get_option( 'fs_homepage_seo_version' ) === $version ) return;
    update_option( 'fs_homepage_seo_version', $version );

    $home = get_page_by_path( 'home', OBJECT, 'page' );
    if ( ! $home ) return;

    update_post_meta( $home->ID, 'rank_math_title', 'Free Financial Calculators & Tools | FinanceSpots' );
    update_post_meta( $home->ID, 'rank_math_description', 'FinanceSpots offers 70+ free financial calculators for mortgages, loans, taxes, retirement and crypto. Instant results, PDF export, no signup needed.' );
}, 30 );
