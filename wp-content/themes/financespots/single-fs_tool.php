<?php
/**
 * Template: Single Tool Page
 * @package financespots
 */
require_once get_template_directory() . '/inc/calculators.php';

// â"€â"€ Advanced VA Loan Calculator â€" full-page template â"€â"€
if ( have_posts() ) {
    the_post();
    $tool_type = get_post_meta( get_the_ID(), '_fs_tool_type', true );
    if ( $tool_type === 'va_loan_advanced' ) {
        // Inject SEO head before output
        get_header();
        $tool_id  = get_the_ID();
        $tool_url = get_permalink( $tool_id );
        $seo_desc = 'Free VA loan funding fee calculator 2026. Calculate your VA funding fee, PITI monthly payment, amortization schedule, and VA vs conventional comparison â€" with PDF export.';
        // Output advanced calculator
        include get_template_directory() . '/tools/va-loan-calculator.php';
        get_footer();
        exit;
    }
    if ( $tool_type === 'ai_dashboard' ) {
        // Dashboard has its own complete HTML (<!DOCTYPE html> ... </html>)
        include get_template_directory() . '/tools/ai-financial-dashboard.php';
        exit;
    }
    rewind_posts();
}

get_header();

while ( have_posts() ) : the_post();
    $tool_type = get_post_meta( get_the_ID(), '_fs_tool_type', true ) ?: 'simple_calc';
    $tool_title = get_the_title();
    $tool_cats  = get_the_terms( get_the_ID(), 'fs_tool_cat' );
    $cat        = $tool_cats ? $tool_cats[0] : null;

    $cat_icons = [
        'loan-calculators'    => '&#127974;',
        'investment-tools'    => '&#128200;',
        'tax-calculators'     => '&#129534;',
        'savings-planners'    => '&#128176;',
        'retirement-planning' => '&#127958;&#65039;',
        'currency-converters' => '&#128177;',
        'budget-analyzers'    => '&#128202;',
        'crypto-tools'        => '₿',
    ];
    $icon = $cat ? ( $cat_icons[ $cat->slug ] ?? '&#128736;&#65039;' ) : '&#128736;&#65039;';
?>

