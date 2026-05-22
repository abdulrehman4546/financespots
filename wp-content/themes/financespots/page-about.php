<?php
/**
 * Template Name: About Us
 */
get_header();
?>

<div class="fs-about-page">

    <!-- Hero -->
    <section class="fs-about-hero">
        <div class="container">
            <div class="fs-about-hero__inner">
                <span class="fs-badge fs-badge--green">&#128075; Our Story</span>
                <h1 class="fs-about-hero__title">We Make Financial<br><span class="fs-about-hero__accent">Clarity Simple</span></h1>
                <p class="fs-about-hero__desc">FinanceSpots is a free platform built to give everyone -- regardless of income or background -- access to professional-grade financial tools and education.</p>
                <div class="fs-about-hero__stats">
                    <div class="fs-about-stat"><span class="fs-about-stat__num">30+</span><span class="fs-about-stat__lbl">Free Tools</span></div>
                    <div class="fs-about-stat"><span class="fs-about-stat__num">10+</span><span class="fs-about-stat__lbl">Expert Guides</span></div>
                    <div class="fs-about-stat"><span class="fs-about-stat__num">100%</span><span class="fs-about-stat__lbl">Free Forever</span></div>
                    <div class="fs-about-stat"><span class="fs-about-stat__num">0</span><span class="fs-about-stat__lbl">Ads or Upsells</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission -->
    <section class="fs-about-section">
        <div class="container">
            <div class="fs-about-two-col">
                <div class="fs-about-two-col__text">
                    <span class="fs-badge fs-badge--green">&#127919; Our Mission</span>
                    <h2>Financial Tools That Work For Everyone</h2>
                    <p>Most financial tools are either too expensive, too complicated, or hidden behind paywalls and aggressive upsells. We built FinanceSpots to change that.</p>
                    <p>Every calculator on our platform is completely free, runs instantly in your browser, and requires absolutely no account creation. We believe financial clarity is a right, not a premium feature.</p>
                    <p>Whether you are calculating your first mortgage, trying to pay off credit card debt, or planning for retirement -- our tools give you the same analysis a financial advisor would charge hundreds of dollars for.</p>
                </div>
                <div class="fs-about-two-col__visual">
                    <div class="fs-about-mission-card">
                        <div class="fs-about-mission-item"><span class="fs-about-mission-icon">&#9989;</span><span>No account required</span></div>
                        <div class="fs-about-mission-item"><span class="fs-about-mission-icon">&#9989;</span><span>No hidden fees or subscriptions</span></div>
                        <div class="fs-about-mission-item"><span class="fs-about-mission-icon">&#9989;</span><span>No data sold to third parties</span></div>
                        <div class="fs-about-mission-item"><span class="fs-about-mission-icon">&#9989;</span><span>All calculations run in your browser</span></div>
                        <div class="fs-about-mission-item"><span class="fs-about-mission-icon">&#9989;</span><span>Expert-written guides with every tool</span></div>
                        <div class="fs-about-mission-item"><span class="fs-about-mission-icon">&#9989;</span><span>PDF export on every calculator</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Founder -->
    <section class="fs-about-section fs-about-section--dark">
        <div class="container">
            <div class="fs-about-founder">
                <div class="fs-about-founder__avatar">
                    <div class="fs-about-founder__initials">AR</div>
                </div>
                <div class="fs-about-founder__content">
                    <span class="fs-badge fs-badge--green">&#128075; Founder</span>
                    <h2 class="fs-about-founder__name">Abdul Rahman</h2>
                    <p class="fs-about-founder__role">Founder & Developer, FinanceSpots</p>
                    <blockquote class="fs-about-founder__quote">
                        "I built FinanceSpots because I was frustrated with financial tools that were either locked behind subscriptions or buried in confusing interfaces. Everyone deserves clear, instant answers to their money questions -- for free. That is the only promise this platform makes."
                    </blockquote>
                    <p>Abdul Rahman is the sole developer and creator behind FinanceSpots. With a passion for personal finance and web development, he designed every tool and wrote every guide on this platform with one goal: making your financial decisions easier and smarter.</p>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="fs-about-contact-btn">Get In Touch &rarr;</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="fs-about-section">
        <div class="container">
            <div class="fs-about-section-header">
                <span class="fs-badge fs-badge--green">&#10024; What We Stand For</span>
                <h2>Our Core Values</h2>
            </div>
            <div class="fs-about-values">
                <div class="fs-about-value-card">
                    <div class="fs-about-value-icon">&#128275;</div>
                    <h3>Radical Transparency</h3>
                    <p>Every formula we use is explained. We show our work so you understand the numbers, not just the output.</p>
                </div>
                <div class="fs-about-value-card">
                    <div class="fs-about-value-icon">&#9889;</div>
                    <h3>Speed & Simplicity</h3>
                    <p>No loading screens, no signups, no complexity. Enter your numbers and get your answer in under 3 seconds.</p>
                </div>
                <div class="fs-about-value-card">
                    <div class="fs-about-value-icon">&#128274;</div>
                    <h3>Your Privacy First</h3>
                    <p>All calculations happen in your browser. We never see your financial data. Ever. No tracking, no profiling.</p>
                </div>
                <div class="fs-about-value-card">
                    <div class="fs-about-value-icon">&#127891;</div>
                    <h3>Education Built In</h3>
                    <p>Every tool comes with an expert guide explaining the concept, the math, and the best strategies to apply it.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tools CTA -->
    <section class="fs-about-cta">
        <div class="container">
            <div class="fs-about-cta__inner">
                <h2>&#128200; Ready to Take Control of Your Finances?</h2>
                <p>Join thousands of people using FinanceSpots to make smarter money decisions -- completely free.</p>
                <div style="display:flex;gap:14px;flex-wrap:wrap;justify-content:center;">
                    <a href="<?php echo esc_url(home_url('/tools/')); ?>" class="fs-btn fs-btn--primary fs-btn--lg">Explore All Free Tools &rarr;</a>
                    <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="fs-btn fs-btn--outline fs-btn--lg">Read Finance Guides</a>
                </div>
            </div>
        </div>
    </section>

