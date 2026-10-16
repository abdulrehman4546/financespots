<?php
/**
 * One-time factual corrections to older blog posts (figures that were out of date or wrong).
 * Each rule is [ text to find, replacement ]. Dashes and spaces in the search text match their HTML
 * entity forms too. Run once per $version; the log is kept in the option fs_content_corrections_log.
 *
 * Sources checked in October 2026: IRS Notice 2025-67 (2026 retirement limits), SSA 2026 COLA fact
 * sheet, Federal Student Aid (2026-27 Direct Loan rates), IRS/Kipliger on the 2026 SALT cap, SPIVA 2024.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_cc_pattern( $find ) {
    $p = preg_quote( $find, '~' );
    $p = str_replace( '–', '(?:–|&ndash;|&#8211;)', $p );
    $p = str_replace( '—', '(?:—|&mdash;|&#8212;)', $p );
    $p = preg_replace( '/(?:\\\\ |\s)+/', '\s+', $p );
    return '~' . $p . '~u';
}

function fs_cc_rules() {
    return [
        'retirement-planning-how-much-need-2026' => [
            [ '$23,500 ($31,000 if 50+)', '$24,500 ($32,500 if 50+; $35,750 at ages 60–63)' ],
            [ '$7,000 ($8,000 if 50+)', '$7,500 ($8,600 if 50+)' ],
            [ 'Up to $7,000 a year ($8,000 if 50+)', 'Up to $7,500 a year ($8,600 if 50+)' ],
            [ 'Max catch-up contributions ($31,000/year)', 'Max catch-up contributions ($32,500/year)' ],
            [ '401(k): $31,000/year (vs $23,500). IRA: $8,000/year (vs $7,000).', '401(k): $32,500/year (vs $24,500; $35,750 at ages 60 to 63). IRA: $8,600/year (vs $7,500).' ],
            [ '$1,907/month', '$2,071/month' ],
            [ '$37,000–$40,000/year, requiring $925,000–$1,000,000 rather than $1.5 million', 'about $35,000/year, requiring roughly $880,000 rather than $1.5 million' ],
            [ '$1,900+/month', '$2,070+/month' ],
            [ '~$1,900/mo', '~$2,070/mo' ],
            [ '<strong>$1,900</strong>, or about $22,800 a year', '<strong>$2,070</strong>, or about $24,850 a year' ],
        ],
        'how-to-pay-off-student-loans-faster-2026' => [
            [ '5.50%–8.05% fixed', '6.52%–9.07% fixed' ],
            [ 'Yes — SAVE, IBR, PAYE', 'Yes — IBR and RAP (SAVE has ended)' ],
            [ 'According to the Education Data Initiative, the average federal student loan interest rate for undergraduates is 5.50% for 2025–2026 (up from 3.73% in 2020–2021). Graduate PLUS loan rates are 8.05%', 'According to the U.S. Department of Education, the interest rate on new federal undergraduate loans is 6.52% for 2026–27 (6.39% in 2025–26, up from 3.73% in 2020–21). Graduate unsubsidized loans are 8.07% and PLUS loans are 9.07%' ],
            [ 'even $100/month extra cuts a 10-year loan by nearly 3 years', 'even $100/month extra cuts a 10-year loan by more than 2 years' ],
            [ 'cuts payoff time by nearly 3 years and saves $4,500 in interest', 'cuts payoff time by more than 3 years and saves about $4,700 in interest' ],
            [ 'adding $200/month cuts your payoff time by about 3.5 years and saves $4,800 in interest. On a $60,000 loan, the same extra payment saves over $8,000 and removes 4+ years.', 'adding $200/month cuts your payoff time by about 3.8 years and saves about $5,700 in interest. On a $60,000 loan at 6.5%, an extra $200 a month saves about $6,700 and removes about 2.8 years.' ],
            [ 'You save roughly $8,000 in interest and finish 5 years earlier.', 'You save roughly $7,200 in interest and finish 5 years earlier.' ],
            [ 'Refinancing can save $7,000+', 'Refinancing can save $6,000+' ],
            [ '$7,000+ on $40k loan', '$6,000+ on $40k loan' ],
            [ 'saves $7,000+ in total interest', 'saves about $6,000 in total interest over 10 years' ],
            [ 'Does the SAVE repayment plan help pay off student loans faster?', 'Do income-driven repayment plans help pay off student loans faster?' ],
            [ 'SAVE (Saving on a Valuable Education) is an income-driven repayment plan where unpaid interest does not capitalize. For low-income borrowers pursuing eventual forgiveness, SAVE can be the right path. For borrowers wanting to pay off faster and save on total interest, aggressive extra principal payments beat SAVE unless you qualify for PSLF forgiveness.', 'The SAVE plan ended in 2026 after court rulings, and the new Repayment Assistance Plan (RAP) became available on July 1, 2026. Income-driven plans lower the monthly payment and can lead to forgiveness after many years, which suits borrowers who struggle to afford payments. For borrowers wanting to pay off faster and save on total interest, extra principal payments usually beat them unless you expect PSLF forgiveness.' ],
        ],
        'bitcoin-vs-gold-better-investment-2026' => [
            [ '-20% maximum modern drawdown', 'a worst modern drawdown of about -45% (2011 to 2015)' ],
            [ '<strong>~-20%</strong>', '<strong>~-45%</strong>' ],
            [ '<strong>580%</strong> (about 8.4%/year)', '<strong>about 935%</strong> (roughly 9.8%/year)' ],
            [ '>~580%</div>', '>~935%</div>' ],
            [ '<strong>580% — about 8.4% annualized</strong>', '<strong>935% — about 9.8% annualized</strong>' ],
            [ 'Gold in 2023–2024:</strong>', 'Gold from early 2023 to early 2025:</strong>' ],
        ],
        'emergency-fund-how-much-where-to-keep-2026' => [
            [ 'paying 4.0%–5.0% APY', 'paying roughly 3.8%–4.4% APY' ],
            [ 'Top HYSAs in 2026 pay 4.5%–5.0% APY. On a $15,000 emergency fund, that is $675–$750 a year', 'Top HYSAs in 2026 pay roughly 3.8%–4.4% APY (a few promotional offers go higher with conditions). On a $15,000 emergency fund, that is about $570–$660 a year' ],
            [ 'In 2026 these pay 4.5%–5.0% APY', 'In 2026 these pay roughly 3.8%–4.4% APY' ],
            [ 'A HYSA at 4.5% APY', 'A HYSA at about 4% APY' ],
            [ '>4–5%</div>', '>~4%</div>' ],
            [ '4–5%', 'about 4%' ],
        ],
        'how-to-save-10000-in-6-months-2026' => [
            [ 'pay 4.0%–5.0% APY', 'pay roughly 3.8%–4.4% APY' ],
            [ 'between 0.01% and 4.5% APY is roughly $450 a year', 'between 0.01% and about 4.2% APY is roughly $420 a year' ],
            [ '>4–5%</div>', '>~4%</div>' ],
            [ '4–5%', 'about 4%' ],
        ],
        'first-time-home-buyer-guide-2026' => [
            [ 'Deduct up to $10,000 in state and local taxes (SALT limit) if itemizing.', 'Deduct state and local taxes up to the SALT cap ($40,400 for 2026, lower for incomes above $505,000) if itemizing.' ],
        ],
        'how-to-improve-credit-score-fast-2026' => [
            [ 'which 1 in 5 Americans have', 'which about 1 in 5 consumers have, according to an FTC study' ],
            [ 'About 1 in 5 Americans has an error lowering their score', 'An FTC study found that about 1 in 5 consumers had an error on at least one credit report' ],
            [ 'average 8–12% APR difference', 'often several percentage points of APR difference' ],
            [ '(1 in 5 Americans has an error)', '(about 1 in 5 consumers has an error, per an FTC study)' ],
        ],
        'index-fund-investing-beginners-guide-2026' => [
            [ '90% of professional fund managers fail to beat', 'roughly 9 in 10 professional fund managers fail to beat' ],
            [ 'beaten roughly 90% of actively managed U.S. stock funds', 'beaten roughly 88% of actively managed U.S. large-cap stock funds' ],
            [ '>90%</div>', '>~88%</div>' ],
            [ 'Why Index Funds Beat 90% of Professional Managers', 'Why Index Funds Beat Most Professional Managers' ],
            [ '90% of actively managed U.S. stock funds fail to beat', '88% of actively managed U.S. large-cap stock funds fail to beat' ],
            [ 'Outperforms ~90% of active funds', 'Outperforms roughly 88% of active funds' ],
        ],
        'va-loan-benefits-complete-guide-veterans-2026' => [
            [ 'and interest rates 0.25%–0.50% lower than conventional loans', 'and interest rates that are often 0.25%–0.50% lower than conventional loans' ],
            [ 'VA interest rates are 0.25%–0.50% lower than conventional', 'VA interest rates are often 0.25%–0.50% lower than conventional' ],
            [ 'VA rates run 0.25%–0.50% below conventional', 'VA rates often run 0.25%–0.50% below conventional' ],
        ],
    ];
}

/** Apply a post's rules to a string; returns the new string and fills $log[ index ] = replacements made. */
function fs_cc_apply( $text, array $rules, array &$log = [] ) {
    foreach ( $rules as $i => $r ) {
        $count = 0;
        $repl  = $r[1];
        $text  = preg_replace_callback( fs_cc_pattern( $r[0] ), function () use ( $repl ) { return $repl; }, $text, -1, $count );
        $log[ $i ] = $count;
    }
    return $text;
}

add_action( 'init', function () {
    $version = '2026-10-16-a';
    if ( get_option( 'fs_content_corrections_v' ) === $version ) return;
    update_option( 'fs_content_corrections_v', $version );

    $log = [];
    foreach ( fs_cc_rules() as $slug => $rules ) {
        $post = get_page_by_path( $slug, OBJECT, 'post' );
        if ( ! $post ) { $log[ $slug ] = 'missing'; continue; }
        $n = [];
        $new = fs_cc_apply( $post->post_content, $rules, $n );
        $log[ $slug ] = $n;
        if ( $new !== $post->post_content ) {
            wp_update_post( [ 'ID' => $post->ID, 'post_content' => wp_slash( $new ) ] );
        }
    }
    update_option( 'fs_content_corrections_log', $log, false );
}, 40 );
