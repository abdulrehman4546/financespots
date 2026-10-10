<?php
/**
 * Creates the batch-4 tool pages and guide posts once per version.
 * Links written as {tool:slug} are resolved to real permalinks when the post is created.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_new_content_tools() {
    return [
        'balance-transfer-calculator' => [ 'Balance Transfer Calculator', 'balance_transfer', 'loan-calculators' ],
        'student-loan-rap-calculator' => [ 'RAP Student Loan Calculator', 'student_rap', 'loan-calculators' ],
    ];
}

function fs_new_content_faq( array $faqs ) {
    $o = '<h2>Frequently asked questions</h2>';
    foreach ( $faqs as $f ) {
        $o .= '<h3>' . $f[0] . '</h3><p>' . $f[1] . '</p>';
    }
    return $o;
}

function fs_new_content_guides() {
    return [
    [
        'slug'    => 'does-debt-consolidation-hurt-your-credit',
        'title'   => 'Does Debt Consolidation Hurt Your Credit? What Really Happens',
        'keyword' => 'does debt consolidation hurt your credit',
        'desc'    => 'Debt consolidation can cause a small short-term dip and often helps your score later. See what changes, and test your own numbers with our calculator.',
        'excerpt' => 'A clear look at what debt consolidation does to your credit score in the short and long term.',
        'content' => '<p class="lead">Debt consolidation can lower your credit score slightly at first and often improves it over the following months. What matters is how you do it and what you do afterward. You can test your own numbers with our <a href="{tool:debt-consolidation-calculator}">debt consolidation calculator</a>.</p>'
            . '<h2>What can lower your score at first</h2><ul><li><strong>A hard inquiry.</strong> Applying for a loan or card usually causes a small, temporary dip.</li><li><strong>A new account.</strong> It lowers the average age of your accounts.</li><li><strong>Closing old cards.</strong> This can raise your credit utilization and shorten your history, so consider keeping them open with no balance.</li></ul>'
            . '<h2>What can raise your score over time</h2><ul><li><strong>Lower utilization.</strong> Paying card balances with an installment loan reduces the share of your revolving limits in use.</li><li><strong>On-time payments.</strong> Payment history is the biggest scoring factor, and one fixed payment is easier to keep on time.</li><li><strong>A better mix.</strong> An installment loan alongside cards can help.</li></ul>'
            . '<h2>Ways to consolidate and the credit impact</h2><table><thead><tr><th>Method</th><th>Typical cost</th><th>Credit effect</th><th>Best for</th></tr></thead><tbody><tr><td>Personal loan</td><td>Fixed rate, sometimes an origination fee</td><td>Hard inquiry, then lower utilization</td><td>Larger balances, a fixed payoff date</td></tr><tr><td>Balance transfer card</td><td>Transfer fee of 3% to 5%, 0% promo</td><td>Hard inquiry, new card</td><td>Balances you can clear within the promo (<a href="{tool:balance-transfer-calculator}">check with the calculator</a>)</td></tr><tr><td>HELOC or home equity loan</td><td>Lower rate, your home is collateral</td><td>Hard inquiry</td><td>Homeowners with equity who accept the risk</td></tr><tr><td>Debt management plan</td><td>Monthly fee through a nonprofit agency</td><td>Cards are usually closed</td><td>Borrowers who need structure</td></tr></tbody></table>'
            . '<h2>How to protect your score</h2><ol><li>Compare offers with soft-pull pre-qualification where available.</li><li>Keep paid-off cards open and unused.</li><li>Do not run the balances back up.</li><li>Make every payment on time; set up autopay.</li></ol>'
            . '<p>Check whether consolidating actually saves money with the <a href="{tool:debt-consolidation-calculator}">debt consolidation calculator</a>, and see what share of your income goes to debt with the <a href="{tool:debt-to-income-ratio-calculator}">debt-to-income ratio calculator</a>.</p>'
            . '{faq}',
        'faqs'    => [
            [ 'Does debt consolidation hurt your credit score?', 'It can cause a small temporary dip from the hard inquiry and the new account, but lower utilization and on-time payments often lift the score over the following months.' ],
            [ 'How long does the credit dip last?', 'The effect of a hard inquiry fades over a few months and stops counting in most scores after 12 months; it stays on the report for two years.' ],
            [ 'Should I close my credit cards after consolidating?', 'Usually not. Closing them can raise utilization and shorten your credit history. Keep them open with a zero balance if you can avoid new spending.' ],
            [ 'Is a balance transfer or a personal loan better?', 'A balance transfer suits balances you can clear within the 0% period. A personal loan suits larger balances or longer payoff times because the rate and payment stay fixed.' ],
        ],
    ],
    [
        'slug'    => 'balance-transfer-vs-personal-loan',
        'title'   => 'Balance Transfer vs Personal Loan: Which Saves More Money?',
        'keyword' => 'balance transfer vs personal loan',
        'desc'    => 'Balance transfer card or personal loan for credit card debt? Compare costs, timelines and risks, then run your own numbers.',
        'excerpt' => 'How to choose between a 0% balance transfer card and a fixed-rate personal loan for paying off debt.',
        'content' => '<p class="lead">A balance transfer card usually wins when you can pay off the debt inside the promotional period. A personal loan usually wins for bigger balances or longer payoff times. Run both with the <a href="{tool:balance-transfer-calculator}">balance transfer calculator</a> and the <a href="{tool:debt-consolidation-calculator}">debt consolidation calculator</a>.</p>'
            . '<h2>Side by side</h2><table><thead><tr><th></th><th>Balance transfer card</th><th>Personal loan</th></tr></thead><tbody><tr><td>Interest</td><td>0% promo (often 12 to 21 months), then a high rate</td><td>Fixed rate for the whole term</td></tr><tr><td>Upfront cost</td><td>Transfer fee, commonly 3% to 5%</td><td>Origination fee on some loans</td></tr><tr><td>Payoff time</td><td>Whatever you choose, but the promo ends</td><td>Set term, often 2 to 7 years</td></tr><tr><td>Risk</td><td>Rate jumps if balance remains or you pay late</td><td>Fewer surprises; a fixed payment</td></tr><tr><td>Credit needed</td><td>Good to excellent</td><td>Fair to excellent</td></tr></tbody></table>'
            . '<h2>A worked example</h2><p>On an $8,000 balance at 24% APR with $400 a month, a 0% card for 18 months with a 3% fee ($240) saves about $2,043 versus staying put and clears the debt in 1 year 9 months. About $1,040 would remain when the promo ends unless you pay roughly $458 a month.</p>'
            . '<h2>How to decide</h2><ol><li>Divide the balance by the promo months. If that payment fits your budget, a transfer is hard to beat.</li><li>If it does not, compare a personal loan’s fixed payment.</li><li>Add the fee to the cost of each option before comparing.</li></ol>'
            . '{faq}',
        'faqs'    => [
            [ 'Is a balance transfer cheaper than a personal loan?', 'If you clear the balance within the 0% period, yes, usually. If you cannot, the post-promo rate can make a fixed-rate personal loan cheaper.' ],
            [ 'What is a typical balance transfer fee?', 'Most cards charge 3% to 5% of the amount moved.' ],
            [ 'What if I cannot pay it off before the promo ends?', 'The remaining balance starts accruing interest at the card’s regular rate, so compare that scenario before you decide.' ],
        ],
    ],
    [
        'slug'    => 'rap-vs-tiered-standard-student-loan-plan',
        'title'   => 'RAP vs Tiered Standard: Choosing a Student Loan Plan in 2026',
        'keyword' => 'rap vs standard repayment plan',
        'desc'    => 'Compare the Repayment Assistance Plan with the Tiered Standard plan for federal loans first disbursed on or after July 1, 2026, with a worked example.',
        'excerpt' => 'How the new Repayment Assistance Plan compares with the Tiered Standard plan, with an example.',
        'content' => '<p class="lead">For federal student loans first disbursed on or after July 1, 2026, borrowers choose between the income-based Repayment Assistance Plan (RAP) and a fixed-payment Tiered Standard plan. Estimate yours with our <a href="{tool:student-loan-rap-calculator}">RAP student loan calculator</a>.</p>'
            . '<h2>How each plan works</h2><ul><li><strong>RAP:</strong> a payment between 1% and 10% of adjusted gross income, reduced by $50 per dependent, with a $10 minimum.</li><li><strong>Tiered Standard:</strong> a fixed payment over 10, 15, 20 or 25 years depending on the amount owed.</li></ul>'
            . '<h2>Example</h2><p>With $52,000 of AGI, no dependents and $38,000 owed at 6.52%, the estimated RAP payment is about $216.67 a month, against $331.44 on the Tiered Standard plan over 15 years.</p>'
            . '<h2>Choosing</h2><ol><li>If your income is tight or uncertain, RAP’s lower payment may matter most.</li><li>If you can afford the standard payment, you get a fixed payoff date.</li><li>Confirm your figures in the Loan Simulator on studentaid.gov; the rules are set by the Department of Education and can change.</li></ol>'
            . '<p>See also the <a href="{tool:loan-payoff-calculator}">loan payoff calculator</a> to see how extra payments shorten a loan.</p>'
            . '{faq}',
        'faqs'    => [
            [ 'What is RAP?', 'The Repayment Assistance Plan is an income-based plan for federal student loans first disbursed on or after July 1, 2026.' ],
            [ 'How is the Tiered Standard term decided?', 'By the balance: roughly 10 years under $25,000, then 15, 20 and 25 years as the amount owed rises.' ],
            [ 'Where do I verify the numbers?', 'Use the Loan Simulator at studentaid.gov or ask your servicer.' ],
        ],
    ],
    ];
}

function fs_new_content_url( $slug ) {
    $p = get_page_by_path( $slug, OBJECT, 'fs_tool' );
    return $p ? get_permalink( $p ) : home_url( '/tools/' . $slug . '/' );
}

add_action( 'init', function () {
    $version = '2026-10-10-a';
    if ( get_option( 'fs_new_content_version' ) === $version ) return;
    update_option( 'fs_new_content_version', $version );

    /* tools */
    foreach ( fs_new_content_tools() as $slug => $t ) {
        if ( get_page_by_path( $slug, OBJECT, 'fs_tool' ) ) continue;
        $id = wp_insert_post( [
            'post_type' => 'fs_tool', 'post_title' => $t[0], 'post_name' => $slug,
            'post_content' => '', 'post_status' => 'publish',
        ] );
        if ( ! $id || is_wp_error( $id ) ) continue;
        update_post_meta( $id, '_fs_tool_type', $t[1] );
        $term = get_term_by( 'slug', $t[2], 'fs_tool_cat' );
        if ( $term ) wp_set_post_terms( $id, [ $term->term_id ], 'fs_tool_cat' );
    }

    /* guides */
    $cat = get_term_by( 'name', 'Finance Tips', 'category' );
    $cat_id = $cat ? $cat->term_id : (int) get_option( 'default_category' );
    foreach ( fs_new_content_guides() as $g ) {
        $html = str_replace( '{faq}', fs_new_content_faq( $g['faqs'] ), $g['content'] );
        $html = preg_replace_callback( '/\{tool:([a-z0-9-]+)\}/', function ( $m ) { return esc_url( fs_new_content_url( $m[1] ) ); }, $html );
        $ex = get_page_by_path( $g['slug'], OBJECT, 'post' );
        $args = [ 'post_title' => $g['title'], 'post_name' => $g['slug'], 'post_content' => $html, 'post_excerpt' => $g['excerpt'],
                  'post_status' => 'publish', 'post_type' => 'post', 'post_author' => 1, 'post_category' => [ $cat_id ] ];
        if ( $ex ) { $args['ID'] = $ex->ID; $id = wp_update_post( $args ); } else { $id = wp_insert_post( $args ); }
        if ( ! $id || is_wp_error( $id ) ) continue;
        update_post_meta( $id, 'rank_math_title', $g['title'] . ' | FinanceSpots' );
        update_post_meta( $id, 'rank_math_description', $g['desc'] );
        update_post_meta( $id, 'rank_math_focus_keyword', $g['keyword'] );
        update_post_meta( $id, 'rank_math_robots', [ 'index', 'follow' ] );
    }
    delete_option( 'fs_tool_content_seo_hash' );
}, 30 );
