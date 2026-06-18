<?php /** Template Name: All Tools */ get_header(); ?>

<div style="background:#0A0F1E;min-height:100vh;">

    <!-- Hero -->
    <section style="padding:70px 0 50px;text-align:center;border-bottom:1px solid rgba(255,255,255,.06);">
        <div class="container">
            <span style="display:inline-block;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);color:#10B981;padding:6px 18px;border-radius:50px;font-size:.82rem;font-weight:700;margin-bottom:16px;">&#129518; Finance Tools</span>
            <h1 style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;color:#fff;margin:0 0 14px;">All <?php echo wp_count_posts('fs_tool')->publish; ?>+ Free Tools</h1>
            <p style="color:#94A3B8;font-size:1rem;max-width:500px;margin:0 auto 28px;">Every calculator you need -- mortgage, investing, taxes, retirement, budgeting and more. 100% free, no account needed.</p>
            <!-- Search -->
            <div style="max-width:440px;margin:0 auto;position:relative;">
                <input type="text" id="fs-tool-search" placeholder="Search tools..." style="width:100%;background:#131929;border:1.5px solid rgba(255,255,255,.1);border-radius:50px;padding:14px 20px 14px 48px;color:#F1F5F9;font-size:.95rem;outline:none;box-sizing:border-box;" onfocus="this.style.borderColor='#10B981'" onblur="this.style.borderColor='rgba(255,255,255,.1)'">
                <svg style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#64748B;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            </div>
        </div>
    </section>

    <!-- Category Nav Links -->
    <nav aria-label="Tool categories" style="background:#0D1424;border-bottom:1px solid rgba(255,255,255,.06);padding:14px 0;">
        <div class="container">
            <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
                <span style="font-size:.75rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.07em;margin-right:4px;">Categories:</span>
                <a href="<?php echo esc_url(home_url('/categories/')); ?>" style="display:inline-flex;align-items:center;gap:5px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);border-radius:20px;padding:5px 12px;font-size:.8rem;font-weight:600;color:#10B981;text-decoration:none;">&#128200; All Categories</a>
                <a href="<?php echo esc_url(home_url('/tool/mortgage-calculator/')); ?>" style="display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:5px 12px;font-size:.8rem;font-weight:600;color:#94A3B8;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94A3B8'">&#127968; Mortgage</a>
                <a href="<?php echo esc_url(home_url('/tool/compound-interest-calculator/')); ?>" style="display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:5px 12px;font-size:.8rem;font-weight:600;color:#94A3B8;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94A3B8'">&#128200; Investing</a>
                <a href="<?php echo esc_url(home_url('/tool/income-tax-calculator/')); ?>" style="display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:5px 12px;font-size:.8rem;font-weight:600;color:#94A3B8;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94A3B8'">&#129534; Taxes</a>
                <a href="<?php echo esc_url(home_url('/tool/retirement-income-calculator/')); ?>" style="display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:5px 12px;font-size:.8rem;font-weight:600;color:#94A3B8;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94A3B8'">&#127958;&#65039; Retirement</a>
                <a href="<?php echo esc_url(home_url('/tool/monthly-budget-planner/')); ?>" style="display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:5px 12px;font-size:.8rem;font-weight:600;color:#94A3B8;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94A3B8'">&#128203; Budgeting</a>
                <a href="<?php echo esc_url(home_url('/tool/crypto-pl-calculator/')); ?>" style="display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:5px 12px;font-size:.8rem;font-weight:600;color:#94A3B8;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94A3B8'">&#8383; Crypto</a>
            </div>
        </div>
    </nav>

    <!-- Tools by category -->
    <section style="padding:50px 0 80px;">
    <div class="container">
    <?php
    $cats = get_terms(['taxonomy'=>'fs_tool_cat','hide_empty'=>true,'orderby'=>'count','order'=>'DESC']);
    $cat_icons = [
        'mortgage-calculators'   => ['&#127968;','Mortgage'],
        'loan-calculators'       => ['&#127974;','Loans'],
        'investment-calculators' => ['&#128200;','Investing'],
        'retirement-calculators' => ['&#127958;&#65039;','Retirement'],
        'budget-calculators'     => ['&#128176;','Budgeting'],
        'tax-calculators'        => ['&#129534;','Taxes'],
        'savings-calculators'    => ['&#128181;','Savings'],
        'crypto-calculators'     => ['₿','Crypto'],
        'currency-tools'         => ['&#127757;','Currency'],
        'debt-calculators'       => ['&#128202;','Debt'],
    ];
    foreach($cats as $cat):
        $icon_data = $cat_icons[$cat->slug] ?? ['&#129518;', $cat->name];
        $tools = get_posts(['post_type'=>'fs_tool','post_status'=>'publish','posts_per_page'=>-1,'tax_query'=>[['taxonomy'=>'fs_tool_cat','field'=>'term_id','terms'=>$cat->term_id]],'orderby'=>'title','order'=>'ASC']);
        if(empty($tools)) continue;
    ?>
    <div class="fs-tools-cat-block" style="margin-bottom:48px;" data-cat="<?php echo esc_attr($cat->slug); ?>">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
            <div style="width:40px;height:40px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;"><?php echo $icon_data[0]; ?></div>
            <div>
                <h2 style="font-size:1.15rem;font-weight:800;color:#fff;margin:0;"><?php echo esc_html($cat->name); ?></h2>
                <span style="font-size:.78rem;color:#64748B;"><?php echo count($tools); ?> tools</span>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:12px;">
        <?php foreach($tools as $tool):
            $is_pro = get_post_meta($tool->ID,'fs_is_pro_tool',true) === '1';
        ?>
        <a href="<?php echo esc_url(get_permalink($tool->ID)); ?>" class="fs-tool-card" style="display:flex;align-items:center;justify-content:space-between;gap:10px;background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:14px 16px;text-decoration:none;transition:all .2s;" onmouseover="this.style.borderColor='rgba(16,185,129,.35)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)';this.style.transform='translateY(0)'">
            <span style="font-size:.875rem;font-weight:600;color:#CBD5E1;" class="fs-tool-name"><?php echo esc_html($tool->post_title); ?></span>
            <?php if($is_pro): ?>
            <span style="background:linear-gradient(135deg,rgba(245,158,11,.2),rgba(16,185,129,.1));border:1px solid rgba(245,158,11,.3);color:#F59E0B;padding:2px 8px;border-radius:20px;font-size:.65rem;font-weight:800;white-space:nowrap;flex-shrink:0;">PRO</span>
            <?php else: ?>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" style="flex-shrink:0;"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <div id="fs-no-results" style="display:none;text-align:center;padding:60px 20px;color:#64748B;">
        <div style="font-size:3rem;margin-bottom:12px;">&#128269;</div>
        <p style="font-size:1rem;">No tools found. Try a different search.</p>
    </div>
    </div>
    </section>
</div>

<script>
document.getElementById('fs-tool-search').addEventListener('input', function(){
    var q = this.value.toLowerCase().trim();
    var blocks = document.querySelectorAll('.fs-tools-cat-block');
    var totalVisible = 0;
    blocks.forEach(function(block){
        var cards = block.querySelectorAll('.fs-tool-card');
        var blockVisible = 0;
        cards.forEach(function(card){
            var name = card.querySelector('.fs-tool-name').textContent.toLowerCase();
            var show = !q || name.includes(q);
            card.style.display = show ? '' : 'none';
            if(show) blockVisible++;
        });
        block.style.display = blockVisible ? '' : 'none';
        totalVisible += blockVisible;
    });
    document.getElementById('fs-no-results').style.display = totalVisible ? 'none' : 'block';
});
</script>

<?php get_footer(); ?>
