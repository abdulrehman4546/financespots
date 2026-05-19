<?php
/**
 * Index Template — fallback for all views
 *
 * @package financespots
 */

get_header();
?>

<div class="fs-page-wrap container">
    <div class="fs-content-area">

        <?php if ( have_posts() ) : ?>

            <?php if ( is_home() && ! is_front_page() ) : ?>
            <header class="fs-archive-header">
                <h1 class="fs-archive-title"><?php esc_html_e( 'Latest Posts', 'financespots' ); ?></h1>
            </header>
            <?php endif; ?>

            <div class="fs-posts-grid">
            <?php
            while ( have_posts() ) :
                the_post();
                get_template_part( 'template-parts/content', get_post_type() );
            endwhile;
            ?>
            </div>

            <?php the_posts_pagination([ 'prev_text' => '&larr; ' . __( 'Newer', 'financespots' ), 'next_text' => __( 'Older', 'financespots' ) . ' &rarr;' ]); ?>

        <?php else : ?>
            <div class="fs-no-results">
                <h2><?php esc_html_e( 'Nothing found', 'financespots' ); ?></h2>
                <p><?php esc_html_e( 'It looks like nothing was found here. Try a search?', 'financespots' ); ?></p>
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>

    </div><!-- .fs-content-area -->

    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
    <aside class="fs-sidebar" role="complementary" aria-label="<?php esc_attr_e( 'Blog Sidebar', 'financespots' ); ?>">
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    </aside>
    <?php endif; ?>

</div>

<?php get_footer(); ?>
