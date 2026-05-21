<?php
/**
 * FinanceSpots -- Trust Strip Admin Panel
 * Adds "FinanceSpots > Trust Strip" in WP Admin
 */
if ( ! defined('ABSPATH') ) exit;

add_action('admin_menu', function(){
    add_menu_page(
        'Trust Strip Settings',
        'FinanceSpots',
        'manage_options',
        'fs-trust-strip',
        'fs_trust_strip_page',
        'dashicons-awards',
        30
    );
    add_submenu_page(
        'fs-trust-strip',
        'Trust Strip',
        'Trust Strip',
        'manage_options',
        'fs-trust-strip',
        'fs_trust_strip_page'
    );
});

// Save settings
add_action('admin_init', function(){
    if( !isset($_POST['fs_trust_save']) ) return;
    if( !check_admin_referer('fs_trust_nonce') ) return;
    if( !current_user_can('manage_options') ) return;

    $ts = [];
    $ts['label']      = sanitize_text_field($_POST['ts_label'] ?? '');
    $ts['speed']      = max(5, min(120, (int)($_POST['ts_speed'] ?? 28)));
    $ts['pause']      = isset($_POST['ts_pause']) ? true : false;
    $ts['show_stats'] = isset($_POST['ts_show_stats']) ? true : false;
    $ts['accent']     = sanitize_hex_color($_POST['ts_accent'] ?? '#10B981') ?: '#10B981';
    $ts['bg']         = sanitize_text_field($_POST['ts_bg'] ?? 'rgba(255,255,255,0.03)');

    // Outlets
    $names  = $_POST['outlet_name']  ?? [];
    $icons  = $_POST['outlet_icon']  ?? [];
    $colors = $_POST['outlet_color'] ?? [];
    $ts['outlets'] = [];
    for($i=0; $i<count($names); $i++){
        $n = sanitize_text_field($names[$i] ?? '');
        if(!$n) continue;
        $ts['outlets'][] = [
            'name'  => $n,
            'icon'  => sanitize_text_field($icons[$i] ?? '?'),
            'color' => sanitize_hex_color($colors[$i] ?? '#10B981') ?: '#10B981',
        ];
    }

    // Stats
    $snums = $_POST['stat_num'] ?? [];
    $slbls = $_POST['stat_lbl'] ?? [];
    $ts['stats'] = [];
    for($i=0; $i<count($snums); $i++){
        $n = sanitize_text_field($snums[$i] ?? '');
        if(!$n) continue;
        $ts['stats'][] = [
            'num' => $n,
            'lbl' => sanitize_text_field($slbls[$i] ?? ''),
        ];
    }

    update_option('fs_trust_settings', $ts);
    wp_redirect( admin_url('admin.php?page=fs-trust-strip&saved=1') );
    exit;
});

