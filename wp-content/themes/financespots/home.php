<?php
/**
 * Blog Archive -- shows when /blog/ is visited
 */
get_header();
?>

<div class="fs-blog-archive">

    <!-- Hero Banner -->
    <section class="fs-blog-hero">
        <div class="container">
            <div class="fs-blog-hero__inner">
                <span class="fs-badge fs-badge--green">&#128240; Finance Blog</span>
                <h1 class="fs-blog-hero__title">Expert Finance Guides & Tips</h1>
                <p class="fs-blog-hero__desc">In-depth guides on investing, budgeting, debt payoff, retirement planning, taxes, and more -- all backed by our free financial tools.</p>

                <!-- Category Filter -->
                <div class="fs-blog-cats">
                    <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="fs-blog-cat-btn active">All Posts</a>
                    <?php
                    $cats = get_categories( [ 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 8 ] );
                    foreach ( $cats as $cat ) :
                    ?>
                    <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="fs-blog-cat-btn">
                        <?php echo esc_html( $cat->name ); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Grid -->
    <section class="fs-blog-grid-section">
        <div class="container">

            <?php if ( have_posts() ) : ?>

            <div class="fs-blog-grid">
                <?php
                $icons = ['Finance Guides'=>'&#127968;','Investing'=>'&#128200;','Debt Management'=>'&#128179;','Retirement'=>'&#127958;&#65039;','Budgeting'=>'&#128203;','Loans'=>'&#128196;','Cryptocurrency'=>'₿','Taxes'=>'&#129534;','Savings'=>'&#127974;'];

                while ( have_posts() ) : the_post();
                    $cats      = get_the_category();
                    $cat       = ! empty( $cats ) ? $cats[0] : null;
                    $cat_name  = $cat ? $cat->name : 'Finance';
                    $icon      = $icons[ $cat_name ] ?? '&#128218;';
                    $excerpt   = get_the_excerpt() ?: wp_trim_words( get_the_content(), 28 );
                    $read_time = max( 3, (int) ( str_word_count( strip_tags( get_the_content() ) ) / 200 ) );
                    $has_thumb = has_post_thumbnail();
                ?>
                <article class="fs-blog-card<?php echo $has_thumb ? ' fs-blog-card--has-img' : ''; ?>">

                    <?php if ( $has_thumb ) : ?>
                    <a href="<?php the_permalink(); ?>" class="fs-blog-card__img-wrap" tabindex="-1" aria-hidden="true">
                        <?php the_post_thumbnail( 'large', [
                            'class'   => 'fs-blog-card__img',
                            'loading' => 'lazy',
                            'alt'     => get_the_title(),
                        ] ); ?>
                        <span class="fs-blog-card__cat-badge"><?php echo esc_html( $cat_name ); ?></span>
                    </a>
                    <?php else : ?>
                    <div class="fs-blog-card__icon-wrap">
                        <span class="fs-blog-card__icon"><?php echo $icon; ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="fs-blog-card__body">
                        <div class="fs-blog-card__meta">
                            <?php if ( $cat && ! $has_thumb ) : ?>
                            <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="fs-blog-card__cat">
                                <?php echo esc_html( $cat_name ); ?>
                            </a>
                            <?php endif; ?>
                            <span class="fs-blog-card__date">&#128197; <?php echo get_the_date( 'M j, Y' ); ?></span>
                            <span class="fs-blog-card__read">&#9201; <?php echo $read_time; ?> min read</span>
                        </div>
                        <h2 class="fs-blog-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p class="fs-blog-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
                        <a href="<?php the_permalink(); ?>" class="fs-blog-card__link">
                            Read Full Guide <span aria-hidden="true">&#x2192;</span>
                        </a>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="fs-blog-pagination">
                <?php
                the_posts_pagination([
                    'prev_text' => '&larr; Newer',
                    'next_text' => 'Older &rarr;',
                    'class'     => 'fs-pagination',
                ]);
                ?>
            </div>

            <?php else : ?>
            <div class="fs-blog-empty">
                <p>&#128218; No blog posts found yet. Check back soon!</p>
            </div>
            <?php endif; ?>

        </div>
    </section>

    <!-- Internal Links: Tool Categories Quick Nav -->
    <section style="padding:0 0 40px;background:#0A0F1E;">
        <div class="container">
            <div style="background:#131929;border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:22px 28px;">
                <p style="font-size:.78rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.08em;margin:0 0 14px;">&#128279; Explore Our Finance Tools</p>
                <div style="display:flex;flex-wrap:wrap;gap:10px;">
                    <a href="<?php echo esc_url(home_url('/all-tools/')); ?>" style="display:inline-flex;align-items:center;gap:6px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);border-radius:8px;padding:8px 14px;font-size:.84rem;font-weight:600;color:#10B981;text-decoration:none;">&#129518; All 150+ Tools</a>
                    <a href="<?php echo esc_url(home_url('/categories/')); ?>" style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:8px 14px;font-size:.84rem;font-weight:600;color:#94A3B8;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94A3B8'">&#128450;&#65039; Categories</a>
                    <a href="<?php echo esc_url(home_url('/tools/mortgage-calculator/')); ?>" style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:8px 14px;font-size:.84rem;font-weight:600;color:#94A3B8;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94A3B8'">&#127968; Mortgage</a>
                    <a href="<?php echo esc_url(home_url('/tools/compound-interest-calculator/')); ?>" style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:8px 14px;font-size:.84rem;font-weight:600;color:#94A3B8;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94A3B8'">&#128200; Investing</a>
                    <a href="<?php echo esc_url(home_url('/tools/retirement-income-calculator/')); ?>" style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:8px 14px;font-size:.84rem;font-weight:600;color:#94A3B8;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94A3B8'">&#127958;&#65039; Retirement</a>
                    <a href="<?php echo esc_url(home_url('/pricing/')); ?>" style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:8px 14px;font-size:.84rem;font-weight:600;color:#94A3B8;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94A3B8'">&#11088; PRO Plans</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Tools CTA -->
    <section class="fs-blog-cta">
        <div class="container">
            <div class="fs-blog-cta__inner">
                <h2>&#128200; Ready to Take Action?</h2>
                <p>Use our free financial tools to apply what you have learned. All calculators are free, instant, and require no signup.</p>
                <a href="<?php echo esc_url( home_url( '/all-tools/' ) ); ?>" class="fs-btn fs-btn--primary fs-btn--lg">
                    Explore All Free Tools &rarr;
                </a>
            </div>
        </div>
    </section>

