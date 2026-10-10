<?php
/**
 * Duplicate tool consolidation.
 *
 * Many tools were the same generic calculator under a different title, which
 * Google treats as duplicate content ("crawled, currently not indexed"). Each
 * duplicate is 301-redirected to the tool that really does the job, removed
 * from sitemaps and hidden from tool listings. The posts stay in the database,
 * so a redirect can be removed here when a real, distinct calculator is built.
 *
 * Kept on purpose (rank in Google today but still show a generic calculator;
 * build a real calculator for each next): property-tax-calculator,
 * ira-calculator, nft-roi-calculator.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

/** old slug => slug of the tool that replaces it */
function fs_tool_redirect_map() {
    return [
        /* budgets */
        'expense-tracker' => 'monthly-budget-planner', 'income-vs-expense-analyzer' => 'monthly-budget-planner',
        'household-budget-calculator' => 'monthly-budget-planner', 'zero-based-budget-calculator' => 'monthly-budget-planner',
        'annual-budget-planner' => 'monthly-budget-planner', 'cash-flow-calculator' => 'monthly-budget-planner',
        'bill-payment-planner' => 'monthly-budget-planner', 'grocery-budget-calculator' => 'monthly-budget-planner',
        'entertainment-budget-calculator' => 'monthly-budget-planner',
        /* compound interest and savings-rate products */
        'investment-growth-calculator' => 'compound-interest-calculator', 'mutual-fund-calculator' => 'compound-interest-calculator',
        'etf-calculator' => 'compound-interest-calculator', 'future-value-calculator' => 'compound-interest-calculator',
        'investment-fee-calculator' => 'compound-interest-calculator',
        'cd-calculator' => 'savings-goal-calculator', 'money-market-calculator' => 'savings-goal-calculator',
        'high-yield-savings-calculator' => 'savings-goal-calculator',
        /* income tax */
        'tax-deduction-estimator' => 'income-tax-calculator', 'tax-bracket-calculator' => 'income-tax-calculator',
        'amt-calculator' => 'income-tax-calculator', 'w-4-calculator' => 'income-tax-calculator',
        'state-tax-calculator' => 'income-tax-calculator', 'tax-withholding-calculator' => 'income-tax-calculator',
        'effective-tax-rate-calculator' => 'income-tax-calculator',
        /* retirement */
        'pension-calculator' => 'retirement-savings-calculator', 'social-security-calculator' => 'retirement-savings-calculator',
        'retirement-income-calculator' => 'retirement-savings-calculator', 'rmd-calculator' => 'retirement-savings-calculator',
        'roth-conversion-calculator' => 'retirement-savings-calculator', 'retirement-withdrawal-calculator' => 'retirement-savings-calculator',
        'early-retirement-calculator' => 'fire-calculator',
        /* currency */
        'historical-exchange-rate' => 'live-currency-converter', 'forex-pip-calculator' => 'live-currency-converter',
        'currency-strength-meter' => 'live-currency-converter', 'cross-rate-calculator' => 'live-currency-converter',
        'travel-money-calculator' => 'live-currency-converter', 'currency-comparison-tool' => 'live-currency-converter',
        /* savings goals */
        'savings-rate-calculator' => 'savings-goal-calculator', 'round-up-savings-calculator' => 'savings-goal-calculator',
        'vacation-savings-calculator' => 'savings-goal-calculator', 'down-payment-savings-calculator' => 'savings-goal-calculator',
        'savings-milestone-tracker' => 'savings-goal-calculator',
        /* portfolio */
        'asset-allocation-calculator' => 'portfolio-analyzer', 'portfolio-rebalancing-tool' => 'portfolio-analyzer',
        'crypto-portfolio-tracker' => 'portfolio-analyzer',
        /* capital gains and crypto */
        'capital-gains-calculator' => 'capital-gains-tax-calculator', 'crypto-tax-calculator' => 'capital-gains-tax-calculator',
        'cryptocurrency-converter' => 'crypto-converter', 'bitcoin-halving-countdown' => 'crypto-converter',
        'stock-return-calculator' => 'roi-calculator', 'crypto-dca-calculator' => 'dollar-cost-averaging',
        'monthly-payment-calculator' => 'amortization-calculator', 'quarterly-tax-calculator' => 'self-employment-tax-calculator',
        'yield-farming-calculator' => 'staking-rewards-calculator',
    ];
}

/** Tools with no matching calculator and no better target: keep reachable, ask Google not to index. */
function fs_tool_noindex_slugs() {
    /* sales-tax, estate-tax, irs-penalty and gas-fee now have real calculators and content, so they are indexable again */
    return [];
}

/* 301 to the replacement tool */
add_action( 'template_redirect', function () {
    if ( ! is_singular( 'fs_tool' ) ) return;
    $map  = fs_tool_redirect_map();
    $slug = get_post_field( 'post_name', get_queried_object_id() );
    if ( ! isset( $map[ $slug ] ) ) return;
    $target = get_page_by_path( $map[ $slug ], OBJECT, 'fs_tool' );
    if ( $target && 'publish' === $target->post_status ) {
        wp_safe_redirect( get_permalink( $target ), 301 );
        exit;
    }
}, 1 );

/* IDs of redirected tools (cached per request) */
function fs_redirected_tool_ids() {
    static $ids = null;
    if ( null !== $ids ) return $ids;
    global $wpdb;
    $slugs = array_keys( fs_tool_redirect_map() );
    $ph    = implode( ',', array_fill( 0, count( $slugs ), '%s' ) );
    $ids   = array_map( 'intval', (array) $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'fs_tool' AND post_name IN ($ph)", $slugs ) ) );
    return $ids;
}

/* Hide redirected tools from every front-end listing (archives, grids, widgets, related lists) */
add_action( 'pre_get_posts', function ( $q ) {
    if ( is_admin() || $q->is_singular() ) return;
    $pt = (array) $q->get( 'post_type' );
    if ( ! in_array( 'fs_tool', $pt, true ) && ! $q->get( 'fs_tool_cat' ) && ! $q->is_tax( 'fs_tool_cat' ) && ! $q->is_post_type_archive( 'fs_tool' ) ) return;
    $ids = fs_redirected_tool_ids();
    if ( $ids ) $q->set( 'post__not_in', array_merge( (array) $q->get( 'post__not_in' ), $ids ) );
} );

/* Keep them out of sitemaps */
add_filter( 'rank_math/sitemap/entry', function ( $url, $type, $object ) {
    if ( 'post' === $type && isset( $object->post_type, $object->post_name ) && 'fs_tool' === $object->post_type
        && ( isset( fs_tool_redirect_map()[ $object->post_name ] ) || in_array( $object->post_name, fs_tool_noindex_slugs(), true ) ) ) {
        return false;
    }
    return $url;
}, 10, 3 );

/* noindex for the placeholder-calculator tools */
add_filter( 'rank_math/frontend/robots', function ( $robots ) {
    if ( is_singular( 'fs_tool' ) && in_array( get_post_field( 'post_name', get_queried_object_id() ), fs_tool_noindex_slugs(), true ) ) {
        $robots['index'] = 'noindex';
    }
    return $robots;
} );