<section class="fst-tool-hero">
    <div class="container">
        <div class="fst-tool-hero__inner">
            <!-- Breadcrumb -->
            <nav class="fst-breadcrumb-nav" aria-label="Breadcrumb" style="display:flex;align-items:center;gap:.4rem;flex-wrap:wrap;margin-bottom:.5rem;">
                <a href="<?php echo esc_url(home_url('/')); ?>" style="font-size:.8rem;color:#64748B;text-decoration:none;" onmouseover="this.style.color='#10B981'" onmouseout="this.style.color='#64748B'">Home</a>
                <span style="color:#334155;font-size:.8rem;">&#x203A;</span>
                <a href="<?php echo esc_url(home_url('/all-tools/')); ?>" style="font-size:.8rem;color:#64748B;text-decoration:none;" onmouseover="this.style.color='#10B981'" onmouseout="this.style.color='#64748B'">All Tools</a>
                <?php if ( $cat ) : ?>
                <span style="color:#334155;font-size:.8rem;">&#x203A;</span>
                <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" style="font-size:.8rem;color:#64748B;text-decoration:none;" onmouseover="this.style.color='#10B981'" onmouseout="this.style.color='#64748B'"><?php echo esc_html( $cat->name ); ?></a>
                <?php endif; ?>
                <span style="color:#334155;font-size:.8rem;">&#x203A;</span>
                <span style="font-size:.8rem;color:#94A3B8;"><?php the_title(); ?></span>
            </nav>
            <?php if ( $cat ) : ?>
            <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="fst-breadcrumb">
                &#x2190; <?php echo esc_html( $cat->name ); ?>
            </a>
            <?php endif; ?>
            <div class="fst-tool-hero__content">
                <span class="fst-tool-hero__icon"><?php echo $icon; ?></span>
                <div>
                    <h1 class="fst-tool-hero__title"><?php the_title(); ?></h1>
                    <p class="fst-tool-hero__desc"><?php echo esc_html( get_the_excerpt() ); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="fst-tool-body section">
    <div class="container">
        <div class="fst-tool-layout">

            <!-- Calculator -->
            <div class="fst-tool-main">
                <div class="fst-tool-card">
                    <div class="fst-tool-card__header">
                        <span class="fst-tool-card__tag">Calculator</span>
                        <h2 class="fst-tool-card__title"><?php echo esc_html( $tool_title ); ?></h2>
                    </div>
                    <?php fs_render_calculator( $tool_type, $tool_title ); ?>

                    <!-- Universal PDF Download Button -->
                    <div id="fs-pdf-bar" style="display:none;margin-top:1.5rem;padding:1rem 1.25rem;background:linear-gradient(135deg,#00C896,#3B82F6);border-radius:12px;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem">
                        <div style="color:#fff">
                            <div style="font-weight:700;font-size:.95rem">&#x1F4CA; Results Ready!</div>
                            <div style="font-size:.8rem;opacity:.85">Save or share your calculation results</div>
                        </div>
                        <div style="display:flex;gap:.75rem;flex-wrap:wrap">
                            <button onclick="fsPrintCalc()" style="background:rgba(255,255,255,.2);color:#fff;border:1.5px solid rgba(255,255,255,.5);padding:.5rem 1rem;border-radius:8px;font-weight:700;font-size:.85rem;cursor:pointer;transition:all .2s" onmouseover="this.style.background='rgba(255,255,255,.35)'" onmouseout="this.style.background='rgba(255,255,255,.2)'">&#x1F5A8; Print</button>
                            <button onclick="fsDownloadPDF()" style="background:#fff;color:#00C896;border:none;padding:.5rem 1.2rem;border-radius:8px;font-weight:800;font-size:.85rem;cursor:pointer;transition:all .2s" onmouseover="this.style.background='#f0fdf9'" onmouseout="this.style.background='#fff'">&#x2B07; Download PDF</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="fst-tool-sidebar">
                <div class="fst-sidebar-card">
                    <h3 class="fst-sidebar-card__title">About This Tool</h3>
                    <p class="fst-sidebar-card__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
                </div>

                <?php if ( $cat ) :
                    $related = get_posts( [
                        'post_type'      => 'fs_tool',
                        'posts_per_page' => 5,
                        'post__not_in'   => [ get_the_ID() ],
                        'tax_query'      => [ [ 'taxonomy' => 'fs_tool_cat', 'terms' => $cat->term_id ] ],
                    ] );
                    if ( $related ) : ?>
                <div class="fst-sidebar-card">
                    <h3 class="fst-sidebar-card__title">More <?php echo esc_html( $cat->name ); ?></h3>
                    <ul class="fst-related-list">
                        <?php foreach ( $related as $r ) : ?>
                        <li><a href="<?php echo esc_url( get_permalink( $r ) ); ?>"><?php echo esc_html( $r->post_title ); ?> &#x2192;</a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                    <?php endif; endif; ?>

                <div class="fst-sidebar-card fst-sidebar-card--cta">
                    <h3 class="fst-sidebar-card__title">Explore All Tools</h3>
                    <a href="<?php echo esc_url( home_url( '/tools/' ) ); ?>" class="fsc-btn" style="width:100%;text-align:center">Browse 110+ Tools</a>
                </div>
            </aside>

        </div>
    </div>
</section>

