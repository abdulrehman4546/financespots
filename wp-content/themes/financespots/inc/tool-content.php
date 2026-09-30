<?php
/**
 * Renders the editorial content for tools that have an entry in
 * tool-content-data.php, feeds the FAQPage schema from the same data, and
 * syncs the matching Rank Math meta (title, description, focus keywords).
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

require_once get_template_directory() . '/inc/tool-content-data.php';

/** Content record for a tool post (by slug), or null. */
function fs_tool_content_for( $post_id = null ) {
    $post = get_post( $post_id ?: get_the_ID() );
    if ( ! $post || 'fs_tool' !== $post->post_type ) return null;
    $all = fs_tool_content_data();
    return $all[ $post->post_name ] ?? null;
}

/** Short direct answer, shown right under the calculator (AEO). */
function fs_render_tool_quick_answer( $c ) {
    if ( empty( $c['answer'] ) ) return;
    echo '<div class="fst-answer" id="quick-answer"><strong class="fst-answer__label">Quick answer</strong><p>' . esc_html( $c['answer'] ) . '</p></div>';
}

/** Full article-style content below the calculator. */
function fs_render_tool_content( $c ) {
    $post_id = get_the_ID();
    $focus   = $c['focus'];
    ?>
<section class="fst-content" aria-label="About this calculator">
  <div class="container fst-content__inner">
    <p class="fst-content__meta">By <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">Abdul Rahman</a> &middot; Updated <time datetime="<?php echo esc_attr( get_the_modified_date( 'c', $post_id ) ); ?>"><?php echo esc_html( get_the_modified_date( 'F j, Y', $post_id ) ); ?></time></p>

    <h2>How to use this <?php echo esc_html( $focus ); ?></h2>
    <ol class="fst-steps">
      <?php foreach ( $c['steps'] as $step ) echo '<li>' . esc_html( $step ) . '</li>'; ?>
    </ol>

    <h2>The math behind the result</h2>
    <?php echo wp_kses_post( $c['formula'] ); ?>

    <h2>Worked example</h2>
    <?php echo wp_kses_post( $c['example'] ); ?>

    <?php foreach ( $c['sections'] as $sec ) : ?>
      <h2><?php echo esc_html( $sec[0] ); ?></h2>
      <?php echo wp_kses_post( $sec[1] ); ?>
    <?php endforeach; ?>

    <h2>Key terms</h2>
    <dl class="fst-terms">
      <?php foreach ( $c['terms'] as $t ) echo '<dt>' . esc_html( $t[0] ) . '</dt><dd>' . esc_html( $t[1] ) . '</dd>'; ?>
    </dl>

    <h2>Frequently asked questions</h2>
    <div class="fst-faq">
      <?php foreach ( $c['faqs'] as $f ) : ?>
        <h3><?php echo esc_html( $f[0] ); ?></h3>
        <p><?php echo esc_html( $f[1] ); ?></p>
      <?php endforeach; ?>
    </div>

    <?php if ( ! empty( $c['sources'] ) ) : ?>
    <h2>Sources and further reading</h2>
    <ul class="fst-sources">
      <?php foreach ( $c['sources'] as $s ) echo '<li><a href="' . esc_url( $s[1] ) . '" rel="noopener" target="_blank">' . esc_html( $s[0] ) . '</a></li>'; ?>
    </ul>
    <?php endif; ?>

    <?php
    $links = [];
    foreach ( (array) ( $c['related'] ?? [] ) as $slug ) {
        $rel = get_page_by_path( $slug, OBJECT, 'fs_tool' );
        if ( $rel && 'publish' === $rel->post_status ) {
            $links[] = '<li><a href="' . esc_url( get_permalink( $rel ) ) . '">' . esc_html( get_the_title( $rel ) ) . '</a></li>';
        }
    }
    if ( $links ) echo '<h2>Related calculators</h2><ul class="fst-related">' . implode( '', $links ) . '</ul>';
    ?>
    <p class="fst-content__disclaimer">Figures in the examples are illustrative and use the inputs shown. Market rates and program rules change; confirm current terms with your lender or the linked official sources before making a decision.</p>
  </div>
</section>
    <?php
}

/** FAQPage schema entity for a tool, built from the content record. */
function fs_tool_content_faq_schema( $c ) {
    $entities = [];
    foreach ( $c['faqs'] as $f ) {
        $entities[] = [
            '@type'          => 'Question',
            'name'           => $f[0],
            'acceptedAnswer' => [ '@type' => 'Answer', 'text' => $f[1] ],
        ];
    }
    return $entities ? [ '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $entities ] : null;
}

/**
 * Sync Rank Math meta + hero excerpt for the tools we have copy for.
 * Re-runs automatically whenever tool-content-data.php changes.
 */
function fs_apply_tool_content_seo() {
    $all  = fs_tool_content_data();
    $hash = md5( wp_json_encode( $all ) );
    if ( get_option( 'fs_tool_content_seo_hash' ) === $hash ) return;

    foreach ( $all as $slug => $c ) {
        $post = get_page_by_path( $slug, OBJECT, 'fs_tool' );
        if ( ! $post ) continue;
        $id    = $post->ID;
        $title = $c['title'] . ' | FinanceSpots';
        $kw    = implode( ',', array_slice( array_merge( [ $c['focus'] ], $c['secondary'] ), 0, 5 ) );
        update_post_meta( $id, 'rank_math_title', $title );
        update_post_meta( $id, 'rank_math_description', $c['meta'] );
        update_post_meta( $id, 'rank_math_focus_keyword', $kw );
        update_post_meta( $id, 'rank_math_og_title', $title );
        update_post_meta( $id, 'rank_math_og_description', $c['meta'] );
        update_post_meta( $id, 'rank_math_twitter_title', $title );
        update_post_meta( $id, 'rank_math_twitter_description', $c['meta'] );
        if ( $post->post_excerpt !== $c['excerpt'] ) {
            wp_update_post( [ 'ID' => $id, 'post_excerpt' => $c['excerpt'] ] );
        }
    }
    update_option( 'fs_tool_content_seo_hash', $hash, false );
}
add_action( 'init', 'fs_apply_tool_content_seo', 60 );
