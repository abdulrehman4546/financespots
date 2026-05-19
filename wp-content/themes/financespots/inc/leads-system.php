<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* =========================================================
   FinanceSpots Leads System
   - Custom AJAX registration
   - Leads stored in wp_fs_leads table
   - Admin panel to view/export leads
   ========================================================= */

/* ── 1. Create DB table on theme activation ── */
function fs_leads_create_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'fs_leads';
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id         BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        name       VARCHAR(100) NOT NULL,
        email      VARCHAR(200) NOT NULL,
        source     VARCHAR(100) DEFAULT 'register_modal',
        status     VARCHAR(20)  DEFAULT 'new',
        wp_user_id BIGINT(20) UNSIGNED DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY email (email)
    ) $charset;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}
add_action( 'after_switch_theme', 'fs_leads_create_table' );
add_action( 'init', function(){
    if ( get_option('fs_leads_table_created') !== '1' ) {
        fs_leads_create_table();
        update_option( 'fs_leads_table_created', '1' );
    }
});

/* ── 2. AJAX: Custom Registration ── */
add_action( 'wp_ajax_nopriv_fs_register', 'fs_ajax_register' );
add_action( 'wp_ajax_fs_register',        'fs_ajax_register' );

function fs_ajax_register() {
    check_ajax_referer( 'fs_auth_nonce', 'nonce' );

    $name     = sanitize_text_field( wp_unslash( $_POST['name']     ?? '' ) );
    $email    = sanitize_email(      wp_unslash( $_POST['email']    ?? '' ) );
    $password = wp_unslash( $_POST['password'] ?? '' );

    if ( ! $name || ! $email || ! $password ) {
        wp_send_json_error( 'Please fill in all fields.' );
    }
    if ( ! is_email( $email ) ) {
        wp_send_json_error( 'Please enter a valid email address.' );
    }
    if ( strlen( $password ) < 6 ) {
        wp_send_json_error( 'Password must be at least 6 characters.' );
    }
    if ( email_exists( $email ) ) {
        wp_send_json_error( 'This email is already registered. Please sign in.' );
    }

    // Generate unique username from email
    $username = sanitize_user( strstr( $email, '@', true ), true );
    $base     = $username;
    $i        = 1;
    while ( username_exists( $username ) ) {
        $username = $base . $i++;
    }

    // Create WP user
    $user_id = wp_create_user( $username, $password, $email );
    if ( is_wp_error( $user_id ) ) {
        wp_send_json_error( $user_id->get_error_message() );
    }

    // Save display name
    wp_update_user( [ 'ID' => $user_id, 'display_name' => $name, 'first_name' => $name ] );

    // Save lead in custom table
    global $wpdb;
    $wpdb->replace(
        $wpdb->prefix . 'fs_leads',
        [
            'name'       => $name,
            'email'      => $email,
            'source'     => 'register_modal',
            'status'     => 'new',
            'wp_user_id' => $user_id,
            'created_at' => current_time( 'mysql' ),
        ],
        [ '%s', '%s', '%s', '%s', '%d', '%s' ]
    );

    // Auto-login
    wp_set_current_user( $user_id );
    wp_set_auth_cookie( $user_id, true );

    wp_send_json_success( [ 'redirect' => home_url( '/' ) ] );
}

/* ── 3. AJAX: Login ── */
add_action( 'wp_ajax_nopriv_fs_login', 'fs_ajax_login' );

function fs_ajax_login() {
    check_ajax_referer( 'fs_auth_nonce', 'nonce' );

    $email    = sanitize_email( wp_unslash( $_POST['email']    ?? '' ) );
    $password = wp_unslash( $_POST['password'] ?? '' );
    $remember = ! empty( $_POST['remember'] );

    if ( ! $email || ! $password ) {
        wp_send_json_error( 'Please enter your email and password.' );
    }

    // Find user by email
    $user = get_user_by( 'email', $email );
    if ( ! $user ) {
        // Try by login
        $user = get_user_by( 'login', $email );
    }
    if ( ! $user ) {
        wp_send_json_error( 'No account found with that email address.' );
    }

    $result = wp_signon([
        'user_login'    => $user->user_login,
        'user_password' => $password,
        'remember'      => $remember,
    ], false );

    if ( is_wp_error( $result ) ) {
        wp_send_json_error( 'Incorrect password. Please try again.' );
    }

    wp_send_json_success( [ 'redirect' => home_url( '/' ) ] );
}