function fs_trust_strip_page(){
    $ts = get_option('fs_trust_settings', []);
    $label      = $ts['label']      ?? 'AS SEEN & TRUSTED BY READERS FROM';
    $speed      = $ts['speed']      ?? 28;
    $pause      = $ts['pause']      ?? true;
    $show_stats = $ts['show_stats'] ?? true;
    $accent     = $ts['accent']     ?? '#10B981';
    $bg         = $ts['bg']         ?? 'rgba(255,255,255,0.03)';
    $outlets    = $ts['outlets']    ?? [
        ['name'=>'Forbes',              'icon'=>'F',  'color'=>'#EF4444'],
        ['name'=>'Bloomberg',           'icon'=>'B',  'color'=>'#F59E0B'],
        ['name'=>'TechCrunch',          'icon'=>'TC', 'color'=>'#10B981'],
        ['name'=>'Wall Street Journal', 'icon'=>'W',  'color'=>'#3B82F6'],
        ['name'=>'Business Insider',    'icon'=>'BI', 'color'=>'#8B5CF6'],
        ['name'=>'CNBC',                'icon'=>'C',  'color'=>'#F97316'],
        ['name'=>'Reuters',             'icon'=>'R',  'color'=>'#06B6D4'],
        ['name'=>'MarketWatch',         'icon'=>'MW', 'color'=>'#EC4899'],
    ];
    $stats = $ts['stats'] ?? [
        ['num'=>'50,000+', 'lbl'=>'Monthly Users'],
        ['num'=>'4.9&#9733;',    'lbl'=>'Average Rating'],
        ['num'=>'30+',     'lbl'=>'Free Tools'],
        ['num'=>'100%',    'lbl'=>'Free Forever'],
    ];
    ?>
    <style>
    .fs-admin{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;max-width:900px;padding:24px 20px;}
    .fs-admin h1{font-size:1.5rem;font-weight:800;color:#1e293b;margin-bottom:4px;display:flex;align-items:center;gap:10px;}
    .fs-admin .fs-a-sub{color:#64748b;font-size:.875rem;margin-bottom:28px;}
    .fs-a-saved{background:#dcfce7;border:1px solid #86efac;color:#166534;padding:12px 18px;border-radius:8px;margin-bottom:20px;font-weight:600;font-size:.9rem;}
    .fs-a-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;margin-bottom:20px;box-shadow:0 1px 4px rgba(0,0,0,.05);}
    .fs-a-card h2{font-size:1rem;font-weight:700;color:#0f172a;margin:0 0 18px;padding-bottom:12px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px;}
    .fs-a-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;}
    .fs-a-field{display:flex;flex-direction:column;gap:6px;margin-bottom:16px;}
    .fs-a-field label{font-size:.8rem;font-weight:600;color:#475569;letter-spacing:.03em;}
    .fs-a-field input[type=text],.fs-a-field input[type=number],.fs-a-field input[type=color]{border:1px solid #cbd5e1;border-radius:8px;padding:9px 12px;font-size:.9rem;color:#0f172a;width:100%;box-sizing:border-box;outline:none;transition:border-color .2s;}
    .fs-a-field input:focus{border-color:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,.12);}
    .fs-a-field input[type=range]{padding:4px 0;accent-color:#10b981;}
    .fs-a-field input[type=color]{height:42px;padding:3px 6px;cursor:pointer;}
    .fs-a-toggle{display:flex;align-items:center;gap:10px;font-size:.9rem;color:#374151;}
    .fs-a-toggle input{width:18px;height:18px;accent-color:#10b981;cursor:pointer;}
    .fs-a-speed-val{display:inline-block;background:#f1f5f9;border-radius:6px;padding:2px 10px;font-weight:700;color:#0f172a;font-size:.85rem;margin-left:6px;}
    /* Outlets table */
    .fs-a-table{width:100%;border-collapse:collapse;font-size:.875rem;}
    .fs-a-table th{text-align:left;padding:8px 10px;background:#f8fafc;border-bottom:2px solid #e2e8f0;font-size:.75rem;color:#64748b;font-weight:700;letter-spacing:.05em;}
    .fs-a-table td{padding:8px 10px;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
    .fs-a-table input[type=text]{border:1px solid #e2e8f0;border-radius:6px;padding:7px 10px;font-size:.85rem;width:100%;box-sizing:border-box;}
    .fs-a-table input[type=color]{height:36px;padding:2px 4px;border-radius:6px;width:52px;cursor:pointer;}
    .fs-a-table .del-row{background:#fef2f2;color:#ef4444;border:none;border-radius:6px;padding:5px 10px;cursor:pointer;font-size:.8rem;font-weight:600;}
    .fs-a-table .del-row:hover{background:#fee2e2;}
    .fs-a-add-btn{background:#f0fdf4;color:#16a34a;border:1px solid #86efac;border-radius:8px;padding:8px 16px;cursor:pointer;font-size:.85rem;font-weight:600;margin-top:10px;transition:background .2s;}
    .fs-a-add-btn:hover{background:#dcfce7;}
    .fs-a-save{background:linear-gradient(135deg,#10b981,#0d9e6f);color:#fff;border:none;border-radius:10px;padding:13px 32px;font-size:1rem;font-weight:700;cursor:pointer;box-shadow:0 4px 14px rgba(16,185,129,.25);transition:all .2s;}
    .fs-a-save:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(16,185,129,.35);}
    .fs-a-preview{background:#0A0F1E;border-radius:12px;padding:24px;margin-top:16px;overflow:hidden;position:relative;}
    .fs-a-preview-track{display:flex;gap:0;width:max-content;animation:adm-scroll 20s linear infinite;}
    .fs-a-preview-track:hover{animation-play-state:paused;}
    @keyframes adm-scroll{from{transform:translateX(0)}to{transform:translateX(-50%)}}
    .fs-a-preview-item{display:flex;align-items:center;gap:12px;padding:10px 28px;border-right:1px solid rgba(255,255,255,.05);}
    .fs-a-preview-badge{width:36px;height:36px;border-radius:9px;border:1px solid;display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:900;flex-shrink:0;}
    .fs-a-preview-name{font-size:.95rem;font-weight:700;color:#94a3b8;white-space:nowrap;}
    </style>

    <div class="fs-admin">
        <h1>&#127942; Trust Strip Settings</h1>
        <p class="fs-a-sub">Control everything about the "As Featured In" scrolling strip on your homepage.</p>

        <?php if(isset($_GET['saved'])): ?>
        <div class="fs-a-saved">&#9989; Settings saved! <a href="<?php echo esc_url(home_url('/')); ?>" target="_blank">View Homepage &#x2192;</a></div>
        <?php endif; ?>

        <form method="post">
            <?php wp_nonce_field('fs_trust_nonce'); ?>

            <!-- General Settings -->
            <div class="fs-a-card">
                <h2>&#9881;&#65039; General Settings</h2>
                <div class="fs-a-field">
                    <label>Section Label Text</label>
                    <input type="text" name="ts_label" value="<?php echo esc_attr($label); ?>" placeholder="AS SEEN & TRUSTED BY READERS FROM">
                </div>
                <div class="fs-a-row">
                    <div class="fs-a-field">
                        <label>Scroll Speed (seconds per loop) -- Lower = Faster <span class="fs-a-speed-val" id="speed-val"><?php echo $speed; ?>s</span></label>
                        <input type="range" name="ts_speed" min="5" max="120" value="<?php echo $speed; ?>" oninput="document.getElementById('speed-val').textContent=this.value+'s'">
                    </div>
                    <div class="fs-a-field">
                        <label>Stats Accent Color</label>
                        <input type="color" name="ts_accent" value="<?php echo esc_attr($accent); ?>">
                    </div>
                </div>
                <div style="display:flex;gap:28px;flex-wrap:wrap;">
                    <label class="fs-a-toggle"><input type="checkbox" name="ts_pause" <?php checked($pause); ?>> Pause scroll on hover</label>
                    <label class="fs-a-toggle"><input type="checkbox" name="ts_show_stats" <?php checked($show_stats); ?>> Show stats bar below</label>
                </div>
            </div>

            <!-- Media Outlets -->
            <div class="fs-a-card">
                <h2>&#128240; Media Outlets (scrolling logos)</h2>
                <p style="font-size:.83rem;color:#64748b;margin:-8px 0 14px;">Add, remove, or edit any outlet. Each needs a Name, short Icon text (1-3 chars), and a color.</p>
                <table class="fs-a-table" id="outlets-table">
                    <thead><tr><th>#</th><th>Name</th><th>Icon (short)</th><th>Color</th><th>Preview</th><th></th></tr></thead>
                    <tbody id="outlets-body">
                    <?php foreach($outlets as $i=>$o): ?>
                    <tr>
                        <td style="color:#94a3b8;font-weight:700;"><?php echo $i+1; ?></td>
                        <td><input type="text" name="outlet_name[]" value="<?php echo esc_attr($o['name']); ?>" placeholder="Forbes"></td>
                        <td><input type="text" name="outlet_icon[]" value="<?php echo esc_attr($o['icon']); ?>" placeholder="F" style="width:70px"></td>
                        <td><input type="color" name="outlet_color[]" value="<?php echo esc_attr($o['color']); ?>"></td>
                        <td><span style="background:<?php echo esc_attr($o['color']); ?>20;border:1px solid <?php echo esc_attr($o['color']); ?>50;color:<?php echo esc_attr($o['color']); ?>;padding:4px 12px;border-radius:6px;font-size:.75rem;font-weight:800;"><?php echo esc_html($o['icon']); ?> <?php echo esc_html($o['name']); ?></span></td>
                        <td><button type="button" class="del-row" onclick="this.closest('tr').remove()">&#10005; Remove</button></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="button" class="fs-a-add-btn" onclick="fsAddOutlet()">+ Add New Outlet</button>

                <!-- Live Preview -->
                <p style="font-size:.78rem;color:#94a3b8;margin:20px 0 8px;font-weight:600;">LIVE PREVIEW (save to apply changes)</p>
                <div class="fs-a-preview">
                    <div class="fs-a-preview-track">
                        <?php for($lp=0;$lp<2;$lp++): foreach($outlets as $o): ?>
                        <div class="fs-a-preview-item">
                            <span class="fs-a-preview-badge" style="background:<?php echo $o['color']; ?>20;border-color:<?php echo $o['color']; ?>50;color:<?php echo $o['color']; ?>"><?php echo esc_html($o['icon']); ?></span>
                            <span class="fs-a-preview-name"><?php echo esc_html($o['name']); ?></span>
                        </div>
                        <?php endforeach; endfor; ?>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="fs-a-card">
                <h2>&#128202; Stats Bar (shown below the strip)</h2>
                <table class="fs-a-table" id="stats-table">
                    <thead><tr><th>#</th><th>Number / Value</th><th>Label</th><th></th></tr></thead>
                    <tbody id="stats-body">
                    <?php foreach($stats as $i=>$st): ?>
                    <tr>
                        <td style="color:#94a3b8;font-weight:700;"><?php echo $i+1; ?></td>
                        <td><input type="text" name="stat_num[]" value="<?php echo esc_attr($st['num']); ?>" placeholder="50,000+"></td>
                        <td><input type="text" name="stat_lbl[]" value="<?php echo esc_attr($st['lbl']); ?>" placeholder="Monthly Users"></td>
                        <td><button type="button" class="del-row" onclick="this.closest('tr').remove()">&#10005; Remove</button></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="button" class="fs-a-add-btn" onclick="fsAddStat()">+ Add Stat</button>
            </div>

            <input type="submit" name="fs_trust_save" class="fs-a-save" value="&#128190; Save All Changes">
        </form>
    </div>

    <script>
    var outletCount = <?php echo count($outlets); ?>;
    var statCount   = <?php echo count($stats); ?>;

    function fsAddOutlet(){
        outletCount++;
        var colors = ['#EF4444','#F59E0B','#10B981','#3B82F6','#8B5CF6','#F97316','#06B6D4','#EC4899'];
        var c = colors[outletCount % colors.length];
        var tr = document.createElement('tr');
        tr.innerHTML = '<td style="color:#94a3b8;font-weight:700;">'+outletCount+'</td>'
            +'<td><input type="text" name="outlet_name[]" placeholder="New Outlet"></td>'
            +'<td><input type="text" name="outlet_icon[]" placeholder="N" style="width:70px"></td>'
            +'<td><input type="color" name="outlet_color[]" value="'+c+'"></td>'
            +'<td><span style="color:#94a3b8;font-size:.8rem">Fill in name first</span></td>'
            +'<td><button type="button" class="del-row" onclick="this.closest(\'tr\').remove()">&#10005; Remove</button></td>';
        document.getElementById('outlets-body').appendChild(tr);
    }

    function fsAddStat(){
        statCount++;
        var tr = document.createElement('tr');
        tr.innerHTML = '<td style="color:#94a3b8;font-weight:700;">'+statCount+'</td>'
            +'<td><input type="text" name="stat_num[]" placeholder="e.g. 10,000+"></td>'
            +'<td><input type="text" name="stat_lbl[]" placeholder="e.g. Happy Users"></td>'
            +'<td><button type="button" class="del-row" onclick="this.closest(\'tr\').remove()">&#10005; Remove</button></td>';
        document.getElementById('stats-body').appendChild(tr);
    }
    </script>
    <?php
}