<?php
/* â"€â"€ SEO Content Section â€" How to use + FAQ per tool â"€â"€ */
$faq_data = [
    'mortgage_calc' => [
        'how'  => 'Enter your home price, down payment, interest rate, loan term, property tax, and insurance. Click "Calculate Payment" to see your full monthly PITI payment and total loan cost.',
        'faqs' => [
            ['q'=>'What is a good mortgage rate in 2026?','a'=>'In 2026, a competitive 30-year fixed mortgage rate is between 6.5%â€"7.5% depending on your credit score, down payment, and lender. A 15-year fixed is typically 0.5â€"1% lower.'],
            ['q'=>'How much house can I afford?','a'=>'A common rule is to keep your total housing costs (PITI) below 28% of your gross monthly income, and total debt payments below 43% (the DTI limit for most conventional loans).'],
            ['q'=>'What is included in a mortgage payment?','a'=>'A full mortgage payment (PITI) includes Principal, Interest, Property Taxes (escrowed), and Homeowner\'s Insurance. HOA fees may also apply.'],
            ['q'=>'Is a 30-year or 15-year mortgage better?','a'=>'A 15-year mortgage has a higher monthly payment but saves significantly more on total interest and pays off faster. A 30-year mortgage offers lower payments and more flexibility.'],
        ],
    ],
    'auto_loan_calc' => [
        'how'  => 'Enter the vehicle price, down payment, trade-in value, sales tax rate, loan APR, and term. The calculator shows your exact monthly payment plus total car cost including interest.',
        'faqs' => [
            ['q'=>'What is a good auto loan rate in 2026?','a'=>'Average auto loan rates in 2026 range from 5%â€"8% for new cars and 7%â€"12% for used cars, depending on credit score. Rates below 6% for new cars are considered excellent.'],
            ['q'=>'Should I put a down payment on a car?','a'=>'Yes â€" a 20% down payment is recommended for new cars and 10% for used cars to avoid being "upside down" (owing more than the car is worth).'],
            ['q'=>'What loan term is best for an auto loan?','a'=>'36â€"48 months minimizes total interest. Terms of 60â€"72 months lower monthly payments but increase total interest and the risk of negative equity.'],
            ['q'=>'Does sales tax affect my auto loan?','a'=>'Yes â€" in most states, sales tax is added to the purchase price and can be rolled into the loan. This increases your loan amount and total interest paid.'],
        ],
    ],
    'personal_loan_calc' => [
        'how'  => 'Enter the loan amount, interest rate (APR), loan term, and any origination fee. The calculator shows monthly payment, total repayment, and effective APR after fees.',
        'faqs' => [
            ['q'=>'What credit score do I need for a personal loan?','a'=>'Most lenders require a minimum credit score of 580â€"640. However, to get the best rates (below 10% APR), you typically need a score of 720 or higher.'],
            ['q'=>'What is a good personal loan rate?','a'=>'Rates below 10% APR are considered good for personal loans. Average personal loan APR in 2026 is around 11%â€"12% for borrowers with good credit.'],
            ['q'=>'How is APR different from interest rate?','a'=>'APR (Annual Percentage Rate) includes the interest rate plus origination fees, making it a more accurate measure of the true cost of the loan.'],
            ['q'=>'Can I pay off a personal loan early?','a'=>'Most personal loans have no prepayment penalty. Paying extra reduces total interest significantly, especially early in the loan term.'],
        ],
    ],
    'student_loan_calc' => [
        'how'  => 'Enter your total loan balance, interest rate, and select a repayment plan. The calculator accounts for the grace period and shows your monthly payment, total interest, and payoff date.',
        'faqs' => [
            ['q'=>'What are the current federal student loan rates for 2026?','a'=>'Federal student loan rates for 2024â€"25 are: 6.53% for undergrad Direct Loans, 8.08% for grad Direct Loans, and 9.08% for PLUS Loans.'],
            ['q'=>'What is the grace period on student loans?','a'=>'Most federal student loans offer a 6-month grace period after graduation before payments begin. Interest may still accrue during this time on unsubsidized loans.'],
            ['q'=>'What repayment plan should I choose?','a'=>'The Standard 10-year plan pays off loans fastest and minimizes interest. Income-Driven Repayment (IDR) plans offer lower payments but extend the term and increase total interest.'],
            ['q'=>'Does interest accrue during the grace period?','a'=>'On unsubsidized federal loans, yes â€" interest accrues during the grace period. On subsidized loans, the government covers interest while you\'re in school and during grace.'],
        ],
    ],
    'heloc_calc' => [
        'how'  => 'Enter your home value, current mortgage balance, and max LTV allowed by your lender (typically 80â€"85%). The calculator shows your available equity and monthly payment.',
        'faqs' => [
            ['q'=>'How much equity can I borrow against my home?','a'=>'Most lenders allow you to borrow up to 80â€"85% of your home\'s value minus your mortgage balance. With an 85% LTV on a $500k home with a $300k mortgage, you can borrow up to $125,000.'],
            ['q'=>'What is the difference between a HELOC and home equity loan?','a'=>'A home equity loan gives you a lump sum with a fixed rate. A HELOC is a revolving credit line with a variable rate â€" like a credit card secured by your home.'],
            ['q'=>'Is home equity loan interest tax deductible?','a'=>'Interest may be deductible if the loan is used to buy, build, or substantially improve the home securing the loan. Consult a tax advisor for your specific situation.'],
            ['q'=>'What is LTV and why does it matter?','a'=>'LTV (Loan-to-Value) is the ratio of your mortgage to your home\'s value. A lower LTV means more equity and better loan terms. Lenders typically require LTV below 80â€"85% for equity loans.'],
        ],
    ],
    'fha_loan_calc' => [
        'how'  => 'Enter the purchase price, down payment percentage, interest rate, and loan term. The calculator automatically applies the 1.75% upfront MIP and annual MIP based on your down payment.',
        'faqs' => [
            ['q'=>'What is FHA Mortgage Insurance Premium (MIP)?','a'=>'FHA loans require an upfront MIP of 1.75% (rolled into the loan) and an annual MIP of 0.55%â€"1.05% depending on loan size, term, and LTV ratio.'],
            ['q'=>'How long do I pay MIP on an FHA loan?','a'=>'With less than 10% down, you pay MIP for the life of the loan. With 10% or more down, MIP is canceled after 11 years. This is a key reason many borrowers refinance to conventional once they have 20% equity.'],
            ['q'=>'What is the minimum credit score for an FHA loan?','a'=>'580 for 3.5% down payment. 500â€"579 requires 10% down. Below 500 is not eligible for FHA financing.'],
            ['q'=>'What are the FHA loan limits for 2026?','a'=>'FHA loan limits for 2026 are $524,225 for most areas (baseline) and up to $1,209,750 in high-cost areas. Check HUD.gov for your specific county.'],
        ],
    ],
    'balloon_calc' => [
        'how'  => 'Enter the loan amount, interest rate, amortization period, and balloon term. The calculator shows your monthly payment and the large lump-sum balloon payment due at the end of the term.',
        'faqs' => [
            ['q'=>'What is a balloon loan?','a'=>'A balloon loan has lower monthly payments based on a long amortization (e.g., 30 years), but the remaining balance comes due as a lump sum at the end of a shorter term (e.g., 5 or 7 years).'],
            ['q'=>'Who uses balloon loans?','a'=>'Balloon loans are common in commercial real estate, where borrowers expect to sell or refinance before the balloon is due. They\'re less common for residential mortgages.'],
            ['q'=>'What happens if I can\'t pay the balloon?','a'=>'You\'ll need to refinance or sell the property. If neither is possible and you default, the lender can foreclose. This is the main risk of balloon financing.'],
            ['q'=>'Are balloon loans a good idea?','a'=>'They can work if you plan to sell or refinance before the balloon date. They\'re risky if rates rise significantly and refinancing becomes expensive or unavailable.'],
        ],
    ],
    'interest_only_calc' => [
        'how'  => 'Enter the loan amount, interest rate, interest-only period, and total loan term. The calculator compares your interest-only payment vs. the fully amortizing payment and shows the total extra cost.',
        'faqs' => [
            ['q'=>'What is an interest-only loan?','a'=>'An interest-only loan requires you to pay only the interest for a set period (typically 5â€"10 years), after which payments jump to cover both principal and interest over the remaining term.'],
            ['q'=>'Are interest-only loans risky?','a'=>'Yes â€" your balance doesn\'t decrease during the IO period, so when P&I payments begin, they\'re higher than a traditional loan. You also build no equity during the IO period.'],
            ['q'=>'Who benefits from an interest-only loan?','a'=>'Investors who plan to sell before the IO period ends, high-income earners with irregular income, or buyers who want maximum short-term cash flow.'],
            ['q'=>'How much more does an interest-only loan cost?','a'=>'Total interest on an IO loan can be 10â€"20% more than a fully amortizing loan because no principal is paid during the IO period.'],
        ],
    ],
    'loan_payoff_calc' => [
        'how'  => 'Enter your current loan balance, interest rate, remaining term, and extra monthly payment (or a one-time lump sum). The calculator shows exactly how many months you save and total interest avoided.',
        'faqs' => [
            ['q'=>'How much do extra payments really save?','a'=>'On a $200,000 mortgage at 7%, an extra $200/month saves about 5 years and over $60,000 in interest. Even $50/month extra makes a significant difference over 30 years.'],
            ['q'=>'When is the best time to make extra payments?','a'=>'Early in the loan term, because interest is front-loaded. Extra payments in year 1â€"5 save far more than the same amount paid in year 20.'],
            ['q'=>'Should I pay extra on my mortgage or invest?','a'=>'If your mortgage rate is higher than expected investment returns, paying extra wins. If your rate is low (e.g., 3â€"4%) and you can earn 7%+ investing, investing often wins mathematically.'],
            ['q'=>'Does my lender require a minimum for extra payments?','a'=>'No â€" most lenders accept any extra amount. Just specify it should go to principal, not future payments. Check your loan documents for any prepayment penalty.'],
        ],
    ],
    'commercial_calc' => [
        'how'  => 'Enter the loan amount, rate, amortization period, balloon term, and annual net operating income. The calculator shows monthly payment, balloon balance, and DSCR â€" the key metric lenders use.',
        'faqs' => [
            ['q'=>'What is DSCR and why does it matter?','a'=>'DSCR (Debt Service Coverage Ratio) = Net Operating Income / Annual Debt Service. Most commercial lenders require DSCR â‰¥ 1.25, meaning the property earns 25% more than the annual loan payment.'],
            ['q'=>'What are typical commercial loan rates in 2026?','a'=>'Commercial loan rates range from 6.5%â€"9% for stabilized properties, and higher (9%â€"12%+) for construction or value-add deals, depending on the lender and property type.'],
            ['q'=>'What is the difference between amortization and loan term?','a'=>'Amortization determines monthly payment size (e.g., 25 years). The loan term is when the balloon is due (e.g., 10 years). At term end, the remaining balance must be paid or refinanced.'],
            ['q'=>'What LTV do commercial lenders allow?','a'=>'Most commercial lenders cap LTV at 65â€"75% for investment properties. SBA loans can go to 85â€"90% for owner-occupied commercial real estate.'],
        ],
    ],
    'bridge_calc' => [
        'how'  => 'Enter the bridge loan amount, interest rate, duration, payment type, origination fee, and exit fee. The calculator shows total cost and effective APR of the short-term financing.',
        'faqs' => [
            ['q'=>'What is a bridge loan?','a'=>'A bridge loan is short-term financing (3â€"24 months) used to "bridge" the gap between buying a new property and selling an existing one, or while securing permanent financing.'],
            ['q'=>'What are typical bridge loan rates?','a'=>'Bridge loan rates range from 8%â€"12% or higher in 2026. They\'re more expensive than conventional loans because of the short term and higher lender risk.'],
            ['q'=>'What fees come with a bridge loan?','a'=>'Typically: 1â€"3% origination fee, 0.5â€"2% exit fee, appraisal, and legal fees. Total upfront and exit costs can add 3â€"5% to the loan amount.'],
            ['q'=>'When does a bridge loan make sense?','a'=>'When you\'re buying before selling, competing in a hot market, rehabilitating a property for permanent financing, or need to close quickly and conventional financing is too slow.'],
        ],
    ],
];

