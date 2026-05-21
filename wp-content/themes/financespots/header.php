<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0A0F1E">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ═══════════════════════════════════════════════════
     LIVE MARKET TICKER BAR
     ═══════════════════════════════════════════════════ -->
<div class="fs-ticker" id="fs-ticker" role="marquee" aria-label="<?php esc_attr_e( 'Live market prices', 'financespots' ); ?>">
    <div class="fs-ticker__inner">
        <div class="fs-ticker__track" id="fs-ticker-track">
            <?php
            $ticker_items = [
                [ 'symbol' => 'S&P 500',  'price' => '5,284.92',  'change' => '+1.2%',  'up' => true  ],
                [ 'symbol' => 'NASDAQ',   'price' => '16,742.39', 'change' => '+0.8%',  'up' => true  ],
                [ 'symbol' => 'DOW',      'price' => '41,503.10', 'change' => '-0.3%',  'up' => false ],
                [ 'symbol' => 'BTC/USD',  'price' => '$103,240',  'change' => '+3.4%',  'up' => true  ],
                [ 'symbol' => 'ETH/USD',  'price' => '$2,481',    'change' => '+2.1%',  'up' => true  ],
                [ 'symbol' => 'GOLD',     'price' => '$3,204',    'change' => '+0.5%',  'up' => true  ],
                [ 'symbol' => '10Y BOND', 'price' => '4.32%',     'change' => '-0.04%', 'up' => false ],
                [ 'symbol' => 'OIL/WTI',  'price' => '$78.42',    'change' => '+1.1%',  'up' => true  ],
                [ 'symbol' => 'EUR/USD',  'price' => '1.0821',    'change' => '-0.2%',  'up' => false ],
                [ 'symbol' => 'GBP/USD',  'price' => '1.2654',    'change' => '+0.3%',  'up' => true  ],
                [ 'symbol' => 'JPY/USD',  'price' => '0.00643',   'change' => '-0.5%',  'up' => false ],
                [ 'symbol' => 'SILVER',   'price' => '$32.18',    'change' => '+0.9%',  'up' => true  ],
            ];
            // Output twice for seamless loop
            for ( $loop = 0; $loop < 2; $loop++ ) :
                foreach ( $ticker_items as $item ) :
                    $dir   = $item['up'] ? 'up' : 'down';
                    $arrow = $item['up'] ? '▲' : '▼';
            ?>
            <span class="fs-ticker__item">
                <span class="fs-ticker__symbol"><?php echo esc_html( $item['symbol'] ); ?></span>
                <span class="fs-ticker__price" data-symbol="<?php echo esc_attr( $item['symbol'] ); ?>">
                    <?php echo esc_html( $item['price'] ); ?>
                </span>
                <span class="fs-ticker__change fs-ticker__change--<?php echo $dir; ?>">
                    <span class="fs-ticker__arrow" aria-hidden="true"><?php echo $arrow; ?></span>
                    <?php echo esc_html( $item['change'] ); ?>
                </span>
            </span>
            <?php endforeach; endfor; ?>
        </div>
    </div>
    <!-- Fade edges -->
    <div class="fs-ticker__fade fs-ticker__fade--left"  aria-hidden="true"></div>
    <div class="fs-ticker__fade fs-ticker__fade--right" aria-hidden="true"></div>
</div>

<!-- ═══════════════════════════════════════════════════
     MAIN NAVBAR
     ═══════════════════════════════════════════════════ -->
