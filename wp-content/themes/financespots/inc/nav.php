<?php
/**
 * Primary navigation: Calculators mega menu + top-level links, desktop and mobile.
 *
 * Used by header.php when no WordPress menu is assigned to the "primary" location (the live site
 * relied on the fallback menu). Group and link lists are data, so adding a calculator is one line.
 * Every URL below is a tool that is live and indexable (not one of the redirected duplicates).
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

/** Calculator groups: title, category archive, links [label, tool slug]. */
function fs_nav_groups() {
    return [
        [ 'title' => 'Loans & Mortgages', 'cat' => 'loan-calculators', 'links' => [
            [ 'Mortgage Calculator', 'mortgage-calculator' ], [ 'Refinance Calculator', 'refinance-calculator' ],
            [ 'FHA Loan Calculator', 'fha-loan-calculator' ], [ 'VA Loan Funding Fee', 'va-loan-funding-fee-calculator' ],
            [ 'Auto Loan Calculator', 'auto-loan-calculator' ], [ 'Personal Loan Calculator', 'personal-loan-calculator' ],
            [ 'Student Loan Calculator', 'student-loan-calculator' ], [ 'Home Equity & HELOC', 'home-equity-loan-calculator' ],
            [ 'Loan Payoff Calculator', 'loan-payoff-calculator' ], [ 'Amortization Calculator', 'amortization-calculator' ],
        ] ],
        [ 'title' => 'Taxes & Income', 'cat' => 'tax-calculators', 'links' => [
            [ 'Income Tax Calculator', 'income-tax-calculator' ], [ 'Self-Employment Tax', 'self-employment-tax-calculator' ],
            [ 'Capital Gains Tax', 'capital-gains-tax-calculator' ], [ 'Property Tax Calculator', 'property-tax-calculator' ],
            [ 'Loan Affordability', 'loan-affordability-calculator' ],
        ] ],
        [ 'title' => 'Retirement & Savings', 'cat' => 'retirement-planning', 'links' => [
            [ 'Retirement Savings', 'retirement-savings-calculator' ], [ '401(k) Calculator', '401k-calculator' ],
            [ 'IRA Calculator', 'ira-calculator' ], [ 'FIRE Calculator', 'fire-calculator' ],
            [ 'Savings Goal Calculator', 'savings-goal-calculator' ], [ 'Emergency Fund Calculator', 'emergency-fund-calculator' ],
        ] ],
        [ 'title' => 'Investing', 'cat' => 'investment-tools', 'links' => [
            [ 'Compound Interest', 'compound-interest-calculator' ], [ 'ROI Calculator', 'roi-calculator' ],
            [ 'Dividend Calculator', 'dividend-calculator' ], [ 'CAGR Calculator', 'cagr-calculator' ],
            [ 'Dollar-Cost Averaging', 'dollar-cost-averaging' ], [ 'Portfolio Analyzer', 'portfolio-analyzer' ],
        ] ],
        [ 'title' => 'Budgeting & Debt', 'cat' => 'budget-analyzers', 'links' => [
            [ 'Monthly Budget Planner', 'monthly-budget-planner' ], [ '50/30/20 Budget', '50-30-20-budget-calculator' ],
            [ 'Net Worth Calculator', 'net-worth-calculator' ], [ 'Debt Consolidation', 'debt-consolidation-calculator' ],
            [ 'Debt-to-Income Ratio', 'debt-to-income-ratio-calculator' ], [ 'Loan Comparison', 'loan-comparison-calculator' ],
        ] ],
        [ 'title' => 'Crypto & Currency', 'cat' => 'crypto-tools', 'links' => [
            [ 'Currency Converter', 'live-currency-converter' ], [ 'Crypto Converter', 'crypto-converter' ],
            [ 'Crypto Profit & Loss', 'crypto-pl-calculator' ], [ 'Staking Rewards', 'staking-rewards-calculator' ],
            [ 'NFT ROI Calculator', 'nft-roi-calculator' ], [ 'Mining Profitability', 'mining-profitability-calculator' ],
        ] ],
    ];
}

/** A few evergreen guides shown in the menu. */
function fs_nav_guides() {
    return [
        [ 'First-time home buyer guide', '/first-time-home-buyer-guide-2026/' ],
        [ 'How much do I need to retire?', '/retirement-planning-how-much-need-2026/' ],
        [ 'Pay off student loans faster', '/how-to-pay-off-student-loans-faster-2026/' ],
        [ 'Emergency fund: how much to keep', '/emergency-fund-how-much-where-to-keep-2026/' ],
    ];
}

/** Plain top-level links (after the Calculators menu). */
function fs_nav_links() {
    return [
        [ 'Guides',  home_url( '/blog/' ) ],
        [ 'About',   home_url( '/about/' ) ],
        [ 'Contact', home_url( '/contact/' ) ],
        [ 'Pricing', home_url( '/pricing/' ) ],
    ];
}

function fs_nav_is_current( $url ) {
    $req = trailingslashit( wp_parse_url( home_url( add_query_arg( [] ) ), PHP_URL_PATH ) );
    $to  = trailingslashit( (string) wp_parse_url( $url, PHP_URL_PATH ) );
    return $req === $to;
}

