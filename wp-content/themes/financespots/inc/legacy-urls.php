<?php
/**
 * Old URLs that Google still remembers: redirect the ones that have a natural replacement,
 * and answer "410 Gone" for the old real-estate-technology posts that no longer exist.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_legacy_redirects() {
    return [
        '/about-us/'                              => '/about/',
        '/contact-us/'                            => '/contact/',
        '/terms-and-conditions/'                  => '/terms-of-service/',
        '/disclaimer/'                            => '/terms-of-service/',
        '/tools-page/'                            => '/tools/',
        '/tool/'                                  => '/tools/',
        '/tool/tool/'                             => '/tools/',
        '/tool/tools/'                            => '/tools/',
        '/tool/tools-page/'                       => '/tools/',
        '/budget/'                                => '/tool/monthly-budget-planner/',
        '/tool/budget/'                           => '/tool/monthly-budget-planner/',
        '/finance-tips/'                          => '/category/finance-tips/',
        '/loans/'                                 => '/category/loans/',
        '/investment-tools/'                      => '/tools/',
        '/mortgage-calculator-complete-guide/'    => '/tool/mortgage-calculator/',
        '/personal-loan-calculator-guide/'        => '/tool/personal-loan-calculator/',
        '/emergency-fund-calculator-guide/'       => '/emergency-fund-how-much-where-to-keep-2026/',
        '/retirement-planning-calculator-guide/'  => '/retirement-planning-how-much-need-2026/',
        '/tax-calculator-2026-guide/'             => '/tool/income-tax-calculator/',
        '/tool/tax-calculator-2026-guide/'        => '/tool/income-tax-calculator/',
        '/budget-planner-complete-guide/'         => '/tool/monthly-budget-planner/',
        '/compound-interest-calculator-guide/'    => '/tool/compound-interest-calculator/',
        '/crypto-pl-calculator-guide/'            => '/tool/crypto-pl-calculator/',
        '/how-to-pay-off-debt-fast/'              => '/debt-avalanche-vs-snowball/',
        '/tool/va-loan-benefits-complete-guide-veterans-2026/' => '/va-loan-benefits-complete-guide-veterans-2026/',
    ];
}

function fs_legacy_gone() {
    return [
        '/3d-modeling-software-for-real-estate/', '/5g-impact-on-real-estate/', '/benefits-of-ai-in-real-estate/',
        '/best-real-estate-apps-for-agents/',
        '/best-real-estate-websites-for-buyers-essential-platforms-for-your-property-search-in-2025/',
        '/crm-tools-for-real-estate-boost-your-sales/', '/data-analytics-in-real-estate/', '/drones-in-real-estate-2025/',
        '/top-real-estate-software-for-property-management-tools-for-2025/',
        '/what-is-proptech-company-complete-guide-to-property-technology-innovation/',
        '/how-to-automate-your-real-estate-business-guide-for-2025/', '/how-to-choose-real-estate-software-info-2025/',
        '/how-to-create-virtual-tours-for-real-estate-guide-of-2025/',
        '/how-to-use-social-media-for-real-estate-complete-strategy-guide-for-2025/',
        '/real-estate-marketing-tools-for-beginners-essential-resources-to-grow-your-business-in-2025/',
        '/smart-home-technology-in-real-estate/', '/sustainable-real-estate-technology/',
        '/category/real-estate-technology/',
    ];
}

add_action( 'init', function () {
    if ( is_admin() || 'GET' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) return;
    $path = parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH );
    if ( ! is_string( $path ) ) return;
    $path = '/' . trim( $path, '/' ) . '/';
    $map = fs_legacy_redirects();
    if ( isset( $map[ $path ] ) ) {
        wp_redirect( home_url( $map[ $path ] ), 301 );
        exit;
    }
    if ( in_array( $path, fs_legacy_gone(), true ) ) {
        status_header( 410 );
        header( 'Content-Type: text/html; charset=utf-8' );
        header( 'X-Robots-Tag: noindex' );
        header( 'Cache-Control: public, max-age=3600' );
        echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex"><title>Page removed | FinanceSpots</title></head><body style="font-family:system-ui,sans-serif;max-width:640px;margin:15vh auto;padding:0 20px"><h1>This page has been removed</h1><p>It is no longer part of FinanceSpots. Try our <a href="' . esc_url( home_url( '/tools/' ) ) . '">free calculators</a> or the <a href="' . esc_url( home_url( '/blog/' ) ) . '">finance guides</a>.</p></body></html>';
        exit;
    }
}, 2 );
