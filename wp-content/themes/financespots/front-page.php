<?php
/**
 * Front Page Template -- FinanceSpots Homepage
 *
 * @package financespots
 */

get_header();

// AI Dashboard URL -- used in hero mini-card and promo section
$ai_dash_post = get_page_by_path( 'ai-financial-dashboard', OBJECT, 'fs_tool' );
$ai_dash_url  = $ai_dash_post ? get_permalink( $ai_dash_post->ID ) : home_url( '/tool/ai-financial-dashboard/' );
?>

<!-- ============================================================
     SECTION 1: HERO -- Advanced v3 (Portfolio Dashboard Style)
     ============================================================ -->
<section class="fs-hero" id="home" aria-labelledby="hero-heading">

    <!-- ── Layered Background ── -->
    <div class="fs-hero__bg" aria-hidden="true">
        <div class="fs-hero__grid"></div>
        <div class="fs-hero__orb fs-hero__orb--1"></div>
        <div class="fs-hero__orb fs-hero__orb--2"></div>
        <div class="fs-hero__orb fs-hero__orb--3"></div>
        <canvas class="fs-hero__particles" id="fs-particles" aria-hidden="true"></canvas>
        <!-- Subtle vignette -->
        <div class="fs-hero__vignette"></div>
    </div>

    <div class="container">
        <div class="fs-hero__inner">

            <!-- ══ LEFT CONTENT ══ -->
            <div class="fs-hero__content">

                <!-- Badge -->
                <div class="fs-hero__badge" role="note">
                    <span class="fs-hero__badge-pulse" aria-hidden="true"></span>
                    <span class="fs-hero__badge-text">
                        <?php echo fs_mod( 'fs_hero_badge', 'AI-POWERED FINANCE PLATFORM 2026' ); ?>
                    </span>
                </div>

                <!-- Main Headline -->
                <h1 class="fs-hero__title" id="hero-heading">
                    <span class="fs-hero__title-line fs-hero__title-line--white">
                        <?php echo fs_mod( 'fs_hero_title_1', 'Smart Financial' ); ?>
                    </span>
                    <span class="fs-hero__title-line fs-hero__title-line--white">
                        <?php echo fs_mod( 'fs_hero_title_2', 'Tools' ); ?>
                    </span>
                    <span class="fs-hero__title-line fs-hero__title-line--mixed">
                        <?php esc_html_e( 'for', 'financespots' ); ?>
                        <span class="fs-hero__title-accent">
                            <?php echo fs_mod( 'fs_hero_title_3', 'Intelligent' ); ?>
                        </span>
                    </span>
                    <span class="fs-hero__title-line fs-hero__title-line--white">
                        <?php echo fs_mod( 'fs_hero_title_4', 'Investing' ); ?>
                    </span>
                </h1>

                <!-- Subtitle -->
                <p class="fs-hero__sub">
                    <?php echo fs_mod( 'fs_hero_sub', 'Professional-grade financial tools powered by AI -- from portfolio analysis to tax optimization. 110+ tools, completely free.' ); ?>
                </p>

                <!-- CTA Row -->
                <div class="fs-hero__cta-group">
                    <a href="<?php echo fs_mod_url( 'fs_hero_cta_url', '#tools' ); ?>"
                       class="fs-btn fs-btn--primary fs-btn--lg fs-btn--glow">
                        <?php echo fs_mod( 'fs_hero_cta_text', 'Explore All Tools' ); ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    <a href="<?php echo fs_mod_url( 'fs_hero_cta2_url', '#how-it-works' ); ?>"
                       class="fs-btn fs-btn--ghost fs-btn--lg">
                        <span class="fs-play-icon" aria-hidden="true">
                            <svg width="10" height="12" viewBox="0 0 10 12" fill="currentColor" aria-hidden="true"><path d="M0 0l10 6-10 6z"/></svg>
                        </span>
                        <?php echo fs_mod( 'fs_hero_cta2_text', 'How It Works' ); ?>
                    </a>
                </div>

                <!-- Social proof -->
                <div class="fs-hero__social-proof">
                    <div class="fs-hero__avatars" aria-hidden="true">
                        <?php
                        $colors = ['#00C896','#3B82F6','#F59E0B','#EF4444','#8B5CF6'];
                        $initials = ['S','M','P','D','+'];
                        foreach ($initials as $idx => $init) :
                        ?>
                        <span class="fs-avatar" style="background:<?php echo $colors[$idx]; ?>"><?php echo $init; ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="fs-hero__social-proof-text">
                        <span><?php esc_html_e( 'Trusted by', 'financespots' ); ?> <strong>50,000+</strong> <?php esc_html_e( 'users worldwide', 'financespots' ); ?></span>
                        <div class="fs-hero__rating" aria-label="4.9 out of 5 stars">
                            <span class="fs-hero__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                            <span>4.9/5</span>
                        </div>
                    </div>
                </div>

            </div><!-- /.fs-hero__content -->

            <!-- ══ RIGHT VISUAL -- Portfolio Dashboard Card ══ -->
            <div class="fs-hero__visual" aria-hidden="true">

                <!-- Main portfolio card -->
                <div class="fs-portfolio-card" id="fs-portfolio-card">
                    <div class="fs-portfolio-card__header">
                        <span class="fs-portfolio-card__label">PORTFOLIO OVERVIEW</span>
                        <span class="fs-portfolio-live">
                            <span class="fs-live-dot"></span>
                            Live
                        </span>
                    </div>

                    <div class="fs-portfolio-card__value-row">
                        <div>
                            <div class="fs-portfolio-card__total" id="fs-portfolio-total">248,420</div>
                            <div class="fs-portfolio-card__change">
                                <span class="fs-portfolio-card__change-icon">▲</span>
                                <span id="fs-portfolio-change">+$18,420 this month (+8.2%)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sparkline Chart -->
                    <div class="fs-portfolio-card__chart">
                        <canvas id="fs-portfolio-chart" width="480" height="140" aria-label="Portfolio performance chart"></canvas>
                    </div>

                    <!-- Mini stats row -->
                    <div class="fs-portfolio-card__stats">
                        <div class="fs-portfolio-stat">
                            <span class="fs-portfolio-stat__label">24h Change</span>
                            <span class="fs-portfolio-stat__val fs-portfolio-stat__val--up">+2.4%</span>
                        </div>
                        <div class="fs-portfolio-stat">
                            <span class="fs-portfolio-stat__label">7d Change</span>
                            <span class="fs-portfolio-stat__val fs-portfolio-stat__val--up">+8.2%</span>
                        </div>
                        <div class="fs-portfolio-stat">
                            <span class="fs-portfolio-stat__label">30d Change</span>
                            <span class="fs-portfolio-stat__val fs-portfolio-stat__val--up">+14.7%</span>
                        </div>
                        <div class="fs-portfolio-stat">
                            <span class="fs-portfolio-stat__label">Sharpe Ratio</span>
                            <span class="fs-portfolio-stat__val">1.84</span>
                        </div>
                    </div>

                    <!-- AI Insight row -->
                    <div class="fs-portfolio-card__ai-row">
                        <span class="fs-portfolio-card__ai-icon">&#129302;</span>
                        <span class="fs-portfolio-card__ai-text" id="fs-ai-insight-text">
                            AI Insight: Your portfolio is outperforming the S&amp;P 500 by 6.3% this month.
                        </span>
                    </div>
                </div>

                <!-- Secondary mini cards -->
                <div class="fs-hero__mini-cards">
                    <!-- Mini card 1: BTC -->
                    <div class="fs-mini-card fs-mini-card--1">
                        <div class="fs-mini-card__top">
                            <span class="fs-mini-card__name">Bitcoin</span>
                            <span class="fs-mini-card__badge fs-mini-card__badge--up">+3.4%</span>
                        </div>
                        <div class="fs-mini-card__price" id="fs-btc-price">$103,240</div>
                        <canvas class="fs-mini-chart" id="fs-mini-chart-1" width="120" height="36" aria-hidden="true"></canvas>
                    </div>

                    <!-- Mini card 2: AI Dashboard -->
                    <a href="<?php echo esc_url( $ai_dash_url ); ?>" class="fs-mini-card fs-mini-card--2" style="text-decoration:none;cursor:pointer;display:flex;">
                        <div class="fs-mini-card__icon">&#129302;</div>
                        <div class="fs-mini-card__info">
                            <span class="fs-mini-card__title">AI Dashboard</span>
                            <span class="fs-mini-card__sub">Open now &#x2192;</span>
                        </div>
                    </a>
                </div>

                <!-- Floating badges -->
                <?php if ( get_theme_mod( 'fs_badge1_enable', '1' ) ) : ?>
                <a href="<?php echo esc_url( get_theme_mod( 'fs_badge1_url', '#tools' ) ); ?>"
                   class="fs-float-badge fs-float-badge--1"
                   id="fs-badge1">
                    <span class="fs-float-badge__icon" aria-hidden="true">&#128200;</span>
                    <span id="fs-badge1-text"><?php echo esc_html( get_theme_mod( 'fs_badge1_text', 'ROI up 24%' ) ); ?></span>
                </a>
                <?php endif; ?>
                <?php if ( get_theme_mod( 'fs_badge3_enable', '1' ) ) : ?>
                <a href="<?php echo esc_url( get_theme_mod( 'fs_badge3_url', '/about/' ) ); ?>"
                   class="fs-float-badge fs-float-badge--3"
                   id="fs-badge3">
                    <span class="fs-float-badge__icon" aria-hidden="true">&#128274;</span>
                    <span id="fs-badge3-text"><?php echo esc_html( get_theme_mod( 'fs_badge3_text', 'Bank-level Security' ) ); ?></span>
                </a>
                <?php endif; ?>

            </div><!-- /.fs-hero__visual -->

        </div><!-- /.fs-hero__inner -->
    </div><!-- /.container -->

    <!-- Bottom wave -->
    <div class="fs-hero__wave" aria-hidden="true">
        <svg viewBox="0 0 1440 90" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,45 C240,90 480,0 720,45 C960,90 1200,10 1440,45 L1440,90 L0,90 Z" fill="#F8FAFC" opacity=".98"/>
        </svg>
    </div>

