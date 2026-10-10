<?php
/**
 * Editorial content + keywords, batch 7: sales tax, federal estate tax, IRS penalty and interest, gas fee.
 * Example numbers match the calculators in calculators-money-3.php with the inputs stated.
 * 2026 federal estate tax basic exclusion: $15,000,000 per person; top rate 40%.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_tool_content_data_7() {
    return [

    'sales-tax-calculator' => [
        'focus'     => 'sales tax calculator',
        'secondary' => [ 'sales tax calculator by state', 'how to calculate sales tax', 'reverse sales tax calculator', 'price before tax calculator' ],
        'title'     => 'Sales Tax Calculator: Add or Remove Tax',
        'meta'      => 'Free sales tax calculator: add tax to a price or work backwards from a total. Enter state and local rates to see the tax, total and pre-tax price.',
        'excerpt'   => 'Add sales tax to a price, or find the pre-tax price from a total that already includes tax.',
        'answer'    => 'A $250 purchase with a 6.25% state rate and a 2% local rate has a combined rate of 8.25%. The sales tax is $20.63 and the total is $270.63. Working backwards, a $270.63 total at 8.25% means a $250.00 pre-tax price.',
        'steps'     => [ 'Choose whether to add tax to a price or remove it from a total.', 'Enter the amount and your state and local tax rates.', 'Enter the quantity if you are buying more than one item.', 'Read the total, pre-tax price, tax and combined rate.' ],
        'formula'   => '<p><strong>Total = Price × (1 + combined rate).</strong> To remove tax from a total, <strong>Price before tax = Total ÷ (1 + combined rate).</strong> The combined rate is the state rate plus any city or county rate.</p>',
        'example'   => '<p>$250 at 6.25% state + 2% local = 8.25%:</p><ul><li>Tax: 250 × 0.0825 = <strong>$20.63</strong> (rounded)</li><li>Total: <strong>$270.63</strong></li><li>Reverse: 270.63 ÷ 1.0825 = <strong>$250.00</strong></li></ul>',
        'sections'  => [
            [ 'Rates vary by place', '<p>Most U.S. states charge a state sales tax, and many cities and counties add their own. A handful of states have no state sales tax. Look up the exact combined rate for the address where the sale happens or where the goods are delivered.</p>' ],
            [ 'What is taxable', '<p>Rules differ by state. Groceries, prescription drugs and clothing are exempt or taxed at a lower rate in some states, while services are taxed in some places and not others. Check your state revenue department for the rules that apply to your purchase.</p>' ],
            [ 'Deducting sales tax', '<p>If you itemize on your federal return, you can deduct either state and local income tax or state and local sales tax, within the overall SALT limit. The IRS has a sales tax deduction calculator for this.</p>' ],
        ],
        'terms'     => [ [ 'Combined rate', 'State plus local sales tax rates added together.' ], [ 'Pre-tax price', 'The price before sales tax is added.' ], [ 'Use tax', 'Tax you owe on taxable items bought without sales tax, such as some online purchases.' ] ],
        'faqs'      => [
            [ 'How do you calculate sales tax?', 'Multiply the price by the combined tax rate as a decimal, then add the result to the price.' ],
            [ 'How do you find the price before tax?', 'Divide the total by one plus the tax rate. A total of $270.63 at 8.25% gives $250.00.' ],
            [ 'Do all states charge sales tax?', 'No. Several states, including Oregon, Montana, New Hampshire and Delaware, have no state sales tax, though some allow local taxes.' ],
            [ 'Is sales tax deductible?', 'It can be if you itemize and choose the sales tax deduction instead of state income tax, subject to the SALT limit.' ],
        ],
        'sources'   => [ [ 'IRS Topic 503: Deductible taxes', 'https://www.irs.gov/taxtopics/tc503' ], [ 'IRS: Sales tax deduction calculator', 'https://www.irs.gov/credits-deductions/individuals/sales-tax-deduction-calculator' ] ],
        'related'   => [ 'income-tax-calculator', 'property-tax-calculator', 'monthly-budget-planner', 'self-employment-tax-calculator' ],
    ],

    'estate-tax-calculator' => [
        'focus'     => 'estate tax calculator',
        'secondary' => [ 'federal estate tax calculator', 'estate tax exemption 2026', 'inheritance tax calculator', 'estate tax rate' ],
        'title'     => 'Estate Tax Calculator (2026 Federal Exemption)',
        'meta'      => 'Free federal estate tax calculator for 2026: enter your estate, debts, deductions and gifts to estimate tax above the $15 million exemption at 40%.',
        'excerpt'   => 'Estimate federal estate tax using the 2026 exemption of $15 million per person and the 40% rate.',
        'answer'    => 'For 2026 the federal estate tax exemption is $15,000,000 per person, and the rate on the excess is 40%. A $16,000,000 estate with $500,000 of debts and costs and no deductions has a $15,500,000 taxable estate, which is $500,000 over the exemption, so the estimated federal tax is $200,000. A married couple using portability can shield up to $30,000,000.',
        'steps'     => [ 'Enter the gross estate: everything you own at death.', 'Enter debts, funeral and administration costs, and any marital or charitable deductions.', 'Enter taxable gifts made during your life.', 'Choose one exemption or a married couple using portability, and read the estimated tax.' ],
        'formula'   => '<p><strong>Taxable estate = Gross estate − debts and costs − marital and charitable deductions.</strong></p><p><strong>Tax = 40% × (taxable estate + taxable lifetime gifts − exemption)</strong>, if that amount is above zero. The exemption is $15,000,000 per person for 2026 (up to $30,000,000 for a married couple using portability).</p>',
        'example'   => '<p>$16,000,000 gross estate, $500,000 debts and costs, no deductions, single exemption:</p><ul><li>Taxable estate: <strong>$15,500,000</strong></li><li>Over the $15,000,000 exemption by <strong>$500,000</strong></li><li>Estimated tax at 40%: <strong>$200,000</strong> (1.25% of the gross estate)</li></ul><p>With a $30,000,000 portability exemption the same estate owes <strong>$0</strong>.</p>',
        'sections'  => [
            [ 'Who actually owes it', '<p>Because the exemption is $15 million per person for 2026, only a small share of estates owe federal estate tax. The exemption is set to be adjusted for inflation each year. Some states have their own estate or inheritance taxes with much lower thresholds.</p>' ],
            [ 'Portability for married couples', '<p>When one spouse dies, the surviving spouse can generally claim the unused part of the deceased spouse’s exemption by filing an estate tax return on time, even if no tax is due. Property left to a U.S.-citizen spouse is generally deductible without limit.</p>' ],
            [ 'Gifts and the exemption', '<p>Gifts above the annual exclusion reduce the same lifetime exemption, so large lifetime gifts count toward the total. Keep records and talk with an estate attorney or tax professional for planning, because trusts, valuation and state rules can change the answer.</p>' ],
        ],
        'terms'     => [ [ 'Gross estate', 'The total value of what you own at death.' ], [ 'Basic exclusion amount', 'The amount you can pass on free of federal estate tax; $15,000,000 per person in 2026.' ], [ 'Portability', 'Letting a surviving spouse use a deceased spouse’s unused exemption.' ], [ 'Marital deduction', 'A deduction for property passing to a surviving U.S.-citizen spouse.' ] ],
        'faqs'      => [
            [ 'What is the federal estate tax exemption for 2026?', '$15,000,000 per person. Amounts above it are taxed at up to 40%.' ],
            [ 'What is the federal estate tax rate?', 'The top rate is 40%, and because the exemption covers the lower brackets, the excess over the exemption is effectively taxed at 40%.' ],
            [ 'Do I need to file an estate tax return if I owe nothing?', 'Sometimes. A married couple generally files one to elect portability, and estates over the filing threshold must file even if no tax is due.' ],
            [ 'Is there an inheritance tax too?', 'A few states charge an inheritance tax paid by heirs. The federal government does not. This calculator covers only the federal estate tax.' ],
        ],
        'sources'   => [ [ 'IRS: Estate tax', 'https://www.irs.gov/businesses/small-businesses-self-employed/estate-tax' ], [ 'IRS: What’s new, estate and gift tax', 'https://www.irs.gov/businesses/small-businesses-self-employed/whats-new-estate-and-gift-tax' ] ],
        'related'   => [ 'net-worth-calculator', 'retirement-savings-calculator', 'capital-gains-tax-calculator', 'income-tax-calculator' ],
    ],

    'irs-penalty-calculator' => [
        'focus'     => 'irs penalty calculator',
        'secondary' => [ 'irs late filing penalty calculator', 'failure to file penalty', 'failure to pay penalty', 'irs interest calculator' ],
        'title'     => 'IRS Penalty and Interest Calculator',
        'meta'      => 'Free IRS penalty calculator: estimate failure-to-file and failure-to-pay penalties plus interest on unpaid tax, with how the 5% and 0.5% monthly rates work.',
        'excerpt'   => 'Estimate the penalties and interest the IRS can charge when a tax return or payment is late.',
        'answer'    => 'On $5,000 of unpaid tax with a return filed 3 months late and the tax paid 3 months late, the estimated failure-to-file penalty is $675 (4.5% a month), the failure-to-pay penalty is $75 (0.5% a month) and interest at 7% is about $88. That is roughly $838 in penalties and interest, so the total owed is about $5,838.',
        'steps'     => [ 'Enter the unpaid tax shown on your return.', 'Enter how many months late the return was filed (0 if on time) and how many months late the tax was paid.', 'Enter the current IRS interest rate from irs.gov.', 'Read the penalties, interest and total owed.' ],
        'formula'   => '<p><strong>Failure to file:</strong> 5% of the unpaid tax for each month or part of a month the return is late, up to 5 months. In months when the failure-to-pay penalty also applies, the filing penalty is 4.5%.</p><p><strong>Failure to pay:</strong> 0.5% of the unpaid tax per month, up to 50 months (25%). <strong>Interest:</strong> the IRS rate, set quarterly, compounded daily on the unpaid tax.</p>',
        'example'   => '<p>$5,000 unpaid, 3 months late filing and paying, 7% interest:</p><ul><li>Failure to file: 3 × 4.5% = 13.5% → <strong>$675</strong></li><li>Failure to pay: 3 × 0.5% = 1.5% → <strong>$75</strong></li><li>Interest: about <strong>$88</strong></li><li>Total penalties and interest: about <strong>$838</strong>; total owed about <strong>$5,838</strong></li></ul>',
        'sections'  => [
            [ 'File on time even if you cannot pay', '<p>The failure-to-file penalty is ten times larger each month than the failure-to-pay penalty. Filing on time, or getting an extension, and paying what you can limits the damage. An extension to file is not an extension to pay.</p>' ],
            [ 'Ways to reduce penalties', '<p>The IRS offers first-time penalty abatement if you have a clean record for the previous three years, and relief for reasonable cause such as serious illness or a disaster. Payment plans can also lower the failure-to-pay rate to 0.25% a month while a plan is in effect.</p>' ],
            [ 'What this estimate leaves out', '<p>It assumes the tax stays unpaid for the whole period and does not include the minimum penalty for returns more than 60 days late, interest on penalties, or accuracy-related penalties. Your IRS notice shows the exact amount.</p>' ],
        ],
        'terms'     => [ [ 'Failure-to-file penalty', 'A penalty for not filing your return by the due date, including extensions.' ], [ 'Failure-to-pay penalty', 'A penalty for not paying the tax by the due date.' ], [ 'First-time abatement', 'IRS relief from certain penalties for taxpayers with a clean compliance history.' ] ],
        'faqs'      => [
            [ 'What is the IRS late filing penalty?', '5% of the unpaid tax for each month or part of a month the return is late, up to 25%, reduced by the failure-to-pay penalty in months when both apply.' ],
            [ 'What is the IRS late payment penalty?', '0.5% of the unpaid tax per month, up to 25% in total.' ],
            [ 'How is IRS interest calculated?', 'It is charged on unpaid tax at a rate set every quarter, equal to the federal short-term rate plus 3 percentage points, and compounds daily.' ],
            [ 'Can the IRS remove penalties?', 'Yes. First-time abatement and reasonable-cause relief are available. Contact the IRS or a tax professional.' ],
        ],
        'sources'   => [ [ 'IRS Topic 653: IRS notices and bills, penalties and interest', 'https://www.irs.gov/taxtopics/tc653' ], [ 'IRS: Interest', 'https://www.irs.gov/payments/interest' ] ],
        'related'   => [ 'income-tax-calculator', 'self-employment-tax-calculator', 'capital-gains-tax-calculator', 'loan-payoff-calculator' ],
    ],

    'gas-fee-calculator' => [
        'focus'     => 'gas fee calculator',
        'secondary' => [ 'ethereum gas fee calculator', 'eth gas calculator', 'gwei to usd', 'how are gas fees calculated' ],
        'title'     => 'Ethereum Gas Fee Calculator: Gwei to USD',
        'meta'      => 'Free Ethereum gas fee calculator: enter gas units, base fee, tip and ETH price to see a transaction’s cost in ETH, gwei and dollars.',
        'excerpt'   => 'Work out what an Ethereum transaction costs in gas, in ETH and in dollars.',
        'answer'    => 'A simple ETH transfer uses 21,000 gas units. At a base fee of 10 gwei plus a 1 gwei tip (11 gwei in total) the fee is 231,000 gwei, or 0.000231 ETH. At an ETH price of $2,500 that is about $0.58.',
        'steps'     => [ 'Pick the transaction type or enter the gas units from your wallet.', 'Enter the base fee and priority tip in gwei.', 'Enter the ETH price, or let it load the current one.', 'Read the fee in dollars, ETH and gwei.' ],
        'formula'   => '<p><strong>Fee (gwei) = gas units × (base fee + priority tip).</strong> <strong>Fee (ETH) = fee in gwei ÷ 1,000,000,000.</strong> <strong>Fee (USD) = fee in ETH × ETH price.</strong></p>',
        'example'   => '<p>ETH transfer, 21,000 gas, 10 gwei base + 1 gwei tip, ETH at $2,500:</p><ul><li>Fee: 21,000 × 11 = <strong>231,000 gwei</strong></li><li>In ETH: <strong>0.000231 ETH</strong></li><li>In dollars: about <strong>$0.58</strong></li></ul>',
        'sections'  => [
            [ 'What gas is', '<p>Gas measures the computing work a transaction needs. Simple transfers need little; swaps and minting need more because they run more code. You pay the gas used multiplied by the gas price.</p>' ],
            [ 'Base fee and tip', '<p>Since Ethereum’s London upgrade, each block has a base fee that is burned and adjusts with demand, and users add a priority tip for the validator. Gas prices are quoted in gwei, where 1 gwei is one-billionth of an ETH.</p>' ],
            [ 'Ways to pay less', '<p>Send transactions when the network is quiet, use a lower tip when you are not in a hurry, and consider layer-2 networks, where fees are usually a small fraction of mainnet costs. Gas amounts for swaps and mints vary by contract, so the presets are typical figures, not exact ones.</p>' ],
        ],
        'terms'     => [ [ 'Gwei', 'A unit equal to one-billionth of an ETH, used for gas prices.' ], [ 'Gas units', 'How much computing work a transaction uses.' ], [ 'Base fee', 'The per-unit fee set by the network and burned.' ], [ 'Priority tip', 'An extra per-unit fee paid to the validator.' ] ],
        'faqs'      => [
            [ 'How are Ethereum gas fees calculated?', 'Multiply the gas units the transaction uses by the gas price (base fee plus tip), then convert from gwei to ETH and to dollars.' ],
            [ 'How much gas does an ETH transfer use?', '21,000 gas units.' ],
            [ 'What is a gwei?', 'One-billionth of an ETH.' ],
            [ 'Why do gas fees change?', 'The base fee rises when blocks are full and falls when demand is low.' ],
        ],
        'sources'   => [ [ 'ethereum.org: Gas and fees', 'https://ethereum.org/en/developers/docs/gas/' ] ],
        'related'   => [ 'crypto-converter', 'crypto-pl-calculator', 'staking-rewards-calculator', 'mining-profitability-calculator' ],
    ],

    ];
}
