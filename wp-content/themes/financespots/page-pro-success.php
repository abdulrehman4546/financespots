<?php /** Template Name: PRO Success */ get_header(); ?>
<div style="background:#0A0F1E;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px 20px;">
<div style="text-align:center;max-width:520px;">
    <div style="font-size:4rem;margin-bottom:20px;animation:fsBounce .6s ease;">🎉</div>
    <div style="display:inline-block;background:linear-gradient(135deg,rgba(245,158,11,.15),rgba(16,185,129,.1));border:1px solid rgba(245,158,11,.3);color:#F59E0B;padding:6px 20px;border-radius:50px;font-size:.82rem;font-weight:800;text-transform:uppercase;margin-bottom:20px;">⭐ Welcome to PRO</div>
    <h1 style="font-size:2.2rem;font-weight:900;color:#fff;margin:0 0 16px;">You're now PRO!</h1>
    <p style="color:#94A3B8;font-size:1rem;line-height:1.7;margin-bottom:32px;">Your account has been upgraded successfully. All PRO features are now unlocked. Thank you for supporting FinanceSpots!</p>

    <div style="background:#131929;border:1px solid rgba(16,185,129,.2);border-radius:20px;padding:28px;margin-bottom:32px;text-align:left;">
        <h3 style="color:#10B981;font-size:1rem;font-weight:800;margin:0 0 16px;">✅ Features Unlocked:</h3>
        <?php foreach(fs_pro_features() as $f): ?>
        <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.05);color:#CBD5E1;font-size:.875rem;">
            <span style="color:#10B981;font-weight:800;">✓</span> <?php echo esc_html($f); ?>
        </div>
        <?php endforeach; ?>
    </div>

    <a href="<?php echo esc_url(home_url('/')); ?>" style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#10B981,#059669);color:#fff;padding:15px 32px;border-radius:14px;font-size:1rem;font-weight:800;text-decoration:none;box-shadow:0 6px 24px rgba(16,185,129,.4);">
        Explore PRO Tools →
    </a>
</div>
</div>
<style>
@keyframes fsBounce{0%,100%{transform:translateY(0);}50%{transform:translateY(-12px);}}
</style>
<?php get_footer(); ?>