</section>

<!-- ============================================================
     SECTION 2: STATS / COUNTERS
     ============================================================ -->
<section class="fs-stats" aria-label="<?php esc_attr_e( 'Platform Statistics', 'financespots' ); ?>">
    <div class="fs-stats__wrapper">
        <div class="container">
            <div class="fs-stats__grid">
                <?php
                $stats = [
                    [
                        'number' => get_theme_mod('fs_stat1_number','2500000'),
                        'label'  => get_theme_mod('fs_stat1_label','Calculations Done'),
                        'icon'   => '&#128202;',
                        'suffix' => '+',
                    ],
                    [
                        'number' => get_theme_mod('fs_stat2_number', (string) wp_count_posts('fs_tool')->publish ),
                        'label'  => get_theme_mod('fs_stat2_label','Finance Tools'),
                        'icon'   => '&#128736;&#65039;',
                        'suffix' => '+',
                    ],
                    [
                        'number' => get_theme_mod('fs_stat3_number','50000'),
                        'label'  => get_theme_mod('fs_stat3_label','Active Users'),
                        'icon'   => '&#128101;',
                        'suffix' => '+',
                    ],
                    [
                        'number' => get_theme_mod('fs_stat4_number','99'),
                        'label'  => get_theme_mod('fs_stat4_label','% Accuracy Rate'),
                        'icon'   => '&#127919;',
                        'suffix' => '%',
                    ],
                ];
                foreach ( $stats as $s ) :
                ?>
                <div class="fs-stat-card" data-animate="fade-up">
                    <div class="fs-stat-card__icon-wrap" aria-hidden="true">
                        <span class="fs-stat-card__icon"><?php echo esc_html( $s['icon'] ); ?></span>
                    </div>
                    <div class="fs-stat-card__number">
                        <span
                            class="fs-counter"
                            data-target="<?php echo esc_attr( preg_replace('/\D/','',$s['number']) ); ?>"
                            data-suffix="<?php echo esc_attr( $s['suffix'] ); ?>"
                        >0</span>
                    </div>
                    <p class="fs-stat-card__label"><?php echo esc_html( $s['label'] ); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="fs-stats__divider"></div>
