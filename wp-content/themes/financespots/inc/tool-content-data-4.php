<?php
/**
 * Editorial content + keywords, batch 4: balance transfer, student loan RAP.
 * Example numbers come from the matching calculators in calculators-money-3.php with default inputs.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_tool_content_data_4() {
    return [

    'balance-transfer-calculator' => [
        'focus'     => 'balance transfer calculator',
        'secondary' => [ 'balance transfer credit card calculator', '0% balance transfer calculator', 'is a balance transfer worth it', 'balance transfer fee calculator' ],
        'title'     => 'Balance Transfer Calculator: Is a 0% Card Worth It?',
        'meta'      => 'Free balance transfer calculator: see if a 0% APR card saves you money after the transfer fee, and what you will still owe when the promo ends.',
        'excerpt'   => 'Compare staying on your current card with a 0% balance transfer, including the transfer fee and the rate after the promo.',
        'answer'    => 'Moving an $8,000 balance from a 24% card to a 0% offer for 18 months with a 3% fee ($240) and paying $400 a month saves about $2,043 in interest and fees and clears the debt in 1 year 9 months instead of 2 years 2 months. You would still owe about $1,040 when the promo ends, so a payment near $458 a month would finish inside the promo.',
        'steps'     => [
            'Enter the balance you want to move and your current card APR.',
            'Enter the monthly amount you can really pay.',
            'Enter the transfer fee, promo length, promo APR and the APR after the promo.',
            'Read the savings, the balance left when the promo ends, and the payment needed to clear it in time.',
        ],
        'formula'   => '<p><strong>Transfer cost = balance × fee %</strong>, added to the new balance. Each month, <strong>interest = balance × (APR ÷ 12)</strong>, then your payment is applied. The calculator runs both paths month by month and compares <strong>total interest (+ fee)</strong>.</p><p>Payment to clear inside the promo (0% promo): <strong>(balance + fee) ÷ promo months</strong>.</p>',
        'example'   => '<p>$8,000 balance, 24% current APR, $400 a month, 3% fee, 18 months at 0%, then 22%:</p><ul><li>Fee: <strong>$240</strong></li><li>Interest staying on the old card: <strong>$2,319</strong> over 2 years 2 months</li><li>Interest with the transfer: <strong>$36</strong> plus the fee, paid off in 1 year 9 months</li><li>Net saving: <strong>$2,043</strong></li><li>Left when the promo ends: <strong>$1,040</strong>; paying about <strong>$458</strong> a month clears it in time</li></ul>',
        'sections'  => [
            [ 'When a balance transfer makes sense', '<p>A transfer works best when you can pay off most or all of the balance during the promotional period and the fee is smaller than the interest you would otherwise pay. Good credit is usually needed to qualify for the best offers.</p>' ],
            [ 'Watch the fine print', '<p>The promo APR often ends early if you miss a payment. New purchases may accrue interest at the regular rate, and the rate after the promo can be high. Most issuers will not let you transfer between cards they issue.</p>' ],
            [ 'Balance transfer vs personal loan', '<p>A balance transfer card suits balances you can clear in under about two years. A fixed-rate personal loan suits larger balances or longer payoff times because the rate and payment never change. See our debt consolidation calculator to compare.</p>' ],
        ],
        'terms'     => [
            [ 'Promo APR', 'The introductory rate, often 0%, for a set number of months.' ],
            [ 'Transfer fee', 'A percentage of the moved balance, commonly 3% to 5%.' ],
            [ 'Go-to APR', 'The regular rate that applies after the promo ends.' ],
        ],
        'faqs'      => [
            [ 'Is a balance transfer worth it?', 'It is worth it when the interest you avoid is larger than the transfer fee and you can pay off most of the balance during the promo.' ],
            [ 'How is a balance transfer fee calculated?', 'It is a percentage of the amount moved, usually 3% to 5%. A 3% fee on $8,000 is $240, added to the new balance.' ],
            [ 'What happens when the 0% period ends?', 'Any remaining balance starts accruing interest at the card’s regular APR, so aim to clear it before then.' ],
            [ 'Does a balance transfer hurt my credit?', 'There is usually a small temporary dip from the new credit inquiry and account, but lower utilization and on-time payments can help your score over time.' ],
        ],
        'sources'   => [
            [ 'CFPB: What is a balance transfer?', 'https://www.consumerfinance.gov/ask-cfpb/what-is-a-balance-transfer-en-45/' ],
        ],
        'related'   => [ 'debt-consolidation-calculator', 'loan-payoff-calculator', 'personal-loan-calculator', 'debt-to-income-ratio-calculator' ],
    ],

    'student-loan-rap-calculator' => [
        'focus'     => 'rap student loan calculator',
        'secondary' => [ 'repayment assistance plan calculator', 'rap vs standard repayment', 'student loan payment 2026', 'income driven repayment calculator' ],
        'title'     => 'RAP Student Loan Calculator (Repayment Assistance Plan)',
        'meta'      => 'Estimate your Repayment Assistance Plan payment and compare it with the Tiered Standard plan for federal loans first disbursed on or after July 1, 2026.',
        'excerpt'   => 'Estimate your monthly RAP payment from your income and dependents and compare it with the new Tiered Standard plan.',
        'answer'    => 'With $52,000 of adjusted gross income, no dependents and a $38,000 balance at 6.52%, the estimated RAP payment is about $216.67 a month (5% of AGI), against $331.44 on the Tiered Standard plan, which repays that balance in 15 years. RAP is about $114.77 a month lower in this example.',
        'steps'     => [
            'Enter your adjusted gross income and number of dependents.',
            'Enter your total federal loan balance and interest rate.',
            'Compare the RAP payment with the Tiered Standard payment and term.',
            'Check the Loan Simulator on studentaid.gov before choosing a plan.',
        ],
        'formula'   => '<p><strong>RAP payment ≈ (AGI × percentage ÷ 12) − $50 per dependent</strong>, with a <strong>$10 minimum</strong>. The percentage rises from 1% of AGI (income above $10,000) in 1-point steps for each additional $10,000, to 10% above $100,000.</p><p><strong>Tiered Standard term:</strong> 10 years under $25,000 owed, 15 years from $25,000, 20 years from $50,000, 25 years from $100,000. Payment uses the normal loan formula.</p>',
        'example'   => '<p>$52,000 AGI, no dependents, $38,000 at 6.52%:</p><ul><li>RAP: <strong>5% of AGI</strong>, about <strong>$216.67</strong> a month</li><li>Tiered Standard: 15 years, about <strong>$331.44</strong> a month</li><li>Monthly interest on the balance: about <strong>$206.47</strong>, so the RAP payment covers it</li></ul>',
        'sections'  => [
            [ 'Who this applies to', '<p>RAP is for federal student loans first disbursed on or after July 1, 2026, and is also offered to existing borrowers who move out of older income-driven plans as those close. The rules come from the 2025 budget law and the Department of Education’s implementation, so check studentaid.gov for the current details.</p>' ],
            [ 'Why RAP can be lower than the standard plan', '<p>RAP bases the payment on income, so borrowers with low or moderate earnings pay less than a fixed payment that repays the loan in 10 to 25 years. The trade-off is that a lower payment can mean more total interest over time unless the plan’s interest benefits apply.</p>' ],
            [ 'Estimate, not a quote', '<p>This calculator follows the published formula in simplified form. Your servicer applies the final figures, including how household income and filing status are counted.</p>' ],
        ],
        'terms'     => [
            [ 'RAP', 'Repayment Assistance Plan, an income-based plan for new federal loans.' ],
            [ 'AGI', 'Adjusted gross income from your tax return.' ],
            [ 'Tiered Standard plan', 'A fixed-payment plan whose length depends on how much you owe.' ],
        ],
        'faqs'      => [
            [ 'What is the Repayment Assistance Plan?', 'RAP is an income-driven repayment plan for federal student loans first disbursed on or after July 1, 2026, with payments between 1% and 10% of adjusted gross income.' ],
            [ 'How is the RAP payment calculated?', 'A percentage of AGI, based on income band, divided by 12, reduced by $50 for each dependent, with a $10 minimum.' ],
            [ 'RAP or Tiered Standard, which is better?', 'RAP usually has the lower payment when income is modest. Tiered Standard has a fixed payment and a set payoff date. Compare both with your numbers.' ],
            [ 'Where can I confirm my payment?', 'Use the Loan Simulator on studentaid.gov or ask your loan servicer.' ],
        ],
        'sources'   => [
            [ 'Federal Student Aid: Repayment plans', 'https://studentaid.gov/manage-loans/repayment/plans' ],
        ],
        'related'   => [ 'loan-payoff-calculator', 'debt-to-income-ratio-calculator', 'monthly-budget-planner' ],
    ],

    ];
}