</div>

<style>
.fs-about-page{background:#0A0F1E;min-height:100vh;}

/* Hero */
.fs-about-hero{padding:90px 0 70px;background:linear-gradient(135deg,#0A0F1E 0%,#131929 60%,#0A0F1E 100%);border-bottom:1px solid rgba(255,255,255,.07);}
.fs-about-hero__inner{text-align:center;max-width:740px;margin:0 auto;}
.fs-about-hero__title{font-size:clamp(2.2rem,4.5vw,3.2rem);font-weight:900;color:#fff;margin:18px 0 14px;line-height:1.15;}
.fs-about-hero__accent{background:linear-gradient(135deg,#10B981,#06B6D4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.fs-about-hero__desc{font-size:1.1rem;color:#94A3B8;line-height:1.7;margin-bottom:40px;}
.fs-about-hero__stats{display:flex;gap:32px;justify-content:center;flex-wrap:wrap;}
.fs-about-stat{text-align:center;}
.fs-about-stat__num{display:block;font-size:2rem;font-weight:900;color:#10B981;line-height:1;}
.fs-about-stat__lbl{display:block;font-size:.78rem;color:#64748B;font-weight:600;margin-top:4px;text-transform:uppercase;letter-spacing:.05em;}

/* Sections */
.fs-about-section{padding:70px 0;}
.fs-about-section--dark{background:#0D1424;}
.fs-about-section-header{text-align:center;margin-bottom:48px;}
.fs-about-section-header h2{font-size:2rem;font-weight:800;color:#fff;margin:12px 0 0;}

/* Two col */
.fs-about-two-col{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;}
@media(max-width:800px){.fs-about-two-col{grid-template-columns:1fr;gap:32px;}}
.fs-about-two-col__text h2{font-size:1.8rem;font-weight:800;color:#fff;margin:12px 0 18px;}
.fs-about-two-col__text p{color:#94A3B8;line-height:1.75;margin-bottom:14px;font-size:.97rem;}

/* Mission card */
.fs-about-mission-card{background:#131929;border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:28px;display:flex;flex-direction:column;gap:14px;}
.fs-about-mission-item{display:flex;align-items:center;gap:12px;color:#CBD5E1;font-size:.92rem;}
.fs-about-mission-icon{font-size:1rem;flex-shrink:0;}

/* Founder */
.fs-about-founder{display:grid;grid-template-columns:auto 1fr;gap:48px;align-items:start;}
@media(max-width:700px){.fs-about-founder{grid-template-columns:1fr;}.fs-about-founder__avatar{margin:0 auto;}}
.fs-about-founder__avatar{width:120px;height:120px;border-radius:50%;background:linear-gradient(135deg,#10B981,#06B6D4);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.fs-about-founder__initials{font-size:2.5rem;font-weight:900;color:#fff;}
.fs-about-founder__name{font-size:1.8rem;font-weight:800;color:#fff;margin:10px 0 4px;}
.fs-about-founder__role{font-size:.88rem;color:#10B981;font-weight:600;margin-bottom:20px;display:block;}
.fs-about-founder__quote{border-left:3px solid #10B981;padding-left:18px;margin:20px 0;font-size:1rem;color:#CBD5E1;line-height:1.75;font-style:italic;}
.fs-about-founder__content p{color:#94A3B8;line-height:1.75;font-size:.95rem;margin-bottom:20px;}
.fs-about-contact-btn{display:inline-flex;align-items:center;gap:6px;background:#10B981;color:#fff;text-decoration:none;padding:11px 24px;border-radius:10px;font-weight:700;font-size:.9rem;transition:background .2s;}
.fs-about-contact-btn:hover{background:#0d9e6f;}

/* Values */
.fs-about-values{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:20px;}
.fs-about-value-card{background:#131929;border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:26px;transition:transform .25s,border-color .25s;}
.fs-about-value-card:hover{transform:translateY(-4px);border-color:rgba(16,185,129,.3);}
.fs-about-value-icon{font-size:2rem;margin-bottom:14px;display:block;}
.fs-about-value-card h3{font-size:1rem;font-weight:700;color:#F1F5F9;margin:0 0 10px;}
.fs-about-value-card p{font-size:.875rem;color:#94A3B8;line-height:1.65;margin:0;}

/* CTA */
.fs-about-cta{padding:70px 0;background:linear-gradient(135deg,#0F2027,#1a3a4a);border-top:1px solid rgba(255,255,255,.07);}
.fs-about-cta__inner{text-align:center;max-width:620px;margin:0 auto;}
.fs-about-cta__inner h2{font-size:1.9rem;font-weight:800;color:#fff;margin-bottom:12px;}
.fs-about-cta__inner p{color:#94A3B8;font-size:1rem;line-height:1.7;margin-bottom:28px;}
</style>

<?php get_footer(); ?>
