<?php
/**
 * Template: Tool Category Archive
 * @package financespots
 */
get_header();

$term      = get_queried_object();
$term_slug = $term ? $term->slug : '';
$term_name = $term ? $term->name : 'Tools';

$icons = [
    'loan-calculators'    => '🏦',
    'investment-tools'    => '📈',
    'tax-calculators'     => '🧾',
    'savings-planners'    => '💰',
    'retirement-planning' => '🏖️',
    'currency-converters' => '💱',
    'budget-analyzers'    => '📊',
    'crypto-tools'        => '₿',
];
$descs = [
    'loan-calculators'    => 'Mortgage, auto, personal loans & more',
    'investment-tools'    => 'ROI, compound interest, portfolio analysis',
    'tax-calculators'     => 'Income tax, capital gains, deductions',
    'savings-planners'    => 'Emergency funds, goals, retirement savings',
    'retirement-planning' => '401k, IRA, pension, FIRE calculators',
    'currency-converters' => 'Live forex rates & currency conversion',
    'budget-analyzers'    => 'Monthly budgets, expense tracking, 50/30/20',
    'crypto-tools'        => 'Crypto P&L, staking rewards, DCA calculator',
];

// Per-tool-type icon map
$type_icons = [
    'loan_payment'   => '💵',
    'amortization'   => '📅',
    'refinance'      => '🔄',
    'loan_compare'   => '⚖️',
    'affordability'  => '🏠',
    'compound'       => '📊',
    'roi'            => '📈',
    'dividend'       => '💸',
    'dca'            => '🔁',
    'portfolio'      => '🗂️',
    'capital_gains'  => '💹',
    'cagr'           => '🚀',
    'pv'             => '⏮️',
    'breakeven'      => '⚖️',
    'inflation'      => '📉',
    'sharpe'         => '📐',
    'bond'           => '🏛️',
    'options'        => '🎯',
    'risk'           => '🛡️',
    'income_tax'     => '🧾',
    'self_emp_tax'   => '💼',
    'savings_goal'   => '🎯',
    'savings_52'     => '📆',
    'retirement'     => '🏖️',
    'fire'           => '🔥',
    'budget'         => '📋',
    'budget_503020'  => '📊',
    'dti'            => '⚖️',
    'net_worth'      => '💎',
    'currency'       => '💱',
    'crypto_convert' => '₿',
    'crypto_pnl'     => '📊',
    'staking'        => '⛏️',
    'mining'         => '⚙️',
    'simple_calc'    => '🧮',
    'va_loan_advanced'=> '🎖️',
];

$icon  = $icons[ $term_slug ] ?? '🛠️';
$desc  = $descs[ $term_slug ] ?? 'Browse all available tools in this category.';
$count = $term ? $term->count : 0;

// Collect all posts for JS search
$all_posts = [];
if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        $all_posts[] = [
            'id'    => get_the_ID(),
            'title' => get_the_title(),
            'desc'  => get_the_excerpt(),
            'url'   => get_permalink(),
            'type'  => get_post_meta( get_the_ID(), '_fs_tool_type', true ) ?: 'simple_calc',
        ];
    }
}
?>

<!-- ── HERO ── -->
<section class="fst-hero">
    <div class="container">
        <div class="fst-hero__inner">
            <a href="<?php echo esc_url( home_url( '/tools/' ) ); ?>"
               style="display:inline-flex;align-items:center;gap:.4rem;font-size:.82rem;font-weight:600;color:rgba(255,255,255,.4);text-decoration:none;margin-bottom:.5rem;transition:color .2s"
               onmouseover="this.style.color='#00C896'" onmouseout="this.style.color='rgba(255,255,255,.4)'">
                ← All Tool Categories
            </a>
            <span class="fst-hero__icon"><?php echo $icon; ?></span>
            <h1 class="fst-hero__title"><?php echo esc_html( $term_name ); ?></h1>
            <p class="fst-hero__sub">
                <?php echo esc_html( $desc ); ?> &mdash;
                <strong style="color:#00C896"><?php echo $count; ?> free tools</strong>
            </p>

            <!-- Live search -->
            <div class="fst-search">
                <div class="fst-search__inner">
                    <input type="text" class="fst-search__input" id="fst-search-input"
                           placeholder="Search <?php echo esc_attr($term_name); ?>...">
                    <button class="fst-search__btn">🔍</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats bar -->
    <div class="fst-stats-bar" style="margin-top:2rem">
        <div class="container">
            <div class="fst-stats-bar__inner">
                <span class="fst-stats-bar__item">✅ <strong><?php echo $count; ?></strong> Free Tools</span>
                <span class="fst-stats-bar__item">📄 <strong>PDF</strong> Export</span>
                <span class="fst-stats-bar__item">⚡ <strong>Instant</strong> Results</span>
                <span class="fst-stats-bar__item">🔒 <strong>No</strong> Signup</span>
            </div>
        </div>
    </div>
</section>

