<?php
/**
 * Plugin Name: FinanceSpots Old Guide Redirects
 * Description: 301 redirects for deleted v1 guide pages
 */
add_action( 'init', function () {
    if ( ! isset( $_SERVER['REQUEST_URI'] ) ) return;
    $map = [
        'mortgage-calculator-guide'   => '/first-time-home-buyer-guide-2026/',
        'compound-interest-guide'     => '/index-fund-investing-beginners-guide-2026/',
        'investment-calculator-guide' => '/index-fund-investing-beginners-guide-2026/',
        'emergency-fund-guide'        => '/emergency-fund-how-much-where-to-keep-2026/',
        'tax-calculator-guide-2026'   => '/tool/income-tax-calculator/',
        'crypto-profit-guide'         => '/bitcoin-vs-gold-better-investment-2026/',
        'personal-loan-guide'         => '/how-to-pay-off-student-loans-faster-2026/',
        'budget-planner-guide'        => '/50-30-20-budget-rule-guide-2026/',
        'retirement-planning-guide'   => '/retirement-planning-how-much-need-2026/',
        'pay-off-debt-fast-guide'     => '/how-to-pay-off-student-loans-faster-2026/',
    ];
    $slug = trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
    if ( isset( $map[ $slug ] ) ) {
        header( 'Location: ' . rtrim( home_url(), '/' ) . $map[ $slug ], true, 301 );
        exit;
    }
}, 1 );
