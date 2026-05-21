<?php
/**
 * Template Name: Pricing
 */
get_header();

$plans   = fs_get_plans();
$features = fs_pro_features();
$is_pro  = fs_is_pro();
?>

<div class="fsp-page">

    <!-- Hero -->
    <section class="fsp-hero">
        <div class="fsp-hero__bg" aria-hidden="true">
            <div class="fsp-hero__orb fsp-hero__orb--1"></div>
            <div class="fsp-hero__orb fsp-hero__orb--2"></div>
        </div>
        <div class="container" style="position:relative;z-index:2;text-align:center;">
            <span class="fsp-badge">&#11088; Upgrade to PRO</span>
            <h1 class="fsp-hero__title">Simple, Transparent Pricing</h1>
            <p class="fsp-hero__sub">Start free forever. Upgrade when you need more power.</p>

            <!-- Toggle monthly/yearly -->
            <div class="fsp-toggle" id="fsp-toggle">
                <button class="fsp-toggle__btn active" data-period="monthly">Monthly</button>
                <button class="fsp-toggle__btn" data-period="yearly">
                    Yearly
                    <span class="fsp-toggle__save">Save 27%</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Plans -->
    <section class="fsp-plans-section">
        <div class="container">

            <?php if ( $is_pro ) : ?>
            <div class="fsp-already-pro">
                <span>&#11088;</span>
                <div>
                    <strong>You are already on PRO!</strong>
                    <span>Enjoy all premium features. Thank you for supporting FinanceSpots.</span>
                </div>
            </div>
            <?php endif; ?>

            <div class="fsp-plans">

                <!-- FREE -->
                <div class="fsp-plan">
                    <div class="fsp-plan__header">
                        <div class="fsp-plan__icon">&#127379;</div>
                        <h2 class="fsp-plan__name">Free</h2>
                        <div class="fsp-plan__price">
                            <span class="fsp-plan__amount">$0</span>
                            <span class="fsp-plan__period">forever</span>
                        </div>
                        <p class="fsp-plan__desc">All core calculators, no credit card needed.</p>
                    </div>
                    <ul class="fsp-plan__features">
                        <li class="yes">30+ finance calculators</li>
                        <li class="yes">All tools free to use</li>
                        <li class="yes">Mobile friendly</li>
                        <li class="yes">PDF export (basic)</li>
                        <li class="no">Save calculations</li>
                        <li class="no">Calculation history</li>
                        <li class="no">Advanced scenarios</li>
                        <li class="no">Ad-free experience</li>
                        <li class="no">Priority support</li>
                    </ul>
                    <a href="<?php echo esc_url( home_url('/#tools') ); ?>" class="fsp-plan__btn fsp-plan__btn--free">
                        Start Free
                    </a>
                </div>

                <!-- PRO MONTHLY -->
                <div class="fsp-plan fsp-plan--pro fsp-plan--monthly" id="plan-monthly">
                    <div class="fsp-plan__popular">Most Popular</div>
                    <div class="fsp-plan__header">
                        <div class="fsp-plan__icon">&#11088;</div>
                        <h2 class="fsp-plan__name">PRO</h2>
                        <div class="fsp-plan__price">
                            <span class="fsp-plan__amount">$9</span>
                            <span class="fsp-plan__period">/month</span>
                        </div>
                        <p class="fsp-plan__desc">Full access to all PRO features. Cancel anytime.</p>
                    </div>
                    <ul class="fsp-plan__features">
                        <li class="yes">Everything in Free</li>
                        <?php foreach($features as $f): ?>
                        <li class="yes"><?php echo esc_html($f); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if($is_pro): ?>
                    <button class="fsp-plan__btn fsp-plan__btn--active" disabled>&#10003; Your Current Plan</button>
                    <?php else: ?>
                    <button class="fsp-plan__btn fsp-plan__btn--pro fsp-checkout-btn" data-plan="monthly">
                        <span>Get PRO Monthly -- $9/mo</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                    <?php endif; ?>
                </div>

                <!-- PRO YEARLY -->
                <div class="fsp-plan fsp-plan--pro fsp-plan--yearly" id="plan-yearly" style="display:none;">
                    <div class="fsp-plan__popular">Best Value</div>
                    <div class="fsp-plan__header">
                        <div class="fsp-plan__icon">&#11088;</div>
                        <h2 class="fsp-plan__name">PRO Yearly</h2>
                        <div class="fsp-plan__price">
                            <span class="fsp-plan__amount">$79</span>
                            <span class="fsp-plan__period">/year</span>
                        </div>
                        <p class="fsp-plan__desc">Best value -- just $6.58/month billed annually.</p>
                    </div>
                    <ul class="fsp-plan__features">
                        <li class="yes">Everything in Free</li>
                        <?php foreach($features as $f): ?>
                        <li class="yes"><?php echo esc_html($f); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if($is_pro): ?>
                    <button class="fsp-plan__btn fsp-plan__btn--active" disabled>&#10003; Current Plan</button>
                    <?php else: ?>
                    <button class="fsp-plan__btn fsp-plan__btn--pro fsp-checkout-btn" data-plan="yearly">
                        <span>Get PRO Yearly -- $79/yr</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                    <?php endif; ?>
                </div>

                <!-- LIFETIME -->
                <div class="fsp-plan fsp-plan--lifetime">
                    <div class="fsp-plan__header">
                        <div class="fsp-plan__icon">&#9854;&#65039;</div>
                        <h2 class="fsp-plan__name">Lifetime</h2>
                        <div class="fsp-plan__price">
                            <span class="fsp-plan__amount">$199</span>
                            <span class="fsp-plan__period">one time</span>
                        </div>
                        <p class="fsp-plan__desc">Pay once, use forever. Includes all future updates.</p>
                    </div>
                    <ul class="fsp-plan__features">
                        <li class="yes">Everything in PRO</li>
                        <li class="yes">All future features</li>
                        <li class="yes">No recurring payments</li>
                        <li class="yes">Lifetime updates</li>
                        <li class="yes">VIP support</li>
                    </ul>
                    <?php if($is_pro): ?>
                    <button class="fsp-plan__btn fsp-plan__btn--active" disabled>&#10003; You have PRO</button>
                    <?php else: ?>
                    <button class="fsp-plan__btn fsp-plan__btn--lifetime fsp-checkout-btn" data-plan="lifetime">
                        <span>Get Lifetime -- $199</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                    <?php endif; ?>
                </div>

            </div><!-- /.fsp-plans -->

            <!-- Error/loading message -->
            <div class="fsp-checkout-msg" id="fsp-checkout-msg" style="display:none;"></div>

        </div>
    </section>

    <!-- Feature comparison -->
    <section class="fsp-compare">
        <div class="container">
            <h2 class="fsp-compare__title">What's Included in PRO?</h2>
            <div class="fsp-features-grid">
                <?php
                $icons = ['&#128190;','&#128196;','&#128202;','&#127968;','&#128200;','&#128683;','&#128231;','&#128640;'];
                foreach ( $features as $i => $f ) : ?>
                <div class="fsp-feature-card">
                    <div class="fsp-feature-card__icon"><?php echo $icons[$i] ?? '&#11088;'; ?></div>
                    <div class="fsp-feature-card__text"><?php echo esc_html($f); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="fsp-faq">
        <div class="container">
            <h2 class="fsp-compare__title">Frequently Asked Questions</h2>
            <div class="fsp-faq__list">
                <?php
                $faqs = [
                    ['Can I cancel anytime?', 'Yes! Monthly and yearly plans can be cancelled at any time from your account. You keep PRO access until the end of your billing period.'],
                    ['What payment methods are accepted?', 'We accept all major credit/debit cards (Visa, Mastercard, Amex) via Stripe. All payments are secure and encrypted.'],
                    ['Is there a free trial?', 'All core tools are free forever -- no trial needed. You can use the free plan as long as you like before upgrading.'],
                    ['What happens after I pay?', 'Your account is instantly upgraded to PRO. You will see a &#11088; PRO badge and all features unlock immediately.'],
                    ['Can I upgrade from monthly to yearly?', 'Yes, you can switch plans at any time. Contact us and we will handle the upgrade with a prorated discount.'],
                ];
                foreach($faqs as [$q,$a]): ?>
                <div class="fsp-faq__item">
                    <button class="fsp-faq__q" onclick="fspFaqToggle(this)">
                        <?php echo esc_html($q); ?>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="fsp-faq__a"><?php echo esc_html($a); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="fsp-cta">
        <div class="container">
            <div class="fsp-cta__inner">
                <h2>Ready to Go PRO?</h2>
                <p>Join hundreds of users who use FinanceSpots PRO to make smarter financial decisions.</p>
                <?php if(!$is_pro): ?>
                <button class="fsp-cta__btn fsp-checkout-btn" data-plan="monthly">
                    &#11088; Get PRO Now -- Start at $9/month
                </button>
                <?php else: ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="fsp-cta__btn">Go to Tools &#x2192;</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

