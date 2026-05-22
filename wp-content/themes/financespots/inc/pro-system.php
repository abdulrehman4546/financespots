<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* =========================================================
   FinanceSpots PRO System
   - PRO user role & meta
   - Stripe payment integration
   - Feature gating helpers
   - Admin: manage PRO users
   ========================================================= */

/* ── 1. PRO Role on init ── */
add_action( 'init', 'fs_register_pro_role' );
function fs_register_pro_role() {
    if ( ! get_role('fs_pro') ) {
        add_role( 'fs_pro', 'FinanceSpots PRO', [
            'read' => true,
        ]);
    }
}

/* ── 2. Helper: is user PRO? ── */
function fs_is_pro( $user_id = null ) {
    if ( ! $user_id ) $user_id = get_current_user_id();
    if ( ! $user_id ) return false;
    // Admin is always PRO
    if ( user_can( $user_id, 'manage_options' ) ) return true;
    return get_user_meta( $user_id, 'fs_pro_active', true ) === '1';
}

/* ── 3. PRO Plans config ── */
function fs_get_plans() {
    return [
        'monthly' => [
            'name'        => 'PRO Monthly',
            'price'       => 9,
            'period'      => 'month',
            'stripe_price'=> get_option('fs_stripe_price_monthly', ''),
            'badge'       => 'Most Flexible',
        ],
        'yearly' => [
            'name'        => 'PRO Yearly',
            'price'       => 79,
            'period'      => 'year',
            'stripe_price'=> get_option('fs_stripe_price_yearly', ''),
            'badge'       => 'Best Value -- Save 27%',
            'popular'     => true,
        ],
        'lifetime' => [
            'name'        => 'PRO Lifetime',
            'price'       => 199,
            'period'      => 'once',
            'stripe_price'=> get_option('fs_stripe_price_lifetime', ''),
            'badge'       => 'Pay Once Forever',
        ],
    ];
}

/* ── 4. PRO Features list ── */
function fs_pro_features() {
    return [
        'Save & bookmark calculations',
        'Export results to PDF',
        'Calculation history (unlimited)',
        'Advanced mortgage scenarios',
        'Portfolio tracker',
        'Ad-free experience',
        'Priority email support',
        'Early access to new tools',
    ];
}

/* ── 5. AJAX: Create Stripe Checkout Session ── */
add_action( 'wp_ajax_fs_create_checkout',        'fs_ajax_create_checkout' );
add_action( 'wp_ajax_nopriv_fs_create_checkout', 'fs_ajax_create_checkout' );

