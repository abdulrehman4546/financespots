<?php
/**
 * Editorial content + keywords, batch 11: 401(k) Early Withdrawal Calculator, Retirement Withdrawal Calculator.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_tool_content_data_11() {
    return [

    '401k-early-withdrawal-calculator' => [
        'focus'     => '401k early withdrawal calculator',
        'secondary' => [ 'tax penalty for 401k withdrawal', '401k tax calculator withdrawal', 'how much will i pay in taxes on 401k withdrawal', 'early withdrawal penalty calculator', 'rule of 55 calculator' ],
        'title'     => '401(k) Early Withdrawal Tax Calculator',
        'meta'      => 'Free 401(k) early withdrawal calculator: see the 10% penalty, federal and state tax, and what you actually keep from a traditional 401(k) or IRA withdrawal.',
        'excerpt'   => 'Estimate the 10% penalty, federal income tax and state tax on an early 401(k) or IRA withdrawal, and what you actually keep.',
        'answer'    => 'An early 401(k) or IRA withdrawal before age 59½ is taxed as ordinary income and usually hit with a 10% federal penalty on top. A $20,000 withdrawal for someone with $50,000 of other taxable income, in the 22% bracket, owes about $4,400 in federal tax plus a $2,000 penalty — keeping roughly $13,600 of the original $20,000, before any state tax.',
        'steps'     => [
            'Enter the withdrawal amount and your age at the time you take it.',
            'Enter your filing status and your other taxable income for the year, not counting this withdrawal.',
            'Select a 10% penalty exception if one applies to your situation — otherwise leave it as "None".',
            'Enter a state income tax rate if your state taxes retirement account withdrawals.',
            'Read the federal tax, penalty, state tax and what you actually keep.',
        ],
        'formula'   => '<p><strong>Federal tax on the withdrawal = tax(other income + withdrawal) − tax(other income)</strong>, using the 2026 marginal tax brackets — the withdrawal is taxed at your marginal rate(s), stacking on top of your other income, not a flat rate.</p><p><strong>10% penalty = withdrawal × 10%</strong>, unless you are 59½ or older or qualify for an IRS exception such as the Rule of 55, SEPP (72(t)), total and permanent disability, or others.</p><p><strong>State tax = withdrawal × your state\'s rate</strong> (most states tax a traditional 401(k)/IRA withdrawal as ordinary income; nine states have no wage income tax at all).</p>',
        'example'   => '<p>$20,000 withdrawal, age 45, single, $50,000 of other taxable income, no exception, no state tax:</p><ul><li>Tax without the withdrawal: on $50,000 taxable income</li><li>Tax with the withdrawal: on $70,000 taxable income</li><li>Federal tax on the withdrawal: about <strong>$4,400</strong> (the withdrawal lands mostly in the 22% bracket)</li><li>10% early withdrawal penalty: <strong>$2,000</strong></li><li>Total tax and penalty: <strong>$6,400</strong></li><li>What you actually keep: about <strong>$13,600</strong>, an effective rate of 32%</li></ul><p>The plan itself would likely withhold 20% ($4,000) at the time of payout for a 401(k) — that is a prepayment toward the $6,400 owed, not the final number.</p>',
        'sections'  => [
            [ 'Why early withdrawals cost so much', '<p>A traditional 401(k) or IRA withdrawal is taxed twice in effect: once as ordinary income (because the money was never taxed going in) and, if you are under 59½ with no exception, again with a flat 10% penalty under IRC §72(t). The income tax applies at your marginal rate, so a large withdrawal can push part of itself into a higher bracket than your regular income alone would reach.</p>' ],
            [ 'The Rule of 55', '<p>If you leave your employer in or after the calendar year you turn 55 (age 50 for qualified public safety employees), you can withdraw from <em>that employer\'s</em> 401(k) or 403(b) without the 10% penalty. It does not apply to IRAs, and it does not apply to 401(k)s from previous employers — only the plan tied to the job you just left. Rolling that 401(k) into an IRA ends the exception for that money.</p>' ],
            [ 'Substantially equal periodic payments (SEPP / 72(t))', '<p>SEPP lets you take penalty-free withdrawals from an IRA or 401(k) at any age, but you must take a fixed, IRS-calculated payment every year for at least 5 years or until you turn 59½, whichever is longer. Stopping early or changing the amount can retroactively trigger the 10% penalty on everything already withdrawn, plus interest, so this is a long commitment, not a one-time withdrawal.</p>' ],
            [ 'Other penalty exceptions', '<p>Beyond the Rule of 55 and SEPP, the 10% penalty is generally waived for: total and permanent disability, death (beneficiary distributions), a qualified domestic relations order (QDRO) in a divorce, unreimbursed medical expenses above 7.5% of adjusted gross income, and an IRS levy on the account. IRAs have a few additional exceptions not available to 401(k)s, including up to $10,000 for a first home and qualified higher-education expenses. Every exception still owes regular income tax — only the 10% penalty is waived.</p>' ],
            [ 'The mandatory 20% withholding is not your tax bill', '<p>When a 401(k) or 403(b) pays an eligible distribution directly to you rather than rolling it over, the plan is required to withhold 20% for federal tax, regardless of your actual bracket. If your real tax and penalty come to more than 20%, as in the example above, you owe the difference when you file. If it comes to less, you get money back. IRAs default to 10% withholding, and you can usually adjust or opt out of it on the distribution request form.</p>' ],
            [ 'Hardship withdrawals are not an exception by themselves', '<p>A 401(k) hardship withdrawal lets you access funds for an immediate, heavy financial need, but it does not by itself waive the 10% penalty — it only lets you take the money out of the plan. The penalty still applies unless a separate exception (like the medical-expense exception above) also applies to your situation.</p>' ],
        ],
        'terms'     => [
            [ 'Early withdrawal', 'A distribution from a retirement account before age 59½.' ],
            [ 'Rule of 55', 'An IRS exception letting workers who leave a job in or after the year they turn 55 withdraw from that employer\'s plan without the 10% penalty.' ],
            [ 'SEPP / 72(t)', 'A series of substantially equal periodic payments that avoids the early withdrawal penalty if followed for the required minimum period.' ],
            [ 'Mandatory withholding', 'The 20% a 401(k)/403(b) plan must withhold on an eligible distribution paid directly to you; a prepayment, not the final tax owed.' ],
        ],
        'faqs'      => [
            [ 'How much tax will I pay on a 401(k) withdrawal?', 'The withdrawal is added to your taxable income for the year and taxed at your marginal rate(s), plus a 10% penalty if you are under 59½ with no exception. The exact amount depends on your other income, filing status and the size of the withdrawal — use the calculator above for your own numbers.' ],
            [ 'Is there a way to avoid the 10% penalty?', 'Yes, if you qualify for an IRS exception: the Rule of 55, substantially equal periodic payments (SEPP), total and permanent disability, a QDRO, certain medical expenses, death, or an IRS levy, among others. You still owe regular income tax in every case — only the 10% penalty is waived.' ],
            [ 'What is the Rule of 55?', 'If you separate from your employer in or after the calendar year you turn 55, you can withdraw from that employer\'s 401(k) or 403(b) without the 10% early withdrawal penalty. It only applies to the plan from the job you just left, not IRAs or old 401(k)s from earlier employers.' ],
            [ 'Why did my 401(k) only pay me 80% of my withdrawal?', 'Plans are required to withhold 20% for federal tax on an eligible distribution paid directly to you. This is a prepayment toward your actual tax bill, not necessarily the final amount — you could owe more or get a refund when you file, depending on your real tax rate and whether the penalty applies.' ],
            [ 'Does this apply to Roth 401(k) withdrawals?', 'This calculator estimates a traditional (pre-tax) account. Withdrawing your own Roth contributions is generally tax- and penalty-free at any time; only the earnings portion of a non-qualified early Roth withdrawal is taxed and potentially penalized.' ],
            [ 'Is a 401(k) loan better than a withdrawal?', 'A loan avoids income tax and the 10% penalty as long as you repay it on schedule, usually within 5 years, but it reduces your invested balance while outstanding and can become a taxable distribution if you leave your job and cannot repay it. Compare the real cost of a loan against the tax and penalty cost of a withdrawal before choosing.' ],
        ],
        'sources'   => [
            [ 'IRS: Retirement topics — exceptions to tax on early distributions', 'https://www.irs.gov/retirement-plans/plan-participant-employee/retirement-topics-tax-on-early-distributions' ],
            [ 'IRS: Substantially equal periodic payments', 'https://www.irs.gov/retirement-plans/plan-participant-employee/substantially-equal-periodic-payments' ],
            [ 'IRS: Topic no. 558, additional tax on early distributions from retirement plans', 'https://www.irs.gov/taxtopics/tc558' ],
        ],
        'related'   => [ 'income-tax-calculator', '401k-calculator', 'ira-calculator', 'retirement-withdrawal-calculator' ],
    ],

    'retirement-withdrawal-calculator' => [
        'focus'     => 'retirement withdrawal calculator',
        'secondary' => [ 'how long will my money last in retirement', 'retirement savings depletion calculator', '4 percent rule calculator', 'how long will my retirement savings last', 'safe withdrawal rate calculator' ],
        'title'     => 'Retirement Withdrawal Calculator: How Long Your Money Lasts',
        'meta'      => 'Free retirement withdrawal calculator: enter your savings and planned withdrawal to see how many years your money lasts, with the 4% rule as a benchmark.',
        'excerpt'   => 'See how many years your retirement savings could last at your planned withdrawal rate, compared with the traditional 4% rule.',
        'answer'    => 'A $500,000 portfolio withdrawing $30,000 a year (a 6% first-year rate), growing 6% annually and raising withdrawals 3% for inflation, runs out in about 25 years. The traditional "4% rule" would cap the first-year withdrawal at about $20,000 a year, designed to have a high probability of lasting at least 30 years.',
        'steps'     => [
            'Enter your current retirement savings balance.',
            'Enter the amount you plan to withdraw in the first year.',
            'Enter an expected annual return and an inflation rate.',
            'Read how many years the balance lasts, and compare your withdrawal rate with the 4% rule.',
        ],
        'formula'   => '<p><strong>Each year: balance = balance × (1 + return) − withdrawal</strong>, then the withdrawal itself increases by the inflation rate for the next year, so your purchasing power stays level. The simulation repeats until the balance reaches zero or the chosen number of years is reached.</p><p><strong>4% rule withdrawal = savings × 4%</strong>, a widely cited starting point from the Trinity study and similar research on historical U.S. market returns over 30-year retirements.</p>',
        'example'   => '<p>$500,000 saved, $30,000 first-year withdrawal (6% rate), 6% annual return, 3% inflation:</p><ul><li>Balance roughly holds steady for the first several years, since the return nearly covers the withdrawal</li><li>As inflation pushes the withdrawal higher each year, the balance begins to decline faster</li><li>The portfolio is depleted in about <strong>25 years</strong></li><li>The 4% rule would have suggested starting at about <strong>$20,000</strong> instead of $30,000</li></ul>',
        'sections'  => [
            [ 'What the 4% rule actually says', '<p>The 4% rule comes from research (most famously the 1998 Trinity study) testing historical U.S. stock and bond returns to find a withdrawal rate that would have survived every rolling 30-year period, adjusting the withdrawal for inflation each year. A 4% first-year withdrawal, from a 50-75% stock portfolio, had a high historical success rate over 30 years. It is a rule of thumb from the past, not a guarantee, and some researchers argue a lower rate is safer for very long retirements or today\'s valuations.</p>' ],
            [ 'Why a fixed average return is a simplification', '<p>This calculator, like most simple withdrawal tools, assumes the same return every single year. Real portfolios do not work that way — markets have strong years and weak years in no predictable order. A few bad years early in retirement, combined with withdrawals, can deplete a portfolio much faster than an average-return model suggests, even if the long-run average return is the same. This is called sequence-of-returns risk, and it is one reason retirement researchers prefer historical simulation or Monte Carlo analysis over a single fixed-return projection.</p>' ],
            [ 'What this calculator leaves out', '<p>This is a simplified model. It does not account for taxes on withdrawals, Social Security income (which can reduce how much you need to pull from savings), required minimum distributions, healthcare cost spikes, or changes in spending across retirement (many retirees spend more in early "go-go" years and less later). Treat the result as a rough benchmark, not a complete retirement plan.</p>' ],
            [ 'Ways to make savings last longer', '<ul><li><strong>Lower the withdrawal rate.</strong> Dropping from 6% to 4-5% meaningfully extends how long a portfolio lasts, as the example above shows.</li><li><strong>Delay Social Security.</strong> Waiting past full retirement age increases the benefit by about 8% a year up to age 70, reducing how much you need from savings.</li><li><strong>Adjust spending in down years.</strong> A flexible approach — spending less after a market drop — historically outperforms a rigid fixed withdrawal in surviving longer retirements.</li><li><strong>Keep some growth exposure.</strong> An all-cash portfolio protects against short-term drops but historically has not kept pace with inflation over a 20-30 year retirement.</li></ul>' ],
        ],
        'terms'     => [
            [ 'Withdrawal rate', 'The percentage of a portfolio withdrawn in the first year of retirement, usually the figure researchers test for sustainability.' ],
            [ '4% rule', 'A guideline suggesting a 4% first-year withdrawal, adjusted for inflation each year after, has historically had a high chance of lasting 30 years.' ],
            [ 'Sequence-of-returns risk', 'The risk that poor investment returns early in retirement, combined with withdrawals, deplete a portfolio faster than the long-run average return would suggest.' ],
        ],
        'faqs'      => [
            [ 'How long will my retirement savings last?', 'It depends on your balance, withdrawal amount, investment return and inflation. Enter your own numbers in the calculator above for an estimate — as a benchmark, a 4% first-year withdrawal rate has historically had a high probability of lasting at least 30 years.' ],
            [ 'What is a safe withdrawal rate in retirement?', 'The traditional benchmark is 4% of the starting balance in the first year, adjusted for inflation afterward. Some researchers suggest a lower rate, such as 3-3.5%, is safer for retirements longer than 30 years or given more conservative return expectations.' ],
            [ 'Does the 4% rule still work today?', 'It remains a widely used starting point, but it is based on historical U.S. market data and is not a guarantee. Many planners now treat it as a rough benchmark to adjust from, rather than a fixed rule, especially for early retirees facing a retirement longer than 30 years.' ],
            [ 'What happens if the market drops early in my retirement?', 'A market decline in the first few years of retirement, combined with ongoing withdrawals, can deplete a portfolio much faster than an average-return projection suggests — this is called sequence-of-returns risk. Reducing withdrawals temporarily after a downturn is one common way retirees manage this risk.' ],
            [ 'Should I include Social Security in this calculator?', 'This tool models savings withdrawals only. If Social Security or a pension covers part of your spending, you can enter just the amount you expect to withdraw from savings, rather than your full spending need, for a more accurate picture.' ],
        ],
        'sources'   => [
            [ 'Trinity study (updated analyses) on sustainable withdrawal rates', 'https://www.aaii.com/journal/article/retirement-savings-choosing-a-withdrawal-rate-that-is-sustainable' ],
            [ 'Social Security Administration: delayed retirement credits', 'https://www.ssa.gov/benefits/retirement/planner/delayret.html' ],
        ],
        'related'   => [ 'retirement-savings-calculator', 'fire-calculator', '401k-calculator', '401k-early-withdrawal-calculator' ],
    ],

    ];
}
