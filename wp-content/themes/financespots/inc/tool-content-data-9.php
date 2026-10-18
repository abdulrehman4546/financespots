<?php
/**
 * Editorial content + keywords, batch 9: Social Security COLA calculator, Trump Account growth calculator.
 * Facts: SSA 2027 COLA announcement (3.6%, October 14, 2026); IRS release IR-2025-117 and Notice 2025-68 (Trump Accounts).
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_tool_content_data_9() {
    return [

    'social-security-cola-calculator' => [
        'focus'     => 'social security cola calculator',
        'secondary' => [ '2027 social security cola calculator', 'social security increase calculator', 'social security raise 2027', 'how much will my social security increase' ],
        'title'     => 'Social Security COLA Calculator (2027 Raise: 3.6%)',
        'meta'      => 'Free Social Security COLA calculator: enter your benefit to see your new check after the 3.6% 2027 raise and the net gain after any Medicare premium rise.',
        'excerpt'   => 'See how much your Social Security check goes up with the 2027 cost-of-living adjustment of 3.6%.',
        'answer'    => 'The Social Security Administration announced a 3.6% cost-of-living adjustment (COLA) for 2027 on October 14, 2026. A $2,000 monthly benefit becomes $2,072, which is $72 more a month and $864 more a year. If your Medicare Part B premium rises by $6.60, the net gain is $65.40 a month.',
        'steps'     => [ 'Enter your monthly benefit before the raise.', 'Leave the COLA at 3.6% for 2027, or change it to test other percentages.', 'Optional: enter the increase in your Medicare Part B premium.', 'Read your new monthly benefit, the yearly increase and the net gain.' ],
        'formula'   => '<p><strong>New benefit = current benefit × (1 + COLA).</strong> <strong>Increase = new benefit − current benefit.</strong> <strong>Net increase = increase − change in Part B premium.</strong></p>',
        'example'   => '<p>$2,000 a month with a 3.6% COLA:</p><ul><li>New benefit: 2,000 × 1.036 = <strong>$2,072</strong></li><li>Increase: <strong>$72</strong> a month, <strong>$864</strong> a year</li><li>If Part B rises $6.60 a month: net gain <strong>$65.40</strong> a month</li></ul>',
        'sections'  => [
            [ 'How the COLA is set', '<p>The adjustment is based on the rise in the Consumer Price Index for Urban Wage Earners and Clerical Workers (CPI-W) from the third quarter of the prior year to the third quarter of this year. The percentage is rounded to the nearest tenth. The 2027 increase is 3.6%, compared with 2.8% for 2026, 2.5% for 2025 and 3.2% for 2024.</p>' ],
            [ 'When the raise arrives', '<p>Social Security payments reflecting the increase begin in January 2027. Supplemental Security Income (SSI) recipients see theirs earlier, starting with the payment on December 31, 2026. The SSA sends notices in December, and you can also see your new amount in your my Social Security account.</p>' ],
            [ 'Medicare premiums can take part of it', '<p>Most beneficiaries have the Medicare Part B premium deducted from their Social Security payment. When the premium rises, the net raise is smaller than the COLA. The 2027 premium is announced in the fall, so use the premium box to test what a rise would do to your check.</p>' ],
            [ 'Taxes on benefits', '<p>Up to 85% of benefits can be taxable depending on your other income. The income thresholds for taxing benefits are not adjusted for inflation, so a larger COLA can push more of a benefit into taxable range over time. See our income tax calculator for the rest of your return.</p>' ],
        ],
        'terms'     => [ [ 'COLA', 'Cost-of-living adjustment, the yearly raise that keeps benefits in step with inflation.' ], [ 'CPI-W', 'The price index the SSA uses to set the COLA.' ], [ 'Part B premium', 'The monthly Medicare charge for doctor and outpatient coverage, usually deducted from Social Security.' ] ],
        'faqs'      => [
            [ 'What is the Social Security COLA for 2027?', '3.6%, announced by the Social Security Administration on October 14, 2026.' ],
            [ 'How much will my check go up?', 'Multiply your monthly benefit by 1.036. For the average retired worker the SSA estimates the increase at about $75 a month.' ],
            [ 'When will I get the 2027 increase?', 'Social Security payments with the new amount begin in January 2027. SSI increases start with the payment on December 31, 2026.' ],
            [ 'Does the COLA apply to SSI and disability benefits?', 'Yes. The 3.6% applies to Social Security retirement, survivor and disability benefits and to SSI.' ],
            [ 'Why is my raise smaller than 3.6%?', 'A higher Medicare Part B premium, tax withholding or other deductions can reduce the amount that reaches your bank account.' ],
        ],
        'sources'   => [ [ 'SSA: Cost-of-Living Adjustment (COLA) information', 'https://www.ssa.gov/oact/cola/colasummary.html' ], [ 'SSA: 2027 COLA fact sheet', 'https://www.ssa.gov/news/en/cola/factsheets/2027.html' ] ],
        'related'   => [ 'retirement-savings-calculator', 'income-tax-calculator', 'inflation-calculator', 'net-worth-calculator' ],
    ],

    'trump-account-calculator' => [
        'focus'     => 'trump account calculator',
        'secondary' => [ 'trump account growth calculator', 'trump account for kids calculator', 'how much will a trump account be worth', 'trump account $1,000 growth' ],
        'title'     => 'Trump Account Calculator: Growth to Age 18 and Beyond',
        'meta'      => 'Free Trump Account calculator: see what the $1,000 pilot deposit and yearly contributions could grow to by age 18 and later, with 4%, 7% and 10% scenarios.',
        'excerpt'   => 'Illustrate how a Trump Account for a child could grow, using your own deposit, contributions and return assumption.',
        'answer'    => 'If a child starts with the $1,000 federal pilot deposit and the family adds $600 a year, a 7% yearly return would grow the account to about $23,779 by age 18. The family would have put in $11,800, so about $11,979 is growth. With no further contributions, that balance would reach about $571,791 by age 65 at the same 7%. These are illustrations, not forecasts.',
        'steps'     => [ 'Enter the starting deposit ($1,000 if your child gets the federal pilot deposit, otherwise 0).', 'Enter how much you plan to add each year (the cap is $5,000 in total).', 'Enter your child’s age and a return assumption.', 'Read the balance at 18, the growth, the balance at a later age and the 4% and 10% scenarios.' ],
        'formula'   => '<p><strong>Balance = P × (1 + r)<sup>n</sup> + C × [((1 + r)<sup>n</sup> − 1) ÷ r]</strong>, where P is the starting deposit, C the yearly contribution, r the yearly return and n the years until age 18. After 18, the balance grows by (1 + r) each year with no more contributions.</p>',
        'example'   => '<p>$1,000 start, $600 a year from birth, 7% return:</p><ul><li>Total contributed by 18: <strong>$11,800</strong></li><li>Balance at 18: <strong>$23,779</strong> (growth <strong>$11,979</strong>)</li><li>At 4%: <strong>$17,413</strong>; at 10%: <strong>$32,919</strong></li><li>Same balance left alone until 65 at 7%: about <strong>$571,791</strong></li></ul>',
        'sections'  => [
            [ 'What a Trump Account is', '<p>A Trump Account is a new type of individual retirement account for children created by the 2025 tax law. It must be invested in a low-cost index fund that tracks the S&amp;P 500 or a similar index of mostly U.S. stocks. Eligible children born from 2025 through 2028 can receive a one-time $1,000 deposit from the U.S. Treasury when an election is made. See our guide for who qualifies and how to open one.</p>' ],
            [ 'Contribution limits to enter', '<p>Family and friends can contribute up to $5,000 per year in total for 2026 and 2027, with the limit adjusted for inflation after 2027. An employer can add up to $2,500 a year per employee, which counts toward the $5,000. The $1,000 federal pilot deposit does not count toward the limit.</p>' ],
            [ 'Why returns are only an assumption', '<p>Stock markets rise and fall, and the account value can drop below what you put in, especially over short periods. The 7% figure is a long-run illustration, not a guarantee. Fund fees would reduce results further. The 4% and 10% scenarios show a range.</p>' ],
            [ 'After age 18', '<p>Under the IRS guidance, money generally cannot be taken out before January 1 of the year the child turns 18, and after that the account is generally treated like a traditional IRA. Rules for withdrawals and taxes can change as regulations are finalized, so check IRS.gov for updates.</p>' ],
        ],
        'terms'     => [ [ 'Pilot deposit', 'The one-time $1,000 federal contribution for eligible children born 2025 to 2028.' ], [ 'Form 4547', 'The IRS form used to open a Trump Account and elect the pilot deposit.' ], [ 'Index fund', 'A fund that tracks a market index at low cost.' ] ],
        'faqs'      => [
            [ 'How much could a Trump Account be worth at 18?', 'It depends on contributions and returns. With the $1,000 pilot deposit, $600 a year and a 7% return, about $23,779. Returns are not guaranteed.' ],
            [ 'What is the Trump Account contribution limit?', '$5,000 per year in total for 2026 and 2027 (indexed after 2027), with employer contributions of up to $2,500 counting toward it. The $1,000 pilot deposit is outside the limit.' ],
            [ 'Who gets the $1,000 deposit?', 'U.S. citizen children born from January 1, 2025 through December 31, 2028 with a Social Security number, once an election is made.' ],
            [ 'Can money be withdrawn before 18?', 'Under IRS guidance, generally not before January 1 of the year the child turns 18.' ],
        ],
        'sources'   => [ [ 'IRS: Treasury and IRS issue guidance on Trump Accounts (IR-2025-117)', 'https://www.irs.gov/newsroom/treasury-irs-issue-guidance-on-trump-accounts-established-under-the-working-families-tax-cuts-notice-announces-upcoming-regulations' ], [ 'IRS: Instructions for Form 4547', 'https://www.irs.gov/pub/irs-pdf/i4547.pdf' ] ],
        'related'   => [ 'compound-interest-calculator', 'savings-goal-calculator', 'retirement-savings-calculator', 'cagr-calculator' ],
    ],

    ];
}
