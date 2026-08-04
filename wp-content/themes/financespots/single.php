<?php
/**
 * Single Post Template
 *
 * @package financespots
 */

get_header();
?>

<div class="fs-page-wrap container">
    <main class="fs-content-area">
        <?php
        while ( have_posts() ) :
            the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'fs-single-post' ); ?>>

            <!-- Breadcrumbs -->
            <nav class="fs-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'financespots' ); ?>">
                <ol class="fs-breadcrumbs__list">
                    <li class="fs-breadcrumbs__item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                    <li class="fs-breadcrumbs__sep" aria-hidden="true">›</li>
                    <li class="fs-breadcrumbs__item"><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a></li>
                    <?php
                    $cats = get_the_category();
                    if ( $cats ) :
                    ?>
                    <li class="fs-breadcrumbs__sep" aria-hidden="true">›</li>
                    <li class="fs-breadcrumbs__item">
                        <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>">
                            <?php echo esc_html( $cats[0]->name ); ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="fs-breadcrumbs__sep" aria-hidden="true">›</li>
                    <li class="fs-breadcrumbs__item fs-breadcrumbs__item--current" aria-current="page">
                        <?php echo esc_html( wp_trim_words( get_the_title(), 6 ) ); ?>
                    </li>
                </ol>
            </nav>

            <!-- Post Header -->
            <header class="fs-single-post__header">
                <?php
                $cats = get_the_category();
                if ( $cats ) :
                ?>
                <div class="fs-single-post__cats">
                    <?php foreach ( $cats as $cat ) : ?>
                    <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="fs-badge fs-badge--secondary">
                        <?php echo esc_html( $cat->name ); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <h1 class="fs-single-post__title"><?php echo esc_html( get_the_title() ); ?></h1>

                <div class="fs-single-post__meta">
                    <time datetime="<?php echo get_the_date( 'c' ); ?>">
                        <?php echo get_the_date(); ?>
                    </time>
                    <span class="fs-single-post__meta-sep" aria-hidden="true">·</span>
                    <span><?php echo esc_html( get_the_author() ); ?></span>
                    <span class="fs-single-post__meta-sep" aria-hidden="true">·</span>
                    <span><?php printf( esc_html__( '%d min read', 'financespots' ), max( 1, ceil( str_word_count( strip_tags( get_the_content() ) ) / 200 ) ) ); ?></span>
                </div>

                <?php $fs_hero_img = fs_get_post_hero_image( get_the_ID() ); ?>
                <?php if ( $fs_hero_img ) : ?>
                <div class="fs-single-post__thumb">
                    <img src="<?php echo esc_url( $fs_hero_img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="fs-single-post__thumb-img" loading="eager" width="900" height="500" />
                </div>
                <?php endif; ?>
            </header>

            <!-- Post Content -->
            <div class="fs-single-post__content fs-prose">
                <?php the_content(); ?>
            </div>

            <!-- Social Share -->
            <div class="fs-share-box">
                <span class="fs-share-box__label">Share this article:</span>
                <div class="fs-share-box__btns">
                    <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>"
                       target="_blank" rel="noopener noreferrer" class="fs-share-btn fs-share-btn--x" aria-label="Share on X (Twitter)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622L18.244 2.25z"/></svg>
                        X / Twitter
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo rawurlencode( get_permalink() ); ?>&title=<?php echo rawurlencode( get_the_title() ); ?>"
                       target="_blank" rel="noopener noreferrer" class="fs-share-btn fs-share-btn--li" aria-label="Share on LinkedIn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452z"/></svg>
                        LinkedIn
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( get_permalink() ); ?>"
                       target="_blank" rel="noopener noreferrer" class="fs-share-btn fs-share-btn--fb" aria-label="Share on Facebook">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        Facebook
                    </a>
                    <button class="fs-share-btn fs-share-btn--copy" onclick="navigator.clipboard.writeText('<?php echo esc_js( get_permalink() ); ?>').then(function(){this.textContent='Copied!';}.bind(this))" aria-label="Copy link">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy Link
                    </button>
                </div>
            </div>

            <!-- Tags -->
            <?php the_tags( '<div class="fs-single-post__tags">', '', '</div>' ); ?>

            <!-- Author Bio -->
            <div class="fs-author-box">
                <div class="fs-author-box__avatar">
                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 80, '', get_the_author(), [ 'class' => 'fs-author-box__avatar-img' ] ); ?>
                </div>
                <div class="fs-author-box__info">
                    <span class="fs-author-box__by"><?php esc_html_e( 'Written by', 'financespots' ); ?></span>
                    <h3 class="fs-author-box__name"><?php echo esc_html( get_the_author() ); ?></h3>
                    <p class="fs-author-box__bio"><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>
                </div>
            </div>

            <!-- Related Posts (topic cluster) -->
            <?php
            $fs_related_cats = get_the_category();
            $fs_related_args = [
                'post_type'           => 'post',
                'posts_per_page'      => 3,
                'post__not_in'        => [ get_the_ID() ],
                'ignore_sticky_posts' => true,
                'orderby'             => 'date',
                'order'               => 'DESC',
            ];
            if ( $fs_related_cats ) {
                $fs_related_args['category__in'] = wp_list_pluck( $fs_related_cats, 'term_id' );
            }
            $fs_related_query = new WP_Query( $fs_related_args );
            // Fall back to latest posts (any category) if this post's category has no siblings yet.
            if ( ! $fs_related_query->have_posts() && $fs_related_cats ) {
                unset( $fs_related_args['category__in'] );
                $fs_related_query = new WP_Query( $fs_related_args );
            }
            if ( $fs_related_query->have_posts() ) :
            ?>
            <section class="fs-related-posts" aria-labelledby="fs-related-heading">
                <h2 class="fs-related-posts__title" id="fs-related-heading">You Might Also Like</h2>
                <div class="fs-blog__grid fs-related-posts__grid">
                    <?php while ( $fs_related_query->have_posts() ) : $fs_related_query->the_post(); ?>
                    <article class="fs-blog-card">
                        <?php $fs_r_img = fs_get_post_hero_image( get_the_ID() ); ?>
                        <?php if ( $fs_r_img ) : ?>
                        <a href="<?php the_permalink(); ?>" class="fs-blog-card__thumb-link" tabindex="-1" aria-hidden="true">
                            <img src="<?php echo esc_url( $fs_r_img ); ?>" alt="<?php the_title_attribute(); ?>" class="fs-blog-card__thumb" loading="lazy" />
                        </a>
                        <?php endif; ?>
                        <div class="fs-blog-card__body">
                            <div class="fs-blog-card__meta">
                                <time datetime="<?php echo get_the_date( 'c' ); ?>" class="fs-blog-card__date"><?php echo get_the_date(); ?></time>
                                <?php
                                $fs_r_cats = get_the_category();
                                if ( $fs_r_cats ) echo '<span class="fs-blog-card__cat">' . esc_html( $fs_r_cats[0]->name ) . '</span>';
                                ?>
                            </div>
                            <h3 class="fs-blog-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="fs-blog-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
                            <a href="<?php the_permalink(); ?>" class="fs-blog-card__link">
                                Read Article
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Post Navigation -->
            <nav class="fs-post-nav" aria-label="<?php esc_attr_e( 'Post navigation', 'financespots' ); ?>">
                <?php previous_post_link( '<div class="fs-post-nav__prev">%link</div>', '&larr; %title' ); ?>
                <?php next_post_link( '<div class="fs-post-nav__next">%link</div>', '%title &rarr;' ); ?>
            </nav>

            <?php // Comments disabled site-wide ?>

        </article>
        <?php endwhile; ?>
    </main>

    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
    <aside class="fs-sidebar" role="complementary" aria-label="<?php esc_attr_e( 'Article Sidebar', 'financespots' ); ?>">
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    </aside>
    <?php endif; ?>

</div>

<?php get_footer(); ?>