</div>

<style>
/* ── Blog Archive Styles ── */
.fs-blog-archive { background: #0A0F1E; min-height: 100vh; }

/* Hero */
.fs-blog-hero { padding: 80px 0 60px; background: linear-gradient(135deg, #0A0F1E 0%, #131929 100%); border-bottom: 1px solid rgba(255,255,255,0.07); }
.fs-blog-hero__inner { text-align: center; max-width: 720px; margin: 0 auto; }
.fs-blog-hero__title { font-size: clamp(2rem,4vw,3rem); font-weight: 800; color: #fff; margin: 16px 0 12px; line-height: 1.2; }
.fs-blog-hero__desc { font-size: 1.1rem; color: #94A3B8; margin-bottom: 32px; line-height: 1.7; }

/* Category pills */
.fs-blog-cats { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; }
.fs-blog-cat-btn { padding: 7px 18px; border-radius: 999px; font-size: 0.82rem; font-weight: 600; border: 1px solid rgba(255,255,255,0.15); color: #94A3B8; text-decoration: none; transition: all 0.2s; }
.fs-blog-cat-btn:hover, .fs-blog-cat-btn.active { background: #10B981; border-color: #10B981; color: #fff; }

/* Grid */
.fs-blog-grid-section { padding: 60px 0 80px; }
.fs-blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 28px; }

/* Card */
.fs-blog-card { background: #131929; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; overflow: hidden; transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s; display: flex; flex-direction: column; }
.fs-blog-card:hover { transform: translateY(-5px); box-shadow: 0 24px 64px rgba(0,0,0,0.5); border-color: rgba(16,185,129,0.35); }

/* Featured image */
.fs-blog-card__img-wrap { display: block; position: relative; overflow: hidden; aspect-ratio: 16/9; }
.fs-blog-card__img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; display: block; }
.fs-blog-card:hover .fs-blog-card__img { transform: scale(1.05); }
.fs-blog-card__cat-badge { position: absolute; top: 14px; left: 14px; background: rgba(10,15,30,0.85); color: #10B981; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; padding: 4px 12px; border-radius: 999px; border: 1px solid rgba(16,185,129,0.4); backdrop-filter: blur(8px); }

/* No-image fallback icon */
.fs-blog-card__icon-wrap { padding: 28px 28px 0; }
.fs-blog-card__icon { font-size: 2.8rem; }

.fs-blog-card__body { padding: 20px 24px 26px; flex: 1; display: flex; flex-direction: column; }
.fs-blog-card__meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 10px; }
.fs-blog-card__cat { font-size: 0.73rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #10B981; background: rgba(16,185,129,0.1); padding: 3px 10px; border-radius: 999px; text-decoration: none; border: 1px solid rgba(16,185,129,0.2); }
.fs-blog-card__date { font-size: 0.76rem; color: #64748B; }
.fs-blog-card__read { font-size: 0.76rem; color: #64748B; }
.fs-blog-card__title { font-size: 1.1rem; font-weight: 700; color: #F1F5F9; margin: 0 0 10px; line-height: 1.45; }
.fs-blog-card__title a { color: inherit; text-decoration: none; }
.fs-blog-card__title a:hover { color: #10B981; }
.fs-blog-card__excerpt { font-size: 0.875rem; color: #94A3B8; line-height: 1.7; margin-bottom: 18px; flex: 1; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
.fs-blog-card__link { font-size: 0.875rem; font-weight: 600; color: #10B981; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-top: auto; transition: gap 0.2s; }
.fs-blog-card__link:hover { gap: 10px; }

/* Pagination */
.fs-blog-pagination { margin-top: 48px; display: flex; justify-content: center; }
.fs-blog-pagination .nav-links { display: flex; gap: 8px; align-items: center; }
.fs-blog-pagination .page-numbers { padding: 8px 16px; border-radius: 8px; background: #131929; border: 1px solid rgba(255,255,255,0.1); color: #94A3B8; text-decoration: none; font-size: 0.9rem; transition: all 0.2s; }
.fs-blog-pagination .page-numbers.current, .fs-blog-pagination .page-numbers:hover { background: #10B981; border-color: #10B981; color: #fff; }

/* Empty */
.fs-blog-empty { text-align: center; padding: 80px 20px; color: #64748B; font-size: 1.1rem; }

/* CTA */
.fs-blog-cta { padding: 60px 0; background: linear-gradient(135deg, #0F2027, #1a3a4a); border-top: 1px solid rgba(255,255,255,0.07); }
.fs-blog-cta__inner { text-align: center; max-width: 600px; margin: 0 auto; }
.fs-blog-cta__inner h2 { font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: 12px; }
.fs-blog-cta__inner p { color: #94A3B8; margin-bottom: 28px; font-size: 1rem; line-height: 1.7; }

/* Responsive */
@media (max-width: 640px) {
    .fs-blog-grid { grid-template-columns: 1fr; }
    .fs-blog-hero { padding: 50px 0 40px; }
}
</style>

<?php get_footer(); ?>