$tool_faq = $faq_data[ $tool_type ] ?? null;
if ( $tool_faq ) : ?>
<section style="padding:3rem 0 2rem;background:#F8FAFC;border-top:1px solid var(--fs-border)">
    <div class="container" style="max-width:860px">
        <div style="background:#fff;border-radius:16px;border:1.5px solid var(--fs-border);padding:2rem 2.5rem;margin-bottom:2rem">
            <h2 style="font-size:1.15rem;font-weight:800;margin:0 0 .75rem">How to Use This Calculator</h2>
            <p style="color:var(--fs-text-muted);line-height:1.7;margin:0"><?php echo esc_html($tool_faq['how']); ?></p>
        </div>

        <h2 style="font-size:1.3rem;font-weight:800;color:var(--fs-text);margin:0 0 1.25rem">Frequently Asked Questions</h2>
        <div style="display:flex;flex-direction:column;gap:.75rem">
            <?php foreach($tool_faq['faqs'] as $i => $faq) : ?>
            <details style="background:#fff;border:1.5px solid var(--fs-border);border-radius:12px;overflow:hidden">
                <summary style="padding:1.1rem 1.5rem;font-weight:700;font-size:.95rem;cursor:pointer;list-style:none;display:flex;align-items:center;justify-content:space-between;gap:1rem">
                    <?php echo esc_html($faq['q']); ?>
                    <span style="flex-shrink:0;font-size:1.1rem;color:var(--fs-primary)">+</span>
                </summary>
                <div style="padding:0 1.5rem 1.25rem;color:var(--fs-text-muted);line-height:1.7;font-size:.92rem;border-top:1px solid var(--fs-border)">
                    <div style="padding-top:1rem"><?php echo esc_html($faq['a']); ?></div>
                </div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
