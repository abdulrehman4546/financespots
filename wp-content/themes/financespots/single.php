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

                <h1 class="fs-single-post__title"><?php the_title(); ?></h1>

                <div class="fs-single-post__meta">
                    <time datetime="<?php echo get_the_date( 'c' ); ?>">
                        <?php echo get_the_date(); ?>
                    </time>
                    <span class="fs-single-post__meta-sep" aria-hidden="true">·</span>
                    <span><?php echo esc_html( get_the_author() ); ?></span>
                    <span class="fs-single-post__meta-sep" aria-hidden="true">·</span>
                    <span><?php printf( esc_html__( '%d min read', 'financespots' ), max( 1, ceil( str_word_count( strip_tags( get_the_content() ) ) / 200 ) ) ); ?></span>
                </div>

                <?php if ( has_post_thumbnail() ) : ?>
                <div class="fs-single-post__thumb">
                    <?php the_post_thumbnail( 'full', [ 'class' => 'fs-single-post__thumb-img', 'alt' => get_the_title() ] ); ?>
                </div>
                <?php endif; ?>
            </header>

            <!-- Post Content -->
            <div class="fs-single-post__content fs-prose">
                <?php the_content(); ?>
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