</section>

<!-- ============================================================
     SECTION 2.5: AI FINANCIAL DASHBOARD PROMO
     ============================================================ -->
<section class="fs-ai-promo" id="ai-dashboard" aria-labelledby="ai-promo-heading">

<div class="container">
    <div class="fs-ai-promo__inner">

        <!-- LEFT: Copy -->
        <div class="fs-ai-promo__content">
            <div class="fs-ai-promo__badge">
                <span class="fs-ai-promo__badge-dot"></span>
                New -- AI-Powered Tool
            </div>
            <h2 class="fs-ai-promo__title" id="ai-promo-heading">
                Meet Your Personal<br>
                <span class="accent">AI Financial Advisor</span>
            </h2>
            <p class="fs-ai-promo__desc">
                One dashboard that analyzes your complete financial life -- budget, debt, investments, taxes, retirement, and net worth. All powered by real AI, completely free. Built by Abdul Rahman for FinanceSpots.
            </p>
            <div class="fs-ai-promo__features">
                <div class="fs-ai-promo__feature">
                    <div class="fs-ai-promo__feature-icon">&#129504;</div>
                    <span><strong style="color:#e2e8f0">Real AI Chat</strong> -- Ask any finance question, get instant expert answers powered by Claude AI</span>
                </div>
                <div class="fs-ai-promo__feature">
                    <div class="fs-ai-promo__feature-icon">&#128202;</div>
                    <span><strong style="color:#e2e8f0">9 Powerful Panels</strong> -- Budget, Debt Optimizer, Net Worth, Tax, Retirement, Investment & more</span>
                </div>
                <div class="fs-ai-promo__feature">
                    <div class="fs-ai-promo__feature-icon">&#9889;</div>
                    <span><strong style="color:#e2e8f0">Live Score</strong> -- Get your personalized Financial Health Score in seconds</span>
                </div>
                <div class="fs-ai-promo__feature">
                    <div class="fs-ai-promo__feature-icon">&#128196;</div>
                    <span><strong style="color:#e2e8f0">PDF Export</strong> -- Download your complete financial report instantly</span>
                </div>
            </div>
            <div style="display:flex;align-items:center;flex-wrap:wrap;gap:.5rem">
                <a href="<?php echo esc_url( $ai_dash_url ); ?>" class="fs-ai-promo__cta">
                    Open AI Dashboard
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="<?php echo esc_url( $ai_dash_url ); ?>#ai-chat" class="fs-ai-promo__cta-ghost">
                    Try AI Chat &#x2192;
                </a>
            </div>
        </div>

        <!-- RIGHT: Preview Card -->
        <div class="fs-ai-promo__preview">
            <div class="fs-ai-promo__glow"></div>
            <div class="fs-ai-dash-card">
                <div class="fs-ai-dash-card__header">
                    <div class="fs-ai-dash-card__dots">
                        <span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span>
                    </div>
                    <span class="fs-ai-dash-card__tab">&#9889; FinanceSpots AI Dashboard</span>
                    <span style="margin-left:auto;background:rgba(0,200,150,.15);color:#00C896;font-size:.65rem;font-weight:700;padding:.2rem .6rem;border-radius:10px;">&#129302; AI Active</span>
                </div>
                <div class="fs-ai-dash-card__body">
                    <!-- Metrics row -->
                    <div class="fs-ai-metric-row">
                        <div class="fs-ai-metric">
                            <div class="fs-ai-metric__val green">87</div>
                            <div class="fs-ai-metric__lbl">Health Score</div>
                        </div>
                        <div class="fs-ai-metric">
                            <div class="fs-ai-metric__val blue">22%</div>
                            <div class="fs-ai-metric__lbl">Savings Rate</div>
                        </div>
                        <div class="fs-ai-metric">
                            <div class="fs-ai-metric__val purple">68%</div>
                            <div class="fs-ai-metric__lbl">Retire Ready</div>
                        </div>
                    </div>
                    <!-- Bars -->
                    <div class="fs-ai-bar-row">
                        <div class="fs-ai-bar-label"><span>Emergency Fund</span><span style="color:#00C896">&#9989; 6 months</span></div>
                        <div class="fs-ai-bar-track"><div class="fs-ai-bar-fill" style="width:90%;background:linear-gradient(90deg,#00C896,#00A87A)"></div></div>
                    </div>
                    <div class="fs-ai-bar-row">
                        <div class="fs-ai-bar-label"><span>Debt-to-Income</span><span style="color:#F59E0B">28%</span></div>
                        <div class="fs-ai-bar-track"><div class="fs-ai-bar-fill" style="width:28%;background:linear-gradient(90deg,#F59E0B,#D97706)"></div></div>
                    </div>
                    <div class="fs-ai-bar-row">
                        <div class="fs-ai-bar-label"><span>Investment Rate</span><span style="color:#3B82F6">15%</span></div>
                        <div class="fs-ai-bar-track"><div class="fs-ai-bar-fill" style="width:75%;background:linear-gradient(90deg,#3B82F6,#2563EB)"></div></div>
                    </div>
                    <!-- AI Chat preview -->
                    <div class="fs-ai-chat-preview">
                        <div class="fs-ai-chat-preview__label">&#129302; AI Advisor -- Abdul Rahman's Dashboard</div>
                        <div class="fs-ai-chat-preview__msg">Your debt-to-income ratio of 28% is healthy. I recommend increasing your 401k contribution by $300/month to reach the IRS limit -- this saves you $792/year in taxes at your 22% bracket.</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</section>

