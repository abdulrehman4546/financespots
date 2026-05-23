<?php
/**
 * Template Name: Tool Categories
 */
get_header();
?>

<div class="fs-cats-page">

    <!-- Hero -->
    <section class="fs-cats-hero">
        <div class="container">
            <div class="fs-cats-hero__inner">
                <span class="fs-badge fs-badge--green">&#128200; Finance Tools</span>
                <h1 class="fs-cats-hero__title">All Financial Tool Categories</h1>
                <p class="fs-cats-hero__desc">Browse our complete collection of free financial calculators and tools -- organized by category. No signup required.</p>
            </div>
        </div>
    </section>

    <!-- Categories Grid -->
    <section class="fs-cats-grid-section">
        <div class="container">
            <div class="fs-cats-grid">

                <?php
                $categories = [
                    [
                        'icon'  => '&#127968;',
                        'title' => 'Mortgage & Home',
                        'desc'  => 'Calculate mortgage payments, compare loan terms, estimate affordability, and plan your home purchase with confidence.',
                        'color' => '#3B82F6',
                        'tools' => ['Mortgage Calculator', 'Home Affordability', 'Refinance Calculator', 'Amortization Schedule'],
                        'link'  => home_url('/tools/'),
                    ],
                    [
                        'icon'  => '&#128200;',
                        'title' => 'Investing & Wealth',
                        'desc'  => 'Compound interest, investment returns, index fund projections, and portfolio growth calculators.',
                        'color' => '#10B981',
                        'tools' => ['Compound Interest', 'Investment Calculator', 'Stock Return Calculator', 'Dividend Calculator'],
                        'link'  => home_url('/tools/'),
                    ],
                    [
                        'icon'  => '&#128179;',
                        'title' => 'Debt Management',
                        'desc'  => 'Pay off debt faster using the avalanche or snowball method. See exactly when you will be debt-free.',
                        'color' => '#EF4444',
                        'tools' => ['Debt Payoff Calculator', 'Credit Card Payoff', 'Debt Avalanche', 'Debt Snowball'],
                        'link'  => home_url('/tools/'),
                    ],
                    [
                        'icon'  => '&#127958;&#65039;',
                        'title' => 'Retirement Planning',
                        'desc'  => 'Plan your retirement savings, calculate your retirement number, and project your 401(k) and IRA growth.',
                        'color' => '#8B5CF6',
                        'tools' => ['Retirement Calculator', '401k Calculator', 'IRA Calculator', 'Social Security Estimator'],
                        'link'  => home_url('/tools/'),
                    ],
                    [
                        'icon'  => '&#128203;',
                        'title' => 'Budgeting',
                        'desc'  => 'Build a monthly budget, track spending, apply the 50/30/20 rule, and take full control of your money.',
                        'color' => '#06B6D4',
                        'tools' => ['Budget Planner', '50/30/20 Calculator', 'Expense Tracker', 'Savings Rate Calculator'],
                        'link'  => home_url('/tools/'),
                    ],
                    [
                        'icon'  => '&#128196;',
                        'title' => 'Loans & Credit',
                        'desc'  => 'Compare personal loans, auto loans, and student loans. Find the best rates and monthly payments.',
                        'color' => '#F59E0B',
                        'tools' => ['Personal Loan Calculator', 'Auto Loan Calculator', 'Student Loan Calculator', 'APR Calculator'],
                        'link'  => home_url('/tools/'),
                    ],
                    [
                        'icon'  => '₿',
                        'title' => 'Cryptocurrency',
                        'desc'  => 'Track your crypto profits and losses, calculate DCA returns, and estimate tax liability on trades.',
                        'color' => '#F97316',
                        'tools' => ['Crypto P&L Calculator', 'Bitcoin DCA Calculator', 'Crypto Tax Estimator', 'Portfolio Tracker'],
                        'link'  => home_url('/tools/'),
                    ],
                    [
                        'icon'  => '&#129534;',
                        'title' => 'Taxes',
                        'desc'  => 'Estimate your federal and state income tax, find deductions, and plan ahead for tax season 2026.',
                        'color' => '#64748B',
                        'tools' => ['Income Tax Calculator', 'Tax Bracket Calculator', 'Self-Employment Tax', 'Capital Gains Tax'],
                        'link'  => home_url('/tools/'),
                    ],
                    [
                        'icon'  => '&#127974;',
                        'title' => 'Savings & Emergency Fund',
                        'desc'  => 'Calculate how much to save, build your emergency fund, and find the best high-yield savings rates.',
                        'color' => '#10B981',
                        'tools' => ['Emergency Fund Calculator', 'Savings Goal Calculator', 'High-Yield Savings', 'CD Calculator'],
                        'link'  => home_url('/tools/'),
                    ],
                ];
                foreach($categories as $cat):
                ?>
                <div class="fs-cat-card">
                    <div class="fs-cat-card__top">
                        <div class="fs-cat-card__icon" style="background:<?php echo $cat['color']; ?>22;border-color:<?php echo $cat['color']; ?>44;">
                            <span><?php echo $cat['icon']; ?></span>
                        </div>
                        <div>
                            <h2 class="fs-cat-card__title"><?php echo esc_html($cat['title']); ?></h2>
                            <span class="fs-cat-card__count"><?php echo count($cat['tools']); ?> tools</span>
                        </div>
                    </div>
                    <p class="fs-cat-card__desc"><?php echo esc_html($cat['desc']); ?></p>
                    <ul class="fs-cat-card__tools">
                        <?php foreach($cat['tools'] as $tool): ?>
                        <li>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            <?php echo esc_html($tool); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo esc_url($cat['link']); ?>" class="fs-cat-card__btn" style="--accent:<?php echo $cat['color']; ?>">
                        Explore Tools <span>&#x2192;</span>
                    </a>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <!-- Cross-Category Quick Links -->
    <section style="padding:0 0 48px;background:#0A0F1E;">
        <div class="container">
            <h2 style="font-size:1rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.07em;margin:0 0 16px;">&#9889; Quick Access -- Most Popular Tools</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:10px;">
                <a href="<?php echo esc_url(home_url('/tools/mortgage-calculator/')); ?>" style="display:flex;align-items:center;gap:8px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;text-decoration:none;color:#CBD5E1;font-size:.85rem;font-weight:600;transition:border-color .2s;" onmouseover="this.style.borderColor='rgba(59,130,246,.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">&#127968; Mortgage Calculator</a>
                <a href="<?php echo esc_url(home_url('/tools/compound-interest-calculator/')); ?>" style="display:flex;align-items:center;gap:8px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;text-decoration:none;color:#CBD5E1;font-size:.85rem;font-weight:600;transition:border-color .2s;" onmouseover="this.style.borderColor='rgba(16,185,129,.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">&#128200; Compound Interest</a>
                <a href="<?php echo esc_url(home_url('/tools/retirement-income-calculator/')); ?>" style="display:flex;align-items:center;gap:8px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;text-decoration:none;color:#CBD5E1;font-size:.85rem;font-weight:600;transition:border-color .2s;" onmouseover="this.style.borderColor='rgba(139,92,246,.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">&#127958;&#65039; Retirement Planner</a>
                <a href="<?php echo esc_url(home_url('/tools/income-tax-calculator/')); ?>" style="display:flex;align-items:center;gap:8px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;text-decoration:none;color:#CBD5E1;font-size:.85rem;font-weight:600;transition:border-color .2s;" onmouseover="this.style.borderColor='rgba(100,116,139,.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">&#129534; Tax Calculator 2026</a>
                <a href="<?php echo esc_url(home_url('/tools/monthly-budget-planner/')); ?>" style="display:flex;align-items:center;gap:8px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;text-decoration:none;color:#CBD5E1;font-size:.85rem;font-weight:600;transition:border-color .2s;" onmouseover="this.style.borderColor='rgba(6,182,212,.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">&#128203; Budget Planner</a>
                <a href="<?php echo esc_url(home_url('/tools/crypto-pl-calculator/')); ?>" style="display:flex;align-items:center;gap:8px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;text-decoration:none;color:#CBD5E1;font-size:.85rem;font-weight:600;transition:border-color .2s;" onmouseover="this.style.borderColor='rgba(249,115,22,.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">&#8383; Crypto P&amp;L Calculator</a>
                <a href="<?php echo esc_url(home_url('/tools/loan-payoff-calculator/')); ?>" style="display:flex;align-items:center;gap:8px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;text-decoration:none;color:#CBD5E1;font-size:.85rem;font-weight:600;transition:border-color .2s;" onmouseover="this.style.borderColor='rgba(239,68,68,.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">&#128179; Debt Payoff Tool</a>
                <a href="<?php echo esc_url(home_url('/all-tools/')); ?>" style="display:flex;align-items:center;gap:8px;background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.2);border-radius:10px;padding:12px 14px;text-decoration:none;color:#10B981;font-size:.85rem;font-weight:700;transition:background .2s;" onmouseover="this.style.background='rgba(16,185,129,.12)'" onmouseout="this.style.background='rgba(16,185,129,.06)'">&#128200; See All 150+ Tools &#x2192;</a>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="fs-cats-cta">
        <div class="container">
            <div class="fs-cats-cta__inner">
                <h2>&#128640; All Tools Are 100% Free</h2>
                <p>No account needed. No credit card. Just open any calculator and start making smarter financial decisions right now.</p>
                <div style="display:flex;gap:14px;flex-wrap:wrap;justify-content:center;">
                    <a href="<?php echo esc_url(home_url('/all-tools/')); ?>" class="fs-btn fs-btn--primary fs-btn--lg">Browse All Free Tools &rarr;</a>
                    <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="fs-btn fs-btn--outline fs-btn--lg">Read Finance Guides</a>
                </div>
            </div>
        </div>
    </section>