</div><!-- /.fsp-page -->

<style>
.fsp-page{background:#0A0F1E;min-height:100vh;color:#F1F5F9;}

/* Hero */
.fsp-hero{padding:80px 0 60px;position:relative;overflow:hidden;text-align:center;}
.fsp-hero__bg{position:absolute;inset:0;pointer-events:none;}
.fsp-hero__orb{position:absolute;border-radius:50%;filter:blur(80px);opacity:.3;}
.fsp-hero__orb--1{width:400px;height:400px;background:#10B981;top:-100px;left:-100px;}
.fsp-hero__orb--2{width:350px;height:350px;background:#6366F1;bottom:-80px;right:-80px;}
.fsp-badge{display:inline-block;background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#10B981;padding:6px 18px;border-radius:50px;font-size:.82rem;font-weight:700;margin-bottom:20px;}
.fsp-hero__title{font-size:clamp(2rem,4vw,3rem);font-weight:900;color:#fff;margin:0 0 14px;}
.fsp-hero__sub{font-size:1.05rem;color:#94A3B8;margin-bottom:32px;}

/* Toggle */
.fsp-toggle{display:inline-flex;background:#131929;border:1px solid rgba(255,255,255,.08);border-radius:50px;padding:4px;gap:4px;}
.fsp-toggle__btn{background:none;border:none;color:#64748B;font-size:.875rem;font-weight:600;padding:9px 22px;border-radius:50px;cursor:pointer;transition:all .2s;position:relative;font-family:inherit;}
.fsp-toggle__btn.active{background:#10B981;color:#fff;box-shadow:0 4px 12px rgba(16,185,129,.4);}
.fsp-toggle__save{position:absolute;top:-8px;right:-4px;background:#F59E0B;color:#fff;font-size:.6rem;font-weight:800;padding:2px 6px;border-radius:20px;}

/* Plans */
.fsp-plans-section{padding:20px 0 60px;}
.fsp-already-pro{display:flex;align-items:center;gap:16px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);border-radius:16px;padding:18px 24px;margin-bottom:32px;font-size:.9rem;}
.fsp-already-pro span{font-size:2rem;}
.fsp-already-pro strong{display:block;color:#10B981;font-weight:700;margin-bottom:3px;}
.fsp-already-pro span:last-of-type{color:#64748B;}

.fsp-plans{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;align-items:start;}
@media(max-width:900px){.fsp-plans{grid-template-columns:1fr;max-width:420px;margin:0 auto;}}

.fsp-plan{background:#131929;border:1px solid rgba(255,255,255,.08);border-radius:24px;padding:28px;position:relative;transition:transform .25s,box-shadow .25s;}
.fsp-plan:hover{transform:translateY(-4px);box-shadow:0 20px 50px rgba(0,0,0,.4);}
.fsp-plan--pro{border-color:rgba(16,185,129,.35);background:linear-gradient(160deg,#131929 0%,#0f2318 100%);}
.fsp-plan--lifetime{border-color:rgba(99,102,241,.35);background:linear-gradient(160deg,#131929 0%,#13122A 100%);}

.fsp-plan__popular{position:absolute;top:-13px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg,#10B981,#059669);color:#fff;font-size:.72rem;font-weight:800;padding:4px 16px;border-radius:20px;white-space:nowrap;text-transform:uppercase;letter-spacing:.05em;}
.fsp-plan__icon{font-size:2rem;margin-bottom:10px;}
.fsp-plan__name{font-size:1.2rem;font-weight:800;color:#fff;margin:0 0 14px;}
.fsp-plan__price{display:flex;align-items:baseline;gap:4px;margin-bottom:12px;}
.fsp-plan__amount{font-size:2.8rem;font-weight:900;color:#fff;line-height:1;}
.fsp-plan__period{font-size:.9rem;color:#64748B;}
.fsp-plan--pro .fsp-plan__amount{color:#10B981;}
.fsp-plan--lifetime .fsp-plan__amount{color:#A78BFA;}
.fsp-plan__desc{font-size:.82rem;color:#64748B;margin-bottom:20px;line-height:1.6;}

.fsp-plan__features{list-style:none;padding:0;margin:0 0 24px;display:flex;flex-direction:column;gap:10px;}
.fsp-plan__features li{font-size:.84rem;padding-left:22px;position:relative;color:#CBD5E1;}
.fsp-plan__features li::before{content:'\2713';position:absolute;left:0;font-weight:800;}
.fsp-plan__features li.yes::before{color:#10B981;}
.fsp-plan__features li.no{color:#475569;}
.fsp-plan__features li.no::before{content:'\2717';color:#374151;}

.fsp-plan__btn{width:100%;padding:14px 20px;border-radius:12px;font-size:.9rem;font-weight:700;border:none;cursor:pointer;transition:all .25s;display:flex;align-items:center;justify-content:center;gap:8px;font-family:inherit;}
.fsp-plan__btn--free{background:rgba(255,255,255,.06);color:#CBD5E1;text-decoration:none;}
.fsp-plan__btn--free:hover{background:rgba(255,255,255,.1);}
.fsp-plan__btn--pro{background:linear-gradient(135deg,#10B981,#059669);color:#fff;box-shadow:0 6px 20px rgba(16,185,129,.35);}
.fsp-plan__btn--pro:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(16,185,129,.5);}
.fsp-plan__btn--lifetime{background:linear-gradient(135deg,#6366F1,#4F46E5);color:#fff;box-shadow:0 6px 20px rgba(99,102,241,.3);}
.fsp-plan__btn--lifetime:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(99,102,241,.5);}
.fsp-plan__btn--active{background:rgba(16,185,129,.12);color:#10B981;border:1px solid rgba(16,185,129,.3);cursor:default;}
.fsp-plan__btn:disabled{cursor:not-allowed;opacity:.7;transform:none!important;}

/* Checkout message */
.fsp-checkout-msg{margin-top:20px;padding:14px 18px;border-radius:12px;font-size:.875rem;font-weight:600;text-align:center;}
.fsp-checkout-msg.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#EF4444;}
.fsp-checkout-msg.loading{background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);color:#10B981;}

/* Features grid */
.fsp-compare{padding:60px 0;}
.fsp-compare__title{font-size:1.7rem;font-weight:900;color:#fff;text-align:center;margin-bottom:36px;}
.fsp-features-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;}
@media(max-width:900px){.fsp-features-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:500px){.fsp-features-grid{grid-template-columns:1fr;}}
.fsp-feature-card{background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:16px;padding:22px 18px;display:flex;flex-direction:column;gap:12px;transition:transform .2s;}
.fsp-feature-card:hover{transform:translateY(-3px);}
.fsp-feature-card__icon{font-size:1.8rem;}
.fsp-feature-card__text{font-size:.85rem;color:#CBD5E1;line-height:1.5;}

/* FAQ */
.fsp-faq{padding:0 0 60px;}
.fsp-faq__list{max-width:700px;margin:0 auto;display:flex;flex-direction:column;gap:4px;}
.fsp-faq__item{background:#131929;border:1px solid rgba(255,255,255,.07);border-radius:14px;overflow:hidden;}
.fsp-faq__q{width:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;background:none;border:none;color:#CBD5E1;font-size:.9rem;font-weight:600;padding:18px 20px;cursor:pointer;text-align:left;transition:color .2s;font-family:inherit;}
.fsp-faq__q:hover,.fsp-faq__q.open{color:#10B981;}
.fsp-faq__q svg{flex-shrink:0;transition:transform .25s;}
.fsp-faq__q.open svg{transform:rotate(180deg);}
.fsp-faq__a{font-size:.85rem;color:#64748B;line-height:1.7;padding:0 20px;max-height:0;overflow:hidden;transition:max-height .3s,padding .3s;}
.fsp-faq__a.open{max-height:200px;padding:0 20px 18px;}

/* CTA */
.fsp-cta{padding:60px 0 80px;}
.fsp-cta__inner{background:linear-gradient(135deg,#0f2318,#131929);border:1px solid rgba(16,185,129,.2);border-radius:24px;padding:52px 40px;text-align:center;}
.fsp-cta__inner h2{font-size:2rem;font-weight:900;color:#fff;margin-bottom:12px;}
.fsp-cta__inner p{color:#94A3B8;font-size:1rem;margin-bottom:28px;}
.fsp-cta__btn{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#10B981,#059669);color:#fff;border:none;border-radius:14px;padding:16px 32px;font-size:1rem;font-weight:700;cursor:pointer;transition:all .25s;box-shadow:0 6px 24px rgba(16,185,129,.35);text-decoration:none;font-family:inherit;}
.fsp-cta__btn:hover{transform:translateY(-3px);box-shadow:0 12px 36px rgba(16,185,129,.5);}

/* PRO badge (global) */
.fs-pro-badge{display:inline-flex;align-items:center;gap:4px;background:linear-gradient(135deg,#F59E0B,#D97706);color:#fff;font-size:.7rem;font-weight:800;padding:2px 8px;border-radius:20px;letter-spacing:.03em;text-transform:uppercase;}
</style>

<script>
var fspAjax  = '<?php echo esc_js( admin_url('admin-ajax.php') ); ?>';
var fspNonce = '<?php echo esc_js( wp_create_nonce('fs_pro_nonce') ); ?>';
var fspLoggedIn = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;

// Toggle monthly/yearly
document.querySelectorAll('.fsp-toggle__btn').forEach(function(btn){
    btn.addEventListener('click', function(){
        document.querySelectorAll('.fsp-toggle__btn').forEach(function(b){ b.classList.remove('active'); });
        btn.classList.add('active');
        var period = btn.dataset.period;
        document.querySelectorAll('.fsp-plan--monthly,.fsp-plan--yearly').forEach(function(p){ p.style.display='none'; });
        var show = document.getElementById('plan-' + period);
        if(show) show.style.display='block';
    });
});

// Checkout
document.querySelectorAll('.fsp-checkout-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
        if(!fspLoggedIn){
            // Open login modal
            var overlay = document.getElementById('fs-login-overlay');
            if(overlay){ overlay.classList.add('open'); overlay.setAttribute('aria-hidden','false'); document.body.style.overflow='hidden'; }
            return;
        }

        var plan = btn.dataset.plan;
        var msg  = document.getElementById('fsp-checkout-msg');
        document.querySelectorAll('.fsp-checkout-btn').forEach(function(b){ b.disabled=true; });
        msg.className='fsp-checkout-msg loading';
        msg.textContent='Connecting to payment… please wait.';
        msg.style.display='block';

        var fd = new FormData();
        fd.append('action','fs_create_checkout');
        fd.append('nonce', fspNonce);
        fd.append('plan', plan);

        fetch(fspAjax,{method:'POST',body:fd})
        .then(function(r){return r.json();})
        .then(function(res){
            if(res.success){
                window.location.href = res.data.url;
            } else {
                msg.className='fsp-checkout-msg error';
                msg.textContent = '&#9888; ' + (res.data || 'Payment error. Please try again.');
                document.querySelectorAll('.fsp-checkout-btn').forEach(function(b){ b.disabled=false; });
            }
        })
        .catch(function(){
            msg.className='fsp-checkout-msg error';
            msg.textContent='&#9888; Connection error. Please try again.';
            document.querySelectorAll('.fsp-checkout-btn').forEach(function(b){ b.disabled=false; });
        });
    });
});

// FAQ toggle
function fspFaqToggle(btn){
    var a = btn.nextElementSibling;
    var isOpen = btn.classList.contains('open');
    document.querySelectorAll('.fsp-faq__q').forEach(function(b){ b.classList.remove('open'); });
    document.querySelectorAll('.fsp-faq__a').forEach(function(a){ a.classList.remove('open'); });
    if(!isOpen){ btn.classList.add('open'); a.classList.add('open'); }
}
</script>

<?php get_footer(); ?>