<!-- ============================================================
     SECTION 3: WHY CHOOSE US / FEATURES
     ============================================================ -->
<section class="fs-features" id="features" aria-labelledby="features-heading">
    <div class="container">
        <div class="fs-section-header" data-animate="fade-up">
            <span class="fs-badge fs-badge--primary">&#128640; <?php esc_html_e( 'Why FinanceSpots', 'financespots' ); ?></span>
            <h2 class="fs-section-title" id="features-heading">
                <?php esc_html_e( 'Built Different. Built Better.', 'financespots' ); ?>
            </h2>
            <p class="fs-section-desc">
                <?php esc_html_e( 'We studied Calculator.net, SmartAsset, and NerdWallet -- then built something faster, smarter, and more beautiful than all of them.', 'financespots' ); ?>
            </p>
        </div>

        <div class="fs-features__grid">
            <?php foreach ( fs_get_features() as $i => $f ) : ?>
            <article class="fs-feature-card" data-animate="fade-up" data-delay="<?php echo $i * 100; ?>">
                <div class="fs-feature-card__icon" aria-hidden="true"><?php echo esc_html( $f['icon'] ); ?></div>
                <h3 class="fs-feature-card__title"><?php echo esc_html( $f['title'] ); ?></h3>
                <p class="fs-feature-card__desc"><?php echo esc_html( $f['desc'] ); ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     SECTION 4: TOOL CATEGORIES (Filterable)
     ============================================================ -->
<section class="fs-categories" id="categories" aria-labelledby="categories-heading">
    <div class="fs-categories__bg" aria-hidden="true">
        <div class="fs-categories__bg-shape"></div>
    </div>
    <div class="container">
        <div class="fs-section-header" data-animate="fade-up">
            <span class="fs-badge fs-badge--secondary">&#128450;&#65039; <?php printf( esc_html__( '%d+ Tools Available', 'financespots' ), wp_count_posts('fs_tool')->publish ); ?></span>
            <h2 class="fs-section-title" id="categories-heading">
                <?php esc_html_e( 'Explore by Category', 'financespots' ); ?>
            </h2>
            <p class="fs-section-desc">
                <?php esc_html_e( 'Every financial tool you\'ll ever need, organized for instant discovery.', 'financespots' ); ?>
            </p>
        </div>

        <!-- Category filter tabs -->
        <div class="fs-filter-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Filter tool categories', 'financespots' ); ?>">
            <button class="fs-filter-tab active" data-filter="all" role="tab" aria-selected="true">
                <?php esc_html_e( 'All Tools', 'financespots' ); ?>
            </button>
            <button class="fs-filter-tab" data-filter="popular" role="tab" aria-selected="false">
                <?php esc_html_e( '&#128293; Popular', 'financespots' ); ?>
            </button>
            <button class="fs-filter-tab" data-filter="new" role="tab" aria-selected="false">
                <?php esc_html_e( '&#10024; New', 'financespots' ); ?>
            </button>
            <button class="fs-filter-tab" data-filter="ai" role="tab" aria-selected="false">
                <?php esc_html_e( '&#129302; AI Enhanced', 'financespots' ); ?>
            </button>
        </div>

        <div class="fs-categories__grid" id="fs-categories-grid">
            <?php
            $cat_slug_map = [
                'loans'      => 'loan-calculators',
                'investment' => 'investment-tools',
                'tax'        => 'tax-calculators',
                'savings'    => 'savings-planners',
                'retirement' => 'retirement-planning',
                'currency'   => 'currency-converters',
                'budget'     => 'budget-analyzers',
                'crypto'     => 'crypto-tools',
            ];
            foreach ( fs_get_tool_categories() as $i => $cat ) :
                $tax_slug     = $cat_slug_map[ $cat['slug'] ] ?? $cat['slug'];
                $tax_term     = get_term_by( 'slug', $tax_slug, 'fs_tool_cat' );
                $cat_url      = $tax_term ? get_term_link( $tax_term ) : home_url( '/tools/' . $tax_slug . '/' );
                // Use real DB count if term exists
                if ( $tax_term && $tax_term->count > 0 ) $cat['count'] = $tax_term->count;
            ?>
            <article
                class="fs-cat-card fs-cat-card--<?php echo esc_attr( $cat['color'] ); ?>"
                data-animate="fade-up"
                data-delay="<?php echo $i * 80; ?>"
                data-category="<?php echo esc_attr( $cat['slug'] ); ?>"
            >
                <a href="<?php echo esc_url( $cat_url ); ?>" class="fs-cat-card__link" aria-label="<?php echo esc_attr( $cat['name'] ) . ' -- ' . esc_attr( $cat['count'] ) . ' tools'; ?>">
                    <div class="fs-cat-card__icon-wrap" aria-hidden="true">
                        <span class="fs-cat-card__icon"><?php echo esc_html( $cat['icon'] ); ?></span>
                    </div>
                    <div class="fs-cat-card__content">
                        <h3 class="fs-cat-card__name"><?php echo esc_html( $cat['name'] ); ?></h3>
                        <p class="fs-cat-card__desc"><?php echo esc_html( $cat['desc'] ); ?></p>
                        <span class="fs-cat-card__count">
                            <?php echo esc_html( $cat['count'] ); ?> <?php esc_html_e( 'tools', 'financespots' ); ?>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
            </article>
            <?php endforeach; ?>
        </div>

        <div class="fs-section-cta" data-animate="fade-up">
            <a href="#tools" class="fs-btn fs-btn--outline">
                <?php printf( esc_html__( 'View All %d+ Tools', 'financespots' ), wp_count_posts('fs_tool')->publish ); ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     SECTION 5: POPULAR TOOLS
     ============================================================ -->
