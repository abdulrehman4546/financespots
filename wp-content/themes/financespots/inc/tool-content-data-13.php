<?php
/**
 * Editorial content + keywords, batch 13: RV Loan Calculator, Grad PLUS Loan Calculator.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_tool_content_data_13() {
    return [

    'rv-loan-calculator' => [
        'focus'     => 'rv loan calculator',
        'secondary' => [ 'rv loan rates', 'rv financing', 'motorhome loan calculator', 'travel trailer loan', 'rv loan terms' ],
        'title'     => 'RV Loan Calculator: Payment & Interest',
        'meta'      => 'Free RV loan calculator: estimate your monthly payment, total interest, and sales tax for a travel trailer, fifth wheel, or motorhome.',
        'excerpt'   => 'Estimate your monthly RV payment, total interest, and how a longer loan term changes the real cost of financing a travel trailer or motorhome.',
        'answer'    => 'An RV loan payment is calculated the same way as an auto loan: the amount financed (price minus down payment and trade-in, plus any rolled-in tax and fees) is amortized over the loan term at your interest rate. RV loans typically carry higher rates than auto loans, roughly 7-12% depending on credit and the RV\'s age, and can stretch out to 15 or even 20 years for larger motorhomes.',
        'steps'     => [
            'Enter the RV price, your down payment, and any trade-in value.',
            'Enter the loan APR and choose a term from 5 to 20 years.',
            'Enter your state sales tax rate and any title or registration fees.',
            'Review your monthly payment, total interest, and how the term affects total cost.',
        ],
        'formula'   => '<p><strong>Amount financed = price − down payment − trade-in + (tax and fees, if rolled into the loan)</strong>.</p><p><strong>Monthly payment</strong> uses the standard amortization formula: payment = P × [r(1+r)^n] / [(1+r)^n − 1], where P is the amount financed, r is the monthly rate, and n is the number of months.</p>',
        'example'   => '<p>$60,000 travel trailer, $9,000 down, no trade-in, 8.5% APR, 10-year (120-month) term, 7% sales tax rolled into the loan:</p><ul><li>Taxable price: $60,000 → sales tax = <strong>$4,200</strong></li><li>Amount financed: $60,000 − $9,000 + $4,200 + $500 fees = <strong>$55,700</strong></li><li>Monthly payment: roughly <strong>$690/mo</strong></li><li>Total interest over 10 years: roughly <strong>$27,100</strong></li></ul>',
        'sections'  => [
            [ 'Why RV loan rates run higher than auto loans', '<p>RVs depreciate faster than cars and are considered recreational rather than essential transportation, so lenders price in more risk. Expect rates roughly 1-4 percentage points above a comparable new-car loan, with the gap widening for used RVs, lower credit scores, or RVs older than 10-15 years, which many lenders will not finance at all.</p>' ],
            [ 'How loan term changes the real cost', '<p>A 20-year term on a $100,000 motorhome can cut the monthly payment dramatically compared to a 10-year term, but it also roughly doubles the total interest paid and keeps you making payments on a depreciating asset for far longer than it may remain in good condition. Use the term-comparison table in the calculator to see the trade-off in dollars, not just monthly payment size.</p>' ],
            [ 'Secured vs. unsecured RV financing', '<p>Most RV loans are secured, using the RV itself as collateral, similar to an auto loan — this is what keeps rates lower than a personal loan. Some buyers use a home equity loan or HELOC instead, which can offer a lower rate but puts the home itself at risk if payments are missed; see the home equity loan calculator to compare that option.</p>' ],
            [ 'What lenders look at beyond your credit score', '<p>RV lenders also weigh the age, class, and resale value of the RV. Class A motorhomes and newer travel trailers qualify for the longest terms and lowest rates; older units, converted buses, or RVs bought from a private seller rather than a dealer often face shorter maximum terms and higher down payment requirements.</p>' ],
        ],
        'terms'     => [
            [ 'Amount financed', 'The loan principal: price minus down payment and trade-in, plus any tax or fees rolled into the loan.' ],
            [ 'APR', 'Annual percentage rate — the yearly cost of the loan including interest, used to calculate the monthly payment.' ],
            [ 'Negative equity', 'Owing more on an RV than it is currently worth, common with long loan terms on a fast-depreciating asset.' ],
        ],
        'faqs'      => [
            [ 'What credit score do I need for an RV loan?', 'Most lenders want a score of 660 or higher for the best rates; some specialty RV lenders will approve scores in the low 600s but at a noticeably higher APR.' ],
            [ 'How long can an RV loan term be?', 'Terms commonly range from 5 to 15 years, and some lenders offer up to 20 years for high-value motorhomes, similar in length to a mortgage.' ],
            [ 'Is it better to finance an RV through a dealer or a bank?', 'Compare both. Dealers sometimes offer promotional rates on new units, while banks and credit unions often have more flexible terms for used RVs or private-party purchases.' ],
            [ 'Can I pay off an RV loan early without a penalty?', 'Most RV loans do not carry prepayment penalties, but confirm this in your loan agreement before adding extra payments, since a small number of lenders do include one.' ],
            [ 'Do RV loans require a down payment?', 'Most lenders require 10-20% down, and a larger down payment improves your odds of approval and reduces the risk of owing more than the RV is worth.' ],
        ],
        'sources'   => [
            [ 'Consumer Financial Protection Bureau: auto and vehicle loans', 'https://www.consumerfinance.gov/consumer-tools/auto-loans/' ],
            [ 'Federal Trade Commission: vehicle financing', 'https://consumer.ftc.gov/articles/understanding-vehicle-financing' ],
        ],
        'related'   => [ 'auto-loan-calculator', 'home-equity-loan-calculator', 'loan-payoff-calculator', 'loan-affordability-calculator' ],
    ],

    'grad-plus-loan-calculator' => [
        'focus'     => 'grad plus loan calculator',
        'secondary' => [ 'grad plus loan interest rate', 'grad plus loan origination fee', 'graduate plus loan repayment', 'direct plus loan calculator', 'grad school loan calculator' ],
        'title'     => 'Grad PLUS Loan Calculator (2026)',
        'meta'      => 'Free Grad PLUS loan calculator: see your net disbursement after the origination fee, monthly payment, and total interest on your federal graduate loan.',
        'excerpt'   => 'See how much you actually receive after the Grad PLUS origination fee, plus your estimated monthly payment and total interest.',
        'answer'    => 'A Direct Grad PLUS loan is a federal, credit-based loan for graduate and professional students that can cover the full cost of attendance minus other aid. It carries a fixed interest rate set annually by Congress and an origination fee deducted directly from the amount disbursed, so the amount you actually receive is less than the amount you borrow. Interest accrues from the day the loan is disbursed — there is no interest-free grace period.',
        'steps'     => [
            'Enter the amount you plan to borrow and confirm the current interest rate and origination fee.',
            'Choose a repayment plan — standard 10-year, extended 25-year, or a custom term.',
            'Enter how many months before repayment starts (in-school period plus any grace period).',
            'Review your net disbursement, monthly payment, and total interest.',
        ],
        'formula'   => '<p><strong>Net disbursement = amount borrowed × (1 − origination fee %)</strong>.</p><p><strong>Starting balance at repayment = amount borrowed + interest accrued during school and any grace period</strong> (PLUS loans are never subsidized).</p><p><strong>Monthly payment</strong> amortizes that starting balance over the chosen term using the standard loan payment formula.</p>',
        'example'   => '<p>$20,000 borrowed at a 9.07% rate with a 4.228% origination fee, 6 months before repayment starts, standard 10-year term:</p><ul><li>Origination fee: $20,000 × 4.228% = <strong>$846</strong></li><li>Net disbursement to the school/you: $20,000 − $846 = <strong>$19,154</strong></li><li>Interest accrued in 6 months: roughly <strong>$907</strong>, added to the $20,000 balance</li><li>Estimated monthly payment: roughly <strong>$264/mo</strong> over 10 years</li></ul><p>Confirm the exact current rate and fee for your disbursement year at studentaid.gov, since both are reset by law each October 1.</p>',
        'sections'  => [
            [ 'Why you receive less than you borrow', '<p>The origination fee is subtracted before the money reaches your school, not added on top of your balance. If you borrow $20,000 and the fee is roughly 4.2%, your school receives about $800 less than the full amount — but you owe interest on the full $20,000, not the reduced amount actually disbursed.</p>' ],
            [ 'Grad PLUS vs. unsubsidized Direct Loans', '<p>Most graduate students first borrow through the Direct Unsubsidized Loan program, which has a lower interest rate and a lower (or no) origination fee, before turning to Grad PLUS to cover the remaining cost of attendance. Because Grad PLUS carries a higher rate and fee, it is generally worth maximizing your unsubsidized loan eligibility first.</p>' ],
            [ 'Credit requirements', '<p>Unlike undergraduate federal loans, Grad PLUS loans require a credit check. You do not need good credit, but you cannot have an "adverse credit history" — meaning recent delinquencies, defaults, bankruptcy, or certain collections — within the past five years. Borrowers who do not pass the credit check can still qualify with an endorser or by documenting extenuating circumstances.</p>' ],
            [ 'Repayment plan trade-offs', '<p>The standard 10-year plan pays off the loan fastest and costs the least interest. An extended 25-year term lowers the monthly payment substantially but can roughly double the total interest paid over the life of the loan — useful for cash-flow flexibility early in a career, but expensive if kept for the full term.</p>' ],
        ],
        'terms'     => [
            [ 'Origination fee', 'A percentage deducted from the loan amount before disbursement, set by federal law and adjusted each October 1.' ],
            [ 'Adverse credit history', 'Recent serious credit problems (default, bankruptcy, certain delinquencies) that can disqualify a borrower from a Grad PLUS loan without an endorser.' ],
            [ 'Capitalization', 'When unpaid accrued interest is added to the loan\'s principal balance, after which interest begins accruing on the larger amount.' ],
        ],
        'faqs'      => [
            [ 'What is the current Grad PLUS loan interest rate?', 'The rate is fixed for the life of the loan but reset annually each July 1 for loans first disbursed in that academic year. Confirm the exact current rate at studentaid.gov before borrowing.' ],
            [ 'Does the Grad PLUS loan have an origination fee?', 'Yes. A percentage fee, historically around 4.2-4.3%, is deducted from each disbursement. The exact figure is set by law and updated every October 1.' ],
            [ 'Can I be denied a Grad PLUS loan?', 'Yes, if you have an adverse credit history such as recent defaults, bankruptcy, or serious delinquencies within the past five years, unless you apply with an endorser or document extenuating circumstances.' ],
            [ 'Is interest on a Grad PLUS loan subsidized?', 'No. Grad PLUS loans are never subsidized — interest accrues from the day each disbursement is made, including while you are still in school.' ],
            [ 'Should I borrow Grad PLUS or a private graduate loan?', 'Grad PLUS loans come with federal protections private loans do not have, including income-driven repayment eligibility and loan forgiveness programs, which usually outweigh a private loan\'s potentially lower rate for borrowers with strong credit.' ],
        ],
        'sources'   => [
            [ 'Federal Student Aid: Direct PLUS Loans for graduate students', 'https://studentaid.gov/understand-aid/types/loans/plus' ],
            [ 'Federal Student Aid: interest rates and fees', 'https://studentaid.gov/understand-aid/types/loans/interest-rates' ],
        ],
        'related'   => [ 'student-loan-calculator', 'student-loan-rap-calculator', 'loan-affordability-calculator', 'income-tax-calculator' ],
    ],

    ];
}
