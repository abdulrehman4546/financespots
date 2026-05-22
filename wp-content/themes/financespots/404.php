<?php get_header(); ?>
<div style="background:#0A0F1E;min-height:80vh;display:flex;align-items:center;justify-content:center;padding:40px 20px;text-align:center;">
    <div style="max-width:480px;">
        <div style="font-size:5rem;font-weight:900;color:#10B981;line-height:1;margin-bottom:8px;">404</div>
        <h1 style="font-size:1.8rem;font-weight:900;color:#fff;margin:0 0 14px;">Page Not Found</h1>
        <p style="color:#64748B;font-size:.95rem;line-height:1.7;margin-bottom:32px;">The page you are looking for does not exist or has been moved. Try searching for a tool or return to the homepage.</p>
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" style="margin-bottom:20px;display:flex;max-width:380px;margin:0 auto 20px;">
            <input type="search" name="s" placeholder="Search tools..." style="flex:1;background:#131929;border:1.5px solid rgba(255,255,255,.1);border-right:none;border-radius:12px 0 0 12px;padding:12px 16px;color:#F1F5F9;font-size:.9rem;outline:none;">
            <button type="submit" style="background:#10B981;border:none;border-radius:0 12px 12px 0;padding:12px 18px;color:#fff;cursor:pointer;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            </button>
        </form>
        <a href="<?php echo esc_url(home_url('/')); ?>" style="display:inline-block;background:rgba(255,255,255,.06);color:#CBD5E1;padding:11px 24px;border-radius:10px;text-decoration:none;font-size:.875rem;font-weight:600;margin-top:8px;">&#x2190; Back to Homepage</a>
    </div>
</div>
<?php get_footer(); ?>