<section class="fs-popular-tools" id="tools" aria-labelledby="tools-heading">
    <div class="container">
        <div class="fs-section-header" data-animate="fade-up">
            <span class="fs-badge fs-badge--gold">&#11088; <?php esc_html_e( 'Top Rated', 'financespots' ); ?></span>
            <h2 class="fs-section-title" id="tools-heading">
                <?php esc_html_e( 'Most Popular Tools', 'financespots' ); ?>
            </h2>
            <p class="fs-section-desc">
                <?php esc_html_e( 'The finance tools our users love most -- handpicked, expert-verified, and AI-enhanced.', 'financespots' ); ?>
            </p>
        </div>

        <div class="fs-tools__grid">
            <?php foreach ( fs_get_popular_tools() as $i => $tool ) :
                $tool_url  = fs_get_tool_url_by_title( $tool['name'], $tool['cat'] ?? '' );
                $cat_term  = isset($tool['cat']) ? get_term_by('slug', $tool['cat'], 'fs_tool_cat') : null;
                $cat_label = $cat_term ? $cat_term->name : ( $tool['category'] ?? '' );
                $cat_url   = $cat_term ? get_term_link($cat_term) : home_url('/tools/');
            ?>
            <article
                class="fs-tool-card"
                data-animate="fade-up"
                data-delay="<?php echo $i * 100; ?>"
            >
                <div class="fs-tool-card__header">
                    <div class="fs-tool-card__icon-wrap" aria-hidden="true">
                        <span class="fs-tool-card__icon"><?php echo esc_html( $tool['icon'] ); ?></span>
                    </div>
                    <span class="fs-tool-badge fs-tool-badge--<?php echo esc_attr( $tool['badge_color'] ); ?>">
                        <?php echo esc_html( $tool['badge'] ); ?>
                    </span>
                </div>
                <div class="fs-tool-card__body">
                    <a href="<?php echo esc_url($cat_url); ?>" class="fs-tool-card__category"
                       style="text-decoration:none;color:inherit">
                        <?php echo esc_html( $cat_label ); ?>
                    </a>
                    <h3 class="fs-tool-card__name"><?php echo esc_html( $tool['name'] ); ?></h3>
                    <p class="fs-tool-card__desc"><?php echo esc_html( $tool['desc'] ); ?></p>
                </div>
                <div class="fs-tool-card__footer">
                    <a href="<?php echo esc_url( $tool_url ); ?>" class="fs-tool-card__link">
                        <?php esc_html_e( 'Use Tool', 'financespots' ); ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    <button class="fs-tool-card__save" aria-label="Save tool">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                    </button>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <!-- Widget area for additional tool cards -->
        <?php if ( is_active_sidebar( 'homepage-tools' ) ) : ?>
        <div class="fs-tools-widget-area">
            <?php dynamic_sidebar( 'homepage-tools' ); ?>
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- ============================================================
     SECTION 7: HOW IT WORKS
     ============================================================ -->
<section class="fs-how-it-works" id="how-it-works" aria-labelledby="hiw-heading">
    <div class="container">
        <div class="fs-section-header" data-animate="fade-up">
            <span class="fs-badge fs-badge--primary">&#128506;&#65039; <?php esc_html_e( 'Simple Process', 'financespots' ); ?></span>
            <h2 class="fs-section-title" id="hiw-heading">
                <?php esc_html_e( 'How FinanceSpots Works', 'financespots' ); ?>
            </h2>
            <p class="fs-section-desc">
                <?php esc_html_e( 'Get professional-grade financial insights in four simple steps.', 'financespots' ); ?>
            </p>
        </div>

        <div class="fs-steps">
            <!-- Connector line -->
            <div class="fs-steps__connector" aria-hidden="true"></div>

            <?php foreach ( fs_get_steps() as $i => $step ) : ?>
            <div class="fs-step" data-animate="fade-up" data-delay="<?php echo $i * 150; ?>">
                <div class="fs-step__number" aria-hidden="true"><?php echo esc_html( $step['num'] ); ?></div>
                <div class="fs-step__content">
                    <h3 class="fs-step__title"><?php echo esc_html( $step['title'] ); ?></h3>
                    <p class="fs-step__desc"><?php echo esc_html( $step['desc'] ); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     SECTION 8: TESTIMONIALS
     ============================================================ -->