/* ── Related Finance Topics + You Might Also Need ── */
$cat_topic_data = [
    'loan-calculators' => [
        'topic' => 'Understanding loan payments and interest is foundational to smart borrowing. Whether you are financing a home, car, or personal expense, knowing your true cost helps you negotiate better rates and pay off debt faster. Always compare the APR -- not just the interest rate -- to understand the total cost.',
        'links' => [
            ['Mortgage Calculator', home_url('/tools/mortgage-calculator/')],
            ['Auto Loan Calculator', home_url('/tools/auto-loan-calculator/')],
            ['Personal Loan Calculator', home_url('/tools/personal-loan-calculator/')],
            ['Loan Payoff Calculator', home_url('/tools/loan-payoff-calculator/')],
        ],
    ],
    'investment-tools' => [
        'topic' => 'Compound interest is the most powerful force in investing -- starting early and staying consistent consistently outperforms timing the market. Understanding investment return metrics like CAGR, IRR, and Sharpe ratio helps you evaluate opportunities objectively. Use our free calculators to model different scenarios before committing capital.',
        'links' => [
            ['Compound Interest Calculator', home_url('/tools/compound-interest-calculator/')],
            ['Investment Return Calculator', home_url('/tools/investment-return-calculator/')],
            ['Dividend Reinvestment Calculator', home_url('/tools/dividend-reinvestment-calculator/')],
            ['Net Worth Calculator', home_url('/tools/net-worth-calculator/')],
        ],
    ],
    'tax-calculators' => [
        'topic' => 'Tax planning throughout the year -- not just in April -- can save thousands. Understanding your marginal vs effective tax rate, maximizing tax-advantaged accounts, and tracking deductible expenses are the three biggest levers for reducing your annual tax burden. Use our calculators to estimate before you file.',
        'links' => [
            ['Income Tax Calculator', home_url('/tools/income-tax-calculator/')],
            ['Self-Employment Tax Calculator', home_url('/tools/self-employment-tax-calculator/')],
            ['Capital Gains Tax Calculator', home_url('/tools/capital-gains-tax-calculator/')],
            ['W-4 Withholding Calculator', home_url('/tools/w4-withholding-calculator/')],
        ],
    ],
    'savings-planners' => [
        'topic' => 'A fully funded emergency fund -- typically 3 to 6 months of expenses -- is the single most important financial safety net you can build. Beyond emergencies, automating savings through high-yield accounts and CDs maximizes growth with minimal effort. Calculate your target savings number before choosing an account.',
        'links' => [
            ['Emergency Fund Calculator', home_url('/tools/emergency-fund-calculator/')],
            ['Savings Goal Calculator', home_url('/tools/savings-goal-calculator/')],
            ['CD Calculator', home_url('/tools/cd-calculator/')],
            ['High-Yield Savings Calculator', home_url('/tools/high-yield-savings-calculator/')],
        ],
    ],
    'retirement-planning' => [
        'topic' => 'Retirement planning requires knowing your "retirement number" -- the total portfolio value needed to sustain your lifestyle indefinitely. The 4% withdrawal rule is a common starting point, but your specific number depends on spending, inflation, and Social Security income. Start calculating early to understand how much to save each month.',
        'links' => [
            ['Retirement Income Calculator', home_url('/tools/retirement-income-calculator/')],
            ['401k Calculator', home_url('/tools/401k-calculator/')],
            ['IRA Calculator', home_url('/tools/ira-calculator/')],
            ['Social Security Calculator', home_url('/tools/social-security-calculator/')],
        ],
    ],
    'budget-analyzers' => [
        'topic' => 'A budget is not a restriction -- it is a plan for your money to do exactly what you want it to do. The 50/30/20 rule (needs/wants/savings) is a proven starting framework, but personalizing your budget to your income and goals is what creates lasting financial change. Track expenses for 30 days before setting budget limits.',
        'links' => [
            ['Monthly Budget Planner', home_url('/tools/monthly-budget-planner/')],
            ['50/30/20 Budget Calculator', home_url('/tools/503020-budget-calculator/')],
            ['Expense Tracker', home_url('/tools/expense-tracker/')],
            ['Savings Rate Calculator', home_url('/tools/savings-rate-calculator/')],
        ],
    ],
    'crypto-tools' => [
        'topic' => 'Cryptocurrency investing combines the volatility of a startup with 24/7 global trading -- making disciplined position sizing and tax tracking critical. Dollar-cost averaging reduces the impact of price swings, while accurate P&L tracking ensures you report gains correctly. Always calculate your cost basis before each trade.',
        'links' => [
            ['Crypto P&L Calculator', home_url('/tools/crypto-pl-calculator/')],
            ['Bitcoin DCA Calculator', home_url('/tools/bitcoin-dca-calculator/')],
            ['Crypto Tax Estimator', home_url('/tools/crypto-tax-estimator/')],
            ['Compound Interest Calculator', home_url('/tools/compound-interest-calculator/')],
        ],
    ],
];

