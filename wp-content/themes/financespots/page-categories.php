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
                // Icon/color/description are curated per real fs_tool_cat slug; everything
                // else (link, count, sample tools) is pulled live from the taxonomy so this
                // grid can never drift out of sync with what tools actually exist.
                $cat_meta = [
                    'loan-calculators'     => [ 'icon' => '&#127968;',        'color' => '#3B82F6', 'desc' => 'Calculate mortgage and loan payments, compare terms, estimate affordability, and plan your next purchase with confidence.' ],
                    'investment-tools'     => [ 'icon' => '&#128200;',        'color' => '#10B981', 'desc' => 'Compound interest, investment returns, portfolio growth, and every calculator you need to grow your wealth.' ],
                    'retirement-planning'  => [ 'icon' => '&#127958;&#65039;', 'color' => '#8B5CF6', 'desc' => 'Plan your retirement savings, calculate your retirement number, and project your 401(k) and IRA growth.' ],
                    'budget-analyzers'     => [ 'icon' => '&#128203;',        'color' => '#06B6D4', 'desc' => 'Build a monthly budget, track spending, apply the 50/30/20 rule, and take full control of your money.' ],
                    'tax-calculators'      => [ 'icon' => '&#129534;',        'color' => '#64748B', 'desc' => 'Estimate your federal and state income tax, find deductions, and plan ahead for tax season 2026.' ],
                    'crypto-tools'         => [ 'icon' => '₿',                'color' => '#F97316', 'desc' => 'Track your crypto profits and losses, calculate DCA returns, and estimate tax liability on trades.' ],
                    'savings-planners'     => [ 'icon' => '&#127974;',        'color' => '#10B981', 'desc' => 'Calculate how much to save, build your emergency fund, and find the best high-yield savings rates.' ],
                    'currency-converters'  => [ 'icon' => '&#128176;',        'color' => '#EAB308', 'desc' => 'Convert currencies, track exchange rates, and plan travel or international payments with live rates.' ],
                ];

                $cat_terms = get_terms( [ 'taxonomy' => 'fs_tool_cat', 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC' ] );
                foreach ( $cat_terms as $term ):
                    $meta = $cat_meta[ $term->slug ] ?? [ 'icon' => '&#129518;', 'color' => '#64748B', 'desc' => $term->description ?: 'Free calculators in this category.' ];
                    $sample_tools = get_posts( [
                        'post_type'      => 'fs_tool',
                        'post_status'    => 'publish',
                        'posts_per_page' => 4,
                        'orderby'        => 'title',
                        'order'          => 'ASC',
                        'tax_query'      => [ [ 'taxonomy' => 'fs_tool_cat', 'field' => 'term_id', 'terms' => $term->term_id ] ],
                    ] );
                ?>
                <div class="fs-cat-card">
                    <div class="fs-cat-card__top">
                        <div class="fs-cat-card__icon" style="background:<?php echo esc_attr( $meta['color'] ); ?>22;border-color:<?php echo esc_attr( $meta['color'] ); ?>44;">
                            <span><?php echo $meta['icon']; ?></span>
                        </div>
                        <div>
                            <h2 class="fs-cat-card__title"><?php echo esc_html( $term->name ); ?></h2>
                            <span class="fs-cat-card__count"><?php echo intval( $term->count ); ?> tools</span>
                        </div>
                    </div>
                    <p class="fs-cat-card__desc"><?php echo esc_html( $meta['desc'] ); ?></p>
                    <ul class="fs-cat-card__tools">
                        <?php foreach ( $sample_tools as $tool ): ?>
                        <li>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            <?php echo esc_html( $tool->post_title ); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="fs-cat-card__btn" style="--accent:<?php echo esc_attr( $meta['color'] ); ?>">
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
                <a href="<?php echo esc_url(home_url('/tool/mortgage-calculator/')); ?>" style="display:flex;align-items:center;gap:8px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;text-decoration:none;color:#CBD5E1;font-size:.85rem;font-weight:600;transition:border-color .2s;" onmouseover="this.style.borderColor='rgba(59,130,246,.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">&#127968; Mortgage Calculator</a>
                <a href="<?php echo esc_url(home_url('/tool/compound-interest-calculator/')); ?>" style="display:flex;align-items:center;gap:8px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;text-decoration:none;color:#CBD5E1;font-size:.85rem;font-weight:600;transition:border-color .2s;" onmouseover="this.style.borderColor='rgba(16,185,129,.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">&#128200; Compound Interest</a>
                <a href="<?php echo esc_url(home_url('/tool/retirement-income-calculator/')); ?>" style="display:flex;align-items:center;gap:8px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;text-decoration:none;color:#CBD5E1;font-size:.85rem;font-weight:600;transition:border-color .2s;" onmouseover="this.style.borderColor='rgba(139,92,246,.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">&#127958;&#65039; Retirement Planner</a>
                <a href="<?php echo esc_url(home_url('/tool/income-tax-calculator/')); ?>" style="display:flex;align-items:center;gap:8px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;text-decoration:none;color:#CBD5E1;font-size:.85rem;font-weight:600;transition:border-color .2s;" onmouseover="this.style.borderColor='rgba(100,116,139,.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">&#129534; Tax Calculator 2026</a>
                <a href="<?php echo esc_url(home_url('/tool/monthly-budget-planner/')); ?>" style="display:flex;align-items:center;gap:8px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;text-decoration:none;color:#CBD5E1;font-size:.85rem;font-weight:600;transition:border-color .2s;" onmouseover="this.style.borderColor='rgba(6,182,212,.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">&#128203; Budget Planner</a>
                <a href="<?php echo esc_url(home_url('/tool/crypto-pl-calculator/')); ?>" style="display:flex;align-items:center;gap:8px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;text-decoration:none;color:#CBD5E1;font-size:.85rem;font-weight:600;transition:border-color .2s;" onmouseover="this.style.borderColor='rgba(249,115,22,.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">&#8383; Crypto P&amp;L Calculator</a>
                <a href="<?php echo esc_url(home_url('/tool/loan-payoff-calculator/')); ?>" style="display:flex;align-items:center;gap:8px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;text-decoration:none;color:#CBD5E1;font-size:.85rem;font-weight:600;transition:border-color .2s;" onmouseover="this.style.borderColor='rgba(239,68,68,.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">&#128179; Debt Payoff Tool</a>
                <a href="<?php echo esc_url(home_url('/all-tools/')); ?>" style="display:flex;align-items:center;gap:8px;background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.2);border-radius:10px;padding:12px 14px;text-decoration:none;color:#10B981;font-size:.85rem;font-weight:700;transition:background .2s;" onmouseover="this.style.background='rgba(16,185,129,.12)'" onmouseout="this.style.background='rgba(16,185,129,.06)'">&#128200; See All <?php echo intval( wp_count_posts('fs_tool')->publish ); ?>+ Tools &#x2192;</a>
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
