<?php
/**
 * Batch 5a for new-content.php: Social Security 2027 earnings limit guide.
 * Facts verified on 27 October 2026 against primary and reported-primary sources:
 * - SSA retirement earnings test page (ssa.gov/OACT/COLA/rtea.html): structure of the two exempt amounts,
 *   the $1-for-$2 and $1-for-$3 withholding rules, and that earnings in or after the month of reaching full
 *   retirement age (FRA/NRA) do not count. This page could not be fetched directly in this environment (the
 *   site returned an access-denied response to automated requests), so its 2026 figures were confirmed from
 *   cached search results rather than a live page read.
 * - SSA's October 14, 2026 announcement of the 2027 COLA (3.6%), which the site has already used in
 *   new-content.php (social-security-2027-cola-increase quick answer) and which multiple outlets report also
 *   included the 2027 earnings test limits. Because a direct ssa.gov fact-sheet fetch was not possible, the
 *   specific 2027 dollar limits below ($25,560 lower limit and $67,920 higher limit) are reported figures
 *   attributed to that SSA announcement by several independent secondary sources, including a Society of
 *   Actuaries newsletter dated October 14, 2026 and multiple news outlets citing SSA directly. They are
 *   flagged in the guide as figures to confirm on ssa.gov, since this guide could not complete a live
 *   ssa.gov fetch to quote the fact sheet first-hand.
 * - 2026 baseline figures ($24,480 lower limit, $65,160 higher limit) are confirmed, stable SSA figures used
 *   in multiple of this site's existing published guides and widely reported without conflict.
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_new_content_guides_5a() {
    return [
    [
        'slug' => 'social-security-earnings-limit-2027',
        'title' => 'Social Security Earnings Limit 2027: How Much You Can Work and Earn',
        'keyword' => 'social security earnings limit 2027',
        'desc' => 'Social security earnings limit 2027: the $25,560 and $67,920 thresholds, how $1-for-$2 and $1-for-$3 withholding works, and a worked example.',
        'excerpt' => 'The 2027 Social Security earnings test limits, how benefit withholding is calculated above them, and a worked example for someone earning $30,000 while under full retirement age.',
        'answer' => 'For 2027, Social Security is reported to withhold $1 in benefits for every $2 you earn above $25,560 if you will not reach full retirement age all year, or $1 for every $3 above $67,920 in the year you reach full retirement age. The limit applies only before full retirement age; after that, you can earn any amount without losing benefits.',
        'publish_at' => '2026-11-02 09:00',
        'takeaways' => [
            'Two different 2027 earnings limits apply depending on whether you reach full retirement age during the year.',
            'Benefits are withheld, not forfeited forever: SSA raises your monthly payment later to account for withheld months.',
            'The earnings test only counts wages and net self-employment income, not pensions, interest or investment gains.',
            'Once you reach full retirement age, the earnings limit no longer applies and you can earn any amount.',
        ],
        'content' => '<p class="lead">The <strong>social security earnings limit 2027</strong> decides how much you can earn from a job while collecting Social Security before full retirement age without some of your benefit being withheld. Based on Social Security Administration figures reported alongside its October 14, 2026 cost-of-living announcement, the 2027 limits are expected to rise to $25,560 for people who will not reach full retirement age at all during 2027, and $67,920 for people who reach full retirement age sometime during the year. This guide explains both limits, how the withholding formulas work, and runs a worked example.</p>'
            . '<p><em>The specific 2027 dollar limits in this guide are reported figures tied to SSA\'s October 2026 announcement. Confirm the final numbers on the SSA retirement earnings test page before making decisions based on them.</em></p>'
            . '<h2>What is the Social Security earnings limit?</h2><p>The earnings limit, officially called the retirement earnings test, applies only if you claim Social Security retirement benefits before reaching full retirement age (FRA) and keep working. The Social Security Administration withholds part of your benefit if your earnings from work go above a yearly exempt amount. The test has two separate exempt amounts, because the rule treats the year you actually reach FRA differently from every year before it.</p>'
            . '<h2>Social security earnings limit 2027: the two thresholds</h2><table><thead><tr><th>Situation</th><th>2026 limit</th><th>2027 limit (reported)</th><th>Withholding formula</th></tr></thead><tbody>'
            . '<tr><td>Under full retirement age for all of the year</td><td>$24,480</td><td>$25,560</td><td>$1 withheld for every $2 earned above the limit</td></tr>'
            . '<tr><td>Reaching full retirement age sometime during the year (counts only earnings in months before the month you reach FRA)</td><td>$65,160</td><td>$67,920</td><td>$1 withheld for every $3 earned above the limit</td></tr>'
            . '<tr><td>At and after full retirement age</td><td>No limit</td><td>No limit</td><td>None; benefits are not reduced for earnings</td></tr>'
            . '</tbody></table><p>Notice the higher limit is roughly 2.7 times the lower one, and the withholding rate in that year is gentler ($1 for $3, not $1 for $2). Only the months before you reach FRA are tested in that year.</p>'
            . '<h2>How Social Security earnings limit withholding actually works</h2><p>The rule only looks at earned income: wages from a job and net earnings from self-employment. It ignores pensions, annuities, investment income, interest, dividends, capital gains, rental income from property you do not actively manage, and the Social Security benefit itself. If you are under FRA all year and your earnings go above the lower limit, SSA withholds $1 of benefits for every $2 above that limit. In the year you reach FRA, only earnings in the months before you turn FRA count, and the rate becomes $1 withheld for every $3 above the higher limit. Starting the month you reach FRA, there is no limit at all, and SSA recalculates your benefit going forward with no further reduction for work.</p>'
            . '<h3>The "grace year" rule for the year you retire</h3><p>If you retire mid-year and already earned more than the yearly limit before you stopped working, a special monthly rule can still let you collect a full benefit for any month after retirement in which your earnings fall under a monthly limit (about one-twelfth of the yearly amount) and you did not perform substantial self-employment work. This applies only in the first year you are entitled to benefits.</p>'
            . '<h3>The withheld money is not gone for good</h3><p>A common misunderstanding is that withheld benefits are simply lost. They are not. Once you reach full retirement age, SSA recalculates your monthly benefit to credit you for the months benefits were withheld, in effect treating those as months you had not yet claimed. Over a normal retirement, many people who work past claiming age recover some or all of the withheld amount through this higher ongoing payment, though the math depends on how long you live after FRA.</p>'
            . '<h2>Worked example: earning $30,000 while under full retirement age in 2027</h2><p>Assume someone claimed Social Security before full retirement age and will not reach FRA at any point in 2027, so the lower limit applies.</p><table><thead><tr><th>Step</th><th>Amount</th></tr></thead><tbody>'
            . '<tr><td>2027 earnings from a job</td><td>$30,000</td></tr>'
            . '<tr><td>2027 lower earnings limit (reported)</td><td>$25,560</td></tr>'
            . '<tr><td>Earnings above the limit</td><td>$4,440</td></tr>'
            . '<tr><td>Benefits withheld ($1 for every $2 above the limit)</td><td>$2,220</td></tr>'
            . '</tbody></table><p>SSA does not usually withhold this gradually from each monthly check. Instead, it typically withholds entire monthly payments until the $2,220 is covered, starting from January, then resumes paying the full monthly benefit once the withheld total is reached. For this example, at a monthly benefit of roughly $1,850, SSA would withhold about one full month of benefits and part of a second to reach $2,220, then pay normally again. The $2,220 is not lost; it becomes part of the credit SSA applies once this person reaches full retirement age. Estimate your own numbers with the <a href="{tool:social-security-cola-calculator}">Social Security COLA calculator</a> and compare against your expected <a href="/average-social-security-check-by-age/">average check by age</a>.</p>'
            . '<h3>Second example: reaching full retirement age during 2027</h3><p>Suppose someone turns FRA in July 2027 and earns $50,000 from January through June, before FRA, with no countable earnings after. Only the $50,000 counts, and the higher limit and $1-for-$3 formula apply.</p><table><thead><tr><th>Step</th><th>Amount</th></tr></thead><tbody>'
            . '<tr><td>Countable earnings before the month of reaching FRA</td><td>$50,000</td></tr>'
            . '<tr><td>2027 higher earnings limit (reported)</td><td>$67,920</td></tr>'
            . '<tr><td>Earnings above the limit</td><td>$0 (earnings are below the higher limit)</td></tr>'
            . '<tr><td>Benefits withheld</td><td>$0</td></tr>'
            . '</tbody></table><p>Because the $50,000 is below the $67,920 higher limit, nothing is withheld in this case, and starting the month this person reaches FRA, there is no earnings limit at all, no matter how much they earn afterward.</p>'
            . '<h2>Who does the earnings limit apply to?</h2><ul><li><strong>It applies to you</strong> if you are collecting retirement, spousal, or survivor benefits before your own full retirement age and you are still working.</li><li><strong>It does not apply</strong> once you reach full retirement age, regardless of how much you earn.</li><li><strong>It is separate from taxes on benefits.</strong> Even after FRA, when the earnings test no longer reduces your check, your combined income can still make part of your Social Security benefit subject to federal income tax.</li><li><strong>It is unrelated to Supplemental Security Income (SSI)</strong>, which has its own, much lower income and resource rules.</li></ul>'
            . '<h2>Why the earnings limit rises most years</h2><p>SSA generally adjusts both earnings test limits each year based on growth in average wages, in the same way it adjusts the Social Security taxable maximum. That is why the limit tends to move most years, including into 2027, alongside the annual cost-of-living adjustment. The 2027 COLA was announced at 3.6%; see our <a href="/social-security-2027-cola-increase/">2027 COLA increase guide</a> and <a href="/social-security-2027-cola-chart/">2027 COLA chart</a> for how that raise affects your monthly check separately from the earnings test.</p>'
            . '<h2>Common mistakes with the earnings limit</h2><ul><li><strong>Assuming retirement income counts.</strong> Pensions, 401(k) withdrawals, interest and investment income are not counted against the earnings limit.</li><li><strong>Forgetting the limit disappears at full retirement age.</strong> Many people keep worrying about it long after it stops applying to them.</li><li><strong>Not reporting an estimated earnings change to SSA.</strong> If your work income changes a lot, telling SSA can prevent a large one-time withholding or repayment later.</li><li><strong>Thinking withheld money is lost forever.</strong> It is credited back through a higher monthly benefit once you reach full retirement age.</li><li><strong>Mixing up the two limits.</strong> The lower limit and $1-for-$2 rate apply before the year you reach FRA; the higher limit and $1-for-$3 rate apply only in the months of your FRA year before you actually reach FRA.</li></ul>'
            . '<h2>Related guides</h2><ul><li><a href="/social-security-2027-cola-increase/">Social Security 2027 COLA increase: what it means for your check</a></li><li><a href="/social-security-2027-cola-chart/">Social Security 2027 COLA chart</a></li><li><a href="/average-social-security-check-by-age/">Average Social Security check by age</a></li></ul>'
            . '<h2>Sources</h2><p>Social Security Administration, <a href="https://www.ssa.gov/OACT/COLA/rtea.html" rel="noopener" target="_blank">Retirement Earnings Test Exempt Amounts</a> (structure of the two exempt amounts and the withholding formulas); Social Security Administration, <a href="https://www.ssa.gov/news/press/releases/" rel="noopener" target="_blank">SSA Press Releases</a> (home of the official October 14, 2026 cost-of-living adjustment announcement). The 2027 figures of $25,560 and $67,920 are as reported from SSA\'s October 14, 2026 announcement; confirm the final numbers on ssa.gov before relying on them for a specific decision.</p>'
            . '<p>This guide is educational and not personal tax or financial advice.</p>'
            . '{faq}',
        'faqs' => [
            [ 'What is the Social Security earnings limit for 2027?', 'Based on figures reported alongside SSA\'s October 2026 cost-of-living announcement, the 2027 earnings limit is expected to be $25,560 for people who will not reach full retirement age during the year, and $67,920 for people who reach full retirement age sometime in 2027. Confirm the final numbers on ssa.gov.' ],
            [ 'How much will be withheld if I earn over the limit in 2027?', 'If you are under full retirement age all year, SSA is expected to withhold $1 in benefits for every $2 you earn above $25,560. In the year you reach full retirement age, the rate is gentler: $1 withheld for every $3 earned above $67,920, counting only earnings before the month you reach full retirement age.' ],
            [ 'Does the earnings limit apply after full retirement age?', 'No. Once you reach full retirement age, the earnings test no longer applies, and you can earn any amount from work without losing any Social Security benefit. Your combined income can still affect whether part of your benefit is taxable, which is a separate rule.' ],
            [ 'What counts as earnings for the Social Security earnings test?', 'Only wages from a job and net earnings from self-employment count. Pension payments, 401(k) or IRA withdrawals, interest, dividends, capital gains and most rental income are not counted against the earnings limit.' ],
            [ 'Is money withheld under the earnings limit lost permanently?', 'No. Social Security credits you for the months benefits were withheld by increasing your monthly benefit once you reach full retirement age. Depending on how long you live after that, you may recover some or all of the withheld amount over time.' ],
            [ 'Why are there two different earnings limits?', 'The lower limit and $1-for-$2 withholding rate apply to every year before the one in which you reach full retirement age. The higher limit and gentler $1-for-$3 rate apply only in the year you actually reach full retirement age, and only to earnings in the months before you reach it.' ],
            [ 'Does the earnings limit apply to spousal or survivor benefits?', 'Yes. The same retirement earnings test applies to anyone collecting Social Security retirement, spousal, or survivor benefits before their own full retirement age who also has earnings from work, not only to retired workers collecting their own benefit.' ],
            [ 'Has the SSA officially confirmed the 2027 earnings limit figures?', 'The 2027 COLA of 3.6% was announced by SSA on October 14, 2026. The $25,560 and $67,920 earnings limit figures are as reported from that announcement; check ssa.gov for the final confirmed numbers before relying on them.' ],
        ],
    ],
    ];
}