$topic_info = $cat ? ( $cat_topic_data[ $cat->slug ] ?? null ) : null;

// "You Might Also Need" -- links to 3 other categories
$other_cats = [
    ['&#128200; Investing Tools', home_url('/tools/compound-interest-calculator/'), 'investment-tools'],
    ['&#127968; Mortgage Calculators', home_url('/tools/mortgage-calculator/'), 'loan-calculators'],
    ['&#127958;&#65039; Retirement Planner', home_url('/tools/retirement-income-calculator/'), 'retirement-planning'],
    ['&#129534; Tax Calculators', home_url('/tools/income-tax-calculator/'), 'tax-calculators'],
    ['&#128203; Budget Planner', home_url('/tools/monthly-budget-planner/'), 'budget-analyzers'],
    ['&#8383; Crypto Tools', home_url('/tools/crypto-pl-calculator/'), 'crypto-tools'],
];
$current_cat_slug = $cat ? $cat->slug : '';
$suggested = array_filter($other_cats, function($c) use ($current_cat_slug){ return $c[2] !== $current_cat_slug; });
$suggested = array_values($suggested);
?>

<?php if ( $topic_info ) : ?>
<section style="padding:2.5rem 0 2rem;background:#F1F5F9;border-top:1px solid #E2E8F0;">
    <div class="container" style="max-width:860px;">
        <h2 style="font-size:1.15rem;font-weight:800;color:#0F172A;margin:0 0 .75rem;">&#128218; Related Finance Topics</h2>
        <p style="color:#475569;line-height:1.75;font-size:.94rem;margin:0 0 1.25rem;"><?php echo esc_html($topic_info['topic']); ?></p>
        <div style="display:flex;flex-wrap:wrap;gap:.6rem;margin-bottom:1rem;">
            <?php foreach(array_slice($topic_info['links'],0,4) as $l): ?>
            <a href="<?php echo esc_url($l[1]); ?>" style="display:inline-flex;align-items:center;gap:5px;background:#fff;border:1.5px solid #CBD5E1;border-radius:8px;padding:7px 13px;font-size:.83rem;font-weight:600;color:#334155;text-decoration:none;transition:border-color .2s,color .2s;" onmouseover="this.style.borderColor='#10B981';this.style.color='#059669'" onmouseout="this.style.borderColor='#CBD5E1';this.style.color='#334155'">
                &#x2192; <?php echo esc_html($l[0]); ?>
            </a>
            <?php endforeach; ?>
        </div>
        <?php if ( $cat ) : ?>
        <a href="<?php echo esc_url(get_term_link($cat)); ?>" style="font-size:.85rem;font-weight:700;color:#10B981;text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
            See All <?php echo esc_html($cat->name); ?> Tools &#x2192;
        </a>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<section style="padding:2rem 0 2.5rem;background:#F8FAFC;border-top:1px solid #E2E8F0;">
    <div class="container" style="max-width:860px;">
        <h2 style="font-size:1.05rem;font-weight:800;color:#0F172A;margin:0 0 1rem;">&#128736;&#65039; You Might Also Need</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:.65rem;">
            <?php foreach(array_slice($suggested,0,3) as $s): ?>
            <a href="<?php echo esc_url($s[1]); ?>" style="display:flex;align-items:center;gap:8px;background:#fff;border:1.5px solid #E2E8F0;border-radius:10px;padding:12px 14px;text-decoration:none;color:#334155;font-size:.86rem;font-weight:600;transition:border-color .2s,color .2s;" onmouseover="this.style.borderColor='#10B981';this.style.color='#059669'" onmouseout="this.style.borderColor='#E2E8F0';this.style.color='#334155'">
                <?php echo $s[0]; ?>
            </a>
            <?php endforeach; ?>
            <a href="<?php echo esc_url(home_url('/all-tools/')); ?>" style="display:flex;align-items:center;gap:8px;background:#ECFDF5;border:1.5px solid #BBF7D0;border-radius:10px;padding:12px 14px;text-decoration:none;color:#15803D;font-size:.86rem;font-weight:600;transition:background .2s;" onmouseover="this.style.background='#D1FAE5'" onmouseout="this.style.background='#ECFDF5'">
                &#128200; Browse All 150+ Tools &#x2192;
            </a>
        </div>
    </div>
