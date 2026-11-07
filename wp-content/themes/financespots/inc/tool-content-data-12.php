<?php
/**
 * Editorial content + keywords, batch 12: Child Tax Credit Calculator, SALT Deduction Calculator.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_tool_content_data_12() {
    return [

    'child-tax-credit-calculator' => [
        'focus'     => 'child tax credit calculator',
        'secondary' => [ 'child tax credit 2026', 'child tax credit income limit', 'additional child tax credit', 'child tax credit phase out', 'how much is the child tax credit' ],
        'title'     => 'Child Tax Credit Calculator (2026)',
        'meta'      => 'Free child tax credit calculator: estimate your 2026 credit at $2,200 per child, see the income phase-out, and the refundable Additional Child Tax Credit.',
        'excerpt'   => 'Estimate your 2026 Child Tax Credit, including the income phase-out and how much could be refundable even if you owe no tax.',
        'answer'    => 'The 2026 Child Tax Credit is $2,200 per qualifying child under 17, plus $500 for other dependents. It phases out by $50 for every $1,000 of modified adjusted gross income above $200,000 (single) or $400,000 (married filing jointly). Up to $1,700 per child can be refundable through the Additional Child Tax Credit even if you owe no federal tax.',
        'steps'     => [
            'Enter your filing status and modified adjusted gross income (MAGI).',
            'Enter the number of qualifying children under 17 and any other dependents.',
            'Enter your earned income to estimate the refundable portion.',
            'Review your estimated credit, the phase-out reduction, and the maximum refundable amount.',
        ],
        'formula'   => '<p><strong>Total credit = (qualifying children × $2,200) + (other dependents × $500)</strong>.</p><p><strong>Phase-out reduction = $50 × ROUNDUP(MAGI − threshold, to the nearest $1,000)</strong>, where the threshold is $200,000 (single/HOH) or $400,000 (married filing jointly).</p><p><strong>Refundable (ACTC) = the smallest of</strong>: $1,700 × qualifying children, 15% × (earned income − $2,500), or the remaining credit after phase-out.</p>',
        'example'   => '<p>Married filing jointly, $420,000 MAGI, 2 qualifying children:</p><ul><li>Total credit before phase-out: $2,200 × 2 = <strong>$4,400</strong></li><li>MAGI is $20,000 over the $400,000 threshold, so the reduction is $50 × 20 = <strong>$1,000</strong></li><li>Final estimated credit: $4,400 − $1,000 = <strong>$3,400</strong></li></ul>',
        'sections'  => [
            [ 'Who qualifies as a child for this credit', '<p>A qualifying child must be under 17 at the end of the tax year, have a valid Social Security number, live with you for more than half the year, and be claimed as your dependent. A child who turns 17 during the year no longer qualifies for the full credit — they may instead qualify for the smaller $500 credit for other dependents.</p>' ],
            [ 'How the phase-out actually works', '<p>The credit does not disappear all at once. For every $1,000 (or part of $1,000) your MAGI exceeds the threshold, the total credit drops by $50, across all children combined. A family with a very high income can see their credit reduced to zero, but the reduction happens gradually rather than as a sudden cliff.</p>' ],
            [ 'What makes it refundable', '<p>If your tax liability is lower than your Child Tax Credit, the Additional Child Tax Credit (ACTC) lets you receive up to $1,700 per child back as a refund, even with zero tax owed. The refundable amount is capped at 15% of your earned income above $2,500 — families with very low earned income may not be able to claim the full refundable amount even though they qualify for the credit on paper.</p>' ],
            [ 'The $500 credit for other dependents', '<p>Dependents who do not meet the under-17 test — an older child, a disabled adult child, or an elderly parent you support — can still qualify for a flat $500 nonrefundable credit, using the same income phase-out thresholds as the main Child Tax Credit.</p>' ],
        ],
        'terms'     => [
            [ 'Qualifying child', 'A dependent under 17 with a Social Security number who lived with you more than half the year.' ],
            [ 'MAGI', 'Modified adjusted gross income — for most filers, the same as adjusted gross income (AGI).' ],
            [ 'ACTC', 'Additional Child Tax Credit — the refundable portion of the Child Tax Credit, up to $1,700 per child.' ],
            [ 'Schedule 8812', 'The IRS form used to calculate and claim the Child Tax Credit and ACTC on your return.' ],
        ],
        'faqs'      => [
            [ 'How much is the Child Tax Credit for 2026?', '$2,200 per qualifying child under 17, made permanent and indexed for inflation by the One Big Beautiful Bill Act. Other dependents who do not meet the under-17 test can receive a separate $500 credit.' ],
            [ 'What is the income limit for the Child Tax Credit?', 'The credit begins phasing out above $200,000 of MAGI for single filers and $400,000 for married filing jointly, reduced by $50 for every $1,000 over that threshold.' ],
            [ 'Is the Child Tax Credit refundable?', 'Partially. Up to $1,700 per child can be refunded through the Additional Child Tax Credit even if you owe no tax, limited to 15% of your earned income above $2,500.' ],
            [ 'Can I claim the Child Tax Credit for a child who turned 17 this year?', 'No. The child must be under 17 at the end of the tax year to qualify for the $2,200 credit. A child who turns 17 may instead qualify for the $500 credit for other dependents.' ],
            [ 'Does a baby born during the year qualify?', 'Yes. A child born at any point during the tax year, including December 31, qualifies as long as they have a Social Security number and meet the other requirements.' ],
        ],
        'sources'   => [
            [ 'IRS: Child Tax Credit', 'https://www.irs.gov/credits-deductions/individuals/child-tax-credit' ],
            [ 'IRS: Schedule 8812 instructions', 'https://www.irs.gov/forms-pubs/about-schedule-8812-form-1040' ],
        ],
        'related'   => [ 'income-tax-calculator', 'self-employment-tax-calculator', 'salt-deduction-calculator', '401k-early-withdrawal-calculator' ],
    ],

    'salt-deduction-calculator' => [
        'focus'     => 'salt deduction calculator',
        'secondary' => [ 'salt deduction 2026', 'salt tax cap', 'state and local tax deduction limit', 'salt deduction phase out', 'property tax deduction limit' ],
        'title'     => 'SALT Deduction Calculator (2026 Cap)',
        'meta'      => 'Free SALT deduction calculator: see your 2026 state and local tax deduction, the $40,400 cap, and how the phase-out above $505,000 MAGI works.',
        'excerpt'   => 'Calculate your 2026 SALT deduction, including the $40,400 cap and how it phases down for higher incomes.',
        'answer'    => 'The 2026 SALT (state and local tax) deduction cap is $40,400 ($20,200 if married filing separately), covering property tax plus either state income tax or sales tax combined. The cap phases down by 30% of MAGI above $505,000, but never below a $10,000 floor ($5,000 MFS). You can only use this deduction if you itemize instead of taking the standard deduction.',
        'steps'     => [
            'Enter your filing status and modified adjusted gross income (MAGI).',
            'Enter your property tax paid for the year.',
            'Enter your state/local income tax or sales tax paid, whichever is larger.',
            'Confirm whether you are itemizing deductions, then review your capped deduction.',
        ],
        'formula'   => '<p><strong>Combined SALT = property tax + (state/local income tax or sales tax, not both)</strong>.</p><p><strong>2026 cap = $40,400</strong> ($20,200 MFS), reduced by <strong>30% of MAGI above $505,000</strong>, down to a floor of $10,000 ($5,000 MFS).</p><p><strong>Deduction = the smaller of your combined SALT or the applicable cap</strong>, and only counts if you itemize.</p>',
        'example'   => '<p>Single filer, $350,000 MAGI (under the phase-out threshold), $9,000 property tax, $12,000 state income tax:</p><ul><li>Combined SALT: $9,000 + $12,000 = <strong>$21,000</strong></li><li>2026 cap at this income: the full <strong>$40,400</strong> (no phase-out, since MAGI is under $505,000)</li><li>Deduction: the smaller of the two = <strong>$21,000</strong>, fully deductible</li></ul><p>Compare this to a filer with $600,000 MAGI: the cap would be reduced to roughly $12,500 after the 30% phase-out, meaning a large share of their SALT payments would not be deductible.</p>',
        'sections'  => [
            [ 'What counts toward the SALT cap', '<p>The deduction combines three categories, but with a key restriction: property tax (real estate and, in some cases, personal property tax) plus either state and local income tax or state and local sales tax — you choose whichever is larger, not both. Residents of states with no income tax typically use the sales tax option instead.</p>' ],
            [ 'Why the cap exists and how it changed for 2026', '<p>The SALT deduction was capped at $10,000 starting with the 2018 tax year under the Tax Cuts and Jobs Act. The One Big Beautiful Bill Act raised the cap to $40,400 for 2026, with inflation adjustments in future years, while adding an income-based phase-out for higher earners that was not part of the original 2018 cap.</p>' ],
            [ 'The phase-out for higher incomes', '<p>Above $505,000 of MAGI, the cap itself shrinks — not the deduction directly. The cap is reduced by 30 cents for every dollar of MAGI over that threshold, but it never falls below a $10,000 floor ($5,000 for married filing separately), so even very high earners retain at least the old pre-2026 cap amount.</p>' ],
            [ 'Itemizing vs. the standard deduction', '<p>The SALT deduction only matters if your total itemized deductions (SALT, mortgage interest, charitable gifts, and others) exceed your standard deduction. For 2026 the standard deduction is $16,100 (single), $32,200 (married filing jointly), or $24,150 (head of household) — many filers with moderate SALT payments and no mortgage will still come out ahead taking the standard deduction instead.</p>' ],
        ],
        'terms'     => [
            [ 'SALT', 'State and local tax — the combined category of property tax plus state/local income or sales tax.' ],
            [ 'Itemizing', 'Listing out individual deductions (SALT, mortgage interest, charitable gifts) instead of taking the standard deduction.' ],
            [ 'MAGI', 'Modified adjusted gross income, used to determine the SALT cap phase-out for high earners.' ],
        ],
        'faqs'      => [
            [ 'What is the SALT deduction cap for 2026?', '$40,400 for most filers ($20,200 if married filing separately), covering property tax plus either state/local income tax or sales tax. The cap phases down for MAGI above $505,000, with a $10,000 floor.' ],
            [ 'Can I deduct both state income tax and sales tax?', 'No. You choose whichever is larger — state and local income tax, or state and local sales tax — and add that to your property tax. You cannot deduct both income and sales tax in the same year.' ],
            [ 'Does the SALT deduction phase out for high earners?', 'Yes, starting in 2026. The $40,400 cap itself is reduced by 30% of MAGI above $505,000, but it never drops below a $10,000 floor, so the deduction does not disappear entirely even at very high incomes.' ],
            [ 'Is the SALT deduction available if I take the standard deduction?', 'No. SALT is only deductible if you itemize. If your total itemized deductions, including SALT, are less than the standard deduction for your filing status, the standard deduction is the better choice.' ],
            [ 'What if I live in a state with no income tax?', 'You can still deduct property tax plus state and local sales tax paid, up to the same $40,400 cap — the sales tax option exists specifically for residents of no-income-tax states.' ],
        ],
        'sources'   => [
            [ 'IRS: Topic no. 503, deductible taxes', 'https://www.irs.gov/taxtopics/tc503' ],
            [ 'IRS Revenue Procedure 2025-32 (2026 inflation adjustments)', 'https://www.irs.gov/pub/irs-drop/rp-25-32.pdf' ],
        ],
        'related'   => [ 'income-tax-calculator', 'property-tax-calculator', 'child-tax-credit-calculator', 'self-employment-tax-calculator' ],
    ],

    ];
}