/* ── 4. Admin Menu: Leads ── */
add_action( 'admin_menu', 'fs_leads_admin_menu' );

function fs_leads_admin_menu() {
    add_menu_page(
        'FinanceSpots Leads',
        'FS Leads',
        'manage_options',
        'fs-leads',
        'fs_leads_admin_page',
        'dashicons-groups',
        4
    );
}

function fs_leads_admin_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'fs_leads';

    // Handle status update
    if ( isset( $_POST['fs_update_lead'] ) && check_admin_referer('fs_leads_action') ) {
        $id     = intval( $_POST['lead_id'] );
        $status = sanitize_text_field( $_POST['lead_status'] );
        $wpdb->update( $table, ['status' => $status], ['id' => $id], ['%s'], ['%d'] );
        echo '<div class="notice notice-success"><p>Lead updated.</p></div>';
    }

    // Handle delete
    if ( isset( $_GET['delete_lead'] ) && check_admin_referer('fs_delete_lead') ) {
        $id = intval( $_GET['delete_lead'] );
        $wpdb->delete( $table, ['id' => $id], ['%d'] );
        echo '<div class="notice notice-success"><p>Lead deleted.</p></div>';
    }

    // Stats
    $total  = $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
    $new    = $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status='new'" );
    $today  = $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE DATE(created_at)=CURDATE()" );
    $leads  = $wpdb->get_results( "SELECT * FROM $table ORDER BY created_at DESC" );

    $status_colors = [
        'new'        => '#10B981',
        'contacted'  => '#3B82F6',
        'converted'  => '#F59E0B',
        'spam'       => '#EF4444',
    ];
    ?>
    <div class="wrap" style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
        <h1 style="color:#1E293B;font-size:1.6rem;margin-bottom:24px;">📋 FinanceSpots — Leads</h1>

        <!-- Stats -->
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px;max-width:600px;">
            <?php foreach([
                ['Total Leads',    $total, '#6366F1'],
                ['New / Unread',   $new,   '#10B981'],
                ['Joined Today',   $today, '#F59E0B'],
            ] as [$label,$val,$color]): ?>
            <div style="background:#fff;border:1px solid #E2E8F0;border-radius:12px;padding:18px 20px;border-top:3px solid <?php echo $color;?>;">
                <div style="font-size:1.8rem;font-weight:900;color:<?php echo $color;?>"><?php echo intval($val);?></div>
                <div style="font-size:.8rem;color:#64748B;margin-top:4px;"><?php echo $label;?></div>
            </div>
            <?php endforeach;?>
        </div>

        <!-- Export CSV -->
        <form method="post" action="<?php echo admin_url('admin-post.php');?>" style="margin-bottom:16px;">
            <?php wp_nonce_field('fs_export_leads');?>
            <input type="hidden" name="action" value="fs_export_leads_csv">
            <button type="submit" style="background:#10B981;color:#fff;border:none;padding:9px 18px;border-radius:8px;font-weight:700;cursor:pointer;font-size:.875rem;">⬇ Export CSV</button>
        </form>

        <!-- Table -->
        <div style="background:#fff;border:1px solid #E2E8F0;border-radius:16px;overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;font-size:.875rem;">
                <thead>
                    <tr style="background:#F8FAFC;border-bottom:2px solid #E2E8F0;">
                        <th style="padding:14px 16px;text-align:left;color:#64748B;font-weight:700;">#</th>
                        <th style="padding:14px 16px;text-align:left;color:#64748B;font-weight:700;">Name</th>
                        <th style="padding:14px 16px;text-align:left;color:#64748B;font-weight:700;">Email</th>
                        <th style="padding:14px 16px;text-align:left;color:#64748B;font-weight:700;">Source</th>
                        <th style="padding:14px 16px;text-align:left;color:#64748B;font-weight:700;">Status</th>
                        <th style="padding:14px 16px;text-align:left;color:#64748B;font-weight:700;">Date</th>
                        <th style="padding:14px 16px;text-align:left;color:#64748B;font-weight:700;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ( empty($leads) ): ?>
                    <tr><td colspan="7" style="padding:40px;text-align:center;color:#94A3B8;">No leads yet. Share your site to collect leads!</td></tr>
                <?php else: ?>
                <?php foreach ( $leads as $i => $lead ):
                    $sc = $status_colors[ $lead->status ] ?? '#94A3B8';
                ?>
                    <tr style="border-bottom:1px solid #F1F5F9;<?php echo ($i%2===0)?'background:#fff':'background:#FAFBFC';?>">
                        <td style="padding:12px 16px;color:#94A3B8;"><?php echo $lead->id;?></td>
                        <td style="padding:12px 16px;font-weight:600;color:#1E293B;"><?php echo esc_html($lead->name);?></td>
                        <td style="padding:12px 16px;color:#3B82F6;"><a href="mailto:<?php echo esc_attr($lead->email);?>"><?php echo esc_html($lead->email);?></a></td>
                        <td style="padding:12px 16px;color:#64748B;"><?php echo esc_html($lead->source);?></td>
                        <td style="padding:12px 16px;">
                            <span style="background:<?php echo $sc;?>20;color:<?php echo $sc;?>;border:1px solid <?php echo $sc;?>40;padding:3px 10px;border-radius:20px;font-size:.78rem;font-weight:700;text-transform:uppercase;">
                                <?php echo esc_html($lead->status);?>
                            </span>
                        </td>
                        <td style="padding:12px 16px;color:#64748B;"><?php echo date('M j, Y', strtotime($lead->created_at));?></td>
                        <td style="padding:12px 16px;">
                            <form method="post" style="display:inline-flex;gap:6px;align-items:center;">
                                <?php wp_nonce_field('fs_leads_action');?>
                                <input type="hidden" name="lead_id" value="<?php echo $lead->id;?>">
                                <select name="lead_status" style="padding:4px 8px;border:1px solid #E2E8F0;border-radius:6px;font-size:.8rem;">
                                    <?php foreach(['new','contacted','converted','spam'] as $s):?>
                                    <option value="<?php echo $s;?>" <?php selected($lead->status,$s);?>><?php echo ucfirst($s);?></option>
                                    <?php endforeach;?>
                                </select>
                                <button name="fs_update_lead" style="background:#3B82F6;color:#fff;border:none;padding:5px 12px;border-radius:6px;cursor:pointer;font-size:.8rem;">Save</button>
                                <a href="<?php echo wp_nonce_url(add_query_arg(['delete_lead'=>$lead->id]),  'fs_delete_lead');?>" onclick="return confirm('Delete this lead?')" style="background:#FEE2E2;color:#EF4444;padding:5px 10px;border-radius:6px;font-size:.8rem;text-decoration:none;">✕</a>
                            </form>
                        </td>
                    </tr>
                <?php endforeach;?>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}

/* ── 5. CSV Export ── */
add_action( 'admin_post_fs_export_leads_csv', 'fs_export_leads_csv' );
function fs_export_leads_csv() {
    if ( ! current_user_can('manage_options') ) wp_die('No access');
    check_admin_referer('fs_export_leads');
    global $wpdb;
    $leads = $wpdb->get_results( "SELECT name,email,source,status,created_at FROM {$wpdb->prefix}fs_leads ORDER BY created_at DESC", ARRAY_A );
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=financespots-leads-' . date('Y-m-d') . '.csv');
    $out = fopen('php://output','w');
    fputcsv($out, ['Name','Email','Source','Status','Date']);
    foreach($leads as $row) fputcsv($out, $row);
    fclose($out);
    exit;
}