<header class="fs-header" id="fs-header" role="banner">
    <div class="fs-header__inner container">

        <!-- ── Logo ── -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="fs-header__logo"
           aria-label="<?php bloginfo( 'name' ); ?>">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <span class="fs-logo-text">
                    <span class="fs-logo-dot" aria-hidden="true"></span>
                    <span class="fs-logo-word fs-logo-word--light">finance</span><span class="fs-logo-word fs-logo-word--accent">spots</span>
                </span>
            <?php endif; ?>
        </a>

        <!-- ── Primary Navigation ── -->
        <nav class="fs-header__nav" id="fs-primary-nav"
             role="navigation"
             aria-label="<?php esc_attr_e( 'Primary Navigation', 'financespots' ); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'menu_class'     => 'fs-nav-list',
                'container'      => false,
                'fallback_cb'    => 'financespots_fallback_menu',
                'walker'         => class_exists('FinanceSpots_Walker_Nav') ? new FinanceSpots_Walker_Nav() : null,
            ]);
            ?>
        </nav>

        <!-- ── Right Actions ── -->
        <div class="fs-header__actions">
            <!-- Search trigger -->
            <button class="fs-header__search-btn" id="fs-search-btn"
                    aria-label="<?php esc_attr_e( 'Open search', 'financespots' ); ?>"
                    aria-expanded="false">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
            </button>

            <!-- Sign In / User Menu -->
            <?php if ( is_user_logged_in() ) :
                $current_user = wp_get_current_user();
                $avatar = get_avatar_url( $current_user->ID, [ 'size' => 36 ] );
            ?>
            <div class="fs-user-menu" id="fs-user-menu">
                <button class="fs-user-menu__trigger" id="fs-user-trigger" aria-expanded="false" aria-haspopup="true">
                    <img src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $current_user->display_name ); ?>" class="fs-user-menu__avatar">
                    <span class="fs-user-menu__name fs-user-menu__name--desktop"><?php echo esc_html( $current_user->display_name ); ?></span>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="fs-user-menu__dropdown" id="fs-user-dropdown" aria-hidden="true">
                    <div class="fs-user-menu__info">
                        <img src="<?php echo esc_url( $avatar ); ?>" alt="" class="fs-user-menu__info-avatar">
                        <div>
                            <div class="fs-user-menu__info-name">
                                <?php echo esc_html( $current_user->display_name ); ?>
                                <?php if ( fs_is_pro() ) : ?>
                                <span class="fs-pro-badge" style="font-size:.6rem;vertical-align:middle;margin-left:4px;">&#11088; PRO</span>
                                <?php endif; ?>
                            </div>
                            <div class="fs-user-menu__info-email"><?php echo esc_html( $current_user->user_email ); ?></div>
                        </div>
                    </div>
                    <div class="fs-user-menu__divider"></div>
                    <?php if ( ! fs_is_pro() ) : ?>
                    <a href="<?php echo esc_url( home_url('/pricing/') ); ?>" class="fs-user-menu__item fs-user-menu__item--upgrade">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        Upgrade to PRO
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>" class="fs-user-menu__item">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                        My Account
                    </a>
                    <?php if ( current_user_can('manage_options') ) : ?>
                    <a href="<?php echo esc_url( admin_url() ); ?>" class="fs-user-menu__item">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                        Dashboard
                    </a>
                    <?php endif; ?>
                    <div class="fs-user-menu__divider"></div>
                    <a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>" class="fs-user-menu__item fs-user-menu__item--logout">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                        Log Out
                    </a>
                </div>
            </div>
            <?php else : ?>
            <button class="fs-btn-signin" id="fs-signin-btn" aria-label="Sign In">
                Sign In
            </button>
            <?php endif; ?>

            <!-- Get Started CTA -->
            <a href="<?php echo esc_url( get_theme_mod( 'fs_cta_nav_url', '#tools' ) ); ?>"
               class="fs-btn fs-btn--primary fs-btn--nav">
                <?php echo esc_html( get_theme_mod( 'fs_cta_nav_label', __( 'Get Started Free', 'financespots' ) ) ); ?>
            </a>

            <!-- Mobile hamburger -->
            <button class="fs-hamburger" id="fs-hamburger"
                    aria-controls="fs-primary-nav"
                    aria-expanded="false"
                    aria-label="<?php esc_attr_e( 'Toggle navigation', 'financespots' ); ?>">
                <span class="fs-hamburger__bar"></span>
                <span class="fs-hamburger__bar"></span>
                <span class="fs-hamburger__bar"></span>
            </button>
        </div>

    </div><!-- /.fs-header__inner -->

    <!-- ── Search Overlay ── -->
    <div class="fs-search-overlay" id="fs-search-overlay" aria-hidden="true">
        <div class="fs-search-overlay__inner container">
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>"
                  class="fs-search-form">
                <label for="fs-search-input" class="screen-reader-text">
                    <?php esc_html_e( 'Search finance tools', 'financespots' ); ?>
                </label>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="search" id="fs-search-input" name="s"
                       class="fs-search-form__input"
                       placeholder="<?php esc_attr_e( 'Search tools, calculators, guides…', 'financespots' ); ?>"
                       autocomplete="off">
                <button type="button" class="fs-search-overlay__close" id="fs-search-close"
                        aria-label="<?php esc_attr_e( 'Close search', 'financespots' ); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </form>
            <div class="fs-search-suggestions">
                <span class="fs-search-suggestions__label">
                    <?php esc_html_e( 'Popular:', 'financespots' ); ?>
                </span>
                <?php
                $suggestions = [ 'Mortgage Calculator', 'Compound Interest', 'Tax Estimator', 'Retirement Planner', 'Budget Planner' ];
                foreach ( $suggestions as $s ) :
                ?>
                <a href="<?php echo esc_url( home_url( '/?s=' . urlencode( $s ) ) ); ?>"
                   class="fs-search-suggestion-pill">
                    <?php echo esc_html( $s ); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ── Mobile Nav Drawer ── -->
    <div class="fs-mobile-nav" id="fs-mobile-nav" aria-hidden="true">
        <div class="fs-mobile-nav__inner">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_class'     => 'fs-mobile-nav-list',
                'container'      => false,
                'fallback_cb'    => 'financespots_fallback_menu',
            ]);
            ?>
            <div class="fs-mobile-nav__actions">
                <?php if ( is_user_logged_in() ) : ?>
                <a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"
                   class="fs-btn fs-btn--outline fs-btn--full">
                    My Account
                </a>
                <?php else : ?>
                <button class="fs-btn fs-btn--outline fs-btn--full" id="fs-signin-btn-mobile">
                    Sign In
                </button>
                <?php endif; ?>
                <a href="<?php echo esc_url( get_theme_mod( 'fs_cta_nav_url', '#tools' ) ); ?>"
                   class="fs-btn fs-btn--primary fs-btn--full">
                    <?php echo esc_html( get_theme_mod( 'fs_cta_nav_label', __( 'Get Started Free', 'financespots' ) ) ); ?>
                </a>
            </div>
        </div>
    </div>

