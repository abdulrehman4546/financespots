<?php
/**
 * Editorial content + keyword map for the first 10 loan tools.
 *
 * Keys are fs_tool post slugs. Every number in an 'example' was produced by
 * assets/js/fs-loan-engine.js with the stated inputs. Market figures carry a
 * source in 'sources'; re-check them when rates change.
 *
 * Fields:
 *  focus / secondary  Rank Math focus keyword + supporting keywords
 *  title / meta       SEO title (<=60 chars) and meta description (<=155)
 *  excerpt            one-sentence hero description
 *  answer             40-70 word direct answer shown under the calculator (AEO)
 *  steps              how-to-use steps
 *  formula            HTML: the math behind the result
 *  example            HTML: a worked example
 *  sections           [ [h2, html], ... ] topical depth
 *  terms              [ [term, definition], ... ]
 *  faqs               [ [question, answer], ... ]  (plain text, also FAQPage schema)
 *  sources            [ [label, url], ... ]
 *  related            slugs of related tools
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_tool_content_data() {
    static $data = null;
    if ( null !== $data ) return $data;

    $data = [

    /* ───────────────────────── MORTGAGE ───────────────────────── */
    'mortgage-calculator' => [
        'focus'     => 'mortgage calculator',
        'secondary' => [ 'mortgage payment calculator', 'mortgage calculator with taxes and insurance', 'mortgage calculator with PMI', 'mortgage calculator extra payments', 'how much house can I afford' ],
        'title'     => 'Mortgage Calculator with Taxes & PMI',
        'meta'      => 'Free mortgage calculator: see your full monthly payment with taxes, insurance, PMI and HOA, test extra payments, and view the amortization schedule.',
        'excerpt'   => 'Calculate your monthly mortgage payment including property tax, insurance, PMI and HOA, then see how extra payments shorten the loan.',
        'answer'    => 'Your monthly mortgage payment is principal and interest plus property tax, homeowners insurance, and (with under 20% down) PMI. On a $400,000 home with 20% down at 7% for 30 years, principal and interest is $2,129 a month. With typical taxes and insurance, the total comes to about $2,679.',
        'steps'     => [
            'Enter the home price and your down payment, as dollars or as a percentage.',
            'Add the interest rate and loan term. Use a quote from a lender, or the weekly Freddie Mac average as a starting point.',
            'Enter yearly property tax and homeowners insurance, plus any monthly HOA dues.',
            'If you put down less than 20%, leave the PMI rate on. The calculator drops PMI automatically once your balance reaches 80% of the home price.',
            'Optionally add an extra monthly payment or a one-time lump sum to see the interest and time you save.',
        ],
        'formula'   => '<p>Principal and interest use the standard amortization formula:</p><p><strong>M = P × r ÷ (1 − (1 + r)<sup>−n</sup>)</strong></p><ul><li><strong>P</strong> = loan amount (home price minus down payment)</li><li><strong>r</strong> = monthly interest rate (annual rate ÷ 12)</li><li><strong>n</strong> = number of monthly payments (years × 12)</li></ul><p>The full payment, often called <strong>PITI</strong>, is M + monthly property tax + monthly insurance + monthly PMI + HOA dues.</p>',
        'example'   => '<p>A $400,000 home, $80,000 down (20%), 7% rate, 30-year fixed:</p><ul><li>Loan amount: <strong>$320,000</strong></li><li>Principal and interest: <strong>$2,128.97</strong> per month</li><li>Property tax $4,800 and insurance $1,800 a year add $550: total <strong>about $2,679</strong></li><li>Total interest over 30 years: <strong>$446,428</strong></li></ul><p>Switching to a 15-year loan raises principal and interest to $2,876 a month but cuts total interest to $197,725, a saving of about $248,700. Adding $200 a month to the 30-year loan pays it off in 279 months (23 years 3 months) and saves about $118,972 in interest.</p>',
        'sections'  => [
            [ 'What goes into a mortgage payment', '<p>Lenders quote the rate on principal and interest, but the check you write each month is larger. Most lenders collect property tax and homeowners insurance in an escrow account and pay those bills for you, so both are part of your monthly housing cost. Private mortgage insurance (PMI) is added when you borrow more than 80% of the home’s value on a conventional loan. HOA dues are paid separately but count toward the budget lenders use to approve you.</p><p>Early in the loan most of each payment is interest. On a $320,000 loan at 7%, the first payment sends about $1,867 to interest and only $262 to principal. The balance falls slowly at first and faster later, which is why extra payments made early save the most.</p>' ],
            [ 'How much house can I afford?', '<p>A common guideline is the 28/36 rule: keep housing costs under 28% of gross monthly income and total debt payments under 36%. Lenders often approve up to 43–45% total debt-to-income on a conventional loan. The calculator shows the income you would need for the payment shown using the 28% rule. A $2,679 monthly payment needs roughly $114,800 of gross annual income. Your own comfort level, savings, and other debts matter more than any ratio.</p>' ],
            [ 'How PMI works and how to avoid it', '<p>PMI protects the lender, not you, and usually costs between 0.3% and 1.5% of the loan each year depending on your credit score and down payment. Under the Homeowners Protection Act you can ask your servicer to cancel PMI once your balance reaches 80% of the home’s original value, and it must end automatically at 78% if you are current on payments. You can avoid PMI by putting 20% down, using a piggyback loan, or choosing a lender-paid option with a slightly higher rate.</p>' ],
            [ '15-year vs 30-year: which is better?', '<p>A 15-year loan has a higher payment but a lower rate and far less interest. A 30-year loan keeps the payment manageable and leaves room to invest or build savings. Use the term comparison table to see the trade-off for your exact loan, then decide whether the lower total cost or the flexibility matters more.</p>' ],
            [ 'Ways to pay off a mortgage faster', '<ul><li><strong>Extra monthly principal:</strong> even $100–$200 a month shortens a 30-year loan by several years.</li><li><strong>One extra payment a year:</strong> equal to making 13 payments instead of 12.</li><li><strong>Recast or refinance:</strong> if rates fall, compare savings with our <a href="/tool/refinance-calculator/">refinance calculator</a>.</li></ul><p>Tell your servicer that extra money should go to principal, not to next month’s payment.</p>' ],
        ],
        'terms'     => [
            [ 'PITI', 'Principal, interest, taxes and insurance: the four parts of a typical monthly payment.' ],
            [ 'LTV (loan-to-value)', 'Loan amount divided by home value. A lower LTV means lower risk and usually a lower rate.' ],
            [ 'PMI', 'Private mortgage insurance, required on most conventional loans with less than 20% down.' ],
            [ 'Escrow', 'Account your servicer uses to pay property tax and insurance from your monthly payment.' ],
            [ 'Amortization', 'Paying off a loan in scheduled installments that split between interest and principal.' ],
            [ 'APR', 'Annual percentage rate: the interest rate plus certain lender fees, expressed yearly.' ],
        ],
        'faqs'      => [
            [ 'How do you calculate a monthly mortgage payment?', 'Multiply the loan amount by the monthly rate, then divide by 1 minus (1 + monthly rate) raised to the negative number of payments. That gives principal and interest. Add monthly property tax, homeowners insurance, PMI if you put down less than 20%, and any HOA dues to get the full payment.' ],
            [ 'What is PITI on a mortgage?', 'PITI stands for principal, interest, taxes and insurance. It is the total monthly housing payment most lenders use when they decide how much you can borrow.' ],
            [ 'How much is the monthly payment on a $400,000 mortgage?', 'With 20% down, a 7% rate and a 30-year term, principal and interest on a $320,000 loan is about $2,129 a month. Adding $550 a month for property tax and insurance brings it to about $2,679. A lower rate, larger down payment or shorter term changes the result; enter your own numbers above.' ],
            [ 'How much income do I need for a $400,000 house?', 'Using the 28% rule, a total payment near $2,679 a month requires about $114,800 in gross annual income. Lenders also look at your other debts, credit score and savings, so your real requirement may be higher or lower.' ],
            [ 'When can I remove PMI?', 'You can request PMI cancellation when your loan balance reaches 80% of the home’s original value, and your servicer must end it automatically at 78% if you are current. FHA loans follow different mortgage insurance rules.' ],
            [ 'Is it better to put 20% down?', 'A 20% down payment avoids PMI and lowers the monthly payment, but it is not required. Many buyers put down less to keep savings for repairs and emergencies. Compare the PMI cost against the benefit of keeping cash on hand.' ],
            [ 'Do extra payments really save money?', 'Yes. Extra principal reduces the balance that accrues interest. On a $320,000, 7%, 30-year loan, an extra $200 a month saves about $118,972 in interest and removes roughly 6 years 9 months from the loan.' ],
            [ 'What is a good mortgage rate right now?', 'Rates change weekly. The Freddie Mac 30-year fixed average crossed 7% in late September 2026, while 15-year rates run lower. Your rate depends on credit score, down payment, loan type and points, so get quotes from at least three lenders.' ],
        ],
        'sources'   => [
            [ 'Freddie Mac Primary Mortgage Market Survey (weekly rates)', 'https://www.freddiemac.com/pmms' ],
            [ 'CFPB: When can I remove PMI from my loan?', 'https://www.consumerfinance.gov/ask-cfpb/when-can-i-remove-private-mortgage-insurance-pmi-from-my-loan-en-202/' ],
            [ 'FHFA: 2026 conforming loan limit ($832,750)', 'https://www.fhfa.gov/news/news-release/fhfa-announces-conforming-loan-limit-values-for-2026' ],
        ],
        'related'   => [ 'amortization-calculator', 'refinance-calculator', 'fha-loan-calculator', 'loan-comparison-calculator' ],
    ],

    /* ───────────────────────── AUTO LOAN ───────────────────────── */
    'auto-loan-calculator' => [
        'focus'     => 'auto loan calculator',
        'secondary' => [ 'car loan calculator', 'car payment calculator', 'auto loan calculator with trade-in', 'auto loan calculator with sales tax', 'how much car can I afford' ],
        'title'     => 'Auto Loan Calculator: Payment, Tax & Trade-In',
        'meta'      => 'Free auto loan calculator: estimate your monthly car payment with sales tax, fees, trade-in, rebates and down payment, then compare loan terms.',
        'excerpt'   => 'Estimate your monthly car payment with sales tax, fees, trade-in and rebates, and see how the loan term changes what you pay.',
        'answer'    => 'Your car payment depends on the amount financed, APR and loan term. Financing $33,050 (a $35,000 car, $5,000 down, 7% sales tax and fees rolled in) at 7% for 60 months costs $654 a month and $6,216 in interest. Stretching to 84 months lowers the payment to $499 but raises interest to $8,850.',
        'steps'     => [
            'Enter the vehicle price and any cash down payment.',
            'Add your trade-in value and the amount you still owe on it. If you owe more than it is worth, the difference is rolled into the loan.',
            'Enter the manufacturer rebate, your combined state and local sales tax rate, and title, registration and dealer fees.',
            'Choose the APR and loan term. Check the term comparison table to see the cost of each option.',
            'Add an extra monthly payment if you plan to pay the loan off early.',
        ],
        'formula'   => '<p><strong>Amount financed = price − rebate − down payment − trade-in value + amount owed on trade-in + sales tax + fees</strong> (tax and fees only if you roll them in).</p><p><strong>Payment = P × r ÷ (1 − (1 + r)<sup>−n</sup>)</strong>, where P is the amount financed, r is APR ÷ 12 and n is the number of months.</p><p>Sales tax is charged on the price minus your trade-in in most states, but a few states tax the full price.</p>',
        'example'   => '<p>$35,000 car, $5,000 down, no trade-in, 7% sales tax ($2,450), $600 in fees, 7% APR:</p><ul><li>Amount financed: <strong>$33,050</strong></li></ul><table><thead><tr><th>Term</th><th>Monthly payment</th><th>Total interest</th></tr></thead><tbody><tr><td>48 months</td><td>$791</td><td>$4,938</td></tr><tr><td>60 months</td><td>$654</td><td>$6,216</td></tr><tr><td>72 months</td><td>$563</td><td>$7,520</td></tr><tr><td>84 months</td><td>$499</td><td>$8,850</td></tr></tbody></table><p>Going from 60 to 84 months cuts the payment by $155 but adds $2,634 in interest.</p>',
        'sections'  => [
            [ 'What is a good auto loan rate in 2026?', '<p>Edmunds reported an average new-car APR of about 7.0% in the second quarter of 2026, with an average loan of roughly 70 months. Used-car loans usually cost more, and rates vary widely by credit score: borrowers with excellent credit often qualify well below the average, while subprime borrowers can pay double-digit rates. Get a pre-approval from a bank or credit union before visiting the dealer so you can compare it with the dealer’s offer.</p>' ],
            [ 'How long should a car loan be?', '<p>Shorter is cheaper. A 48-month loan on the example above costs $3,912 less in interest than an 84-month loan. Long terms also raise the risk of negative equity, where you owe more than the car is worth. Edmunds found that more than a third of new-car loans now run longer than 72 months. If you need a 72- or 84-month term to afford the payment, consider a less expensive car or a larger down payment.</p>' ],
            [ 'Negative equity and trade-ins', '<p>If you owe $18,000 on a car worth $15,000, you have $3,000 of negative equity. Rolling it into your next loan means you pay interest on your old car too. Enter both the trade-in value and the amount owed, and the calculator adds the shortfall to the amount financed.</p>' ],
            [ 'Cash rebate or low APR?', '<p>Dealers often offer a choice between a rebate and a special low rate. Run the calculator twice: once with the rebate and your bank’s rate, once with no rebate and the promotional rate. Pick the lower total cost, not the lower payment.</p>' ],
            [ 'Rules of thumb for car affordability', '<p>A widely used guideline is 20/4/10: put 20% down, finance for no more than 4 years, and keep total car costs (payment, insurance, fuel) under 10% of gross income. It is a guide, not a rule, but it keeps you out of negative equity.</p>' ],
        ],
        'terms'     => [
            [ 'APR', 'The yearly cost of the loan including interest and certain fees.' ],
            [ 'Amount financed', 'The total you borrow after down payment, trade-in and rebates.' ],
            [ 'Negative equity', 'Owing more on a car than its market value; also called being upside down.' ],
            [ 'Trade-in credit', 'The value a dealer gives for your old car, which reduces the price you finance.' ],
            [ 'Doc fee', 'A dealer charge for preparing paperwork; limits and amounts vary by state.' ],
        ],
        'faqs'      => [
            [ 'How is a car loan payment calculated?', 'Take the amount financed, multiply by the monthly interest rate, and divide by 1 minus (1 + monthly rate) raised to the negative number of months. The amount financed is the price plus sales tax and fees, minus your down payment, trade-in and rebates.' ],
            [ 'How much car can I afford?', 'A common guide is to keep total car costs under 10% of gross monthly income and to finance for no more than 48 to 60 months. Use the calculator to work backward: adjust the price until the monthly payment fits your budget.' ],
            [ 'Is it better to take a longer loan for a lower payment?', 'It lowers the payment but raises total interest and the chance of owing more than the car is worth. On a $33,050 loan at 7%, 84 months costs $2,634 more in interest than 60 months.' ],
            [ 'Does a down payment lower my monthly payment?', 'Yes. Every dollar down reduces the amount financed and the interest. A 20% down payment on a new car also helps you avoid negative equity.' ],
            [ 'Is sales tax included in the loan?', 'It can be. Many buyers roll sales tax and fees into the loan, which raises the amount financed. Uncheck the roll-in option to see the cash you need at signing instead.' ],
            [ 'What credit score do I need for a good auto loan rate?', 'Borrowers with scores above about 720 usually receive the lowest rates. Scores below 620 often face much higher APRs. Check your credit report before you shop and compare offers from a credit union, your bank and the dealer.' ],
            [ 'Can I pay off a car loan early?', 'Most auto loans allow it without a penalty, but check your contract for prepayment fees. Use the extra payment box to see the interest you would save.' ],
            [ 'How do I calculate a car payment without a calculator?', 'Divide the amount financed by the number of months for a rough principal estimate, then add interest: multiply the loan amount by APR and the years, divide by the months. This is only approximate because interest is charged on a declining balance; the calculator above is exact.' ],
        ],
        'sources'   => [
            [ 'Edmunds: average amount financed and APR, Q1 2026', 'https://www.globenewswire.com/news-release/2026/04/01/3266741/0/en/average-amount-financed-for-new-vehicle-purchases-hits-record-43-899-in-q1-2026-according-to-edmunds.html' ],
            [ 'GM Authority: Edmunds Q2 2026 loan term and APR data', 'https://gmauthority.com/blog/2026/07/new-car-buyers-stretching-out-loans-at-record-levels-in-q2-2026/' ],
        ],
        'related'   => [ 'loan-comparison-calculator', 'personal-loan-calculator', 'amortization-calculator', 'refinance-calculator' ],
    ],

    /* ───────────────────────── PERSONAL LOAN ───────────────────────── */
    'personal-loan-calculator' => [
        'focus'     => 'personal loan calculator',
        'secondary' => [ 'personal loan payment calculator', 'personal loan APR calculator', 'personal loan calculator with origination fee', 'how much personal loan can I afford' ],
        'title'     => 'Personal Loan Calculator with APR & Fees',
        'meta'      => 'Free personal loan calculator: get your monthly payment, total interest and true APR including origination fee, plus the largest loan your budget allows.',
        'excerpt'   => 'See your monthly payment, total interest and the true APR after the origination fee, and find the largest loan your budget supports.',
        'answer'    => 'A $15,000 personal loan at 12% for 36 months costs $498 a month and $2,936 in interest. A 3% origination fee ($450) is taken from the money you receive, so the true APR is 14.13%, not 12%. Always compare loans by APR, because it includes fees.',
        'steps'     => [
            'Enter the amount you want to borrow and the interest rate the lender quoted.',
            'Choose the loan term in months.',
            'Enter the origination fee as a percentage. Most lenders deduct it from your proceeds.',
            'Read the true APR, cash received and total cost of borrowing.',
            'Enter a monthly budget to see the largest loan that fits, or an extra payment to see the interest you would save.',
        ],
        'formula'   => '<p><strong>Payment = P × r ÷ (1 − (1 + r)<sup>−n</sup>)</strong> with P the loan amount, r the monthly rate and n the months.</p><p>The <strong>true APR</strong> is the rate at which the payments you make equal the cash you actually receive (loan amount minus the origination fee). This follows the idea behind the federal APR definition in Regulation Z.</p><p><strong>Largest loan for a budget = payment × (1 − (1 + r)<sup>−n</sup>) ÷ r</strong>.</p>',
        'example'   => '<p>$15,000 at 12% for 36 months with a 3% origination fee:</p><ul><li>Monthly payment: <strong>$498.21</strong></li><li>Cash you receive: <strong>$14,550</strong> (after a $450 fee)</li><li>Total interest: <strong>$2,936</strong></li><li>Total cost of borrowing (interest + fee): <strong>$3,386</strong></li><li>True APR: <strong>14.13%</strong></li></ul><p>With a $400 monthly budget at the same rate and term, the largest loan is about $12,043.</p>',
        'sections'  => [
            [ 'Interest rate vs APR', '<p>The interest rate is the price of borrowing the principal. The APR adds fees, so it is the better number for comparing offers. A loan with a lower rate but a 6% origination fee can cost more than a loan with a slightly higher rate and no fee. Use the loan comparison calculator to put offers side by side.</p>' ],
            [ 'What personal loan rates look like in 2026', '<p>The Federal Reserve’s G.19 report showed commercial banks charging an average of 11.86% on 24-month personal loans in the second quarter of 2026. Online lenders and credit unions vary. Borrowers with strong credit may qualify for single-digit rates, while fair or poor credit can mean rates above 20%. Some lenders charge origination fees between 1% and 10%.</p>' ],
            [ 'When a personal loan makes sense', '<p>Personal loans work best for a fixed, one-time expense or to consolidate higher-interest debt. The fixed payment and end date make them predictable. They are a poor fit for ongoing spending, and a missed payment can damage your credit. If you are replacing credit card balances, use the debt consolidation calculator to confirm you will really save.</p>' ],
            [ 'How to lower the cost', '<ul><li>Choose the shortest term whose payment fits your budget.</li><li>Check for lenders that charge no origination fee.</li><li>Pre-qualify with a soft credit check to compare real rates.</li><li>Make extra payments. Personal loans rarely charge a prepayment penalty, but check the contract.</li></ul>' ],
        ],
        'terms'     => [
            [ 'Origination fee', 'A one-time fee, usually a percentage of the loan, deducted from the money you receive.' ],
            [ 'APR', 'The annual cost of the loan including interest and fees.' ],
            [ 'Unsecured loan', 'A loan with no collateral; approval and rate depend on credit and income.' ],
            [ 'Soft inquiry', 'A credit check that does not affect your score, used to pre-qualify.' ],
            [ 'Term', 'The number of months you have to repay the loan.' ],
        ],
        'faqs'      => [
            [ 'How is a personal loan payment calculated?', 'Multiply the loan amount by the monthly interest rate and divide by 1 minus (1 + monthly rate) raised to the negative number of months. The result is a fixed payment that covers interest and principal until the loan is repaid.' ],
            [ 'What is the monthly payment on a $10,000 personal loan?', 'At 12% for 36 months the payment is about $332 a month with total interest of about $1,957. A shorter term raises the payment and lowers interest; a longer term does the opposite. Enter your own terms in the calculator.' ],
            [ 'What is the difference between interest rate and APR on a personal loan?', 'The interest rate is the cost of borrowing the principal. APR includes the rate plus fees such as the origination fee, so it shows the real cost. On a $15,000 loan at 12% with a 3% fee, the APR is 14.13%.' ],
            [ 'How much personal loan can I afford?', 'Enter the monthly payment that fits your budget, along with the rate and term, and the calculator shows the largest loan. At 12% over 36 months, a $400 payment supports about $12,043.' ],
            [ 'Are origination fees worth paying?', 'Only if the loan’s APR is still lower than your other options. Compare the APR, not the quoted rate, and consider a no-fee lender.' ],
            [ 'Can I get a personal loan with bad credit?', 'Yes, but expect a higher rate and possibly higher fees. A co-signer, secured loan or credit-union option may lower the cost. Check the APR and the total repaid before accepting.' ],
            [ 'Will a personal loan hurt my credit score?', 'A hard inquiry can cause a small, temporary dip. On-time payments help build credit over time, while late payments hurt. Using a personal loan to pay off credit cards can also lower your credit utilization.' ],
            [ 'Can I pay a personal loan off early?', 'Usually yes, and most lenders do not charge a prepayment penalty. Paying extra each month reduces the interest you owe.' ],
        ],
        'sources'   => [
            [ 'Federal Reserve G.19 Consumer Credit release (personal loan rates)', 'https://www.federalreserve.gov/releases/g19/current/' ],
            [ 'CFPB: Regulation Z, determination of the annual percentage rate', 'https://www.consumerfinance.gov/rules-policy/regulations/1026/22/' ],
        ],
        'related'   => [ 'debt-consolidation-calculator', 'loan-comparison-calculator', 'amortization-calculator', 'auto-loan-calculator' ],
    ],

    /* ───────────────────────── STUDENT LOAN ───────────────────────── */
    'student-loan-calculator' => [
        'focus'     => 'student loan calculator',
        'secondary' => [ 'student loan payment calculator', 'student loan repayment calculator', 'student loan interest calculator', 'student loan calculator 2026', 'student loan grace period interest' ],
        'title'     => 'Student Loan Payment Calculator (2026 Rates)',
        'meta'      => 'Free student loan calculator: estimate monthly payments, interest and payoff date with 2026-27 federal rates, grace-period interest and extra payments.',
        'excerpt'   => 'Estimate your student loan payment, total interest and payoff date with current federal rates, grace-period interest and extra payments.',
        'answer'    => 'A $38,000 federal student loan at the 2026-27 undergraduate rate of 6.52% costs about $446 a month over the 10-year standard plan, with roughly $15,514 in total interest including interest that builds during a 6-month grace period. A 15-year term lowers the payment to about $342 but raises interest to about $23,604.',
        'steps'     => [
            'Enter your total balance and interest rate. For federal loans issued in 2026-27 the undergraduate rate is 6.52%.',
            'Choose a repayment plan: standard 10-year, the new tiered standard plan for loans made from July 2026, graduated, extended, or a custom term.',
            'Enter the months before repayment starts, including time in school and the grace period. Keep the interest checkbox on for unsubsidized loans.',
            'Add an extra monthly payment to see how much interest and time you save.',
            'Review the schedule. Income-driven plans are based on your income and are not modeled here.',
        ],
        'formula'   => '<p><strong>Payment = B × r ÷ (1 − (1 + r)<sup>−n</sup>)</strong> where B is the balance when repayment starts, r is the rate ÷ 12 and n is the number of months.</p><p>On unsubsidized loans, interest builds before repayment: <strong>B = loan + loan × r × months before repayment</strong>, and that interest is added to your balance when repayment starts.</p><p>The graduated plan starts lower and steps up every two years; the calculator estimates it with 20% steps.</p>',
        'example'   => '<p>$38,000 at 6.52% with 6 months of accrued interest ($1,239), so a starting balance of $39,239:</p><table><thead><tr><th>Plan</th><th>Monthly payment</th><th>Total interest*</th></tr></thead><tbody><tr><td>10-year standard</td><td>$446</td><td>$15,514</td></tr><tr><td>15-year</td><td>$342</td><td>$23,604</td></tr><tr><td>25-year extended</td><td>$265</td><td>$41,631</td></tr></tbody></table><p>*Includes the $1,239 of interest that accrued before repayment. A 10% income rule of thumb suggests you want gross income of about $53,500 for the $446 payment.</p>',
        'sections'  => [
            [ '2026-27 federal student loan rates', '<p>Federal Direct Loan rates are fixed for the life of each loan and reset every July 1 based on the May 10-year Treasury auction. For loans first disbursed from July 1, 2026 through June 30, 2027 the rates are 6.52% for undergraduate loans, 8.07% for graduate and professional loans, and 9.07% for PLUS loans. Private lenders set their own rates and may be fixed or variable. Check studentaid.gov for the official table.</p>' ],
            [ 'What changed on July 1, 2026', '<p>Under the One Big Beautiful Bill Act, borrowers who take out new federal loans on or after July 1, 2026 choose between a new tiered Standard plan and the Repayment Assistance Plan (RAP). RAP sets a payment between 1% and 10% of adjusted gross income, with a $10 minimum and a $50 reduction per dependent, and forgiveness after 30 years. Borrowers who have no loans made on or after July 1, 2026 can generally stay on existing plans and may also opt into RAP. The tiered Standard plan assigns a 10-year term to balances under $25,000, 15 years for $25,000–$49,999, 20 years for $50,000–$99,999 and 25 years for $100,000 or more. Plan rules can change, so confirm details with your servicer.</p>' ],
            [ 'Grace period and interest capitalization', '<p>Most federal loans have a six-month grace period after you leave school. On subsidized loans the government pays the interest during grace; on unsubsidized loans interest accrues and is added to the balance when repayment begins. On a $38,000 loan at 6.52% that is about $1,239 after six months. Paying the interest during school or grace keeps the starting balance from growing.</p>' ],
            [ 'Standard, graduated or extended?', '<p>The standard plan has the highest payment and the lowest total interest. Graduated payments start lower and rise every two years, which helps when income is expected to grow, but costs more overall. The extended plan stretches to 25 years and is aimed at larger balances. If your payment is hard to afford, compare these results with an income-driven plan on studentaid.gov before you choose.</p>' ],
            [ 'Paying student loans off faster', '<p>Extra payments go first to interest, then to principal. Adding $100 a month to the 10-year example saves about $3,600 in interest and shortens the loan by about 2 years 4 months. Put extra money toward the loan with the highest rate first. Private loan refinancing can lower the rate, but it permanently gives up federal protections like income-driven repayment and forgiveness programs.</p>' ],
        ],
        'terms'     => [
            [ 'Subsidized loan', 'A federal loan on which the government pays interest while you are in school and during grace.' ],
            [ 'Unsubsidized loan', 'A federal loan on which interest accrues from disbursement.' ],
            [ 'Capitalization', 'Adding unpaid interest to the loan balance so future interest is charged on it.' ],
            [ 'Grace period', 'Time after leaving school, usually six months, before repayment begins.' ],
            [ 'RAP', 'Repayment Assistance Plan: an income-based plan for federal loans made from July 1, 2026.' ],
            [ 'Servicer', 'The company that bills you and manages your federal loan.' ],
        ],
        'faqs'      => [
            [ 'How do I calculate my student loan payment?', 'Add any interest that built up before repayment to your balance, then use the amortization formula: balance times the monthly rate, divided by 1 minus (1 + monthly rate) raised to the negative number of months. The calculator above does this for each plan.' ],
            [ 'What are the federal student loan interest rates for 2026-27?', 'For loans disbursed from July 1, 2026 to June 30, 2027: 6.52% for undergraduate, 8.07% for graduate and professional, and 9.07% for PLUS loans. Rates are fixed for the life of the loan.' ],
            [ 'How much is the monthly payment on $38,000 of student loans?', 'At 6.52% over 10 years the payment is about $446 when 6 months of grace-period interest is included. Over 15 years it is about $342, and over 25 years about $265.' ],
            [ 'Does interest accrue during the grace period?', 'On unsubsidized federal loans and most private loans, yes. On subsidized federal loans the government pays it. Interest that accrues is added to your balance when repayment begins.' ],
            [ 'What is the Repayment Assistance Plan (RAP)?', 'RAP is an income-driven plan for federal loans made on or after July 1, 2026. Payments range from 1% to 10% of adjusted gross income with a $10 minimum and $50 off per dependent, and remaining balances are forgiven after 30 years. This calculator does not model RAP.' ],
            [ 'Should I pay off student loans early?', 'If your rate is higher than what you would earn investing after taxes, paying extra can make sense, especially after building an emergency fund. Keep any employer retirement match first.' ],
            [ 'Is it worth refinancing student loans?', 'Refinancing with a private lender can lower your rate, but you lose federal benefits such as income-driven repayment and forgiveness. Refinance only if you have stable income and no need for those protections.' ],
            [ 'What happens to my payment if I choose a longer term?', 'The monthly payment falls, but you pay more total interest because the balance stays outstanding longer. The comparison table above shows the trade-off for your loan.' ],
        ],
        'sources'   => [
            [ 'Federal Student Aid: interest rates and fees', 'https://studentaid.gov/understand-aid/types/loans/interest-rates' ],
            [ 'Federal Student Aid: repayment plans', 'https://studentaid.gov/manage-loans/repayment/plans' ],
            [ 'CNBC: student loan rates for 2026-27', 'https://www.cnbc.com/2026/05/12/student-loan-interest-rates.html' ],
        ],
        'related'   => [ 'amortization-calculator', 'personal-loan-calculator', 'refinance-calculator', 'debt-consolidation-calculator' ],
    ],

    /* ───────────────────────── HOME EQUITY ───────────────────────── */
    'home-equity-loan-calculator' => [
        'focus'     => 'home equity loan calculator',
        'secondary' => [ 'HELOC calculator', 'home equity line of credit calculator', 'how much equity can I borrow', 'home equity loan payment calculator', 'CLTV calculator' ],
        'title'     => 'Home Equity Loan & HELOC Calculator',
        'meta'      => 'Free home equity loan and HELOC calculator: find how much you can borrow at 80–90% CLTV, your monthly payment, APR with closing costs and total interest.',
        'excerpt'   => 'Find out how much equity you can borrow, then compare the payment on a fixed home equity loan with an interest-only HELOC.',
        'answer'    => 'Most lenders let you borrow up to 80–90% of your home’s value minus your mortgage. On a $500,000 home with a $300,000 mortgage at 85% CLTV, you can borrow up to $125,000. A $60,000 home equity loan at 8.5% over 15 years costs about $591 a month and $46,352 in interest.',
        'steps'     => [
            'Enter your home’s estimated value and your current mortgage balance.',
            'Pick the maximum combined loan-to-value (CLTV) your lender allows. 85% is common.',
            'Choose a fixed-rate home equity loan or a HELOC. For a HELOC, set the interest-only draw period.',
            'Enter the amount, rate, repayment term and closing costs.',
            'Read the payment, total interest and APR including closing costs. Check the schedule for the full payoff path.',
        ],
        'formula'   => '<p><strong>Maximum borrowing = home value × max CLTV − mortgage balance</strong></p><p><strong>CLTV after the loan = (mortgage balance + new loan) ÷ home value</strong></p><p>A fixed loan uses the standard payment formula P × r ÷ (1 − (1 + r)<sup>−n</sup>). A HELOC charges interest only during the draw period (P × r), then amortizes over the repayment period.</p>',
        'example'   => '<p>Home value $500,000, mortgage $300,000, max CLTV 85%:</p><ul><li>Maximum you can borrow: <strong>$125,000</strong> ($425,000 − $300,000)</li><li>Current equity: <strong>$200,000</strong></li></ul><p>Borrowing $60,000 at 8.5% for 15 years as a fixed loan: payment <strong>$590.84</strong>, total interest <strong>$46,352</strong>, CLTV after the loan <strong>72%</strong>. As a HELOC with a 10-year interest-only draw and 15-year repayment, the payment is $425 during the draw, then rises as principal repayment begins.</p>',
        'sections'  => [
            [ 'Home equity loan vs HELOC', '<p>A home equity loan pays out a lump sum with a fixed rate and a fixed payment. A HELOC is a revolving credit line: you borrow as needed during a draw period (often 10 years, interest-only), then repay principal over 10–20 years. HELOC rates usually float with the prime rate, so the payment can change. Choose a loan for a single known expense such as a renovation; choose a HELOC for costs that arrive over time.</p>' ],
            [ 'How much equity can you borrow?', '<p>Lenders limit your combined loan-to-value (CLTV), the total of all loans secured by the home divided by its value. Many cap CLTV at 80–85%, and some go to 90% for strong borrowers. They also require a minimum credit score, steady income and a debt-to-income ratio they can approve, and they order an appraisal or automated valuation.</p>' ],
            [ 'Closing costs and the true cost', '<p>Home equity loans often charge 2–5% in closing costs; some lenders waive them on HELOCs. Adding closing costs to the equation raises the APR: in the example, $1,000 of costs lifts the APR from 8.50% to 8.78%. Compare the APR and total cost, not just the rate.</p>' ],
            [ 'Tax treatment and risk', '<p>Interest may be deductible only when the money is used to buy, build or substantially improve the home that secures the loan, and only if you itemize. See IRS Publication 936. The bigger risk is that your home is the collateral. If you cannot repay, the lender can foreclose, so borrow only what you can afford to repay from stable income.</p>' ],
            [ 'Alternatives', '<p>A cash-out refinance replaces your mortgage with a larger one, which may make sense if today’s rate is lower than your current rate. With a higher current mortgage rate, a separate home equity loan lets you keep the low-rate first mortgage. Use the refinance calculator to compare.</p>' ],
        ],
        'terms'     => [
            [ 'CLTV', 'Combined loan-to-value: all loans on the home divided by its value.' ],
            [ 'Draw period', 'The early stage of a HELOC, when you can borrow and often pay interest only.' ],
            [ 'Repayment period', 'The stage when a HELOC is paid down with principal and interest.' ],
            [ 'Equity', 'Home value minus what you owe on it.' ],
            [ 'Prime rate', 'The benchmark most HELOC rates are based on.' ],
        ],
        'faqs'      => [
            [ 'How much can I borrow with a home equity loan?', 'Multiply your home’s value by the lender’s maximum CLTV (often 80–85%) and subtract your mortgage balance. For a $500,000 home with a $300,000 mortgage at 85% CLTV, the maximum is $125,000.' ],
            [ 'What is the difference between a home equity loan and a HELOC?', 'A home equity loan is a lump sum with a fixed rate and payment. A HELOC is a credit line with a variable rate, a draw period and a repayment period.' ],
            [ 'How is a HELOC payment calculated?', 'During the draw period the payment is usually interest only: balance times the annual rate divided by 12. During repayment it becomes a standard amortizing payment. A $60,000 balance at 8.5% costs $425 a month interest-only.' ],
            [ 'Is home equity loan interest tax deductible?', 'Only when the funds are used to buy, build or substantially improve the home that secures the loan, and only if you itemize deductions. Consult IRS Publication 936 or a tax professional.' ],
            [ 'What credit score do I need for a home equity loan?', 'Many lenders want a score of at least 620, and the best rates go to borrowers above 700. Lenders also check income, debt-to-income and the amount of equity remaining.' ],
            [ 'What are the risks of a home equity loan?', 'Your home secures the debt, so missed payments can lead to foreclosure. A HELOC payment can rise when rates do, and falling home values can reduce your available credit.' ],
            [ 'Should I use a HELOC or a cash-out refinance?', 'If your current mortgage rate is lower than today’s rates, a HELOC or home equity loan preserves it. If today’s rate is lower, a cash-out refinance may cost less overall. Compare total cost over the time you will keep the loan.' ],
        ],
        'sources'   => [
            [ 'FTC: Home equity loans and home equity lines of credit', 'https://consumer.ftc.gov/articles/home-equity-loans-and-home-equity-lines-credit' ],
            [ 'IRS Publication 936: Home Mortgage Interest Deduction', 'https://www.irs.gov/publications/p936' ],
        ],
        'related'   => [ 'refinance-calculator', 'mortgage-calculator', 'debt-consolidation-calculator', 'amortization-calculator' ],
    ],

    /* ───────────────────────── DEBT CONSOLIDATION ───────────────────────── */
    'debt-consolidation-calculator' => [
        'focus'     => 'debt consolidation calculator',
        'secondary' => [ 'debt consolidation loan calculator', 'should I consolidate my debt', 'debt consolidation savings calculator', 'is debt consolidation worth it', 'credit card consolidation calculator' ],
        'title'     => 'Debt Consolidation Calculator: Compare & Save',
        'meta'      => 'Free debt consolidation calculator: enter your debts and a loan offer to see the real savings, true APR with fees and how long you will be in debt.',
        'excerpt'   => 'Enter up to ten debts and a consolidation loan offer to see whether combining them actually saves you money.',
        'answer'    => 'Debt consolidation saves money only if the new loan’s total interest plus fees is lower than what you would pay on your current debts. Consolidating $16,500 of credit card and medical debt into an 11% five-year loan with a 3% fee saves about $1,737, but it stretches repayment by 6 months.',
        'steps'     => [
            'List each debt with its balance, APR and minimum monthly payment. Add more rows if you need them (up to ten).',
            'Enter the consolidation loan’s APR, term and origination fee.',
            'Tick the box if you will keep paying your current total payment toward the new loan to finish sooner.',
            'Compare the interest and fees, the monthly payments and the time until you are debt-free.',
            'Check the true APR of the new loan. If it is higher than the average APR of your debts, consolidating will cost more.',
        ],
        'formula'   => '<p><strong>Current cost:</strong> each debt is paid with its own minimum until it is gone; the calculator adds the interest on all of them.</p><p><strong>New loan amount = debts ÷ (1 − fee%)</strong>, because the fee is deducted from what the lender sends to your creditors. <strong>Payment = L × r ÷ (1 − (1 + r)<sup>−n</sup>)</strong>.</p><p><strong>Net savings = current interest − (new loan interest + fee)</strong>. The true APR is the rate that makes your payments equal the debt actually paid off.</p>',
        'example'   => '<p>Three debts totaling $16,500:</p><ul><li>Credit card 1: $8,000 at 22.9%, $240 minimum</li><li>Credit card 2: $5,500 at 19.99%, $165 minimum</li><li>Medical bill: $3,000 at 0%, $100 payment</li></ul><p>Paying them as they are: <strong>$505 a month</strong>, done in <strong>4 years 6 months</strong>, about <strong>$7,428</strong> in interest. An 11% five-year loan with a 3% fee: <strong>$370 a month</strong>, done in <strong>5 years</strong>, <strong>$5,691</strong> in interest and fee. Net savings <strong>$1,737</strong>, true APR <strong>12.34%</strong>. If you keep paying $505 a month on the new loan, you finish sooner and save more.</p>',
        'sections'  => [
            [ 'When debt consolidation helps', '<p>It helps most when you replace high-APR credit card debt with a lower fixed rate, can avoid a large fee, and stop adding new balances. A single fixed payment also simplifies budgeting. The calculator shows both the money saved and the extra months, so you can tell whether savings come from a lower rate or only from a longer term.</p>' ],
            [ 'When it does not', '<p>Consolidation costs more if the new APR is similar to your current rates, the fee is high (a 10% fee erases most savings), or the longer term adds interest. It also does nothing about the spending that created the debt. If your debts are already at low interest, or you can pay them off within two years, it may not be worth a new loan.</p>' ],
            [ 'Ways to consolidate', '<ul><li><strong>Personal loan:</strong> fixed rate and payment, often with an origination fee of 1–10%.</li><li><strong>Balance transfer card:</strong> a 0% intro offer with a transfer fee, typically 3–5%, that works if you can repay before the promotion ends.</li><li><strong>Home equity loan or HELOC:</strong> lower rates, but your home becomes collateral.</li><li><strong>Debt management plan:</strong> arranged through a nonprofit credit counselor, who may negotiate lower rates.</li></ul>' ],
            [ 'Effect on your credit', '<p>Applying creates a hard inquiry and a new account, which can lower your score slightly at first. Paying off credit cards lowers your utilization, which often raises your score over time if you do not run the cards up again.</p>' ],
        ],
        'terms'     => [
            [ 'Consolidation loan', 'A single new loan used to pay off several existing debts.' ],
            [ 'Origination fee', 'A fee charged for making the loan, usually a percentage of the amount.' ],
            [ 'Balance transfer', 'Moving a card balance to a new card, often with a fee and an introductory rate.' ],
            [ 'Utilization', 'The share of your revolving credit limits you are using.' ],
            [ 'Debt management plan', 'A repayment program run by a credit-counseling agency.' ],
        ],
        'faqs'      => [
            [ 'Is debt consolidation worth it?', 'It is worth it when the total interest and fees on the new loan are lower than what you would pay on your current debts, and you can afford the payment. The calculator shows the net savings and the true APR.' ],
            [ 'How do I know if I should consolidate my debt?', 'Compare the average APR of your debts with the new loan’s true APR (rate plus fee). If the new APR is meaningfully lower and the term is not much longer, consolidation probably saves money.' ],
            [ 'Does debt consolidation hurt your credit?', 'There is usually a small, temporary dip from the credit inquiry and new account. Paying down credit cards can improve your utilization and help your score over time.' ],
            [ 'What is the best way to consolidate credit card debt?', 'For many borrowers it is a fixed-rate personal loan with a low or no origination fee, or a 0% balance transfer card if you can repay within the promotional period. Compare both with this calculator.' ],
            [ 'Can I consolidate different types of debt?', 'Yes. Personal loans can combine credit cards, medical bills and other unsecured debts. Federal student loans have their own consolidation program, and converting them to a private loan removes federal protections.' ],
            [ 'What fees should I watch for?', 'Origination fees (1–10%), balance transfer fees (3–5%), prepayment penalties and closing costs on home equity products. The calculator includes the origination fee in the true APR.' ],
            [ 'What if my minimum payments are too low to ever pay off the debt?', 'If a minimum payment barely covers the monthly interest, the balance barely falls. The calculator flags this, and a fixed-term loan or a higher payment can get you out of debt.' ],
        ],
        'sources'   => [
            [ 'FTC: Coping with debt', 'https://consumer.ftc.gov/articles/coping-debt' ],
            [ 'CFPB: Regulation Z, determination of the annual percentage rate', 'https://www.consumerfinance.gov/rules-policy/regulations/1026/22/' ],
        ],
        'related'   => [ 'personal-loan-calculator', 'loan-comparison-calculator', 'home-equity-loan-calculator', 'amortization-calculator' ],
    ],

    /* ───────────────────────── LOAN COMPARISON ───────────────────────── */
    'loan-comparison-calculator' => [
        'focus'     => 'loan comparison calculator',
        'secondary' => [ 'compare two loans', 'loan APR calculator', 'which loan is cheaper', 'compare loan offers', 'loan cost calculator' ],
        'title'     => 'Loan Comparison Calculator by APR',
        'meta'      => 'Free loan comparison calculator: compare up to three loans side by side on payment, true APR with fees, total interest and total cost.',
        'excerpt'   => 'Compare up to three loan offers side by side on monthly payment, true APR with fees, interest and total cost.',
        'answer'    => 'The cheapest loan is the one with the lowest total cost, meaning interest plus fees, not necessarily the lowest rate or payment. Comparing a $20,000 loan at 7.5% with no fee against 6.9% with a $600 fee over 60 months, the lower-rate loan has a true APR of 8.18% and costs $259 more in total.',
        'steps'     => [
            'Enter the amount, interest rate, term in months and upfront fees for Loan A and Loan B.',
            'Tick "include" to add a third loan.',
            'Read the comparison table: monthly payment, true APR, total interest, fees and total cost of borrowing.',
            'The best value in each row is highlighted. Read the note beneath for the plain-language verdict.',
        ],
        'formula'   => '<p>Each loan’s payment is <strong>P × r ÷ (1 − (1 + r)<sup>−n</sup>)</strong>.</p><p><strong>Total cost of borrowing = total interest + upfront fees.</strong></p><p><strong>True APR</strong> is the rate at which the payments equal the amount you actually receive (loan amount minus fees). It lets you compare loans with different rates and fees on equal terms.</p>',
        'example'   => '<p>Both loans are $20,000 over 60 months:</p><table><thead><tr><th></th><th>Loan A</th><th>Loan B</th></tr></thead><tbody><tr><td>Rate</td><td>7.5%</td><td>6.9%</td></tr><tr><td>Upfront fee</td><td>$0</td><td>$600</td></tr><tr><td>Monthly payment</td><td>$400.76</td><td>$395.08</td></tr><tr><td>Total interest</td><td>$4,046</td><td>$3,705</td></tr><tr><td>Total cost (interest + fee)</td><td>$4,046</td><td>$4,305</td></tr><tr><td>True APR</td><td>7.50%</td><td>8.18%</td></tr></tbody></table><p>Loan B has the lower rate and payment, but Loan A is $259 cheaper overall.</p>',
        'sections'  => [
            [ 'Look at APR and total cost, not just the rate', '<p>A lower rate can hide a higher price. Fees, points and insurance add to what you pay, and a longer term can raise total interest even when the payment is smaller. Total cost of borrowing and true APR put every offer on the same scale.</p>' ],
            [ 'Term length changes everything', '<p>Two loans with the same rate can differ by thousands of dollars if the terms differ. A 72-month loan has a smaller payment than a 48-month loan but charges interest for two more years. If you are comparing offers with different terms, use total cost rather than the payment.</p>' ],
            [ 'What else to compare', '<ul><li>Prepayment penalties: can you pay off early without a fee?</li><li>Fixed or variable rate: variable loans can get more expensive.</li><li>Lender reputation and customer service.</li><li>Funding speed and whether the lender requires autopay to keep the rate.</li></ul>' ],
        ],
        'terms'     => [
            [ 'True APR', 'The yearly rate including fees, based on the cash you actually receive.' ],
            [ 'Total cost of borrowing', 'Interest plus fees over the life of the loan.' ],
            [ 'Origination fee', 'A fee charged up front, often deducted from the loan proceeds.' ],
            [ 'Prepayment penalty', 'A charge for paying the loan off ahead of schedule.' ],
        ],
        'faqs'      => [
            [ 'How do I compare two loans?', 'Compare the APR, the total cost of borrowing (interest plus fees), the monthly payment and the term. The loan with the lowest total cost is the cheapest overall; the loan with the lowest payment is easiest month to month.' ],
            [ 'Which is better, a lower rate or a lower fee?', 'Neither on its own. Use the true APR and total cost. On a $20,000 five-year loan, a $600 fee raised the APR of a 6.9% loan to 8.18%.' ],
            [ 'What is APR and how is it different from the interest rate?', 'APR includes the interest rate and certain fees, so it shows the full yearly cost of borrowing. The interest rate covers only interest on the principal.' ],
            [ 'Should I pick the loan with the lowest monthly payment?', 'Only if you need the cash flow. A lower payment often means a longer term and more total interest. The calculator shows both.' ],
            [ 'Can I compare loans with different terms?', 'Yes. Enter each term in months. The total cost row compares them fairly, but remember a longer loan leaves you in debt longer.' ],
            [ 'Does this work for mortgages, car loans and personal loans?', 'Yes, for any fixed-rate installment loan. Variable-rate loans can change, so treat their results as an estimate.' ],
        ],
        'sources'   => [
            [ 'CFPB: Regulation Z, determination of the annual percentage rate', 'https://www.consumerfinance.gov/rules-policy/regulations/1026/22/' ],
        ],
        'related'   => [ 'personal-loan-calculator', 'auto-loan-calculator', 'mortgage-calculator', 'debt-consolidation-calculator' ],
    ],

    /* ───────────────────────── AMORTIZATION ───────────────────────── */
    'amortization-calculator' => [
        'focus'     => 'amortization calculator',
        'secondary' => [ 'amortization schedule calculator', 'loan amortization calculator', 'amortization calculator with extra payments', 'loan payoff schedule', 'how does amortization work' ],
        'title'     => 'Amortization Calculator with Extra Payments',
        'meta'      => 'Free amortization calculator: build a full monthly or annual schedule, add extra payments, see interest saved, and download the table as CSV.',
        'excerpt'   => 'Build a full amortization schedule for any fixed-rate loan, add extra payments and see the interest and time you save.',
        'answer'    => 'Amortization is paying off a loan in equal installments that split between interest and principal. On a $250,000 loan at 7% for 30 years, the payment is $1,663.26 a month and total interest is $348,772. In the first payment, $1,458 is interest and only $205 reduces the balance.',
        'steps'     => [
            'Enter the loan amount, interest rate and term in years and months.',
            'Choose the first payment month.',
            'Optionally add extra monthly payments, a yearly extra payment, or a one-time lump sum.',
            'Review the payoff date, interest saved and the schedule. Switch between monthly and annual views.',
            'Download the schedule as a CSV file to open in a spreadsheet.',
        ],
        'formula'   => '<p><strong>Payment = P × r ÷ (1 − (1 + r)<sup>−n</sup>)</strong></p><p>Each month: <strong>interest = balance × r</strong>, <strong>principal = payment − interest</strong>, <strong>new balance = balance − principal</strong>. Extra payments reduce the balance directly, so every later month charges less interest.</p>',
        'example'   => '<p>$250,000 at 7% for 30 years, first payment in a month of your choice:</p><ul><li>Monthly payment: <strong>$1,663.26</strong></li><li>Total paid: <strong>$598,772</strong>; total interest: <strong>$348,772</strong> (139.5% of the loan)</li><li>First payment: <strong>$1,458.33</strong> interest, <strong>$204.92</strong> principal</li><li>Half the loan is repaid at payment <strong>#261</strong>, after 21 years 9 months</li></ul><p>Adding $200 a month pays the loan off in 263 payments (21 years 11 months) and saves about <strong>$109,800</strong> in interest.</p>',
        'sections'  => [
            [ 'How amortization works', '<p>On a fixed-rate loan the payment never changes, but its makeup does. Interest is charged on the remaining balance, so early payments are mostly interest. As the balance drops, interest shrinks and more of the same payment goes to principal. That is why the schedule starts with a slow decline and accelerates later.</p>' ],
            [ 'The power of extra payments', '<p>An extra payment goes straight to principal, which removes the interest it would have earned for the rest of the loan. Extra money paid in the first years saves the most. You can test monthly extras, one extra payment each year (for example a tax refund or bonus) or a single lump sum.</p>' ],
            [ 'Reading an amortization schedule', '<p>Each row shows the payment date, the total payment, how much reduced the balance, how much was interest and the new balance. The annual view summarizes each calendar year, which is useful for tax planning on loans with deductible interest. Use the CSV download to explore scenarios in a spreadsheet.</p>' ],
            [ 'What amortizes, and what does not', '<p>Mortgages, auto loans, personal loans and student loans are fully amortizing. Credit cards and interest-only loans are not, because the required payment may not reduce the balance. Some loans end with a balloon payment, where the last payment is larger than the rest.</p>' ],
        ],
        'terms'     => [
            [ 'Amortization', 'Gradual repayment of a loan through scheduled payments of principal and interest.' ],
            [ 'Principal', 'The amount borrowed that remains unpaid.' ],
            [ 'Balloon payment', 'A large final payment due at the end of a loan that did not fully amortize.' ],
            [ 'Interest-only period', 'A stretch of a loan when you pay interest and no principal.' ],
            [ 'Payoff date', 'The month your balance reaches zero.' ],
        ],
        'faqs'      => [
            [ 'What is an amortization schedule?', 'It is a table listing every loan payment with the amount applied to interest, the amount applied to principal and the remaining balance.' ],
            [ 'How do I calculate loan amortization?', 'Compute the fixed payment with P times the monthly rate divided by 1 minus (1 + monthly rate) to the negative number of payments. Each month, charge interest on the remaining balance, subtract it from the payment to get principal, and reduce the balance by that principal.' ],
            [ 'Why is most of my early payment interest?', 'Interest is calculated on the outstanding balance, which is highest at the start. On a $250,000 loan at 7%, the first month’s interest is $1,458 of a $1,663 payment.' ],
            [ 'How much do extra payments save?', 'On $250,000 at 7% for 30 years, $200 extra each month saves about $109,800 and cuts the loan by about 8 years 1 month.' ],
            [ 'What is the difference between monthly and annual schedules?', 'The monthly schedule lists every payment. The annual view totals payments, principal, interest and ending balance for each calendar year.' ],
            [ 'Can I use this for a mortgage or auto loan?', 'Yes, for any fixed-rate loan. For mortgage-specific costs such as taxes and PMI, use the mortgage calculator.' ],
            [ 'How do I download the schedule?', 'Use the Download CSV button above the schedule. It exports every payment so you can open it in Excel or Google Sheets.' ],
        ],
        'sources'   => [
            [ 'CFPB: Learn how mortgage payments and amortization work', 'https://www.consumerfinance.gov/owning-a-home/' ],
        ],
        'related'   => [ 'mortgage-calculator', 'refinance-calculator', 'personal-loan-calculator', 'student-loan-calculator' ],
    ],

    /* ───────────────────────── REFINANCE ───────────────────────── */
    'refinance-calculator' => [
        'focus'     => 'refinance calculator',
        'secondary' => [ 'mortgage refinance calculator', 'refinance break even calculator', 'should I refinance my mortgage', 'cash out refinance calculator', 'refinance closing costs' ],
        'title'     => 'Refinance Calculator: Break-Even & Savings',
        'meta'      => 'Free mortgage refinance calculator: see your new payment, break-even month, net savings after closing costs and points, and the effect of cash-out.',
        'excerpt'   => 'Find your new payment, break-even point and real savings after closing costs, points and a longer loan term.',
        'answer'    => 'Refinancing pays off when the savings after closing costs are positive for as long as you keep the loan. Refinancing a $250,000 balance from 7.25% with 27 years left to 6.0% over 30 years with $4,500 in costs cuts the payment by $262 a month and breaks even in 18 months, for about $16,117 net savings after 7 years.',
        'steps'     => [
            'Enter your remaining balance, current rate and years remaining. Add your current payment if you know it.',
            'Enter the new rate and term, plus points and closing costs.',
            'Choose whether to roll costs into the new loan and whether you want cash out.',
            'Set how many years you expect to keep the loan.',
            'Read the break-even point and the net savings at your horizon.',
        ],
        'formula'   => '<p><strong>Break-even month</strong> is the first month when the total you have paid on the new loan plus its remaining balance, including closing costs, is lower than on the old loan.</p><p>A simple estimate is <strong>closing costs ÷ monthly savings</strong>: $4,500 ÷ $262 ≈ 17 months. The calculator uses the fuller method because a longer new term leaves more balance outstanding.</p><p><strong>Net savings = (old payments + old balance) − (new payments + new balance + upfront costs − cash out)</strong> measured at your horizon.</p>',
        'example'   => '<p>Balance $250,000 at 7.25% with 27 years left (payment $1,760) refinanced to 6.0% for 30 years with $4,500 of closing costs:</p><ul><li>New payment: <strong>$1,499</strong>; savings: <strong>$261.61 a month</strong></li><li>Break-even: <strong>18 months</strong></li><li>Net savings after 7 years: <strong>about $16,117</strong></li><li>Lifetime interest: $320,397 on the current loan vs $289,595 on the new one, though the new loan runs 3 years longer</li></ul>',
        'sections'  => [
            [ 'When does refinancing make sense?', '<p>Refinancing usually makes sense when you can lower your rate enough to recover closing costs within the time you will own the home, shorten your term without straining your budget, switch from an adjustable to a fixed rate, or remove mortgage insurance. It does not make sense if you expect to move or sell before the break-even month.</p>' ],
            [ 'Closing costs and points', '<p>Refinance closing costs typically run 2–6% of the loan and include origination, appraisal, title and recording fees. Points are optional upfront interest: one point costs 1% of the loan and usually lowers the rate by a fraction of a percent. Buying points helps only if you keep the loan past their own break-even.</p>' ],
            [ 'Watch out for term resets', '<p>If you have 22 years left and refinance into a new 30-year loan, your payment falls but you add eight years of payments. Compare total interest, not only the monthly savings. Choosing a shorter term, or making extra payments, keeps the original payoff date.</p>' ],
            [ 'Cash-out refinancing', '<p>A cash-out refinance borrows more than you owe and pays you the difference. It can fund renovations or consolidate debt, but it raises your balance and replaces a low-rate mortgage with today’s rate. Compare with a home equity loan, which leaves your first mortgage untouched.</p>' ],
        ],
        'terms'     => [
            [ 'Break-even point', 'The month when cumulative savings equal what you paid to refinance.' ],
            [ 'Points', 'Prepaid interest: each point costs 1% of the loan amount.' ],
            [ 'Rate-and-term refinance', 'A refinance that changes only the rate or term, without taking cash out.' ],
            [ 'Cash-out refinance', 'A refinance for more than the current balance, paid to you in cash.' ],
            [ 'Closing costs', 'Fees paid to complete a loan, including lender, appraisal and title charges.' ],
        ],
        'faqs'      => [
            [ 'How do I know if I should refinance?', 'Refinance if your net savings are positive over the time you plan to keep the loan. Compare the break-even month with how long you will own the home, and check total interest if the new term is longer.' ],
            [ 'How do you calculate the break-even point on a refinance?', 'A quick estimate is closing costs divided by monthly savings. The calculator uses a more precise comparison of what you would have paid, plus the remaining balance, on each loan over time.' ],
            [ 'How much do refinance closing costs cost?', 'Typically 2–6% of the loan amount, covering lender fees, appraisal, title insurance and recording charges. You can pay them upfront or roll them into the loan.' ],
            [ 'Is it worth refinancing for a 1% lower rate?', 'Often yes, if you will stay long enough to recover costs. On a $250,000 loan, a 1.25-point rate drop saves about $262 a month in the example above, and recovers $4,500 in 18 months.' ],
            [ 'Should I roll closing costs into my loan?', 'It avoids cash at closing but increases the loan balance and the interest you pay. If you will keep the loan a long time, paying costs upfront is usually cheaper.' ],
            [ 'Does refinancing restart my loan term?', 'It does when you take a new 30-year loan. Choose a shorter term or pay extra to avoid adding years to your payoff date.' ],
            [ 'What is a cash-out refinance?', 'It replaces your mortgage with a larger one and pays you the extra as cash. It raises your balance and payment, so use it for purposes that are worth the cost.' ],
        ],
        'sources'   => [
            [ 'Freddie Mac weekly mortgage rates', 'https://www.freddiemac.com/pmms' ],
            [ 'CFPB: Owning a home tools and resources', 'https://www.consumerfinance.gov/owning-a-home/' ],
        ],
        'related'   => [ 'mortgage-calculator', 'amortization-calculator', 'home-equity-loan-calculator', 'fha-loan-calculator' ],
    ],

    /* ───────────────────────── FHA ───────────────────────── */
    'fha-loan-calculator' => [
        'focus'     => 'FHA loan calculator',
        'secondary' => [ 'FHA mortgage calculator', 'FHA MIP calculator', 'FHA loan limits 2026', 'FHA down payment', 'FHA mortgage insurance premium' ],
        'title'     => 'FHA Loan Calculator with MIP & PITI (2026)',
        'meta'      => 'Free FHA loan calculator: monthly payment with upfront and annual MIP, taxes and insurance, 2026 loan limits, down payment options and cash to close.',
        'excerpt'   => 'Estimate your full FHA payment with upfront and annual MIP, taxes and insurance, and see how your down payment changes the cost.',
        'answer'    => 'An FHA loan allows 3.5% down with a 580+ credit score, but charges an upfront mortgage insurance premium of 1.75% and an annual MIP. On a $350,000 home with 3.5% down at 6.75%, the loan is $343,661 with financed MIP and the total payment is about $2,884 a month including taxes and insurance.',
        'steps'     => [
            'Enter the home price and your down payment percentage. The minimum is 3.5% (580+ credit score) or 10% (500–579).',
            'Choose your credit range, rate and loan term.',
            'Enter your county’s FHA loan limit. The 2026 floor is $541,287 and the high-cost ceiling is $1,249,125.',
            'Add property tax, insurance and HOA dues.',
            'Keep the upfront MIP financed or pay it at closing, and read the monthly MIP and how long it lasts.',
        ],
        'formula'   => '<p><strong>Base loan = price − down payment.</strong> Upfront MIP = 1.75% of the base loan, usually financed, so the loan amount is 1.0175 × base loan.</p><p><strong>Annual MIP = base loan × MIP rate ÷ 12</strong> per month. For 30-year loans of $726,200 or less, the rate is 0.55% when LTV is above 95% and 0.50% at 95% or below. For 15-year loans it is 0.40% above 90% LTV and 0.15% at 90% or below.</p><p><strong>Duration:</strong> with LTV above 90%, MIP lasts the life of the loan; at 90% or below, 11 years.</p>',
        'example'   => '<p>$350,000 home, 3.5% down ($12,250), 6.75%, 30 years, $4,200 tax and $1,800 insurance a year:</p><ul><li>Base loan: <strong>$337,750</strong>; upfront MIP: <strong>$5,911</strong> (financed); loan amount <strong>$343,661</strong></li><li>Principal and interest: <strong>$2,229</strong></li><li>Annual MIP 0.55% = about <strong>$155 a month</strong> in year one</li><li>Total payment: <strong>about $2,884</strong>; MIP lasts the life of the loan</li></ul><p>Raise the down payment to 10% and the annual MIP rate drops to 0.50% and ends after 11 years, cutting the total payment to about $2,710.</p>',
        'sections'  => [
            [ 'FHA loan requirements', '<p>FHA loans are insured by the Federal Housing Administration and issued by approved lenders. You need a 580 credit score for a 3.5% down payment or 500–579 with 10% down, steady income and a debt-to-income ratio that typically stays under 43%, though lenders can approve higher ratios with compensating factors. The home must be your primary residence and meet FHA property standards.</p>' ],
            [ '2026 FHA loan limits', '<p>HUD set the 2026 single-family floor at $541,287 and the ceiling for high-cost counties at $1,249,125. Your county’s limit depends on local home prices, so look it up before you shop. The floor equals 65% of the $832,750 conforming loan limit and the ceiling equals 150% of it.</p>' ],
            [ 'FHA mortgage insurance (MIP)', '<p>FHA charges two kinds of mortgage insurance. The upfront premium is 1.75% of the base loan amount, most often financed into the loan. The annual premium is paid monthly and depends on loan term, loan amount and loan-to-value. With less than 10% down it lasts the life of the loan; with 10% or more down it ends after 11 years. Unlike conventional PMI, FHA MIP applies even with a 20% down payment.</p>' ],
            [ 'FHA vs conventional', '<p>FHA suits buyers with lower credit scores or smaller down payments. A conventional loan can be cheaper if you have a score above about 700 and at least 5% down, because PMI drops off once you reach 20% equity. Compare both with the mortgage calculator, and use this page to see the cost of FHA MIP over time.</p>' ],
            [ 'Getting rid of FHA mortgage insurance', '<p>If you put down less than 10%, the usual ways to end MIP are to refinance into a conventional loan once your equity is above 20% or to sell. Watch closing costs and current rates before you refinance, and use the refinance calculator.</p>' ],
        ],
        'terms'     => [
            [ 'UFMIP', 'Upfront mortgage insurance premium, 1.75% of the base loan on FHA purchases.' ],
            [ 'Annual MIP', 'Monthly mortgage insurance premium, based on term, amount and LTV.' ],
            [ 'Base loan amount', 'The loan before the financed upfront premium is added.' ],
            [ 'FHA loan limit', 'The maximum FHA loan amount in a county.' ],
            [ 'DTI', 'Debt-to-income ratio: monthly debts divided by gross monthly income.' ],
        ],
        'faqs'      => [
            [ 'How much is the down payment on an FHA loan?', 'The minimum is 3.5% with a credit score of 580 or higher and 10% for scores from 500 to 579. On a $350,000 home, 3.5% is $12,250.' ],
            [ 'How much is FHA mortgage insurance?', 'There is a 1.75% upfront premium plus an annual premium, typically 0.50% to 0.55% for 30-year loans under $726,200. On a $337,750 base loan, upfront MIP is $5,911 and annual MIP is about $155 a month.' ],
            [ 'How long do you pay MIP on an FHA loan?', 'If your down payment is less than 10% (LTV above 90%), you pay MIP for the life of the loan. With 10% or more down, it ends after 11 years.' ],
            [ 'Do you pay MIP with 20% down on an FHA loan?', 'Yes. FHA mortgage insurance is required on all FHA loans regardless of the down payment, unlike conventional PMI.' ],
            [ 'What are the FHA loan limits for 2026?', 'The single-family floor is $541,287 and the ceiling in high-cost areas is $1,249,125. Check your county on HUD’s lookup tool.' ],
            [ 'What credit score do I need for an FHA loan?', 'A minimum of 580 for a 3.5% down payment, or 500 to 579 with 10% down. Lenders may require higher scores.' ],
            [ 'Is an FHA loan better than a conventional loan?', 'It depends on your credit and down payment. FHA is easier to qualify for, but its MIP can last the life of the loan. With good credit and 5% or more down, a conventional loan is often cheaper.' ],
            [ 'Can I get rid of FHA mortgage insurance?', 'With under 10% down, you generally need to refinance into a non-FHA loan once you have enough equity. With 10% or more down, MIP ends after 11 years.' ],
        ],
        'sources'   => [
            [ 'HUD Mortgagee Letter 2023-05 (annual MIP rates)', 'https://www.hud.gov/sites/dfiles/OCHCO/documents/2023-05hsgml.pdf' ],
            [ 'HUD: look up FHA loan limits by county', 'https://entp.hud.gov/idapp/html/hicostlook.cfm' ],
            [ 'FHFA: 2026 conforming loan limits', 'https://www.fhfa.gov/news/news-release/fhfa-announces-conforming-loan-limit-values-for-2026' ],
        ],
        'related'   => [ 'mortgage-calculator', 'refinance-calculator', 'amortization-calculator', 'loan-comparison-calculator' ],
    ],

    ];

    require_once get_template_directory() . '/inc/tool-content-data-2.php';
    $data = array_merge( $data, fs_tool_content_data_2() );

    return $data;
}