</div>

<style>
.fs-cats-page{background:#0A0F1E;min-height:100vh;}

/* Hero */
.fs-cats-hero{padding:80px 0 60px;background:linear-gradient(135deg,#0A0F1E 0%,#131929 100%);border-bottom:1px solid rgba(255,255,255,.07);}
.fs-cats-hero__inner{text-align:center;max-width:680px;margin:0 auto;}
.fs-cats-hero__title{font-size:clamp(2rem,4vw,2.8rem);font-weight:800;color:#fff;margin:16px 0 12px;line-height:1.2;}
.fs-cats-hero__desc{font-size:1.05rem;color:#94A3B8;line-height:1.7;}

/* Grid */
.fs-cats-grid-section{padding:60px 0 80px;}
.fs-cats-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:24px;}

/* Card */
.fs-cat-card{background:#131929;border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:24px;display:flex;flex-direction:column;gap:16px;transition:transform .25s,box-shadow .25s,border-color .25s;}
.fs-cat-card:hover{transform:translateY(-5px);box-shadow:0 20px 50px rgba(0,0,0,.4);border-color:rgba(16,185,129,.25);}
.fs-cat-card__top{display:flex;align-items:center;gap:14px;}
.fs-cat-card__icon{width:52px;height:52px;border-radius:12px;border:1px solid;display:flex;align-items:center;justify-content:center;font-size:1.6rem;flex-shrink:0;}
.fs-cat-card__title{font-size:1.05rem;font-weight:700;color:#F1F5F9;margin:0 0 2px;}
.fs-cat-card__count{font-size:.75rem;color:#64748B;font-weight:600;}
.fs-cat-card__desc{font-size:.875rem;color:#94A3B8;line-height:1.65;margin:0;}
.fs-cat-card__tools{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:6px;}
.fs-cat-card__tools li{display:flex;align-items:center;gap:7px;font-size:.82rem;color:#64748B;}
.fs-cat-card__tools svg{color:#10B981;flex-shrink:0;}
.fs-cat-card__btn{display:inline-flex;align-items:center;gap:6px;font-size:.875rem;font-weight:700;color:var(--accent,#10B981);text-decoration:none;margin-top:auto;padding:10px 0;border-top:1px solid rgba(255,255,255,.06);transition:gap .2s;}
.fs-cat-card__btn:hover{gap:10px;}

/* CTA */
.fs-cats-cta{padding:60px 0;background:linear-gradient(135deg,#0F2027,#1a3a4a);border-top:1px solid rgba(255,255,255,.07);}
.fs-cats-cta__inner{text-align:center;max-width:580px;margin:0 auto;}
.fs-cats-cta__inner h2{font-size:1.9rem;font-weight:800;color:#fff;margin-bottom:12px;}
.fs-cats-cta__inner p{color:#94A3B8;font-size:1rem;line-height:1.7;margin-bottom:28px;}

@media(max-width:640px){.fs-cats-grid{grid-template-columns:1fr;}.fs-cats-hero{padding:50px 0 40px;}}
</style>

<?php get_footer(); ?>
