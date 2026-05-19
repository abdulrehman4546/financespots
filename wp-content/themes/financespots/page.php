<?php
/**
 * Page Template
 *
 * @package financespots
 */

get_header();
?>

<div class="fs-page-wrap fs-page-wrap--single container">
    <main class="fs-content-area fs-content-area--full">
        <?php
        while ( have_posts() ) :
            the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'fs-page-article' ); ?>>
            <header class="fs-page-article__header">
                <h1 class="fs-page-article__title"><?php the_title(); ?></h1>
            </header>
            <div class="fs-page-article__content fs-prose">
                <?php the_content(); ?>
            </div>
            <?php
            wp_link_pages([
                'before' => '<nav class="fs-page-links" aria-label="' . esc_attr__( 'Page sections', 'financespots' ) . '">',
                'after'  => '</nav>',
            ]);
            ?>
        </article>

        <?php if ( comments_open() || get_comments_number() ) comments_template(); ?>

        <?php endwhile; ?>
    </main>
</div>

<?php get_footer(); ?>
