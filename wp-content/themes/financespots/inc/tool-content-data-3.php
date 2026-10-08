<?php
/**
 * Editorial content + keywords, batch 3: loan payoff, loan affordability, interest-only, balloon,
 * bridge, commercial loan, debt-to-income, self-employment tax, monthly budget planner, dividends.
 * Example numbers come from the matching calculator in calculators-money-2.php with default inputs.
 * 2026 figures: SSA wage base $184,500, IRS Rev. Proc. 2025-32.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_tool_content_data_3() {
    return [

    /* ───────────────────────── LOAN PAYOFF ───────────────────────── */
    'loan-payoff-calculator' => [
        'focus'     => 'loan payoff calculator',
        'secondary' => [ 'extra payment calculator', 'pay off loan early calculator', 'how much interest will I save by paying extra', 'mortgage payoff calculator', 'loan payoff date' ],
        'title'     => 'Loan Payoff Calculator: Extra Payments',
        'meta'      => 'Free loan payoff calculator: add extra monthly payments or a lump sum and see your new payoff date, interest saved and time saved, with a full schedule.',
        'excerpt'   => 'See how extra monthly payments or a lump sum shorten your loan, with the new payoff date, interest saved and a full schedule.',
        'answer'    => 'Adding $200 a month to a $185,000 loan at 6.75% with 25 years left pays it off in 18 years 2 months instead of 25, saving about $62,650 in interest. Every dollar of extra payment goes straight to principal, so it stops charging interest for the rest of the loan.',
        'steps'     => [
            'Enter your current balance, interest rate and the time left on the loan.',
            'Enter the extra amount you can pay each month.',
            'Optionally add a one-time lump sum, such as a bonus or tax refund, applied now.',
            'Read the new payoff date, the time and interest saved, and the return on your extra money.',
            'Check the chart and schedule, then tell your lender to apply extra money to principal.',
        ],
        'formula'   => '<p>The regular payment is <strong>P × r ÷ (1 − (1 + r)<sup>−n</sup>)</strong>. Each month interest is the balance times the monthly rate, and everything paid above that reduces principal: <strong>new balance = balance − (payment − interest + extra)</strong>.</p><p>The calculator repeats this month by month until the balance reaches zero, with and without your extra payments, and compares the two schedules.</p>',
        'example'   => '<p>$185,000 balance, 6.75% APR, 25 years left, regular payment $1,278.19:</p><ul><li>With <strong>$200 extra a month</strong>: paid off Dec 2044 instead of Oct 2051</li><li>Time saved: <strong>6 years 10 months</strong></li><li>Interest: $135,806 instead of $198,456, saving <strong>$62,650</strong></li><li>You add about $43,600 of extra payments over the shorter loan, so each extra dollar returns about $1.44 in interest saved</li></ul>',
        'sections'  => [
            [ 'Why extra payments save so much', '<p>Interest accrues on your remaining balance. An extra $200 today removes $200 of balance, so you stop paying interest on it for every remaining month, and that saving compounds. Extra payments made early in the loan matter most, because the balance and the interest are highest then. A lump sum in year one can save more than the same amount paid ten years later.</p>' ],
            [ 'Check for prepayment penalties first', '<p>Most mortgages, auto loans and student loans allow extra payments without a penalty, but some loans charge a prepayment fee, especially certain personal, business and older mortgage loans. Read your loan agreement or ask your servicer. Also confirm how the servicer applies extra money: it should be applied to principal, not held toward next month’s payment.</p>' ],
            [ 'Should you pay extra or invest?', '<p>Paying extra on a loan earns a guaranteed return equal to the loan rate, here 6.75%. Investing might earn more but with risk. A sensible order is: capture any employer retirement match, build an emergency fund, pay off high-interest debt (credit cards), then decide between extra loan payments and investing based on the loan’s rate and your comfort with risk.</p>' ],
            [ 'Other ways to pay a loan off faster', '<ul><li><strong>Biweekly payments:</strong> half the payment every two weeks adds up to 13 full payments a year instead of 12.</li><li><strong>Round up:</strong> pay $1,300 instead of $1,278.</li><li><strong>Apply windfalls:</strong> bonuses, refunds and gifts.</li><li><strong>Refinance to a shorter term</strong> if you can get a lower rate; compare with our refinance calculator.</li></ul>' ],
        ],
        'terms'     => [
            [ 'Principal', 'The amount you owe, not counting future interest.' ],
            [ 'Prepayment penalty', 'A fee some lenders charge for paying a loan off early.' ],
            [ 'Amortization', 'Gradual repayment of a loan through scheduled payments.' ],
            [ 'Recast', 'Re-calculating your payment after a large principal payment, without changing the rate.' ],
        ],
        'faqs'      => [
            [ 'How do I calculate my loan payoff date?', 'Take your balance, rate and payment, and step forward month by month: interest is balance times the monthly rate, the rest of the payment reduces principal. The date the balance reaches zero is your payoff date. The calculator does this with and without extra payments.' ],
            [ 'How much interest will I save by paying an extra $200 a month?', 'On a $185,000 loan at 6.75% with 25 years left, about $62,650, and you finish 6 years 10 months early.' ],
            [ 'Is it better to make one lump-sum payment or small extra payments?', 'The earlier the money reaches the principal, the more interest you avoid. A lump sum now saves more than the same total spread over years, but regular extra payments are easier to sustain.' ],
            [ 'Will extra payments lower my monthly payment?', 'Not unless you recast or refinance. On most loans the payment stays the same and the loan simply ends sooner.' ],
            [ 'Do extra payments go to principal automatically?', 'Not always. Tell your servicer in writing, or use the extra-principal option when you pay, so it is not applied to future interest or held for next month.' ],
            [ 'Should I pay off my mortgage early?', 'It depends on your rate, other debts, emergency savings and investment options. A guaranteed saving at your loan rate is attractive when the rate is high; with a very low rate, investing may earn more.' ],
        ],
        'sources'   => [
            [ 'CFPB: Owning a home, mortgage tools and guides', 'https://www.consumerfinance.gov/owning-a-home/' ],
        ],
        'related'   => [ 'amortization-calculator', 'refinance-calculator', 'mortgage-calculator', 'debt-consolidation-calculator' ],
    ],

    /* ───────────────────────── LOAN AFFORDABILITY ───────────────────────── */
    'loan-affordability-calculator' => [
        'focus'     => 'loan affordability calculator',
        'secondary' => [ 'how much loan can I afford', 'how much can I borrow', 'debt-to-income loan calculator', 'maximum loan amount calculator', 'how much house can I afford' ],
        'title'     => 'Loan Affordability Calculator by DTI',
        'meta'      => 'Free loan affordability calculator: find the most you can borrow from your income, debts and a DTI limit, including mortgage tax and insurance.',
        'excerpt'   => 'Find the largest loan you can afford from your income, existing debts and a debt-to-income limit.',
        'answer'    => 'With $7,500 of gross monthly income, $600 of existing debt payments and a 36% debt-to-income limit, you can add up to $2,100 a month in new payments. At 7% over 30 years that supports a loan of about $315,646. At a 43% limit it rises to about $394,557, but stretching leaves less room for emergencies.',
        'steps'     => [
            'Enter your gross (before-tax) monthly income.',
            'Enter your current monthly debt payments, not including the new loan.',
            'Choose the debt-to-income ratio you want to stay under: 36% is a common guide, 43% is a typical lender limit.',
            'Enter the interest rate and term. For a mortgage, add monthly property tax and insurance and any down payment.',
            'Read the most you can borrow, the maximum payment and the price you can afford.',
        ],
        'formula'   => '<p><strong>Total debt payments allowed = gross monthly income × DTI limit</strong></p><p><strong>Room for the new payment = allowed − existing debts (− tax and insurance for a mortgage)</strong></p><p><strong>Maximum loan = payment × (1 − (1 + r)<sup>−n</sup>) ÷ r</strong>, with r the monthly rate and n the number of payments. <strong>Affordable price = maximum loan + down payment.</strong></p>',
        'example'   => '<p>$7,500 gross monthly income, $600 existing debt, 7% rate, 30 years:</p><table><thead><tr><th>DTI limit</th><th>Total debt allowed</th><th>New payment</th><th>Loan supported</th></tr></thead><tbody><tr><td>28%</td><td>$2,100</td><td>$1,500</td><td>$225,461</td></tr><tr><td>36%</td><td>$2,700</td><td>$2,100</td><td><strong>$315,646</strong></td></tr><tr><td>43%</td><td>$3,225</td><td>$2,625</td><td>$394,557</td></tr></tbody></table><p>The calculator’s “comfortable payment” of 28% of income is $1,500 after existing debts and any tax and insurance.</p>',
        'sections'  => [
            [ 'What lenders look at', '<p>Lenders compare your monthly debt payments with your gross monthly income, your debt-to-income ratio. Many prefer a back-end ratio (all debts including the new loan) at or below 36%, will approve up to about 43% for many loans, and some programs allow up to 50% with strong credit or savings. They also check credit score, employment history, savings and, for mortgages, the value of the property.</p>' ],
            [ 'Affordable is not the same as approved', '<p>A lender may approve more than you can comfortably repay. Your own budget includes costs the DTI ratio ignores: taxes, groceries, childcare, insurance, savings. Use the 28% figure as a comfortable payment and the 36% or 43% figure as a ceiling.</p>' ],
            [ 'How to borrow more safely', '<ul><li>Pay down credit card balances and car loans to free room in your DTI.</li><li>Make a larger down payment to reduce the loan and the payment.</li><li>Improve your credit score to qualify for a lower rate, which raises the loan a given payment supports.</li><li>Choose a longer term only if you can handle the extra interest.</li></ul>' ],
            [ 'Buying a home: include taxes and insurance', '<p>For a mortgage, property tax and homeowners insurance count toward your housing payment. Enter them so the calculator subtracts them before sizing the loan. Our mortgage calculator then shows the full payment, including PMI.</p>' ],
        ],
        'terms'     => [
            [ 'DTI', 'Debt-to-income ratio: monthly debt payments divided by gross monthly income.' ],
            [ 'Front-end ratio', 'Housing costs divided by gross income.' ],
            [ 'Back-end ratio', 'All monthly debts divided by gross income.' ],
            [ 'Gross income', 'Income before taxes and deductions.' ],
        ],
        'faqs'      => [
            [ 'How much loan can I afford?', 'Multiply your gross monthly income by your DTI limit (for example 36%), subtract existing debt payments, and find the loan that payment supports at your rate and term. With $7,500 income and $600 of debt at 36%, that is about $315,646 over 30 years at 7%.' ],
            [ 'What debt-to-income ratio do lenders want?', 'Many prefer 36% or lower, accept up to about 43% for many loans, and some programs allow up to 50%. FHA guidelines use 31% for housing and 43% for total debt as a baseline.' ],
            [ 'Is it safe to borrow the maximum?', 'Usually not. A smaller payment, such as 28% of income for housing, leaves room for savings and emergencies.' ],
            [ 'Does a longer term let me borrow more?', 'Yes, because the payment is spread out, but you pay much more interest in total. The calculator shows the total interest.' ],
            [ 'How can I qualify for a bigger loan?', 'Lower existing debts, raise income, increase your down payment or improve your credit for a lower rate.' ],
            [ 'Does this work for car and personal loans?', 'Yes. Leave tax and insurance at 0 and choose the loan term, such as 5 years for a car.' ],
        ],
        'sources'   => [
            [ 'CFPB: What is a debt-to-income ratio?', 'https://www.consumerfinance.gov/ask-cfpb/what-is-a-debt-to-income-ratio-why-is-the-43-debt-to-income-ratio-important-en-1791/' ],
            [ 'CFPB: Ability-to-repay and qualified mortgage rule', 'https://www.consumerfinance.gov/rules-policy/regulations/1026/43/' ],
        ],
        'related'   => [ 'debt-to-income-ratio', 'mortgage-calculator', 'auto-loan-calculator', 'personal-loan-calculator' ],
    ],

    /* ───────────────────────── INTEREST ONLY ───────────────────────── */
    'interest-only-calculator' => [
        'focus'     => 'interest-only loan calculator',
        'secondary' => [ 'interest only mortgage calculator', 'interest only payment calculator', 'interest only vs principal and interest', 'interest only period', 'interest-only loan risks' ],
        'title'     => 'Interest-Only Loan Calculator & Payment Jump',
        'meta'      => 'Free interest-only loan calculator: see the payment now, the higher payment after the period ends, total interest and the extra cost vs a standard loan.',
        'excerpt'   => 'Compare an interest-only loan with a standard loan: the low early payment, the jump when the period ends and the extra interest you pay.',
        'answer'    => 'A $300,000 loan at 7% with 10 interest-only years costs $1,750 a month at first. Then the payment jumps to $2,325.90 (33% higher) because the full balance must be repaid in 20 years. Total interest is $468,215, about $49,689 more than the $418,527 on a standard 30-year loan.',
        'steps'     => [
            'Enter the loan amount and interest rate.',
            'Enter how many years are interest-only and the total loan term.',
            'Read the payment during the interest-only period and the higher payment after it.',
            'Compare total interest with a standard fully amortizing loan.',
            'Check the schedule to see the balance stay flat, then fall.',
        ],
        'formula'   => '<p><strong>Interest-only payment = loan × annual rate ÷ 12.</strong> The balance does not fall during this period.</p><p><strong>After the interest-only period</strong> the loan is re-amortized over the remaining years: <strong>payment = P × r ÷ (1 − (1 + r)<sup>−m</sup>)</strong>, with m the remaining months. A standard loan uses all months from the start.</p>',
        'example'   => '<p>$300,000 at 7%, 30-year term with 10 interest-only years:</p><ul><li>Payment for the first 10 years: <strong>$1,750.00</strong>; principal paid: $0</li><li>Payment from year 11: <strong>$2,325.90</strong> (+$575.90, 33% higher)</li><li>Standard 30-year loan: <strong>$1,995.91</strong> from day one</li><li>Total interest: <strong>$468,215</strong> vs <strong>$418,527</strong>, so <strong>$49,689 more</strong></li></ul>',
        'sections'  => [
            [ 'How interest-only loans work', '<p>During the interest-only period you pay only the interest, so the balance stays the same and you build no equity from payments. When the period ends, the loan either converts to a fully amortizing payment over the remaining term, or a balloon payment is due. Because the same principal must be repaid in fewer years, the payment rises sharply, an effect called payment shock.</p>' ],
            [ 'Who uses interest-only loans', '<p>Interest-only loans can suit investors who plan to sell or refinance before the period ends, borrowers with irregular or rising income, and people who want to invest the payment difference. They are risky if home prices fall, rates rise, or income does not grow, because you may owe more than the property is worth or be unable to refinance.</p>' ],
            [ 'Costs and risks', '<ul><li>You pay more interest over the loan’s life.</li><li>No principal is repaid, so equity grows only if the property rises in value.</li><li>The payment can jump by 30% or more.</li><li>Many interest-only loans have adjustable rates, which can raise payments further.</li><li>Interest-only loans do not qualify as “qualified mortgages” under federal ability-to-repay rules, so terms and availability vary.</li></ul>' ],
            [ 'Options', '<p>You can make principal payments voluntarily during the interest-only period to reduce the later jump. Compare the result with a standard loan using our mortgage and amortization calculators.</p>' ],
        ],
        'terms'     => [
            [ 'Interest-only period', 'A stretch when you pay only interest and no principal.' ],
            [ 'Payment shock', 'A large rise in the monthly payment when the interest-only period ends.' ],
            [ 'Re-amortization', 'Recalculating the payment to repay the balance over the remaining term.' ],
            [ 'Equity', 'Property value minus the loan balance.' ],
        ],
        'faqs'      => [
            [ 'How is an interest-only payment calculated?', 'Multiply the loan balance by the annual interest rate and divide by 12. On $300,000 at 7% it is $1,750 a month.' ],
            [ 'What happens when the interest-only period ends?', 'The payment rises because the whole balance must be repaid over the remaining years, or a balloon payment comes due. In the example, the payment goes from $1,750 to $2,325.90.' ],
            [ 'Is an interest-only loan cheaper?', 'Monthly, yes at first. Overall it costs more: about $49,689 more interest in the example than a standard 30-year loan.' ],
            [ 'Do I build equity with an interest-only loan?', 'Not from payments during the interest-only period. Equity grows only if the property value rises or if you pay extra principal.' ],
            [ 'Are interest-only loans risky?', 'They can be, because of payment shock, rising rates and the chance of owing more than the property is worth. Make sure you can afford the higher payment.' ],
            [ 'Can I pay principal during the interest-only period?', 'Many lenders allow extra principal payments. Check your loan terms.' ],
        ],
        'sources'   => [
            [ 'CFPB: Ability-to-repay and qualified mortgage rule (12 CFR 1026.43)', 'https://www.consumerfinance.gov/rules-policy/regulations/1026/43/' ],
            [ 'CFPB: Owning a home', 'https://www.consumerfinance.gov/owning-a-home/' ],
        ],
        'related'   => [ 'mortgage-calculator', 'amortization-calculator', 'balloon-loan-calculator', 'loan-comparison-calculator' ],
    ],

    /* ───────────────────────── BALLOON ───────────────────────── */
    'balloon-loan-calculator' => [
        'focus'     => 'balloon loan calculator',
        'secondary' => [ 'balloon payment calculator', 'balloon mortgage calculator', 'what is a balloon payment', 'balloon loan vs fixed rate', 'balloon payment refinance' ],
        'title'     => 'Balloon Loan Calculator: Payment & Balloon',
        'meta'      => 'Free balloon loan calculator: enter loan, rate, amortization and balloon term to see the monthly payment, balloon due and interest paid before it.',
        'excerpt'   => 'See your monthly payment and the large balloon payment due at the end of a balloon loan, with a full schedule.',
        'answer'    => 'A $300,000 balloon loan at 6.5%, with payments sized for 30 years but due in 7, costs $1,896.20 a month. At the end of year 7 you still owe a balloon payment of about $271,249, or 90.4% of the original loan. You must pay it in cash, sell, or refinance.',
        'steps'     => [
            'Enter the loan amount and interest rate.',
            'Enter the amortization period: the number of years the payment is calculated over.',
            'Enter when the balloon comes due, which must be earlier than the amortization period.',
            'Read the monthly payment, the balloon amount and the date it is due.',
            'Review the schedule and plan how you will pay or refinance the balloon.',
        ],
        'formula'   => '<p><strong>Monthly payment = P × r ÷ (1 − (1 + r)<sup>−n</sup>)</strong> using the full amortization period (for example 360 months).</p><p>The calculator then runs the schedule for only the balloon term (for example 84 months). <strong>Balloon payment = the balance left at that point.</strong> <strong>Total paid before the balloon = payment × months.</strong></p>',
        'example'   => '<p>$300,000 at 6.5%, amortized over 30 years, balloon due after 7 years:</p><ul><li>Monthly payment: <strong>$1,896.20</strong></li><li>Total paid before the balloon: <strong>$159,281</strong>, of which $130,530 is interest</li><li>Balloon payment due: <strong>$271,249</strong> (90.4% of the loan)</li><li>A fully amortizing 7-year loan would cost $4,454.83 a month</li></ul>',
        'sections'  => [
            [ 'How a balloon loan works', '<p>A balloon loan keeps monthly payments low by sizing them as if the loan lasted much longer than it really does. When the shorter term ends, the remaining balance, the balloon, is due in one lump sum. Because early payments are mostly interest, the balloon is usually a large share of the original loan.</p>' ],
            [ 'Where balloon loans are used', '<p>Balloon structures are common in commercial real estate, seller financing and some short-term business loans. They are less common for home loans, since many federal consumer mortgage rules restrict them. They suit borrowers who expect to sell, refinance or receive a large sum before the balloon date.</p>' ],
            [ 'The main risk: refinancing', '<p>If you plan to refinance the balloon, you need to qualify and rates need to cooperate. If rates have risen, your income has dropped or the property has lost value, refinancing may be hard or costly. Without a way to pay, you could lose the property. Build a plan and a cash reserve before choosing a balloon loan.</p>' ],
            [ 'Balloon vs a fully amortizing loan', '<p>A fully amortizing loan has higher payments but no lump sum. Compare the monthly savings with the balloon risk: here the payment is $1,896.20 instead of what a normal 7-year loan would require, but you still owe $271,249 at the end.</p>' ],
        ],
        'terms'     => [
            [ 'Balloon payment', 'A large final payment that repays the remaining balance.' ],
            [ 'Amortization period', 'The length of time used to calculate the monthly payment.' ],
            [ 'Loan term', 'How long until the loan comes due.' ],
            [ 'Refinance risk', 'The chance you cannot replace the loan when the balloon is due.' ],
        ],
        'faqs'      => [
            [ 'What is a balloon payment?', 'A large lump-sum payment due at the end of a loan, after smaller regular payments that did not fully repay it.' ],
            [ 'How do you calculate a balloon payment?', 'Calculate the regular payment from the full amortization period, then find the balance remaining after the shorter loan term. In the example, it is $271,249 after 7 years.' ],
            [ 'What happens if I cannot pay the balloon?', 'You can refinance, sell the asset, or negotiate an extension. If none work, you risk default and, for secured loans, losing the asset.' ],
            [ 'Are balloon loans a good idea?', 'Only if you have a realistic plan to pay or refinance. They can lower payments but carry significant risk.' ],
            [ 'Why is the balloon so large?', 'Early in a long amortization most of each payment is interest, so little principal is repaid. After 7 years of a 30-year schedule you have repaid less than 10% of the loan.' ],
            [ 'Can I make extra payments on a balloon loan?', 'Usually yes, and extra principal reduces the balloon. Check for prepayment penalties.' ],
        ],
        'sources'   => [
            [ 'CFPB: Ability-to-repay and qualified mortgage rule (12 CFR 1026.43)', 'https://www.consumerfinance.gov/rules-policy/regulations/1026/43/' ],
        ],
        'related'   => [ 'amortization-calculator', 'commercial-loan-calculator', 'refinance-calculator', 'interest-only-calculator' ],
    ],

    /* ───────────────────────── BRIDGE ───────────────────────── */
    'bridge-loan-calculator' => [
        'focus'     => 'bridge loan calculator',
        'secondary' => [ 'bridge loan cost', 'bridge loan interest calculator', 'what is a bridge loan', 'bridge loan fees', 'bridge loan vs HELOC' ],
        'title'     => 'Bridge Loan Calculator: True Cost & APR',
        'meta'      => 'Free bridge loan calculator: enter amount, rate, term and fees to see total cost, monthly interest and the effective APR of a short-term loan.',
        'excerpt'   => 'See the real cost of a short-term bridge loan, including origination and exit fees, as a total and as an effective APR.',
        'answer'    => 'A $250,000 bridge loan for 6 months at 10% with a 2% origination fee, 1% exit fee and $2,500 of other costs costs $22,500 in total ($12,500 interest plus $10,000 fees). Because fees are charged on a short loan, the effective APR is about 18.25%, well above the 10% stated rate.',
        'steps'     => [
            'Enter the bridge loan amount, the interest rate and how many months you need it.',
            'Choose whether interest is paid monthly or accrued and paid at the end.',
            'Enter the origination fee, exit fee and any appraisal, legal or other costs.',
            'Read the total cost, effective APR, cash received and cost per month.',
            'Compare the cost with the benefit of buying before you sell.',
        ],
        'formula'   => '<p><strong>Interest = loan × annual rate ÷ 12 × months.</strong> <strong>Fees = loan × origination % + loan × exit % + other costs.</strong></p><p><strong>Total cost = interest + fees.</strong> <strong>Cash received = loan − origination fee − other costs.</strong></p><p>The <strong>effective APR</strong> is the rate that makes your payments (interest each month, then the principal and exit fee at the end) equal the cash you actually received.</p>',
        'example'   => '<p>$250,000 for 6 months at 10%, 2% origination, 1% exit, $2,500 other costs, interest paid monthly:</p><ul><li>Interest: <strong>$12,500</strong> ($2,083.33 a month)</li><li>Fees: $5,000 origination + $2,500 exit + $2,500 other = <strong>$10,000</strong></li><li>Total cost: <strong>$22,500</strong>, or $3,750 for each month of the loan</li><li>Cash you receive: <strong>$242,500</strong>; you repay $252,500 at the end</li><li>Effective APR: about <strong>18.25%</strong></li></ul>',
        'sections'  => [
            [ 'What a bridge loan is for', '<p>A bridge loan is short-term financing, usually 3 to 24 months, that “bridges” a gap, most often letting you buy a new home before the old one sells, or funding a property purchase until permanent financing is in place. It is secured by property and repaid when the home sells or the longer-term loan closes.</p>' ],
            [ 'Why bridge loans cost so much', '<p>Lenders charge higher rates and fees because the loan is short and carries timing risk. Fees are spread over only a few months, so the effective APR is much higher than the note rate. Compare the total dollar cost, not just the interest rate.</p>' ],
            [ 'Alternatives to consider', '<ul><li><strong>HELOC:</strong> borrow against your current home’s equity before you list it.</li><li><strong>Home equity loan</strong> for a down payment.</li><li><strong>Contingent offer:</strong> make your purchase conditional on selling your home.</li><li><strong>Sale-leaseback or longer closing:</strong> negotiate timing so you do not need a bridge.</li></ul>' ],
            [ 'Risks', '<p>If the old property does not sell as planned, you may carry two housing payments plus the bridge loan. Have a clear exit and a cushion, and make sure the loan term has room for delays.</p>' ],
        ],
        'terms'     => [
            [ 'Bridge loan', 'A short-term loan used until permanent financing or a sale.' ],
            [ 'Exit fee', 'A fee charged when the loan is repaid.' ],
            [ 'Origination fee', 'A fee charged for making the loan.' ],
            [ 'Effective APR', 'The yearly rate including all fees, on the cash actually received.' ],
        ],
        'faqs'      => [
            [ 'How much does a bridge loan cost?', 'In the example, $22,500 on a $250,000 loan over 6 months: $12,500 of interest and $10,000 of fees. Actual costs depend on the lender, rate, fees and term.' ],
            [ 'What is the APR on a bridge loan?', 'Including fees, the effective APR in the example is about 18.25%, far above the 10% note rate, because the fees are charged on a short loan.' ],
            [ 'How long can I keep a bridge loan?', 'Most last 3 to 24 months. Check extension fees if the sale takes longer.' ],
            [ 'Is a bridge loan a good idea?', 'Only when you have a firm exit and the benefit outweighs the cost. Often a HELOC or contingent offer is cheaper.' ],
            [ 'Do I pay interest monthly?', 'It depends on the lender: some require monthly interest-only payments, others accrue interest and collect it at payoff.' ],
            [ 'Can I get a bridge loan with a mortgage still on my old home?', 'Often yes. Lenders look at the equity in your current home and your ability to carry the payments.' ],
        ],
        'sources'   => [
            [ 'CFPB: Owning a home, tools and resources', 'https://www.consumerfinance.gov/owning-a-home/' ],
        ],
        'related'   => [ 'home-equity-loan-calculator', 'mortgage-calculator', 'loan-comparison-calculator', 'refinance-calculator' ],
    ],

    /* ───────────────────────── COMMERCIAL ───────────────────────── */
    'commercial-loan-calculator' => [
        'focus'     => 'commercial loan calculator',
        'secondary' => [ 'commercial mortgage calculator', 'DSCR calculator', 'debt service coverage ratio calculator', 'commercial real estate loan calculator', 'commercial loan balloon' ],
        'title'     => 'Commercial Loan Calculator with DSCR',
        'meta'      => 'Free commercial loan calculator: monthly payment, balloon balance, LTV and debt service coverage ratio (DSCR), plus the largest loan your income supports.',
        'excerpt'   => 'Calculate a commercial real estate loan payment, balloon balance, loan-to-value and DSCR, and the largest loan your income can support.',
        'answer'    => 'A $1,000,000 commercial loan at 7.25%, amortized over 25 years with a 10-year term, costs $7,228.07 a month. With $130,000 of annual net operating income, the DSCR is 1.50x, above the typical 1.25x lender minimum. After 10 years, a balloon of about $791,802 is due.',
        'steps'     => [
            'Enter the property value and the loan amount.',
            'Enter the interest rate, the amortization period and the loan term (balloon date).',
            'Enter the property’s annual net operating income (NOI).',
            'Enter the DSCR your lender requires. 1.25 is common.',
            'Read the payment, DSCR, LTV, balloon balance and the largest loan your income supports.',
        ],
        'formula'   => '<p><strong>Monthly payment = P × r ÷ (1 − (1 + r)<sup>−n</sup>)</strong> over the amortization period. <strong>Annual debt service = payment × 12.</strong></p><p><strong>DSCR = net operating income ÷ annual debt service.</strong> <strong>LTV = loan ÷ property value.</strong></p><p><strong>Largest loan at your required DSCR</strong> = the loan whose annual payment equals NOI ÷ required DSCR. <strong>Balloon = balance left at the end of the loan term.</strong></p>',
        'example'   => '<p>$1,500,000 property, $1,000,000 loan, 7.25%, 25-year amortization, 10-year term, $130,000 NOI, lender requires 1.25x:</p><ul><li>Monthly payment: <strong>$7,228.07</strong> (annual debt service $86,737)</li><li>DSCR: <strong>1.50x</strong>, which meets the 1.25x requirement</li><li>LTV: <strong>66.7%</strong></li><li>Balloon after 10 years: <strong>$791,802</strong></li><li>Largest loan at a 1.25x DSCR: about <strong>$1,199,029</strong></li></ul>',
        'sections'  => [
            [ 'What lenders want to see', '<p>Commercial lenders underwrite the property’s income as much as the borrower. The two key measures are DSCR, which shows whether the property earns enough to pay its debt, and LTV, which shows how much of the value is borrowed. Typical requirements are a DSCR of at least 1.20 to 1.25 and an LTV of 65% to 80%, depending on property type.</p>' ],
            [ 'Amortization, term and balloon', '<p>Most commercial loans have a shorter term than their amortization schedule: payments might be sized over 25 years but the loan comes due in 5, 7 or 10 years. At that date a balloon balance must be refinanced or repaid. The calculator shows the balloon so you can plan your exit.</p>' ],
            [ 'How to improve your DSCR', '<ul><li>Raise net operating income: increase rents, reduce vacancy, trim operating costs.</li><li>Borrow less or put more down.</li><li>Lengthen the amortization period to lower the payment.</li><li>Shop for a lower rate or fixed-rate structure.</li></ul>' ],
            [ 'SBA and other programs', '<p>Owner-occupied commercial property may qualify for SBA 504 loans, which allow higher loan-to-value, and SBA 7(a) loans for working capital and real estate. Check current terms with the Small Business Administration and an approved lender.</p>' ],
        ],
        'terms'     => [
            [ 'NOI', 'Net operating income: income minus operating expenses, before debt payments and taxes.' ],
            [ 'DSCR', 'Debt service coverage ratio: NOI divided by annual debt payments.' ],
            [ 'LTV', 'Loan-to-value: loan divided by property value.' ],
            [ 'Balloon', 'The remaining balance due at the end of the loan term.' ],
        ],
        'faqs'      => [
            [ 'What is DSCR?', 'Debt service coverage ratio is the property’s net operating income divided by its annual loan payments. A DSCR of 1.50 means it earns 1.5 times what it owes.' ],
            [ 'What DSCR do lenders require for a commercial loan?', 'Commonly 1.20 to 1.25 or higher, depending on the lender and property type.' ],
            [ 'How is a commercial loan payment calculated?', 'Use the amortization formula with the loan amount, interest rate and the amortization period. On $1,000,000 at 7.25% over 25 years it is $7,228.07 a month.' ],
            [ 'What is a typical commercial loan term?', 'Often 5 to 10 years, with payments based on a 20 to 30 year amortization and a balloon at the end.' ],
            [ 'What LTV can I get on commercial real estate?', 'Usually 65% to 80%. SBA programs can go higher for owner-occupied properties.' ],
            [ 'How much commercial loan can I afford?', 'Divide NOI by the required DSCR to get the maximum annual payment, then find the loan that payment supports. The calculator does this for you.' ],
        ],
        'sources'   => [
            [ 'SBA: 504 loans', 'https://www.sba.gov/funding-programs/loans/504-loans' ],
            [ 'SBA: 7(a) loans', 'https://www.sba.gov/funding-programs/loans/7a-loans' ],
        ],
        'related'   => [ 'balloon-loan-calculator', 'amortization-calculator', 'loan-comparison-calculator', 'refinance-calculator' ],
    ],

    /* ───────────────────────── DTI ───────────────────────── */
    'debt-to-income-ratio' => [
        'focus'     => 'debt-to-income ratio calculator',
        'secondary' => [ 'DTI calculator', 'how to calculate debt to income ratio', 'what is a good debt to income ratio', 'front-end and back-end DTI', 'DTI for mortgage' ],
        'title'     => 'Debt-to-Income Ratio Calculator (DTI)',
        'meta'      => 'Free debt-to-income ratio calculator: get your front-end and back-end DTI, how lenders see it, and how much more debt you can take on at 36% and 43%.',
        'excerpt'   => 'Calculate your front-end and back-end debt-to-income ratio, see how lenders view it, and find how much room you have for new debt.',
        'answer'    => 'Debt-to-income ratio (DTI) is your monthly debt payments divided by your gross monthly income. With $7,000 of income and $2,750 of debt payments, your back-end DTI is 39.3% and your housing-only (front-end) ratio is 25.7%. Many lenders prefer 36% or lower and accept up to about 43% for many loans.',
        'steps'     => [
            'Enter your gross monthly income, before taxes.',
            'Enter your monthly housing payment: rent, or mortgage with taxes, insurance and HOA.',
            'Enter your monthly payments for car loans, student loans, credit card minimums and other debts.',
            'Read your back-end and front-end DTI, how lenders rate it, and your room for new debt at 36% and 43%.',
        ],
        'formula'   => '<p><strong>Back-end DTI = (housing + all other monthly debt payments) ÷ gross monthly income × 100</strong></p><p><strong>Front-end DTI = housing payment ÷ gross monthly income × 100</strong></p><p><strong>Room for new debt at a limit = income × limit − current debt payments.</strong> Do not include groceries, utilities or insurance (other than what is in your housing payment); only recurring debt payments count.</p>',
        'example'   => '<p>$7,000 gross monthly income. Housing $1,800, car $400, student loans $300, credit cards $150, other $100:</p><ul><li>Total monthly debt: <strong>$2,750</strong></li><li>Back-end DTI: <strong>39.3%</strong></li><li>Front-end DTI: <strong>25.7%</strong></li><li>Room for new debt at 36%: <strong>$0</strong> (you are already above it)</li><li>Room at 43%: <strong>$260 a month</strong></li></ul>',
        'sections'  => [
            [ 'What is a good DTI?', '<p>As a rule of thumb: <strong>36% or lower</strong> is considered healthy, <strong>37% to 43%</strong> is acceptable for many loans, <strong>44% to 50%</strong> is high and may need compensating factors like strong credit or large savings, and above 50% usually makes approval hard. Housing-only (front-end) ratios around 28% are a common target, and FHA guidelines use 31% for housing and 43% for total debt as a baseline.</p>' ],
            [ 'Front-end vs back-end', '<p>The front-end ratio looks at housing costs alone: mortgage principal, interest, property tax, insurance and HOA dues, or rent. The back-end ratio adds every other recurring debt. Mortgage lenders look at both, with the back-end ratio usually the deciding one.</p>' ],
            [ 'How to lower your DTI', '<ul><li>Pay down credit cards and loans with the highest payment relative to balance.</li><li>Avoid taking on new debt before applying for a loan.</li><li>Increase income: a raise, a second job or documented side income.</li><li>Refinance or extend a loan to lower the monthly payment, if the total cost makes sense.</li></ul>' ],
            [ 'What DTI does not include', '<p>DTI counts debt payments only. It does not include living costs like groceries, utilities or insurance, and it uses gross, not take-home, income. A DTI that looks acceptable may still feel tight on your actual budget, so check it against your own spending.</p>' ],
        ],
        'terms'     => [
            [ 'DTI', 'Debt-to-income ratio.' ],
            [ 'Gross income', 'Income before taxes and deductions.' ],
            [ 'Front-end ratio', 'Housing cost as a percentage of gross income.' ],
            [ 'Back-end ratio', 'Total debt payments as a percentage of gross income.' ],
        ],
        'faqs'      => [
            [ 'How do I calculate my debt-to-income ratio?', 'Add your monthly debt payments (housing, car, student loans, credit card minimums, other loans) and divide by your gross monthly income. For $2,750 of debt and $7,000 of income, it is 39.3%.' ],
            [ 'What is a good debt-to-income ratio?', 'Under 36% is generally considered good. Many lenders accept up to about 43%, and some programs allow more with strong credit or savings.' ],
            [ 'What DTI do I need for a mortgage?', 'Conventional lenders often prefer 36% to 43% back-end, with housing near 28%. FHA uses 31% housing and 43% total as baseline guidelines. Lenders can make exceptions.' ],
            [ 'Do utilities and groceries count in DTI?', 'No. Only recurring debt payments and, for housing, your rent or mortgage payment count.' ],
            [ 'Does DTI affect my credit score?', 'No. Credit scores do not include income, but lenders look at DTI separately when deciding to approve you.' ],
            [ 'How can I lower my DTI quickly?', 'Pay off small balances, avoid new credit, and ask whether a lower-payment loan structure is available. Raising income also helps.' ],
        ],
        'sources'   => [
            [ 'CFPB: What is a debt-to-income ratio?', 'https://www.consumerfinance.gov/ask-cfpb/what-is-a-debt-to-income-ratio-why-is-the-43-debt-to-income-ratio-important-en-1791/' ],
        ],
        'related'   => [ 'loan-affordability-calculator', 'mortgage-calculator', 'debt-consolidation-calculator', 'net-worth-calculator' ],
    ],

    /* ───────────────────────── SELF-EMPLOYMENT TAX ───────────────────────── */
    'self-employment-tax-calculator' => [
        'focus'     => 'self-employment tax calculator',
        'secondary' => [ 'self employment tax rate 2026', 'freelance tax calculator', 'quarterly estimated tax calculator', 'SE tax calculator', 'how much tax do freelancers pay' ],
        'title'     => 'Self-Employment Tax Calculator (2026)',
        'meta'      => 'Free 2026 self-employment tax calculator: SE tax at 15.3%, the deductible half, estimated income tax and quarterly payment for freelancers and contractors.',
        'excerpt'   => 'Calculate 2026 self-employment tax, the deductible half and your estimated quarterly payment for freelance and contractor income.',
        'answer'    => 'On $80,000 of net self-employment profit, 2026 self-employment tax is $11,303.64, an effective 14.13% of profit (15.3% on 92.35% of earnings). Half, $5,651.82, is deductible. Adding about $7,527 of federal income tax, a single filer should set aside about $18,830 a year, or $4,708 a quarter.',
        'steps'     => [
            'Enter your net self-employment profit: revenue minus business expenses.',
            'Enter any W-2 wages from a job, because wages count toward the Social Security wage base.',
            'Choose your filing status.',
            'Keep the income tax estimate on to see the total to set aside, or turn it off for SE tax only.',
            'Read your SE tax, deductible half, estimated income tax and quarterly payment.',
        ],
        'formula'   => '<p><strong>Net earnings = net profit × 92.35%</strong></p><p><strong>Social Security = 12.4% × earnings up to the 2026 wage base of $184,500</strong> (less any W-2 wages). <strong>Medicare = 2.9% × all earnings, plus 0.9% on earnings above $200,000 (single) or $250,000 (married filing jointly).</strong></p><p><strong>SE tax = Social Security + Medicare.</strong> You deduct half of it from income. <strong>Income tax</strong> is estimated on profit − half of SE tax − the standard deduction, using the 2026 brackets.</p>',
        'example'   => '<p>Single filer, $80,000 net profit, no other income:</p><ul><li>Net earnings: $80,000 × 92.35% = <strong>$73,880</strong></li><li>Social Security: $73,880 × 12.4% = <strong>$9,161.12</strong></li><li>Medicare: $73,880 × 2.9% = <strong>$2,142.52</strong></li><li>SE tax: <strong>$11,303.64</strong> (14.13% of profit); deductible half <strong>$5,651.82</strong></li><li>Taxable income: $80,000 − $5,651.82 − $16,100 = $58,248, federal income tax about <strong>$7,527</strong></li><li>Total to set aside: <strong>$18,830</strong>, or <strong>$4,708 per quarter</strong></li></ul>',
        'sections'  => [
            [ 'Why self-employed people pay 15.3%', '<p>Employees pay half of Social Security and Medicare taxes and the employer pays the other half. When you work for yourself you are both, so you pay the full 15.3%: 12.4% for Social Security and 2.9% for Medicare. The 92.35% factor adjusts for the fact that employers’ half is deductible, so you pay SE tax on 92.35% of your net profit.</p>' ],
            [ 'Quarterly estimated taxes', '<p>No employer withholds tax from your profit, so you generally pay estimated tax four times a year using Form 1040-ES: April, June, September and January. If you owe $1,000 or more at filing and have not paid enough through the year, you may owe an underpayment penalty. Paying the quarterly amount from the calculator, with a small cushion, helps avoid it.</p>' ],
            [ 'Deductions that reduce your bill', '<ul><li>Half of SE tax (calculated here).</li><li>Business expenses: equipment, software, mileage, home office, supplies.</li><li>Retirement contributions to a SEP-IRA, Solo 401(k) or SIMPLE IRA.</li><li>Self-employed health insurance premiums.</li><li>The qualified business income (QBI) deduction of up to 20% for many pass-through businesses, which is not included in this estimate.</li></ul>' ],
            [ 'The Social Security wage base', '<p>Social Security tax applies only to the first $184,500 of combined wages and self-employment earnings in 2026. Above that, only the 2.9% Medicare tax (plus 0.9% for high earners) applies. If you also have a job, your W-2 wages use up part of the base.</p>' ],
            [ 'Record keeping', '<p>Keep records of income and expenses all year, separate business and personal accounts, and set aside a percentage of every payment you receive. Many freelancers save 25% to 30% of income for taxes, then adjust after their first year.</p>' ],
        ],
        'terms'     => [
            [ 'SE tax', 'Self-employment tax: Social Security and Medicare for the self-employed.' ],
            [ 'Net earnings from self-employment', 'Net profit × 92.35%, the base for SE tax.' ],
            [ 'Estimated tax', 'Tax you pay during the year on income without withholding.' ],
            [ 'QBI deduction', 'A deduction of up to 20% of qualified business income for many pass-through businesses.' ],
        ],
        'faqs'      => [
            [ 'What is the self-employment tax rate for 2026?', '15.3%: 12.4% Social Security (on earnings up to $184,500) plus 2.9% Medicare, applied to 92.35% of net profit. High earners pay an extra 0.9% Medicare tax above $200,000 (single) or $250,000 (married filing jointly).' ],
            [ 'How much is self-employment tax on $80,000?', 'About $11,303.64, which is 14.13% of the profit. Half of it, $5,651.82, is deductible.' ],
            [ 'How much should I set aside for taxes as a freelancer?', 'Set aside the SE tax plus income tax. On $80,000 of profit for a single filer that is about $18,830, roughly 23.5%. Many freelancers save 25% to 30% as a buffer.' ],
            [ 'When are quarterly estimated taxes due?', 'Typically April 15, June 15, September 15 and January 15 for the previous year’s fourth quarter. Weekend or holiday dates move to the next business day.' ],
            [ 'Can I deduct self-employment tax?', 'You can deduct half of it as an adjustment to income on Schedule 1, which lowers your income tax but not your SE tax.' ],
            [ 'Do I pay SE tax if I also have a W-2 job?', 'Yes, on your self-employment profit. Your W-2 wages count toward the Social Security wage base, so you may owe less of the 12.4% portion.' ],
            [ 'Who has to pay self-employment tax?', 'Anyone with net self-employment earnings of $400 or more in a year, including freelancers, contractors and gig workers.' ],
        ],
        'sources'   => [
            [ 'IRS Topic 554: Self-employment tax', 'https://www.irs.gov/taxtopics/tc554' ],
            [ 'IRS: Estimated taxes', 'https://www.irs.gov/businesses/small-businesses-self-employed/estimated-taxes' ],
            [ 'SSA: 2026 Social Security wage base', 'https://www.ssa.gov/news/en/press/releases/2025-10-24.html' ],
        ],
        'related'   => [ 'income-tax-calculator', 'retirement-savings-calculator', 'monthly-budget-planner', 'emergency-fund-calculator' ],
    ],

    /* ───────────────────────── BUDGET PLANNER ───────────────────────── */
    'monthly-budget-planner' => [
        'focus'     => 'monthly budget planner',
        'secondary' => [ 'monthly budget calculator', 'budget planner', 'how to make a monthly budget', 'budget template', 'budget calculator income and expenses' ],
        'title'     => 'Monthly Budget Planner & Calculator',
        'meta'      => 'Free monthly budget planner: enter income and spending by category to see what is left, your savings rate, needs vs wants and a spending breakdown chart.',
        'excerpt'   => 'Enter your income and spending by category to see what is left each month, your savings rate and a breakdown of where money goes.',
        'answer'    => 'A monthly budget compares your take-home income with your spending. With $5,000 of income and $4,250 of planned spending, including $500 of savings, you have $750 left over and a 25% savings rate. In this example needs take 63% of income, above the 50% guide, while wants are only 12%.',
        'steps'     => [
            'Enter your monthly take-home income and any other income.',
            'Enter what you spend in each category. Use your last two or three bank statements for real numbers.',
            'Include a line for savings and investing so you pay yourself first.',
            'Read what is left over, your savings rate and how needs and wants compare with 50% and 30%.',
            'Adjust categories until the budget fits your goals, then check it against your actual spending each month.',
        ],
        'formula'   => '<p><strong>Left over = total income − total spending</strong></p><p><strong>Savings rate = (savings category + money left over) ÷ income × 100</strong></p><p><strong>Needs % = needs ÷ income, Wants % = wants ÷ income.</strong> Needs are housing, utilities, groceries, transportation, insurance and minimum debt payments. Wants are optional spending. The 50/30/20 guideline suggests about 50% needs, 30% wants and 20% savings.</p>',
        'example'   => '<p>$5,000 take-home income and these monthly amounts: housing $1,500, utilities $250, groceries $500, transportation $350, insurance $250, minimum debt payments $300, dining and entertainment $250, shopping and subscriptions $200, travel and hobbies $150, savings $500:</p><ul><li>Total spending: <strong>$4,250</strong></li><li>Left over: <strong>$750</strong></li><li>Savings rate: <strong>25%</strong> ($500 planned + $750 left)</li><li>Needs: <strong>63%</strong> of income; wants: <strong>12%</strong></li></ul><p>The needs share is high, which is common where housing costs a lot. The low wants share leaves room for the 25% savings rate.</p>',
        'sections'  => [
            [ 'Why a monthly budget works', '<p>A budget gives every dollar a job before the month starts. People who budget are more likely to build savings and less likely to rely on credit cards. Start with your real spending, not what you wish you spent: review three months of statements, then decide what to change.</p>' ],
            [ 'The categories to include', '<p>Housing, utilities, groceries, transportation, insurance and minimum debt payments are the core needs. Add dining out, entertainment, subscriptions, shopping, travel and hobbies as wants. Do not forget irregular costs such as car maintenance, annual insurance premiums and holiday gifts: divide the yearly total by 12 and include it monthly.</p>' ],
            [ 'Compare with the 50/30/20 guide', '<p>The 50/30/20 rule suggests about half of take-home pay for needs, 30% for wants and 20% for savings and extra debt payments. It is a guide, not a rule. In high-cost cities, needs may reach 60% or more. Use the comparison to see where you differ and whether that fits your goals; see our 50/30/20 budget calculator.</p>' ],
            [ 'Fixing a budget that does not balance', '<ul><li>Start with the largest items: housing and transportation have the biggest effect.</li><li>Cut or pause wants, subscriptions and dining out.</li><li>Look for a cheaper insurance, phone or internet plan.</li><li>Increase income with a raise, side work or selling unused items.</li><li>Pay down high-interest debt to free monthly cash.</li></ul>' ],
            [ 'Automate and review', '<p>Move savings automatically on payday, pay bills by autopay and review the budget monthly. Compare planned and actual spending, and adjust. Over time the habit matters more than any perfect number.</p>' ],
        ],
        'terms'     => [
            [ 'Take-home pay', 'Income after taxes and payroll deductions.' ],
            [ 'Needs', 'Essential costs you must pay.' ],
            [ 'Wants', 'Optional spending.' ],
            [ 'Savings rate', 'The share of income you save.' ],
        ],
        'faqs'      => [
            [ 'How do I make a monthly budget?', 'List your take-home income, list every expense by category using real statements, add savings as a line item, and make sure income minus spending is zero or positive. Review it monthly.' ],
            [ 'What is a good savings rate?', 'A common goal is 20% of take-home pay, including retirement contributions. Even 10% is a strong start if you build it up over time.' ],
            [ 'How much should I spend on housing?', 'A common guide is no more than 30% of gross income, or about 25% to 35% of take-home pay. High-cost areas often exceed it, so make sure the rest of the budget still works.' ],
            [ 'Should I use gross or take-home income?', 'Use take-home income, the amount that arrives in your bank account after taxes and payroll deductions.' ],
            [ 'What if I spend more than I earn?', 'Cut wants first, then reduce the largest needs, and look for extra income. Avoid using credit cards to cover a monthly shortfall.' ],
            [ 'How often should I review my budget?', 'Once a month at least. Compare what you planned with what you spent and adjust the next month.' ],
        ],
        'sources'   => [
            [ 'CFPB: Budgeting tools and guides', 'https://www.consumerfinance.gov/consumer-tools/budgeting/' ],
        ],
        'related'   => [ '50-30-20-budget-calculator', 'emergency-fund-calculator', 'savings-goal-calculator', 'net-worth-calculator' ],
    ],

    /* ───────────────────────── DIVIDEND ───────────────────────── */
    'dividend-calculator' => [
        'focus'     => 'dividend calculator',
        'secondary' => [ 'dividend reinvestment calculator', 'DRIP calculator', 'dividend income calculator', 'dividend growth calculator', 'how much dividend income will I earn' ],
        'title'     => 'Dividend Calculator with DRIP & Growth',
        'meta'      => 'Free dividend calculator: project dividend income, portfolio growth and yield with reinvestment (DRIP), dividend growth, monthly contributions and tax.',
        'excerpt'   => 'Project your dividend income and portfolio value with reinvested dividends, dividend growth and regular contributions.',
        'answer'    => 'Investing $20,000 at a 3.5% dividend yield with 5% yearly dividend growth, 4% price growth and $200 added monthly grows to about $207,964 in 20 years with dividends reinvested. In the final year it pays about $8,167 in dividends, around $681 a month, and you receive about $69,715 in dividends in total.',
        'steps'     => [
            'Enter the amount you are investing and the dividend yield.',
            'Enter how fast you expect the dividend and the share price to grow each year.',
            'Add monthly contributions and the number of years.',
            'Choose whether dividends are reinvested (DRIP) or taken as cash.',
            'Set your tax rate on dividends and read the portfolio value, yearly and monthly income and total dividends.',
        ],
        'formula'   => '<p><strong>Annual dividend = shares × dividend per share</strong>, where the first-year dividend per share equals the yield times the starting price and then grows by the dividend growth rate each year.</p><p>Each month the model buys shares with your contribution at the current price, pays one-twelfth of the annual dividend, and, with DRIP on, uses the dividend to buy more shares. <strong>Portfolio value = shares × price (+ cash if dividends are not reinvested).</strong> <strong>After-tax dividends = dividends × (1 − tax rate).</strong></p>',
        'example'   => '<p>$20,000 start, 3.5% yield, 5% dividend growth, 4% price growth, $200 a month, 20 years, dividends reinvested, 15% tax:</p><ul><li>Total invested: <strong>$68,000</strong></li><li>Portfolio value after 20 years: <strong>$207,964</strong></li><li>First-year dividends: <strong>$756</strong></li><li>Final-year dividends: <strong>$8,167</strong> ($681 a month)</li><li>Total dividends received: <strong>$69,715</strong>, or <strong>$59,258</strong> after 15% tax</li></ul>',
        'sections'  => [
            [ 'How dividend investing works', '<p>Many companies share part of their profits with shareholders as dividends, usually paid quarterly. The dividend yield is the yearly dividend divided by the share price. Total return is dividends plus any change in the share price. Reinvesting dividends buys more shares, which pay more dividends, so growth compounds.</p>' ],
            [ 'Dividend growth matters', '<p>A company that raises its dividend every year can turn a modest starting yield into a much larger income on your original investment. In the example, the first year pays $756 while year 20 pays $8,167. That is not guaranteed: companies can cut or eliminate dividends when profits fall.</p>' ],
            [ 'Taxes on dividends', '<p>Qualified dividends are taxed at the long-term capital gains rates of 0%, 15% or 20%, depending on income. Other dividends are taxed as ordinary income. Dividends in a retirement account such as an IRA or 401(k) are not taxed each year. High earners may also owe the 3.8% net investment income tax. See our capital gains calculator for the rate bands.</p>' ],
            [ 'Choose realistic assumptions', '<p>The S&amp;P 500’s dividend yield has been roughly 1% to 2% in recent years, while dividend-focused funds and some sectors yield more. Very high yields can signal risk. Use modest growth rates and test several scenarios, including lower price growth than you hope for.</p>' ],
            [ 'Risks', '<p>Dividend stocks can fall in price, and dividends are not guaranteed. Diversify across companies and sectors, and consider low-cost dividend or total-market index funds rather than a few single stocks.</p>' ],
        ],
        'terms'     => [
            [ 'Dividend yield', 'Yearly dividend per share divided by the share price.' ],
            [ 'DRIP', 'Dividend reinvestment plan: dividends automatically buy more shares.' ],
            [ 'Qualified dividend', 'A dividend taxed at long-term capital gains rates.' ],
            [ 'Payout ratio', 'The share of earnings paid out as dividends.' ],
        ],
        'faqs'      => [
            [ 'How do you calculate dividend income?', 'Multiply the number of shares by the annual dividend per share, or multiply the amount invested by the dividend yield. $20,000 at 3.5% pays about $700 a year at the start.' ],
            [ 'What is a DRIP?', 'A dividend reinvestment plan automatically uses your dividends to buy more shares of the same stock or fund, which compounds growth.' ],
            [ 'How much will $20,000 in dividend stocks earn?', 'At a 3.5% yield it pays about $700 in the first year. With dividend growth, reinvestment and monthly contributions, the example reaches about $8,167 a year by year 20.' ],
            [ 'Are dividends taxed?', 'Yes, unless held in a tax-advantaged account. Qualified dividends are taxed at 0%, 15% or 20%; others at ordinary income rates.' ],
            [ 'What is a good dividend yield?', 'It depends. The overall stock market yields roughly 1% to 2%, and 3% to 4% is common for dividend-focused investments. Very high yields can mean higher risk.' ],
            [ 'Can dividends be cut?', 'Yes. Companies can reduce or stop dividends when profits drop, so diversify.' ],
        ],
        'sources'   => [
            [ 'IRS Topic 404: Dividends and other corporate distributions', 'https://www.irs.gov/taxtopics/tc404' ],
            [ 'IRS Topic 409: Capital gains and losses', 'https://www.irs.gov/taxtopics/tc409' ],
        ],
        'related'   => [ 'compound-interest-calculator', 'roi-calculator', 'capital-gains-tax-calculator', 'retirement-savings-calculator' ],
    ],

    ];
}
