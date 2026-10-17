<?php
/**
 * Short, original introductions and calculator links for the blog category pages, which otherwise
 * show only a list of posts. Shown only when the category has no description of its own.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_category_intros() {
    return [
        'loans' => [
            'Borrowing is the biggest financial decision most people make, and small differences in the rate or the term change what you pay by thousands of dollars. The guides here explain how mortgages, auto loans, student loans and personal loans work, what drives your monthly payment, and how to compare offers before you sign. Where a guide discusses numbers, it links to a free calculator so you can test your own balance, rate and term.',
            [ 'mortgage-calculator' => 'Mortgage calculator', 'auto-loan-calculator' => 'Auto loan calculator', 'student-loan-calculator' => 'Student loan calculator', 'personal-loan-calculator' => 'Personal loan calculator', 'refinance-calculator' => 'Refinance calculator' ],
        ],
        'debt-management' => [
            'Getting out of debt is mostly a question of order and rate: which balance you attack first, whether a lower rate is available, and how much you can add above the minimum. These guides compare payoff strategies, explain how consolidation and balance transfers work, and show what each choice can do to your credit. Use the calculators to see how long payoff takes and how much interest each plan costs.',
            [ 'loan-payoff-calculator' => 'Loan payoff calculator', 'debt-consolidation-calculator' => 'Debt consolidation calculator', 'balance-transfer-calculator' => 'Balance transfer calculator', 'debt-to-income-ratio-calculator' => 'Debt-to-income ratio calculator' ],
        ],
        'budgeting' => [
            'A budget is a plan for your money, not a punishment. The guides in this category explain simple frameworks such as the 50/30/20 rule, how to find money you did not know you had, and how to turn a plan into automatic habits. Try your own income in the budget calculators and adjust the percentages until the plan fits your life.',
            [ 'monthly-budget-planner' => 'Monthly budget planner', '50-30-20-budget-calculator' => '50/30/20 budget calculator', 'savings-goal-calculator' => 'Savings goal calculator' ],
        ],
        'savings' => [
            'Saving works best when it is automatic and has a clear goal. These guides cover how big an emergency fund should be, where to keep cash so it earns interest and stays safe, and how to reach a specific target by a specific date. The calculators turn a goal and a deadline into the monthly amount you need.',
            [ 'savings-goal-calculator' => 'Savings goal calculator', 'emergency-fund-calculator' => 'Emergency fund calculator', '52-week-savings-challenge' => '52-week savings challenge', 'compound-interest-calculator' => 'Compound interest calculator' ],
        ],
        'investing' => [
            'Long-term investing rewards patience, low costs and diversification more than prediction. The guides here explain how index funds work, why fees matter, how returns compound, and how to think about risk. Nothing here is personal advice, but the calculators let you see how contributions, time and rates of return interact.',
            [ 'compound-interest-calculator' => 'Compound interest calculator', 'cagr-calculator' => 'CAGR calculator', 'dollar-cost-averaging' => 'Dollar-cost averaging calculator', 'portfolio-analyzer' => 'Portfolio analyzer', 'roi-calculator' => 'ROI calculator' ],
        ],
        'retirement' => [
            'Retirement planning comes down to three numbers: how much you will spend, how much income comes from Social Security and other sources, and how long your savings need to last. The guides in this category explain withdrawal rules, retirement accounts such as the 401(k) and IRA, and what to do if you are starting late. Contribution limits change every year, so each guide names the tax year it covers.',
            [ 'retirement-savings-calculator' => 'Retirement savings calculator', '401k-calculator' => '401(k) calculator', 'ira-calculator' => 'IRA calculator', 'fire-calculator' => 'FIRE calculator' ],
        ],
        'cryptocurrency' => [
            'Cryptocurrency prices can move sharply in a day, and many people lose money by buying without a plan. The guides here explain what these assets are, how they compare with traditional stores of value such as gold, and what taxes and fees apply. They are educational, not recommendations. The calculators help you work out profit and loss after fees, staking yield and conversions.',
            [ 'crypto-converter' => 'Crypto converter', 'crypto-pl-calculator' => 'Crypto profit calculator', 'staking-rewards-calculator' => 'Staking rewards calculator', 'mining-profitability-calculator' => 'Mining profitability calculator' ],
        ],
        'finance-tips' => [
            'These articles cover everyday money questions: how to save a large goal in a short time, how to improve a credit score, how to compare financing offers and what to do before a big purchase. Each guide shows its numbers and links to the calculator behind them, so you can check the math with your own figures instead of taking a rule of thumb on faith.',
            [ 'monthly-budget-planner' => 'Monthly budget planner', 'debt-consolidation-calculator' => 'Debt consolidation calculator', 'loan-comparison-calculator' => 'Loan comparison calculator', 'net-worth-calculator' => 'Net worth calculator' ],
        ],
    ];
}

add_action( 'fs_archive_heading', function () {
    if ( ! is_category() || is_paged() ) return;
    $term = get_queried_object();
    if ( ! $term || trim( wp_strip_all_tags( term_description() ) ) !== '' ) return;
    $all = fs_category_intros();
    if ( empty( $all[ $term->slug ] ) ) return;
    list( $text, $tools ) = $all[ $term->slug ];
    echo '<div class="fs-archive-intro" style="max-width:820px;margin:0 0 28px"><p style="font-size:1.05rem;line-height:1.7">' . esc_html( $text ) . '</p><p style="margin:12px 0 0"><strong>' . esc_html__( 'Free calculators:', 'financespots' ) . '</strong> ';
    $links = [];
    foreach ( $tools as $slug => $label ) {
        $links[] = '<a href="' . esc_url( home_url( '/tool/' . $slug . '/' ) ) . '">' . esc_html( $label ) . '</a>';
    }
    echo implode( ' &middot; ', $links ) . '</p></div>';
}, 20 );
