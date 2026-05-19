<?php
/**
 * Template: All Tools Archive  (/tools/)
 * @package financespots
 */
get_header();

$cat_icons = [
    'loan-calculators'    => '🏦',
    'investment-tools'    => '📈',
    'tax-calculators'     => '🧾',
    'savings-planners'    => '💰',
    'retirement-planning' => '🏖️',
    'currency-converters' => '💱',
    'budget-analyzers'    => '📊',
    'crypto-tools'        => '₿',
];
$cat_descs = [
    'loan-calculators'    => 'Mortgage, auto, personal loans & more',
    'investment-tools'    => 'ROI, compound interest, portfolio analysis',
    'tax-calculators'     => 'Income tax, capital gains, deductions',
    'savings-planners'    => 'Emergency funds, goals, retirement savings',
    'retirement-planning' => '401k, IRA, pension, FIRE calculators',
    'currency-converters' => 'Live forex rates & currency conversion',
    'budget-analyzers'    => 'Monthly budgets, expense tracking, 50/30/20',
    'crypto-tools'        => 'Crypto P&L, staking rewards, DCA calculator',
];

$categories = get_terms( [
    'taxonomy'   => 'fs_tool_cat',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
] );

$total_tools = array_sum( array_map( fn($c) => $c->count, is_array($categories) ? $categories : [] ) );
?>

<!-- ── HERO ── -->
<section class="fst-hero">
    <div class="container">
        <div class="fst-hero__inner">
            <span class="fst-hero__icon">🛠️</span>
            <h1 class="fst-hero__title">All Finance Tools</h1>
            <p class="fst-hero__sub">
                <?php echo $total_tools ?: '110'; ?>+ free calculators across <?php echo count( (array) $categories ); ?> categories — instant results, no signup required
            </p>
            <div class="fst-search">
                <div class="fst-search__inner">
                    <input type="text" class="fst-search__input" id="fst-cat-search"
                           placeholder="Search categories...">
                    <button class="fst-search__btn">🔍</button>
                </div>
            </div>
        </div>
    </div>
    <div class="fst-stats-bar" style="margin-top:2rem">
        <div class="container">
            <div class="fst-stats-bar__inner">
                <span class="fst-stats-bar__item">✅ <strong><?php echo $total_tools ?: '110'; ?>+</strong> Free Tools</span>
                <span class="fst-stats-bar__item">📄 <strong>PDF</strong> Export on Every Tool</span>
                <span class="fst-stats-bar__item">⚡ <strong>Instant</strong> Results</span>
                <span class="fst-stats-bar__item">🔒 <strong>No</strong> Signup Needed</span>
            </div>
        </div>
    </div>
</section>

<!-- ── CATEGORIES GRID ── -->
<section style="padding:3.5rem 0 5rem;background:var(--fs-bg)">
    <div class="container">
        <?php if ( ! empty($categories) && ! is_wp_error($categories) ) : ?>

        <div class="fst-toolbar">
            <p class="fst-toolbar__count">
                <strong><?php echo count($categories); ?></strong> categories,
                <strong><?php echo $total_tools ?: '110'; ?>+</strong> tools total
            </p>
        </div>

        <div class="fst-cat-grid" id="fst-cat-grid">
            <?php foreach ( $categories as $cat ) :
                $icon = $cat_icons[ $cat->slug ] ?? '🛠️';
                $desc = $cat_descs[ $cat->slug ] ?? '';
                $url  = get_term_link( $cat );
            ?>
            <a href="<?php echo esc_url( $url ); ?>" class="fst-cat-card"
               data-name="<?php echo esc_attr( strtolower($cat->name) ); ?>">
                <div class="fst-cat-card__icon"><?php echo $icon; ?></div>
                <h2 class="fst-cat-card__name"><?php echo esc_html( $cat->name ); ?></h2>
                <p style="font-size:.875rem;color:var(--fs-text-muted);margin:0;line-height:1.5">
                    <?php echo esc_html( $desc ); ?>
                </p>
                <span class="fst-cat-card__count"><?php echo $cat->count; ?> tools →</span>
            </a>
            <?php endforeach; ?>
        </div>

        <?php else : ?>
        <div class="fst-empty">
            <div class="fst-empty__icon">🛠️</div>
            <h3 class="fst-empty__title">Tools are loading…</h3>
            <p class="fst-empty__desc">Visit the WP admin panel once to initialize all tools automatically.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ── POPULAR TOOLS STRIP ── -->
<?php
$popular = get_posts( [
    'post_type'      => 'fs_tool',
    'posts_per_page' => 6,
    'meta_key'       => '_fs_tool_featured',
    'meta_value'     => '1',
    'post_status'    => 'publish',
] );
if ( empty($popular) ) {
    $popular = get_posts( [ 'post_type' => 'fs_tool', 'posts_per_page' => 6, 'post_status' => 'publish' ] );
}
if ( $popular ) :
?>
<section style="padding:3rem 0 4rem;background:#fff;border-top:1px solid var(--fs-border)">
    <div class="container">
        <h2 style="font-size:1.5rem;font-weight:800;color:var(--fs-text);margin:0 0 1.5rem;text-align:center">
            ⭐ Most Popular Tools
        </h2>
        <div class="fst-grid">
            <?php foreach ( $popular as $p ) :
                $type = get_post_meta($p->ID, '_fs_tool_type', true) ?: 'simple_calc';
                $type_icons = [
                    'va_loan_advanced'=>'🎖️','loan_payment'=>'💵','compound'=>'📊','income_tax'=>'🧾',
                    'budget'=>'📋','crypto_pnl'=>'₿','roi'=>'📈','savings_goal'=>'🎯',
                    'retirement'=>'🏖️','currency'=>'💱','budget_503020'=>'📊','dca'=>'🔁',
                ];
                $t_icon = $type_icons[$type] ?? '🧮';
            ?>
            <a href="<?php echo esc_url( get_permalink($p) ); ?>" class="fst-card">
                <div class="fst-card__icon"><?php echo $t_icon; ?></div>
                <h3 class="fst-card__title"><?php echo esc_html($p->post_title); ?></h3>
                <p class="fst-card__desc"><?php echo esc_html(get_the_excerpt($p)); ?></p>
                <span class="fst-card__cta">Open Calculator →</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
document.getElementById('fst-cat-search').addEventListener('input', function() {
    var q = this.value.toLowerCase().trim();
    document.querySelectorAll('#fst-cat-grid .fst-cat-card').forEach(function(card) {
        var name = card.getAttribute('data-name') || '';
        card.style.display = (!q || name.indexOf(q) !== -1) ? '' : 'none';
    });
});
</script>

<?php get_footer(); ?>