</section>

<?php endwhile; ?>

<!-- Universal PDF / Print system -->
<script>
/* Expose globally so calculators can call it directly */
function fsPdfBarShow(){
    var bar = document.getElementById('fs-pdf-bar');
    if(bar){ bar.style.display='flex'; }
}
/* Also watch via MutationObserver as fallback */
(function(){
    var observer = new MutationObserver(function(mutations){
        for(var i=0;i<mutations.length;i++){
            var t = mutations[i].target;
            if(t && t.style && (t.style.display==='grid'||t.style.display==='block'||t.style.display==='table')){
                if(t.id && (t.id.indexOf('-results')>-1 || t.id.indexOf('-table')>-1)){
                    fsPdfBarShow(); break;
                }
            }
        }
    });
    observer.observe(document.body, { attributes:true, attributeFilter:['style'], subtree:true });
})();

function fsPrintCalc(){
    window.print();
}

function fsDownloadPDF(){
    var title = document.querySelector('.fst-tool-hero__title, .fst-tool-card__title, h1');
    var toolTitle = title ? title.textContent.trim() : 'FinanceSpots Calculator';

    /* Collect all visible result cards */
    var cards = document.querySelectorAll('.fsc-result-card');
    var rows = [];
    cards.forEach(function(c){
        var lbl = c.querySelector('.fsc-result-label');
        var val = c.querySelector('.fsc-result-value');
        if(lbl && val && val.textContent.trim() !== 'â€"'){
            rows.push([lbl.textContent.trim(), val.textContent.trim()]);
        }
    });

    /* Collect table rows if present */
    var tableRows = [];
    var tHead = document.querySelector('.fsc-table thead tr');
    var tBody = document.querySelector('#am-tbody, .fsc-table tbody');
    if(tHead && tBody){
        var ths = tHead.querySelectorAll('th');
        var headers = [];
        ths.forEach(function(th){ headers.push(th.textContent.trim()); });
        var trs = tBody.querySelectorAll('tr');
        trs.forEach(function(tr){
            var tds = tr.querySelectorAll('td');
            var r = [];
            tds.forEach(function(td){ r.push(td.textContent.trim()); });
            if(r.length) tableRows.push(r);
        });
        if(headers.length) tableRows.unshift(headers);
    }

    if(typeof window.jspdf === 'undefined' && typeof window.jsPDF === 'undefined'){
        alert('PDF library loadingâ€¦ please try again in a moment.');
        return;
    }

    var jsPDF = window.jspdf ? window.jspdf.jsPDF : window.jsPDF;
    var doc = new jsPDF({ orientation:'portrait', unit:'mm', format:'a4' });
    var W = doc.internal.pageSize.getWidth();
    var y = 18;

    /* Header band */
    doc.setFillColor(0,200,150);
    doc.rect(0,0,W,24,'F');
    doc.setTextColor(255,255,255);
    doc.setFontSize(14); doc.setFont('helvetica','bold');
    doc.text('FinanceSpots', 14, 10);
    doc.setFontSize(10); doc.setFont('helvetica','normal');
    doc.text('Free Financial Calculators â€" financespots.com', 14, 17);

    /* Tool title */
    y = 34;
    doc.setTextColor(20,20,40);
    doc.setFontSize(16); doc.setFont('helvetica','bold');
    doc.text(toolTitle, 14, y); y += 8;

    /* Date */
    doc.setFontSize(9); doc.setFont('helvetica','normal');
    doc.setTextColor(120,120,140);
    doc.text('Generated: ' + new Date().toLocaleDateString('en-US',{year:'numeric',month:'long',day:'numeric'}), 14, y); y += 10;

    /* Result cards */
    if(rows.length){
        doc.setFontSize(12); doc.setFont('helvetica','bold');
        doc.setTextColor(20,20,40);
        doc.text('Calculation Results', 14, y); y += 6;

        rows.forEach(function(row, i){
            if(y > 265){ doc.addPage(); y = 20; }
            var bg = i % 2 === 0 ? [248,250,252] : [255,255,255];
            doc.setFillColor(bg[0],bg[1],bg[2]);
            doc.rect(14, y-4, W-28, 10, 'F');
            doc.setFont('helvetica','normal'); doc.setFontSize(10);
            doc.setTextColor(80,80,100);
            doc.text(row[0], 18, y+2);
            doc.setFont('helvetica','bold');
            doc.setTextColor(0,150,110);
            doc.text(row[1], W-18, y+2, {align:'right'});
            y += 11;
        });
        y += 6;
    }

    /* Amortization table (first 24 rows) */
    if(tableRows.length > 1){
        if(y > 200){ doc.addPage(); y = 20; }
        doc.setFontSize(12); doc.setFont('helvetica','bold');
        doc.setTextColor(20,20,40);
        doc.text('Amortization Schedule (First 24 Payments)', 14, y); y += 6;
        var colW = (W-28)/tableRows[0].length;
        tableRows.slice(0, 25).forEach(function(row, ri){
            if(y > 270){ doc.addPage(); y = 20; }
            var isHead = ri === 0;
            if(isHead){ doc.setFillColor(0,200,150); doc.setTextColor(255,255,255); }
            else{ doc.setFillColor(ri%2===0?248:255, ri%2===0?250:255, ri%2===0?252:255); doc.setTextColor(60,60,80); }
            doc.rect(14, y-4, W-28, 8, 'F');
            doc.setFont('helvetica', isHead?'bold':'normal'); doc.setFontSize(8);
            row.forEach(function(cell, ci){
                doc.text(String(cell), 16+ci*colW, y);
            });
            y += 9;
        });
        y += 4;
    }

    /* Footer */
    doc.setFontSize(8); doc.setFont('helvetica','normal');
    doc.setTextColor(150,150,170);
    doc.text('Disclaimer: For informational purposes only. Consult a licensed financial advisor for major financial decisions.', 14, 285);
    doc.text('Â© ' + new Date().getFullYear() + ' FinanceSpots.com â€" Free Financial Calculators', W-14, 285, {align:'right'});

    var fname = toolTitle.replace(/[^a-z0-9]/gi,'_').toLowerCase() + '_results.pdf';
    doc.save(fname);
}
</script>

<?php get_footer(); ?>

