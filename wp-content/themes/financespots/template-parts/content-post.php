<?php
/**
 * Template part for displaying posts in loops
 *
 * @package financespots
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'fs-blog-card' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
    <a href="<?php the_permalink(); ?>" class="fs-blog-card__thumb-link" tabindex="-1" aria-hidden="true">
        <?php the_post_thumbnail( 'financespots-tool-thumb', [ 'class' => 'fs-blog-card__thumb', 'alt' => get_the_title() ] ); ?>
    </a>
    <?php endif; ?>
    <div class="fs-blog-card__body">
        <div class="fs-blog-card__meta">
            <time datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time>
        </div>
        <h2 class="fs-blog-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <p class="fs-blog-card__excerpt"><?php the_excerpt(); ?></p>
        <a href="<?php the_permalink(); ?>" class="fs-blog-card__link">
            <?php esc_html_e( 'Read More', 'financespots' ); ?>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</article>
