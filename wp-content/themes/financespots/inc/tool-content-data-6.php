<?php
/**
 * Editorial content + keywords, batch 6: 52-week challenge, risk assessment, currency and crypto tools,
 * staking, mining, VA loan tools. Example numbers match the calculators in calculators.php with the inputs stated.
 * Currency and crypto examples use stated illustrative rates because the live tools fetch current rates.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

function fs_tool_content_data_6() {
    return [

    '52-week-savings-challenge' => [
        'focus'     => '52 week savings challenge',
        'secondary' => [ '52 week money challenge', '52 week savings plan', '52 week savings challenge calculator', 'weekly savings challenge' ],
        'title'     => '52-Week Savings Challenge Calculator',
        'meta'      => 'Free 52-week savings challenge calculator: save $1 in week 1, $2 in week 2 and so on. See your weekly amounts, running total and a custom multiplier.',
        'excerpt'   => 'Save a little more each week for a year and see the total, with a table of every week.',
        'answer'    => 'In the classic 52-week challenge you save $1 in week 1, $2 in week 2, up to $52 in week 52. The total is $1,378 over the year, an average of $26.50 a week. Doubling every amount saves $2,756.',
        'steps'     => [ 'Choose a multiplier. 1 saves $1 × the week number; 2 saves $2 × the week number.', 'Click Calculate Total.', 'Read the total saved, the weekly average and the biggest week.', 'Use the table to track each week’s deposit and the running total.' ],
        'formula'   => '<p><strong>Week n deposit = n × multiplier.</strong> The year’s total is the sum of 1 to 52 times the multiplier: <strong>52 × 53 ÷ 2 × multiplier = 1,378 × multiplier.</strong></p>',
        'example'   => '<p>Multiplier 1:</p><ul><li>Week 1: <strong>$1</strong>; week 26: <strong>$26</strong>; week 52: <strong>$52</strong></li><li>Total: <strong>$1,378</strong>; average <strong>$26.50</strong> a week</li></ul><p>Multiplier 2 gives <strong>$2,756</strong>, with a $104 deposit in week 52.</p>',
        'sections'  => [
            [ 'Why the challenge works', '<p>The first weeks are almost painless, which builds the habit. By the time deposits get large you have a track record. The plan turns a big goal into small steps.</p>' ],
            [ 'Make it easier: count down', '<p>Many people find the last weeks hard because the deposits are largest. You can reverse the order and start with $52, so the biggest amounts come when your budget is freshest, and the final weeks are the easiest.</p>' ],
            [ 'Where to keep the money', '<p>Put it in a separate high-yield savings account so it earns interest and is hard to spend by accident. The FDIC insures deposits up to $250,000 per depositor, per bank. A good first use is an emergency fund; use our emergency fund calculator to set a target.</p>' ],
        ],
        'terms'     => [ [ 'Multiplier', 'The number you multiply each week’s number by.' ], [ 'Running total', 'The sum saved so far.' ], [ 'High-yield savings account', 'A savings account that pays a higher interest rate than a standard one.' ] ],
        'faqs'      => [
            [ 'How much do you save in the 52-week challenge?', '$1,378 if you save $1 in week 1 up to $52 in week 52.' ],
            [ 'Can I do the challenge in reverse?', 'Yes. Start with $52 in week 1 and work down to $1. The total is the same, and it front-loads the hard weeks.' ],
            [ 'What if I miss a week?', 'Catch up the next week or skip that deposit. The goal is the habit, not a perfect record.' ],
            [ 'How can I save more?', 'Use a multiplier of 2 or 3, or add the interest earned in a high-yield savings account.' ],
        ],
        'sources'   => [ [ 'FDIC: Deposit insurance', 'https://www.fdic.gov/resources/deposit-insurance/' ] ],
        'related'   => [ 'savings-goal-calculator', 'emergency-fund-calculator', 'compound-interest-calculator', 'monthly-budget-planner' ],
    ],

    'risk-assessment-tool' => [
        'focus'     => 'investor risk tolerance quiz',
        'secondary' => [ 'risk assessment tool', 'investment risk profile', 'risk tolerance calculator', 'asset allocation by risk' ],
        'title'     => 'Investor Risk Tolerance Quiz and Risk Profile',
        'meta'      => 'Free risk tolerance quiz: answer four questions about your time horizon, reaction to a 20% drop and goals to get a risk profile and a sample stock and bond mix.',
        'excerpt'   => 'Answer four questions to see your investor risk profile and an example stock, bond and cash mix.',
        'answer'    => 'Four questions score your time horizon, reaction to a 20% loss, main goal and how much of your savings you are investing. A score of 10 to 12 gives a Growth profile, which this tool illustrates as roughly 80% stocks, 18% bonds and 2% cash. The default answers score 10 (Growth).',
        'steps'     => [ 'Choose your investment time horizon.', 'Say how you would react if your portfolio dropped 20%.', 'Pick your main goal and the share of your savings you are investing.', 'Click Get My Risk Profile for your profile and a sample allocation.' ],
        'formula'   => '<p>Each answer scores 1 to 4 (the goal question 1 to 3). The total decides the profile:</p><ul><li>4 to 6: <strong>Conservative</strong>, about 30% stocks, 50% bonds, 20% cash</li><li>7 to 9: <strong>Moderate</strong>, about 60% stocks, 35% bonds, 5% cash</li><li>10 to 12: <strong>Growth</strong>, about 80% stocks, 18% bonds, 2% cash</li><li>13 and above: <strong>Aggressive Growth</strong>, about 95% stocks, 5% bonds</li></ul>',
        'example'   => '<p>Defaults: 5 to 10 years (3) + hold steady in a 20% drop (3) + moderate growth (2) + investing 10% to 30% of savings (2):</p><ul><li>Score: <strong>10</strong></li><li>Profile: <strong>Growth</strong>, sample mix <strong>80 / 18 / 2</strong></li></ul>',
        'sections'  => [
            [ 'Risk tolerance vs risk capacity', '<p>Tolerance is how much volatility you can stomach. Capacity is how much loss you can afford: your income, savings, debts and how soon you need the money. Your plan should respect the lower of the two.</p>' ],
            [ 'Time horizon matters most', '<p>Money needed within a few years generally belongs in safer assets, because stocks can fall sharply in the short run. Money you will not touch for ten years or more can usually take more risk.</p>' ],
            [ 'This is an educational guide', '<p>The allocations are simple illustrations, not personalized advice. Your situation may call for something different; consider speaking with a licensed financial professional. Investor.gov explains risk tolerance and asset allocation in detail.</p>' ],
        ],
        'terms'     => [ [ 'Risk tolerance', 'How much investment volatility you are comfortable with.' ], [ 'Time horizon', 'How long until you need the money.' ], [ 'Asset allocation', 'How your money is divided between stocks, bonds and cash.' ] ],
        'faqs'      => [
            [ 'What is risk tolerance?', 'It is the amount of ups and downs in your investments that you can accept without panicking or changing your plan.' ],
            [ 'What is a good stock and bond mix?', 'It depends on your age, goals and comfort with risk. A common rule of thumb is more stocks for long horizons and more bonds as you near a goal, but there is no one right mix.' ],
            [ 'Does a higher score mean I should take more risk?', 'No. It describes your answers today. Your capacity to take losses matters too.' ],
            [ 'Is this financial advice?', 'No. It is an educational tool. Speak with a licensed advisor for personal advice.' ],
        ],
        'sources'   => [ [ 'Investor.gov: Assessing your risk tolerance', 'https://www.investor.gov/introduction-investing/getting-started/assessing-your-risk-tolerance' ] ],
        'related'   => [ 'portfolio-analyzer', 'retirement-savings-calculator', 'sharpe-ratio-calculator', 'compound-interest-calculator' ],
    ],

    'live-currency-converter' => [
        'focus'     => 'currency converter',
        'secondary' => [ 'live currency converter', 'exchange rate calculator', 'usd to eur', 'foreign exchange rate converter' ],
        'title'     => 'Live Currency Converter: Exchange Rate Calculator',
        'meta'      => 'Free currency converter with live European Central Bank reference rates for 20 major currencies. See the rate, inverse rate and converted amount instantly.',
        'excerpt'   => 'Convert between 20 major currencies using current European Central Bank reference rates.',
        'answer'    => 'Enter an amount, pick two currencies and the converter multiplies by the current reference rate. For example, if 1 USD equals 0.89 EUR, then $1,000 converts to €890. The tool loads the latest European Central Bank rates each time you open it and shows the date.',
        'steps'     => [ 'Enter the amount you want to convert.', 'Choose the currency you are converting from and to.', 'Read the converted amount, the exchange rate and the inverse rate.', 'Use Swap to reverse the direction.' ],
        'formula'   => '<p><strong>Converted amount = Amount × (rate of the target currency ÷ rate of the source currency)</strong>, with all rates measured against the same base. The inverse rate is 1 ÷ the exchange rate.</p>',
        'example'   => '<p>Illustration: if 1 USD = 0.89 EUR,</p><ul><li>$1,000 → <strong>€890</strong></li><li>Inverse: 1 EUR = <strong>1.1236 USD</strong></li></ul><p>The live tool uses the current rate, not this illustrative one.</p>',
        'sections'  => [
            [ 'Where the rates come from', '<p>Rates are the European Central Bank’s daily reference rates, served by the free Frankfurter service. They are mid-market rates published once each working day, so they will not match the minute-by-minute market exactly.</p>' ],
            [ 'Why your bank gives a different rate', '<p>Banks, card networks and money-transfer services add a margin to the mid-market rate and may charge fees. Compare the rate you are offered with the reference rate to see the real cost of an exchange.</p>' ],
            [ 'Tips for travel and transfers', '<p>Compare the total you receive, not just the headline rate. Be cautious of airport exchange desks and of paying in your home currency when a card terminal offers it, since the conversion is often less favorable.</p>' ],
        ],
        'terms'     => [ [ 'Exchange rate', 'The price of one currency in terms of another.' ], [ 'Mid-market rate', 'The midpoint between buy and sell prices, before any markup.' ], [ 'Inverse rate', 'The rate for converting in the opposite direction.' ] ],
        'faqs'      => [
            [ 'Are these rates live?', 'They are the latest European Central Bank reference rates, updated each working day, and the tool shows the date. They are not tick-by-tick trading prices.' ],
            [ 'Why is my bank’s rate different?', 'Banks and card providers add a margin and sometimes fees to the mid-market rate.' ],
            [ 'Which currencies are supported?', 'Twenty major currencies including USD, EUR, GBP, JPY, CAD, AUD, CHF, CNY and INR.' ],
            [ 'Can I use this for transactions?', 'Use it as a guide. The rate you actually get is set by your bank or provider.' ],
        ],
        'sources'   => [ [ 'European Central Bank: Euro foreign exchange reference rates', 'https://www.ecb.europa.eu/stats/policy_and_exchange_rates/euro_reference_exchange_rates/html/index.en.html' ] ],
        'related'   => [ 'crypto-converter', 'inflation-calculator', 'roi-calculator', 'present-value-calculator' ],
    ],

    'crypto-converter' => [
        'focus'     => 'crypto converter',
        'secondary' => [ 'bitcoin to usd converter', 'crypto to fiat calculator', 'btc to usd', 'cryptocurrency converter' ],
        'title'     => 'Crypto Converter: Bitcoin, Ethereum to USD and More',
        'meta'      => 'Free crypto converter with live prices from CoinGecko: convert Bitcoin, Ethereum, XRP, Solana and more to USD, EUR, GBP, INR, CAD or PKR.',
        'excerpt'   => 'Convert popular cryptocurrencies to major fiat currencies using current market prices.',
        'answer'    => 'Pick a coin, enter how many you hold and choose a currency. The value is the amount times the coin’s current price. For example, at an illustrative $80,000 per bitcoin, 0.5 BTC is worth $40,000. The tool fetches live prices from CoinGecko when you open it.',
        'steps'     => [ 'Enter the number of coins.', 'Choose the cryptocurrency and the currency to convert to.', 'Read the value and the price per coin.', 'Check the note under the fields to see whether live prices loaded.' ],
        'formula'   => '<p><strong>Value = Number of coins × Price per coin in the chosen currency.</strong></p>',
        'example'   => '<p>Illustration at $80,000 per bitcoin:</p><ul><li>0.5 BTC × $80,000 = <strong>$40,000</strong></li><li>2 ETH at $2,500 = <strong>$5,000</strong></li></ul><p>The live tool uses the current market price, not these illustrative ones.</p>',
        'sections'  => [
            [ 'Where prices come from', '<p>Prices are pulled from the free CoinGecko API, which averages prices across exchanges. Your exchange’s price can differ slightly, and prices change every second.</p>' ],
            [ 'Volatility and fees', '<p>Crypto prices can move several percent in a day. Exchanges also charge trading and withdrawal fees and may apply a spread, so the amount you receive when you sell is lower than a simple conversion.</p>' ],
            [ 'Taxes', '<p>In the United States, the IRS treats cryptocurrency as property. Selling or spending it can trigger capital gains or losses, which you report on your tax return. See our crypto profit and loss calculator and capital gains tax calculator.</p>' ],
        ],
        'terms'     => [ [ 'Fiat currency', 'Government-issued money such as the dollar or euro.' ], [ 'Spread', 'The gap between the buy and sell price on an exchange.' ], [ 'Market price', 'The most recent price at which the asset traded.' ] ],
        'faqs'      => [
            [ 'How do I convert Bitcoin to USD?', 'Multiply the number of bitcoins by the current bitcoin price in dollars.' ],
            [ 'Where do the prices come from?', 'CoinGecko’s public API, fetched when the page loads.' ],
            [ 'Why is my exchange’s price different?', 'Each exchange has its own order book and fees, so prices differ slightly.' ],
            [ 'Is converting crypto taxable?', 'Selling or trading crypto in the U.S. can create a taxable gain or loss. Converting on this calculator is only an estimate and does not trade anything.' ],
        ],
        'sources'   => [ [ 'IRS: Digital assets', 'https://www.irs.gov/filing/digital-assets' ] ],
        'related'   => [ 'crypto-pl-calculator', 'live-currency-converter', 'capital-gains-tax-calculator', 'staking-rewards-calculator' ],
    ],

    'crypto-pl-calculator' => [
        'focus'     => 'crypto profit calculator',
        'secondary' => [ 'crypto profit and loss calculator', 'bitcoin profit calculator', 'crypto roi calculator', 'crypto break even calculator' ],
        'title'     => 'Crypto Profit Calculator: P&L After Fees',
        'meta'      => 'Free crypto profit calculator: enter buy price, sell price, amount and trading fees to see net profit, ROI, total fees and your break-even price.',
        'excerpt'   => 'Work out your net profit, ROI and break-even price on a crypto trade after exchange fees.',
        'answer'    => 'Buying 0.5 BTC at $40,000 and selling at $65,000 with a 0.1% fee each way earns a net profit of about $12,447.50. That is an ROI of 62.18% on a cost of $20,020, with $52.50 in total fees. The break-even sell price is about $40,080.08.',
        'steps'     => [ 'Enter the buy price and the sell price per coin.', 'Enter how many coins you bought.', 'Enter the exchange fee as a percentage per trade.', 'Read the net profit, ROI, total fees and break-even price.' ],
        'formula'   => '<p><strong>Cost = buy price × coins × (1 + fee).</strong> <strong>Proceeds = sell price × coins × (1 − fee).</strong> <strong>Net profit = proceeds − cost.</strong> ROI = profit ÷ cost.</p><p><strong>Break-even price = buy price × (1 + fee) ÷ (1 − fee).</strong></p>',
        'example'   => '<p>0.5 coin bought at $40,000, sold at $65,000, 0.1% fee:</p><ul><li>Cost: <strong>$20,020</strong>; proceeds: <strong>$32,467.50</strong></li><li>Net profit: <strong>$12,447.50</strong>; ROI <strong>62.18%</strong></li><li>Fees paid: <strong>$52.50</strong>; break-even price <strong>$40,080.08</strong></li></ul>',
        'sections'  => [
            [ 'Fees change the break-even point', '<p>Because you pay a fee when buying and again when selling, the price must rise slightly above your purchase price before you make money. The break-even price shows exactly how far.</p>' ],
            [ 'Tax on crypto gains', '<p>The IRS treats crypto as property. Gains on assets held more than a year are generally taxed at the lower long-term capital gains rates; those held a year or less are taxed as ordinary income. Use the capital gains tax calculator to estimate the tax.</p>' ],
            [ 'What this does not include', '<p>Network (gas) fees, withdrawal fees, spread and taxes are not included unless you add them into the fee percentage. Keep records of every trade for your tax return.</p>' ],
        ],
        'terms'     => [ [ 'ROI', 'Net profit as a percentage of the cost.' ], [ 'Break-even price', 'The sell price at which profit is zero after fees.' ], [ 'Capital gain', 'The profit from selling an asset for more than you paid.' ] ],
        'faqs'      => [
            [ 'How do you calculate crypto profit?', 'Subtract your total cost, including fees, from the sale proceeds after fees.' ],
            [ 'What is the break-even price?', 'The sell price at which you recover your purchase cost and both fees.' ],
            [ 'Is crypto profit taxable?', 'Yes. In the U.S. selling crypto at a gain is a taxable event, with long-term gains taxed at lower rates than short-term ones.' ],
            [ 'Can I model different fees?', 'Yes. Change the fee percentage, for example to include an exchange spread.' ],
        ],
        'sources'   => [ [ 'IRS: Digital assets', 'https://www.irs.gov/filing/digital-assets' ], [ 'IRS Topic 409: Capital gains and losses', 'https://www.irs.gov/taxtopics/tc409' ] ],
        'related'   => [ 'crypto-converter', 'capital-gains-tax-calculator', 'roi-calculator', 'staking-rewards-calculator' ],
    ],

    'staking-rewards-calculator' => [
        'focus'     => 'staking rewards calculator',
        'secondary' => [ 'crypto staking calculator', 'staking apy calculator', 'crypto staking rewards', 'apy vs apr staking' ],
        'title'     => 'Staking Rewards Calculator: Crypto APY Earnings',
        'meta'      => 'Free staking rewards calculator: enter the amount staked, APY, period and compounding to see total rewards, final value, monthly reward and effective APY.',
        'excerpt'   => 'Estimate what you could earn from staking crypto at a given yield, with compounding.',
        'answer'    => 'Staking $10,000 at a 12% rate compounded monthly for 12 months earns about $1,268.25 in rewards, for a final value of $11,268.25. That is about $105.69 a month, and the effective APY is 12.68%. Staking yields are variable and the coin’s price can fall.',
        'steps'     => [ 'Enter the dollar value you are staking and the advertised rate.', 'Enter the staking period in months.', 'Choose how often rewards compound.', 'Read the total rewards, final value, average monthly reward and effective APY.' ],
        'formula'   => '<p><strong>Final value = Amount × (1 + rate ÷ n)<sup>n × years</sup></strong>, where n is the number of compounding periods per year. <strong>Effective APY = (1 + rate ÷ n)<sup>n</sup> − 1.</strong></p>',
        'example'   => '<p>$10,000 at 12%, monthly compounding, 12 months:</p><ul><li>Final value: <strong>$11,268.25</strong></li><li>Rewards: <strong>$1,268.25</strong> (about <strong>$105.69</strong> a month)</li><li>Effective APY: <strong>12.68%</strong></li></ul>',
        'sections'  => [
            [ 'APR vs APY', '<p>APR is the stated yearly rate without compounding. APY includes compounding, so it is higher when rewards compound more often. Enter the stated rate and choose the compounding frequency to see the APY.</p>' ],
            [ 'The calculator assumes a fixed rate', '<p>Real staking yields change with the network and how many people stake. Rewards are usually paid in the coin itself, so their dollar value moves with the price. Lock-up periods and slashing penalties can also apply.</p>' ],
            [ 'Taxes', '<p>The IRS treats staking rewards as income generally when you gain control of them, valued at their fair market value at that time. Keep records and see the IRS digital-assets guidance.</p>' ],
        ],
        'terms'     => [ [ 'Staking', 'Locking coins to help run a proof-of-stake network in return for rewards.' ], [ 'APY', 'Annual percentage yield, including compounding.' ], [ 'Slashing', 'A penalty on staked coins for validator misbehavior or downtime.' ] ],
        'faqs'      => [
            [ 'How do you calculate staking rewards?', 'Grow the staked amount by the rate each compounding period for the length of the staking period.' ],
            [ 'What is a good staking APY?', 'Yields vary by coin and change over time. Very high advertised yields can signal higher risk.' ],
            [ 'Are staking rewards taxable?', 'In the U.S., rewards are generally treated as income when you receive control of them.' ],
            [ 'Can I lose money staking?', 'Yes. The coin’s price can fall, and some networks lock your funds or apply penalties.' ],
        ],
        'sources'   => [ [ 'IRS: Digital assets', 'https://www.irs.gov/filing/digital-assets' ] ],
        'related'   => [ 'crypto-pl-calculator', 'crypto-converter', 'compound-interest-calculator', 'mining-profitability-calculator' ],
    ],

    'mining-profitability-calculator' => [
        'focus'     => 'bitcoin mining profitability calculator',
        'secondary' => [ 'bitcoin mining calculator', 'mining profit calculator', 'asic mining calculator', 'bitcoin mining break even' ],
        'title'     => 'Bitcoin Mining Profitability Calculator',
        'meta'      => 'Free Bitcoin mining calculator: enter hash rate, power, electricity cost, network hash rate and block reward to see daily profit and the break-even price.',
        'excerpt'   => 'Estimate daily and monthly Bitcoin mining profit after electricity and pool fees.',
        'answer'    => 'A 110 TH/s miner using 3,250 W at $0.12 per kWh on a 900 EH/s network with a 3.125 BTC block reward mines about 0.0000545 BTC a day. At $80,000 per bitcoin that is about $4.36 of revenue against $9.36 of electricity, a loss of about $5.00 a day. The break-even price is about $171,901.',
        'steps'     => [ 'Enter your miner’s hash rate and power use.', 'Enter your electricity price per kWh.', 'Enter the network hash rate (from mempool.space), the Bitcoin price and the block reward.', 'Enter the pool fee and read the daily profit, monthly profit and break-even price.' ],
        'formula'   => '<p><strong>BTC per day = your hash rate ÷ network hash rate × 144 blocks × block reward × (1 − pool fee).</strong></p><p><strong>Revenue = BTC per day × price. Electricity per day = kW × 24 × price per kWh. Profit = revenue − electricity.</strong> Break-even price = electricity ÷ BTC per day.</p>',
        'example'   => '<p>110 TH/s, 3,250 W, $0.12/kWh, 900 EH/s network, 3.125 BTC reward, 1% fee, $80,000 BTC:</p><ul><li>Mined: <strong>0.00005445 BTC</strong> a day</li><li>Revenue: <strong>$4.36</strong>; electricity: <strong>$9.36</strong></li><li>Profit: <strong>−$5.00</strong> a day (about <strong>−$150</strong> a month)</li><li>Break-even price: <strong>$171,901</strong></li></ul>',
        'sections'  => [
            [ 'Electricity decides profitability', '<p>The biggest cost is power. At $0.12 per kWh an older, less efficient machine often loses money, while cheap electricity and efficient hardware can still earn a profit. Compare machines by efficiency in joules per terahash.</p>' ],
            [ 'What changes over time', '<p>Network hash rate and difficulty rise as more miners join, which cuts your share of rewards. The block reward halves roughly every four years. Bitcoin’s price moves constantly. Update the inputs regularly.</p>' ],
            [ 'What is not included', '<p>Hardware cost, cooling, hosting, repairs and transaction fees included in blocks are not in this estimate. Mined coins are taxable income in the U.S. at their value when received, and later sales may add capital gains.</p>' ],
        ],
        'terms'     => [ [ 'Hash rate', 'How many calculations per second a miner performs (TH/s = trillion, EH/s = quintillion).' ], [ 'Block reward', 'New bitcoin awarded for each block mined, 3.125 BTC after the April 2024 halving.' ], [ 'Pool fee', 'The share of rewards a mining pool keeps.' ] ],
        'faqs'      => [
            [ 'Is Bitcoin mining profitable?', 'It depends on your electricity cost, hardware efficiency and the Bitcoin price. At typical household electricity prices many machines lose money.' ],
            [ 'How is mining profit calculated?', 'Multiply your share of the network by the daily block rewards, convert to dollars, and subtract electricity and pool fees.' ],
            [ 'Where do I find the network hash rate?', 'Public sites such as mempool.space show the current figure. Enter it in EH/s.' ],
            [ 'Is mined bitcoin taxable?', 'Yes. In the U.S. it is income at the fair market value when you receive it.' ],
        ],
        'sources'   => [ [ 'IRS: Digital assets', 'https://www.irs.gov/filing/digital-assets' ] ],
        'related'   => [ 'crypto-pl-calculator', 'crypto-converter', 'break-even-calculator', 'staking-rewards-calculator' ],
    ],

    'va-loan-calculator' => [
        'focus'     => 'va loan calculator',
        'secondary' => [ 'va mortgage calculator', 'va home loan calculator', 'va loan payment calculator', 'va loan vs conventional' ],
        'title'     => 'VA Loan Calculator: Monthly Payment with Funding Fee',
        'meta'      => 'Free VA loan calculator: estimate your monthly payment with the VA funding fee, taxes and insurance, and compare a VA loan with a conventional mortgage.',
        'excerpt'   => 'Estimate your VA home loan payment, including the funding fee, and compare it with a conventional loan.',
        'answer'    => 'On a $400,000 home with no down payment, a first-use VA loan has a 2.15% funding fee of $8,600. Financed, the loan becomes $408,600, and at 6.5% over 30 years the principal-and-interest payment is about $2,582.63 a month, compared with $2,528.27 without the fee. There is no monthly mortgage insurance on a VA loan.',
        'steps'     => [ 'Enter the home price and your down payment.', 'Choose the loan type, your military category and whether you have used your benefit before.', 'Enter the interest rate and loan term.', 'Read the funding fee, total loan amount and monthly payment, and compare with a conventional loan.' ],
        'formula'   => '<p><strong>Funding fee = loan amount × fee rate</strong> (it can be financed). Monthly principal and interest = <strong>P × r ÷ (1 − (1 + r)<sup>−n</sup>)</strong> where P is the loan including the financed fee, r the monthly rate and n the number of payments.</p>',
        'example'   => '<p>$400,000 home, 0% down, first use, 2.15% fee, 6.5%, 30 years:</p><ul><li>Funding fee: <strong>$8,600</strong>; loan: <strong>$408,600</strong></li><li>Principal and interest: <strong>$2,582.63</strong> a month (<strong>$2,528.27</strong> without the fee)</li></ul><p>Taxes, insurance and HOA are added on top. The 6.5% rate is an illustration.</p>',
        'sections'  => [
            [ 'Why VA loans are popular', '<p>Eligible veterans, service members and some surviving spouses can buy with no down payment, no monthly mortgage insurance and competitive rates. Instead there is a one-time funding fee unless you are exempt.</p>' ],
            [ 'VA vs conventional', '<p>A conventional loan with under 20% down usually requires private mortgage insurance. A VA loan replaces it with the funding fee, which is often cheaper over the life of the loan. Compare both in the calculator for your numbers.</p>' ],
            [ 'Eligibility', '<p>You need a Certificate of Eligibility based on your service. The VA explains requirements, entitlement and limits on its home-loan pages.</p>' ],
        ],
        'terms'     => [ [ 'Funding fee', 'A one-time VA charge, a percentage of the loan, that can be financed.' ], [ 'Entitlement', 'The amount the VA will guarantee for your loan.' ], [ 'Certificate of Eligibility', 'The document that proves you qualify for a VA loan.' ] ],
        'faqs'      => [
            [ 'Is there a down payment on a VA loan?', 'Not required for eligible borrowers with full entitlement, although a down payment lowers the funding fee.' ],
            [ 'Does a VA loan have PMI?', 'No. VA loans do not have monthly mortgage insurance.' ],
            [ 'Can I finance the funding fee?', 'Yes, it can be rolled into the loan, as in the example.' ],
            [ 'Who is exempt from the funding fee?', 'Veterans receiving VA disability compensation, Purple Heart recipients and some surviving spouses, among others. Check the VA’s list.' ],
        ],
        'sources'   => [ [ 'U.S. Department of Veterans Affairs: Home loans', 'https://www.va.gov/housing-assistance/home-loans/' ], [ 'VA: Funding fee and closing costs', 'https://www.va.gov/housing-assistance/home-loans/funding-fee-and-closing-costs/' ] ],
        'related'   => [ 'va-loan-funding-fee-calculator', 'mortgage-calculator', 'fha-loan-calculator', 'refinance-calculator' ],
    ],

    'va-loan-funding-fee-calculator' => [
        'focus'     => 'va funding fee calculator',
        'secondary' => [ 'va loan funding fee', 'va funding fee 2026', 'va funding fee exemption', 'va irrrl funding fee' ],
        'title'     => 'VA Funding Fee Calculator (2026 Rates)',
        'meta'      => 'Free VA funding fee calculator: see your fee for purchase or refinance by down payment and first or subsequent use, plus who is exempt, and the payment impact.',
        'excerpt'   => 'Find your VA funding fee by down payment and use, see who is exempt, and see how the fee changes your payment.',
        'answer'    => 'The VA funding fee is a one-time charge on the loan. For a first-time purchase it is 2.15% with no down payment, 1.5% with 5% or more down and 1.25% with 10% or more down; subsequent use with no down payment is 3.3%, and an IRRRL is 0.5%. On a $400,000 loan at 2.15% the fee is $8,600.',
        'steps'     => [ 'Enter the home price and your down payment percentage.', 'Select purchase, refinance or IRRRL.', 'Choose your military category and whether this is your first use of the benefit.', 'Mark whether you receive VA disability compensation, which can exempt you, and read the fee and the monthly payment.' ],
        'formula'   => '<p><strong>Funding fee = loan amount × fee rate</strong>, where the rate depends on the loan type, down payment and whether you have used the benefit before. If you finance the fee, <strong>total loan = loan amount + fee</strong>.</p>',
        'example'   => '<p>First-use purchase rates (regular military):</p><ul><li>0% down: <strong>2.15%</strong> → on $400,000, <strong>$8,600</strong></li><li>5% to under 10% down: <strong>1.5%</strong></li><li>10% or more down: <strong>1.25%</strong></li><li>Subsequent use, 0% down: <strong>3.3%</strong>; IRRRL: <strong>0.5%</strong></li></ul><p>Financing $8,600 at 6.5% for 30 years adds about <strong>$54.36</strong> a month to the payment.</p>',
        'sections'  => [
            [ 'Who pays no funding fee', '<p>Veterans receiving VA compensation for a service-connected disability, Purple Heart recipients and some surviving spouses are exempt. Confirm your status on your Certificate of Eligibility or with your lender.</p>' ],
            [ 'How to lower the fee', '<p>A down payment of 5% or 10% lowers the rate. Whether it makes sense depends on how much cash you have and the interest rate. Test both in the calculator.</p>' ],
            [ 'Always confirm with the VA', '<p>Fee rates are set by the VA and can change. Check the current table on va.gov before you close. This tool is an estimate for planning.</p>' ],
        ],
        'terms'     => [ [ 'IRRRL', 'Interest Rate Reduction Refinance Loan, the VA’s streamline refinance.' ], [ 'Subsequent use', 'Using your VA loan benefit more than once.' ], [ 'Service-connected disability', 'A disability the VA has determined is linked to military service.' ] ],
        'faqs'      => [
            [ 'How much is the VA funding fee?', 'For a first-use purchase it is 2.15% with no down payment, 1.5% with 5% down and 1.25% with 10% or more down. Rates differ for subsequent use and refinances.' ],
            [ 'Can I avoid the funding fee?', 'Yes, if you are exempt, for example when receiving VA disability compensation.' ],
            [ 'Can the fee be financed?', 'Yes. It can be added to the loan amount, which slightly raises your monthly payment.' ],
            [ 'What is the IRRRL fee?', '0.5% of the loan amount.' ],
        ],
        'sources'   => [ [ 'VA: Funding fee and closing costs', 'https://www.va.gov/housing-assistance/home-loans/funding-fee-and-closing-costs/' ] ],
        'related'   => [ 'va-loan-calculator', 'mortgage-calculator', 'refinance-calculator', 'fha-loan-calculator' ],
    ],

    ];
}