function fs_ajax_create_checkout() {
    check_ajax_referer( 'fs_pro_nonce', 'nonce' );

    $plan = sanitize_text_field( $_POST['plan'] ?? 'monthly' );
    $plans = fs_get_plans();

    if ( ! isset( $plans[$plan] ) ) {
        wp_send_json_error( 'Invalid plan.' );
    }

    $secret_key = get_option( 'fs_stripe_secret_key', '' );
    if ( ! $secret_key ) {
        wp_send_json_error( 'Payment not configured yet. Please contact admin.' );
    }

    $price_id = $plans[$plan]['stripe_price'];
    if ( ! $price_id ) {
        wp_send_json_error( 'This plan is not yet configured. Please contact admin.' );
    }

    $user_id = get_current_user_id();

    // Build Stripe Checkout session via API
    $mode = ( $plan === 'lifetime' ) ? 'payment' : 'subscription';

    $body = [
        'mode'                => $mode,
        'line_items[0][price]'    => $price_id,
        'line_items[0][quantity]' => 1,
        'success_url'         => home_url('/pro-success/?session_id={CHECKOUT_SESSION_ID}&plan=' . $plan),
        'cancel_url'          => home_url('/pricing/'),
        'metadata[user_id]'   => $user_id,
        'metadata[plan]'      => $plan,
    ];

    if ( $user_id ) {
        $user = get_userdata( $user_id );
        $body['customer_email'] = $user->user_email;
    }

    $response = wp_remote_post( 'https://api.stripe.com/v1/checkout/sessions', [
        'headers' => [
            'Authorization' => 'Bearer ' . $secret_key,
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ],
        'body'    => http_build_query( $body ),
        'timeout' => 20,
    ]);

    if ( is_wp_error( $response ) ) {
        wp_send_json_error( 'Connection error. Please try again.' );
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( isset( $data['error'] ) ) {
        wp_send_json_error( $data['error']['message'] ?? 'Payment error.' );
    }

    wp_send_json_success( [ 'url' => $data['url'] ] );
}

/* ── 6. PRO Success page handler ── */
add_action( 'template_redirect', 'fs_pro_success_handler' );
function fs_pro_success_handler() {
    if ( ! isset( $_GET['session_id'] ) || strpos( $_SERVER['REQUEST_URI'] ?? '', 'pro-success' ) === false ) return;

    $session_id = sanitize_text_field( $_GET['session_id'] );
    $secret_key = get_option( 'fs_stripe_secret_key', '' );
    if ( ! $secret_key || ! $session_id ) return;

    // Verify session with Stripe
    $response = wp_remote_get( 'https://api.stripe.com/v1/checkout/sessions/' . $session_id, [
        'headers' => [ 'Authorization' => 'Bearer ' . $secret_key ],
        'timeout' => 15,
    ]);

    if ( is_wp_error( $response ) ) return;

    $session = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( isset($session['payment_status']) && in_array($session['payment_status'], ['paid','no_payment_required']) ) {
        $user_id = intval( $session['metadata']['user_id'] ?? 0 );
        $plan    = sanitize_text_field( $session['metadata']['plan'] ?? 'monthly' );

        if ( $user_id ) {
            fs_activate_pro( $user_id, $plan, $session_id );
        } elseif ( is_user_logged_in() ) {
            fs_activate_pro( get_current_user_id(), $plan, $session_id );
        }
    }
}

function fs_activate_pro( $user_id, $plan, $session_id = '' ) {
    update_user_meta( $user_id, 'fs_pro_active',  '1' );
    update_user_meta( $user_id, 'fs_pro_plan',    $plan );
    update_user_meta( $user_id, 'fs_pro_since',   current_time('mysql') );
    update_user_meta( $user_id, 'fs_pro_session', $session_id );

    // Add PRO role (keep existing roles)
    $user = new WP_User( $user_id );
    $user->add_role( 'fs_pro' );
}

/* ── 7. Stripe Webhook ── */
add_action( 'wp_ajax_nopriv_fs_stripe_webhook', 'fs_stripe_webhook' );
function fs_stripe_webhook() {
    $payload   = file_get_contents('php://input');
    $sig       = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
    $secret    = get_option('fs_stripe_webhook_secret', '');

    // Verify signature if webhook secret is set
    if ( $secret ) {
        $parts = [];
        foreach ( explode(',', $sig) as $part ) {
            list($k,$v) = explode('=', $part, 2);
            $parts[$k] = $v;
        }
        $signed = hash_hmac('sha256', $parts['t'] . '.' . $payload, $secret);
        if ( ! hash_equals($signed, $parts['v1'] ?? '') ) {
            status_header(400); exit('Invalid signature');
        }
    }

    $event = json_decode( $payload, true );
    $type  = $event['type'] ?? '';

    if ( $type === 'checkout.session.completed' ) {
        $session = $event['data']['object'];
        $user_id = intval( $session['metadata']['user_id'] ?? 0 );
        $plan    = sanitize_text_field( $session['metadata']['plan'] ?? '' );
        if ( $user_id ) fs_activate_pro( $user_id, $plan, $session['id'] );
    }

    if ( $type === 'customer.subscription.deleted' ) {
        // Subscription cancelled -- revoke PRO
        $sub     = $event['data']['object'];
        $user_id = intval( $sub['metadata']['user_id'] ?? 0 );
        if ( $user_id ) {
            update_user_meta( $user_id, 'fs_pro_active', '0' );
            $user = new WP_User( $user_id );
            $user->remove_role('fs_pro');
        }
    }

    status_header(200); echo 'ok'; exit;
}

/* ── 8. Admin: PRO Settings + Users ── */
add_action( 'admin_menu', 'fs_pro_admin_menu' );
function fs_pro_admin_menu() {
    add_menu_page(
        'FinanceSpots PRO',
        'FS PRO',
        'manage_options',
        'fs-pro',
        'fs_pro_admin_page',
        'dashicons-star-filled',
        3
    );
    add_submenu_page( 'fs-pro', 'PRO Settings', 'Settings',  'manage_options', 'fs-pro', 'fs_pro_admin_page' );
    add_submenu_page( 'fs-pro', 'PRO Users',    'PRO Users', 'manage_options', 'fs-pro-users', 'fs_pro_users_page' );
}

function fs_pro_admin_page() {
    if ( isset($_POST['fs_save_pro']) && check_admin_referer('fs_pro_settings') ) {
        $fields = [
            'fs_stripe_secret_key','fs_stripe_publishable_key','fs_stripe_webhook_secret',
            'fs_stripe_price_monthly','fs_stripe_price_yearly','fs_stripe_price_lifetime',
        ];
        foreach ( $fields as $f ) {
            update_option( $f, sanitize_text_field( $_POST[$f] ?? '' ) );
        }
        echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
    }

    $fields = [
        'fs_stripe_secret_key'        => ['Stripe Secret Key',         'sk_live_...'],
        'fs_stripe_publishable_key'   => ['Stripe Publishable Key',    'pk_live_...'],
        'fs_stripe_webhook_secret'    => ['Stripe Webhook Secret',     'whsec_...'],
        'fs_stripe_price_monthly'     => ['Price ID -- Monthly ($9)',   'price_...'],
        'fs_stripe_price_yearly'      => ['Price ID -- Yearly ($79)',   'price_...'],
        'fs_stripe_price_lifetime'    => ['Price ID -- Lifetime ($199)','price_...'],
    ];
    ?>
    <div class="wrap" style="font-family:-apple-system,sans-serif;max-width:700px;">
        <h1 style="font-size:1.5rem;margin-bottom:6px;">&#11088; FinanceSpots PRO -- Settings</h1>
        <p style="color:#64748B;margin-bottom:24px;">Connect Stripe to start accepting payments. <a href="https://dashboard.stripe.com" target="_blank">Open Stripe Dashboard &#x2192;</a></p>

        <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:10px;padding:14px 18px;margin-bottom:24px;font-size:.875rem;">
            <strong>Setup Steps:</strong><br>
            1. Create products in Stripe Dashboard (Monthly $9/mo, Yearly $79/yr, Lifetime $199)<br>
            2. Copy each Price ID (starts with <code>price_</code>) and paste below<br>
            3. Set Webhook URL in Stripe: <code><?php echo home_url('/?fs_webhook=stripe'); ?></code><br>
            4. Add Webhook events: <code>checkout.session.completed</code>, <code>customer.subscription.deleted</code>
        </div>

        <form method="post" style="background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:28px;">
            <?php wp_nonce_field('fs_pro_settings'); ?>
            <table style="width:100%;border-collapse:collapse;">
            <?php foreach($fields as $key => [$label,$placeholder]): $val = get_option($key,''); ?>
                <tr style="border-bottom:1px solid #F1F5F9;">
                    <td style="padding:14px 0 14px;width:240px;font-size:.875rem;font-weight:600;color:#374151;"><?php echo $label; ?></td>
                    <td style="padding:14px 0;">
                        <input type="<?php echo str_contains($key,'secret')?'password':'text'; ?>"
                               name="<?php echo $key; ?>"
                               value="<?php echo esc_attr($val); ?>"
                               placeholder="<?php echo $placeholder; ?>"
                               style="width:100%;padding:8px 12px;border:1.5px solid #E2E8F0;border-radius:8px;font-size:.875rem;font-family:monospace;">
                    </td>
                </tr>
            <?php endforeach; ?>
            </table>
            <button name="fs_save_pro" style="margin-top:20px;background:#10B981;color:#fff;border:none;padding:11px 24px;border-radius:8px;font-weight:700;font-size:.9rem;cursor:pointer;">Save Settings</button>
        </form>

        <!-- Manual PRO grant -->
        <div style="background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:28px;margin-top:20px;">
            <h3 style="margin:0 0 16px;font-size:1rem;">Grant PRO Manually</h3>
            <?php
            if ( isset($_POST['fs_grant_pro']) && check_admin_referer('fs_grant_pro') ) {
                $uid = email_exists( sanitize_email($_POST['grant_email']) );
                if ($uid) { fs_activate_pro($uid,'manual'); echo '<div class="notice notice-success" style="margin:0 0 12px;"><p>PRO granted!</p></div>'; }
                else echo '<div class="notice notice-error" style="margin:0 0 12px;"><p>User not found.</p></div>';
            }
            ?>
            <form method="post" style="display:flex;gap:10px;align-items:center;">
                <?php wp_nonce_field('fs_grant_pro'); ?>
                <input type="email" name="grant_email" placeholder="user@email.com" required style="padding:9px 14px;border:1.5px solid #E2E8F0;border-radius:8px;font-size:.875rem;flex:1;">
                <button name="fs_grant_pro" style="background:#6366F1;color:#fff;border:none;padding:9px 18px;border-radius:8px;font-weight:700;cursor:pointer;">Grant PRO</button>
            </form>
        </div>
    </div>
    <?php
}

function fs_pro_users_page() {
    global $wpdb;
    $pros = get_users(['meta_key'=>'fs_pro_active','meta_value'=>'1']);

    if ( isset($_GET['revoke']) && check_admin_referer('fs_revoke_pro') ) {
        $uid = intval($_GET['revoke']);
        update_user_meta($uid,'fs_pro_active','0');
        $u = new WP_User($uid); $u->remove_role('fs_pro');
        echo '<div class="notice notice-success"><p>PRO revoked.</p></div>';
        $pros = get_users(['meta_key'=>'fs_pro_active','meta_value'=>'1']);
    }
    ?>
    <div class="wrap" style="font-family:-apple-system,sans-serif;">
        <h1 style="font-size:1.5rem;margin-bottom:20px;">&#11088; PRO Users (<?php echo count($pros); ?>)</h1>
        <div style="background:#fff;border:1px solid #E2E8F0;border-radius:16px;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;font-size:.875rem;">
            <thead><tr style="background:#F8FAFC;border-bottom:2px solid #E2E8F0;">
                <th style="padding:14px 16px;text-align:left;color:#64748B;">User</th>
                <th style="padding:14px 16px;text-align:left;color:#64748B;">Email</th>
                <th style="padding:14px 16px;text-align:left;color:#64748B;">Plan</th>
                <th style="padding:14px 16px;text-align:left;color:#64748B;">Since</th>
                <th style="padding:14px 16px;text-align:left;color:#64748B;">Action</th>
            </tr></thead>
            <tbody>
            <?php if(empty($pros)): ?>
                <tr><td colspan="5" style="padding:40px;text-align:center;color:#94A3B8;">No PRO users yet.</td></tr>
            <?php else: foreach($pros as $i=>$u):
                $plan  = get_user_meta($u->ID,'fs_pro_plan',true) ?: 'manual';
                $since = get_user_meta($u->ID,'fs_pro_since',true);
            ?>
                <tr style="border-bottom:1px solid #F1F5F9;<?php echo $i%2?'background:#FAFBFC':''; ?>">
                    <td style="padding:12px 16px;font-weight:600;"><?php echo esc_html($u->display_name); ?></td>
                    <td style="padding:12px 16px;color:#3B82F6;"><?php echo esc_html($u->user_email); ?></td>
                    <td style="padding:12px 16px;">
                        <span style="background:#FEF3C7;color:#D97706;padding:3px 10px;border-radius:20px;font-size:.78rem;font-weight:700;text-transform:uppercase;"><?php echo esc_html($plan); ?></span>
                    </td>
                    <td style="padding:12px 16px;color:#64748B;"><?php echo $since ? date('M j, Y', strtotime($since)) : '--'; ?></td>
                    <td style="padding:12px 16px;">
                        <a href="<?php echo wp_nonce_url(add_query_arg(['revoke'=>$u->ID]),'fs_revoke_pro'); ?>"
                           onclick="return confirm('Revoke PRO for <?php echo esc_js($u->display_name); ?>?')"
                           style="background:#FEE2E2;color:#EF4444;padding:5px 12px;border-radius:6px;font-size:.8rem;text-decoration:none;">Revoke</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
        </div>
    </div>
    <?php
}

/* ── 9. Webhook URL via query string ── */
add_action( 'init', function() {
    if ( isset($_GET['fs_webhook']) && $_GET['fs_webhook'] === 'stripe' ) {
        do_action( 'wp_ajax_nopriv_fs_stripe_webhook' );
    }
});

/* ── 10. PRO badge shortcode ── */
add_shortcode( 'fs_pro_badge', function() {
    return fs_is_pro() ? '<span class="fs-pro-badge">&#11088; PRO</span>' : '';
});