<!-- ── TOOLS GRID ── -->
<section class="fst-archive">
    <div class="container">

        <?php if ( ! empty( $all_posts ) ) : ?>

        <div class="fst-toolbar">
            <p class="fst-toolbar__count">Showing <strong id="fst-visible-count"><?php echo count($all_posts); ?></strong> of <strong><?php echo count($all_posts); ?></strong> tools</p>
        </div>

        <div class="fst-grid" id="fst-tools-grid">
            <?php foreach ( $all_posts as $p ) :
                $t_icon  = get_post_meta( $p['id'], '_fs_card_icon',        true ) ?: ( $type_icons[ $p['type'] ] ?? '🧮' );
                $card_bg = get_post_meta( $p['id'], '_fs_card_bg',          true ) ?: '#ffffff';
                $badge   = get_post_meta( $p['id'], '_fs_card_badge',       true );
                $c_link  = get_post_meta( $p['id'], '_fs_card_link',        true ) ?: $p['url'];
                $c_target= get_post_meta( $p['id'], '_fs_card_link_target', true ) ?: '_self';
                // Darken card bg for border tint
                $border_color = $card_bg !== '#ffffff' ? 'rgba(0,0,0,.08)' : 'var(--fs-border)';
            ?>
            <a href="<?php echo esc_url( $c_link ); ?>"
               target="<?php echo esc_attr( $c_target ); ?>"
               class="fst-card"
               data-title="<?php echo esc_attr( strtolower($p['title']) ); ?>"
               style="background:<?php echo esc_attr($card_bg); ?>;border-color:<?php echo $border_color; ?>">
                <?php if ( $badge ) : ?>
                <span style="position:absolute;top:12px;right:12px;font-size:.7rem;font-weight:700;background:linear-gradient(135deg,#00C896,#3B82F6);color:#fff;padding:3px 8px;border-radius:20px;white-space:nowrap">
                    <?php echo esc_html($badge); ?>
                </span>
                <?php endif; ?>
                <div class="fst-card__icon"><?php echo $t_icon; ?></div>
                <h3 class="fst-card__title"><?php echo esc_html( $p['title'] ); ?></h3>
                <p class="fst-card__desc"><?php echo esc_html( $p['desc'] ); ?></p>
                <span class="fst-card__cta">Open Calculator →</span>
            </a>
            <?php endforeach; ?>
        </div>

        <div id="fst-no-results" class="fst-empty" style="display:none">
            <div class="fst-empty__icon">🔍</div>
            <h3 class="fst-empty__title">No tools match your search</h3>
            <p class="fst-empty__desc">Try a different keyword or browse all tools below.</p>
            <button onclick="document.getElementById('fst-search-input').value='';filterTools()"
                    style="padding:.6rem 1.4rem;background:var(--fs-primary);color:#fff;border:none;border-radius:8px;font-weight:700;cursor:pointer">
                Clear Search
            </button>
        </div>

        <?php else : ?>
        <div class="fst-empty">
            <div class="fst-empty__icon">🛠️</div>
            <h3 class="fst-empty__title">No tools found in this category</h3>
            <p class="fst-empty__desc">Check back soon — we're adding new tools every week.</p>
            <a href="<?php echo esc_url( home_url('/') ); ?>"
               style="display:inline-block;padding:.75rem 1.5rem;background:var(--fs-primary);color:#fff;border-radius:8px;text-decoration:none;font-weight:700">
                Back to Home
            </a>
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- ── RELATED CATEGORIES ── -->
<section style="padding:3rem 0 4rem;background:#fff;border-top:1px solid var(--fs-border)">
    <div class="container">
        <h2 style="font-size:1.4rem;font-weight:800;color:var(--fs-text);margin:0 0 1.5rem;text-align:center">
            Explore More Tool Categories
        </h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem">
            <?php
            $all_terms = get_terms( [ 'taxonomy' => 'fs_tool_cat', 'hide_empty' => false ] );
            foreach ( $all_terms as $t ) :
                if ( $t->slug === $term_slug ) continue;
                $t_icon = $icons[ $t->slug ] ?? '🛠️';
                $t_link = get_term_link( $t );
            ?>
            <a href="<?php echo esc_url( $t_link ); ?>"
               style="display:flex;align-items:center;gap:.75rem;padding:1rem;background:var(--fs-bg);border:1.5px solid var(--fs-border);border-radius:var(--fs-radius);text-decoration:none;color:var(--fs-text);font-weight:600;font-size:.9rem;transition:all .2s"
               onmouseover="this.style.borderColor='#00C896';this.style.background='rgba(0,200,150,.06)'"
               onmouseout="this.style.borderColor='var(--fs-border)';this.style.background='var(--fs-bg)'">
                <span style="font-size:1.4rem"><?php echo $t_icon; ?></span>
                <span><?php echo esc_html($t->name); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── LIVE SEARCH JS ── -->
<script>
function filterTools() {
    var q     = document.getElementById('fst-search-input').value.toLowerCase().trim();
    var cards = document.querySelectorAll('#fst-tools-grid .fst-card');
    var shown = 0;
    cards.forEach(function(card) {
        var title = card.getAttribute('data-title') || '';
        var match = !q || title.indexOf(q) !== -1;
        card.style.display = match ? '' : 'none';
        if (match) shown++;
    });
    document.getElementById('fst-visible-count').textContent = shown;
    document.getElementById('fst-no-results').style.display = (shown === 0 && q) ? 'block' : 'none';
}
document.getElementById('fst-search-input').addEventListener('input', filterTools);
</script>

<?php get_footer(); ?>