<section class="fs-testimonials" id="testimonials" aria-labelledby="testimonials-heading">
    <div class="fs-testimonials__bg" aria-hidden="true">
        <div class="fs-testimonials__bg-orb"></div>
    </div>
    <div class="container">
        <div class="fs-section-header" data-animate="fade-up">
            <span class="fs-badge fs-badge--gold">&#11088; <?php esc_html_e( '4.9/5 Average Rating', 'financespots' ); ?></span>
            <h2 class="fs-section-title" id="testimonials-heading">
                <?php esc_html_e( 'Trusted by 50,000+ Users', 'financespots' ); ?>
            </h2>
            <p class="fs-section-desc">
                <?php esc_html_e( 'Real people, real results. See how FinanceSpots transforms financial decision-making.', 'financespots' ); ?>
            </p>
        </div>

        <div class="fs-testimonials__carousel" id="fs-testimonials-carousel" role="region" aria-label="<?php esc_attr_e( 'User Testimonials', 'financespots' ); ?>">
            <div class="fs-testimonials__track" id="fs-testimonials-track">
                <?php foreach ( fs_get_testimonials() as $i => $t ) : ?>
                <article class="fs-testimonial-card fs-glass" data-animate="fade-up" data-delay="<?php echo $i * 100; ?>">
                    <div class="fs-testimonial-card__stars" aria-label="<?php echo esc_attr( sprintf( '%d out of 5 stars', $t['rating'] ) ); ?>">
                        <?php echo str_repeat( '&#9733;', $t['rating'] ); ?>
                    </div>
                    <blockquote class="fs-testimonial-card__text">
                        <p><?php echo esc_html( $t['text'] ); ?></p>
                    </blockquote>
                    <footer class="fs-testimonial-card__author">
                        <div class="fs-testimonial-card__avatar" aria-hidden="true">
                            <?php echo esc_html( $t['avatar'] ); ?>
                        </div>
                        <div>
                            <cite class="fs-testimonial-card__name"><?php echo esc_html( $t['name'] ); ?></cite>
                            <span class="fs-testimonial-card__role"><?php echo esc_html( $t['role'] ); ?></span>
                        </div>
                    </footer>
                </article>
                <?php endforeach; ?>
            </div>

            <!-- Carousel Controls -->
            <div class="fs-testimonials__controls">
                <button class="fs-carousel-btn" id="fs-carousel-prev" aria-label="<?php esc_attr_e( 'Previous testimonial', 'financespots' ); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                </button>
                <div class="fs-carousel-dots" id="fs-carousel-dots" role="tablist" aria-label="<?php esc_attr_e( 'Testimonial navigation dots', 'financespots' ); ?>"></div>
                <button class="fs-carousel-btn" id="fs-carousel-next" aria-label="<?php esc_attr_e( 'Next testimonial', 'financespots' ); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        <!-- Trust Strip -- DISABLED -->
        <?php if(false): // Trust strip hidden ?>
        <!-- Trust Strip -- Full Control via WP Admin > FinanceSpots > Trust Strip -->
        <?php
        $ts = get_option('fs_trust_settings', []);
        $label      = !empty($ts['label'])   ? $ts['label']   : 'AS SEEN & TRUSTED BY READERS FROM';
        $speed      = !empty($ts['speed'])   ? (int)$ts['speed'] : 28;
        $pause      = isset($ts['pause'])    ? (bool)$ts['pause'] : true;
        $show_stats = isset($ts['show_stats'])? (bool)$ts['show_stats'] : true;
        $outlets    = !empty($ts['outlets']) ? $ts['outlets']  : [
            ['name'=>'Forbes',              'icon'=>'F',  'color'=>'#EF4444'],
            ['name'=>'Bloomberg',           'icon'=>'B',  'color'=>'#F59E0B'],
            ['name'=>'TechCrunch',          'icon'=>'TC', 'color'=>'#10B981'],
            ['name'=>'Wall Street Journal', 'icon'=>'W',  'color'=>'#3B82F6'],
            ['name'=>'Business Insider',    'icon'=>'BI', 'color'=>'#8B5CF6'],
            ['name'=>'CNBC',                'icon'=>'C',  'color'=>'#F97316'],
            ['name'=>'Reuters',             'icon'=>'R',  'color'=>'#06B6D4'],
            ['name'=>'MarketWatch',         'icon'=>'MW', 'color'=>'#EC4899'],
        ];
        $stats = !empty($ts['stats']) ? $ts['stats'] : [
            ['num'=>'50,000+', 'lbl'=>'Monthly Users'],
            ['num'=>'4.9&#9733;',    'lbl'=>'Average Rating'],
            ['num'=>'30+',     'lbl'=>'Free Tools'],
            ['num'=>'100%',    'lbl'=>'Free Forever'],
        ];
        $accent = !empty($ts['accent']) ? $ts['accent'] : '#10B981';
        $bg     = !empty($ts['bg'])     ? $ts['bg']     : 'rgba(255,255,255,0.03)';
        ?>
        <div class="fs-trust3" data-animate="fade-up" data-speed="<?php echo $speed; ?>" data-pause="<?php echo $pause ? '1' : '0'; ?>">
            <p class="fs-trust3__label"><?php echo esc_html($label); ?></p>
            <div class="fs-trust3__wrap">
                <div class="fs-trust3__track" id="fs-trust3-track">
                    <?php for($lp=0;$lp<2;$lp++): foreach($outlets as $o): ?>
                    <div class="fs-trust3__item">
                        <span class="fs-trust3__badge" style="background:<?php echo esc_attr($o['color']); ?>15;border-color:<?php echo esc_attr($o['color']); ?>40;color:<?php echo esc_attr($o['color']); ?>"><?php echo esc_html($o['icon']); ?></span>
                        <span class="fs-trust3__name"><?php echo esc_html($o['name']); ?></span>
                    </div>
                    <?php endforeach; endfor; ?>
                </div>
                <div class="fs-trust3__fade fs-trust3__fade--l"></div>
                <div class="fs-trust3__fade fs-trust3__fade--r"></div>
            </div>
            <?php if($show_stats && !empty($stats)): ?>
            <div class="fs-trust3__stats">
                <?php foreach($stats as $i=>$st): ?>
                <?php if($i>0): ?><div class="fs-trust3__div"></div><?php endif; ?>
                <div class="fs-trust3__stat">
                    <strong style="color:<?php echo esc_attr($accent); ?>"><?php echo esc_html($st['num']); ?></strong>
                    <?php echo esc_html($st['lbl']); ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <style>
        .fs-trust3{padding:52px 0 24px;text-align:center;}
        .fs-trust3__label{font-size:.66rem;font-weight:800;letter-spacing:.2em;color:#334155;margin-bottom:30px;text-transform:uppercase;}
        .fs-trust3__wrap{position:relative;overflow:hidden;margin-bottom:36px;padding:4px 0;}
        .fs-trust3__track{display:flex;width:max-content;}
        @keyframes trust3-scroll{from{transform:translateX(0)}to{transform:translateX(-50%)}}
        .fs-trust3__item{display:flex;align-items:center;gap:12px;padding:10px 36px;border-right:1px solid rgba(255,255,255,.04);}
        .fs-trust3__badge{width:38px;height:38px;border-radius:10px;border:1px solid;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:900;flex-shrink:0;transition:transform .2s;}
        .fs-trust3__item:hover .fs-trust3__badge{transform:scale(1.1);}
        .fs-trust3__name{font-size:1rem;font-weight:700;color:#94A3B8;white-space:nowrap;letter-spacing:.01em;transition:color .2s;}
        .fs-trust3__item:hover .fs-trust3__name{color:#CBD5E1;}
        .fs-trust3__fade{position:absolute;top:0;bottom:0;width:140px;pointer-events:none;z-index:2;}
        .fs-trust3__fade--l{left:0;background:linear-gradient(90deg,#0A0F1E 30%,transparent);}
        .fs-trust3__fade--r{right:0;background:linear-gradient(-90deg,#0A0F1E 30%,transparent);}
        .fs-trust3__stats{display:inline-flex;align-items:center;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:16px;padding:14px 8px;flex-wrap:wrap;justify-content:center;}
        .fs-trust3__stat{padding:6px 28px;font-size:.82rem;color:#64748B;line-height:1.3;}
        .fs-trust3__stat strong{display:block;font-size:1.2rem;font-weight:900;margin-bottom:3px;}
        .fs-trust3__div{width:1px;height:34px;background:rgba(255,255,255,.07);}
        @media(max-width:600px){.fs-trust3__div{display:none;}.fs-trust3__stat{padding:8px 16px;}}
        </style>
        <script>
        (function(){
            var wrap  = document.querySelector('.fs-trust3');
            var track = document.getElementById('fs-trust3-track');
            if(!wrap || !track) return;
            var speed = parseInt(wrap.getAttribute('data-speed')) || 60;
            var pause = wrap.getAttribute('data-pause') === '1';
            // Apply animation via JS so it always uses DB value
            track.style.animation = 'trust3-scroll ' + speed + 's linear infinite';
            if(pause){
                track.addEventListener('mouseenter', function(){ track.style.animationPlayState = 'paused'; });
                track.addEventListener('mouseleave', function(){ track.style.animationPlayState = 'running'; });
            }
        })();
        </script>
        <?php endif; // end trust strip hidden ?>
    </div>
</section>

<!-- ============================================================
     SECTION 9: BLOG POSTS (latest 3)
     ============================================================ -->
<?php
$blog_query = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

if ( $blog_query->have_posts() ) :
?>
<section class="fs-blog" id="blog" aria-labelledby="blog-heading">
    <div class="container">
        <div class="fs-section-header" data-animate="fade-up">
            <span class="fs-badge fs-badge--secondary">&#128221; <?php esc_html_e( 'Finance Insights', 'financespots' ); ?></span>
            <h2 class="fs-section-title" id="blog-heading">
                <?php esc_html_e( 'Latest from the Blog', 'financespots' ); ?>
            </h2>
            <p class="fs-section-desc">
                <?php esc_html_e( 'Expert analysis, tips, and guides to help you make smarter money moves.', 'financespots' ); ?>
            </p>
        </div>

        <div class="fs-blog__grid">
            <?php
            $i = 0;
            while ( $blog_query->have_posts() ) :
                $blog_query->the_post();
            ?>
            <article class="fs-blog-card" data-animate="fade-up" data-delay="<?php echo $i * 120; ?>">
                <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>" class="fs-blog-card__thumb-link" tabindex="-1" aria-hidden="true">
                    <?php the_post_thumbnail( 'financespots-tool-thumb', [ 'class' => 'fs-blog-card__thumb', 'alt' => get_the_title() ] ); ?>
                </a>
                <?php endif; ?>
                <div class="fs-blog-card__body">
                    <div class="fs-blog-card__meta">
                        <time datetime="<?php echo get_the_date( 'c' ); ?>" class="fs-blog-card__date">
                            <?php echo get_the_date(); ?>
                        </time>
                        <?php
                        $cats = get_the_category();
                        if ( $cats ) echo '<span class="fs-blog-card__cat">' . esc_html( $cats[0]->name ) . '</span>';
                        ?>
                    </div>
                    <h3 class="fs-blog-card__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <p class="fs-blog-card__excerpt"><?php the_excerpt(); ?></p>
                    <a href="<?php the_permalink(); ?>" class="fs-blog-card__link">
                        <?php esc_html_e( 'Read Article', 'financespots' ); ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>
            <?php $i++; endwhile; wp_reset_postdata(); ?>
        </div>

        <div class="fs-section-cta" data-animate="fade-up">
            <a href="<?php echo esc_url( get_permalink( get_option('page_for_posts') ) ?: home_url('/blog') ); ?>" class="fs-btn fs-btn--outline">
                <?php esc_html_e( 'View All Articles', 'financespots' ); ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============================================================
     SECTION: EXPLORE BY CATEGORY (Internal Linking)
     ============================================================ -->
<section class="fs-explore-cats" aria-labelledby="explore-cats-heading" style="padding:2rem 0 1.75rem;background:#F8FAFC;border-top:1px solid #E2E8F0;">
    <div class="container">
        <h2 id="explore-cats-heading" style="font-size:1.4rem;font-weight:800;color:#0F172A;margin:0 0 .9rem;text-align:center;">&#x1F4CA; Explore FinanceSpots</h2>
        <div style="display:flex;flex-wrap:wrap;gap:.65rem;justify-content:center;margin-bottom:1.25rem;">
            <a href="<?php echo esc_url(home_url('/categories/')); ?>" style="display:inline-flex;align-items:center;gap:.4rem;background:#fff;border:1.5px solid #E2E8F0;border-radius:10px;padding:.6rem 1.1rem;font-size:.88rem;font-weight:700;color:#0F172A;text-decoration:none;transition:border-color .2s;" onmouseover="this.style.borderColor='#10B981'" onmouseout="this.style.borderColor='#E2E8F0'">&#128450;&#65039; Browse Categories</a>
            <a href="<?php echo esc_url(home_url('/all-tools/')); ?>" style="display:inline-flex;align-items:center;gap:.4rem;background:#fff;border:1.5px solid #E2E8F0;border-radius:10px;padding:.6rem 1.1rem;font-size:.88rem;font-weight:700;color:#0F172A;text-decoration:none;transition:border-color .2s;" onmouseover="this.style.borderColor='#10B981'" onmouseout="this.style.borderColor='#E2E8F0'">&#129518; All Tools</a>
            <a href="<?php echo esc_url(home_url('/blog/')); ?>" style="display:inline-flex;align-items:center;gap:.4rem;background:#fff;border:1.5px solid #E2E8F0;border-radius:10px;padding:.6rem 1.1rem;font-size:.88rem;font-weight:700;color:#0F172A;text-decoration:none;transition:border-color .2s;" onmouseover="this.style.borderColor='#10B981'" onmouseout="this.style.borderColor='#E2E8F0'">&#128221; Finance Blog</a>
            <a href="<?php echo esc_url(home_url('/about/')); ?>" style="display:inline-flex;align-items:center;gap:.4rem;background:#fff;border:1.5px solid #E2E8F0;border-radius:10px;padding:.6rem 1.1rem;font-size:.88rem;font-weight:700;color:#0F172A;text-decoration:none;transition:border-color .2s;" onmouseover="this.style.borderColor='#10B981'" onmouseout="this.style.borderColor='#E2E8F0'">&#128075; About Us</a>
            <a href="<?php echo esc_url(home_url('/pricing/')); ?>" style="display:inline-flex;align-items:center;gap:.4rem;background:#fff;border:1.5px solid #E2E8F0;border-radius:10px;padding:.6rem 1.1rem;font-size:.88rem;font-weight:700;color:#0F172A;text-decoration:none;transition:border-color .2s;" onmouseover="this.style.borderColor='#10B981'" onmouseout="this.style.borderColor='#E2E8F0'">&#11088; PRO Pricing</a>
        </div>

        <h3 style="font-size:1.05rem;font-weight:700;color:#334155;margin:0 0 .6rem;text-align:center;">&#128200; Popular Tools to Try Now</h3>
        <div style="display:flex;flex-wrap:wrap;gap:.65rem;justify-content:center;">
            <a href="<?php echo esc_url(home_url('/tool/mortgage-calculator/')); ?>" style="display:inline-flex;align-items:center;gap:.35rem;background:#EFF6FF;border:1px solid #BFDBFE;border-radius:8px;padding:.5rem .9rem;font-size:.83rem;font-weight:600;color:#1D4ED8;text-decoration:none;">&#127968; Mortgage Calculator</a>
            <a href="<?php echo esc_url(home_url('/tool/compound-interest-calculator/')); ?>" style="display:inline-flex;align-items:center;gap:.35rem;background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:.5rem .9rem;font-size:.83rem;font-weight:600;color:#15803D;text-decoration:none;">&#128200; Compound Interest</a>
            <a href="<?php echo esc_url(home_url('/tool/retirement-income-calculator/')); ?>" style="display:inline-flex;align-items:center;gap:.35rem;background:#F5F3FF;border:1px solid #DDD6FE;border-radius:8px;padding:.5rem .9rem;font-size:.83rem;font-weight:600;color:#7C3AED;text-decoration:none;">&#127958;&#65039; Retirement Planner</a>
            <a href="<?php echo esc_url(home_url('/tool/income-tax-calculator/')); ?>" style="display:inline-flex;align-items:center;gap:.35rem;background:#FFF7ED;border:1px solid #FED7AA;border-radius:8px;padding:.5rem .9rem;font-size:.83rem;font-weight:600;color:#C2410C;text-decoration:none;">&#129534; Tax Calculator</a>
            <a href="<?php echo esc_url(home_url('/tool/monthly-budget-planner/')); ?>" style="display:inline-flex;align-items:center;gap:.35rem;background:#F0F9FF;border:1px solid #BAE6FD;border-radius:8px;padding:.5rem .9rem;font-size:.83rem;font-weight:600;color:#0369A1;text-decoration:none;">&#128203; Budget Planner</a>
            <a href="<?php echo esc_url(home_url('/tool/loan-payoff-calculator/')); ?>" style="display:inline-flex;align-items:center;gap:.35rem;background:#FFF1F2;border:1px solid #FECDD3;border-radius:8px;padding:.5rem .9rem;font-size:.83rem;font-weight:600;color:#BE123C;text-decoration:none;">&#128179; Debt Payoff Tool</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
