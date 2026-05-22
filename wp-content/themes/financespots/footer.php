</main><!-- /#main-content -->

<!-- ===== SITE FOOTER ===== -->
<footer class="fs-footer" role="contentinfo">

    <!-- Newsletter CTA Band -- Redesigned -->
    <section class="fs-nl2" aria-labelledby="newsletter-heading">

        <!-- Animated background -->
        <div class="fs-nl2__bg" aria-hidden="true">
            <div class="fs-nl2__orb fs-nl2__orb--1"></div>
            <div class="fs-nl2__orb fs-nl2__orb--2"></div>
            <div class="fs-nl2__orb fs-nl2__orb--3"></div>
            <div class="fs-nl2__grid"></div>
        </div>

        <div class="container" style="position:relative;z-index:2;">

            <!-- Top trust bar -->
            <div class="fs-nl2__trust">
                <span class="fs-nl2__trust-item"><span class="fs-nl2__trust-dot"></span> 10,000+ Subscribers</span>
                <span class="fs-nl2__trust-sep">·</span>
                <span class="fs-nl2__trust-item">&#128236; Every Monday</span>
                <span class="fs-nl2__trust-sep">·</span>
                <span class="fs-nl2__trust-item">&#128274; Zero Spam</span>
            </div>

            <!-- Main content -->
            <div class="fs-nl2__inner">

                <!-- Left -->
                <div class="fs-nl2__left">
                    <div class="fs-nl2__badge">
                        <span class="fs-nl2__badge-pulse"></span>
                        &#128236; Weekly Finance Digest
                    </div>
                    <h2 class="fs-nl2__title" id="newsletter-heading">
                        Stay <span class="fs-nl2__title-accent">One Step Ahead</span><br>of Your Finances
                    </h2>
                    <p class="fs-nl2__desc">Join thousands of smart readers who get our weekly finance tips, market insights, tool updates, and money-saving strategies -- free every Monday morning.</p>

                    <!-- What you get -->
                    <div class="fs-nl2__perks">
                        <div class="fs-nl2__perk"><span class="fs-nl2__perk-icon">&#128200;</span> Weekly market & rate updates</div>
                        <div class="fs-nl2__perk"><span class="fs-nl2__perk-icon">&#129518;</span> New tool announcements first</div>
                        <div class="fs-nl2__perk"><span class="fs-nl2__perk-icon">&#128161;</span> Actionable money-saving tips</div>
                        <div class="fs-nl2__perk"><span class="fs-nl2__perk-icon">&#128196;</span> Exclusive finance guides</div>
                    </div>
                </div>

                <!-- Right: Form card -->
                <div class="fs-nl2__right">
                    <div class="fs-nl2__card">
                        <div class="fs-nl2__card-header">
                            <div class="fs-nl2__card-avatar">AR</div>
                            <div>
                                <div class="fs-nl2__card-name">Abdul Rahman</div>
                                <div class="fs-nl2__card-role">FinanceSpots Founder</div>
                            </div>
                            <div class="fs-nl2__card-live">● LIVE</div>
                        </div>
                        <p class="fs-nl2__card-quote">"Every week I share the best finance insights I find -- the same tips I use personally. Join free."</p>

                        <form class="fs-nl2__form" id="fs-nl2-form" novalidate>
                            <div class="fs-nl2__field">
                                <span class="fs-nl2__field-icon">&#9993;</span>
                                <input type="email" id="fs-nl2-email" placeholder="Enter your email address" required>
                            </div>
                            <button type="submit" class="fs-nl2__btn" id="fs-nl2-btn">
                                <span class="fs-nl2__btn-text">Subscribe Free -- It's Monday's Best Email</span>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </button>
                            <div class="fs-nl2__success" id="fs-nl2-success" style="display:none">
                                &#127881; You're in! Check your inbox Monday morning.
                            </div>
                            <p class="fs-nl2__fine">&#128274; No spam, ever. Unsubscribe in one click anytime.</p>
                        </form>

                        <!-- Social proof avatars -->
                        <div class="fs-nl2__social-proof">
                            <div class="fs-nl2__avatars">
                                <div class="fs-nl2__av" style="background:#10B981">JM</div>
                                <div class="fs-nl2__av" style="background:#3B82F6">SK</div>
                                <div class="fs-nl2__av" style="background:#8B5CF6">AR</div>
                                <div class="fs-nl2__av" style="background:#F59E0B">ZK</div>
                                <div class="fs-nl2__av" style="background:#EF4444">MN</div>
                            </div>
                            <span class="fs-nl2__social-text">Joined by <strong>10,000+</strong> finance readers</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <style>
    /* ── Newsletter v2 ── */
    .fs-nl2{position:relative;padding:80px 0;overflow:hidden;background:#050A14;}
    .fs-nl2__bg{position:absolute;inset:0;pointer-events:none;}
    .fs-nl2__grid{position:absolute;inset:0;background-image:linear-gradient(rgba(16,185,129,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(16,185,129,.04) 1px,transparent 1px);background-size:60px 60px;}
    .fs-nl2__orb{position:absolute;border-radius:50%;filter:blur(80px);}
    .fs-nl2__orb--1{width:500px;height:500px;background:radial-gradient(circle,rgba(16,185,129,.18) 0%,transparent 70%);top:-100px;left:-100px;}
    .fs-nl2__orb--2{width:400px;height:400px;background:radial-gradient(circle,rgba(59,130,246,.12) 0%,transparent 70%);bottom:-80px;right:-80px;}
    .fs-nl2__orb--3{width:300px;height:300px;background:radial-gradient(circle,rgba(139,92,246,.1) 0%,transparent 70%);top:50%;left:50%;transform:translate(-50%,-50%);}

    /* Trust bar */
    .fs-nl2__trust{display:flex;align-items:center;justify-content:center;gap:16px;margin-bottom:52px;flex-wrap:wrap;}
    .fs-nl2__trust-item{font-size:.8rem;font-weight:600;color:#64748B;display:flex;align-items:center;gap:6px;}
    .fs-nl2__trust-dot{width:7px;height:7px;border-radius:50%;background:#10B981;display:inline-block;animation:nl-pulse 2s infinite;}
    .fs-nl2__trust-sep{color:#1E293B;font-size:1.2rem;}
    @keyframes nl-pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(1.5)}}

    /* Inner grid */
    .fs-nl2__inner{display:grid;grid-template-columns:1fr 480px;gap:60px;align-items:center;}
    @media(max-width:960px){.fs-nl2__inner{grid-template-columns:1fr;gap:40px;}}

    /* Left */
    .fs-nl2__badge{display:inline-flex;align-items:center;gap:8px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);color:#10B981;font-size:.75rem;font-weight:700;padding:.4rem 1rem;border-radius:20px;letter-spacing:.05em;margin-bottom:20px;}
    .fs-nl2__badge-pulse{width:7px;height:7px;border-radius:50%;background:#10B981;display:inline-block;animation:nl-pulse 1.5s infinite;flex-shrink:0;}
    .fs-nl2__title{font-size:clamp(2rem,3.5vw,2.8rem);font-weight:900;color:#fff;line-height:1.15;margin:0 0 16px;}
    .fs-nl2__title-accent{background:linear-gradient(135deg,#10B981,#06B6D4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
    .fs-nl2__desc{color:#94A3B8;font-size:1rem;line-height:1.75;margin-bottom:28px;max-width:480px;}
    .fs-nl2__perks{display:flex;flex-direction:column;gap:11px;}
    .fs-nl2__perk{display:flex;align-items:center;gap:11px;color:#CBD5E1;font-size:.9rem;}
    .fs-nl2__perk-icon{width:32px;height:32px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.95rem;flex-shrink:0;}

    /* Right card */
    .fs-nl2__card{background:rgba(19,25,41,.95);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:28px;box-shadow:0 25px 60px rgba(0,0,0,.5),0 0 0 1px rgba(16,185,129,.08),inset 0 1px 0 rgba(255,255,255,.06);backdrop-filter:blur(10px);}
    .fs-nl2__card-header{display:flex;align-items:center;gap:12px;margin-bottom:14px;}
    .fs-nl2__card-avatar{width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#10B981,#06B6D4);display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:800;color:#fff;flex-shrink:0;}
    .fs-nl2__card-name{font-size:.9rem;font-weight:700;color:#F1F5F9;}
    .fs-nl2__card-role{font-size:.73rem;color:#64748B;}
    .fs-nl2__card-live{margin-left:auto;font-size:.68rem;font-weight:700;color:#10B981;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);border-radius:20px;padding:.2rem .65rem;animation:nl-pulse 2s infinite;}
    .fs-nl2__card-quote{font-size:.875rem;color:#94A3B8;line-height:1.65;margin:0 0 20px;font-style:italic;border-left:2px solid #10B981;padding-left:12px;}

    /* Form */
    .fs-nl2__form{display:flex;flex-direction:column;gap:12px;}
    .fs-nl2__field{display:flex;align-items:center;gap:10px;background:#0A0F1E;border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:0 16px;transition:border-color .2s;}
    .fs-nl2__field:focus-within{border-color:#10B981;box-shadow:0 0 0 3px rgba(16,185,129,.12);}
    .fs-nl2__field-icon{color:#64748B;font-size:1rem;flex-shrink:0;}
    .fs-nl2__field input{flex:1;background:none;border:none;outline:none;color:#F1F5F9;font-size:.9rem;padding:14px 0;font-family:inherit;}
    .fs-nl2__field input::placeholder{color:#475569;}
    .fs-nl2__btn{display:flex;align-items:center;justify-content:center;gap:8px;background:linear-gradient(135deg,#10B981,#0d9e6f);color:#fff;border:none;border-radius:12px;padding:14px 20px;font-size:.88rem;font-weight:800;cursor:pointer;transition:all .25s;box-shadow:0 4px 20px rgba(16,185,129,.3);width:100%;}
    .fs-nl2__btn:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(16,185,129,.45);}
    .fs-nl2__btn-text{flex:1;text-align:center;}
    .fs-nl2__success{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);border-radius:10px;padding:12px 16px;color:#10B981;font-size:.875rem;font-weight:600;text-align:center;}
    .fs-nl2__fine{font-size:.75rem;color:#475569;text-align:center;margin:0;}

    /* Social proof */
    .fs-nl2__social-proof{display:flex;align-items:center;gap:12px;margin-top:18px;padding-top:18px;border-top:1px solid rgba(255,255,255,.06);}
    .fs-nl2__avatars{display:flex;}
    .fs-nl2__av{width:30px;height:30px;border-radius:50%;border:2px solid #131929;display:flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:800;color:#fff;margin-left:-8px;}
    .fs-nl2__avatars .fs-nl2__av:first-child{margin-left:0;}
    .fs-nl2__social-text{font-size:.78rem;color:#64748B;}
    .fs-nl2__social-text strong{color:#94A3B8;}
    </style>

    <script>
    document.getElementById('fs-nl2-form').addEventListener('submit',function(e){
        e.preventDefault();
        var email = document.getElementById('fs-nl2-email').value.trim();
        var btn   = document.getElementById('fs-nl2-btn');
        var suc   = document.getElementById('fs-nl2-success');
        if(!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return;
        btn.disabled = true;
        btn.querySelector('.fs-nl2__btn-text').textContent = 'Subscribing...';
        setTimeout(function(){
            btn.style.display = 'none';
            suc.style.display = 'block';
            document.getElementById('fs-nl2-form').reset();
        }, 1000);
    });
    </script>

    <!-- ═══ MAIN FOOTER ═══ -->
    <footer class="fsf" role="contentinfo">

        <!-- Top border glow -->
        <div class="fsf__topbar"></div>

        <div class="fsf__main">
            <div class="container">
                <div class="fsf__grid">

                    <!-- Brand col -->
                    <div class="fsf__brand">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="fsf__logo">
                            <span class="fsf__logo-dot"></span>
                            <span class="fsf__logo-light">finance</span><span class="fsf__logo-accent">spots</span>
                        </a>
                        <p class="fsf__desc">Free, professional-grade financial calculators and tools -- no account required. Built by Abdul Rahman to make smart money decisions accessible to everyone.</p>

                        <!-- Mini stats -->
                        <div class="fsf__mini-stats">
                            <div class="fsf__mini-stat"><span>30+</span> Free Tools</div>
                            <div class="fsf__mini-stat"><span>100%</span> Free</div>
                            <div class="fsf__mini-stat"><span>0</span> Signups</div>
                        </div>

                        <!-- Social -->
                        <div class="fsf__socials">
                            <a href="#" class="fsf__social" aria-label="Twitter/X">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622L18.244 2.25z"/></svg>
                            </a>
                            <a href="#" class="fsf__social" aria-label="LinkedIn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                            <a href="#" class="fsf__social" aria-label="YouTube">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Tools col -->
                    <div class="fsf__col">
                        <h4 class="fsf__col-title">
                            <span class="fsf__col-dot" style="background:#10B981"></span>
                            Popular Tools
                        </h4>
                        <ul class="fsf__links">
                            <li><a href="<?php echo esc_url(home_url('/tools/mortgage-calculator/')); ?>">&#127968; Mortgage Calculator</a></li>
                            <li><a href="<?php echo esc_url(home_url('/tools/compound-interest-calculator/')); ?>">&#128200; Compound Interest</a></li>
                            <li><a href="<?php echo esc_url(home_url('/tools/retirement-income-calculator/')); ?>">&#127958;&#65039; Retirement Planner</a></li>
                            <li><a href="<?php echo esc_url(home_url('/tools/loan-payoff-calculator/')); ?>">&#128179; Debt Payoff Tool</a></li>
                            <li><a href="<?php echo esc_url(home_url('/tools/monthly-budget-planner/')); ?>">&#128203; Budget Planner</a></li>
                            <li><a href="<?php echo esc_url(home_url('/tools/crypto-pl-calculator/')); ?>">₿ Crypto P&amp;L Calculator</a></li>
                            <li><a href="<?php echo esc_url(home_url('/tools/income-tax-calculator/')); ?>">&#129534; Tax Calculator 2026</a></li>
                        </ul>
                    </div>

                    <!-- Company col -->
                    <div class="fsf__col">
                        <h4 class="fsf__col-title">
                            <span class="fsf__col-dot" style="background:#3B82F6"></span>
                            Company
                        </h4>
                        <ul class="fsf__links">
                            <li><a href="<?php echo esc_url(home_url('/')); ?>">&#127968; Home</a></li>
                            <li><a href="<?php echo esc_url(home_url('/about/')); ?>">&#128075; About Us</a></li>
                            <li><a href="<?php echo esc_url(home_url('/blog/')); ?>">&#128221; Finance Blog</a></li>
                            <li><a href="<?php echo esc_url(home_url('/all-tools/')); ?>">&#129518; All Tools</a></li>
                            <li><a href="<?php echo esc_url(home_url('/pricing/')); ?>">&#11088; PRO Plans</a></li>
                            <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">&#128233; Contact Us</a></li>
                        </ul>
                    </div>

                    <!-- Legal col -->
                    <div class="fsf__col">
                        <h4 class="fsf__col-title">
                            <span class="fsf__col-dot" style="background:#8B5CF6"></span>
                            Legal & Info
                        </h4>
                        <ul class="fsf__links">
                            <li><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">&#128274; Privacy Policy</a></li>
                            <li><a href="<?php echo esc_url(home_url('/terms-of-service/')); ?>">&#128196; Terms of Service</a></li>
                            <li><a href="<?php echo esc_url(home_url('/privacy-policy/#cookies')); ?>">&#127850; Cookie Policy</a></li>
                            <li><a href="<?php echo esc_url(home_url('/terms-of-service/#disclaimer')); ?>">&#9888;&#65039; Disclaimer</a></li>
                        </ul>
                        <!-- Trust badges -->
                        <div class="fsf__badges">
                            <span class="fsf__badge">&#128274; SSL Secured</span>
                            <span class="fsf__badge">&#9989; Expert Verified</span>
                            <span class="fsf__badge">&#127379; Always Free</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Bottom bar -->
        <div class="fsf__bottom">
            <div class="container">
                <div class="fsf__bottom-inner">
                    <p class="fsf__copy">© <?php echo date('Y'); ?> <strong>FinanceSpots</strong> by Abdul Rahman · All rights reserved · For informational purposes only, not financial advice.</p>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="fsf__back-home">
                        <span class="fsf__logo-dot" style="width:8px;height:8px;"></span>
                        finance<span style="color:#10B981">spots</span>
                    </a>
                </div>
            </div>
        </div>

    </footer>

    <style>
    /* ══ New Footer ══ */
    .fsf{background:#030712;border-top:1px solid rgba(255,255,255,.06);}
    .fsf__topbar{height:3px;background:linear-gradient(90deg,#10B981,#3B82F6,#8B5CF6,#10B981);background-size:200% 100%;animation:fsf-bar 4s linear infinite;}
    @keyframes fsf-bar{0%{background-position:0%}100%{background-position:200%}}

    .fsf__main{padding:64px 0 48px;}
    .fsf__grid{display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;gap:48px;}
    @media(max-width:1024px){.fsf__grid{grid-template-columns:1fr 1fr;gap:36px;}}
    @media(max-width:600px){.fsf__grid{grid-template-columns:1fr;gap:28px;}}

    /* Brand */
    .fsf__logo{display:inline-flex;align-items:center;gap:8px;text-decoration:none;font-size:1.25rem;font-weight:900;letter-spacing:-.02em;margin-bottom:16px;}
    .fsf__logo-dot{width:10px;height:10px;border-radius:50%;background:#10B981;display:inline-block;box-shadow:0 0 10px rgba(16,185,129,.6);}
    .fsf__logo-light{color:#fff;}
    .fsf__logo-accent{color:#10B981;}
    .fsf__desc{color:#475569;font-size:.875rem;line-height:1.75;margin-bottom:20px;max-width:320px;}
    .fsf__mini-stats{display:flex;gap:0;margin-bottom:20px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:10px;overflow:hidden;}
    .fsf__mini-stat{flex:1;text-align:center;padding:10px 8px;font-size:.72rem;color:#475569;border-right:1px solid rgba(255,255,255,.06);}
    .fsf__mini-stat:last-child{border-right:none;}
    .fsf__mini-stat span{display:block;font-size:1rem;font-weight:800;color:#10B981;margin-bottom:1px;}
    .fsf__socials{display:flex;gap:10px;}
    .fsf__social{width:36px;height:36px;border-radius:9px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;color:#64748B;text-decoration:none;transition:all .2s;}
    .fsf__social:hover{background:rgba(16,185,129,.15);border-color:rgba(16,185,129,.35);color:#10B981;transform:translateY(-2px);}

    /* Cols */
    .fsf__col-title{font-size:.75rem;font-weight:800;color:#94A3B8;text-transform:uppercase;letter-spacing:.1em;margin:0 0 18px;display:flex;align-items:center;gap:8px;}
    .fsf__col-dot{width:6px;height:6px;border-radius:50%;display:inline-block;flex-shrink:0;}
    .fsf__links{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;}
    .fsf__links li a{color:#475569;text-decoration:none;font-size:.875rem;transition:color .2s;display:flex;align-items:center;gap:7px;}
    .fsf__links li a:hover{color:#10B981;padding-left:4px;}
    .fsf__badges{display:flex;flex-direction:column;gap:7px;margin-top:20px;}
    .fsf__badge{font-size:.72rem;color:#334155;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:6px;padding:5px 10px;display:inline-block;width:fit-content;}

    /* Bottom */
    .fsf__bottom{border-top:1px solid rgba(255,255,255,.05);padding:18px 0;}
    .fsf__bottom-inner{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;}
    .fsf__copy{font-size:.75rem;color:#334155;margin:0;}
    .fsf__copy strong{color:#475569;}
    .fsf__back-home{font-size:.85rem;font-weight:800;color:#475569;text-decoration:none;display:flex;align-items:center;gap:6px;transition:color .2s;}
    .fsf__back-home:hover{color:#fff;}
    </style>

    <!-- Back to Top -->
    <button class="fs-back-to-top" id="fs-back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'financespots' ); ?>">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M18 15l-6-6-6 6"/></svg>
    </button>

<?php wp_footer(); ?>
</body>
</html>
