<?php
/**
 * Editorial Policy page (created once) and the /ads.txt file (served once an AdSense publisher ID is saved).
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_editorial_policy_html() {
    $contact = esc_url( home_url( '/contact/' ) );
    $about   = esc_url( home_url( '/about/' ) );
    $privacy = esc_url( home_url( '/privacy-policy/' ) );
    return '<p>FinanceSpots publishes free calculators and plain-English guides about loans, taxes, saving, investing and retirement. This page explains how that content is written, checked and corrected, and how advertising fits in. It applies to every calculator page, guide and category page on the site.</p>'
        . '<h2>Who is responsible</h2><p>The site is built and run by Abdul Rahman (see the <a href="' . $about . '">About page</a>). He is responsible for what is published. FinanceSpots is not a licensed financial adviser, tax professional, mortgage lender or law firm, and nothing on the site is personal advice.</p>'
        . '<h2>How content is written</h2><p>Guides and calculator pages are drafted with the help of AI writing tools and then checked against the primary sources named on each page. We use AI to speed up drafting, not to replace checking. Every guide states what it covers, shows its numbers and links to the calculator behind them, so you can test your own figures instead of relying on a rule of thumb.</p>'
        . '<h2>How numbers are checked</h2><ul><li><strong>Calculators:</strong> every calculator is tested against independent calculations, and the worked example on each page uses the stated inputs so you can reproduce it.</li><li><strong>Figures that change each year</strong> (contribution limits, tax brackets, loan rates, deduction caps) are taken from official publications such as the IRS, the Social Security Administration, the CFPB and Federal Student Aid, and the tax year is named in the text.</li><li><strong>Assumptions</strong> such as interest rates or returns are labeled as assumptions. Examples are illustrations, not forecasts or promises.</li></ul>'
        . '<h2>Sources we prefer</h2><p>Government and regulator pages first (IRS, SSA, CFPB, FTC, FDIC, Federal Student Aid, the U.S. Department of Veterans Affairs), then research from recognized industry bodies. Sources are linked at the bottom of calculator pages and inside guides where a specific figure comes from them.</p>'
        . '<h2>Updates and corrections</h2><p>Each guide shows the date it was last updated. We review figures that change every year when the official numbers are published, and we correct errors when we find them or when readers report them. If you think something is wrong or out of date, tell us through the <a href="' . $contact . '">contact page</a> and include the page address and, if you can, a link to the correct source. We fix confirmed errors promptly and update the "last updated" date.</p>'
        . '<h2>Advertising and independence</h2><p>FinanceSpots is supported by advertising served by Google. Advertisers do not review or influence our content, our calculator results or our conclusions, and we do not publish paid reviews or sponsored recommendations. If that ever changes, sponsored content will be clearly labeled. See the <a href="' . $privacy . '">privacy policy</a> for how advertising cookies work and how to manage them.</p>'
        . '<h2>What the site cannot do</h2><p>Calculators give estimates based on the numbers you enter and simplified rules. Real loan terms, tax outcomes and investment results depend on details we cannot see. For decisions that matter, speak with a licensed professional.</p>'
        . '<p><em>Questions about this policy? Use the <a href="' . $contact . '">contact page</a>.</em></p>';
}

add_action( 'init', function () {
    $version = '2026-10-17-a';
    if ( get_option( 'fs_trust_pages_v' ) === $version ) return;
    update_option( 'fs_trust_pages_v', $version );
    $args = [
        'post_type' => 'page', 'post_title' => 'Editorial Policy', 'post_name' => 'editorial-policy',
        'post_content' => fs_editorial_policy_html(), 'post_status' => 'publish', 'post_author' => 1,
    ];
    $ex = get_page_by_path( 'editorial-policy' );
    if ( $ex ) { $args['ID'] = $ex->ID; $id = wp_update_post( wp_slash( $args ) ); } else { $id = wp_insert_post( wp_slash( $args ) ); }
    if ( $id && ! is_wp_error( $id ) ) {
        update_post_meta( $id, 'rank_math_title', 'Editorial Policy: How FinanceSpots Writes and Checks Content' );
        update_post_meta( $id, 'rank_math_description', 'How FinanceSpots writes, sources, checks and corrects its calculators and guides, how AI is used, and how advertising fits in.' );
        update_post_meta( $id, 'rank_math_robots', [ 'index', 'follow' ] );
    }
}, 45 );

/* /ads.txt: Google requires this line once AdSense is connected. Served only when a publisher ID is saved under FS PRO > AdSense. */
add_action( 'init', function () {
    $path = isset( $_SERVER['REQUEST_URI'] ) ? parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
    if ( '/ads.txt' !== $path ) return;
    $id = trim( (string) get_option( 'fs_adsense_publisher_id', '' ) );
    $pub = preg_replace( '/^ca-/', '', $id );
    if ( ! preg_match( '/^pub-\d{8,20}$/', $pub ) ) return;   // no valid ID yet: let WordPress answer 404
    status_header( 200 );
    header( 'Content-Type: text/plain; charset=utf-8' );
    header( 'Cache-Control: public, max-age=3600' );
    echo 'google.com, ' . $pub . ', DIRECT, f08c47fec0942fa0' . "\n";
    exit;
}, 1 );
