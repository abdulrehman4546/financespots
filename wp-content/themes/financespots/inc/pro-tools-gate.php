<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* =========================================================
   FinanceSpots -- PRO Tool Gate System
   - Mark any page as PRO tool via meta box
   - Auto blur overlay for non-PRO users
   - [fs_pro_gate] shortcode for inline gating
   - Admin: list & manage all PRO tools
   ========================================================= */

/* ── 1. Define default PRO tools (page slugs) ── */
function fs_get_default_pro_tools() {
    return [
        'retirement-planner'     => 'Retirement Planner',
        'portfolio-tracker'      => 'Portfolio Tracker',
        'tax-estimator'          => 'Tax Estimator',
        'net-worth-tracker'      => 'Net Worth Tracker',
        'advanced-mortgage'      => 'Advanced Mortgage Analyzer',
        'debt-payoff-planner'    => 'Debt Payoff Planner',
        'investment-calculator'  => 'Investment Return Calculator',
        'fire-calculator'        => 'FIRE Calculator',
    ];
}

/* ── 2. Meta box: mark a page as PRO tool ── */
add_action( 'add_meta_boxes', function() {
    add_meta_box(
        'fs_pro_tool_box',
        '&#11088; PRO Tool Settings',
        'fs_pro_tool_metabox_html',
        'page',
        'side',
        'high'
    );
});

function fs_pro_tool_metabox_html( $post ) {
    $is_pro_tool = get_post_meta( $post->ID, 'fs_is_pro_tool', true );
    $tool_label  = get_post_meta( $post->ID, 'fs_pro_tool_label', true );
    wp_nonce_field( 'fs_pro_tool_nonce', 'fs_pro_tool_nonce' );
    ?>
    <div style="font-family:-apple-system,sans-serif;">
        <label style="display:flex;align-items:center;gap:8px;font-size:.875rem;cursor:pointer;padding:4px 0;">
            <input type="checkbox" name="fs_is_pro_tool" value="1" <?php checked($is_pro_tool,'1'); ?> style="width:16px;height:16px;accent-color:#10B981;">
            <span style="font-weight:600;">This is a PRO Tool</span>
        </label>
        <p style="font-size:.78rem;color:#64748B;margin:6px 0 12px;">Non-PRO users will see a locked overlay.</p>

        <label style="font-size:.78rem;font-weight:600;color:#374151;display:block;margin-bottom:4px;">Tool Display Name</label>
        <input type="text" name="fs_pro_tool_label" value="<?php echo esc_attr($tool_label); ?>"
               placeholder="e.g. Retirement Planner"
               style="width:100%;padding:6px 10px;border:1px solid #E2E8F0;border-radius:6px;font-size:.82rem;">
        <p style="font-size:.75rem;color:#94A3B8;margin:4px 0 0;">Shown on the lock screen.</p>
    </div>
    <?php
}

