<?php
/**
 * Editorial content + keywords, batch 8: negative equity car loan, cash back vs low APR.
 * Example numbers match the calculators in calculators-money-3.php with the inputs stated.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_tool_content_data_8() {
    return [

    'negative-equity-car-loan-calculator' => [
        'focus'     => 'negative equity car loan calculator',
        'secondary' => [ 'auto loan with negative equity calculator', 'upside down car loan calculator', 'trade in with negative equity calculator', 'how much negative equity can i roll over' ],
        'title'     => 'Negative Equity Car Loan Calculator (Trade-In)',
        'meta'      => 'Free negative equity calculator: see how much you owe above your car’s value, what rolling it into a new loan does to your payment, and the extra cost.',
        'excerpt'   => 'Work out your equity, the new loan amount and what rolling negative equity into a new car loan really costs.',
        'answer'    => 'If your car is worth $12,000 and you owe $16,000, you have $4,000 of negative equity. Rolling it into a $28,000 car with $2,000 down, 6% sales tax and $500 in fees makes the new loan $32,180, or 115% of the car’s price. At 7.5% over 72 months the payment is about $556.40 a month, which is $69.16 more than it would be without the old debt, or about $4,980 extra over the loan.',
        'steps'     => [ 'Enter your car’s trade-in value and the loan payoff amount.', 'Enter the new car price, your cash down payment, sales tax and fees.', 'Enter the loan APR and term in months.', 'Read your equity, the new loan, the payment and the extra cost of any negative equity rolled in.' ],
        'formula'   => '<p><strong>Equity = trade-in value − loan payoff.</strong> A negative number is negative equity (being upside down).</p><p><strong>New loan = price × (1 + sales tax) + fees − down payment + negative equity</strong>. Positive equity is subtracted instead, because it acts like a down payment. The monthly payment uses the standard loan formula, and the extra cost compares the payment with and without the rolled-in amount.</p>',
        'example'   => '<p>Trade-in $12,000, payoff $16,000, new car $28,000, $2,000 down, 6% tax, $500 fees, 7.5% for 72 months:</p><ul><li>Equity: <strong>−$4,000</strong></li><li>New loan: <strong>$32,180</strong> (115% of the price); without the old debt it would be $28,180</li><li>Payment: <strong>$556.40</strong> a month vs <strong>$487.24</strong>, so <strong>$69.16</strong> more</li><li>Extra paid over 72 months because of the rolled-in debt: about <strong>$4,980</strong> (the $4,000 plus about $980 of interest)</li></ul>',
        'sections'  => [
            [ 'What negative equity means', '<p>You have negative equity, also called being upside down or underwater, when you owe more on the loan than the car is worth. Cars lose value fastest in the first years, while a long loan with a small down payment pays down the balance slowly, so the gap is common.</p>' ],
            [ 'What happens if you roll it into a new loan', '<p>The old balance is added to the new loan. You pay interest on it again, and you start the new loan owing more than the new car is worth, so you are underwater from day one. Many lenders also limit how much they will lend compared with the car’s value, so a large gap can get a trade-in declined or push the rate up.</p>' ],
            [ 'Ways to shrink the gap', '<p>Pay extra toward the principal, put cash toward the shortfall at the dealer, wait while the loan balance falls faster than the car’s value, or sell the car yourself, since private buyers often pay more than a dealer’s trade-in offer. Our <a href="/tool/loan-payoff-calculator/">loan payoff calculator</a> shows how much extra payments save.</p>' ],
            [ 'Positive equity works in your favor', '<p>If the car is worth more than you owe, the difference reduces what you borrow. Enter a higher trade-in value than the payoff and the calculator subtracts the equity from the new loan.</p>' ],
        ],
        'terms'     => [ [ 'Negative equity', 'Owing more on a loan than the asset is worth.' ], [ 'Payoff amount', 'The exact figure needed to close the loan today, from your lender.' ], [ 'Loan-to-value (LTV)', 'The loan amount as a percentage of the car’s value or price.' ], [ 'GAP insurance', 'Coverage that pays the difference between what you owe and the car’s value if it is totaled or stolen.' ] ],
        'faqs'      => [
            [ 'How do I calculate negative equity on a car?', 'Subtract the loan payoff amount from the car’s trade-in value. If the result is negative, that is your negative equity.' ],
            [ 'Can I trade in a car with negative equity?', 'Usually yes, but the dealer adds the shortfall to the new loan unless you pay it in cash. That raises the loan amount, the payment and the interest you pay.' ],
            [ 'Is it a bad idea to roll negative equity into a new loan?', 'It is expensive because you borrow and pay interest on the old balance, and you start the new loan underwater. It can be worth it only when the new loan is cheaper overall and you can afford the higher payment.' ],
            [ 'Does GAP insurance cover negative equity on a trade-in?', 'No. GAP covers the shortfall if the car is totaled or stolen, not the shortfall when you trade it in.' ],
            [ 'How much do cars depreciate?', 'New cars commonly lose a large share of their value in the first year, often around 20%, then continue to lose value each year. Actual figures vary a lot by model.' ],
        ],
        'sources'   => [ [ 'CFPB: Auto loans', 'https://www.consumerfinance.gov/consumer-tools/auto-loans/' ], [ 'FTC: Auto trade-ins and negative equity', 'https://consumer.ftc.gov/articles/auto-trade-ins-and-negative-equity-when-you-owe-more-your-car-worth' ] ],
        'related'   => [ 'auto-loan-calculator', 'loan-payoff-calculator', 'refinance-calculator', 'cash-back-vs-low-apr-calculator', 'loan-comparison-calculator' ],
    ],

    'cash-back-vs-low-apr-calculator' => [
        'focus'     => 'cash back vs low apr calculator',
        'secondary' => [ 'rebate or low interest calculator', 'cash back or 0 financing', 'car rebate vs low apr', 'should i take the rebate or low apr' ],
        'title'     => 'Cash Back vs Low APR Calculator for Car Deals',
        'meta'      => 'Free calculator: compare a cash rebate with low-APR dealer financing on a car. See both payments, total cost, savings and the break-even promo rate.',
        'excerpt'   => 'Decide whether a car’s cash rebate or its low promotional APR is the cheaper deal.',
        'answer'    => 'On a $35,000 car with $3,000 down over 60 months, taking a $3,000 rebate and financing at your own 6.5% costs $34,045 in payments ($567.42 a month). A 1.9% dealer offer with no rebate costs $33,569 ($559.49 a month). The low-APR deal saves about $476, and the two tie at a promotional rate of about 2.46%.',
        'steps'     => [ 'Enter the price, your down payment and the loan term.', 'Option A: enter the cash rebate and the APR you can get from your own bank or credit union.', 'Option B: enter the dealer’s promotional APR.', 'Read the payments, total cost, savings and the break-even promo rate.' ],
        'formula'   => '<p><strong>Option A loan = price − rebate − down payment</strong>, financed at your own APR. <strong>Option B loan = price − down payment</strong>, financed at the promo APR.</p><p>Each payment uses the loan formula, and the totals compare payments over the term. The break-even promo rate is the rate at which Option B’s total equals Option A’s.</p>',
        'example'   => '<p>$35,000 car, $3,000 down, 60 months, $3,000 rebate, your APR 6.5%, promo 1.9%:</p><ul><li>A (rebate): loan $29,000, payment <strong>$567.42</strong>, total <strong>$34,045</strong></li><li>B (low APR): loan $32,000, payment <strong>$559.49</strong>, total <strong>$33,569</strong></li><li>B is cheaper by about <strong>$476</strong>; break-even promo APR about <strong>2.46%</strong></li></ul><p>If the promo rate were 4.5% instead, the rebate would win by about $1,749.</p>',
        'sections'  => [
            [ 'Why it is usually either-or', '<p>Manufacturers often let you choose either a cash rebate or special financing, not both. The rebate lowers the amount you borrow, while the low rate lowers the interest. Which is worth more depends on the loan size, the term and the rate you could get elsewhere.</p>' ],
            [ 'Get your own rate first', '<p>The comparison only works if you know the rate you would pay without the dealer’s offer. Ask your bank or credit union for a pre-approval before you shop. Promotional rates usually go to buyers with very good credit and are often limited to shorter terms.</p>' ],
            [ 'Longer terms favor the low rate', '<p>The longer the loan, the more interest costs add up, so a very low rate becomes more valuable. On short loans or small balances the rebate often wins. Try different terms in the calculator.</p>' ],
            [ 'Negotiate the price first', '<p>Settle on the price of the car before discussing incentives and financing, and compare the final numbers. Check whether the rebate is taxed in your state, since sales tax rules on rebates differ.</p>' ],
        ],
        'terms'     => [ [ 'APR', 'Annual percentage rate, the yearly cost of borrowing.' ], [ 'Rebate', 'A manufacturer’s cash discount on a new vehicle.' ], [ 'Break-even rate', 'The promotional rate at which both options cost the same.' ], [ 'Pre-approval', 'A lender’s offer of a loan amount and rate before you buy.' ] ],
        'faqs'      => [
            [ 'Should I take the cash rebate or the low APR?', 'Compare total cost. When the promo rate is below the break-even rate, the low APR is cheaper; when it is above, the rebate is cheaper.' ],
            [ 'Can I get both the rebate and the low rate?', 'Sometimes, but manufacturers often make them either-or. Ask the dealer and read the offer terms.' ],
            [ 'Does a longer loan term change the answer?', 'Yes. Longer terms make interest costs bigger, which usually favors the low rate.' ],
            [ 'Is 0% financing always the best deal?', 'No. A large rebate with a decent outside rate can cost less than 0% financing with no rebate. Run the numbers.' ],
        ],
        'sources'   => [ [ 'CFPB: Auto loans', 'https://www.consumerfinance.gov/consumer-tools/auto-loans/' ], [ 'FTC: Financing or leasing a car', 'https://consumer.ftc.gov/articles/financing-or-leasing-car' ] ],
        'related'   => [ 'auto-loan-calculator', 'negative-equity-car-loan-calculator', 'loan-comparison-calculator', 'personal-loan-calculator' ],
    ],

    ];
}