</header><!-- /.fs-header -->

<!-- ═══════════════════════════════════════════════════
     LOGIN MODAL
     ═══════════════════════════════════════════════════ -->
<?php if ( ! is_user_logged_in() ) : ?>
<div class="fs-login-overlay" id="fs-login-overlay" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="fs-login-title">
    <div class="fs-login-modal">

        <button class="fs-login-modal__close" id="fs-login-close" aria-label="Close">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>

        <div class="fs-login-modal__header">
            <span class="fs-logo-text" style="font-size:1.2rem;">
                <span class="fs-logo-dot"></span>
                <span class="fs-logo-word fs-logo-word--light">finance</span><span class="fs-logo-word fs-logo-word--accent">spots</span>
            </span>
            <h2 class="fs-login-modal__title" id="fs-login-title">Welcome Back</h2>
            <p class="fs-login-modal__sub">Sign in to your FinanceSpots account</p>
        </div>

        <div class="fs-login-tabs" role="tablist">
            <button class="fs-login-tab active" data-tab="login" role="tab" aria-selected="true">Sign In</button>
            <button class="fs-login-tab" data-tab="register" role="tab" aria-selected="false">Create Account</button>
        </div>

        <!-- LOGIN -->
        <div class="fs-login-panel active" id="fs-tab-login">
            <form class="fs-login-form" id="fs-login-form" novalidate>

                <div class="fs-login-field">
                    <label for="fl-email">Email Address</label>
                    <div class="fs-login-field__wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="text" id="fl-email" placeholder="your@email.com" autocomplete="email" inputmode="email">
                    </div>
                </div>

                <div class="fs-login-field">
                    <label for="fl-password">
                        Password
                        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="fs-login-forgot">Forgot password?</a>
                    </label>
                    <div class="fs-login-field__wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" id="fl-password" placeholder="&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;" required autocomplete="current-password">
                        <button type="button" class="fs-login-eye" onclick="fsTogglePwd('fl-password',this)" aria-label="Show password">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <label class="fs-login-remember">
                    <input type="checkbox" id="fl-remember">
                    <span>Remember me for 30 days</span>
                </label>

                <div class="fs-login-msg" id="fl-msg" style="display:none"></div>

                <button type="submit" class="fs-login-submit" id="fl-submit">
                    <span class="fs-login-submit__text">Sign In</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>

                <p style="text-align:center;font-size:.8rem;color:#64748B;margin-top:4px;">
                    No account? <button type="button" class="fs-login-switch" data-tab="register">Create one free &#x2192;</button>
                </p>
            </form>
        </div>

        <!-- REGISTER -->
        <div class="fs-login-panel" id="fs-tab-register">
            <form class="fs-login-form" id="fs-register-form" novalidate>

                <div class="fs-login-field">
                    <label for="fr-name">Full Name</label>
                    <div class="fs-login-field__wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                        <input type="text" id="fr-name" placeholder="Abdul Rahman" required autocomplete="name">
                    </div>
                </div>

                <div class="fs-login-field">
                    <label for="fr-email">Email Address</label>
                    <div class="fs-login-field__wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="text" id="fr-email" placeholder="your@email.com" autocomplete="email" inputmode="email">
                    </div>
                </div>

                <div class="fs-login-field">
                    <label for="fr-password">Password <span style="color:#64748B;font-weight:400;">(min 6 characters)</span></label>
                    <div class="fs-login-field__wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" id="fr-password" placeholder="Create a password" required autocomplete="new-password">
                        <button type="button" class="fs-login-eye" onclick="fsTogglePwd('fr-password',this)" aria-label="Show password">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div class="fs-login-msg" id="fr-msg" style="display:none"></div>

                <button type="submit" class="fs-login-submit" id="fr-submit">
                    <span class="fs-login-submit__text">Create Free Account</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>

                <p style="text-align:center;font-size:.78rem;color:#475569;margin-top:4px;">
                    By signing up you agree to our <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>" style="color:#10B981;">Privacy Policy</a>
                </p>
            </form>
        </div>

    </div>