add_action( 'save_post', function( $post_id ) {
    if ( ! isset($_POST['fs_pro_tool_nonce']) ) return;
    if ( ! wp_verify_nonce($_POST['fs_pro_tool_nonce'], 'fs_pro_tool_nonce') ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_page', $post_id) ) return;

    $val = isset($_POST['fs_is_pro_tool']) ? '1' : '0';
    update_post_meta( $post_id, 'fs_is_pro_tool', $val );

    $label = sanitize_text_field( $_POST['fs_pro_tool_label'] ?? '' );
    update_post_meta( $post_id, 'fs_pro_tool_label', $label );
});

/* ── 3. Auto-inject overlay on PRO tool pages ── */
add_action( 'wp_footer', 'fs_maybe_inject_pro_gate' );

function fs_maybe_inject_pro_gate() {
    if ( ! is_page() ) return;
    $post_id = get_the_ID();
    if ( ! $post_id ) return;

    $is_pro_tool = get_post_meta( $post_id, 'fs_is_pro_tool', true );
    if ( $is_pro_tool !== '1' ) return;

    // PRO users see nothing
    if ( fs_is_pro() ) return;

    $label = get_post_meta( $post_id, 'fs_pro_tool_label', true ) ?: get_the_title();
    fs_render_pro_overlay( $label, get_the_permalink() );
}

/* ── 4. Shortcode: [fs_pro_gate label="Tool Name"] content [/fs_pro_gate] ── */
add_shortcode( 'fs_pro_gate', function( $atts, $content = '' ) {
    $atts = shortcode_atts(['label' => 'This Tool'], $atts);
    if ( fs_is_pro() ) return do_shortcode($content);

    ob_start();
    ?>
    <div class="fs-pro-inline-gate">
        <div class="fs-pro-inline-gate__content" aria-hidden="true">
            <?php echo do_shortcode($content); ?>
        </div>
        <div class="fs-pro-inline-gate__overlay">
            <?php fs_render_inline_lock( $atts['label'] ); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
});

/* ── 5. Render full-page overlay ── */
function fs_render_pro_overlay( $tool_name, $current_url = '' ) {
    $pricing_url = home_url('/pricing/');
    ?>
    <div class="fs-pg-overlay" id="fs-pg-overlay">
        <div class="fs-pg-blur-bg" aria-hidden="true"></div>
        <div class="fs-pg-modal" role="dialog" aria-labelledby="fs-pg-title">

            <div class="fs-pg-icon">&#128274;</div>

            <div class="fs-pg-badge">&#11088; PRO Feature</div>

            <h2 class="fs-pg-title" id="fs-pg-title">
                <?php echo esc_html($tool_name); ?> is PRO
            </h2>
            <p class="fs-pg-desc">
                Upgrade to FinanceSpots PRO to unlock this advanced tool plus 7 more premium features -- starting at just $9/month.
            </p>

            <div class="fs-pg-perks">
                <div class="fs-pg-perk"><span>&#128190;</span> Save your calculations</div>
                <div class="fs-pg-perk"><span>&#128196;</span> Export to PDF</div>
                <div class="fs-pg-perk"><span>&#128202;</span> Advanced scenarios</div>
                <div class="fs-pg-perk"><span>&#128683;</span> Ad-free experience</div>
            </div>

            <div class="fs-pg-actions">
                <a href="<?php echo esc_url($pricing_url); ?>" class="fs-pg-btn-upgrade">
                    &#11088; Upgrade to PRO -- From $9/mo
                </a>
                <?php if ( ! is_user_logged_in() ) : ?>
                <p class="fs-pg-signin-note">
                    Already PRO? <button class="fs-pg-signin-link" id="fs-pg-open-login">Sign in to your account &#x2192;</button>
                </p>
                <?php endif; ?>
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="fs-pg-btn-free">
                    &#x2190; Back to Free Tools
                </a>
            </div>

        </div>
    </div>

    <style>
    /* Full page gate */
    .fs-pg-overlay{position:fixed;inset:0;z-index:99998;display:flex;align-items:center;justify-content:center;padding:20px;}
    .fs-pg-blur-bg{position:absolute;inset:0;background:rgba(10,15,30,.92);backdrop-filter:blur(12px);}
    .fs-pg-modal{position:relative;z-index:1;background:#131929;border:1px solid rgba(16,185,129,.2);border-radius:28px;padding:44px 36px;max-width:480px;width:100%;text-align:center;box-shadow:0 40px 100px rgba(0,0,0,.7),0 0 0 1px rgba(16,185,129,.1);animation:fsPgIn .4s cubic-bezier(.34,1.56,.64,1);}
    @keyframes fsPgIn{from{opacity:0;transform:translateY(24px) scale(.95);}to{opacity:1;transform:translateY(0) scale(1);}}
    .fs-pg-icon{font-size:3rem;margin-bottom:12px;filter:drop-shadow(0 0 20px rgba(16,185,129,.4));}
    .fs-pg-badge{display:inline-block;background:linear-gradient(135deg,rgba(245,158,11,.15),rgba(16,185,129,.1));border:1px solid rgba(245,158,11,.3);color:#F59E0B;padding:5px 16px;border-radius:50px;font-size:.78rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase;margin-bottom:16px;}
    .fs-pg-title{font-size:1.6rem;font-weight:900;color:#fff;margin:0 0 12px;line-height:1.25;}
    .fs-pg-desc{color:#94A3B8;font-size:.9rem;line-height:1.7;margin-bottom:22px;}
    .fs-pg-perks{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:28px;}
    .fs-pg-perk{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:10px 12px;font-size:.82rem;color:#CBD5E1;display:flex;align-items:center;gap:8px;}
    .fs-pg-actions{display:flex;flex-direction:column;gap:10px;}
    .fs-pg-btn-upgrade{display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#10B981,#059669);color:#fff;padding:15px 24px;border-radius:14px;font-size:.95rem;font-weight:800;text-decoration:none;box-shadow:0 6px 24px rgba(16,185,129,.4);transition:all .25s;}
    .fs-pg-btn-upgrade:hover{transform:translateY(-2px);box-shadow:0 10px 32px rgba(16,185,129,.55);}
    .fs-pg-signin-note{font-size:.82rem;color:#64748B;margin:0;}
    .fs-pg-signin-link{background:none;border:none;color:#10B981;font-size:.82rem;font-weight:600;cursor:pointer;padding:0;font-family:inherit;}
    .fs-pg-signin-link:hover{text-decoration:underline;}
    .fs-pg-btn-free{color:#475569;font-size:.82rem;text-decoration:none;transition:color .2s;}
    .fs-pg-btn-free:hover{color:#94A3B8;}

    /* Inline gate */
    .fs-pro-inline-gate{position:relative;border-radius:16px;overflow:hidden;}
    .fs-pro-inline-gate__content{filter:blur(6px);pointer-events:none;user-select:none;}
    .fs-pro-inline-gate__overlay{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(10,15,30,.7);backdrop-filter:blur(2px);}
    </style>

    <script>
    // Prevent scrolling while gate is open
    document.body.style.overflow = 'hidden';

    // Sign in button opens login modal
    var pgLogin = document.getElementById('fs-pg-open-login');
    if(pgLogin){
        pgLogin.addEventListener('click', function(){
            var overlay = document.getElementById('fs-login-overlay');
            if(overlay){ overlay.classList.add('open'); overlay.setAttribute('aria-hidden','false'); }
        });
    }
    </script>
    <?php
}

/* ── 6. Render inline lock card ── */
function fs_render_inline_lock( $tool_name ) {
    ?>
    <div style="text-align:center;padding:32px 24px;max-width:320px;">
        <div style="font-size:2.5rem;margin-bottom:10px;">&#128274;</div>
        <span style="display:inline-block;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.3);color:#F59E0B;padding:3px 12px;border-radius:20px;font-size:.72rem;font-weight:800;text-transform:uppercase;margin-bottom:10px;">PRO Feature</span>
        <h3 style="color:#fff;font-size:1.1rem;font-weight:800;margin:0 0 8px;"><?php echo esc_html($tool_name); ?></h3>
        <p style="color:#64748B;font-size:.82rem;margin-bottom:18px;line-height:1.6;">Upgrade to unlock this advanced tool.</p>
        <a href="<?php echo esc_url(home_url('/pricing/')); ?>" style="display:inline-block;background:linear-gradient(135deg,#10B981,#059669);color:#fff;padding:10px 22px;border-radius:10px;font-size:.875rem;font-weight:700;text-decoration:none;">&#11088; Upgrade to PRO</a>
    </div>
    <?php
}

/* ── 7. Admin submenu: PRO Tools list ── */
add_action( 'admin_menu', 'fs_pro_tools_admin_menu' );
function fs_pro_tools_admin_menu() {
    add_submenu_page(
        'fs-pro',
        'PRO Tools',
        'PRO Tools',
        'manage_options',
        'fs-pro-tools',
        'fs_pro_tools_admin_page'
    );
}

function fs_pro_tools_admin_page() {
    // Get all pages marked as PRO tool
    $pro_pages = get_posts([
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_key'       => 'fs_is_pro_tool',
        'meta_value'     => '1',
    ]);

    // Get all pages for the "add" dropdown
    $all_pages = get_pages(['post_status' => 'publish']);

    // Handle quick-mark via form
    if ( isset($_POST['fs_quick_mark']) && check_admin_referer('fs_quick_mark') ) {
        $pid = intval($_POST['page_id']);
        $lbl = sanitize_text_field($_POST['tool_label']);
        if ($pid) {
            update_post_meta($pid, 'fs_is_pro_tool', '1');
            update_post_meta($pid, 'fs_pro_tool_label', $lbl ?: get_the_title($pid));
            echo '<div class="notice notice-success"><p>Page marked as PRO tool.</p></div>';
            $pro_pages = get_posts(['post_type'=>'page','post_status'=>'publish','posts_per_page'=>-1,'meta_key'=>'fs_is_pro_tool','meta_value'=>'1']);
        }
    }

    // Handle unmark
    if ( isset($_GET['unmark']) && check_admin_referer('fs_unmark_tool') ) {
        $pid = intval($_GET['unmark']);
        update_post_meta($pid, 'fs_is_pro_tool', '0');
        echo '<div class="notice notice-success"><p>Page unmarked from PRO tools.</p></div>';
        $pro_pages = get_posts(['post_type'=>'page','post_status'=>'publish','posts_per_page'=>-1,'meta_key'=>'fs_is_pro_tool','meta_value'=>'1']);
    }
    ?>
    <div class="wrap" style="font-family:-apple-system,sans-serif;max-width:800px;">
        <h1 style="font-size:1.5rem;margin-bottom:6px;">&#128274; PRO Tools Management</h1>
        <p style="color:#64748B;margin-bottom:24px;">Mark pages as PRO tools -- non-PRO users will see a locked overlay on those pages.</p>

        <!-- Quick mark form -->
        <div style="background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:24px;margin-bottom:24px;">
            <h3 style="margin:0 0 16px;font-size:1rem;color:#1E293B;">Mark a Page as PRO Tool</h3>
            <form method="post" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
                <?php wp_nonce_field('fs_quick_mark'); ?>
                <div style="flex:1;min-width:200px;">
                    <label style="font-size:.78rem;font-weight:600;color:#64748B;display:block;margin-bottom:4px;">Select Page</label>
                    <select name="page_id" style="width:100%;padding:8px 12px;border:1.5px solid #E2E8F0;border-radius:8px;font-size:.875rem;">
                        <option value="">-- Choose a page --</option>
                        <?php foreach($all_pages as $p): ?>
                        <option value="<?php echo $p->ID; ?>"><?php echo esc_html($p->post_title); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="flex:1;min-width:180px;">
                    <label style="font-size:.78rem;font-weight:600;color:#64748B;display:block;margin-bottom:4px;">Tool Display Name</label>
                    <input type="text" name="tool_label" placeholder="e.g. Retirement Planner"
                           style="width:100%;padding:8px 12px;border:1.5px solid #E2E8F0;border-radius:8px;font-size:.875rem;">
                </div>
                <button name="fs_quick_mark" style="background:#10B981;color:#fff;border:none;padding:9px 20px;border-radius:8px;font-weight:700;cursor:pointer;white-space:nowrap;">&#128274; Mark as PRO</button>
            </form>
        </div>

        <!-- Current PRO tools -->
        <div style="background:#fff;border:1px solid #E2E8F0;border-radius:16px;overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:2px solid #E2E8F0;background:#F8FAFC;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-weight:700;color:#1E293B;">Active PRO Tools (<?php echo count($pro_pages); ?>)</span>
                <span style="font-size:.78rem;color:#64748B;">Non-PRO users see a lock overlay on these pages</span>
            </div>

            <?php if(empty($pro_pages)): ?>
            <div style="padding:40px;text-align:center;color:#94A3B8;font-size:.875rem;">
                No PRO tools yet. Mark pages above or edit individual pages in the editor.
            </div>
            <?php else: ?>
            <table style="width:100%;border-collapse:collapse;font-size:.875rem;">
                <thead><tr style="border-bottom:1px solid #E2E8F0;">
                    <th style="padding:12px 16px;text-align:left;color:#64748B;font-weight:600;">Page</th>
                    <th style="padding:12px 16px;text-align:left;color:#64748B;font-weight:600;">Tool Label</th>
                    <th style="padding:12px 16px;text-align:left;color:#64748B;font-weight:600;">URL</th>
                    <th style="padding:12px 16px;text-align:left;color:#64748B;font-weight:600;">Actions</th>
                </tr></thead>
                <tbody>
                <?php foreach($pro_pages as $i=>$p):
                    $label = get_post_meta($p->ID,'fs_pro_tool_label',true) ?: $p->post_title;
                ?>
                <tr style="border-bottom:1px solid #F1F5F9;<?php echo $i%2?'background:#FAFBFC':''; ?>">
                    <td style="padding:12px 16px;font-weight:600;color:#1E293B;">
                        <span style="display:inline-block;width:8px;height:8px;background:#10B981;border-radius:50%;margin-right:8px;"></span>
                        <?php echo esc_html($p->post_title); ?>
                    </td>
                    <td style="padding:12px 16px;">
                        <span style="background:#FEF3C7;color:#D97706;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:700;">
                            &#128274; <?php echo esc_html($label); ?>
                        </span>
                    </td>
                    <td style="padding:12px 16px;">
                        <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" target="_blank"
                           style="color:#3B82F6;font-size:.8rem;text-decoration:none;">
                            /<?php echo esc_html($p->post_name); ?>/ &#x2197;
                        </a>
                    </td>
                    <td style="padding:12px 16px;display:flex;gap:6px;">
                        <a href="<?php echo esc_url(get_edit_post_link($p->ID)); ?>"
                           style="background:#EFF6FF;color:#3B82F6;padding:5px 12px;border-radius:6px;font-size:.78rem;text-decoration:none;font-weight:600;">Edit</a>
                        <a href="<?php echo wp_nonce_url(add_query_arg(['unmark'=>$p->ID]),'fs_unmark_tool'); ?>"
                           onclick="return confirm('Remove PRO lock from this page?')"
                           style="background:#FEE2E2;color:#EF4444;padding:5px 12px;border-radius:6px;font-size:.78rem;text-decoration:none;font-weight:600;">Unlock</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <!-- How to use -->
        <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:12px;padding:18px 20px;margin-top:20px;font-size:.85rem;">
            <strong style="color:#059669;">&#128161; How to use Shortcode (for partial locking):</strong><br>
            <code style="background:#D1FAE5;padding:4px 8px;border-radius:4px;display:inline-block;margin-top:6px;">[fs_pro_gate label="Tool Name"] your tool HTML here [/fs_pro_gate]</code><br>
            <span style="color:#047857;margin-top:6px;display:block;">Free users see a blur overlay. PRO users see the full tool.</span>
        </div>
    </div>
    <?php
}