/** Desktop: top bar items + mega panel. */
function fs_render_desktop_nav() {
    $groups = fs_nav_groups();
    $in_tools = is_singular( 'fs_tool' ) || is_post_type_archive( 'fs_tool' ) || is_tax( 'fs_tool_cat' ) || fs_nav_is_current( home_url( '/all-tools/' ) );
    ?>
    <ul class="fs-nav-list" id="primary-menu">
        <li class="fs-nav-item fs-nav-item--mega<?php echo $in_tools ? ' is-current' : ''; ?>">
            <button type="button" class="fs-nav-link fs-nav-trigger" id="fs-mega-btn" aria-expanded="false" aria-controls="fs-mega" aria-haspopup="true">
                Calculators
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div class="fs-mega" id="fs-mega" role="region" aria-label="All calculators">
                <div class="fs-mega__panel">
                    <div class="fs-mega__grid">
                        <?php
                        /* columns of group indexes; the last column also carries the guides box */
                        $columns = [ [ 0 ], [ 1, 2 ], [ 3, 4 ], [ 5 ] ];
                        foreach ( $columns as $ci => $idxs ) : ?>
                        <div class="fs-mega__col">
                            <?php foreach ( $idxs as $gi ) : $g = $groups[ $gi ]; ?>
                            <div class="fs-mega__group">
                                <a class="fs-mega__title" href="<?php echo esc_url( home_url( '/tools/' . $g['cat'] . '/' ) ); ?>"><?php echo esc_html( $g['title'] ); ?></a>
                                <ul class="fs-mega__list">
                                    <?php foreach ( $g['links'] as $l ) : ?>
                                    <li><a href="<?php echo esc_url( home_url( '/tool/' . $l[1] . '/' ) ); ?>"><?php echo esc_html( $l[0] ); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endforeach; ?>
                            <?php if ( $ci === count( $columns ) - 1 ) : ?>
                            <div class="fs-mega__guides">
                                <a class="fs-mega__title" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Popular guides</a>
                                <ul class="fs-mega__list">
                                    <?php foreach ( fs_nav_guides() as $gd ) : ?>
                                    <li><a href="<?php echo esc_url( home_url( $gd[1] ) ); ?>"><?php echo esc_html( $gd[0] ); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="fs-mega__foot">
                        <span>Free, no sign-up, and every result can be saved as a PDF.</span>
                        <a class="fs-mega__all" href="<?php echo esc_url( home_url( '/all-tools/' ) ); ?>">Browse all calculators &rarr;</a>
                    </div>
                </div>
            </div>
        </li>
        <?php foreach ( fs_nav_links() as $l ) : ?>
        <li class="fs-nav-item<?php echo fs_nav_is_current( $l[1] ) ? ' is-current' : ''; ?>">
            <a class="fs-nav-link" href="<?php echo esc_url( $l[1] ); ?>"<?php echo fs_nav_is_current( $l[1] ) ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $l[0] ); ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php
}

/** Mobile drawer: accordion of calculator groups + links. */
function fs_render_mobile_nav() {
    $groups = fs_nav_groups(); ?>
    <ul class="fs-mnav" id="fs-mnav">
        <li class="fs-mnav__item">
            <button type="button" class="fs-mnav__toggle" aria-expanded="false" aria-controls="fs-mnav-calc">Calculators
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></button>
            <div class="fs-mnav__panel" id="fs-mnav-calc" hidden>
                <?php foreach ( $groups as $i => $g ) : ?>
                <div class="fs-mnav__group">
                    <button type="button" class="fs-mnav__subtoggle" aria-expanded="false" aria-controls="fs-mnav-g<?php echo (int) $i; ?>"><?php echo esc_html( $g['title'] ); ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></button>
                    <ul class="fs-mnav__sub" id="fs-mnav-g<?php echo (int) $i; ?>" hidden>
                        <?php foreach ( $g['links'] as $l ) : ?>
                        <li><a href="<?php echo esc_url( home_url( '/tool/' . $l[1] . '/' ) ); ?>"><?php echo esc_html( $l[0] ); ?></a></li>
                        <?php endforeach; ?>
                        <li><a class="fs-mnav__more" href="<?php echo esc_url( home_url( '/tools/' . $g['cat'] . '/' ) ); ?>">All <?php echo esc_html( strtolower( $g['title'] ) ); ?> &rarr;</a></li>
                    </ul>
                </div>
                <?php endforeach; ?>
                <a class="fs-mnav__all" href="<?php echo esc_url( home_url( '/all-tools/' ) ); ?>">Browse all calculators &rarr;</a>
            </div>
        </li>
        <?php foreach ( fs_nav_links() as $l ) : ?>
        <li class="fs-mnav__item"><a class="fs-mnav__link" href="<?php echo esc_url( $l[1] ); ?>"><?php echo esc_html( $l[0] ); ?></a></li>
        <?php endforeach; ?>
    </ul>
    <?php
}

add_action( 'wp_enqueue_scripts', function () {
    $dir = get_template_directory();
    $uri = get_template_directory_uri();
    wp_enqueue_style( 'fs-nav', $uri . '/assets/css/nav.css', [ 'financespots-style' ], filemtime( $dir . '/assets/css/nav.css' ) );
    wp_enqueue_script( 'fs-nav', $uri . '/assets/js/nav.js', [], filemtime( $dir . '/assets/js/nav.js' ), true );
}, 30 );

/** Header call-to-action: send visitors to the calculators list (the old default was an on-page anchor that only exists on the homepage). */
function fs_nav_cta_url() {
    $u = get_theme_mod( 'fs_cta_nav_url', '' );
    return ( '' === $u || '#tools' === $u ) ? home_url( '/all-tools/' ) : $u;
}
function fs_nav_cta_label() {
    $l = get_theme_mod( 'fs_cta_nav_label', '' );
    return ( '' === $l || 'Get Started Free' === $l ) ? __( 'All Calculators', 'financespots' ) : $l;
}