</div>

<style>
.fs-login-overlay{position:fixed;inset:0;background:rgba(0,0,0,.75);backdrop-filter:blur(8px);z-index:99999;display:flex;align-items:center;justify-content:center;padding:16px;opacity:0;pointer-events:none;transition:opacity .25s;}
.fs-login-overlay.open{opacity:1;pointer-events:all;}
.fs-login-modal{background:#111827;border:1px solid rgba(255,255,255,.1);border-radius:24px;padding:36px 32px;width:100%;max-width:440px;position:relative;transform:translateY(24px) scale(.97);transition:transform .3s cubic-bezier(.34,1.56,.64,1);max-height:92vh;overflow-y:auto;}
.fs-login-overlay.open .fs-login-modal{transform:translateY(0) scale(1);}
.fs-login-modal__close{position:absolute;top:14px;right:14px;background:rgba(255,255,255,.06);border:none;border-radius:8px;width:34px;height:34px;display:flex;align-items:center;justify-content:center;color:#94A3B8;cursor:pointer;transition:all .2s;}
.fs-login-modal__close:hover{background:rgba(239,68,68,.15);color:#EF4444;}
.fs-login-modal__header{text-align:center;margin-bottom:22px;}
.fs-login-modal__title{font-size:1.45rem;font-weight:900;color:#fff;margin:10px 0 5px;}
.fs-login-modal__sub{color:#64748B;font-size:.85rem;}
.fs-login-tabs{display:flex;background:#0D1424;border-radius:12px;padding:4px;gap:4px;margin-bottom:22px;}
.fs-login-tab{flex:1;background:none;border:none;color:#64748B;font-size:.875rem;font-weight:600;padding:10px;border-radius:9px;cursor:pointer;transition:all .2s;font-family:inherit;}
.fs-login-tab.active{background:#1E2A42;color:#10B981;box-shadow:0 2px 8px rgba(0,0,0,.35);}
.fs-login-tab:not(.active):hover{color:#CBD5E1;}
.fs-login-panel{display:none;}
.fs-login-panel.active{display:block;}
.fs-login-form{display:flex;flex-direction:column;gap:16px;}
.fs-login-field{display:flex;flex-direction:column;gap:6px;}
.fs-login-field label{display:flex;align-items:center;justify-content:space-between;font-size:.8rem;font-weight:600;color:#94A3B8;}
.fs-login-forgot{color:#10B981;font-size:.78rem;font-weight:600;text-decoration:none;}
.fs-login-forgot:hover{text-decoration:underline;}
.fs-login-field__wrap{position:relative;display:flex;align-items:center;}
.fs-login-field__wrap>svg:first-child{position:absolute;left:13px;color:#4B5563;pointer-events:none;}
.fs-login-field__wrap input{width:100%;background:#0D1424;border:1.5px solid rgba(255,255,255,.08);border-radius:10px;padding:12px 42px 12px 40px;color:#F1F5F9;font-size:.9rem;outline:none;transition:border-color .2s,box-shadow .2s;font-family:inherit;}
.fs-login-field__wrap input:focus{border-color:#10B981;box-shadow:0 0 0 3px rgba(16,185,129,.12);}
.fs-login-eye{position:absolute;right:11px;background:none;border:none;color:#4B5563;cursor:pointer;padding:4px;display:flex;transition:color .2s;}
.fs-login-eye:hover{color:#10B981;}
.fs-login-remember{display:flex;align-items:center;gap:9px;cursor:pointer;}
.fs-login-remember input[type=checkbox]{width:16px;height:16px;accent-color:#10B981;cursor:pointer;flex-shrink:0;}
.fs-login-remember span{font-size:.82rem;color:#94A3B8;}
.fs-login-msg{border-radius:10px;padding:11px 15px;font-size:.85rem;font-weight:500;}
.fs-login-msg.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#EF4444;}
.fs-login-msg.success{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);color:#10B981;}
.fs-login-submit{display:flex;align-items:center;justify-content:center;gap:8px;background:linear-gradient(135deg,#10B981,#059669);color:#fff;border:none;border-radius:12px;padding:14px 24px;font-size:.95rem;font-weight:700;cursor:pointer;transition:all .25s;box-shadow:0 4px 18px rgba(16,185,129,.3);width:100%;font-family:inherit;}
.fs-login-submit:hover:not(:disabled){transform:translateY(-2px);box-shadow:0 8px 28px rgba(16,185,129,.45);}
.fs-login-submit:disabled{opacity:.55;cursor:not-allowed;transform:none;}
.fs-login-switch{background:none;border:none;color:#10B981;font-size:.8rem;font-weight:600;cursor:pointer;padding:0;font-family:inherit;}
.fs-login-switch:hover{text-decoration:underline;}
</style>

<script>
var fsAjaxUrl  = '<?php echo esc_js( admin_url('admin-ajax.php') ); ?>';
var fsAuthNonce = '<?php echo esc_js( wp_create_nonce('fs_auth_nonce') ); ?>';

// ── Modal open/close ──
(function(){
    var overlay  = document.getElementById('fs-login-overlay');
    var closeBtn = document.getElementById('fs-login-close');
    var btn1 = document.getElementById('fs-signin-btn');
    var btn2 = document.getElementById('fs-signin-btn-mobile');

    function openModal(tab){
        overlay.classList.add('open');
        overlay.setAttribute('aria-hidden','false');
        document.body.style.overflow='hidden';
        if(tab) fsAuthSwitchTab(tab);
    }
    function closeModal(){
        overlay.classList.remove('open');
        overlay.setAttribute('aria-hidden','true');
        document.body.style.overflow='';
    }

    if(btn1) btn1.addEventListener('click', function(){ openModal('login'); });
    if(btn2) btn2.addEventListener('click', function(){ openModal('login'); });
    if(closeBtn) closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', function(e){ if(e.target===overlay) closeModal(); });
    document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeModal(); });

    // Tab buttons
    document.querySelectorAll('.fs-login-tab').forEach(function(tab){
        tab.addEventListener('click', function(){ fsAuthSwitchTab(tab.dataset.tab); });
    });

    // Switch links inside forms
    document.querySelectorAll('.fs-login-switch').forEach(function(btn){
        btn.addEventListener('click', function(){ fsAuthSwitchTab(btn.dataset.tab); });
    });
})();

function fsAuthSwitchTab(name){
    document.querySelectorAll('.fs-login-tab').forEach(function(t){
        t.classList.toggle('active', t.dataset.tab===name);
        t.setAttribute('aria-selected', t.dataset.tab===name ? 'true' : 'false');
    });
    document.querySelectorAll('.fs-login-panel').forEach(function(p){
        p.classList.toggle('active', p.id==='fs-tab-'+name);
    });
    var h = document.querySelector('.fs-login-modal__title');
    if(h) h.textContent = name==='login' ? 'Welcome Back' : 'Join FinanceSpots';
    var s = document.querySelector('.fs-login-modal__sub');
    if(s) s.textContent = name==='login' ? 'Sign in to your account' : 'Create your free account today';
}

// ── Password toggle ──
function fsTogglePwd(id,btn){
    var inp = document.getElementById(id);
    if(!inp) return;
    inp.type = inp.type==='password' ? 'text' : 'password';
    btn.style.color = inp.type==='text' ? '#10B981' : '';
}

function fsShowMsg(id, type, msg){
    var el = document.getElementById(id);
    if(!el) return;
    el.className = 'fs-login-msg ' + type;
    el.textContent = msg;
    el.style.display = 'block';
}

// ── LOGIN via AJAX ──
document.getElementById('fs-login-form').addEventListener('submit', function(e){
    e.preventDefault();
    var email    = document.getElementById('fl-email').value.trim();
    var password = document.getElementById('fl-password').value;
    var remember = document.getElementById('fl-remember').checked;
    var btn      = document.getElementById('fl-submit');
    var msgEl    = 'fl-msg';

    document.getElementById(msgEl).style.display='none';
    if(!email||!password){ fsShowMsg(msgEl,'error','Please fill in all fields.'); return; }
    if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){ fsShowMsg(msgEl,'error','Please enter a valid email address.'); return; }

    btn.disabled=true;
    btn.querySelector('.fs-login-submit__text').textContent='Signing in…';

    var fd = new FormData();
    fd.append('action','fs_login');
    fd.append('nonce', fsAuthNonce);
    fd.append('email', email);
    fd.append('password', password);
    fd.append('remember', remember ? '1' : '');

    fetch(fsAjaxUrl,{method:'POST',body:fd})
    .then(function(r){return r.json();})
    .then(function(res){
        if(res.success){
            fsShowMsg(msgEl,'success','&#10003; Signed in! Redirecting…');
            setTimeout(function(){ window.location.href = res.data.redirect; }, 800);
        } else {
            fsShowMsg(msgEl,'error', res.data || 'Login failed. Please try again.');
            btn.disabled=false;
            btn.querySelector('.fs-login-submit__text').textContent='Sign In';
        }
    })
    .catch(function(){ fsShowMsg(msgEl,'error','Connection error. Please try again.'); btn.disabled=false; btn.querySelector('.fs-login-submit__text').textContent='Sign In'; });
});

// ── REGISTER via AJAX ──
document.getElementById('fs-register-form').addEventListener('submit', function(e){
    e.preventDefault();
    var name     = document.getElementById('fr-name').value.trim();
    var email    = document.getElementById('fr-email').value.trim();
    var password = document.getElementById('fr-password').value;
    var btn      = document.getElementById('fr-submit');
    var msgEl    = 'fr-msg';

    document.getElementById(msgEl).style.display='none';
    if(!name||!email||!password){ fsShowMsg(msgEl,'error','Please fill in all fields.'); return; }
    if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){ fsShowMsg(msgEl,'error','Please enter a valid email address (e.g. you@gmail.com).'); return; }
    if(password.length<6){ fsShowMsg(msgEl,'error','Password must be at least 6 characters.'); return; }

    btn.disabled=true;
    btn.querySelector('.fs-login-submit__text').textContent='Creating account…';

    var fd = new FormData();
    fd.append('action','fs_register');
    fd.append('nonce', fsAuthNonce);
    fd.append('name', name);
    fd.append('email', email);
    fd.append('password', password);

    fetch(fsAjaxUrl,{method:'POST',body:fd})
    .then(function(r){return r.json();})
    .then(function(res){
        if(res.success){
            fsShowMsg(msgEl,'success','&#127881; Account created! Redirecting…');
            setTimeout(function(){ window.location.href = res.data.redirect; }, 1000);
        } else {
            fsShowMsg(msgEl,'error', res.data || 'Registration failed. Please try again.');
            btn.disabled=false;
            btn.querySelector('.fs-login-submit__text').textContent='Create Free Account';
        }
    })
    .catch(function(){ fsShowMsg(msgEl,'error','Connection error. Please try again.'); btn.disabled=false; btn.querySelector('.fs-login-submit__text').textContent='Create Free Account'; });
});
</script>
<?php endif; ?>

<!-- User menu CSS + JS (always output) -->
<style>
.fs-user-menu{position:relative;z-index:1000;}
.fs-user-menu__trigger{display:flex;align-items:center;gap:8px;background:rgba(16,185,129,.08);border:1.5px solid rgba(16,185,129,.3);border-radius:50px;padding:5px 14px 5px 5px;cursor:pointer;color:#E2E8F0;font-size:.85rem;font-weight:600;transition:all .2s;white-space:nowrap;}
.fs-user-menu__trigger:hover{background:rgba(16,185,129,.15);border-color:rgba(16,185,129,.6);}
.fs-user-menu__trigger svg{color:#94A3B8;flex-shrink:0;transition:transform .25s;}
.fs-user-menu__trigger[aria-expanded="true"] svg{transform:rotate(180deg);}
.fs-user-menu__avatar{width:30px;height:30px;border-radius:50%;object-fit:cover;border:2px solid #10B981;flex-shrink:0;}
.fs-user-menu__name--desktop{max-width:90px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
@media(max-width:768px){.fs-user-menu__name--desktop{display:none;}}

.fs-user-menu__dropdown{position:absolute;top:calc(100% + 12px);right:0;background:#151E35;border:1px solid rgba(255,255,255,.09);border-radius:18px;padding:6px;min-width:230px;box-shadow:0 24px 64px rgba(0,0,0,.65),0 0 0 1px rgba(16,185,129,.07);opacity:0;pointer-events:none;transform:translateY(-8px) scale(.97);transition:opacity .2s,transform .2s;z-index:99999;}
.fs-user-menu__dropdown.open{opacity:1;pointer-events:all;transform:translateY(0) scale(1);}

.fs-user-menu__info{display:flex;align-items:center;gap:10px;padding:12px;}
.fs-user-menu__info-avatar{width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid #10B981;flex-shrink:0;}
.fs-user-menu__info-name{font-size:.85rem;font-weight:700;color:#F1F5F9;line-height:1.3;}
.fs-user-menu__info-email{font-size:.72rem;color:#64748B;margin-top:2px;max-width:158px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.fs-user-menu__divider{height:1px;background:rgba(255,255,255,.06);margin:4px 6px;}
.fs-user-menu__item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:12px;color:#CBD5E1;font-size:.84rem;font-weight:500;text-decoration:none;transition:all .18s;width:100%;border:none;background:none;cursor:pointer;font-family:inherit;text-align:left;}
.fs-user-menu__item svg{color:#64748B;flex-shrink:0;transition:color .18s;}
.fs-user-menu__item:hover{background:rgba(16,185,129,.1);color:#10B981;}
.fs-user-menu__item:hover svg{color:#10B981;}
.fs-user-menu__item--logout:hover{background:rgba(239,68,68,.08);color:#F87171;}
.fs-user-menu__item--logout:hover svg{color:#F87171;}
.fs-user-menu__item--upgrade{background:linear-gradient(135deg,rgba(245,158,11,.08),rgba(16,185,129,.08));color:#F59E0B!important;border:1px solid rgba(245,158,11,.2);border-radius:12px;font-weight:700;}
.fs-user-menu__item--upgrade svg{color:#F59E0B!important;}
.fs-user-menu__item--upgrade:hover{background:linear-gradient(135deg,rgba(245,158,11,.15),rgba(16,185,129,.12))!important;}
</style>
<script>
(function(){
    var trigger  = document.getElementById('fs-user-trigger');
    var dropdown = document.getElementById('fs-user-dropdown');
    if(!trigger || !dropdown) return;
    trigger.addEventListener('click', function(e){
        e.stopPropagation();
        var open = dropdown.classList.contains('open');
        dropdown.classList.toggle('open');
        trigger.setAttribute('aria-expanded', String(!open));
    });
    document.addEventListener('click', function(e){
        if(!trigger.contains(e.target) && !dropdown.contains(e.target)){
            dropdown.classList.remove('open');
            trigger.setAttribute('aria-expanded','false');
        }
    });
})();
</script>

<!-- Reading progress bar -->
<div class="fs-progress-bar" id="fs-progress-bar" aria-hidden="true"></div>

<main id="main-content" class="fs-main" role="main">

<?php
/* ── Fallback nav menu ── */
function financespots_fallback_menu() {

    $blog_posts = get_posts([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 10,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    echo '<ul class="fs-nav-list">';

    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">Home</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/all-tools/' ) ) . '">Tools</a></li>';

    // Blog with sub-menu (uses theme's built-in .sub-menu system)
    echo '<li>';
    echo '<a href="' . esc_url( home_url( '/blog/' ) ) . '">';
    echo 'Blog';
    echo '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true" style="margin-left:3px;opacity:.6;"><path d="M6 9l6 6 6-6"/></svg>';
    echo '</a>';

    if ( ! empty( $blog_posts ) ) {
        echo '<ul class="sub-menu">';
        echo '<li><a href="' . esc_url( home_url( '/blog/' ) ) . '" style="color:#10B981!important;font-weight:700;">&#128240; All Blog Posts</a></li>';
        foreach ( $blog_posts as $bp ) {
            echo '<li><a href="' . esc_url( get_permalink( $bp->ID ) ) . '">' . esc_html( $bp->post_title ) . '</a></li>';
        }
        echo '</ul>';
    }
    echo '</li>';

    echo '<li><a href="' . esc_url( home_url( '/about/' ) ) . '">About Us</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/contact/' ) ) . '">Contact Us</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/pricing/' ) ) . '" style="color:#10B981!important;font-weight:700;">&#11088; PRO</a></li>';

    echo '</ul>';
}
