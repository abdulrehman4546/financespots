<?php
/**
 * FinanceSpots Theme Functions
 *
 * @package financespots
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'FINANCESPOTS_VERSION', '1.2.0' );

define( 'FINANCESPOTS_DIR', get_template_directory() );
define( 'FINANCESPOTS_URI', get_template_directory_uri() );

/* =========================================================
   1. THEME SETUP
   ========================================================= */
function financespots_setup() {
    load_theme_textdomain( 'financespots', FINANCESPOTS_DIR . '/languages' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form','comment-form','comment-list','gallery','caption','style','script' ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_image_size( 'financespots-tool-thumb', 400, 250, true );
    add_image_size( 'financespots-hero', 1920, 800, true );

    register_nav_menus([
        'primary'   => __( 'Primary Navigation', 'financespots' ),
        'footer-1'  => __( 'Footer Column 1 -- Tools', 'financespots' ),
        'footer-2'  => __( 'Footer Column 2 -- Company', 'financespots' ),
        'footer-3'  => __( 'Footer Column 3 -- Resources', 'financespots' ),
    ]);
}
add_action( 'after_setup_theme', 'financespots_setup' );

/* =========================================================
   2. ENQUEUE SCRIPTS & STYLES
   ========================================================= */
function financespots_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'financespots-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap',
        [],
        null
    );

    // Main stylesheet
    wp_enqueue_style(
        'financespots-style',
        FINANCESPOTS_URI . '/assets/css/style.css',
        [ 'financespots-fonts' ],
        FINANCESPOTS_VERSION
    );

    // Tools stylesheet (loaded on tool pages only)
    if ( is_singular('fs_tool') || is_post_type_archive('fs_tool') || is_tax('fs_tool_cat') ) {
        wp_enqueue_style(
            'financespots-tools',
            FINANCESPOTS_URI . '/assets/css/tools.css',
            [ 'financespots-style' ],
            FINANCESPOTS_VERSION
        );
    }

    // Main JS (deferred)
    wp_enqueue_script(
        'financespots-main',
        FINANCESPOTS_URI . '/assets/js/main.js',
        [],
        FINANCESPOTS_VERSION,
        true
    );

    // Localise JS with ajaxurl + customizer values
    wp_localize_script( 'financespots-main', 'financeSpots', [
        'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
        'nonce'      => wp_create_nonce( 'financespots_nonce' ),
        'siteUrl'    => esc_url( home_url() ),
        'themeUrl'   => FINANCESPOTS_URI,
    ]);

    // Comments disabled site-wide
}
add_action( 'wp_enqueue_scripts', 'financespots_scripts' );

/* =========================================================
   3. WIDGET AREAS
   ========================================================= */
function financespots_widgets_init() {
    $sidebars = [
        [
            'name'        => __( 'Sidebar', 'financespots' ),
            'id'          => 'sidebar-1',
            'description' => __( 'Main sidebar shown on blog & tool pages.', 'financespots' ),
        ],
        [
            'name'        => __( 'Homepage -- Featured Tools', 'financespots' ),
            'id'          => 'homepage-tools',
            'description' => __( 'Tool cards displayed in the Popular Tools section.', 'financespots' ),
        ],
        [
            'name'        => __( 'Footer Widget Area', 'financespots' ),
            'id'          => 'footer-widgets',
            'description' => __( 'Widgets in the footer area.', 'financespots' ),
        ],
    ];

    foreach ( $sidebars as $s ) {
        register_sidebar( array_merge( $s, [
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]));
    }
}
add_action( 'widgets_init', 'financespots_widgets_init' );

/* =========================================================
   3b. SCHEMA MARKUP
   ========================================================= */
function fs_add_schema_markup() {
    $schemas = [];

    // ── BreadcrumbList (all pages) ──────────────────────────
    $breadcrumb_items = [
        [ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url('/') ],
    ];
    if ( is_singular('fs_tool') ) {
        $tool_cats = get_the_terms( get_the_ID(), 'fs_tool_cat' );
        if ( $tool_cats && ! is_wp_error($tool_cats) ) {
            $cat = $tool_cats[0];
            $breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 2, 'name' => 'All Tools', 'item' => home_url('/all-tools/') ];
            $breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 3, 'name' => $cat->name, 'item' => get_term_link($cat) ];
            $breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 4, 'name' => get_the_title(), 'item' => get_permalink() ];
        } else {
            $breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 2, 'name' => get_the_title(), 'item' => get_permalink() ];
        }
    } elseif ( is_singular('post') ) {
        $cats = get_the_category();
        if ( $cats ) {
            $breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => home_url('/blog/') ];
            $breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => get_permalink() ];
        }
    } elseif ( is_page() ) {
        $breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 2, 'name' => get_the_title(), 'item' => get_permalink() ];
    }
    if ( count($breadcrumb_items) > 1 ) {
        $schemas[] = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $breadcrumb_items,
        ];
    }

    // ── Homepage: WebSite + Organization ────────────────────
    if ( is_front_page() ) {
        $schemas[] = [
            '@context'        => 'https://schema.org',
            '@type'           => 'WebSite',
            'name'            => get_bloginfo('name'),
            'url'             => home_url('/'),
            'description'     => get_bloginfo('description'),
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [ '@type' => 'EntryPoint', 'urlTemplate' => home_url('/?s={search_term_string}') ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
        $schemas[] = [
            '@context'   => 'https://schema.org',
            '@type'      => 'Organization',
            'name'       => 'FinanceSpots',
            'url'        => home_url('/'),
            'logo'       => home_url('/wp-content/themes/financespots/assets/images/logo.png'),
            'founder'    => [ '@type' => 'Person', 'name' => 'Abdul Rahman' ],
            'contactPoint' => [
                '@type'       => 'ContactPoint',
                'contactType' => 'customer support',
                'url'         => home_url('/contact/'),
            ],
            'sameAs' => [],
        ];
    }

    // ── Tool pages: SoftwareApplication + FAQPage ───────────
    if ( is_singular('fs_tool') ) {
        $tool_id   = get_the_ID();
        $tool_name = get_the_title();
        $tool_desc = get_the_excerpt() ?: wp_trim_words(get_the_content(), 40);
        $schemas[] = [
            '@context'            => 'https://schema.org',
            '@type'               => 'SoftwareApplication',
            'name'                => $tool_name . ' - Free Online Calculator',
            'description'         => $tool_desc,
            'url'                 => get_permalink($tool_id),
            'applicationCategory' => 'FinanceApplication',
            'operatingSystem'     => 'Web',
            'offers'              => [
                '@type'    => 'Offer',
                'price'    => '0',
                'priceCurrency' => 'USD',
                'availability' => 'https://schema.org/InStock',
            ],
            'aggregateRating' => [
                '@type'       => 'AggregateRating',
                'ratingValue' => '4.9',
                'reviewCount' => '500000',
                'bestRating'  => '5',
                'worstRating' => '1',
            ],
            'author' => [ '@type' => 'Person', 'name' => 'Abdul Rahman' ],
        ];

        // FAQPage schema from $faq_data
        require_once get_template_directory() . '/inc/calculators.php';
        $tool_type = get_post_meta($tool_id, '_fs_tool_type', true) ?: 'simple_calc';
        $faq_data_schema = [
            'mortgage_calc'       => [ ['What is a good mortgage rate in 2026?','In 2026, a competitive 30-year fixed mortgage rate is between 6.5%-7.5% depending on your credit score, down payment, and lender.'],['How much house can I afford?','Keep total housing costs (PITI) below 28% of gross monthly income, and total debt payments below 43%.'],['What is included in a mortgage payment?','A full mortgage payment (PITI) includes Principal, Interest, Property Taxes, and Homeowners Insurance.'],['Is a 30-year or 15-year mortgage better?','A 15-year mortgage saves significantly on total interest. A 30-year offers lower payments and more flexibility.'] ],
            'auto_loan_calc'      => [ ['What is a good auto loan rate in 2026?','Average auto loan rates in 2026 range from 5%-8% for new cars and 7%-12% for used cars.'],['Should I put a down payment on a car?','Yes - a 20% down payment is recommended for new cars and 10% for used cars.'],['What loan term is best for an auto loan?','36-48 months minimizes total interest. Longer terms increase total cost.'],['Does sales tax affect my auto loan?','Yes - sales tax is added to the purchase price and can be rolled into the loan.'] ],
            'personal_loan_calc'  => [ ['What credit score do I need for a personal loan?','Most lenders require a minimum score of 580-640. Best rates (below 10% APR) require 720+.'],['What is a good personal loan rate?','Rates below 10% APR are considered good. Average in 2026 is 11%-12% for good credit.'],['How is APR different from interest rate?','APR includes the interest rate plus origination fees, making it the true cost measure.'],['Can I pay off a personal loan early?','Most personal loans have no prepayment penalty.'] ],
            'loan_payoff_calc'    => [ ['How much do extra payments really save?','On a $200,000 mortgage at 7%, an extra $200/month saves about 5 years and over $60,000 in interest.'],['When is the best time to make extra payments?','Early in the loan term, because interest is front-loaded.'],['Should I pay extra on my mortgage or invest?','If your mortgage rate is higher than expected investment returns, paying extra wins.'],['Does my lender require a minimum for extra payments?','No - most lenders accept any extra amount.'] ],
        ];
        if ( isset($faq_data_schema[$tool_type]) ) {
            $faq_entities = [];
            foreach ( $faq_data_schema[$tool_type] as $faq ) {
                $faq_entities[] = [
                    '@type'          => 'Question',
                    'name'           => $faq[0],
                    'acceptedAnswer' => [ '@type' => 'Answer', 'text' => $faq[1] ],
                ];
            }
            $schemas[] = [
                '@context'   => 'https://schema.org',
                '@type'      => 'FAQPage',
                'mainEntity' => $faq_entities,
            ];
        }
    }

    // ── Blog posts: Article ──────────────────────────────────
    if ( is_singular('post') ) {
        $author_id  = get_post_field('post_author', get_the_ID());
        $author     = get_the_author_meta('display_name', $author_id) ?: 'Abdul Rahman';
        $schemas[] = [
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => get_the_title(),
            'description'   => get_the_excerpt() ?: wp_trim_words(get_the_content(), 40),
            'url'           => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified'  => get_the_modified_date('c'),
            'author'        => [ '@type' => 'Person', 'name' => $author ],
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => 'FinanceSpots',
                'url'   => home_url('/'),
            ],
            'mainEntityOfPage' => [ '@type' => 'WebPage', '@id' => get_permalink() ],
        ];
    }

    // ── Pricing page: Product ────────────────────────────────
    if ( is_page('pricing') || ( is_page() && get_page_template_slug() === 'page-pricing.php' ) ) {
        $schemas[] = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => 'FinanceSpots PRO',
            'description' => 'Professional-grade financial calculators and tools with advanced features, save functionality, and ad-free experience.',
            'url'         => home_url('/pricing/'),
            'brand'       => [ '@type' => 'Brand', 'name' => 'FinanceSpots' ],
            'offers'      => [
                [
                    '@type'         => 'Offer',
                    'name'          => 'Free Plan',
                    'price'         => '0',
                    'priceCurrency' => 'USD',
                    'availability'  => 'https://schema.org/InStock',
                    'url'           => home_url('/pricing/'),
                ],
                [
                    '@type'         => 'Offer',
                    'name'          => 'PRO Monthly',
                    'price'         => '9',
                    'priceCurrency' => 'USD',
                    'billingIncrement' => 'monthly',
                    'availability'  => 'https://schema.org/InStock',
                    'url'           => home_url('/pricing/'),
                ],
                [
                    '@type'         => 'Offer',
                    'name'          => 'PRO Lifetime',
                    'price'         => '199',
                    'priceCurrency' => 'USD',
                    'availability'  => 'https://schema.org/InStock',
                    'url'           => home_url('/pricing/'),
                ],
            ],
            'aggregateRating' => [
                '@type'       => 'AggregateRating',
                'ratingValue' => '4.9',
                'reviewCount' => '500000',
                'bestRating'  => '5',
            ],
        ];
    }

    // ── About page ───────────────────────────────────────────
    if ( is_page('about') || ( is_page() && get_page_template_slug() === 'page-about.php' ) ) {
        $schemas[] = [
            '@context'    => 'https://schema.org',
            '@type'       => 'AboutPage',
            'name'        => 'About FinanceSpots',
            'description' => 'FinanceSpots is a free platform built by Abdul Rahman to give everyone access to professional-grade financial tools and education.',
            'url'         => home_url('/about/'),
            'mainEntity'  => [
                '@type'  => 'Organization',
                'name'   => 'FinanceSpots',
                'url'    => home_url('/'),
                'founder' => [ '@type' => 'Person', 'name' => 'Abdul Rahman', 'email' => 'chabdulrehman4546@gmail.com' ],
            ],
        ];
    }

    // Output all schemas
    if ( ! empty($schemas) ) {
        foreach ( $schemas as $schema ) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
        }
    }
}
add_action( 'wp_head', 'fs_add_schema_markup', 5 );

/* =========================================================
   4. CUSTOMIZER API
   ========================================================= */
function financespots_customize_register( WP_Customize_Manager $wp_customize ) {

    // ── Helper: add a section ──────────────────────────────────
    $add_section = function( $id, $title, $priority = 30 ) use ( $wp_customize ) {
        $wp_customize->add_section( $id, [ 'title' => $title, 'priority' => $priority ] );
    };

    // ── Helper: text setting + control ────────────────────────
    $text = function( $id, $section, $label, $default = '', $type = 'text' ) use ( $wp_customize ) {
        $wp_customize->add_setting( $id, [
            'default'           => $default,
            'sanitize_callback' => $type === 'url' ? 'esc_url_raw' : 'sanitize_text_field',
            'transport'         => 'postMessage',
        ]);
        $wp_customize->add_control( $id, [
            'label'   => $label,
            'section' => $section,
            'type'    => $type === 'url' ? 'url' : 'text',
        ]);
    };

    // ── Helper: textarea setting + control ────────────────────
    $textarea = function( $id, $section, $label, $default = '' ) use ( $wp_customize ) {
        $wp_customize->add_setting( $id, [
            'default'           => $default,
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage',
        ]);
        $wp_customize->add_control( $id, [
            'label'   => $label,
            'section' => $section,
            'type'    => 'textarea',
        ]);
    };

    // ── Helper: color setting + control ───────────────────────
    $color = function( $id, $section, $label, $default = '#000000' ) use ( $wp_customize ) {
        $wp_customize->add_setting( $id, [
            'default'           => $default,
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ]);
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, [
            'label'   => $label,
            'section' => $section,
        ]));
    };

    /* === COLORS SECTION === */
    $add_section( 'financespots_colors', __( 'FinanceSpots -- Colors', 'financespots' ), 20 );
    $color( 'fs_color_primary',    'financespots_colors', __( 'Primary Accent Color', 'financespots' ),    '#00C896' );
    $color( 'fs_color_secondary',  'financespots_colors', __( 'Secondary Accent Color', 'financespots' ),  '#3B82F6' );
    $color( 'fs_color_gold',       'financespots_colors', __( 'Gold Accent Color', 'financespots' ),       '#F59E0B' );
    $color( 'fs_color_bg',         'financespots_colors', __( 'Background Color', 'financespots' ),        '#F8FAFC' );
    $color( 'fs_color_text',       'financespots_colors', __( 'Body Text Color', 'financespots' ),         '#1E293B' );
    $color( 'fs_color_card_bg',    'financespots_colors', __( 'Card Background Color', 'financespots' ),   '#FFFFFF' );

    /* === HERO SECTION === */
    /* === NAVBAR SECTION (new) === */
    $add_section( 'financespots_navbar', __( 'FinanceSpots -- Navbar Buttons', 'financespots' ), 25 );
    $text( 'fs_signin_label',   'financespots_navbar', __( 'Sign In Button Label', 'financespots' ),        __( 'Sign In', 'financespots' ) );
    $text( 'fs_signin_url',     'financespots_navbar', __( 'Sign In URL', 'financespots' ),                 '/wp-login.php', 'url' );
    $text( 'fs_cta_nav_label',  'financespots_navbar', __( 'Get Started Button Label', 'financespots' ),    __( 'Get Started Free', 'financespots' ) );
    $text( 'fs_cta_nav_url',    'financespots_navbar', __( 'Get Started Button URL', 'financespots' ),      '#tools', 'url' );

    /* === TICKER BAR ANIMATION CONTROLS === */
    $add_section( 'financespots_ticker', __( 'FinanceSpots -- Ticker Bar', 'financespots' ), 26 );

    // Show/Hide ticker
    $wp_customize->add_setting( 'fs_ticker_enable', [ 'default' => '1', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_ticker_enable', [
        'label'   => __( 'Show Ticker Bar', 'financespots' ),
        'section' => 'financespots_ticker',
        'type'    => 'checkbox',
    ] );

    // Animation enable
    $wp_customize->add_setting( 'fs_ticker_anim_enable', [ 'default' => '1', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_ticker_anim_enable', [
        'label'   => __( 'Enable Scroll Animation', 'financespots' ),
        'section' => 'financespots_ticker',
        'type'    => 'checkbox',
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_ticker_enable', '1' ); },
    ] );

    // Animation Type
    $wp_customize->add_setting( 'fs_ticker_anim_type', [ 'default' => 'scroll', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_ticker_anim_type', [
        'label'   => __( 'Scroll Direction / Type', 'financespots' ),
        'section' => 'financespots_ticker',
        'type'    => 'select',
        'choices' => [
            'scroll'        => __( 'Scroll Left (Default)', 'financespots' ),
            'scroll_right'  => __( 'Scroll Right', 'financespots' ),
            'fade'          => __( 'Fade In/Out (No Scroll)', 'financespots' ),
            'bounce'        => __( 'Bounce Left', 'financespots' ),
        ],
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_ticker_enable', '1' ) && (bool) get_theme_mod( 'fs_ticker_anim_enable', '1' ); },
    ] );

    // Speed
    $wp_customize->add_setting( 'fs_ticker_speed', [ 'default' => '240', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_ticker_speed', [
        'label'       => __( 'Scroll Speed (seconds for 1 full loop)', 'financespots' ),
        'description' => __( 'Lower = faster. Range: 30-600s', 'financespots' ),
        'section'     => 'financespots_ticker',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 30, 'max' => 600, 'step' => 10 ],
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_ticker_enable', '1' ) && (bool) get_theme_mod( 'fs_ticker_anim_enable', '1' ); },
    ] );

    // Pause on hover
    $wp_customize->add_setting( 'fs_ticker_pause_hover', [ 'default' => '1', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_ticker_pause_hover', [
        'label'   => __( 'Pause on Mouse Hover', 'financespots' ),
        'section' => 'financespots_ticker',
        'type'    => 'checkbox',
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_ticker_enable', '1' ) && (bool) get_theme_mod( 'fs_ticker_anim_enable', '1' ); },
    ] );

    // Background color
    $wp_customize->add_setting( 'fs_ticker_bg', [ 'default' => '#090E1C', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'fs_ticker_bg', [
        'label'   => __( 'Ticker Background Color', 'financespots' ),
        'section' => 'financespots_ticker',
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_ticker_enable', '1' ); },
    ] ) );

    // Text color
    $wp_customize->add_setting( 'fs_ticker_text_color', [ 'default' => '#CBD5E1', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'fs_ticker_text_color', [
        'label'   => __( 'Ticker Text Color', 'financespots' ),
        'section' => 'financespots_ticker',
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_ticker_enable', '1' ); },
    ] ) );

    // Font size
    $wp_customize->add_setting( 'fs_ticker_font_size', [ 'default' => '13', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_ticker_font_size', [
        'label'       => __( 'Font Size (px)', 'financespots' ),
        'section'     => 'financespots_ticker',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 10, 'max' => 20, 'step' => 1 ],
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_ticker_enable', '1' ); },
    ] );

    // Ticker height
    $wp_customize->add_setting( 'fs_ticker_height', [ 'default' => '36', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_ticker_height', [
        'label'       => __( 'Ticker Bar Height (px)', 'financespots' ),
        'section'     => 'financespots_ticker',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 28, 'max' => 60, 'step' => 2 ],
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_ticker_enable', '1' ); },
    ] );

    /* === HERO SECTION (updated) === */
    $add_section( 'financespots_hero', __( 'FinanceSpots -- Hero Section', 'financespots' ), 30 );
    $text(     'fs_hero_badge',    'financespots_hero', __( 'Badge Text', 'financespots' ),          __( 'AI-POWERED FINANCE PLATFORM 2026', 'financespots' ) );
    $text(     'fs_hero_title_1',  'financespots_hero', __( 'Headline Line 1', 'financespots' ),     __( 'Smart Financial', 'financespots' ) );
    $text(     'fs_hero_title_2',  'financespots_hero', __( 'Headline Line 2', 'financespots' ),     __( 'Tools', 'financespots' ) );
    $text(     'fs_hero_title_3',  'financespots_hero', __( 'Headline Accent Word (blue)', 'financespots' ), __( 'Intelligent', 'financespots' ) );
    $text(     'fs_hero_title_4',  'financespots_hero', __( 'Headline Line 4', 'financespots' ),     __( 'Investing', 'financespots' ) );
    $textarea( 'fs_hero_sub',      'financespots_hero', __( 'Sub-headline', 'financespots' ),        __( 'Professional-grade financial tools powered by AI -- from portfolio analysis to tax optimization. 150+ tools, completely free.', 'financespots' ) );
    $text(     'fs_hero_cta_text', 'financespots_hero', __( 'Primary CTA Text', 'financespots' ),   __( 'Explore All Tools', 'financespots' ) );
    $text(     'fs_hero_cta_url',  'financespots_hero', __( 'Primary CTA URL', 'financespots' ),    '#tools', 'url' );
    $text(     'fs_hero_cta2_text','financespots_hero', __( 'Secondary CTA Text', 'financespots' ), __( 'Watch Demo', 'financespots' ) );
    $text(     'fs_hero_cta2_url', 'financespots_hero', __( 'Secondary CTA URL', 'financespots' ),  '#how-it-works', 'url' );

    /* === PORTFOLIO CARD (new) === */
    $add_section( 'financespots_portfolio', __( 'FinanceSpots -- Portfolio Card', 'financespots' ), 32 );
    $text( 'fs_portfolio_label',   'financespots_portfolio', __( 'Card Label', 'financespots' ),           __( 'PORTFOLIO OVERVIEW', 'financespots' ) );
    $text( 'fs_portfolio_total',   'financespots_portfolio', __( 'Portfolio Total Value', 'financespots' ), '248420' );
    $text( 'fs_portfolio_change',  'financespots_portfolio', __( 'Change Text', 'financespots' ),          __( '+$18,420 this month (+8.2%)', 'financespots' ) );

    /* --- Portfolio Card Animation Controls --- */
    // Enable/Disable animation
    $wp_customize->add_setting( 'fs_card_anim_enable', [ 'default' => '1', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_card_anim_enable', [
        'label'   => __( 'Enable Card Animation', 'financespots' ),
        'section' => 'financespots_portfolio',
        'type'    => 'checkbox',
    ] );

    // Animation Type
    $wp_customize->add_setting( 'fs_card_anim_type', [ 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_card_anim_type', [
        'label'   => __( 'Animation Type', 'financespots' ),
        'section' => 'financespots_portfolio',
        'type'    => 'select',
        'choices' => [
            'none'   => __( 'No Animation (Static)', 'financespots' ),
            'float'  => __( 'Float (Up & Down)', 'financespots' ),
            'pulse'  => __( 'Pulse (Scale In/Out)', 'financespots' ),
            'glow'   => __( 'Glow (Border Glow)', 'financespots' ),
            'tilt'   => __( 'Tilt (Gentle Rotate)', 'financespots' ),
            'shimmer'=> __( 'Shimmer (Light Sweep)', 'financespots' ),
        ],
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_card_anim_enable', '1' ); },
    ] );

    // Animation Speed
    $wp_customize->add_setting( 'fs_card_anim_speed', [ 'default' => '6', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_card_anim_speed', [
        'label'       => __( 'Animation Speed (seconds)', 'financespots' ),
        'description' => __( 'Lower = faster. Range: 2-20s', 'financespots' ),
        'section'     => 'financespots_portfolio',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 2, 'max' => 20, 'step' => 1 ],
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_card_anim_enable', '1' ) && get_theme_mod( 'fs_card_anim_type', 'none' ) !== 'none'; },
    ] );

    // Float height (only for float type)
    $wp_customize->add_setting( 'fs_card_anim_float_px', [ 'default' => '8', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_card_anim_float_px', [
        'label'       => __( 'Float Height (px)', 'financespots' ),
        'description' => __( 'How many pixels the card moves up. Range: 2-30', 'financespots' ),
        'section'     => 'financespots_portfolio',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 2, 'max' => 30, 'step' => 1 ],
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_card_anim_enable', '1' ) && get_theme_mod( 'fs_card_anim_type', 'none' ) === 'float'; },
    ] );

    /* --- Floating Badge 1 (ROI up 24%) --- */
    $wp_customize->add_setting( 'fs_badge1_enable', [ 'default' => '1', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_badge1_enable', [
        'label'   => __( 'Badge 1: Show (ROI badge)', 'financespots' ),
        'section' => 'financespots_portfolio',
        'type'    => 'checkbox',
    ] );
    $wp_customize->add_setting( 'fs_badge1_text', [ 'default' => 'ROI up 24%', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ] );
    $wp_customize->add_control( 'fs_badge1_text', [
        'label'   => __( 'Badge 1: Text', 'financespots' ),
        'section' => 'financespots_portfolio',
        'type'    => 'text',
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_badge1_enable', '1' ); },
    ] );
    $wp_customize->add_setting( 'fs_badge1_url', [ 'default' => '#tools', 'sanitize_callback' => 'esc_url_raw', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_badge1_url', [
        'label'   => __( 'Badge 1: Link URL', 'financespots' ),
        'section' => 'financespots_portfolio',
        'type'    => 'url',
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_badge1_enable', '1' ); },
    ] );

    /* --- Floating Badge 3 (Bank-level Security) --- */
    $wp_customize->add_setting( 'fs_badge3_enable', [ 'default' => '1', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_badge3_enable', [
        'label'   => __( 'Badge 2: Show (Security badge)', 'financespots' ),
        'section' => 'financespots_portfolio',
        'type'    => 'checkbox',
    ] );
    $wp_customize->add_setting( 'fs_badge3_text', [ 'default' => 'Bank-level Security', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ] );
    $wp_customize->add_control( 'fs_badge3_text', [
        'label'   => __( 'Badge 2: Text', 'financespots' ),
        'section' => 'financespots_portfolio',
        'type'    => 'text',
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_badge3_enable', '1' ); },
    ] );
    $wp_customize->add_setting( 'fs_badge3_url', [ 'default' => '#security', 'sanitize_callback' => 'esc_url_raw', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_badge3_url', [
        'label'   => __( 'Badge 2: Link URL', 'financespots' ),
        'section' => 'financespots_portfolio',
        'type'    => 'url',
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_badge3_enable', '1' ); },
    ] );

    /* --- Badge 1 Animation --- */
    $badge_anim_choices = [
        'none'   => __( 'No Animation (Static)', 'financespots' ),
        'float'  => __( 'Float (Up & Down)', 'financespots' ),
        'pulse'  => __( 'Pulse (Scale In/Out)', 'financespots' ),
        'shake'  => __( 'Shake (Left/Right)', 'financespots' ),
        'glow'   => __( 'Glow (Border Glow)', 'financespots' ),
        'bounce' => __( 'Bounce', 'financespots' ),
        'spin'   => __( 'Spin (Slow Rotate)', 'financespots' ),
    ];

    $wp_customize->add_setting( 'fs_badge1_anim_type', [ 'default' => 'float', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_badge1_anim_type', [
        'label'   => __( 'Badge 1: Animation Type', 'financespots' ),
        'section' => 'financespots_portfolio',
        'type'    => 'select',
        'choices' => $badge_anim_choices,
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_badge1_enable', '1' ); },
    ] );
    $wp_customize->add_setting( 'fs_badge1_anim_speed', [ 'default' => '9', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_badge1_anim_speed', [
        'label'       => __( 'Badge 1: Animation Speed (seconds)', 'financespots' ),
        'description' => __( 'Lower = faster. Range: 1-20s', 'financespots' ),
        'section'     => 'financespots_portfolio',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 1, 'max' => 20, 'step' => 1 ],
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_badge1_enable', '1' ) && get_theme_mod( 'fs_badge1_anim_type', 'float' ) !== 'none'; },
    ] );

    /* --- Badge 2 (Security) Animation --- */
    $wp_customize->add_setting( 'fs_badge3_anim_type', [ 'default' => 'float', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_badge3_anim_type', [
        'label'   => __( 'Badge 2: Animation Type', 'financespots' ),
        'section' => 'financespots_portfolio',
        'type'    => 'select',
        'choices' => $badge_anim_choices,
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_badge3_enable', '1' ); },
    ] );
    $wp_customize->add_setting( 'fs_badge3_anim_speed', [ 'default' => '9', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'fs_badge3_anim_speed', [
        'label'       => __( 'Badge 2: Animation Speed (seconds)', 'financespots' ),
        'description' => __( 'Lower = faster. Range: 1-20s', 'financespots' ),
        'section'     => 'financespots_portfolio',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 1, 'max' => 20, 'step' => 1 ],
        'active_callback' => function() { return (bool) get_theme_mod( 'fs_badge3_enable', '1' ) && get_theme_mod( 'fs_badge3_anim_type', 'float' ) !== 'none'; },
    ] );

    /* === STATS SECTION === */
    $add_section( 'financespots_stats', __( 'FinanceSpots -- Stats/Counters', 'financespots' ), 40 );
    $text( 'fs_stat1_number', 'financespots_stats', __( 'Stat 1 Number', 'financespots' ),  '2500000' );
    $text( 'fs_stat1_label',  'financespots_stats', __( 'Stat 1 Label', 'financespots' ),   __( 'Calculations Done', 'financespots' ) );
    $text( 'fs_stat2_number', 'financespots_stats', __( 'Stat 2 Number', 'financespots' ),  '150' );
    $text( 'fs_stat2_label',  'financespots_stats', __( 'Stat 2 Label', 'financespots' ),   __( 'Finance Tools', 'financespots' ) );
    $text( 'fs_stat3_number', 'financespots_stats', __( 'Stat 3 Number', 'financespots' ),  '500000' );
    $text( 'fs_stat3_label',  'financespots_stats', __( 'Stat 3 Label', 'financespots' ),   __( 'Active Users', 'financespots' ) );
    $text( 'fs_stat4_number', 'financespots_stats', __( 'Stat 4 Number', 'financespots' ),  '99' );
    $text( 'fs_stat4_label',  'financespots_stats', __( 'Stat 4 Label', 'financespots' ),   __( '% Accuracy Rate', 'financespots' ) );

    /* === AI SECTION === */
    $add_section( 'financespots_ai', __( 'FinanceSpots -- AI Section', 'financespots' ), 50 );
    $text(     'fs_ai_badge',    'financespots_ai', __( 'AI Badge Text', 'financespots' ),   __( 'AI-Powered Intelligence', 'financespots' ) );
    $text(     'fs_ai_title',    'financespots_ai', __( 'Section Title', 'financespots' ),   __( 'Smarter Finance Decisions with AI', 'financespots' ) );
    $textarea( 'fs_ai_desc',     'financespots_ai', __( 'Section Description', 'financespots' ), __( 'Our AI engine analyzes thousands of data points to give you personalized financial insights, optimize calculations, and suggest the best strategies for your goals.', 'financespots' ) );

    /* === NEWSLETTER SECTION === */
    $add_section( 'financespots_newsletter', __( 'FinanceSpots -- Newsletter CTA', 'financespots' ), 60 );
    $text(     'fs_nl_title',  'financespots_newsletter', __( 'Section Title', 'financespots' ),    __( 'Stay Ahead of Your Finances', 'financespots' ) );
    $textarea( 'fs_nl_desc',   'financespots_newsletter', __( 'Description', 'financespots' ),      __( 'Get weekly finance tips, tool updates, and market insights delivered straight to your inbox.', 'financespots' ) );
    $text(     'fs_nl_btn',    'financespots_newsletter', __( 'Button Text', 'financespots' ),       __( 'Subscribe Free', 'financespots' ) );
    $text(     'fs_nl_action', 'financespots_newsletter', __( 'Form Action URL', 'financespots' ),  '#', 'url' );

    /* === FOOTER === */
    $add_section( 'financespots_footer', __( 'FinanceSpots -- Footer', 'financespots' ), 70 );
    $textarea( 'fs_footer_about',     'financespots_footer', __( 'About Text', 'financespots' ),   __( 'FinanceSpots is your all-in-one destination for free, AI-powered financial tools, calculators, and insights.', 'financespots' ) );
    $text(     'fs_footer_copyright', 'financespots_footer', __( 'Copyright Text', 'financespots' ), __( '© 2026 FinanceSpots. All rights reserved.', 'financespots' ) );
    $text(     'fs_social_twitter',   'financespots_footer', __( 'Twitter/X URL', 'financespots' ),  '#', 'url' );
    $text(     'fs_social_linkedin',  'financespots_footer', __( 'LinkedIn URL', 'financespots' ),   '#', 'url' );
    $text(     'fs_social_youtube',   'financespots_footer', __( 'YouTube URL', 'financespots' ),    '#', 'url' );
    $text(     'fs_social_facebook',  'financespots_footer', __( 'Facebook URL', 'financespots' ),   '#', 'url' );
}
add_action( 'customize_register', 'financespots_customize_register' );

/* =========================================================
   5. CUSTOMIZER POSTMESSAGE JS
   ========================================================= */
function financespots_customize_preview_js() {
    wp_enqueue_script(
        'financespots-customizer',
        FINANCESPOTS_URI . '/assets/js/customizer.js',
        [ 'customize-preview' ],
        FINANCESPOTS_VERSION,
        true
    );
}
add_action( 'customize_preview_init', 'financespots_customize_preview_js' );

/* =========================================================
   6. DYNAMIC CSS FROM CUSTOMIZER VALUES
   ========================================================= */
function financespots_dynamic_css() {
    $primary   = get_theme_mod( 'fs_color_primary',   '#00C896' );
    $secondary = get_theme_mod( 'fs_color_secondary', '#3B82F6' );
    $gold      = get_theme_mod( 'fs_color_gold',      '#F59E0B' );
    $bg        = get_theme_mod( 'fs_color_bg',        '#F8FAFC' );
    $text      = get_theme_mod( 'fs_color_text',      '#1E293B' );
    $card_bg   = get_theme_mod( 'fs_color_card_bg',   '#FFFFFF' );

    $css = "
    :root {
        --fs-primary:   {$primary};
        --fs-secondary: {$secondary};
        --fs-gold:      {$gold};
        --fs-bg:        {$bg};
        --fs-text:      {$text};
        --fs-card-bg:   {$card_bg};
    }
    ";

    // Ticker bar
    $ticker_enable      = get_theme_mod( 'fs_ticker_enable',       '1' );
    $ticker_anim_enable = get_theme_mod( 'fs_ticker_anim_enable',  '1' );
    $ticker_anim_type   = get_theme_mod( 'fs_ticker_anim_type',    'scroll' );
    $ticker_speed       = (int) get_theme_mod( 'fs_ticker_speed',  240 );
    $ticker_pause       = get_theme_mod( 'fs_ticker_pause_hover',  '1' );
    $ticker_bg          = get_theme_mod( 'fs_ticker_bg',           '#090E1C' );
    $ticker_text        = get_theme_mod( 'fs_ticker_text_color',   '#CBD5E1' );
    $ticker_font        = (int) get_theme_mod( 'fs_ticker_font_size', 13 );
    $ticker_height      = (int) get_theme_mod( 'fs_ticker_height', 36 );

    if ( ! $ticker_enable ) {
        $css .= ".fs-ticker { display:none !important; }\n";
    } else {
        $css .= ":root { --fs-ticker-h:{$ticker_height}px; --fs-ticker-bg:{$ticker_bg}; }\n";
        $css .= ".fs-ticker { background:{$ticker_bg}; }\n";
        $css .= ".fs-ticker__symbol { color:{$ticker_text}; font-size:{$ticker_font}px; }\n";
        $css .= ".fs-ticker__price  { color:#fff; font-size:{$ticker_font}px; }\n";
        $css .= ".fs-ticker__change { font-size:{$ticker_font}px; }\n";

        if ( $ticker_anim_enable ) {
            if ( $ticker_anim_type === 'scroll' ) {
                $css .= ".fs-ticker__track { animation: fs_ticker_scroll {$ticker_speed}s linear infinite !important; }\n";
                $css .= "@keyframes fs_ticker_scroll { 0%{ transform:translateX(0); } 100%{ transform:translateX(-50%); } }\n";
            } elseif ( $ticker_anim_type === 'scroll_right' ) {
                $css .= ".fs-ticker__track { animation: fs_ticker_scrollr {$ticker_speed}s linear infinite !important; }\n";
                $css .= "@keyframes fs_ticker_scrollr { 0%{ transform:translateX(-50%); } 100%{ transform:translateX(0); } }\n";
            } elseif ( $ticker_anim_type === 'fade' ) {
                $css .= ".fs-ticker__track { display:flex; animation:none !important; flex-wrap:wrap; justify-content:center; }\n";
                $css .= ".fs-ticker__item { animation: fs_ticker_fade {$ticker_speed}s ease-in-out infinite; }\n";
                $css .= ".fs-ticker__item:nth-child(2n) { animation-delay:.5s; }\n";
                $css .= "@keyframes fs_ticker_fade { 0%,100%{ opacity:.4; } 50%{ opacity:1; } }\n";
            } elseif ( $ticker_anim_type === 'bounce' ) {
                $css .= ".fs-ticker__track { animation: fs_ticker_bounce {$ticker_speed}s ease-in-out infinite alternate !important; }\n";
                $css .= "@keyframes fs_ticker_bounce { 0%{ transform:translateX(0); } 100%{ transform:translateX(-50%); } }\n";
            }

            if ( $ticker_pause ) {
                $css .= ".fs-ticker:hover .fs-ticker__track { animation-play-state:paused !important; }\n";
            }
        } else {
            $css .= ".fs-ticker__track { animation:none !important; }\n";
        }
    }

    // Badge animations
    $badge_keyframes = [
        'float'  => "@keyframes NAME { 0%,100%{ transform:translateY(0); } 50%{ transform:translateY(-7px); } }",
        'pulse'  => "@keyframes NAME { 0%,100%{ transform:scale(1); } 50%{ transform:scale(1.1); } }",
        'shake'  => "@keyframes NAME { 0%,100%{ transform:translateX(0); } 25%{ transform:translateX(-5px); } 75%{ transform:translateX(5px); } }",
        'glow'   => "@keyframes NAME { 0%,100%{ box-shadow:0 8px 32px rgba(0,0,0,.40); } 50%{ box-shadow:0 8px 32px rgba(0,0,0,.40),0 0 18px rgba(0,200,150,.55); } }",
        'bounce' => "@keyframes NAME { 0%,100%{ transform:translateY(0); } 40%{ transform:translateY(-12px); } 60%{ transform:translateY(-6px); } }",
        'spin'   => "@keyframes NAME { 0%{ transform:rotate(0deg); } 100%{ transform:rotate(360deg); } }",
    ];
    foreach ( [
        [ 'fs_badge1_anim_type', 'fs_badge1_anim_speed', 'fs_badge1_enable', '.fs-float-badge--1', 'fs_b1' ],
        [ 'fs_badge3_anim_type', 'fs_badge3_anim_speed', 'fs_badge3_enable', '.fs-float-badge--3', 'fs_b3' ],
    ] as $b ) {
        [ $type_key, $speed_key, $enable_key, $selector, $name ] = $b;
        if ( ! get_theme_mod( $enable_key, '1' ) ) continue;
        $btype  = get_theme_mod( $type_key, 'float' );
        $bspeed = (int) get_theme_mod( $speed_key, 9 );
        if ( $btype === 'none' ) {
            $css .= "{$selector} { animation:none !important; }\n";
        } else {
            $css .= "{$selector} { animation:{$name} {$bspeed}s ease-in-out infinite !important; }\n";
            if ( isset( $badge_keyframes[ $btype ] ) ) {
                $css .= str_replace( 'NAME', $name, $badge_keyframes[ $btype ] ) . "\n";
            }
        }
    }

    // Portfolio card animation
    $anim_enable = get_theme_mod( 'fs_card_anim_enable', '1' );
    $anim_type   = get_theme_mod( 'fs_card_anim_type',   'none' );
    $anim_speed  = (int) get_theme_mod( 'fs_card_anim_speed',   6 );
    $anim_float  = (int) get_theme_mod( 'fs_card_anim_float_px', 8 );

    if ( $anim_enable && $anim_type !== 'none' ) {
        $anim_name = 'fs_card_' . $anim_type;
        $css .= ".fs-portfolio-card { animation:{$anim_name} {$anim_speed}s ease-in-out infinite !important; }\n";
        if ( $anim_type === 'float' ) {
            $css .= "@keyframes fs_card_float { 0%,100%{ transform:translateY(0); } 50%{ transform:translateY(-{$anim_float}px); } }\n";
        } elseif ( $anim_type === 'pulse' ) {
            $css .= "@keyframes fs_card_pulse { 0%,100%{ transform:scale(1); } 50%{ transform:scale(1.025); } }\n";
        } elseif ( $anim_type === 'glow' ) {
            $css .= "@keyframes fs_card_glow { 0%,100%{ box-shadow:0 30px 80px rgba(0,0,0,.60),0 0 0 1px rgba(255,255,255,.06) inset; } 50%{ box-shadow:0 30px 80px rgba(0,0,0,.60),0 0 40px rgba(0,200,150,.35),0 0 0 1px rgba(0,200,150,.3) inset; } }\n";
        } elseif ( $anim_type === 'tilt' ) {
            $css .= "@keyframes fs_card_tilt { 0%,100%{ transform:rotate(0deg); } 25%{ transform:rotate(.4deg); } 75%{ transform:rotate(-.4deg); } }\n";
        } elseif ( $anim_type === 'shimmer' ) {
            $css .= ".fs-portfolio-card { overflow:hidden; }\n";
            $css .= ".fs-portfolio-card::after { content:''; position:absolute; inset:0; border-radius:20px; background:linear-gradient(105deg,transparent 40%,rgba(255,255,255,.07) 50%,transparent 60%); background-size:200% 100%; animation:fs_card_shimmer {$anim_speed}s linear infinite !important; pointer-events:none; z-index:1; }\n";
            $css .= "@keyframes fs_card_shimmer { 0%{ background-position:200% 0; } 100%{ background-position:-200% 0; } }\n";
        }
    } else {
        $css .= ".fs-portfolio-card { animation:none !important; }\n";
    }

    wp_add_inline_style( 'financespots-style', $css );
}
add_action( 'wp_enqueue_scripts', 'financespots_dynamic_css', 20 );

/* =========================================================
   7. OPEN GRAPH + SCHEMA MARKUP
   ========================================================= */
/* Returns FAQPage schema array for a given tool type */
function fs_get_tool_faq_schema( $tool_type ) {
    $faqs = [
        'mortgage_calc'     => [
            ['q'=>'What is a good mortgage rate in 2026?',         'a'=>'A competitive 30-year fixed rate in 2026 is 6.5%-7.5%. 15-year fixed rates are typically 0.5-1% lower. Your rate depends on credit score, down payment, and lender.'],
            ['q'=>'How much house can I afford?',                  'a'=>'Keep total housing costs (PITI) under 28% of gross monthly income. Total debt payments should stay below 43% DTI for conventional loan qualification.'],
            ['q'=>'What does PITI stand for in a mortgage?',       'a'=>'PITI = Principal, Interest, Taxes, Insurance. It is the true monthly cost of homeownership beyond just the loan payment itself.'],
        ],
        'auto_loan_calc'    => [
            ['q'=>'What is a good auto loan rate in 2026?',        'a'=>'Rates of 5%-8% for new cars and 7%-12% for used are typical in 2026. Below 6% on a new car is considered excellent credit pricing.'],
            ['q'=>'How much should I put down on a car?',          'a'=>'A 20% down payment for new cars and 10% for used cars is recommended to avoid negative equity (owing more than the car is worth).'],
            ['q'=>'What loan term is best for an auto loan?',      'a'=>'36-48 months minimizes total interest. Terms over 60 months reduce monthly payments but increase total cost and risk of negative equity.'],
        ],
        'personal_loan_calc'=> [
            ['q'=>'What credit score do I need for a personal loan?','a'=>'Most lenders require 580-640 minimum. For rates below 10% APR, you typically need a score of 720 or higher.'],
            ['q'=>'What is the difference between APR and interest rate?','a'=>'APR includes both the interest rate and origination fees, giving you the true annual cost of borrowing. Always compare APRs, not just rates.'],
            ['q'=>'Can I pay off a personal loan early?',          'a'=>'Most personal loans have no prepayment penalty. Paying extra reduces total interest significantly, especially early in the term.'],
        ],
        'student_loan_calc' => [
            ['q'=>'What are federal student loan rates for 2024-25?','a'=>'6.53% for undergrad Direct Loans, 8.08% for grad Direct Loans, and 9.08% for PLUS Loans.'],
            ['q'=>'What is the student loan grace period?',        'a'=>'Most federal loans offer 6 months after graduation before payments begin. Interest accrues on unsubsidized loans during this period.'],
            ['q'=>'Should I choose standard or income-driven repayment?','a'=>'Standard 10-year pays off fastest and costs least interest. Income-Driven plans offer lower payments but extend the term and increase total interest.'],
        ],
        'heloc_calc'        => [
            ['q'=>'How much equity can I borrow?',                 'a'=>'Most lenders allow up to 80-85% combined LTV. With an $85% LTV on a $500k home with a $300k mortgage, you can borrow up to $125,000.'],
            ['q'=>'What is the difference between HELOC and home equity loan?','a'=>'A home equity loan gives a fixed-rate lump sum. A HELOC is a revolving variable-rate credit line -- like a credit card secured by your home.'],
            ['q'=>'Is home equity loan interest tax deductible?',  'a'=>'It may be deductible if used to buy, build, or improve the home. Consult a tax advisor for your situation.'],
        ],
        'fha_loan_calc'     => [
            ['q'=>'What is FHA Mortgage Insurance Premium (MIP)?','a'=>'FHA requires a 1.75% upfront MIP (rolled into the loan) and annual MIP of 0.55%-1.05% monthly based on loan size and LTV.'],
            ['q'=>'How long do I pay MIP on an FHA loan?',        'a'=>'With under 10% down, MIP lasts the life of the loan. With 10%+ down, it cancels after 11 years.'],
            ['q'=>'What is the minimum credit score for FHA?',    'a'=>'580 for 3.5% down; 500-579 requires 10% down; below 500 is ineligible for FHA financing.'],
        ],
        'balloon_calc'      => [
            ['q'=>'What is a balloon loan?',                       'a'=>'A balloon loan has low monthly payments based on a 30-year amortization but the full remaining balance is due as a lump sum at the end of a shorter term (5-10 years).'],
            ['q'=>'Who uses balloon loans?',                       'a'=>'Mostly commercial real estate investors who expect to sell or refinance before the balloon payment is due.'],
            ['q'=>'What happens if I cannot pay the balloon?',    'a'=>'You must refinance or sell. If neither is possible, you risk defaulting and the lender can foreclose on the property.'],
        ],
        'interest_only_calc'=> [
            ['q'=>'What is an interest-only loan?',                'a'=>'You pay only interest for a set period (5-10 years), then fully amortizing P&I payments begin -- which are higher than a traditional loan.'],
            ['q'=>'Do interest-only loans build equity?',          'a'=>'No -- your balance does not decrease during the IO period. Equity builds only through appreciation, not loan paydown.'],
            ['q'=>'How much more does an IO loan cost?',           'a'=>'Total interest can be 10-20% more than a fully amortizing loan because principal is not reduced during the IO period.'],
        ],
        'loan_payoff_calc'  => [
            ['q'=>'How much do extra mortgage payments save?',     'a'=>'On a $200k loan at 7%, an extra $200/month saves about 5 years and $60,000+ in interest. Even $50/month makes a meaningful difference.'],
            ['q'=>'When is the best time to make extra payments?', 'a'=>'Early in the loan -- payments are front-loaded with interest, so extra payments in years 1-5 save far more than the same amount in later years.'],
            ['q'=>'Should I overpay my mortgage or invest?',       'a'=>'If your rate exceeds expected investment returns, overpaying wins. For low rates (3-4%), investing in the stock market often wins mathematically.'],
        ],
        'commercial_calc'   => [
            ['q'=>'What is DSCR and what is a good ratio?',       'a'=>'DSCR = Net Operating Income / Annual Debt Service. Most commercial lenders require 1.25 or higher, meaning the property earns 25% more than the loan payment.'],
            ['q'=>'What are commercial real estate loan rates in 2026?','a'=>'Rates range from 6.5%-9% for stabilized properties, higher for construction or value-add. Rates depend on property type, LTV, and lender.'],
            ['q'=>'What LTV do commercial lenders allow?',         'a'=>'Most cap LTV at 65-75% for investment properties. SBA loans allow up to 85-90% for owner-occupied commercial real estate.'],
        ],
        'bridge_calc'       => [
            ['q'=>'What is a bridge loan?',                        'a'=>'Short-term financing (3-24 months) used to bridge the gap between buying a new property and selling an existing one, or while securing permanent financing.'],
            ['q'=>'What are typical bridge loan rates?',           'a'=>'Bridge loan rates range from 8%-12%+ in 2026. They cost more than conventional loans due to the short term and higher lender risk.'],
            ['q'=>'What fees come with a bridge loan?',            'a'=>'Typical costs: 1-3% origination fee, 0.5-2% exit fee, appraisal, and legal fees. Total fees can add 3-5% to the loan amount.'],
        ],
        'va_loan_advanced'  => [
            ['q'=>'What is the VA funding fee for 2026?',          'a'=>'2.15% for first-time use with 0% down (Regular Military), 3.30% for subsequent use. Veterans with 10%+ service-connected disability are exempt.'],
            ['q'=>'Who is exempt from the VA funding fee?',        'a'=>'Veterans receiving VA disability compensation, surviving spouses of veterans who died in service, and active duty Purple Heart recipients.'],
            ['q'=>'Can I roll the VA funding fee into my loan?',   'a'=>'Yes -- the VA funding fee can be financed into your loan amount so you pay nothing at closing. This is the most common approach.'],
        ],
    ];

    $items = $faqs[ $tool_type ] ?? [
        ['q'=>'Is this calculator free to use?',                   'a'=>'Yes -- all calculators on FinanceSpots are completely free, with no signup or subscription required. Results are instant.'],
        ['q'=>'Can I download my results as a PDF?',               'a'=>'Yes -- every calculator includes a "Download PDF Report" button after calculating. The PDF includes a branded results report with all key figures.'],
        ['q'=>'How accurate are these calculators?',               'a'=>'Our calculators use standard financial formulas and are updated for 2026 rates and regulations. Always verify results with a licensed financial advisor for major decisions.'],
    ];

    $entities = [];
    foreach ( $items as $item ) {
        $entities[] = [
            '@type'          => 'Question',
            'name'           => $item['q'],
            'acceptedAnswer' => [ '@type' => 'Answer', 'text' => $item['a'] ],
        ];
    }
    return [ '@type' => 'FAQPage', 'mainEntity' => $entities ];
}

function financespots_head_meta() {
    global $post;
    $site_name  = get_bloginfo( 'name' );
    $site_desc  = get_bloginfo( 'description' );
    $site_url   = esc_url( home_url( '/' ) );
    $logo_id    = get_theme_mod( 'custom_logo' );
    $logo_url   = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : FINANCESPOTS_URI . '/assets/images/og-default.png';

    if ( is_singular( 'fs_tool' ) && isset( $post ) ) {
        // RankMath meta override if set
        $rm_title = get_post_meta( $post->ID, 'rank_math_title', true );
        $rm_desc  = get_post_meta( $post->ID, 'rank_math_description', true );
        $rm_kw    = get_post_meta( $post->ID, 'rank_math_focus_keyword', true );
        $title       = $rm_title  ? esc_attr( $rm_title )  : esc_attr( get_the_title() . ' | FinanceSpots' );
        $description = $rm_desc   ? esc_attr( $rm_desc )   : esc_attr( get_the_excerpt() );
        $url         = esc_url( get_permalink() );
        $image       = get_the_post_thumbnail_url( $post, 'large' ) ?: $logo_url;
        $tool_type   = get_post_meta( $post->ID, '_fs_tool_type', true );

        // Output tool-specific SEO
        echo '<meta name="keywords" content="' . esc_attr( $rm_kw ) . ', finance calculator, free tool" />' . "\n";

        // Schema.org WebApplication for tools
        $schema = [
            '@context' => 'https://schema.org',
            '@graph'   => [
                [
                    '@type'               => 'WebApplication',
                    '@id'                 => $url . '#tool',
                    'name'                => get_the_title(),
                    'description'         => wp_strip_all_tags( get_the_excerpt() ),
                    'url'                 => $url,
                    'applicationCategory' => 'FinanceApplication',
                    'operatingSystem'     => 'Web Browser',
                    'offers'              => [ '@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD' ],
                    'publisher'           => [ '@type' => 'Organization', 'name' => $site_name, 'url' => $site_url ],
                ],
                [
                    '@type'       => 'BreadcrumbList',
                    'itemListElement' => [
                        [ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home',  'item' => $site_url ],
                        [ '@type' => 'ListItem', 'position' => 2, 'name' => 'Tools', 'item' => home_url('/tools/') ],
                        [ '@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => $url ],
                    ],
                ],
                fs_get_tool_faq_schema( $tool_type ),
            ],
        ];
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";

    } elseif ( is_singular() && isset( $post ) ) {
        $title       = esc_attr( get_the_title() );
        $description = esc_attr( get_the_excerpt() );
        $url         = esc_url( get_permalink() );
        $image       = get_the_post_thumbnail_url( $post, 'large' ) ?: $logo_url;
    } else {
        $title       = esc_attr( $site_name );
        $description = esc_attr( $site_desc );
        $url         = $site_url;
        $image       = $logo_url;
    }
    ?>
<!-- Open Graph -->
<meta property="og:type"        content="website" />
<meta property="og:site_name"   content="<?php echo esc_attr( $site_name ); ?>" />
<meta property="og:title"       content="<?php echo $title; ?>" />
<meta property="og:description" content="<?php echo $description; ?>" />
<meta property="og:url"         content="<?php echo $url; ?>" />
<meta property="og:image"       content="<?php echo esc_url( $image ); ?>" />
<meta property="og:image:width"  content="1200" />
<meta property="og:image:height" content="630" />
<!-- Twitter Card -->
<meta name="twitter:card"        content="summary_large_image" />
<meta name="twitter:title"       content="<?php echo $title; ?>" />
<meta name="twitter:description" content="<?php echo $description; ?>" />
<meta name="twitter:image"       content="<?php echo esc_url( $image ); ?>" />
<!-- Schema.org -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "WebSite",
      "@id": "<?php echo $site_url; ?>#website",
      "url": "<?php echo $site_url; ?>",
      "name": "<?php echo esc_js( $site_name ); ?>",
      "description": "<?php echo esc_js( $site_desc ); ?>",
      "potentialAction": {
        "@type": "SearchAction",
        "target": {"@type":"EntryPoint","urlTemplate":"<?php echo $site_url; ?>?s={search_term_string}"},
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@type": "Organization",
      "@id": "<?php echo $site_url; ?>#organization",
      "name": "<?php echo esc_js( $site_name ); ?>",
      "url": "<?php echo $site_url; ?>",
      "logo": {
        "@type": "ImageObject",
        "url": "<?php echo esc_js( $logo_url ); ?>"
      }
    }
  ]
}
</script>
    <?php
}
add_action( 'wp_head', 'financespots_head_meta' );

/* =========================================================
   8. HELPER FUNCTIONS
   ========================================================= */

/** Get customizer value with fallback */
function fs_mod( $key, $default = '' ) {
    return esc_html( get_theme_mod( $key, $default ) );
}

/** Get customizer URL value with fallback */
function fs_mod_url( $key, $default = '#' ) {
    return esc_url( get_theme_mod( $key, $default ) );
}

/** Tool categories data */
function fs_get_tool_categories() {
    return [
        [ 'slug' => 'loans',       'icon' => '&#127974;', 'name' => 'Loan Calculators',       'desc' => 'Mortgage, auto, personal loans & more',        'count' => 18, 'color' => 'primary' ],
        [ 'slug' => 'investment',  'icon' => '&#128200;', 'name' => 'Investment Tools',        'desc' => 'ROI, compound interest, portfolio analysis',   'count' => 22, 'color' => 'secondary' ],
        [ 'slug' => 'tax',         'icon' => '&#129534;', 'name' => 'Tax Calculators',         'desc' => 'Income tax, capital gains, deductions',        'count' => 15, 'color' => 'gold' ],
        [ 'slug' => 'savings',     'icon' => '&#128176;', 'name' => 'Savings Planners',        'desc' => 'Emergency funds, goals, retirement savings',   'count' => 12, 'color' => 'primary' ],
        [ 'slug' => 'retirement',  'icon' => '&#127958;&#65039;', 'name' => 'Retirement Planning',    'desc' => '401k, IRA, pension, FIRE calculators',         'count' => 10, 'color' => 'secondary' ],
        [ 'slug' => 'currency',    'icon' => '&#128177;', 'name' => 'Currency Converters',     'desc' => 'Live forex rates & currency conversion',       'count' => 8,  'color' => 'gold' ],
        [ 'slug' => 'budget',      'icon' => '&#128202;', 'name' => 'Budget Analyzers',        'desc' => 'Monthly budgets, expense tracking, 50/30/20',  'count' => 14, 'color' => 'primary' ],
        [ 'slug' => 'crypto',      'icon' => '₿',  'name' => 'Crypto Tools',            'desc' => 'Crypto P&L, staking rewards, DCA calculator',  'count' => 11, 'color' => 'secondary' ],
    ];
}

/** Popular tools data -- titles must exactly match DB post_title */
function fs_get_popular_tools() {
    return [
        [ 'name' => 'VA Loan Funding Fee Calculator', 'desc' => 'Calculate VA funding fee, monthly PITI, amortization & VA vs Conventional comparison -- with PDF export.',    'icon' => '&#127894;&#65039;', 'badge' => '&#128293; Advanced',   'badge_color' => 'primary',   'cat' => 'loan-calculators' ],
        [ 'name' => 'Mortgage Calculator',            'desc' => 'Calculate monthly payments, total interest, and full amortization schedule for any home loan.',               'icon' => '&#127968;', 'badge' => '&#11088; Most Popular','badge_color' => 'secondary', 'cat' => 'loan-calculators' ],
        [ 'name' => 'Compound Interest Calculator',   'desc' => 'See how your money grows over time with daily, monthly, or annual compounding.',                               'icon' => '&#128200;', 'badge' => '&#128202; Popular',    'badge_color' => 'ai',        'cat' => 'investment-tools' ],
        [ 'name' => 'Income Tax Calculator',          'desc' => 'Estimate federal and state income tax based on your income, deductions, and filing status.',                   'icon' => '&#129534;', 'badge' => '&#9989; 2026',       'badge_color' => 'gold',      'cat' => 'tax-calculators' ],
        [ 'name' => '401k Calculator',                'desc' => 'Project your 401k balance at retirement with employer match, contribution rate, and growth assumptions.',      'icon' => '&#127958;&#65039;', 'badge' => '&#128197; Retirement', 'badge_color' => 'ai',        'cat' => 'retirement-planning' ],
        [ 'name' => '50/30/20 Budget Calculator',     'desc' => 'Automatically allocate your income using the popular 50/30/20 budgeting rule and track your spending.',       'icon' => '&#128202;', 'badge' => '&#128176; Free',       'badge_color' => 'secondary', 'cat' => 'budget-analyzers' ],
    ];
}

/** Resolve a tool's permalink by exact post title -- cached per request */
function fs_get_tool_url_by_title( $title, $cat_slug = '' ) {
    static $cache = [];
    if ( isset( $cache[ $title ] ) ) return $cache[ $title ];

    // Special case: VA Loan Advanced
    if ( stripos( $title, 'VA Loan Funding Fee' ) !== false ) {
        $va_id = get_option( 'fs_va_loan_tool_id', 0 );
        if ( $va_id ) {
            $cache[$title] = get_permalink( $va_id );
            return $cache[$title];
        }
    }

    $posts = get_posts( [
        'post_type'   => 'fs_tool',
        'numberposts' => 5,
        'post_status' => 'publish',
        's'           => $title,
    ] );

    $url = home_url('/tools/');
    foreach ( $posts as $p ) {
        if ( strtolower( trim($p->post_title) ) === strtolower( trim($title) ) ) {
            $url = get_permalink( $p );
            break;
        }
    }

    // Fallback: go to category page
    if ( $url === home_url('/tools/') && $cat_slug ) {
        $term = get_term_by( 'slug', $cat_slug, 'fs_tool_cat' );
        if ( $term ) $url = get_term_link( $term );
    }

    $cache[$title] = $url;
    return $url;
}

/** AI features data */
function fs_get_ai_features() {
    return [
        [ 'icon' => '&#129302;', 'title' => 'Smart Suggestions',      'desc' => 'AI analyzes your inputs and recommends optimal financial strategies tailored to your situation.' ],
        [ 'icon' => '&#128225;', 'title' => 'Real-Time Data',          'desc' => 'Live market rates, interest rates, and tax tables automatically updated daily.' ],
        [ 'icon' => '&#128302;', 'title' => 'Predictive Modeling',     'desc' => 'Monte Carlo simulations and scenario analysis to stress-test your financial plans.' ],
        [ 'icon' => '&#128737;&#65039;', 'title' => '100% Private',            'desc' => 'All calculations run in your browser. No data is stored or shared -- ever.' ],
    ];
}

/** How it works steps */
function fs_get_steps() {
    return [
        [ 'num' => '01', 'title' => 'Choose Your Tool',   'desc' => 'Browse 150+ finance tools organized by category. Use our smart search to find what you need instantly.' ],
        [ 'num' => '02', 'title' => 'Enter Your Data',    'desc' => 'Input your financial details into our clean, guided forms. Tooltips explain every field.' ],
        [ 'num' => '03', 'title' => 'Get AI Insights',    'desc' => 'Receive instant results plus AI-generated insights, comparisons, and personalized recommendations.' ],
        [ 'num' => '04', 'title' => 'Plan & Take Action', 'desc' => 'Export your results, save scenarios, or share with your financial advisor -- all for free.' ],
    ];
}

/** Testimonials */
function fs_get_testimonials() {
    return [
        [ 'name' => 'Sarah Johnson',    'role' => 'Financial Planner, NYC',         'avatar' => 'SJ', 'rating' => 5, 'text' => 'FinanceSpots has completely transformed how I explain financial concepts to my clients. The mortgage calculator alone saves us hours each week.' ],
        [ 'name' => 'Marcus Chen',      'role' => 'Software Engineer & Investor',   'avatar' => 'MC', 'rating' => 5, 'text' => 'The compound interest visualizations are incredible. I finally understand the power of early investing thanks to these tools.' ],
        [ 'name' => 'Priya Patel',      'role' => 'Small Business Owner',           'avatar' => 'PP', 'rating' => 5, 'text' => 'Tax season is no longer stressful. The tax calculator gives me accurate estimates without paying for expensive software.' ],
        [ 'name' => 'David Thompson',   'role' => 'Retirement Planning Consultant', 'avatar' => 'DT', 'rating' => 5, 'text' => 'I recommend FinanceSpots to all my clients. The retirement planner accounts for inflation in ways that other tools simply don\'t.' ],
    ];
}

/** Why Us features */
function fs_get_features() {
    return [
        [ 'icon' => '&#9889;', 'title' => 'Instant Results',       'desc' => 'Every calculation is instant -- no waiting, no server calls. Results update in real-time as you type.' ],
        [ 'icon' => '&#127919;', 'title' => '100% Accurate',         'desc' => 'Our formulas are verified by certified financial professionals and updated with the latest regulations.' ],
        [ 'icon' => '&#128275;', 'title' => 'Completely Free',       'desc' => 'Every tool, every feature, every calculation -- completely free forever. No account required.' ],
        [ 'icon' => '&#128241;', 'title' => 'Works Everywhere',      'desc' => 'Perfectly optimized for desktop, tablet, and mobile. Use our tools anywhere, anytime.' ],
        [ 'icon' => '&#129309;', 'title' => 'Expert Verified',       'desc' => 'Built with input from CPAs, CFPs, and investment advisors to ensure professional-grade accuracy.' ],
        [ 'icon' => '&#128260;', 'title' => 'Always Updated',        'desc' => 'Tax rates, market data, and financial regulations updated automatically throughout the year.' ],
    ];
}

/* =========================================================
   9. BODY CLASSES
   ========================================================= */
function financespots_body_classes( $classes ) {
    if ( is_front_page() ) $classes[] = 'is-front-page';
    if ( is_singular() )   $classes[] = 'is-singular';
    return $classes;
}
add_filter( 'body_class', 'financespots_body_classes' );

/* =========================================================
   10. EXCERPT LENGTH
   ========================================================= */
function financespots_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'financespots_excerpt_length' );

function financespots_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'financespots_excerpt_more' );

/* ── Show ALL tools on taxonomy & archive pages ── */
function fs_tools_archive_query( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) return;
    if ( $query->is_tax( 'fs_tool_cat' ) || $query->is_post_type_archive( 'fs_tool' ) ) {
        $query->set( 'posts_per_page', -1 );
        $query->set( 'orderby', 'title' );
        $query->set( 'order', 'ASC' );
    }
}
add_action( 'pre_get_posts', 'fs_tools_archive_query' );

/* =========================================================
   11. CUSTOM QUERY VARS (for tool filtering)
   ========================================================= */
function financespots_query_vars( $vars ) {
    $vars[] = 'tool_category';
    return $vars;
}
add_filter( 'query_vars', 'financespots_query_vars' );

/* =========================================================
   12. CUSTOM POST TYPE: fs_tool + TAXONOMY: fs_tool_cat
   ========================================================= */
function fs_register_tools_cpt() {
    // CPT: single tool pages at /tool/slug/
    register_post_type( 'fs_tool', [
        'labels'      => [
            'name'          => 'Finance Tools',
            'singular_name' => 'Finance Tool',
            'add_new_item'  => 'Add New Tool',
            'edit_item'     => 'Edit Tool',
            'view_item'     => 'View Tool',
            'all_items'     => 'All Tools',
        ],
        'public'       => true,
        'has_archive'  => 'tools',          // archive at /tools/
        'rewrite'      => [ 'slug' => 'tool', 'with_front' => false ],  // single at /tool/slug/
        'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail' ],
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-calculator',
    ] );

    // Taxonomy: category pages at /tools/category-slug/
    register_taxonomy( 'fs_tool_cat', 'fs_tool', [
        'labels'       => [
            'name'          => 'Tool Categories',
            'singular_name' => 'Tool Category',
            'all_items'     => 'All Categories',
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => [ 'slug' => 'tools', 'with_front' => false ],  // /tools/loan-calculators/
        'show_in_rest' => true,
    ] );
}
add_action( 'init', 'fs_register_tools_cpt' );

// Force our taxonomy template -- prevent WordPress from using index.php fallback
function fs_taxonomy_template_fix( $template ) {
    if ( is_tax( 'fs_tool_cat' ) ) {
        $custom = get_template_directory() . '/taxonomy-fs_tool_cat.php';
        if ( file_exists( $custom ) ) return $custom;
    }
    if ( is_post_type_archive( 'fs_tool' ) ) {
        $custom = get_template_directory() . '/archive-fs_tool.php';
        if ( file_exists( $custom ) ) return $custom;
    }
    if ( is_singular( 'fs_tool' ) ) {
        $custom = get_template_directory() . '/single-fs_tool.php';
        if ( file_exists( $custom ) ) return $custom;
    }
    return $template;
}
add_filter( 'template_include', 'fs_taxonomy_template_fix', 99 );

// Flush rewrite rules whenever slug structure changes
function fs_maybe_flush_rewrites() {
    if ( get_option('fs_rewrite_flushed_v5') ) return;
    flush_rewrite_rules( true );
    update_option('fs_rewrite_flushed_v5', true);
    // Clean old flags
    delete_option('fs_rewrite_flushed_v3');
    delete_option('fs_rewrite_flushed_v4');
}
add_action( 'init', 'fs_maybe_flush_rewrites', 99 );

/* ── Auto-insert all tools once ── */
function fs_insert_all_tools() {
    // If flag set, only skip if tools actually exist in DB
    if ( get_option( 'fs_tools_inserted_v1' ) ) {
        $count = wp_count_posts( 'fs_tool' );
        $published = isset( $count->publish ) ? (int) $count->publish : 0;
        if ( $published >= 10 ) return; // tools exist, skip
        // tools missing -- reset flag and re-insert
        delete_option( 'fs_tools_inserted_v1' );
    }

    // Remove any WordPress page with slug 'tools' that conflicts with CPT archive
    $conflict_page = get_page_by_path( 'tools', OBJECT, 'page' );
    if ( $conflict_page ) {
        wp_update_post( [ 'ID' => $conflict_page->ID, 'post_name' => 'tools-page', 'post_status' => 'draft' ] );
    }

    $categories = [
        'loan-calculators'    => [ 'name' => 'Loan Calculators',    'icon' => '&#127974;' ],
        'investment-tools'    => [ 'name' => 'Investment Tools',     'icon' => '&#128200;' ],
        'tax-calculators'     => [ 'name' => 'Tax Calculators',      'icon' => '&#129534;' ],
        'savings-planners'    => [ 'name' => 'Savings Planners',     'icon' => '&#128176;' ],
        'retirement-planning' => [ 'name' => 'Retirement Planning',  'icon' => '&#127958;&#65039;' ],
        'currency-converters' => [ 'name' => 'Currency Converters',  'icon' => '&#128177;' ],
        'budget-analyzers'    => [ 'name' => 'Budget Analyzers',     'icon' => '&#128202;' ],
        'crypto-tools'        => [ 'name' => 'Crypto Tools',         'icon' => '₿'  ],
    ];

    $tools = [
        // ── Loan Calculators (18) ──
        [ 'title'=>'Mortgage Calculator',          'cat'=>'loan-calculators',    'type'=>'loan_payment',    'desc'=>'Calculate your monthly mortgage payment, total interest, and amortization schedule.' ],
        [ 'title'=>'Auto Loan Calculator',         'cat'=>'loan-calculators',    'type'=>'loan_payment',    'desc'=>'Find your monthly car payment and total cost of an auto loan.' ],
        [ 'title'=>'Personal Loan Calculator',     'cat'=>'loan-calculators',    'type'=>'loan_payment',    'desc'=>'Estimate monthly payments and interest for personal loans.' ],
        [ 'title'=>'Student Loan Calculator',      'cat'=>'loan-calculators',    'type'=>'loan_payment',    'desc'=>'Plan student loan repayment and see total interest paid.' ],
        [ 'title'=>'Home Equity Loan Calculator',  'cat'=>'loan-calculators',    'type'=>'loan_payment',    'desc'=>'Calculate payments for home equity loans and lines of credit.' ],
        [ 'title'=>'Debt Consolidation Calculator','cat'=>'loan-calculators',    'type'=>'amortization',    'desc'=>'See how consolidating debts can lower your monthly payment.' ],
        [ 'title'=>'Loan Comparison Calculator',   'cat'=>'loan-calculators',    'type'=>'loan_compare',    'desc'=>'Compare two loans side by side to find the best deal.' ],
        [ 'title'=>'Amortization Calculator',      'cat'=>'loan-calculators',    'type'=>'amortization',    'desc'=>'Generate a full amortization schedule for any loan.' ],
        [ 'title'=>'Refinance Calculator',         'cat'=>'loan-calculators',    'type'=>'refinance',       'desc'=>'Calculate savings from refinancing your mortgage or loan.' ],
        [ 'title'=>'FHA Loan Calculator',          'cat'=>'loan-calculators',    'type'=>'loan_payment',    'desc'=>'Estimate FHA loan payments including mortgage insurance premium.' ],
        [ 'title'=>'VA Loan Calculator',           'cat'=>'loan-calculators',    'type'=>'loan_payment',    'desc'=>'Calculate VA loan payments with funding fee included.' ],
        [ 'title'=>'Balloon Loan Calculator',      'cat'=>'loan-calculators',    'type'=>'loan_payment',    'desc'=>'Calculate balloon loan payments and final balloon amount.' ],
        [ 'title'=>'Interest Only Calculator',     'cat'=>'loan-calculators',    'type'=>'loan_payment',    'desc'=>'See interest-only loan payments vs. principal+interest.' ],
        [ 'title'=>'Loan Payoff Calculator',       'cat'=>'loan-calculators',    'type'=>'amortization',    'desc'=>'Find out when you will pay off your loan with extra payments.' ],
        [ 'title'=>'Monthly Payment Calculator',   'cat'=>'loan-calculators',    'type'=>'loan_payment',    'desc'=>'Calculate the monthly payment for any loan amount and rate.' ],
        [ 'title'=>'Loan Affordability Calculator','cat'=>'loan-calculators',    'type'=>'affordability',   'desc'=>'Find out how much you can borrow based on your income.' ],
        [ 'title'=>'Commercial Loan Calculator',   'cat'=>'loan-calculators',    'type'=>'loan_payment',    'desc'=>'Calculate payments for commercial real estate and business loans.' ],
        [ 'title'=>'Bridge Loan Calculator',       'cat'=>'loan-calculators',    'type'=>'loan_payment',    'desc'=>'Estimate costs and payments for short-term bridge financing.' ],

        // ── Investment Tools (22) ──
        [ 'title'=>'ROI Calculator',               'cat'=>'investment-tools',    'type'=>'roi',             'desc'=>'Calculate return on investment for any asset or project.' ],
        [ 'title'=>'Compound Interest Calculator', 'cat'=>'investment-tools',    'type'=>'compound',        'desc'=>'See how compound interest grows your money over time.' ],
        [ 'title'=>'Portfolio Analyzer',           'cat'=>'investment-tools',    'type'=>'portfolio',       'desc'=>'Analyze portfolio allocation, returns, and risk.' ],
        [ 'title'=>'Stock Return Calculator',      'cat'=>'investment-tools',    'type'=>'roi',             'desc'=>'Calculate total stock return including dividends and capital gains.' ],
        [ 'title'=>'Dividend Calculator',          'cat'=>'investment-tools',    'type'=>'dividend',        'desc'=>'Calculate dividend income, yield, and growth projections.' ],
        [ 'title'=>'Investment Growth Calculator', 'cat'=>'investment-tools',    'type'=>'compound',        'desc'=>'Project how your investments grow with regular contributions.' ],
        [ 'title'=>'Dollar Cost Averaging',        'cat'=>'investment-tools',    'type'=>'dca',             'desc'=>'Simulate DCA strategy returns versus lump sum investing.' ],
        [ 'title'=>'Risk Assessment Tool',         'cat'=>'investment-tools',    'type'=>'risk',            'desc'=>'Evaluate your investment risk tolerance and asset allocation.' ],
        [ 'title'=>'Asset Allocation Calculator',  'cat'=>'investment-tools',    'type'=>'portfolio',       'desc'=>'Build the optimal portfolio mix based on your goals.' ],
        [ 'title'=>'Bond Yield Calculator',        'cat'=>'investment-tools',    'type'=>'bond',            'desc'=>'Calculate current yield, YTM, and bond price.' ],
        [ 'title'=>'Options Profit Calculator',    'cat'=>'investment-tools',    'type'=>'options',         'desc'=>'Calculate profit/loss for call and put options.' ],
        [ 'title'=>'Mutual Fund Calculator',       'cat'=>'investment-tools',    'type'=>'compound',        'desc'=>'Project mutual fund growth with expense ratio included.' ],
        [ 'title'=>'ETF Calculator',               'cat'=>'investment-tools',    'type'=>'compound',        'desc'=>'Calculate ETF returns with fees and dividend reinvestment.' ],
        [ 'title'=>'Capital Gains Calculator',     'cat'=>'investment-tools',    'type'=>'capital_gains',   'desc'=>'Calculate short and long-term capital gains tax owed.' ],
        [ 'title'=>'CAGR Calculator',              'cat'=>'investment-tools',    'type'=>'cagr',            'desc'=>'Calculate compound annual growth rate for any investment.' ],
        [ 'title'=>'Present Value Calculator',     'cat'=>'investment-tools',    'type'=>'pv',              'desc'=>'Find the present value of future cash flows.' ],
        [ 'title'=>'Future Value Calculator',      'cat'=>'investment-tools',    'type'=>'compound',        'desc'=>'Calculate the future value of investments today.' ],
        [ 'title'=>'Break Even Calculator',        'cat'=>'investment-tools',    'type'=>'breakeven',       'desc'=>'Find the break-even point for investments and businesses.' ],
        [ 'title'=>'Inflation Calculator',         'cat'=>'investment-tools',    'type'=>'inflation',       'desc'=>'See how inflation erodes purchasing power over time.' ],
        [ 'title'=>'Investment Fee Calculator',    'cat'=>'investment-tools',    'type'=>'compound',        'desc'=>'Calculate the true cost of investment fees over time.' ],
        [ 'title'=>'Sharpe Ratio Calculator',      'cat'=>'investment-tools',    'type'=>'sharpe',          'desc'=>'Measure risk-adjusted return with the Sharpe ratio.' ],
        [ 'title'=>'Portfolio Rebalancing Tool',   'cat'=>'investment-tools',    'type'=>'portfolio',       'desc'=>'Calculate trades needed to rebalance your target allocation.' ],

        // ── Tax Calculators (15) ──
        [ 'title'=>'Income Tax Calculator',        'cat'=>'tax-calculators',     'type'=>'income_tax',      'desc'=>'Estimate federal income tax based on your income and filing status.' ],
        [ 'title'=>'Capital Gains Tax Calculator', 'cat'=>'tax-calculators',     'type'=>'capital_gains',   'desc'=>'Calculate capital gains tax on investment profits.' ],
        [ 'title'=>'Tax Deduction Estimator',      'cat'=>'tax-calculators',     'type'=>'income_tax',      'desc'=>'Estimate itemized vs. standard deductions for tax savings.' ],
        [ 'title'=>'Self Employment Tax Calculator','cat'=>'tax-calculators',    'type'=>'self_emp_tax',    'desc'=>'Calculate self-employment tax and estimated quarterly payments.' ],
        [ 'title'=>'Property Tax Calculator',      'cat'=>'tax-calculators',     'type'=>'income_tax',      'desc'=>'Estimate annual property tax based on assessed value.' ],
        [ 'title'=>'Sales Tax Calculator',         'cat'=>'tax-calculators',     'type'=>'simple_calc',     'desc'=>'Add or remove sales tax from any amount instantly.' ],
        [ 'title'=>'Tax Bracket Calculator',       'cat'=>'tax-calculators',     'type'=>'income_tax',      'desc'=>'See which tax brackets apply to your income.' ],
        [ 'title'=>'Estate Tax Calculator',        'cat'=>'tax-calculators',     'type'=>'simple_calc',     'desc'=>'Estimate federal estate tax on inherited assets.' ],
        [ 'title'=>'AMT Calculator',               'cat'=>'tax-calculators',     'type'=>'income_tax',      'desc'=>'Check if the Alternative Minimum Tax applies to you.' ],
        [ 'title'=>'Quarterly Tax Calculator',     'cat'=>'tax-calculators',     'type'=>'self_emp_tax',    'desc'=>'Calculate estimated quarterly tax payments for freelancers.' ],
        [ 'title'=>'W-4 Calculator',               'cat'=>'tax-calculators',     'type'=>'income_tax',      'desc'=>'Calculate the right W-4 withholding to avoid underpayment.' ],
        [ 'title'=>'IRS Penalty Calculator',       'cat'=>'tax-calculators',     'type'=>'simple_calc',     'desc'=>'Estimate IRS underpayment penalty and interest owed.' ],
        [ 'title'=>'State Tax Calculator',         'cat'=>'tax-calculators',     'type'=>'income_tax',      'desc'=>'Estimate state income tax for all 50 US states.' ],
        [ 'title'=>'Tax Withholding Calculator',   'cat'=>'tax-calculators',     'type'=>'income_tax',      'desc'=>'Check if enough tax is withheld from your paycheck.' ],
        [ 'title'=>'Effective Tax Rate Calculator','cat'=>'tax-calculators',     'type'=>'income_tax',      'desc'=>'Calculate your true effective tax rate vs. marginal rate.' ],

        // ── Savings Planners (12) ──
        [ 'title'=>'Emergency Fund Calculator',    'cat'=>'savings-planners',    'type'=>'savings_goal',    'desc'=>'Calculate how much emergency fund you need and how to build it.' ],
        [ 'title'=>'Savings Goal Calculator',      'cat'=>'savings-planners',    'type'=>'savings_goal',    'desc'=>'Plan how much to save monthly to reach any financial goal.' ],
        [ 'title'=>'Retirement Savings Calculator','cat'=>'savings-planners',    'type'=>'compound',        'desc'=>'Project retirement savings growth with regular contributions.' ],
        [ 'title'=>'CD Calculator',                'cat'=>'savings-planners',    'type'=>'compound',        'desc'=>'Calculate Certificate of Deposit returns at maturity.' ],
        [ 'title'=>'Money Market Calculator',      'cat'=>'savings-planners',    'type'=>'compound',        'desc'=>'Project money market account growth with interest.' ],
        [ 'title'=>'Savings Rate Calculator',      'cat'=>'savings-planners',    'type'=>'savings_goal',    'desc'=>'Calculate your savings rate as a percentage of income.' ],
        [ 'title'=>'52 Week Savings Challenge',    'cat'=>'savings-planners',    'type'=>'savings_52',      'desc'=>'Track the popular 52-week savings challenge to save $1,378.' ],
        [ 'title'=>'Round Up Savings Calculator',  'cat'=>'savings-planners',    'type'=>'savings_goal',    'desc'=>'Estimate savings from rounding up everyday purchases.' ],
        [ 'title'=>'Vacation Savings Calculator',  'cat'=>'savings-planners',    'type'=>'savings_goal',    'desc'=>'Plan how to save for your dream vacation step by step.' ],
        [ 'title'=>'Down Payment Savings Calculator','cat'=>'savings-planners',  'type'=>'savings_goal',    'desc'=>'Figure out how long it takes to save a home down payment.' ],
        [ 'title'=>'High Yield Savings Calculator','cat'=>'savings-planners',    'type'=>'compound',        'desc'=>'Compare HYSA returns vs. regular savings accounts.' ],
        [ 'title'=>'Savings Milestone Tracker',    'cat'=>'savings-planners',    'type'=>'savings_goal',    'desc'=>'Track progress toward multiple savings milestones at once.' ],

        // ── Retirement Planning (10) ──
        [ 'title'=>'401k Calculator',              'cat'=>'retirement-planning', 'type'=>'retirement',      'desc'=>'Project 401k balance at retirement with employer match.' ],
        [ 'title'=>'IRA Calculator',               'cat'=>'retirement-planning', 'type'=>'retirement',      'desc'=>'Calculate Traditional or Roth IRA growth to retirement.' ],
        [ 'title'=>'Pension Calculator',           'cat'=>'retirement-planning', 'type'=>'retirement',      'desc'=>'Estimate monthly pension benefit at retirement.' ],
        [ 'title'=>'FIRE Calculator',              'cat'=>'retirement-planning', 'type'=>'fire',            'desc'=>'Calculate your FIRE number and early retirement date.' ],
        [ 'title'=>'Social Security Calculator',   'cat'=>'retirement-planning', 'type'=>'retirement',      'desc'=>'Estimate Social Security benefits at different claiming ages.' ],
        [ 'title'=>'Retirement Income Calculator', 'cat'=>'retirement-planning', 'type'=>'retirement',      'desc'=>'Calculate sustainable retirement income from your nest egg.' ],
        [ 'title'=>'RMD Calculator',               'cat'=>'retirement-planning', 'type'=>'retirement',      'desc'=>'Calculate Required Minimum Distribution from retirement accounts.' ],
        [ 'title'=>'Roth Conversion Calculator',   'cat'=>'retirement-planning', 'type'=>'retirement',      'desc'=>'Decide if converting to a Roth IRA makes financial sense.' ],
        [ 'title'=>'Retirement Withdrawal Calculator','cat'=>'retirement-planning','type'=>'retirement',    'desc'=>'How long will your retirement savings last?' ],
        [ 'title'=>'Early Retirement Calculator',  'cat'=>'retirement-planning', 'type'=>'fire',            'desc'=>'Plan for retirement before the traditional age 65.' ],

        // ── Currency Converters (8) ──
        [ 'title'=>'Live Currency Converter',      'cat'=>'currency-converters', 'type'=>'currency',        'desc'=>'Convert between 150+ currencies with live exchange rates.' ],
        [ 'title'=>'Historical Exchange Rate',     'cat'=>'currency-converters', 'type'=>'currency',        'desc'=>'Look up historical exchange rates for any currency pair.' ],
        [ 'title'=>'Forex Pip Calculator',         'cat'=>'currency-converters', 'type'=>'currency',        'desc'=>'Calculate pip value for any forex pair and lot size.' ],
        [ 'title'=>'Currency Strength Meter',      'cat'=>'currency-converters', 'type'=>'currency',        'desc'=>'Compare relative strength of major world currencies.' ],
        [ 'title'=>'Cross Rate Calculator',        'cat'=>'currency-converters', 'type'=>'currency',        'desc'=>'Calculate cross exchange rates between any two currencies.' ],
        [ 'title'=>'Travel Money Calculator',      'cat'=>'currency-converters', 'type'=>'currency',        'desc'=>'Convert your travel budget to local currency for any destination.' ],
        [ 'title'=>'Cryptocurrency Converter',     'cat'=>'currency-converters', 'type'=>'crypto_convert',  'desc'=>'Convert between crypto and fiat currencies in real time.' ],
        [ 'title'=>'Currency Comparison Tool',     'cat'=>'currency-converters', 'type'=>'currency',        'desc'=>'Compare exchange rates from multiple providers at once.' ],

        // ── Budget Analyzers (14) ──
        [ 'title'=>'Monthly Budget Planner',       'cat'=>'budget-analyzers',    'type'=>'budget',          'desc'=>'Build a complete monthly budget with income and expense tracking.' ],
        [ 'title'=>'50/30/20 Budget Calculator',   'cat'=>'budget-analyzers',    'type'=>'budget_503020',   'desc'=>'Apply the 50/30/20 rule to your income automatically.' ],
        [ 'title'=>'Expense Tracker',              'cat'=>'budget-analyzers',    'type'=>'budget',          'desc'=>'Track daily expenses and see where your money goes.' ],
        [ 'title'=>'Income vs Expense Analyzer',   'cat'=>'budget-analyzers',    'type'=>'budget',          'desc'=>'See a clear picture of income vs. expenses and net cash flow.' ],
        [ 'title'=>'Debt to Income Ratio',         'cat'=>'budget-analyzers',    'type'=>'dti',             'desc'=>'Calculate your debt-to-income ratio for loan qualification.' ],
        [ 'title'=>'Household Budget Calculator',  'cat'=>'budget-analyzers',    'type'=>'budget',          'desc'=>'Plan a complete household budget for your family.' ],
        [ 'title'=>'Zero Based Budget Calculator', 'cat'=>'budget-analyzers',    'type'=>'budget',          'desc'=>'Assign every dollar a job with zero-based budgeting.' ],
        [ 'title'=>'Annual Budget Planner',        'cat'=>'budget-analyzers',    'type'=>'budget',          'desc'=>'Plan your yearly budget and track spending against goals.' ],
        [ 'title'=>'Net Worth Calculator',         'cat'=>'budget-analyzers',    'type'=>'net_worth',       'desc'=>'Calculate your total net worth from assets and liabilities.' ],
        [ 'title'=>'Cash Flow Calculator',         'cat'=>'budget-analyzers',    'type'=>'budget',          'desc'=>'Analyze monthly cash flow and identify spending leaks.' ],
        [ 'title'=>'Bill Payment Planner',         'cat'=>'budget-analyzers',    'type'=>'budget',          'desc'=>'Schedule bill payments to avoid late fees and stay on track.' ],
        [ 'title'=>'Grocery Budget Calculator',    'cat'=>'budget-analyzers',    'type'=>'budget',          'desc'=>'Set and track a monthly grocery budget for your household.' ],
        [ 'title'=>'Entertainment Budget Calculator','cat'=>'budget-analyzers',  'type'=>'budget',          'desc'=>'Allocate discretionary spending for fun without guilt.' ],
        [ 'title'=>'Savings Rate Calculator',      'cat'=>'budget-analyzers',    'type'=>'savings_goal',    'desc'=>'Track your savings rate as a percentage of take-home pay.' ],

        // ── Crypto Tools (11) ──
        [ 'title'=>'Crypto P&L Calculator',        'cat'=>'crypto-tools',        'type'=>'crypto_pnl',      'desc'=>'Calculate profit and loss on any crypto trade.' ],
        [ 'title'=>'Staking Rewards Calculator',   'cat'=>'crypto-tools',        'type'=>'staking',         'desc'=>'Project staking rewards and APY for any cryptocurrency.' ],
        [ 'title'=>'Crypto DCA Calculator',        'cat'=>'crypto-tools',        'type'=>'dca',             'desc'=>'Simulate dollar cost averaging into Bitcoin or any crypto.' ],
        [ 'title'=>'Crypto Tax Calculator',        'cat'=>'crypto-tools',        'type'=>'capital_gains',   'desc'=>'Calculate capital gains tax on cryptocurrency transactions.' ],
        [ 'title'=>'Mining Profitability Calculator','cat'=>'crypto-tools',      'type'=>'mining',          'desc'=>'Calculate crypto mining profitability after electricity costs.' ],
        [ 'title'=>'Crypto Portfolio Tracker',     'cat'=>'crypto-tools',        'type'=>'portfolio',       'desc'=>'Track total crypto portfolio value and allocation.' ],
        [ 'title'=>'Bitcoin Halving Countdown',    'cat'=>'crypto-tools',        'type'=>'crypto_convert',  'desc'=>'See the next Bitcoin halving date and block countdown.' ],
        [ 'title'=>'Gas Fee Calculator',           'cat'=>'crypto-tools',        'type'=>'simple_calc',     'desc'=>'Estimate Ethereum gas fees for any transaction type.' ],
        [ 'title'=>'NFT ROI Calculator',           'cat'=>'crypto-tools',        'type'=>'roi',             'desc'=>'Calculate return on investment for NFT purchases.' ],
        [ 'title'=>'Yield Farming Calculator',     'cat'=>'crypto-tools',        'type'=>'staking',         'desc'=>'Project DeFi yield farming returns and impermanent loss.' ],
        [ 'title'=>'Crypto Converter',             'cat'=>'crypto-tools',        'type'=>'crypto_convert',  'desc'=>'Convert any cryptocurrency to USD or other fiat currencies.' ],
    ];

    // Create categories first
    foreach ( $categories as $slug => $cat ) {
        $term = term_exists( $cat['name'], 'fs_tool_cat' );
        if ( ! $term ) {
            wp_insert_term( $cat['name'], 'fs_tool_cat', [ 'slug' => $slug, 'description' => $cat['icon'] ] );
        }
    }

    // Insert tools
    foreach ( $tools as $tool ) {
        $existing = get_posts( [ 'post_type'=>'fs_tool', 'title'=>$tool['title'], 'numberposts'=>1, 'post_status'=>'any' ] );
        if ( $existing ) continue;

        $id = wp_insert_post( [
            'post_type'    => 'fs_tool',
            'post_title'   => $tool['title'],
            'post_excerpt' => $tool['desc'],
            'post_content' => $tool['desc'],
            'post_status'  => 'publish',
        ] );

        if ( $id && ! is_wp_error( $id ) ) {
            update_post_meta( $id, '_fs_tool_type', $tool['type'] );
            $term = get_term_by( 'slug', $tool['cat'], 'fs_tool_cat' );
            if ( $term ) wp_set_post_terms( $id, [ $term->term_id ], 'fs_tool_cat' );
        }
    }

    update_option( 'fs_tools_inserted_v1', true );

    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action( 'admin_init', 'fs_insert_all_tools' );

/* =========================================================
   TOOL CARD META BOX -- editable card fields in WP Admin
   Fields: _fs_card_bg, _fs_card_icon, _fs_card_link, _fs_card_badge
   ========================================================= */
function fs_tool_card_metabox() {
    add_meta_box(
        'fs_tool_card_settings',
        '&#127183; Tool Card Settings',
        'fs_tool_card_metabox_html',
        'fs_tool',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'fs_tool_card_metabox' );

function fs_tool_card_metabox_html( $post ) {
    wp_nonce_field( 'fs_tool_card_save', 'fs_tool_card_nonce' );
    $bg     = get_post_meta( $post->ID, '_fs_card_bg',    true ) ?: '#ffffff';
    $icon   = get_post_meta( $post->ID, '_fs_card_icon',  true ) ?: '';
    $link   = get_post_meta( $post->ID, '_fs_card_link',  true ) ?: '';
    $badge  = get_post_meta( $post->ID, '_fs_card_badge', true ) ?: '';
    $target = get_post_meta( $post->ID, '_fs_card_link_target', true ) ?: '_self';
    ?>
    <table class="form-table" style="margin:0">
      <tr>
        <th style="padding:6px 0;font-size:12px">Card Background</th>
        <td style="padding:4px 0">
          <div style="display:flex;align-items:center;gap:8px">
            <input type="color" name="fs_card_bg" value="<?php echo esc_attr($bg); ?>"
                   style="width:44px;height:30px;border:none;padding:0;cursor:pointer">
            <input type="text" name="fs_card_bg_hex" value="<?php echo esc_attr($bg); ?>"
                   style="width:90px;font-size:12px" placeholder="#ffffff"
                   oninput="document.querySelector('[name=fs_card_bg]').value=this.value">
            <script>
            document.querySelector('[name=fs_card_bg]').addEventListener('input',function(){
              document.querySelector('[name=fs_card_bg_hex]').value=this.value;
            });
            </script>
          </div>
          <p style="margin:4px 0 0;font-size:11px;color:#666">Leave white for default. Try light colors like #EFF6FF (blue), #F0FDF4 (green), #FFF7ED (orange).</p>
        </td>
      </tr>
      <tr>
        <th style="padding:6px 0;font-size:12px">Card Icon (emoji)</th>
        <td style="padding:4px 0">
          <input type="text" name="fs_card_icon" value="<?php echo esc_attr($icon); ?>"
                 placeholder="&#129518;" style="width:80px;font-size:18px;text-align:center">
          <p style="margin:4px 0 0;font-size:11px;color:#666">Overrides the default icon. Leave blank to use category default.</p>
        </td>
      </tr>
      <tr>
        <th style="padding:6px 0;font-size:12px">Badge Text</th>
        <td style="padding:4px 0">
          <input type="text" name="fs_card_badge" value="<?php echo esc_attr($badge); ?>"
                 placeholder="&#128293; New" style="width:140px;font-size:12px">
          <p style="margin:4px 0 0;font-size:11px;color:#666">Shows as a small badge on the card (e.g. "&#128293; New", "&#11088; Popular", "&#128640; Advanced").</p>
        </td>
      </tr>
      <tr>
        <th style="padding:6px 0;font-size:12px">Custom Link URL</th>
        <td style="padding:4px 0">
          <input type="url" name="fs_card_link" value="<?php echo esc_attr($link); ?>"
                 placeholder="https://example.com" style="width:100%;font-size:12px">
          <p style="margin:4px 0 0;font-size:11px;color:#666">Leave blank to use this post's permalink. Use for external tools or affiliate links.</p>
        </td>
      </tr>
      <tr>
        <th style="padding:6px 0;font-size:12px">Link Target</th>
        <td style="padding:4px 0">
          <select name="fs_card_link_target" style="font-size:12px">
            <option value="_self"  <?php selected($target,'_self'); ?>>Same Tab</option>
            <option value="_blank" <?php selected($target,'_blank'); ?>>New Tab</option>
          </select>
        </td>
      </tr>
    </table>
    <?php
}

function fs_tool_card_save( $post_id ) {
    if ( ! isset( $_POST['fs_tool_card_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['fs_tool_card_nonce'], 'fs_tool_card_save' ) ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // Use the hex text field as authoritative (synced from color picker)
    $bg     = sanitize_hex_color( $_POST['fs_card_bg_hex'] ?? $_POST['fs_card_bg'] ?? '#ffffff' );
    $icon   = sanitize_text_field( $_POST['fs_card_icon']   ?? '' );
    $badge  = sanitize_text_field( $_POST['fs_card_badge']  ?? '' );
    $link   = esc_url_raw( $_POST['fs_card_link']           ?? '' );
    $target = in_array( $_POST['fs_card_link_target'] ?? '', ['_self','_blank'] ) ? $_POST['fs_card_link_target'] : '_self';

    update_post_meta( $post_id, '_fs_card_bg',          $bg );
    update_post_meta( $post_id, '_fs_card_icon',        $icon );
    update_post_meta( $post_id, '_fs_card_badge',       $badge );
    update_post_meta( $post_id, '_fs_card_link',        $link );
    update_post_meta( $post_id, '_fs_card_link_target', $target );
}
add_action( 'save_post_fs_tool', 'fs_tool_card_save' );

/* ── Admin columns: show card bg + badge in tools list ── */
function fs_tool_admin_columns( $cols ) {
    $new = [];
    foreach ( $cols as $k => $v ) {
        $new[$k] = $v;
        if ( $k === 'title' ) {
            $new['fs_card_badge'] = 'Badge';
            $new['fs_card_bg']    = 'Card BG';
            $new['fs_card_link']  = 'Custom Link';
        }
    }
    return $new;
}
add_filter( 'manage_fs_tool_posts_columns', 'fs_tool_admin_columns' );

function fs_tool_admin_column_content( $col, $post_id ) {
    if ( $col === 'fs_card_badge' ) {
        $v = get_post_meta( $post_id, '_fs_card_badge', true );
        echo $v ? esc_html($v) : '<span style="color:#aaa">--</span>';
    }
    if ( $col === 'fs_card_bg' ) {
        $v = get_post_meta( $post_id, '_fs_card_bg', true ) ?: '#ffffff';
        echo '<span style="display:inline-block;width:22px;height:22px;border-radius:4px;background:' . esc_attr($v) . ';border:1px solid #ddd;vertical-align:middle"></span> ' . esc_html($v);
    }
    if ( $col === 'fs_card_link' ) {
        $v = get_post_meta( $post_id, '_fs_card_link', true );
        echo $v ? '<a href="' . esc_url($v) . '" target="_blank" style="font-size:11px">Custom &#x2197;</a>' : '<span style="color:#aaa">Default</span>';
    }
}
add_action( 'manage_fs_tool_posts_custom_column', 'fs_tool_admin_column_content', 10, 2 );

/* =========================================================
   LOAN CALCULATORS -- SEO meta for all 18 tools
   ========================================================= */
function fs_loan_tools_seo() {
    if ( get_option( 'fs_loan_seo_v1' ) ) return;
    // Only run after tools are inserted
    $count = wp_count_posts( 'fs_tool' );
    if ( ! isset( $count->publish ) || (int)$count->publish < 10 ) return;

    $seo_data = [
        'Mortgage Calculator' => [
            'kw'    => 'mortgage calculator',
            'title' => 'Mortgage Calculator 2026 -- Monthly Payment & Amortization | FinanceSpots',
            'desc'  => 'Free mortgage calculator: enter home price, down payment, interest rate & term to instantly see monthly payment, total interest, and full amortization schedule. No signup.',
            'type'  => 'mortgage_calc',
        ],
        'Auto Loan Calculator' => [
            'kw'    => 'auto loan calculator',
            'title' => 'Auto Loan Calculator 2026 -- Car Payment & Total Cost | FinanceSpots',
            'desc'  => 'Calculate your exact monthly car payment, total interest paid, and true cost of any auto loan. Includes trade-in value, taxes & fees. Free, instant results.',
            'type'  => 'auto_loan_calc',
        ],
        'Personal Loan Calculator' => [
            'kw'    => 'personal loan calculator',
            'title' => 'Personal Loan Calculator 2026 -- Monthly Payment Estimator | FinanceSpots',
            'desc'  => 'Estimate personal loan payments for any amount and rate. Compare 2-7 year terms, see total interest cost, and decide which loan is right for you.',
            'type'  => 'personal_loan_calc',
        ],
        'Student Loan Calculator' => [
            'kw'    => 'student loan calculator',
            'title' => 'Student Loan Calculator 2026 -- Repayment & Interest | FinanceSpots',
            'desc'  => 'Calculate student loan monthly payments, total interest, and payoff date. Supports federal and private loans with multiple repayment plans.',
            'type'  => 'student_loan_calc',
        ],
        'Home Equity Loan Calculator' => [
            'kw'    => 'home equity loan calculator',
            'title' => 'Home Equity Loan Calculator 2026 -- HELOC Payment Tool | FinanceSpots',
            'desc'  => 'Calculate home equity loan or HELOC payments. See how much you can borrow based on your home value and current mortgage balance.',
            'type'  => 'heloc_calc',
        ],
        'Debt Consolidation Calculator' => [
            'kw'    => 'debt consolidation calculator',
            'title' => 'Debt Consolidation Calculator 2026 -- Save Money & Simplify Debt | FinanceSpots',
            'desc'  => 'See if debt consolidation saves money. Enter multiple debts and compare with a single consolidation loan -- monthly savings and interest reduction.',
            'type'  => 'debt_consol_calc',
        ],
        'Loan Comparison Calculator' => [
            'kw'    => 'loan comparison calculator',
            'title' => 'Loan Comparison Calculator 2026 -- Compare Two Loans Side by Side | FinanceSpots',
            'desc'  => 'Compare two loan offers side by side. See which loan costs less in total interest and monthly payments. Free and instant.',
            'type'  => 'loan_compare',
        ],
        'Amortization Calculator' => [
            'kw'    => 'amortization calculator',
            'title' => 'Amortization Calculator 2026 -- Full Loan Schedule | FinanceSpots',
            'desc'  => 'Generate a complete amortization schedule for any loan. See principal vs. interest each month and how extra payments accelerate payoff.',
            'type'  => 'amortization',
        ],
        'Refinance Calculator' => [
            'kw'    => 'mortgage refinance calculator',
            'title' => 'Refinance Calculator 2026 -- Should You Refinance? | FinanceSpots',
            'desc'  => 'Calculate refinance savings: monthly savings, break-even point, and lifetime interest reduction. Includes closing cost analysis. Free tool.',
            'type'  => 'refinance',
        ],
        'FHA Loan Calculator' => [
            'kw'    => 'FHA loan calculator',
            'title' => 'FHA Loan Calculator 2026 -- Payment + MIP Included | FinanceSpots',
            'desc'  => 'Calculate FHA loan monthly payment including upfront MIP (1.75%) and annual MIP. Compare 3.5% down FHA vs. conventional loans instantly.',
            'type'  => 'fha_loan_calc',
        ],
        'VA Loan Calculator' => [
            'kw'    => 'VA loan calculator',
            'title' => 'VA Loan Calculator 2026 -- No PMI Military Loan Payment | FinanceSpots',
            'desc'  => 'Calculate VA loan monthly payment with no PMI. Includes VA funding fee based on service type, down payment, and usage. Free for veterans.',
            'type'  => 'loan_payment',
        ],
        'Balloon Loan Calculator' => [
            'kw'    => 'balloon loan calculator',
            'title' => 'Balloon Loan Calculator 2026 -- Payment & Balloon Amount | FinanceSpots',
            'desc'  => 'Calculate balloon loan monthly payments and final balloon payment. See amortization for 5, 7, and 10-year balloon mortgages.',
            'type'  => 'balloon_calc',
        ],
        'Interest Only Calculator' => [
            'kw'    => 'interest only loan calculator',
            'title' => 'Interest Only Loan Calculator 2026 -- Payment Comparison | FinanceSpots',
            'desc'  => 'Calculate interest-only loan payments and compare with fully amortizing loans. See the cost difference and when P&I payments begin.',
            'type'  => 'interest_only_calc',
        ],
        'Loan Payoff Calculator' => [
            'kw'    => 'loan payoff calculator',
            'title' => 'Loan Payoff Calculator 2026 -- Pay Off Faster With Extra Payments | FinanceSpots',
            'desc'  => 'See how extra monthly payments accelerate your loan payoff. Calculate exact payoff date, months saved, and total interest saved.',
            'type'  => 'loan_payoff_calc',
        ],
        'Monthly Payment Calculator' => [
            'kw'    => 'monthly payment calculator',
            'title' => 'Monthly Payment Calculator 2026 -- Any Loan, Instant Results | FinanceSpots',
            'desc'  => 'Calculate the monthly payment for any loan amount, interest rate, and term. Simple and fast -- no signup needed.',
            'type'  => 'loan_payment',
        ],
        'Loan Affordability Calculator' => [
            'kw'    => 'how much loan can I afford',
            'title' => 'Loan Affordability Calculator 2026 -- How Much Can You Borrow? | FinanceSpots',
            'desc'  => 'Find out how much loan you can afford based on your income, debts, and desired monthly payment. Uses industry-standard DTI guidelines.',
            'type'  => 'affordability',
        ],
        'Commercial Loan Calculator' => [
            'kw'    => 'commercial loan calculator',
            'title' => 'Commercial Loan Calculator 2026 -- Business Real Estate Payments | FinanceSpots',
            'desc'  => 'Calculate commercial real estate and business loan payments. Includes balloon payment, DSCR analysis, and total financing cost.',
            'type'  => 'commercial_calc',
        ],
        'Bridge Loan Calculator' => [
            'kw'    => 'bridge loan calculator',
            'title' => 'Bridge Loan Calculator 2026 -- Short-Term Financing Cost | FinanceSpots',
            'desc'  => 'Estimate bridge loan costs: interest-only payments, origination fees, and total cost of short-term bridge financing for real estate.',
            'type'  => 'bridge_calc',
        ],
    ];

    foreach ( $seo_data as $tool_title => $data ) {
        $posts = get_posts( [
            'post_type'   => 'fs_tool',
            's'           => $tool_title,
            'numberposts' => 5,
            'post_status' => 'publish',
        ] );
        // Find exact match
        $post_id = 0;
        foreach ( $posts as $p ) {
            if ( strtolower( trim($p->post_title) ) === strtolower( trim($tool_title) ) ) {
                $post_id = $p->ID;
                break;
            }
        }
        if ( ! $post_id ) continue;

        // Update tool type if more specific one defined
        if ( ! in_array( $data['type'], ['loan_payment','affordability','amortization','refinance','loan_compare'] ) ) {
            update_post_meta( $post_id, '_fs_tool_type', $data['type'] );
        }

        // RankMath SEO meta
        update_post_meta( $post_id, 'rank_math_focus_keyword', $data['kw'] );
        update_post_meta( $post_id, 'rank_math_title',         $data['title'] );
        update_post_meta( $post_id, 'rank_math_description',   $data['desc'] );
        update_post_meta( $post_id, 'rank_math_robots',        [ 'index', 'follow' ] );

        // No custom schema -- let RankMath handle it via its own UI
    }

    update_option( 'fs_loan_seo_v1', true );
}
add_action( 'admin_init', 'fs_loan_tools_seo', 25 );
add_action( 'init',       'fs_loan_tools_seo', 45 );

/* =========================================================
   MAIN PAGES SEO -- Homepage, About, Pricing, Tools, Blog
   ========================================================= */
function fs_main_pages_seo() {
    if ( get_option( 'fs_main_pages_seo_v1' ) ) return;

    $pages = [
        // slug => [ title, description, focus_keyword ]
        ''           => [
            'title' => 'Free Financial Calculators & Tools 2026 | FinanceSpots',
            'desc'  => 'FinanceSpots offers 110+ free financial calculators for mortgages, VA loans, taxes, investments, retirement, crypto & more. Instant results with PDF export. No signup needed.',
            'kw'    => 'free financial calculators',
        ],
        'all-tools'  => [
            'title' => 'All Financial Tools & Calculators 2026 | FinanceSpots',
            'desc'  => 'Browse 110+ free finance tools: mortgage calculators, investment analyzers, tax estimators, retirement planners, crypto tools and more. Built for US investors & homebuyers.',
            'kw'    => 'financial tools online',
        ],
        'blog'       => [
            'title' => 'Personal Finance Blog 2026 -- Tips, Guides & Insights | FinanceSpots',
            'desc'  => 'Expert personal finance tips, mortgage guides, investment strategies, tax advice and money-saving insights. Weekly updates from the FinanceSpots team.',
            'kw'    => 'personal finance tips 2026',
        ],
        'about'      => [
            'title' => 'About FinanceSpots -- Free Finance Tools Built for Everyone',
            'desc'  => 'FinanceSpots was built by Abdul Rahman to make professional-grade financial tools free and accessible to everyone. Learn about our mission, tools and team.',
            'kw'    => 'about financespots',
        ],
        'contact'    => [
            'title' => 'Contact FinanceSpots -- Get in Touch',
            'desc'  => 'Have a question or suggestion? Contact the FinanceSpots team. We respond within 24 hours.',
            'kw'    => 'contact financespots',
        ],
        'pricing'    => [
            'title' => 'FinanceSpots PRO Plans & Pricing 2026 -- Upgrade for $9/mo',
            'desc'  => 'Upgrade to FinanceSpots PRO for unlimited calculation history, PDF exports, advanced mortgage scenarios, portfolio tracker and ad-free experience. Start at $9/month.',
            'kw'    => 'financial calculator pro plan',
        ],
        'categories' => [
            'title' => 'Financial Calculator Categories | FinanceSpots',
            'desc'  => 'Explore financial calculators by category: loan calculators, investment tools, tax calculators, savings planners, retirement planning, budget analyzers and more.',
            'kw'    => 'financial calculator categories',
        ],
    ];

    foreach ( $pages as $slug => $seo ) {
        if ( $slug === '' ) {
            $page_id = (int) get_option( 'page_on_front' );
            if ( ! $page_id ) {
                $front = get_posts( [ 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 1, 'orderby' => 'ID', 'order' => 'ASC' ] );
                $page_id = $front ? $front[0]->ID : 0;
            }
        } else {
            $page = get_page_by_path( $slug );
            $page_id = $page ? $page->ID : 0;
        }
        if ( ! $page_id ) continue;

        update_post_meta( $page_id, 'rank_math_title',         $seo['title'] );
        update_post_meta( $page_id, 'rank_math_description',   $seo['desc'] );
        update_post_meta( $page_id, 'rank_math_focus_keyword', $seo['kw'] );
        update_post_meta( $page_id, 'rank_math_robots',        [ 'index', 'follow' ] );
        update_post_meta( $page_id, 'rank_math_og_title',      $seo['title'] );
        update_post_meta( $page_id, 'rank_math_og_description',$seo['desc'] );
    }

    update_option( 'fs_main_pages_seo_v1', true );
}
add_action( 'admin_init', 'fs_main_pages_seo', 20 );
add_action( 'init',       'fs_main_pages_seo', 40 );

/* =========================================================
   VA LOAN FUNDING FEE CALCULATOR -- Advanced Page + SEO
   ========================================================= */
function fs_create_va_loan_tool() {
    if ( get_option( 'fs_va_loan_tool_v2' ) ) return;

    // Check if already exists
    $existing = get_posts( [
        'post_type'   => 'fs_tool',
        'title'       => 'VA Loan Funding Fee Calculator',
        'numberposts' => 1,
        'post_status' => 'any',
    ] );

    $id = $existing ? $existing[0]->ID : 0;

    $focus_kw  = 'VA loan funding fee calculator';
    $seo_title = 'VA Loan Funding Fee Calculator 2026 -- Free Military Tool | FinanceSpots';
    $seo_desc  = 'Free VA loan funding fee calculator 2026. Instantly calculate your VA funding fee, monthly PITI payment, amortization schedule, and compare VA vs conventional loan savings. Built for US military veterans.';
    $content   = '<!-- fs_tool_type:va_loan_advanced -->
<p>Use our free <strong>VA loan funding fee calculator</strong> to instantly estimate your VA funding fee, total loan amount, monthly payment (PITI), and amortization schedule. Compare VA loan vs conventional to see your exact savings.</p>
<h2>What Is the VA Loan Funding Fee?</h2>
<p>The VA funding fee is a one-time charge paid to the Department of Veterans Affairs. For first-time use with 0% down, it is 2.15% for regular military and 2.40% for Reserves/National Guard. Veterans with a 10%+ service-connected disability rating are <strong>exempt</strong> from the funding fee.</p>
<h2>2026 VA Funding Fee Rates</h2>
<ul>
<li>First-time use, 0% down: <strong>2.15%</strong> (Regular) / <strong>2.40%</strong> (Reserves)</li>
<li>First-time use, 5%+ down: <strong>1.50%</strong> / <strong>1.75%</strong></li>
<li>First-time use, 10%+ down: <strong>1.25%</strong> / <strong>1.50%</strong></li>
<li>Subsequent use: <strong>3.30%</strong> all categories</li>
<li>IRRRL / Streamline Refinance: <strong>0.50%</strong></li>
</ul>
<h2>How to Use This Calculator</h2>
<ol>
<li>Enter your purchase price and down payment percentage</li>
<li>Select your loan type (purchase, refinance, IRRRL)</li>
<li>Choose your military category and disability status</li>
<li>Enter your interest rate and loan term</li>
<li>Download your full PDF report instantly</li>
</ol>';

    if ( ! $id ) {
        $id = wp_insert_post( [
            'post_type'    => 'fs_tool',
            'post_title'   => 'VA Loan Funding Fee Calculator',
            'post_name'    => 'va-loan-funding-fee-calculator',
            'post_excerpt' => 'Free VA loan funding fee calculator 2026. Estimate your VA funding fee, monthly payment, amortization schedule, and compare VA vs conventional loan -- with PDF export.',
            'post_content' => $content,
            'post_status'  => 'publish',
        ] );
    } else {
        wp_update_post( [
            'ID'           => $id,
            'post_content' => $content,
            'post_name'    => 'va-loan-funding-fee-calculator',
            'post_status'  => 'publish',
        ] );
    }

    if ( ! $id || is_wp_error( $id ) ) return;

    // ── Tool meta ──
    update_post_meta( $id, '_fs_tool_type',     'va_loan_advanced' );
    update_post_meta( $id, '_fs_tool_template', 'va-loan-calculator' );
    update_post_meta( $id, '_fs_tool_featured', '1' );

    // ── Assign taxonomy ──
    $term = get_term_by( 'slug', 'loan-calculators', 'fs_tool_cat' );
    if ( $term ) wp_set_post_terms( $id, [ $term->term_id ], 'fs_tool_cat' );

    // ── RankMath SEO meta ──
    update_post_meta( $id, 'rank_math_focus_keyword',        $focus_kw );
    update_post_meta( $id, 'rank_math_title',                $seo_title );
    update_post_meta( $id, 'rank_math_description',          $seo_desc );
    update_post_meta( $id, 'rank_math_robots',               [ 'index', 'follow' ] );
    update_post_meta( $id, 'rank_math_canonical_url',        home_url( '/tools/va-loan-funding-fee-calculator/' ) );
    update_post_meta( $id, 'rank_math_og_title',             $seo_title );
    update_post_meta( $id, 'rank_math_og_description',       $seo_desc );
    update_post_meta( $id, 'rank_math_twitter_title',        $seo_title );
    update_post_meta( $id, 'rank_math_twitter_description',  $seo_desc );
    update_post_meta( $id, 'rank_math_twitter_use_facebook', 'off' );
    // No custom schema -- let RankMath handle it via its own UI

    // ── Store the tool ID for homepage linking ──
    update_option( 'fs_va_loan_tool_id', $id );
    update_option( 'fs_va_loan_tool_v2', true );
    flush_rewrite_rules();
}
add_action( 'admin_init', 'fs_create_va_loan_tool' );
add_action( 'init',       'fs_create_va_loan_tool', 30 );

/* =========================================================
   AI FINANCIAL DASHBOARD -- Tool Post Creation
   ========================================================= */
function fs_create_ai_dashboard_tool() {
    if ( get_option( 'fs_ai_dashboard_tool_v1' ) ) return;

    // Check if already exists
    $existing = get_page_by_path( 'ai-financial-dashboard', OBJECT, 'fs_tool' );
    if ( $existing ) {
        update_post_meta( $existing->ID, '_fs_tool_type', 'ai_dashboard' );
        update_post_meta( $existing->ID, 'rank_math_title',       'AI Financial Dashboard 2026 | FinanceSpots' );
        update_post_meta( $existing->ID, 'rank_math_description', 'Free AI-powered financial dashboard 2026. Analyze budget, net worth, debt, investments, retirement & taxes in one place. All features work instantly.' );
        update_post_meta( $existing->ID, 'rank_math_focus_keyword', 'ai financial dashboard' );
        update_post_meta( $existing->ID, 'rank_math_robots',      [ 'index', 'follow' ] );
        update_option( 'fs_ai_dashboard_tool_v1', true );
        return;
    }

    // Find or create category
    $cat = get_term_by( 'slug', 'investment-tools', 'fs_tool_cat' );
    if ( ! $cat ) {
        $cat_result = wp_insert_term( 'Investment Tools', 'fs_tool_cat', [ 'slug' => 'investment-tools' ] );
        $cat_id = is_wp_error( $cat_result ) ? 0 : $cat_result['term_id'];
    } else {
        $cat_id = $cat->term_id;
    }

    $post_id = wp_insert_post( [
        'post_title'   => 'AI Financial Dashboard',
        'post_name'    => 'ai-financial-dashboard',
        'post_type'    => 'fs_tool',
        'post_status'  => 'publish',
        'post_content' => '',
        'post_excerpt' => 'All-in-one AI-powered financial dashboard with budget analysis, net worth tracking, debt optimizer, investment projections, retirement planning, and tax optimization.',
        'tax_input'    => $cat_id ? [ 'fs_tool_cat' => [ $cat_id ] ] : [],
    ] );

    if ( $post_id && ! is_wp_error( $post_id ) ) {
        update_post_meta( $post_id, '_fs_tool_type',            'ai_dashboard' );
        update_post_meta( $post_id, 'rank_math_title',          'AI Financial Dashboard 2026 | FinanceSpots' );
        update_post_meta( $post_id, 'rank_math_description',    'Free AI-powered financial dashboard 2026. Analyze budget, net worth, debt, investments, retirement & taxes in one place. All features work instantly.' );
        update_post_meta( $post_id, 'rank_math_focus_keyword',  'ai financial dashboard' );
        update_post_meta( $post_id, 'rank_math_robots',         [ 'index', 'follow' ] );
        if ( $cat_id ) {
            wp_set_post_terms( $post_id, [ $cat_id ], 'fs_tool_cat' );
        }
    }

    update_option( 'fs_ai_dashboard_tool_v1', true );
    flush_rewrite_rules();
}
add_action( 'admin_init', 'fs_create_ai_dashboard_tool' );
add_action( 'init',       'fs_create_ai_dashboard_tool', 30 );

/* =========================================================
   RANKMATH COMPLETE CONFIGURATION
   ========================================================= */
function fs_rankmath_setup() {
    if ( ! class_exists( 'RankMath' ) && ! defined( 'RANK_MATH_VERSION' ) ) return;
    if ( get_option( 'fs_rankmath_configured_v1' ) ) return;

    $site_url  = home_url('/');
    $site_name = get_bloginfo('name') ?: 'FinanceSpots';

    /* ── 1. General Settings ── */
    $general = get_option( 'rank-math-options-general', [] );
    $general = array_merge( $general, [
        'breadcrumbs'                => 'on',
        'breadcrumbs_separator'      => '›',
        'breadcrumbs_home_label'     => 'Home',
        'breadcrumbs_prefix'         => '',
        'noindex_empty_taxonomies'   => 'on',
        'noindex_password_protected' => 'on',
        'attachment_redirect_urls'   => 'on',
        'redirect_cat'               => 'on',
        'redirect_author'            => 'on',
        'open_graph_image'           => FINANCESPOTS_URI . '/assets/images/og-default.png',
        'twitter_card_type'          => 'summary_large_image',
        'facebook_author_urls'       => 'on',
        'local_business_type'        => 'WebSite',
        'local_business_name'        => $site_name,
        'local_business_url'         => $site_url,
        'knowledgegraph_type'        => 'company',
        'knowledgegraph_name'        => $site_name,
        'url'                        => $site_url,
    ] );
    update_option( 'rank-math-options-general', $general );

    /* ── 2. Titles & Descriptions ── */
    $titles = get_option( 'rank-math-options-titles', [] );
    $titles = array_merge( $titles, [
        // Site-wide
        'separator'                  => '|',
        'capitalize_titles'          => 'on',

        // Homepage
        'homepage_title'             => 'FinanceSpots -- Free Financial Calculators & Tools 2026',
        'homepage_description'       => 'Free financial calculators for mortgages, VA loans, taxes, investments, crypto & more. 110+ expert-built tools with instant PDF export. Trusted by US veterans & investors.',

        // Posts
        'post_title'                 => '%title% | FinanceSpots',
        'post_description'           => '%excerpt%',
        'post_robots'                => [ 'index', 'follow' ],

        // fs_tool CPT
        'fs_tool_title'              => '%title% Calculator -- Free Online Tool 2026 | FinanceSpots',
        'fs_tool_description'        => '%excerpt% Use our free %title% instantly -- no signup required.',
        'fs_tool_robots'             => [ 'index', 'follow' ],
        'fs_tool_default_rich_snippet' => 'WebApplication',
        'fs_tool_default_snippet_name' => '%title%',
        'fs_tool_default_snippet_desc' => '%excerpt%',

        // Taxonomy fs_tool_cat
        'fs_tool_cat_title'          => '%term% Finance Tools -- Free Calculators 2026 | FinanceSpots',
        'fs_tool_cat_description'    => 'Browse free %term% -- instant results, no signup, PDF export. Trusted by 50,000+ users.',
        'fs_tool_cat_robots'         => [ 'index', 'follow' ],

        // Pages
        'page_title'                 => '%title% | FinanceSpots',
        'page_description'           => '%excerpt%',

        // Archives
        'archive_robots'             => [ 'index', 'follow' ],

        // Author
        'author_robots'              => [ 'noindex' ],

        // Search
        'search_robots'              => [ 'noindex' ],

        // 404
        '404_robots'                 => [ 'noindex' ],
    ] );
    update_option( 'rank-math-options-titles', $titles );

    /* ── 3. Sitemap Settings ── */
    $sitemap = get_option( 'rank-math-options-sitemap', [] );
    $sitemap = array_merge( $sitemap, [
        'items_per_page'          => 200,
        'include_images'          => 'on',
        'ping_search_engines'     => 'on',
        'exclude_roles'           => [],

        // Enable post types
        'post_sitemap'            => 'on',
        'page_sitemap'            => 'on',
        'fs_tool_sitemap'         => 'on',

        // Enable taxonomies
        'fs_tool_cat_sitemap'     => 'on',
        'category_sitemap'        => 'off',

        // Priority
        'post_priority'           => '0.5',
        'page_priority'           => '0.7',
        'fs_tool_priority'        => '0.9',   // Tools = highest priority
        'fs_tool_cat_priority'    => '0.8',

        // Change frequency
        'post_frequency'          => 'weekly',
        'page_frequency'          => 'monthly',
        'fs_tool_frequency'       => 'weekly',
        'fs_tool_cat_frequency'   => 'weekly',
    ] );
    update_option( 'rank-math-options-sitemap', $sitemap );

    /* ── 4. Schema / Rich Snippets for all fs_tool posts ── */
    $all_tools = get_posts( [
        'post_type'      => 'fs_tool',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ] );

    foreach ( $all_tools as $tool_id ) {
        $tool_title = get_the_title( $tool_id );
        $tool_url   = get_permalink( $tool_id );
        $tool_desc  = get_post_field( 'post_excerpt', $tool_id );
        $tool_type  = get_post_meta( $tool_id, '_fs_tool_type', true );

        // Only set if not already customized
        if ( ! get_post_meta( $tool_id, 'rank_math_title', true ) ) {
            update_post_meta( $tool_id, 'rank_math_title',       $tool_title . ' -- Free Online Calculator 2026 | FinanceSpots' );
            update_post_meta( $tool_id, 'rank_math_description', $tool_desc ? $tool_desc : 'Use our free ' . $tool_title . ' instantly. No signup required. Get instant results with PDF export.' );
            update_post_meta( $tool_id, 'rank_math_focus_keyword', strtolower( $tool_title ) );
        }

        // Robots
        update_post_meta( $tool_id, 'rank_math_robots', [ 'index', 'follow' ] );

        // No custom schema -- let RankMath handle it via its own UI

        // OG / Twitter
        if ( ! get_post_meta( $tool_id, 'rank_math_og_title', true ) ) {
            update_post_meta( $tool_id, 'rank_math_og_title',            get_post_meta( $tool_id, 'rank_math_title', true ) );
            update_post_meta( $tool_id, 'rank_math_og_description',      get_post_meta( $tool_id, 'rank_math_description', true ) );
            update_post_meta( $tool_id, 'rank_math_twitter_title',       get_post_meta( $tool_id, 'rank_math_title', true ) );
            update_post_meta( $tool_id, 'rank_math_twitter_description', get_post_meta( $tool_id, 'rank_math_description', true ) );
        }
    }

    /* ── 5. Homepage SEO (front page post if set) ── */
    $front_id = get_option('page_on_front');
    if ( $front_id ) {
        update_post_meta( $front_id, 'rank_math_title',         'FinanceSpots -- Free Financial Calculators & Tools 2026' );
        update_post_meta( $front_id, 'rank_math_description',   'Free financial calculators: VA loans, mortgages, taxes, investments, crypto & more. 110+ expert tools with PDF export.' );
        update_post_meta( $front_id, 'rank_math_focus_keyword', 'free financial calculators' );
        update_post_meta( $front_id, 'rank_math_robots',        [ 'index', 'follow' ] );
    }

    /* ── 6. Taxonomy SEO ── */
    $tax_seo = [
        'loan-calculators'    => [ 'kw' => 'loan calculators free',          'title' => 'Free Loan Calculators 2026 -- Mortgage, Auto, VA | FinanceSpots',        'desc' => 'Free loan calculators: mortgage, auto, VA loan, personal loan, student loan & more. Instant results with PDF export.' ],
        'investment-tools'    => [ 'kw' => 'investment calculators free',     'title' => 'Free Investment Calculators 2026 -- ROI, Compound Interest | FinanceSpots', 'desc' => 'Free investment tools: ROI, compound interest, dividend, portfolio analyzer & more. AI-enhanced results.' ],
        'tax-calculators'     => [ 'kw' => 'free tax calculator 2026',        'title' => 'Free Tax Calculators 2026 -- Income Tax, Capital Gains | FinanceSpots',    'desc' => 'Free tax calculators: income tax estimator, capital gains, self-employment tax & more. Updated for 2026.' ],
        'savings-planners'    => [ 'kw' => 'savings calculator free',         'title' => 'Free Savings Calculators 2026 -- Emergency Fund, Goals | FinanceSpots',    'desc' => 'Free savings planners: emergency fund, savings goal, CD calculator, high-yield savings & more.' ],
        'retirement-planning' => [ 'kw' => 'retirement calculator free 2026', 'title' => 'Free Retirement Calculators 2026 -- 401k, IRA, FIRE | FinanceSpots',       'desc' => 'Free retirement planning tools: 401k, IRA, FIRE, Social Security, pension calculators & more.' ],
        'currency-converters' => [ 'kw' => 'currency converter free',         'title' => 'Free Currency Converter 2026 -- Live Forex Rates | FinanceSpots',          'desc' => 'Free currency converters with live forex rates. Convert USD, EUR, GBP, JPY & 150+ currencies instantly.' ],
        'budget-analyzers'    => [ 'kw' => 'budget calculator free',          'title' => 'Free Budget Calculators 2026 -- 50/30/20 Rule | FinanceSpots',             'desc' => 'Free budget analyzers: 50/30/20 planner, monthly budget, expense tracker & more.' ],
        'crypto-tools'        => [ 'kw' => 'crypto calculator free',          'title' => 'Free Crypto Calculators 2026 -- Bitcoin, DCA, P&L | FinanceSpots',         'desc' => 'Free crypto tools: Bitcoin profit calculator, DCA simulator, staking rewards, crypto tax & more.' ],
    ];

    foreach ( $tax_seo as $slug => $seo ) {
        $term = get_term_by( 'slug', $slug, 'fs_tool_cat' );
        if ( ! $term ) continue;
        update_term_meta( $term->term_id, 'rank_math_title',         $seo['title'] );
        update_term_meta( $term->term_id, 'rank_math_description',   $seo['desc'] );
        update_term_meta( $term->term_id, 'rank_math_focus_keyword', $seo['kw'] );
        update_term_meta( $term->term_id, 'rank_math_robots',        [ 'index', 'follow' ] );
    }

    update_option( 'fs_rankmath_configured_v1', true );
}
add_action( 'admin_init', 'fs_rankmath_setup', 20 );
add_action( 'init',       'fs_rankmath_setup', 40 );

/* ── Add llms.txt reference to robots.txt via Rank Math filter ── */
add_filter( 'rank_math/robotstxt/extra_rules', function( $rules ) {
    $rules[] = '';
    $rules[] = '# Block RSS/Atom feeds';
    $rules[] = 'Disallow: /feed/';
    $rules[] = 'Disallow: /*/feed/';
    $rules[] = 'Disallow: /comments/feed/';
    $rules[] = '';
    $rules[] = '# Block pagination';
    $rules[] = 'Disallow: /page/';
    $rules[] = '';
    $rules[] = '# Block author archives';
    $rules[] = 'Disallow: /author/';
    $rules[] = '';
    $rules[] = '# Block tag and category feeds';
    $rules[] = 'Disallow: /tag/*/feed/';
    $rules[] = 'Disallow: /category/*/feed/';
    $rules[] = '';
    $rules[] = '# AI crawler discovery';
    $rules[] = 'LLMs: https://financespots.com/llms.txt';
    return $rules;
} );

/* =========================================================
   FIX ALL NOINDEX + INDEXING ISSUES
   Removes noindex from all published content, fixes blog
   visibility, resets Rank Math robots to index/follow.
   Version-gated: fs_fix_indexing_v2
   ========================================================= */
/* ── Custom robots.txt rules via WordPress filter (overrides Rank Math dynamic output) ── */
add_filter( 'robots_txt', function( $output, $public ) {
    if ( ! $public ) return $output;
    $output .= "\n# Block RSS/Atom feeds\n";
    $output .= "Disallow: /feed/\n";
    $output .= "Disallow: /*/feed/\n";
    $output .= "Disallow: /comments/feed/\n";
    $output .= "\n# Block pagination\n";
    $output .= "Disallow: /page/\n";
    $output .= "\n# Block author archives\n";
    $output .= "Disallow: /author/\n";
    $output .= "\n# Block tag and category feeds\n";
    $output .= "Disallow: /tag/*/feed/\n";
    $output .= "Disallow: /category/*/feed/\n";
    $output .= "\n# AI crawler discovery\n";
    $output .= "LLMs: https://financespots.com/llms.txt\n";
    return $output;
}, 99, 2 );

/* ── Remove WordPress emoji scripts (stops JS file crawling) ── */
remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles',     'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles',  'print_emoji_styles' );
add_filter( 'emoji_svg_url', '__return_false' );

/* ── Disable comments site-wide ── */
add_filter( 'comments_open',        '__return_false', 20, 2 );
add_filter( 'pings_open',           '__return_false', 20, 2 );
add_filter( 'comments_array',       '__return_empty_array', 10, 2 );
add_action( 'admin_menu', function() { remove_menu_page('edit-comments.php'); } );
add_action( 'wp_before_admin_bar_render', function() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
} );

function fs_fix_all_indexing_issues() {
    if ( get_option( 'fs_fix_indexing_v3' ) ) return;
    delete_option( 'fs_fix_indexing_v2' );

    /* 0. Fix siteurl and home if still pointing to localhost */
    $current_url = get_option('siteurl');
    if ( strpos($current_url, 'localhost') !== false || strpos($current_url, '127.0.0.1') !== false ) {
        update_option( 'siteurl', 'https://financespots.com' );
        update_option( 'home',    'https://financespots.com' );
    }

    global $wpdb;

    /* 1. Make sure WordPress itself is not blocking search engines */
    update_option( 'blog_public', '1' );

    /* 2. Remove noindex from ALL published posts/pages/tools */
    $wpdb->query(
        "UPDATE {$wpdb->postmeta} pm
         JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         SET pm.meta_value = 'a:2:{i:0;s:5:\"index\";i:1;s:6:\"follow\";}'
         WHERE pm.meta_key = 'rank_math_robots'
         AND pm.meta_value LIKE '%noindex%'
         AND p.post_status = 'publish'"
    );

    /* 3. Set index/follow on ALL published content (belt and suspenders) */
    $all_posts = $wpdb->get_col(
        "SELECT ID FROM {$wpdb->posts}
         WHERE post_status = 'publish'
         AND post_type IN ('post','page','fs_tool')"
    );
    foreach ( $all_posts as $pid ) {
        update_post_meta( (int)$pid, 'rank_math_robots', [ 'index', 'follow' ] );
    }

    /* 4. Fix all taxonomy terms — set index/follow */
    $all_terms = $wpdb->get_col(
        "SELECT term_id FROM {$wpdb->terms}"
    );
    foreach ( $all_terms as $tid ) {
        update_term_meta( (int)$tid, 'rank_math_robots', [ 'index', 'follow' ] );
    }

    /* 5. Reset Rank Math title settings to force index on all types */
    $titles = get_option( 'rank-math-options-titles', [] );
    $titles['post_robots']       = [ 'index', 'follow' ];
    $titles['page_robots']       = [ 'index', 'follow' ];
    $titles['fs_tool_robots']    = [ 'index', 'follow' ];
    $titles['fs_tool_cat_robots']= [ 'index', 'follow' ];
    $titles['archive_robots']    = [ 'index', 'follow' ];
    $titles['author_robots']     = [ 'noindex' ];
    $titles['search_robots']     = [ 'noindex' ];
    $titles['404_robots']        = [ 'noindex' ];
    update_option( 'rank-math-options-titles', $titles );

    /* 6. Make sure sitemap is enabled for all types */
    $sitemap = get_option( 'rank-math-options-sitemap', [] );
    $sitemap['post_sitemap']     = 'on';
    $sitemap['page_sitemap']     = 'on';
    $sitemap['fs_tool_sitemap']  = 'on';
    $sitemap['fs_tool_cat_sitemap'] = 'on';
    update_option( 'rank-math-options-sitemap', $sitemap );

    /* 7. Ping search engines with sitemap */
    $sitemap_url = home_url( '/sitemap_index.xml' );
    wp_remote_get( 'https://www.google.com/ping?sitemap=' . urlencode( $sitemap_url ), [ 'timeout' => 5, 'blocking' => false ] );
    wp_remote_get( 'https://www.bing.com/ping?sitemap=' . urlencode( $sitemap_url ),  [ 'timeout' => 5, 'blocking' => false ] );

    update_option( 'fs_fix_indexing_v3', true );
}
add_action( 'admin_init', 'fs_fix_all_indexing_issues', 5 );
add_action( 'init',       'fs_fix_all_indexing_issues', 5 );

/* =========================================================
   FORCE INDEX ALL TOOLS + PAGES — v4
   Sets index/follow on every published post/page/tool,
   regenerates sitemap cache, pings Google + Bing.
   ========================================================= */
function fs_force_index_all_v4() {
    if ( get_option( 'fs_force_index_v4' ) ) return;

    global $wpdb;

    /* 1. index/follow on ALL published content */
    $all_ids = $wpdb->get_col(
        "SELECT ID FROM {$wpdb->posts}
         WHERE post_status = 'publish'
         AND post_type IN ('post','page','fs_tool')"
    );
    foreach ( $all_ids as $pid ) {
        update_post_meta( (int)$pid, 'rank_math_robots',          [ 'index', 'follow' ] );
        update_post_meta( (int)$pid, 'rank_math_advanced_robots', '' );
    }

    /* 2. index/follow on ALL taxonomy terms */
    $all_terms = $wpdb->get_col( "SELECT term_id FROM {$wpdb->terms}" );
    foreach ( $all_terms as $tid ) {
        update_term_meta( (int)$tid, 'rank_math_robots', [ 'index', 'follow' ] );
    }

    /* 3. Force noindex ONLY on junk — search, author, 404 */
    $titles = get_option( 'rank-math-options-titles', [] );
    $titles['search_robots']          = [ 'noindex' ];
    $titles['404_robots']             = [ 'noindex' ];
    $titles['author_robots']          = [ 'noindex' ];
    $titles['post_robots']            = [ 'index', 'follow' ];
    $titles['page_robots']            = [ 'index', 'follow' ];
    $titles['fs_tool_robots']         = [ 'index', 'follow' ];
    $titles['fs_tool_cat_robots']     = [ 'index', 'follow' ];
    $titles['category_robots']        = [ 'index', 'follow' ];
    $titles['post_tag_robots']        = [ 'index', 'follow' ];
    update_option( 'rank-math-options-titles', $titles );

    /* 4. Ensure sitemap enabled for all types */
    $sitemap = get_option( 'rank-math-options-sitemap', [] );
    $sitemap['post_sitemap']          = 'on';
    $sitemap['page_sitemap']          = 'on';
    $sitemap['fs_tool_sitemap']       = 'on';
    $sitemap['fs_tool_cat_sitemap']   = 'on';
    $sitemap['include_images']        = 'on';
    update_option( 'rank-math-options-sitemap', $sitemap );

    /* 5. Clear Rank Math sitemap cache so it regenerates fresh */
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%rank_math_sitemap%'" );

    /* 6. Re-ping Google and Bing with fresh sitemap */
    $sitemap_url = home_url( '/sitemap_index.xml' );
    wp_remote_get( 'https://www.google.com/ping?sitemap=' . urlencode( $sitemap_url ), [ 'timeout' => 5, 'blocking' => false ] );
    wp_remote_get( 'https://www.bing.com/ping?sitemap='   . urlencode( $sitemap_url ), [ 'timeout' => 5, 'blocking' => false ] );

    update_option( 'fs_force_index_v4', true );
}
add_action( 'admin_init', 'fs_force_index_all_v4', 6 );
add_action( 'init',       'fs_force_index_all_v4', 6 );

/* =========================================================
   CREATE 10 NEW BLOG POSTS — version-gated fs_blogs_v3
   ========================================================= */
function fs_create_blog_posts() {
    if ( get_option('fs_blogs_v3') ) return;
    delete_option('fs_blogs_v2');
    delete_option('fs_blogs_v1');

    $cat_id = get_cat_ID('Finance Tips');
    if ( ! $cat_id ) {
        $term = wp_insert_term( 'Finance Tips', 'category', [ 'slug' => 'finance-tips' ] );
        $cat_id = is_wp_error($term) ? 1 : $term['term_id'];
    }

    // Curated Unsplash images — each specifically matched to its section topic
    $img = [
        // VA Loan / Military / Home buying
        'flag_house'    => 'https://images.unsplash.com/photo-1583608205776-bfd35f0d9f83?w=900&q=80',
        'veteran_home'  => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=900&q=80',
        'home_keys'     => 'https://images.unsplash.com/photo-1582407947304-fd86f28320c7?w=900&q=80',
        'nice_house'    => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=900&q=80',
        // Savings / Money
        'piggy_bank'    => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=900&q=80',
        'cash_savings'  => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=900&q=80',
        'savings_jar'   => 'https://images.unsplash.com/photo-1567427017947-545c5f8d16ad?w=900&q=80',
        'bank_app'      => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=900&q=80',
        // Budget / Planning
        'budget_notebook'=> 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=900&q=80',
        'expense_sheet' => 'https://images.unsplash.com/photo-1554224154-22dec7ec8818?w=900&q=80',
        'planner_desk'  => 'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=900&q=80',
        // Credit Score
        'credit_cards'  => 'https://images.unsplash.com/photo-1559526324-593bc073d938?w=900&q=80',
        'credit_report' => 'https://images.unsplash.com/photo-1586034679970-cb7b5fc4928a?w=900&q=80',
        'credit_score'  => 'https://images.unsplash.com/photo-1616077167555-51f6bc516dfa?w=900&q=80',
        // Investment / Stock Market
        'stock_screen'  => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=900&q=80',
        'trading_charts'=> 'https://images.unsplash.com/photo-1642790551116-18e4f54b8e5c?w=900&q=80',
        'portfolio'     => 'https://images.unsplash.com/photo-1543286386-2e659306cd6c?w=900&q=80',
        'etf_chart'     => 'https://images.unsplash.com/photo-1535320903710-d993d3d77d29?w=900&q=80',
        // Student Loan / Education
        'graduate'      => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=900&q=80',
        'student_debt'  => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=900&q=80',
        'loan_doc'      => 'https://images.unsplash.com/photo-1554224154-26032ffc0d07?w=900&q=80',
        // Bitcoin / Crypto / Gold
        'bitcoin_coin'  => 'https://images.unsplash.com/photo-1518546305927-5a555bb7020d?w=900&q=80',
        'crypto_chart'  => 'https://images.unsplash.com/photo-1642790595397-7047b1b50e7e?w=900&q=80',
        'gold_bars'     => 'https://images.unsplash.com/photo-1610375461246-83df859d849d?w=900&q=80',
        'gold_coins'    => 'https://images.unsplash.com/photo-1624365168968-f283d506c6b6?w=900&q=80',
        // Emergency Fund
        'emergency_cash'=> 'https://images.unsplash.com/photo-1559526323-cb2f2fe2591b?w=900&q=80',
        'hysa_phone'    => 'https://images.unsplash.com/photo-1601597111158-2fceff292cdc?w=900&q=80',
        // Retirement
        'retire_couple' => 'https://images.unsplash.com/photo-1505832018823-50331d70d237?w=900&q=80',
        'retirement_plan'=> 'https://images.unsplash.com/photo-1556742031-c6961e8560b0?w=900&q=80',
        '401k_screen'   => 'https://images.unsplash.com/photo-1604594849809-dfedbc827105?w=900&q=80',
        // Home buying
        'house_exterior'=> 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=900&q=80',
        'mortgage_doc'  => 'https://images.unsplash.com/photo-1554224155-1696413565d3?w=900&q=80',
        'house_sign'    => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=900&q=80',
        'laptop_finance'=> 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=900&q=80',
    ];

    $fi = function($url,$alt,$cap='') {
        $c = $cap ? "<p style='text-align:center;color:#64748b;font-size:.85rem;margin-top:8px;font-style:italic'><em>$cap</em></p>" : '';
        return "<figure style='margin:36px 0;text-align:center'><img src='$url' alt='".esc_attr($alt)."' style='max-width:100%;border-radius:14px;box-shadow:0 6px 28px rgba(0,0,0,.14)' loading='lazy' width='900'/>".$c."</figure>";
    };

    $kt = function($items) {
        $li = '';
        foreach($items as $i) $li .= "<li style='margin:6px 0;color:#065f46'>$i</li>";
        return "<div style='background:rgba(16,185,129,.10);border-left:5px solid #10B981;padding:18px 22px;border-radius:10px;margin:28px 0'><strong style='color:#10B981;font-size:1.05rem'>&#9989; Key Takeaways</strong><ul style='margin:10px 0 0 16px;padding:0'>$li</ul></div>";
    };
    $pt = function($text) {
        return "<div style='background:rgba(59,130,246,.09);border-left:5px solid #3b82f6;padding:14px 20px;border-radius:10px;margin:22px 0'><strong style='color:#3b82f6'>&#128161; Pro Tip:</strong> <span style='color:#1e3a5f'>$text</span></div>";
    };
    $faq = function($pairs) {
        $html = "<div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:24px 28px;margin:36px 0'><h2 style='color:#0f172a;margin-top:0;font-size:1.3rem'>&#10067; Frequently Asked Questions</h2>";
        foreach($pairs as $q => $a) {
            $html .= "<div style='margin-bottom:18px'><p style='font-weight:700;color:#0f172a;margin-bottom:6px'>$q</p><p style='color:#475569;margin:0'>$a</p></div>";
        }
        return $html . "</div>";
    };
    $cta = function($url,$label) {
        return "<div style='text-align:center;margin:36px 0'><a href='$url' style='display:inline-block;background:linear-gradient(135deg,#10B981,#059669);color:#fff;font-weight:700;font-size:1.05rem;padding:14px 32px;border-radius:10px;text-decoration:none;box-shadow:0 4px 14px rgba(16,185,129,.35)'>$label &rarr;</a></div>";
    };

    $posts = [
        [
            'title'   => 'VA Loan Benefits: The Complete Guide for Veterans in 2026',
            'slug'    => 'va-loan-benefits-complete-guide-veterans-2026',
            'excerpt' => 'VA loans offer zero down payment, no PMI, and competitive rates. This complete guide covers every VA loan benefit, eligibility, and how to apply in 2026.',
            'keyword' => 'VA loan benefits 2026',
            'desc'    => 'VA loans offer zero down payment, no PMI, and competitive rates for veterans. Complete guide to VA loan benefits, eligibility, and application in 2026.',
            'content' =>
                $kt(['VA loans offer $0 down payment — you buy a home with zero out-of-pocket cost','No Private Mortgage Insurance (PMI) ever — saves $150–$300/month vs conventional','VA interest rates are 0.25%–0.50% lower than conventional — saving $30,000+ over 30 years','Veterans with 10%+ disability rating pay zero VA funding fee','You can reuse your VA loan benefit unlimited times throughout your life'])
                . '<p>You served your country. In return, the government created one of the most powerful home-buying benefits ever built. Yet millions of eligible veterans never use it. This guide will change that.</p>'
                . $fi($img['flag_house'],'American flag on house representing VA loan home ownership for veterans','The VA home loan benefit has helped over 28 million veterans become homeowners since 1944.')
                . '<h2>What Is a VA Loan?</h2><p>A VA loan is a mortgage backed by the U.S. Department of Veterans Affairs. Private lenders — banks, credit unions, and mortgage companies — actually give you the money. But because the VA guarantees part of the loan, lenders can offer you much better terms than a regular mortgage. Think of it as the government co-signing your home loan.</p>'
                . $pt('Your lender can pull your Certificate of Eligibility (COE) online in minutes. You do not need to dig up service records yourself — just ask them to do it.')
                . '<h2>The 5 Benefits That Make VA Loans Unbeatable</h2><ul><li><strong>$0 Down Payment</strong> — buy a $400,000 home with nothing down. A conventional loan at 10% down = $40,000 cash needed. VA loan = $0.</li><li><strong>No PMI Ever</strong> — conventional loans charge $150–$300/month for Private Mortgage Insurance when you put less than 20% down. That is $54,000+ over 30 years. VA loans never charge PMI.</li><li><strong>Lower Interest Rates</strong> — VA rates run 0.25%–0.50% below conventional. On a $300,000 loan, that saves over $30,000 in total interest.</li><li><strong>Flexible Credit Requirements</strong> — most VA lenders approve credit scores of 580–620. Conventional loans usually require 640–680+.</li><li><strong>Reusable for Life</strong> — pay off your VA loan and your full benefit is restored. You can use it again on your next home.</li></ul>'
                . $fi($img['nice_house'],'Beautiful single-family home purchased with VA loan zero down payment benefit','VA loans make it possible to buy a quality home with $0 down — no tricks, no catches.')
                . '<h2>The One Cost: VA Funding Fee</h2><p>The VA funding fee is a one-time payment that keeps the program funded for future veterans. It ranges from 1.25% to 3.3% depending on your down payment and whether it is your first VA loan.</p><p>On a $300,000 loan with 0% down (first use), the fee is $6,450. You can roll it into the loan — no cash out of pocket. And if you have a 10%+ service-connected disability rating, you are completely exempt. Zero fee.</p>'
                . $cta(home_url('/tools/va-loan-funding-fee-calculator/'),'Calculate Your Exact VA Funding Fee Free')
                . '<h2>VA Loan vs Conventional: Side by Side</h2><table style="width:100%;border-collapse:collapse;margin:24px 0"><tr style="background:#0f172a;color:#10B981"><th style="padding:12px;text-align:left">Feature</th><th style="padding:12px">VA Loan</th><th style="padding:12px">Conventional</th></tr><tr style="background:#1e293b;color:#cbd5e1"><td style="padding:10px">Down Payment</td><td style="padding:10px;text-align:center;color:#10B981;font-weight:700">0%</td><td style="padding:10px;text-align:center">3%–20%</td></tr><tr style="background:#0f172a;color:#cbd5e1"><td style="padding:10px">PMI</td><td style="padding:10px;text-align:center;color:#10B981;font-weight:700">Never</td><td style="padding:10px;text-align:center">Required &lt;20% down</td></tr><tr style="background:#1e293b;color:#cbd5e1"><td style="padding:10px">Min. Credit Score</td><td style="padding:10px;text-align:center;color:#10B981;font-weight:700">580+</td><td style="padding:10px;text-align:center">640–680+</td></tr><tr style="background:#0f172a;color:#cbd5e1"><td style="padding:10px">Interest Rate</td><td style="padding:10px;text-align:center;color:#10B981;font-weight:700">0.25–0.50% lower</td><td style="padding:10px;text-align:center">Standard market rate</td></tr></table>'
                . $fi($img['veteran_home'],'Veteran signing VA loan paperwork at lender office with mortgage documents','The VA loan process takes 30–45 days from application to closing — similar to any mortgage.')
                . '<h2>Do You Qualify?</h2><p>You likely qualify if you meet one of these:</p><ul><li>90 consecutive days of active service during wartime</li><li>181 days of active service during peacetime</li><li>6+ years in the National Guard or Reserves</li><li>Surviving spouse of a service member who died in the line of duty</li></ul>'
                . $pt('Apply for a VA loan even if you have used it before. Partial entitlement still works, and full entitlement is restored once the previous loan is paid off or assumed by another eligible veteran.')
                . $fi($img['home_keys'],'Home keys on table representing successful VA loan closing and home purchase','Closing day with a VA loan — your service turns into keys to your own front door.')
                . $faq(['Can I use a VA loan more than once?' => 'Yes. Your VA benefit is reusable throughout your life. Once you pay off a VA loan, full entitlement is restored and you can use it again for your next home purchase.','What credit score do I need for a VA loan?' => 'The VA itself does not set a minimum credit score, but most lenders require 580–620. A score of 680+ will get you the best interest rates available.','Can I use a VA loan to buy a multi-family property?' => 'Yes — you can use a VA loan to buy a 2–4 unit property as long as you live in one of the units as your primary residence. This is one of the most powerful wealth-building strategies available to veterans.']),
        ],
        [
            'title'   => 'How to Save $10,000 in 6 Months: A Realistic Step-by-Step Plan',
            'slug'    => 'how-to-save-10000-in-6-months-2026',
            'excerpt' => 'Saving $10,000 in 6 months is possible on an average income. Here is a step-by-step plan that actually works — with math, automation, and income-boosting strategies.',
            'keyword' => 'how to save 10000 in 6 months',
            'desc'    => 'Save $10,000 in 6 months with this step-by-step plan. Includes budgeting strategies, automation tips, and income boosters that work on an average salary.',
            'content' =>
                $kt(['You need to save $1,667/month — that is $417/week or $59/day','Most people find $300–$800/month in forgotten spending during a spending audit','Automating savings on payday is the single most important habit to build','Adding $300–$500/month in side income makes the goal much easier to reach','A high-yield savings account (4–5% APY) earns you interest while you save'])
                . '<p>Saving $10,000 in six months is not magic. It is math plus a system. The people who pull it off are not earning six figures. They just have a plan and follow it. This guide is that plan.</p>'
                . $fi($img['piggy_bank'],'Pink piggy bank with coins and dollar bills representing personal savings goal','Saving $10,000 in 6 months requires saving $1,667/month — challenging but absolutely achievable.')
                . '<h2>The Math First</h2><p>To hit $10,000 in 6 months, you need to save <strong>$1,667 per month</strong>. That is $417 per week, or about $59 per day. Write that number down — it is your only target. Everything else in this guide helps you hit it.</p>'
                . '<h2>Step 1: Do a 60-Day Spending Audit</h2><p>Before cutting anything, find out where your money actually goes. Download your last 60 days of bank and credit card statements and categorize every transaction. Be honest.</p><p>Most people find $300–$800/month in spending they completely forgot about — unused gym memberships, streaming services, food delivery fees, auto-renewing subscriptions. This step alone can fund a huge chunk of your $1,667 monthly target.</p>'
                . $pt('Use your bank\'s built-in spending breakdown or a free app like Mint or YNAB. Seeing the numbers in chart form makes the problem impossible to ignore.')
                . $fi($img['budget_notebook'],'Person writing monthly budget in notebook with calculator and pen on desk','A spending audit typically reveals $300–$800 in monthly spending most people never consciously chose.')
                . '<h2>Step 2: Build a Lean 6-Month Budget</h2><p>This budget is temporary. You are not giving things up forever — just for 6 months. Here is how to categorize your spending:</p><ul><li><strong>Keep (non-negotiables):</strong> Rent, utilities, groceries, insurance, minimum debt payments</li><li><strong>Reduce:</strong> Restaurants (max 4x/month), entertainment, clothing</li><li><strong>Pause temporarily:</strong> Streaming extras, subscriptions you can cancel, impulse spending</li></ul>'
                . $fi($img['expense_sheet'],'Budget spreadsheet showing monthly income expenses and savings target breakdown','A lean 6-month budget is temporary — you are trading short-term restriction for long-term security.')
                . '<h2>Step 3: Automate on Payday — The #1 Habit</h2><p>Do not try to save what is left over at the end of the month. There is never anything left over. Instead, on the exact day you get paid, automatically transfer $1,667 to a separate savings account.</p><p>Open a dedicated account and call it "10K Goal." Keep it at a different bank so you are not tempted to move money back. Out of sight, out of mind — and earning 4–5% APY.</p>'
                . $pt('Set up the automatic transfer to fire at 6 AM on your payday, before you even log into your account. This one setup takes 10 minutes and does the heavy lifting for 6 months.')
                . $fi($img['cash_savings'],'Stack of cash and coins next to savings goal tracker representing $10,000 milestone','Automating your savings removes the daily decision — once it is set up, progress happens automatically.')
                . '<h2>Step 4: Add Extra Income — This Is the Real Accelerator</h2><p>Cutting expenses gets you halfway. Adding income gets you there faster with less pain. Here are realistic options that work:</p><ul><li><strong>Sell unused items</strong> on Facebook Marketplace or eBay — $200–$1,000 one-time</li><li><strong>Freelance your skills</strong> for 5 hours/week at $25/hr = $500/month</li><li><strong>Food delivery or rideshare</strong> on weekends = $200–$400/month</li><li><strong>Request overtime</strong> at your current job — one extra shift/week adds up fast</li></ul><p>Even $300/month in extra income turns $1,667 into a much easier $1,367/month from your main budget.</p>'
                . $cta(home_url('/tools/savings-goal-calculator/'),'Calculate Your Exact Savings Timeline Free')
                . $faq(['Is it realistic to save $10,000 in 6 months on an average salary?' => 'Yes — but it requires genuine changes to both spending and, ideally, income. On a $55,000 salary ($3,900/month take-home), saving $1,667/month means living on about $2,200/month. Tight, but done every day by real people. Adding even $300/month in side income makes it much more comfortable.','Should I pause investing while saving toward a goal?' => 'If your employer offers a 401(k) match, keep contributing enough to get the full match — that is a 50–100% instant return. For additional investing beyond the match, it is reasonable to temporarily pause while sprinting toward a 6-month goal like this.','What type of account should I use for my $10K goal?' => 'A High-Yield Savings Account (HYSA) at Marcus, Ally, or SoFi. They currently pay 4–5% APY, are FDIC insured, have no fees, and let you withdraw anytime. Your money grows while you save.']),
        ],
        [
            'title'   => 'How to Improve Your Credit Score Fast in 2026: 9 Proven Strategies',
            'slug'    => 'how-to-improve-credit-score-fast-2026',
            'excerpt' => 'A good credit score saves you tens of thousands of dollars over your lifetime. These 9 proven strategies can improve your score — some work within 30 days.',
            'keyword' => 'how to improve credit score fast 2026',
            'desc'    => '9 proven strategies to improve your credit score fast in 2026. Some work within 30 days. Learn what actually moves the needle on your FICO score.',
            'content' =>
                $kt(['Payment history (35%) is the biggest factor — one missed payment drops your score 90–110 points','Keeping credit utilization under 10% can boost your score 50–80 points within 30 days','1 in 5 Americans has a credit report error that is lowering their score','Requesting a credit limit increase (no hard pull) instantly lowers your utilization ratio','A 100-point score improvement can save $30,000+ on a 30-year mortgage'])
                . '<p>Your credit score affects your mortgage rate, car loan, apartment, and sometimes your job offer. A 100-point difference can mean paying $30,000 more in mortgage interest over 30 years. Here is how to improve yours fast — some strategies work within 30 days.</p>'
                . $fi($img['credit_cards'],'Multiple credit cards spread out on table representing credit utilization and credit score factors','Credit cards are tools — used correctly, they build your score; misused, they hurt it.')
                . '<h2>What Makes Up Your Credit Score</h2><ul><li><strong>35% — Payment History:</strong> Paid on time? This is the biggest factor. One missed payment drops your score 90–110 points.</li><li><strong>30% — Credit Utilization:</strong> How much of your available credit are you using? Over 30% hurts. Under 10% is ideal.</li><li><strong>15% — Length of History:</strong> How long your accounts have been open. Older = better.</li><li><strong>10% — Credit Mix:</strong> Having both credit cards and installment loans helps.</li><li><strong>10% — New Credit:</strong> Each new application temporarily lowers your score slightly.</li></ul>'
                . '<h2>Strategy 1: Pay Down Credit Card Balances (Fastest Impact)</h2><p>Utilization is 30% of your score and the fastest thing you can change. If you owe $3,500 on a $5,000 limit card, your utilization is 70% — that is hurting you badly.</p><p>Pay it down to $500 (10% utilization) and you could see a 50–80 point gain within 30 days. No other single action gives you this much return this quickly.</p>'
                . $pt('Pay balances down before your statement closing date — not just before the due date. Your utilization is reported on the closing date, so paying early means a lower number gets reported to the bureaus.')
                . $fi($img['credit_report'],'Person reviewing official credit report document with highlighted errors and account history','Get your free credit reports at AnnualCreditReport.com — all three bureaus, every year.')
                . '<h2>Strategy 2: Set Up Autopay Today</h2><p>Payment history is 35% of your score. One missed payment stays on your report for 7 years. The fix is simple: set up autopay for the minimum payment on every account you have. Do it now — takes 10 minutes and permanently removes the risk of forgetting.</p>'
                . '<h2>Strategy 3: Dispute Errors on Your Credit Report</h2><p>1 in 5 Americans has a credit report error lowering their score. Common errors: accounts that are not yours, payments marked late that were paid on time, wrong balances. Get free reports at <strong>AnnualCreditReport.com</strong> — all three bureaus. Dispute errors online. The bureau has 30 days to investigate and must remove inaccurate items.</p>'
                . $fi($img['credit_score'],'Credit score dashboard on phone showing score improving from 620 to 750 over six months','Consistent on-time payments and low utilization move your score upward every single month.')
                . '<h2>Strategy 4: Request a Credit Limit Increase</h2><p>Call your card issuer and ask for a higher limit. Many will raise it without a hard inquiry after 12 months of good history. If your balance stays at $1,500 and your limit goes from $3,000 to $6,000, your utilization drops from 50% to 25% — a meaningful score boost for zero effort.</p>'
                . '<h2>Strategy 5: Keep Old Accounts Open</h2><p>Closing a credit card raises your utilization and shortens your credit history. Even if you do not use an old card, keep it open. Charge something small every few months and pay it off. That keeps it active and continues building your history length.</p>'
                . '<h2>Credit Score Ranges 2026</h2><table style="width:100%;border-collapse:collapse;margin:24px 0"><tr style="background:#0f172a;color:#10B981"><th style="padding:12px">Score</th><th style="padding:12px">Rating</th><th style="padding:12px">What You Get</th></tr><tr style="background:#1e293b;color:#cbd5e1"><td style="padding:10px;text-align:center">800–850</td><td style="padding:10px;text-align:center;color:#10B981">Exceptional</td><td style="padding:10px">Best rates available</td></tr><tr style="background:#0f172a;color:#cbd5e1"><td style="padding:10px;text-align:center">740–799</td><td style="padding:10px;text-align:center;color:#22d3ee">Very Good</td><td style="padding:10px">Near-best rates</td></tr><tr style="background:#1e293b;color:#cbd5e1"><td style="padding:10px;text-align:center">670–739</td><td style="padding:10px;text-align:center;color:#fbbf24">Good</td><td style="padding:10px">Approved for most products</td></tr><tr style="background:#0f172a;color:#cbd5e1"><td style="padding:10px;text-align:center">580–669</td><td style="padding:10px;text-align:center;color:#f97316">Fair</td><td style="padding:10px">Higher rates, limited options</td></tr></table>'
                . $cta(home_url('/tools/mortgage-calculator/'),'See How Your Score Affects Your Mortgage Rate')
                . $faq(['How fast can I realistically improve my credit score?' => 'Paying down credit card balances can show improvement within 30–45 days once your card reports the new balance. Disputing and removing errors can also cause quick jumps. Going from 620 to 700 typically takes 3–6 months of consistent positive habits.','Does checking my own credit score hurt it?' => 'No. Checking your own score is a "soft inquiry" and has zero impact. Only applications for new credit (hard inquiries) can temporarily lower your score by 5–10 points.','Should I close credit cards I do not use?' => 'Usually no. Closing a card reduces your total available credit (raising utilization) and can shorten your history. Keep old cards open — use them occasionally for a small purchase and pay it off immediately.']),
        ],
        [
            'title'   => 'Index Fund Investing for Beginners: Build Wealth the Simple Way in 2026',
            'slug'    => 'index-fund-investing-beginners-guide-2026',
            'excerpt' => 'Index fund investing is the simplest, most proven way to build long-term wealth. This beginner guide explains how to start in 2026 — even with just $100.',
            'keyword' => 'index fund investing for beginners 2026',
            'desc'    => 'Index fund investing is the simplest proven path to long-term wealth. This beginner guide explains how to start in 2026 with any amount of money.',
            'content' =>
                $kt(['Warren Buffett recommends S&P 500 index funds for most investors — and the data agrees','90% of professional fund managers fail to beat index funds over 15 years','A 1% annual fee vs 0.05% fee costs you $40,000 on a single $10,000 investment over 30 years','$100/month invested for 30 years at 10% = over $200,000 — even on a modest income','You can start investing today with $1 at Fidelity, Vanguard, or Schwab — no minimums'])
                . '<p>Warren Buffett — the greatest investor alive — consistently recommends that most people put their money in a low-cost S&amp;P 500 index fund. Coming from someone who made billions picking individual stocks, that advice is worth paying attention to. This guide explains exactly how to do it.</p>'
                . $fi($img['stock_screen'],'Live stock market screens showing S&P 500 index fund prices and trading data','The S&P 500 has returned an average of ~10% per year over the past 100 years — through crashes, wars, and recessions.')
                . '<h2>What Is an Index Fund?</h2><p>An index fund is an investment that automatically tracks a market index. The S&amp;P 500 contains the 500 largest U.S. companies — Apple, Microsoft, Amazon, Nvidia, Google, and 495 others.</p><p>When you buy one share of an S&amp;P 500 index fund, you instantly own a tiny slice of all 500 companies. When they grow in value, your investment grows. Over any 20-year period in history, the S&amp;P 500 has always gone up.</p>'
                . '<h2>Why 90% of Professional Managers Lose to Index Funds</h2><p>Highly paid analysts at major banks spend thousands of hours picking stocks every year. About 90% of them fail to beat a simple index fund over 15 years — especially after fees. Index funds win by not trying to be clever. They hold everything and charge almost nothing.</p>'
                . $pt('Do not try to pick the "right time" to invest. Time in the market beats timing the market every time. Start today, even if the market feels high. Missing even the 10 best market days in a decade can cut your returns in half.')
                . $fi($img['trading_charts'],'Detailed stock market trading charts showing candlestick patterns and volume indicators','Professional fund managers with Bloomberg terminals still lose to index funds 90% of the time.')
                . '<h2>The Fee Difference That Costs You $40,000</h2><p>Active funds charge 0.5%–1.5%/year in management fees. Index funds charge 0.03%–0.05%. That sounds tiny. But on $10,000 at 10% annual return over 30 years:</p><ul><li>1% annual fee: you end up with <strong>$132,000</strong></li><li>0.05% annual fee: you end up with <strong>$172,000</strong></li></ul><p>The fee difference alone costs you <strong>$40,000</strong>. Over a full career of saving, this compounds to $200,000+ in lost wealth.</p>'
                . '<h2>Best Index Funds for Beginners in 2026</h2><ul><li><strong>VTI — Vanguard Total Stock Market:</strong> 0.03% fee. Owns every publicly traded U.S. company — 3,700+ stocks in one fund.</li><li><strong>FZROX — Fidelity ZERO Total Market:</strong> 0.00% fee. Literally free. Only at Fidelity.</li><li><strong>IVV — iShares Core S&amp;P 500:</strong> 0.03% fee. Tracks the 500 largest U.S. companies.</li><li><strong>VXUS — Vanguard Total International:</strong> 0.07% fee. Adds global diversification outside the U.S.</li></ul>'
                . $fi($img['portfolio'],'Investment portfolio allocation chart showing diversified index fund holdings by percentage','Two funds — VTI (US) + VXUS (International) — cover 99% of the global stock market.')
                . '<h2>How to Start in 4 Steps</h2><ol><li><strong>Open a free account</strong> at Fidelity, Vanguard, or Schwab — no minimums, no commissions</li><li><strong>Choose your account type:</strong> Roth IRA for tax-free growth (best for most beginners), or 401(k) first if your employer matches</li><li><strong>Pick 1–2 funds:</strong> VTI + VXUS is all you need</li><li><strong>Set up automatic monthly contributions:</strong> $100/month for 30 years grows to $200,000+ at historical returns</li></ol>'
                . $fi($img['etf_chart'],'Long-term compound growth chart showing $100 monthly investment growing over 30 years','Compound interest rewards patience above everything else — the earlier you start, the more powerful it becomes.')
                . $cta(home_url('/tools/investment-calculator/'),'See How Your Index Fund Investment Grows Over Time')
                . $faq(['How much money do I need to start investing in index funds?' => 'At Fidelity, you can open a Roth IRA and start with $1 — literally. Vanguard and Schwab also have zero minimum for ETFs. There is no reason to wait until you have a large sum. Start small and add monthly.','Should I invest in index funds when the market is at all-time highs?' => 'Yes — historically, all-time highs are followed by more all-time highs. Missing the best market days (often during volatile periods) severely reduces long-term returns. Consistent monthly investing regardless of market level is the winning strategy.','Roth IRA or 401(k) — which should I choose first?' => 'If your employer offers a 401(k) match, contribute enough to get the full match first — that is an instant 50–100% return. Then open a Roth IRA and contribute up to $7,000/year. Then return to the 401(k) if you have more to invest.']),
        ],
        [
            'title'   => '50/30/20 Budget Rule: The Simplest Budgeting System That Actually Works',
            'slug'    => '50-30-20-budget-rule-guide-2026',
            'excerpt' => 'The 50/30/20 rule is the simplest budgeting method that works. This guide explains how to implement it in 2026 with real examples and a free budget calculator.',
            'keyword' => '50 30 20 budget rule 2026',
            'desc'    => 'The 50/30/20 budget rule is the simplest way to manage money and make financial progress. Learn how to apply it in 2026 with real examples.',
            'content' =>
                $kt(['The 50/30/20 rule splits your income into just 3 buckets: Needs, Wants, Savings','50% goes to needs (rent, groceries, bills), 30% to wants, 20% to savings and debt','On a $4,000/month income: $2,000 needs, $1,200 wants, $800 savings','If your needs exceed 50%, increase income or reduce fixed costs — do not cut savings','This rule works because it is simple enough to actually follow long-term'])
                . '<p>Most budgets fail not because people are bad with money, but because the system is too complicated. Tracking 25 categories feels like a second job. Miss one week and the whole thing collapses. The 50/30/20 rule is different — it is three numbers, and it covers everything that matters.</p>'
                . $fi($img['budget_notebook'],'Open budget planning notebook with pen showing monthly income expenses and 50/30/20 allocation','The 50/30/20 rule fits on one page — simplicity is why it works when complex systems fail.')
                . '<h2>The Rule Explained Simply</h2><p>Take your monthly after-tax income and divide it into three categories:</p><ul><li><strong>50% — Needs:</strong> Everything required to maintain your life. Rent, utilities, groceries (not restaurants), health insurance, minimum debt payments, car payment, gas, basic phone plan.</li><li><strong>30% — Wants:</strong> Things that improve life but are not essential. Restaurants, streaming services, gym membership, vacations, shopping, hobbies, coffee shops.</li><li><strong>20% — Savings and Debt Payoff:</strong> Your financial future. Emergency fund, retirement (401k/IRA), investing, extra debt payments above minimums.</li></ul>'
                . '<h2>Real Numbers: $4,000/Month Take-Home Pay</h2><table style="width:100%;border-collapse:collapse;margin:24px 0"><tr style="background:#0f172a;color:#10B981"><th style="padding:12px;text-align:left">Category</th><th style="padding:12px">Percentage</th><th style="padding:12px">Monthly Amount</th></tr><tr style="background:#1e293b;color:#cbd5e1"><td style="padding:10px">Needs (rent, food, bills)</td><td style="padding:10px;text-align:center">50%</td><td style="padding:10px;text-align:center;font-weight:700">$2,000</td></tr><tr style="background:#0f172a;color:#cbd5e1"><td style="padding:10px">Wants (dining, fun, shopping)</td><td style="padding:10px;text-align:center">30%</td><td style="padding:10px;text-align:center;font-weight:700">$1,200</td></tr><tr style="background:#1e293b;color:#cbd5e1"><td style="padding:10px">Savings and Debt Payoff</td><td style="padding:10px;text-align:center">20%</td><td style="padding:10px;text-align:center;color:#10B981;font-weight:700">$800</td></tr></table>'
                . $fi($img['planner_desk'],'Weekly budget planner on desk with colored markers showing spending categories organized','A simple weekly check of 3 numbers keeps you on track without turning budgeting into a second job.')
                . '<h2>Need vs Want: The Gray Areas</h2><p>The confusing part is borderline items. Here is how to think about them clearly:</p><ul><li><strong>Phone:</strong> Needs cover a basic plan. The premium unlimited plan on the latest iPhone — the difference is a want.</li><li><strong>Car:</strong> If you need it for work, the car is a need. A $45,000 financed SUV when a $20,000 car works just as well — the upgrade is a want.</li><li><strong>Groceries:</strong> Store brand staples are a need. Choosing premium brands every time is a want.</li></ul><p>This is not about shame. It is about being honest with yourself so you can make real choices.</p>'
                . $pt('Set your 20% savings transfer to fire automatically on payday, before you can spend it on wants. Most people who "can\'t save" are actually saving last instead of first.')
                . $fi($img['expense_sheet'],'Detailed expense tracking sheet showing categorized spending with needs wants and savings columns','Categorizing your real spending almost always reveals that wants are much higher than expected.')
                . '<h2>What If Your Needs Are Over 50%?</h2><p>In expensive cities, rent alone can eat 40–50% of your take-home pay. If your needs exceed 50%, you have two options:</p><ol><li><strong>Reduce fixed costs:</strong> Get a roommate, refinance your car, shop for cheaper insurance, move to a less expensive area</li><li><strong>Increase your income:</strong> Part-time work, freelancing, asking for a raise</li></ol><p>What you should not do: cut the 20% savings to balance the budget. Savings is non-negotiable. It is your future self paying for today.</p>'
                . $cta(home_url('/tools/budget-analyzer/'),'Try the Free 50/30/20 Budget Analyzer')
                . $faq(['Does the 50/30/20 rule work on a low income?' => 'Yes — the percentages scale with your income. The key adjustment on a lower income is that your needs may genuinely take 60–65% of your income. In that case, focus on reducing fixed costs over time and building the savings habit at even 10–15%, then increase it as your income grows.','Should I include pre-tax or after-tax income in the calculation?' => 'Always use your after-tax take-home income — the actual amount deposited to your bank account. Your gross salary includes taxes, retirement contributions, and benefits deductions that you never actually see.','What counts as savings in the 20% category?' => 'Everything that builds your financial future: emergency fund contributions, retirement account contributions (401k, IRA, Roth IRA), investing, and extra payments above the minimum on your debts.']),
        ],
        [
            'title'   => 'How to Pay Off Student Loans Faster in 2026: 7 Strategies That Work',
            'slug'    => 'how-to-pay-off-student-loans-faster-2026',
            'excerpt' => 'Student loan debt holds millions of Americans back from building wealth. These 7 proven strategies will help you pay off your loans faster and save thousands in interest.',
            'keyword' => 'how to pay off student loans faster 2026',
            'desc'    => '7 proven strategies to pay off student loans faster in 2026 and save thousands in interest. Start building real wealth sooner.',
            'content' =>
                $kt(['Extra $150/month on a $38,000 loan cuts payoff by 3 years and saves $4,500 in interest','Always specify extra payments go to "principal" — not your next due date','Bi-weekly payments = one extra monthly payment per year, saving 8–10 months of repayment','PSLF forgives your entire remaining federal loan balance after 10 years in government/nonprofit work','Refinancing can save $7,000+ but permanently removes federal loan protections — weigh carefully'])
                . '<p>The average student loan borrower owes about $38,000 and pays it off over 20 years. On a standard 10-year plan at 6.5% interest, that $38,000 ends up costing $52,000 — an extra $14,000 in pure interest. These 7 strategies cut years off your timeline and save you thousands.</p>'
                . $fi($img['graduate'],'College graduate in cap and gown holding diploma representing student loan debt and financial future','Your education was an investment — these strategies help you pay it off faster and start building wealth sooner.')
                . '<h2>Strategy 1: Pay More and Target the Principal</h2><p>Even an extra $100–$150/month has a big effect. On a $38,000 loan at 6.5%, adding $150/month cuts your payoff time by almost 3 years and saves $4,500 in interest.</p><p><strong>The critical step:</strong> When you make extra payments, tell your servicer to apply them to your <strong>principal balance</strong> — not to advance your due date. Many servicers default to the latter, which does nothing for your total interest.</p>'
                . $pt('Log in to your loan servicer\'s website and look for a "payment allocation" or "apply to principal" option. Set it as your default so every extra payment automatically reduces your balance.')
                . $fi($img['student_debt'],'Person at desk reviewing student loan statement with laptop calculator and financial documents','Check your loan servicer portal — most now allow you to set principal-first payment preferences online.')
                . '<h2>Strategy 2: Switch to Bi-Weekly Payments</h2><p>Instead of one monthly payment, make half-payments every two weeks. There are 52 weeks in a year, so this gives you 26 half-payments — equal to 13 full monthly payments instead of 12.</p><p>That one extra payment per year, applied to principal, shortens a 10-year loan by 8–10 months and saves $1,500+. No budget change needed.</p>'
                . '<h2>Strategy 3: Apply Every Windfall to Your Loans</h2><p>Tax refund? Work bonus? Side hustle income? Every windfall is an opportunity to make a lump sum payment. A $2,000 tax refund applied to principal eliminates 2–3 months of future payments and saves $300–$500 in interest immediately. Before spending unexpected income, ask: do I want this thing more than I want to be 2 months closer to debt freedom?</p>'
                . '<h2>Strategy 4: Refinance If You Have Good Credit</h2><p>If your credit score is 700+ and you have stable income, private refinancing can cut your rate significantly. In 2026, qualified borrowers can find rates of 4%–6%. On $40,000, dropping from 7% to 4.5% saves $7,000+ in total interest.</p><p><strong>Important warning:</strong> Refinancing federal loans to private eliminates federal protections permanently — income-driven repayment, PSLF eligibility, and hardship forbearance. Only refinance if you have job stability and are not pursuing forgiveness.</p>'
                . $fi($img['laptop_finance'],'Person on laptop comparing student loan refinancing rates from multiple lenders side by side','Always compare at least 3 lenders before refinancing — rates can vary by 1–2% for the same borrower profile.')
                . '<h2>Strategy 5: Explore Public Service Loan Forgiveness (PSLF)</h2><p>If you work for any government agency or qualifying 501(c)(3) nonprofit, PSLF can forgive your entire remaining federal loan balance after 120 qualifying payments (10 years) — completely tax-free. For someone who owes $80,000 and earns $50,000, this can mean $50,000–$60,000 in forgiveness. Submit your Employment Certification Form annually to track your progress.</p>'
                . $fi($img['loan_doc'],'Student loan payoff letter and celebration representing successful debt freedom','The day you make your final student loan payment is one of the most financially liberating moments of your life.')
                . $cta(home_url('/tools/loan-payoff-calculator/'),'Calculate How Fast You Can Pay Off Your Loans')
                . $faq(['Should I pay off student loans or invest first?' => 'If your loan interest rate is above 6–7%, prioritize paying it off aggressively — the guaranteed return of eliminating that interest beats most investments. If your rate is below 5%, invest while making minimum payments, since index funds historically return 7–10%/year.','Can I pay off student loans in 5 years instead of 10?' => 'Yes — on a $38,000 loan at 6.5%, a 5-year payoff requires about $740/month instead of $430/month. You save about $8,000 in interest and finish 5 years earlier. Use our Loan Payoff Calculator to find your exact numbers.','Does refinancing hurt my credit score?' => 'Applying for refinancing involves a hard inquiry, which temporarily lowers your score by 5–10 points. However, if you apply to multiple lenders within a 14–45 day window, credit bureaus count all of them as a single inquiry. Shop around without fear.']),
        ],
        [
            'title'   => 'Bitcoin vs Gold: Which Is the Better Investment in 2026?',
            'slug'    => 'bitcoin-vs-gold-better-investment-2026',
            'excerpt' => 'Bitcoin and gold are both called stores of value — but they behave very differently. This honest comparison breaks down which is better for your portfolio in 2026.',
            'keyword' => 'bitcoin vs gold investment 2026',
            'desc'    => 'Bitcoin vs Gold — an honest comparison of returns, risk, volatility, and inflation protection in 2026. Which belongs in your portfolio?',
            'content' =>
                $kt(['Gold has a 5,000-year track record; Bitcoin has 16 years — very different risk profiles','Gold returned ~580% from 2000–2025 (8.4%/year); Bitcoin returned 20,000%+ from 2015–2025','Bitcoin has experienced -83% drawdowns; gold\'s worst modern drawdown is about -20%','Most investors benefit from holding both — in proportions matching their risk tolerance','Neither gold nor Bitcoin should replace your core stock market portfolio'])
                . '<p>Gold has been a store of value for 5,000 years. Bitcoin has existed for 16. One is tangible and slow-moving. The other is digital and volatile. Both are called inflation hedges. This is an honest comparison — no hype, no bias, just real data to help you decide.</p>'
                . $fi($img['gold_bars'],'Stacked gold bars with official markings representing traditional store of value investment','Gold bars represent the oldest stored wealth known to humanity — recognized in every country on earth.')
                . '<h2>Gold: The 5,000-Year Proven Store of Value</h2><p>Gold cannot be printed by governments. It has real industrial demand in electronics, medicine, and jewelry. Every central bank in the world holds it as a reserve asset. From 2000 to 2025, gold returned approximately 580% — from $280/oz to over $2,900/oz. During the 2008 crash, gold rose 25% while stocks fell 50%. That is what a real safe haven does.</p><p><strong>Strengths:</strong> Multi-millennium track record, low volatility, no counterparty risk, central bank demand, true safe haven in crises.<br><strong>Weaknesses:</strong> No dividends or yield, storage costs for physical gold, slower appreciation than stocks or Bitcoin.</p>'
                . $fi($img['gold_coins'],'Gold coins and small gold bars spread on dark surface showing investment-grade precious metals','Physical gold coins can be stored at home or in a vault — no password to forget, no counterparty to fail.')
                . '<h2>Bitcoin: The Digital Store of Value</h2><p>Bitcoin launched in 2009 with one revolutionary feature: a hard cap of 21 million coins, enforced by mathematics — impossible to change. No government or central bank can create more. This fixed scarcity, combined with growing institutional adoption, is why it is called "digital gold."</p><p>From 2015 to 2025, Bitcoin returned over 20,000% — compared to gold\'s ~85%. But Bitcoin has also crashed 83% from peak. That is the trade-off: extraordinary potential return, extraordinary volatility.</p><p><strong>Strengths:</strong> Fixed supply, truly portable, censorship-resistant, growing adoption.<br><strong>Weaknesses:</strong> Extreme 50–83% drawdowns, 16-year history, complex custody, regulatory uncertainty.</p>'
                . $fi($img['bitcoin_coin'],'Physical gold-colored Bitcoin coin on reflective surface representing cryptocurrency investment','Bitcoin\'s supply is capped at 21 million coins — enforced by math, not by any government or institution.')
                . $pt('Never invest more in Bitcoin than you could watch drop 80% without changing your behavior. Bitcoin has recovered from -83% drops, but it took years. Only invest what you can leave untouched.')
                . $fi($img['crypto_chart'],'Bitcoin and cryptocurrency price chart showing dramatic bull and bear market cycles 2020-2025','Bitcoin goes through major cycles — massive bull runs followed by brutal drawdowns. Patience is the required skill.')
                . '<h2>10-Year Performance Comparison</h2><table style="width:100%;border-collapse:collapse;margin:24px 0"><tr style="background:#0f172a;color:#10B981"><th style="padding:12px">Asset</th><th style="padding:12px">2015–2025 Return</th><th style="padding:12px">Worst Drawdown</th><th style="padding:12px">Volatility</th></tr><tr style="background:#1e293b;color:#cbd5e1"><td style="padding:10px">Bitcoin</td><td style="padding:10px;text-align:center;color:#10B981;font-weight:700">20,000%+</td><td style="padding:10px;text-align:center">-83%</td><td style="padding:10px;text-align:center">Very High</td></tr><tr style="background:#0f172a;color:#cbd5e1"><td style="padding:10px">Gold</td><td style="padding:10px;text-align:center">~85%</td><td style="padding:10px;text-align:center">-20%</td><td style="padding:10px;text-align:center">Low–Medium</td></tr><tr style="background:#1e293b;color:#cbd5e1"><td style="padding:10px">S&amp;P 500</td><td style="padding:10px;text-align:center">~240%</td><td style="padding:10px;text-align:center">-34%</td><td style="padding:10px;text-align:center">Medium</td></tr></table>'
                . '<h2>The Right Answer: Use Both</h2><p>For most investors, it is not gold OR Bitcoin — it is both, at proportions that match your risk tolerance:</p><ul><li><strong>Conservative (near retirement):</strong> 5–8% gold, 1–2% Bitcoin</li><li><strong>Moderate:</strong> 3–5% gold, 3–5% Bitcoin</li><li><strong>Aggressive:</strong> 1–3% gold, 5–15% Bitcoin</li></ul><p>Neither replaces your core stock portfolio. They are diversifiers — not the whole portfolio.</p>'
                . $cta(home_url('/tools/bitcoin-profit-calculator/'),'Calculate Bitcoin Returns at Any Entry Price')
                . $faq(['Which is safer — gold or Bitcoin?' => 'Gold is significantly safer in the traditional sense. Its maximum modern drawdown is around -20%; Bitcoin has crashed -83% multiple times. Gold is appropriate for capital preservation. Bitcoin is an asymmetric risk/return bet — appropriate for money you can afford to lose entirely.','Can I buy gold and Bitcoin in my IRA?' => 'Yes to both. A Gold IRA (through specialized custodians) holds physical gold. A self-directed IRA or Roth IRA at Fidelity, Schwab, or Coinbase now allows Bitcoin ETF exposure. Neither is available in standard brokerage IRAs without a self-directed structure.','Is now a good time to buy gold or Bitcoin in 2026?' => 'Trying to time the market rarely works. A better approach: decide what percentage of your portfolio belongs in each, then buy in gradually over 3–6 months via dollar-cost averaging. This removes the anxiety of picking the "right" price.']),
        ],
        [
            'title'   => 'Emergency Fund: How Much You Need and Where to Keep It in 2026',
            'slug'    => 'emergency-fund-how-much-where-to-keep-2026',
            'excerpt' => 'An emergency fund is the foundation of every strong financial plan. Learn how much to save, where to keep it, and how to build it fast in 2026.',
            'keyword' => 'emergency fund how much 2026',
            'desc'    => 'How much emergency fund do you need in 2026? Where to keep it? The fastest way to build it on any budget. Complete guide.',
            'content' =>
                $kt(['An emergency fund turns financial crises into minor inconveniences','Most people need 3–6 months of expenses saved — based on expenses, not income','High-yield savings accounts pay 4–5% APY in 2026 — your emergency fund should earn real interest','Build in 3 stages: $1,000 → 1 month → 3–6 months','Keep your emergency fund at a separate bank from your checking account — out of sight, out of mind'])
                . '<p>Here is what happens without an emergency fund: your car needs a $1,200 repair. You put it on a credit card at 22% APR. You pay the minimum for months. A single bad day becomes a months-long debt burden. An emergency fund stops that cycle before it starts. It is the foundation your entire financial life is built on.</p>'
                . $fi($img['emergency_cash'],'Cash money set aside in envelope labeled emergency fund representing financial preparedness','An emergency fund transforms unexpected expenses from crises into minor inconveniences.')
                . '<h2>How Much Do You Actually Need?</h2><p>The right amount depends on your specific situation:</p><ul><li><strong>Stable salaried job, single income:</strong> 3 months of essential expenses</li><li><strong>Variable or commission-based income:</strong> 6 months minimum — your income fluctuates, so your cushion must be larger</li><li><strong>Dual income, both jobs stable:</strong> 3 months — you have a backup if one income stops</li><li><strong>Single parent or sole provider:</strong> 6 months — no backup if you lose your job</li><li><strong>Freelancer or business owner:</strong> 6–12 months — income can disappear for extended periods</li></ul><p><strong>Calculate based on expenses, not income.</strong> What does it actually cost you to live for one month? That number times 3 (or 6) is your target.</p>'
                . $pt('Do not count non-essentials in your emergency fund calculation. Calculate your true minimum monthly survival cost: rent, utilities, groceries, insurance, minimum debt payments. That is your multiplier number.')
                . $fi($img['hysa_phone'],'Person checking high yield savings account on smartphone showing 4.8% APY interest rate earned','A high-yield savings account pays 4–5% APY in 2026 — your emergency fund should work while it waits.')
                . '<h2>Where to Keep It in 2026</h2><p>Your emergency fund has one job: be available immediately when you need it. That means:</p><ul><li><strong>Not in the stock market</strong> — could be down 30% right when you need it most</li><li><strong>Not in a CD</strong> — withdrawal penalties and delays make it unreliable</li><li><strong>Not in your checking account</strong> — too easy to spend gradually</li></ul><p>The right answer is a <strong>High-Yield Savings Account (HYSA)</strong>. Top HYSAs in 2026 pay 4.5%–5.0% APY. On a $15,000 emergency fund, that is $675–$750/year in interest — free money while the fund sits ready.</p><p>Best options: Marcus by Goldman Sachs, Ally Bank, SoFi, American Express High Yield Savings, Discover Online Savings. All are FDIC insured, fee-free, and no minimums.</p>'
                . '<h2>Build It in 3 Stages</h2><p>Starting from zero, the full target feels overwhelming. Do not think about the whole mountain — just the next step:</p><ol><li><strong>Stage 1 — $1,000:</strong> Your first goal. Get here fast — temporarily pause extra debt payments if needed. $1,000 covers most single emergencies: car repair, medical copay, broken appliance.</li><li><strong>Stage 2 — 1 Full Month of Expenses:</strong> Now you are protected against a short job gap or major expense. Resume normal debt payments.</li><li><strong>Stage 3 — Full 3–6 Month Fund:</strong> Build steadily. $200/month gets most people to a 3-month fund in 12–18 months.</li></ol>'
                . $fi($img['piggy_bank'],'Piggy bank with coins falling in representing consistent monthly contributions to emergency savings fund','$200/month builds a 3-month emergency fund in about 15 months — faster than most people expect.')
                . $cta(home_url('/tools/emergency-fund-calculator/'),'Calculate Your Emergency Fund Target Free')
                . $faq(['Should I build an emergency fund before paying off debt?' => 'Get to $1,000 first — no matter what. That starter fund prevents most debt spirals. Once you have $1,000, balance extra debt payments with growing your fund. When high-interest debt is paid off, accelerate your emergency fund to 3–6 months.','What counts as an emergency fund expense?' => 'True emergencies: job loss, major medical expense, emergency car repair (needed for work), urgent home repair (plumbing leak, heating failure), or family crisis. Vacations, sales, and predictable expenses are not emergencies — budget for those separately.','Can I invest my emergency fund for better returns?' => 'No. Your emergency fund should not be in stocks or bonds. The S&P 500 was down 34% in March 2020 — exactly when many people lost their jobs. A HYSA at 4–5% APY provides real return with zero risk of being down when you need the money most.']),
        ],
        [
            'title'   => 'First-Time Home Buyer Guide 2026: Everything You Need to Know',
            'slug'    => 'first-time-home-buyer-guide-2026',
            'excerpt' => 'Buying your first home is the biggest financial decision most people ever make. This guide covers saving for a down payment, getting pre-approved, and closing day.',
            'keyword' => 'first time home buyer guide 2026',
            'desc'    => 'Complete first-time home buyer guide for 2026. How to save for a down payment, get pre-approved, choose a mortgage, and avoid costly mistakes.',
            'content' =>
                $kt(['Check 6 readiness questions before buying — if you fail 2+, wait a year and prepare','Lenders approve you for more than you should spend — use the 28/36 rule to find your real limit','Closing costs (2–5% of loan) are separate from your down payment — budget for both','Get pre-approved from 2–3 lenders before viewing homes — a 0.25% rate difference saves $16,000+','Never skip the home inspection — $400 can reveal $40,000 in problems'])
                . '<p>Buying your first home is the biggest financial decision most people ever make. Most buyers go in relying on an agent who earns a commission when the deal closes. This guide has no such interest — just the information you actually need to protect yourself and make a smart decision.</p>'
                . $fi($img['house_sign'],'For Sale sign in front of beautiful suburban house representing home buying process','Spend 3–6 months preparing financially before you start touring homes — it is worth every single day.')
                . '<h2>Are You Actually Ready? 6 Questions</h2><p>Answer these honestly before you look at a single home:</p><ol><li>Do you have stable income and strong job security?</li><li>Is your credit score 620 or higher? (700+ gets you the best rates)</li><li>Do you have 3–20% of your target home price saved for a down payment?</li><li>Do you have an additional 2–5% for closing costs — completely separate from the down payment?</li><li>Do you have a 3–6 month emergency fund that will survive the purchase intact?</li><li>Do you plan to stay in this area for at least 3–5 years?</li></ol><p>If you answered "no" to more than two, another year of preparation will save you more money and stress than any deal you find today.</p>'
                . $fi($img['house_exterior'],'Beautiful well-maintained home exterior with green lawn and clear skies — ideal first home','The right first home is one you can comfortably afford — not the maximum the lender will approve.')
                . '<h2>How Much House Can You Afford?</h2><p>Lenders will approve you for more than you should spend. That is their business. Your job is to protect your financial life. Use the <strong>28/36 rule</strong>:</p><ul><li>Monthly housing payment (mortgage + taxes + insurance) should not exceed <strong>28%</strong> of your gross monthly income</li><li>All monthly debt combined (housing + car + student loans + credit cards) should not exceed <strong>36%</strong></li></ul><p>On $6,000/month gross income: maximum comfortable housing payment is $1,680/month.</p>'
                . $pt('Calculate your monthly payment at current interest rates BEFORE you fall in love with a specific home price. Many buyers find the homes they love in the wrong price range — then spend months rationalizing the stretch.')
                . '<h2>Down Payment Options in 2026</h2><ul><li><strong>Conventional Loan:</strong> 3%–20% down. Under 20% adds PMI at $100–$200/month until you reach 20% equity.</li><li><strong>FHA Loan:</strong> 3.5% down with 580+ credit score. Has mortgage insurance fees. Good for buyers with moderate credit.</li><li><strong>VA Loan:</strong> 0% down for eligible veterans. No PMI ever. Best home loan available for those who qualify.</li><li><strong>USDA Loan:</strong> 0% down in eligible rural/suburban areas. Income limits apply.</li></ul><p><strong>Budget for closing costs too</strong> — 2–5% of the loan amount, due at the table. On a $300,000 home: $6,000–$15,000 in cash, on top of your down payment.</p>'
                . $fi($img['mortgage_doc'],'Person signing mortgage documents at closing table with pen and real estate paperwork','Closing day means signing 40–50 pages — review your Closing Disclosure carefully 3 business days before.')
                . '<h2>Get Pre-Approved Before You Tour Homes</h2><p>Pre-approval means a lender has verified your income, credit, and debts and committed to a specific loan amount. Get pre-approval from at least 2–3 different lenders before making any offers. A 0.25% rate difference on a $300,000 mortgage saves $16,000+ over 30 years. Comparing offers takes 2 hours and is one of the highest-value hours you can spend.</p>'
                . $fi($img['laptop_finance'],'Person comparing mortgage pre-approval offers from multiple lenders on laptop computer','Comparing 3 mortgage offers takes 2 hours and typically saves $10,000–$20,000 over the loan term.')
                . '<h2>The 5 Biggest First-Time Buyer Mistakes</h2><ol><li><strong>Draining your emergency fund for the down payment</strong> — the house will immediately need something; have cash for it</li><li><strong>Buying the maximum you are approved for</strong> — makes every financial decision tight for years</li><li><strong>Skipping the home inspection</strong> — $400 inspection can reveal $40,000 in hidden problems</li><li><strong>Not locking your interest rate</strong> — rates can rise between offer acceptance and closing day</li><li><strong>Making large purchases between offer and closing</strong> — a new car or credit card changes your debt-to-income ratio and can kill the loan</li></ol>'
                . $cta(home_url('/tools/mortgage-calculator/'),'Calculate Your Monthly Mortgage Payment Free')
                . $faq(['How much should I save for a house before buying?' => 'You need: (1) down payment — minimum 3.5% for FHA, 3% for conventional, 0% for VA/USDA; (2) closing costs — 2–5% of the loan amount; (3) emergency fund — 3+ months expenses that survives the purchase intact; (4) cash reserve for immediate repairs — 1–2% of home value. Total: budget roughly 8–10% of the purchase price before you start seriously shopping.','Is it better to put 20% down or buy sooner with less?' => 'It depends on your market and rent costs. A 20% down payment eliminates PMI ($100–$200/month) and reduces your loan amount. But if home prices are rising 5%/year and you are paying expensive rent, waiting 2 years to save 20% can cost more than the PMI would have.','What credit score do I need to get a good mortgage rate?' => 'A 620 score gets you approved for most programs. A 680 score gets you solid rates. A 740+ score gets you the best available rates. The difference between a 650 and 750 score on a $300,000 30-year mortgage can be $50,000+ in total interest paid.']),
        ],
        [
            'title'   => 'Retirement Planning 2026: How Much Do You Need and How to Get There',
            'slug'    => 'retirement-planning-how-much-need-2026',
            'excerpt' => 'How much money do you actually need to retire? The answer depends on your lifestyle and timeline. This guide breaks it down clearly for 2026.',
            'keyword' => 'retirement planning 2026 how much do I need',
            'desc'    => 'How much do you need to retire in 2026? The 4% rule, retirement number calculator, and the best accounts to use. Complete retirement planning guide.',
            'content' =>
                $kt(['Your retirement number = Annual expenses × 25 (the 4% rule)','Spending $60,000/year in retirement requires $1.5 million saved — but Social Security reduces this significantly','The average Social Security benefit in 2026 is $1,907/month — factor this into your actual savings target','Always get your full 401(k) employer match first — it is a 50–100% guaranteed instant return','By age 35, aim to have 2× your annual salary saved for retirement'])
                . '<p>Retirement planning is the goal most people feel most behind on. The numbers sound impossibly large. The timeline feels abstract. But you do not need to have it all figured out. You just need to know your number, understand the tools, and start moving. This guide covers all three.</p>'
                . $fi($img['retire_couple'],'Happy retired couple sitting on beach enjoying sunset representing successful retirement planning','Retirement planning is not about stopping work — it is about having the option to. That is financial freedom.')
                . '<h2>The 4% Rule: How to Find Your Number</h2><p>The 4% rule is the most widely used retirement framework. If you withdraw 4% of your portfolio in year one and adjust for inflation each year after, your money historically lasts 30+ years in most market conditions.</p><p><strong>Your retirement number = Annual expenses × 25</strong></p><ul><li>$40,000/year → need <strong>$1,000,000</strong></li><li>$60,000/year → need <strong>$1,500,000</strong></li><li>$80,000/year → need <strong>$2,000,000</strong></li></ul><p>This assumes your portfolio averages 7%/year after inflation — below the S&amp;P 500\'s historical average.</p>'
                . $pt('Create a free account at ssa.gov/myaccount to see your personalized Social Security estimate based on your actual earnings history. This one number can change your retirement target by hundreds of thousands of dollars.')
                . $fi($img['retirement_plan'],'Retirement planning document with charts showing savings milestones and compound growth projections','Your retirement plan should be a living document — review and update it every year as your life changes.')
                . '<h2>Social Security Reduces How Much You Need</h2><p>Do not ignore Social Security. The average 2026 benefit is about $1,907/month ($22,884/year). If you need $60,000/year in retirement and Social Security provides $22,884, your portfolio only needs to cover $37,116/year — meaning you need $928,000, not $1,500,000. That is a massive difference.</p>'
                . '<h2>Best Retirement Accounts in 2026</h2><ul><li><strong>401(k) or 403(b):</strong> Pre-tax contributions reduce your taxable income today. Max contribution: $23,500 ($31,000 if 50+). Always contribute enough to get the full employer match first — that match is a 50–100% guaranteed instant return.</li><li><strong>Roth IRA:</strong> Contribute after-tax, but all growth and withdrawals are completely tax-free. Max: $7,000/year ($8,000 if 50+). Best if you expect higher taxes in retirement.</li><li><strong>HSA (Health Savings Account):</strong> Triple tax benefit — pre-tax contributions, tax-free growth, tax-free withdrawals for medical expenses. After 65, withdraw for anything (taxed like traditional IRA). The most tax-efficient account available to most people.</li></ul>'
                . $fi($img['401k_screen'],'401k retirement account statement showing contributions employer match and total balance growth','Employer 401(k) match is one of the only guaranteed 50–100% returns in investing — never leave it on the table.')
                . '<h2>The Right Order to Contribute</h2><p>Follow this priority to maximize your tax advantage:</p><ol><li><strong>401(k) up to full employer match</strong> — free money first, always</li><li><strong>Max your Roth IRA</strong> — $7,000/year of tax-free growth</li><li><strong>Back to 401(k)</strong> up to the $23,500 annual limit</li><li><strong>Taxable brokerage account</strong> after all tax-advantaged accounts are maxed</li></ol>'
                . '<h2>Retirement Benchmarks by Age</h2><table style="width:100%;border-collapse:collapse;margin:24px 0"><tr style="background:#0f172a;color:#10B981"><th style="padding:12px">Age</th><th style="padding:12px">Savings Rate Target</th><th style="padding:12px">Balance Benchmark</th></tr><tr style="background:#1e293b;color:#cbd5e1"><td style="padding:10px;text-align:center">25</td><td style="padding:10px;text-align:center">10–15% of income</td><td style="padding:10px;text-align:center">0.5× annual salary</td></tr><tr style="background:#0f172a;color:#cbd5e1"><td style="padding:10px;text-align:center">35</td><td style="padding:10px;text-align:center">15–20% of income</td><td style="padding:10px;text-align:center">2× annual salary</td></tr><tr style="background:#1e293b;color:#cbd5e1"><td style="padding:10px;text-align:center">45</td><td style="padding:10px;text-align:center">20–25% of income</td><td style="padding:10px;text-align:center">4× annual salary</td></tr><tr style="background:#0f172a;color:#cbd5e1"><td style="padding:10px;text-align:center">55</td><td style="padding:10px;text-align:center">25–35% of income</td><td style="padding:10px;text-align:center">7× annual salary</td></tr></table>'
                . $cta(home_url('/tools/retirement-calculator/'),'Find Your Retirement Number and Monthly Savings Target')
                . $faq(['I am 40 and have very little saved for retirement. Is it too late?' => 'No — but urgency matters. At 40, you have 20–25 years of compounding ahead of you. Max your 401(k) and Roth IRA, increase your savings rate aggressively, and delay Social Security to age 70 for a 76% higher monthly benefit than taking it at 62. Many people build their majority of retirement savings in their 40s and 50s.','What is the difference between a Traditional IRA and Roth IRA?' => 'Traditional IRA: you deduct contributions now, pay taxes on withdrawals in retirement. Roth IRA: you pay taxes now, but all growth and withdrawals are completely tax-free. Roth is usually better if you are young or expect higher income later. Traditional is better if you are in a high tax bracket now and expect lower income in retirement.','Can I retire early if I follow the 4% rule?' => 'Yes — this is the basis of the FIRE (Financial Independence, Retire Early) movement. The math works the same regardless of your age. The main risk of early retirement is longevity — your portfolio may need to last 40–50 years instead of 30. Many early retirees use a more conservative 3–3.5% withdrawal rate for extra safety.']),
        ],
    ];

    foreach ( $posts as $p ) {
        $existing = get_page_by_path( $p['slug'], OBJECT, 'post' );

        if ( $existing ) {
            wp_update_post([
                'ID'           => $existing->ID,
                'post_title'   => $p['title'],
                'post_content' => $p['content'],
                'post_excerpt' => $p['excerpt'],
            ]);
            $post_id = $existing->ID;
        } else {
            $post_id = wp_insert_post([
                'post_title'    => $p['title'],
                'post_name'     => $p['slug'],
                'post_content'  => $p['content'],
                'post_excerpt'  => $p['excerpt'],
                'post_status'   => 'publish',
                'post_type'     => 'post',
                'post_author'   => 1,
                'post_category' => [ $cat_id ],
                'tags_input'    => ['personal finance','financial tips','2026','money management'],
            ]);
        }

        if ( $post_id && ! is_wp_error($post_id) ) {
            update_post_meta( $post_id, 'rank_math_title',         $p['title'] . ' | FinanceSpots' );
            update_post_meta( $post_id, 'rank_math_description',   $p['desc'] );
            update_post_meta( $post_id, 'rank_math_focus_keyword', $p['keyword'] );
            update_post_meta( $post_id, 'rank_math_robots',        [ 'index', 'follow' ] );
        }
    }

    update_option( 'fs_blogs_v3', true );
}
add_action( 'admin_init', 'fs_create_blog_posts', 25 );
add_action( 'init',       'fs_create_blog_posts', 45 );

/* ── RankMath: Set WebApplication as default schema for fs_tool CPT ── */
function fs_rankmath_tool_schema_defaults( $schemas, $post ) {
    if ( ! isset( $post->post_type ) || $post->post_type !== 'fs_tool' ) return $schemas;
    if ( ! empty( $schemas ) ) return $schemas;
    return [
        'schema-1' => [
            '@type'               => 'WebApplication',
            'name'                => get_the_title( $post->ID ),
            'description'         => get_post_field( 'post_excerpt', $post->ID ),
            'url'                 => get_permalink( $post->ID ),
            'applicationCategory' => 'FinanceApplication',
            'operatingSystem'     => 'Web Browser',
            'offers'              => [ '@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD' ],
        ],
    ];
}
add_filter( 'rank_math/schema/post_schemas', 'fs_rankmath_tool_schema_defaults', 10, 2 );

/* =========================================================
   COMPLETE SEO -- All 110 Tools + 8 Categories + Homepage
   Runs once on admin_init. Version-gated with fs_full_seo_v2.
   ========================================================= */
function fs_complete_seo_all_pages() {
    if ( get_option( 'fs_full_seo_v4' ) ) return;
    delete_option( 'fs_full_seo_v3' );
    delete_option( 'fs_full_seo_v2' );
    $post_count = wp_count_posts( 'fs_tool' );
    if ( ! isset( $post_count->publish ) || (int)$post_count->publish < 5 ) return;

    /* ── Helper: find post ID by exact title ── */
    $get_id = function( $title ) {
        static $map = [];
        if ( isset( $map[$title] ) ) return $map[$title];
        global $wpdb;
        $id = $wpdb->get_var( $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_title = %s AND post_type = 'fs_tool' AND post_status = 'publish' LIMIT 1",
            $title
        ) );
        $map[$title] = $id ? (int)$id : 0;
        return $map[$title];
    };

    /* ── Helper: set all SEO meta on a post ── */
    $set_post_seo = function( $id, $kw, $title, $desc ) {
        if ( ! $id ) return;
        update_post_meta( $id, 'rank_math_focus_keyword', $kw );
        update_post_meta( $id, 'rank_math_title',         $title );
        update_post_meta( $id, 'rank_math_description',   $desc );
        update_post_meta( $id, 'rank_math_robots', [ 'index', 'follow' ] );
    };

    /* ═══════════════════════════════════════════════════════
       1. LOAN CALCULATORS (18 tools)
       ═══════════════════════════════════════════════════════ */
    // Format: [ title(exact DB), focus_keyword, seo_title(55-60 chars), meta_desc(155-160 chars) ]
    $loans = [
        ['Mortgage Calculator',
         'mortgage calculator 2026',
         'Free Mortgage Calculator 2026 | FinanceSpots',
         'Calculate monthly mortgage payment with our free tool. Enter home price, down payment, rate & term to see monthly PITI, total interest, and full amortization schedule instantly.'],
        ['Auto Loan Calculator',
         'auto loan calculator 2026',
         'Free Auto Loan Calculator 2026 | FinanceSpots',
         'Find your exact monthly car payment with our free auto loan calculator. Enter price, down payment, trade-in, sales tax & APR to see total cost and payoff date instantly.'],
        ['Personal Loan Calculator',
         'personal loan calculator',
         'Personal Loan Calculator -- Monthly Payment & APR',
         'Estimate personal loan monthly payments for any amount and rate. Compare 1-7 year terms, see total interest cost, and calculate effective APR including origination fees. Free.'],
        ['Student Loan Calculator',
         'student loan repayment calculator',
         'Student Loan Calculator 2026 | FinanceSpots',
         'Calculate student loan payments, total interest, and payoff date. Supports federal & private loans with grace period, Standard, Extended, and Graduated repayment plans.'],
        ['Home Equity Loan Calculator',
         'home equity loan calculator',
         'Home Equity Loan Calculator 2026 | FinanceSpots',
         'Find out how much home equity you can borrow. Calculate HELOC and HELoan payments based on home value, mortgage balance, and lender LTV limit. Free and instant results.'],
        ['Debt Consolidation Calculator',
         'debt consolidation calculator',
         'Debt Consolidation Calculator 2026 | FinanceSpots',
         'See if consolidating debt saves money. Enter up to 5 debts and compare monthly minimums vs. a single consolidation loan. Calculate monthly savings and total interest reduction.'],
        ['Loan Comparison Calculator',
         'loan comparison calculator',
         'Loan Comparison Calculator -- Compare 2 Loans Free',
         'Compare two loan offers side by side and see which costs less. Enter amount, rate, and term for each loan to find the lower monthly payment and total interest. Free tool.'],
        ['Amortization Calculator',
         'loan amortization calculator',
         'Amortization Calculator 2026 | FinanceSpots',
         'Generate a full loan amortization schedule with one click. See principal vs. interest each month, how extra payments save years of payments, and your exact payoff date.'],
        ['Refinance Calculator',
         'mortgage refinance calculator',
         'Refinance Calculator -- Break-Even & Savings 2026',
         'Should you refinance? Calculate monthly savings, break-even point in months, and lifetime interest reduction. Includes closing cost analysis. Free mortgage refinance tool.'],
        ['FHA Loan Calculator',
         'FHA loan calculator 2026',
         'FHA Loan Calculator 2026 -- Payment with MIP',
         'Calculate FHA loan monthly payment with 1.75% upfront MIP and annual MIP included. Compare 3.5% down FHA vs. conventional. Updated for 2026 FHA loan limits. Free.'],
        ['VA Loan Calculator',
         'VA loan calculator 2026',
         'VA Loan Calculator 2026 -- No PMI Payment Tool',
         'Calculate VA loan monthly payment with zero PMI. VA funding fee auto-calculated by service type and down payment. Free tool for veterans, active duty, and surviving spouses.'],
        ['Balloon Loan Calculator',
         'balloon mortgage calculator',
         'Balloon Loan Calculator 2026 | FinanceSpots',
         'Calculate balloon loan monthly payments and final lump-sum balloon amount. See principal paid, interest cost, and remaining balance at 3, 5, 7, and 10-year balloon terms.'],
        ['Interest Only Calculator',
         'interest only mortgage calculator',
         'Interest Only Loan Calculator 2026 | FinanceSpots',
         'Compare interest-only vs. fully amortizing loan payments. See IO savings now, the payment jump when IO ends, and total extra interest cost over the full loan term. Free.'],
        ['Loan Payoff Calculator',
         'loan payoff calculator extra payments',
         'Loan Payoff Calculator -- Pay Off Faster & Save',
         'See how extra monthly or lump-sum payments cut your loan payoff time. Calculate exact months saved, interest avoided, and new payoff date for any loan type. Free tool.'],
        ['Monthly Payment Calculator',
         'monthly loan payment calculator',
         'Monthly Payment Calculator 2026 | FinanceSpots',
         'Instantly calculate the monthly payment for any loan. Enter loan amount, annual interest rate, and term in years. Works for mortgages, auto, personal, and student loans.'],
        ['Loan Affordability Calculator',
         'how much loan can I afford',
         'Loan Affordability Calculator 2026 | FinanceSpots',
         'Find how much loan you can afford based on income and debts. Uses the 28/36 rule and 43% DTI limit used by most lenders. Get your max borrowing power in seconds. Free.'],
        ['Commercial Loan Calculator',
         'commercial real estate loan calculator',
         'Commercial Loan Calculator -- DSCR & Payments',
         'Calculate commercial loan monthly payments, balloon balance, DSCR ratio, and origination fees. Essential tool for real estate investors evaluating CRE financing options.'],
        ['Bridge Loan Calculator',
         'bridge loan cost calculator',
         'Bridge Loan Calculator 2026 | FinanceSpots',
         'Estimate total bridge loan cost including monthly interest, origination fee, exit fee, and effective APR. Compare 3 to 24-month bridge financing scenarios. Free tool.'],
    ];
    foreach ( $loans as $d ) {
        $id = $get_id( $d[0] );
        $set_post_seo( $id, $d[1], $d[2], $d[3] );
    }

    /* ═══════════════════════════════════════════════════════
       2. INVESTMENT TOOLS (22 tools)
       ═══════════════════════════════════════════════════════ */
    $investments = [
        ['ROI Calculator',               'ROI calculator',                      'Free ROI Calculator 2026 | FinanceSpots',
         'Calculate return on investment for any asset or project. Enter total cost and total gain to instantly see ROI percentage, net profit, and annualized return. Free and instant.'],
        ['Compound Interest Calculator', 'compound interest calculator 2026',   'Compound Interest Calculator 2026 | FinanceSpots',
         'See how compound interest grows your money over time. Choose daily, monthly, or annual compounding, add regular contributions, and visualize long-term wealth growth. Free.'],
        ['Portfolio Analyzer',           'investment portfolio analyzer',        'Portfolio Analyzer -- Allocation & Returns | FinanceSpots',
         'Analyze your investment portfolio allocation, weighted returns, and risk exposure. Enter multiple holdings to see diversification score and performance breakdown. Free tool.'],
        ['Stock Return Calculator',      'stock total return calculator',        'Stock Return Calculator 2026 | FinanceSpots',
         'Calculate total stock return including price appreciation and dividends reinvested. See annualized CAGR and compare your actual return vs. the S&P 500 benchmark. Free.'],
        ['Dividend Calculator',          'dividend yield calculator',            'Dividend Calculator -- Yield, Income & DRIP Growth',
         'Calculate dividend income, yield, and projected growth with dividend reinvestment (DRIP). Plan your annual dividend income from any stock or ETF portfolio. Free tool.'],
        ['Investment Growth Calculator', 'investment growth calculator',         'Investment Growth Calculator 2026 | FinanceSpots',
         'Project investment portfolio growth with regular monthly contributions. See account balance over time, total contributions vs. gains, and future value. Free calculator.'],
        ['Dollar Cost Averaging',        'dollar cost averaging calculator',     'Dollar Cost Averaging Calculator 2026 | FinanceSpots',
         'Simulate DCA strategy returns versus lump-sum investing. Calculate average cost per share, total amount invested, and portfolio value over time. Works for stocks and crypto.'],
        ['Risk Assessment Tool',         'investment risk tolerance quiz',       'Investment Risk Assessment Tool 2026 | FinanceSpots',
         'Evaluate your investment risk tolerance and recommended asset allocation. Answer questions about your goals and timeline to get your optimal stock, bond, and cash mix.'],
        ['Asset Allocation Calculator',  'asset allocation calculator by age',   'Asset Allocation Calculator 2026 | FinanceSpots',
         'Build the optimal portfolio mix based on age, risk tolerance, and investment goals. See recommended split between stocks, bonds, real estate, and cash. Free tool.'],
        ['Bond Yield Calculator',        'bond yield to maturity calculator',    'Bond Yield Calculator -- YTM & Current Yield 2026',
         'Calculate bond current yield, yield to maturity (YTM), and fair price. Enter coupon rate, par value, market price, and years to maturity. Free bond yield calculator.'],
        ['Options Profit Calculator',    'options profit loss calculator',       'Options Profit Calculator -- Call & Put P&L',
         'Calculate options profit and loss for calls and puts. Enter strike price, premium paid, and current stock price to see max profit, max loss, and break-even. Free tool.'],
        ['Mutual Fund Calculator',       'mutual fund return calculator',        'Mutual Fund Calculator 2026 | FinanceSpots',
         'Project mutual fund growth with expense ratio impact included. See how fees cost you over 10-30 years and compare high vs. low-fee fund performance. Free calculator.'],
        ['ETF Calculator',               'ETF return calculator with dividends', 'ETF Return Calculator 2026 | FinanceSpots',
         'Calculate ETF total returns with dividends reinvested and expense ratio drag included. Compare ETF vs. mutual fund costs over 20 and 30 years. Free investment tool.'],
        ['Capital Gains Calculator',     'capital gains tax calculator 2026',    'Capital Gains Tax Calculator 2026 | FinanceSpots',
         'Calculate capital gains tax on investment profits. See short-term vs. long-term rates (0%, 15%, 20%), net proceeds after tax, and how to minimize your bill. 2026 rates.'],
        ['CAGR Calculator',              'CAGR calculator',                      'CAGR Calculator -- Compound Annual Growth Rate',
         'Calculate compound annual growth rate (CAGR) for any investment. Enter beginning value, ending value, and years to see annualized return. Free CAGR calculator 2026.'],
        ['Present Value Calculator',     'present value calculator',             'Present Value Calculator 2026 | FinanceSpots',
         'Calculate the present value of future cash flows or a lump sum payment. Useful for comparing investment options, valuing annuities, and bond pricing. Free NPV tool.'],
        ['Future Value Calculator',      'future value calculator',              'Future Value Calculator 2026 | FinanceSpots',
         'Calculate the future value of any investment given interest rate and time. Add regular monthly contributions to see projected account balance at any point. Free tool.'],
        ['Break Even Calculator',        'break even point calculator',          'Break Even Calculator 2026 | FinanceSpots',
         'Find the break-even point for investments and businesses. Calculate units sold and revenue needed to cover all fixed and variable costs. Free break-even analysis tool.'],
        ['Inflation Calculator',         'inflation calculator US',              'Inflation Calculator 2026 -- Real Purchasing Power',
         'See how inflation erodes purchasing power over time. Calculate what past dollars are worth today, or project future value adjusted for expected inflation rate. Free.'],
        ['Investment Fee Calculator',    'investment fee impact calculator',     'Investment Fee Calculator -- Fee Drag on Wealth',
         'Calculate how investment fees erode your wealth over 30 years. Compare 0.5% vs. 1.5% expense ratios and see how much more you keep with a low-cost index fund. Free.'],
        ['Sharpe Ratio Calculator',      'Sharpe ratio calculator',              'Sharpe Ratio Calculator 2026 | FinanceSpots',
         'Calculate the Sharpe ratio to measure risk-adjusted investment return. Enter portfolio return, risk-free rate, and standard deviation to compare investments fairly.'],
        ['Portfolio Rebalancing Tool',   'portfolio rebalancing calculator',     'Portfolio Rebalancing Calculator 2026 | FinanceSpots',
         'Calculate exact buy and sell trades needed to rebalance your portfolio. Enter current holdings and target allocation percentages to see amounts to trade. Free tool.'],
    ];
    foreach ( $investments as $d ) {
        $id = $get_id( $d[0] );
        $set_post_seo( $id, $d[1], $d[2], $d[3] );
    }

    /* ═══════════════════════════════════════════════════════
       3. TAX CALCULATORS (15 tools)
       ═══════════════════════════════════════════════════════ */
    $taxes = [
        ['Income Tax Calculator',         'income tax calculator 2026',          'Free Income Tax Calculator 2026 | FinanceSpots',
         'Estimate your 2026 federal income tax. Enter income, filing status, and deductions to instantly see tax owed, effective tax rate, and marginal tax bracket. Free tool.'],
        ['Capital Gains Tax Calculator',  'capital gains tax calculator',        'Capital Gains Tax Calculator 2026 | FinanceSpots',
         'Calculate capital gains tax on stocks, real estate, and crypto. See short-term vs. long-term rates (0%, 15%, 20%) and net proceeds after tax. Updated 2026 IRS rules.'],
        ['Tax Deduction Estimator',       'tax deduction estimator 2026',        'Tax Deduction Estimator 2026 | FinanceSpots',
         'Compare itemized vs. standard deduction for 2026 ($14,600 single / $29,200 married). Enter deductible expenses to see which filing method saves you the most money.'],
        ['Self Employment Tax Calculator','self employment tax calculator',       'Self Employment Tax Calculator 2026 | FinanceSpots',
         'Calculate self-employment tax (15.3% on net earnings) and quarterly estimated payments. Includes the SE deduction on half of SE tax. Built for freelancers and 1099 workers.'],
        ['Property Tax Calculator',       'property tax calculator',             'Property Tax Calculator 2026 | FinanceSpots',
         'Estimate annual property tax based on home assessed value and local mill rate. Compare property tax by state and county. Essential free tool for every homeowner.'],
        ['Sales Tax Calculator',          'sales tax calculator by state',       'Sales Tax Calculator 2026 -- All 50 US States',
         'Add or remove sales tax from any amount instantly. Supports all 50 US state rates. Calculate tax-inclusive and tax-exclusive prices for any purchase. Free tool.'],
        ['Tax Bracket Calculator',        'tax bracket calculator 2026',         'Tax Bracket Calculator 2026 | FinanceSpots',
         'See exactly which federal tax brackets apply to your income for 2026. View marginal rate, effective rate, and tax owed per bracket. Updated with 2026 IRS tax tables.'],
        ['Estate Tax Calculator',         'federal estate tax calculator',       'Estate Tax Calculator 2026 | FinanceSpots',
         'Estimate federal estate tax on inherited assets. The 2026 exemption is $13.61M per person. Calculate estate tax liability above the exemption threshold. Free tool.'],
        ['AMT Calculator',                'alternative minimum tax calculator',  'AMT Calculator 2026 -- Alternative Minimum Tax',
         'Check if you owe Alternative Minimum Tax in 2026. Enter income, deductions, and preferences to calculate tentative minimum tax and potential AMT liability. Free tool.'],
        ['Quarterly Tax Calculator',      'quarterly estimated tax calculator',  'Quarterly Tax Calculator 2026 | FinanceSpots',
         'Calculate quarterly estimated tax payments for freelancers, self-employed workers, and 1099 contractors. Avoid IRS underpayment penalties with accurate Q1-Q4 estimates.'],
        ['W-4 Calculator',                'W-4 withholding calculator 2026',     'W-4 Withholding Calculator 2026 | FinanceSpots',
         'Calculate the right W-4 withholding for 2026 to avoid a large tax bill or over-withholding. Based on the latest IRS W-4 form instructions. Free paycheck tax tool.'],
        ['IRS Penalty Calculator',        'IRS underpayment penalty calculator', 'IRS Penalty Calculator 2026 | FinanceSpots',
         'Estimate IRS underpayment penalty and interest for missed estimated tax payments. Calculate penalty per quarter using the federal short-term rate plus 3%. Free tool.'],
        ['State Tax Calculator',          'state income tax calculator 2026',    'State Income Tax Calculator 2026 -- All 50 States',
         'Estimate state income tax for all 50 US states. See which states have zero income tax, flat rates, or progressive brackets. Compare state tax burden side by side.'],
        ['Tax Withholding Calculator',    'paycheck withholding calculator',     'Tax Withholding Calculator 2026 | FinanceSpots',
         'Check if your employer withholds the correct federal income tax from your paycheck. Avoid a surprise tax bill or over-withholding at the end of the year. Free tool.'],
        ['Effective Tax Rate Calculator', 'effective tax rate calculator',       'Effective Tax Rate Calculator 2026 | FinanceSpots',
         'Calculate your effective (average) federal tax rate vs. marginal rate. See the true percentage of total income going to taxes -- not just your top bracket. Free.'],
    ];
    foreach ( $taxes as $d ) {
        $id = $get_id( $d[0] );
        $set_post_seo( $id, $d[1], $d[2], $d[3] );
    }

    /* ═══════════════════════════════════════════════════════
       4. SAVINGS PLANNERS (12 tools)
       ═══════════════════════════════════════════════════════ */
    $savings = [
        ['Emergency Fund Calculator',      'emergency fund calculator',             'Emergency Fund Calculator 2026 | FinanceSpots',
         'Calculate exactly how much emergency fund you need based on monthly expenses and months of coverage. Build your emergency fund step by step with a free savings plan.'],
        ['Savings Goal Calculator',        'savings goal calculator',               'Savings Goal Calculator 2026 | FinanceSpots',
         'Plan how much to save each month to reach any financial goal on time. Enter goal amount, timeline, and interest rate to get your monthly savings target. Free tool.'],
        ['Retirement Savings Calculator',  'retirement savings calculator 2026',    'Retirement Savings Calculator 2026 | FinanceSpots',
         'Project retirement savings with regular contributions and employer match. See if you\'re on track to retire on time and how much you\'ll need at retirement. Free.'],
        ['CD Calculator',                  'CD interest calculator 2026',           'CD Calculator 2026 -- Certificate of Deposit Returns',
         'Calculate CD returns at maturity with daily compounding. Compare 3-month to 5-year CDs. See APY vs. APR difference and find the best CD strategy for your savings goals.'],
        ['Money Market Calculator',        'money market account calculator',       'Money Market Calculator 2026 | FinanceSpots',
         'Project money market account and high-yield savings growth with monthly compounding. Compare rates from top online banks to maximize your cash returns. Free tool.'],
        ['Savings Rate Calculator',        'personal savings rate calculator',      'Savings Rate Calculator 2026 | FinanceSpots',
         'Calculate your savings rate as a percentage of gross and net income. Track progress toward the 20% savings rate benchmark recommended by financial planners. Free.'],
        ['52 Week Savings Challenge',      '52 week savings challenge calculator',  '52 Week Savings Challenge 2026 -- Save $1,378',
         'Track the popular 52-week savings challenge. Start with $1 in week 1, increase by $1 each week, and save $1,378 by year-end. See your full weekly schedule. Free tool.'],
        ['Round Up Savings Calculator',    'round up savings calculator',           'Round Up Savings Calculator 2026 | FinanceSpots',
         'Estimate annual savings from rounding up everyday purchases to the nearest dollar. See how small round-ups compound into meaningful savings over time. Free tool.'],
        ['Vacation Savings Calculator',    'vacation savings calculator',           'Vacation Savings Calculator 2026 | FinanceSpots',
         'Calculate how much to save each month for your next vacation. Enter total trip cost and departure date to get a personalized monthly savings plan. Free travel tool.'],
        ['Down Payment Savings Calculator','down payment savings calculator',       'Down Payment Savings Calculator 2026 | FinanceSpots',
         'Calculate how long it takes to save a 3%, 10%, or 20% down payment on a home. Set a target home price and monthly savings amount to see your timeline. Free tool.'],
        ['High Yield Savings Calculator',  'high yield savings account calculator', 'High Yield Savings Calculator 2026 -- HYSA Tool',
         'Compare high-yield savings account returns vs. traditional savings. See how much more you earn at 4.5% APY vs. 0.5% on the same balance over 1, 5, and 10 years. Free.'],
        ['Savings Milestone Tracker',      'savings milestone tracker',             'Savings Milestone Tracker 2026 | FinanceSpots',
         'Track progress toward multiple savings milestones simultaneously. Enter your goals, deadlines, and current balances to see which goals are on track. Free savings tool.'],
    ];
    foreach ( $savings as $d ) {
        $id = $get_id( $d[0] );
        $set_post_seo( $id, $d[1], $d[2], $d[3] );
    }

    /* ═══════════════════════════════════════════════════════
       5. RETIREMENT PLANNING (10 tools)
       ═══════════════════════════════════════════════════════ */
    $retirement = [
        ['401k Calculator',                  '401k calculator 2026',                  'Free 401k Calculator 2026 | FinanceSpots',
         'Project your 401k balance at retirement. Enter contribution rate, employer match, current balance, salary, and growth rate. See if you\'re saving enough for retirement.'],
        ['IRA Calculator',                   'IRA calculator traditional vs Roth',    'IRA Calculator 2026 -- Traditional vs Roth | FinanceSpots',
         'Calculate Traditional or Roth IRA growth to retirement. Compare tax-deferred vs. tax-free growth. See which IRA type saves more based on your current and future tax rate.'],
        ['Pension Calculator',               'pension calculator monthly benefit',    'Pension Calculator 2026 | FinanceSpots',
         'Estimate your monthly defined benefit pension at retirement. Enter years of service, average salary, and benefit multiplier. Compare pension vs. 401k scenarios. Free tool.'],
        ['FIRE Calculator',                  'FIRE calculator early retirement',      'FIRE Calculator 2026 -- Financial Independence Number',
         'Calculate your FIRE number (25x annual expenses) and early retirement date. See how your savings rate and investment returns determine when you can retire early. Free.'],
        ['Social Security Calculator',       'social security benefits calculator',   'Social Security Calculator 2026 | FinanceSpots',
         'Estimate Social Security benefits at claiming ages 62, 67, and 70. See the break-even point and how delaying benefits increases your lifetime retirement income. Free.'],
        ['Retirement Income Calculator',     'retirement income calculator 2026',     'Retirement Income Calculator 2026 | FinanceSpots',
         'Calculate sustainable monthly income from your retirement nest egg. Based on the 4% rule and withdrawal analysis. See how long your savings will last. Free tool.'],
        ['RMD Calculator',                   'required minimum distribution calculator','RMD Calculator 2026 -- IRA & 401k Withdrawals',
         'Calculate your Required Minimum Distribution from Traditional IRA, 401k, and inherited accounts. Updated with 2026 IRS life expectancy tables. Free RMD tool.'],
        ['Roth Conversion Calculator',       'Roth IRA conversion calculator 2026',   'Roth Conversion Calculator 2026 | FinanceSpots',
         'Calculate the tax cost and long-term benefit of converting a Traditional IRA to Roth. See break-even point and lifetime tax savings. 2026 Roth conversion strategy tool.'],
        ['Retirement Withdrawal Calculator', 'retirement withdrawal rate calculator',  'Retirement Withdrawal Calculator 2026 | FinanceSpots',
         'Calculate how long your retirement savings will last at different withdrawal rates. See the impact of inflation and investment returns on your retirement runway. Free.'],
        ['Early Retirement Calculator',      'early retirement calculator 2026',       'Early Retirement Calculator 2026 -- Retire Before 65',
         'Plan for retirement before age 65. Calculate your savings target, required savings rate, and years to retirement. See how spending cuts accelerate your timeline. Free.'],
    ];
    foreach ( $retirement as $d ) {
        $id = $get_id( $d[0] );
        $set_post_seo( $id, $d[1], $d[2], $d[3] );
    }

    /* ═══════════════════════════════════════════════════════
       6. CURRENCY CONVERTERS (8 tools)
       ═══════════════════════════════════════════════════════ */
    $currency = [
        ['Live Currency Converter',    'currency converter 2026',             'Live Currency Converter 2026 -- 150+ Currencies',
         'Convert between 150+ world currencies with live exchange rates. Free currency calculator updated daily. Enter any amount and get instant results. No signup required.'],
        ['Historical Exchange Rate',   'historical exchange rate lookup',     'Historical Exchange Rate Calculator 2026 | FinanceSpots',
         'Look up historical exchange rates for any currency pair on any date. Useful for tax calculations, financial reporting, and investment analysis. Free historical forex tool.'],
        ['Forex Pip Calculator',       'forex pip value calculator',          'Forex Pip Calculator 2026 -- Pip Value Calculator',
         'Calculate pip value for any forex pair and lot size. Essential for forex traders to size positions, calculate risk per trade, and measure profit/loss in account currency.'],
        ['Currency Strength Meter',    'currency strength meter 2026',        'Currency Strength Meter 2026 | FinanceSpots',
         'Compare the relative strength of USD, EUR, GBP, JPY, CAD, AUD, CHF, and more. See which currencies are strongest vs. weakest in real time. Free forex analysis tool.'],
        ['Cross Rate Calculator',      'currency cross rate calculator',      'Cross Rate Calculator 2026 | FinanceSpots',
         'Calculate cross exchange rates between any two currencies without converting through USD. Useful for international business transactions and travel planning. Free tool.'],
        ['Travel Money Calculator',    'travel money calculator 2026',        'Travel Money Calculator 2026 | FinanceSpots',
         'Convert your travel budget to local currency for any destination. Calculate daily spending limits, total trip cost, and currency exchange amounts before you travel.'],
        ['Cryptocurrency Converter',   'crypto to USD converter 2026',        'Cryptocurrency Converter 2026 -- Crypto to Fiat',
         'Convert any cryptocurrency to USD, EUR, GBP, and more in real time. Supports Bitcoin, Ethereum, and 100+ altcoins with current prices. Fast and free crypto converter.'],
        ['Currency Comparison Tool',   'currency exchange rate comparison',   'Currency Comparison Tool 2026 | FinanceSpots',
         'Compare exchange rates from multiple sources for the same currency pair. Find the best rate for your transfer or exchange and save money on international payments. Free.'],
    ];
    foreach ( $currency as $d ) {
        $id = $get_id( $d[0] );
        $set_post_seo( $id, $d[1], $d[2], $d[3] );
    }

    /* ═══════════════════════════════════════════════════════
       7. BUDGET ANALYZERS (14 tools)
       ═══════════════════════════════════════════════════════ */
    $budget = [
        ['Monthly Budget Planner',          'monthly budget planner 2026',          'Monthly Budget Planner 2026 | FinanceSpots',
         'Build a complete monthly budget with income and expense categories. Track spending, find savings opportunities, and take control of your personal finances. Free tool.'],
        ['50/30/20 Budget Calculator',      '50 30 20 budget rule calculator',      '50/30/20 Budget Calculator 2026 | FinanceSpots',
         'Apply the 50/30/20 budgeting rule to your income. Automatically split income into needs (50%), wants (30%), and savings (20%). Get an instant budget breakdown. Free.'],
        ['Expense Tracker',                 'monthly expense tracker 2026',         'Expense Tracker 2026 -- Track Daily Spending | FinanceSpots',
         'Track daily expenses by category and see where your money goes each month. Identify spending leaks and opportunities to save more. Free expense tracking tool.'],
        ['Income vs Expense Analyzer',      'income vs expense calculator',         'Income vs Expense Analyzer 2026 | FinanceSpots',
         'Get a clear picture of monthly income vs. expenses and calculate net cash flow. See your monthly surplus or deficit and identify areas to cut spending. Free tool.'],
        ['Debt to Income Ratio',            'debt to income ratio calculator',      'Debt-to-Income Ratio Calculator 2026 | FinanceSpots',
         'Calculate your debt-to-income (DTI) ratio for mortgage and loan qualification. Most lenders require DTI below 43%. See your DTI and how to improve it. Free tool.'],
        ['Household Budget Calculator',     'family budget calculator 2026',        'Household Budget Calculator 2026 | FinanceSpots',
         'Plan a complete household budget for your family. Enter income for all earners and expenses by category to see monthly and annual budget summary. Free budget tool.'],
        ['Zero Based Budget Calculator',    'zero based budgeting calculator',      'Zero Based Budget Calculator 2026 | FinanceSpots',
         'Assign every dollar of income a specific purpose with zero-based budgeting. End each month with $0 unallocated -- every dollar works for you. Free budgeting tool.'],
        ['Annual Budget Planner',           'annual budget planner 2026',           'Annual Budget Planner 2026 | FinanceSpots',
         'Plan your complete yearly budget and track spending against annual goals. Break down annual income and expenses by month and category for a full year. Free tool.'],
        ['Net Worth Calculator',            'net worth calculator 2026',            'Net Worth Calculator 2026 -- Assets Minus Liabilities',
         'Calculate your total net worth by entering all assets (home, investments, cash) and liabilities (mortgage, loans, credit cards). Track your wealth over time. Free.'],
        ['Cash Flow Calculator',            'monthly cash flow calculator',         'Cash Flow Calculator 2026 | FinanceSpots',
         'Calculate monthly cash flow from all income sources minus all expenses. Identify spending leaks and find opportunities to increase your positive cash flow. Free tool.'],
        ['Bill Payment Planner',            'bill payment schedule planner',        'Bill Payment Planner 2026 | FinanceSpots',
         'Schedule monthly bill payments to avoid late fees. Enter due dates and amounts to create a personalized bill payment calendar and never miss a payment again. Free.'],
        ['Grocery Budget Calculator',       'grocery budget calculator per month',  'Grocery Budget Calculator 2026 | FinanceSpots',
         'Set and track a realistic monthly grocery budget for your household size. See average food costs by household size and find simple ways to cut food spending. Free.'],
        ['Entertainment Budget Calculator', 'entertainment budget calculator',      'Entertainment Budget Calculator 2026 | FinanceSpots',
         'Allocate a realistic discretionary entertainment budget without overspending. Calculate how much you can safely spend on dining, streaming, and fun each month. Free.'],
        ['Savings Rate Calculator',         'savings rate calculator',              'Savings Rate Calculator 2026 -- % of Income Saved',
         'Calculate your personal savings rate as a percentage of gross and net take-home pay. Compare to the recommended 20% savings rate benchmark and track progress. Free.'],
    ];
    foreach ( $budget as $d ) {
        $id = $get_id( $d[0] );
        $set_post_seo( $id, $d[1], $d[2], $d[3] );
    }

    /* ═══════════════════════════════════════════════════════
       8. CRYPTO TOOLS (11 tools)
       ═══════════════════════════════════════════════════════ */
    $crypto = [
        ['Crypto P&L Calculator',            'crypto profit loss calculator',       'Crypto P&L Calculator 2026 | FinanceSpots',
         'Calculate profit and loss on any cryptocurrency trade. Enter buy price, sell price, and quantity to see profit, ROI percentage, and net gain after fees. Free tool.'],
        ['Staking Rewards Calculator',       'crypto staking rewards calculator',   'Staking Rewards Calculator 2026 -- Crypto APY Tool',
         'Project crypto staking rewards and compound APY for Bitcoin, Ethereum, Cardano, and more. Calculate daily, monthly, and annual staking income. Free staking tool.'],
        ['Crypto DCA Calculator',            'Bitcoin DCA calculator 2026',         'Crypto DCA Calculator 2026 | FinanceSpots',
         'Simulate dollar cost averaging (DCA) into Bitcoin or any cryptocurrency over time. See average cost basis, total invested, and current portfolio value. Free tool.'],
        ['Crypto Tax Calculator',            'cryptocurrency tax calculator 2026',  'Crypto Tax Calculator 2026 | FinanceSpots',
         'Calculate capital gains tax on cryptocurrency transactions for 2026. Supports FIFO, LIFO, and HIFO accounting methods. See short-term vs. long-term gains breakdown.'],
        ['Mining Profitability Calculator',  'crypto mining profitability 2026',    'Mining Profitability Calculator 2026 | FinanceSpots',
         'Calculate crypto mining profitability after electricity costs and hardware investment. Enter hash rate, power usage, and electricity cost to see daily net profit. Free.'],
        ['Crypto Portfolio Tracker',         'crypto portfolio tracker 2026',       'Crypto Portfolio Tracker 2026 | FinanceSpots',
         'Track your total crypto portfolio value, allocation percentages, and profit/loss. Enter coin holdings and average buy price to see your P&L instantly. Free tracker.'],
        ['Bitcoin Halving Countdown',        'Bitcoin halving countdown 2026',      'Bitcoin Halving Countdown 2026 | FinanceSpots',
         'See the countdown to the next Bitcoin halving event, expected block reward after halving, and historical price performance around past halvings. Free Bitcoin tool.'],
        ['Gas Fee Calculator',               'Ethereum gas fee calculator 2026',    'Ethereum Gas Fee Calculator 2026 | FinanceSpots',
         'Estimate Ethereum gas fees for any transaction type. Enter gas limit and current gas price in Gwei to see transaction cost in ETH and USD. Free ETH gas tool.'],
        ['NFT ROI Calculator',               'NFT profit calculator 2026',          'NFT ROI Calculator 2026 | FinanceSpots',
         'Calculate return on investment for NFT purchases. Enter buy price, sell price, and marketplace fees to see net profit, ROI percentage, and return after gas fees.'],
        ['Yield Farming Calculator',         'DeFi yield farming calculator 2026',  'Yield Farming Calculator 2026 -- DeFi APY Tool',
         'Project DeFi yield farming returns and calculate impermanent loss risk. Enter liquidity pool details, APY, and holding period to see net returns vs. hodling. Free.'],
        ['Crypto Converter',                 'cryptocurrency converter to USD',     'Crypto Converter 2026 -- Any Coin to USD, EUR, GBP',
         'Convert any cryptocurrency to USD, EUR, GBP, and other fiat currencies. Supports Bitcoin, Ethereum, and 200+ altcoins with real-time prices. Fast and free tool.'],
    ];
    foreach ( $crypto as $d ) {
        $id = $get_id( $d[0] );
        $set_post_seo( $id, $d[1], $d[2], $d[3] );
    }

    /* ═══════════════════════════════════════════════════════
       9. CATEGORY PAGES -- term meta for RankMath
       ═══════════════════════════════════════════════════════ */
    $categories_seo = [
        'loan-calculators' => [
            'kw'    => 'free loan calculators 2026',
            'title' => 'Free Loan Calculators 2026 -- Mortgage, Auto, VA & More | FinanceSpots',
            'desc'  => 'Browse 18 free loan calculators: mortgage, auto loan, personal loan, FHA, VA, refinance, amortization, and more. Instant results, no signup, PDF export included.',
        ],
        'investment-tools' => [
            'kw'    => 'free investment calculators 2026',
            'title' => 'Free Investment Tools 2026 -- ROI, Compound Interest, Portfolio | FinanceSpots',
            'desc'  => 'Explore 22 free investment calculators: ROI, compound interest, dividend yield, CAGR, portfolio analyzer, Sharpe ratio, and more. Instant results, no signup required.',
        ],
        'tax-calculators' => [
            'kw'    => 'free tax calculators 2026',
            'title' => 'Free Tax Calculators 2026 -- Income Tax, Capital Gains & More | FinanceSpots',
            'desc'  => '15 free tax calculators for 2026: income tax estimator, capital gains, self-employment tax, tax bracket finder, W-4 calculator, and more. Updated for 2026 IRS rules.',
        ],
        'savings-planners' => [
            'kw'    => 'free savings calculators 2026',
            'title' => 'Free Savings Planners 2026 -- Goals, Emergency Fund & CD | FinanceSpots',
            'desc'  => '12 free savings planning tools: emergency fund, savings goal, CD calculator, HYSA comparison, 52-week savings challenge, down payment planner, and more.',
        ],
        'retirement-planning' => [
            'kw'    => 'retirement planning calculators 2026',
            'title' => 'Retirement Planning Calculators 2026 -- 401k, IRA, FIRE | FinanceSpots',
            'desc'  => '10 free retirement planning calculators: 401k, Roth IRA, FIRE number, Social Security benefits, RMD, Roth conversion, and retirement income. Updated for 2026.',
        ],
        'currency-converters' => [
            'kw'    => 'free currency converter 2026',
            'title' => 'Free Currency Converters 2026 -- Live Forex & Crypto Rates | FinanceSpots',
            'desc'  => '8 free currency tools: live converter for 150+ currencies, historical exchange rates, forex pip calculator, travel money converter, and crypto converter. Always free.',
        ],
        'budget-analyzers' => [
            'kw'    => 'free budget calculators 2026',
            'title' => 'Free Budget Calculators 2026 -- Monthly Planner, 50/30/20 | FinanceSpots',
            'desc'  => '14 free budget analysis tools: monthly budget planner, 50/30/20 calculator, net worth tracker, DTI ratio, zero-based budget, expense tracker, and more.',
        ],
        'crypto-tools' => [
            'kw'    => 'free crypto calculators 2026',
            'title' => 'Free Crypto Calculators 2026 -- Bitcoin P&L, DCA, Tax & More | FinanceSpots',
            'desc'  => '11 free crypto calculators: P&L calculator, DCA simulator, staking rewards, crypto tax, mining profitability, Ethereum gas fees, yield farming, and more.',
        ],
    ];
    foreach ( $categories_seo as $slug => $seo ) {
        $term = get_term_by( 'slug', $slug, 'fs_tool_cat' );
        if ( ! $term ) continue;
        update_term_meta( $term->term_id, 'rank_math_focus_keyword', $seo['kw'] );
        update_term_meta( $term->term_id, 'rank_math_title',         $seo['title'] );
        update_term_meta( $term->term_id, 'rank_math_description',   $seo['desc'] );
        update_term_meta( $term->term_id, 'rank_math_robots',        [ 'index', 'follow' ] );
    }

    /* ═══════════════════════════════════════════════════════
       10. HOMEPAGE SEO
       ═══════════════════════════════════════════════════════ */
    $front_id = get_option( 'page_on_front' );
    if ( $front_id ) {
        update_post_meta( $front_id, 'rank_math_focus_keyword', 'free financial calculators 2026' );
        update_post_meta( $front_id, 'rank_math_title',         'FinanceSpots -- 110+ Free Financial Calculators 2026 | No Signup' );
        update_post_meta( $front_id, 'rank_math_description',   'FinanceSpots offers 110+ free financial calculators: mortgage, investment, tax, retirement, crypto, currency & budget tools. Instant results, PDF export, no signup required. Trusted by 50,000+ users.' );
        update_post_meta( $front_id, 'rank_math_robots',        [ 'index', 'follow' ] );
    }

    /* ═══════════════════════════════════════════════════════
       11. TOOLS ARCHIVE PAGE (/tools/)
       ═══════════════════════════════════════════════════════ */
    update_option( 'rank_math_title_fs_tool_archive',       'All Finance Tools 2026 -- 110+ Free Calculators | FinanceSpots' );
    update_option( 'rank_math_description_fs_tool_archive', 'Browse all 110+ free finance calculators organized into 8 categories: loan calculators, investment tools, tax calculators, savings planners, retirement planning, crypto, currency, and budget tools.' );

    update_option( 'fs_full_seo_v4', true );
}
add_action( 'admin_init', 'fs_complete_seo_all_pages', 30 );
add_action( 'init',       'fs_complete_seo_all_pages', 50 );

/* ── Immediate fix: patch bad schema data before RankMath processes it ── */
add_filter( 'rank_math/json_ld', function( $data, $jsonld ) {
    if ( ! is_singular( 'fs_tool' ) ) return $data;
    $clean = [];
    foreach ( $data as $key => $value ) {
        if ( is_array( $value ) && isset( $value['@type'] ) ) {
            $clean[ $key ] = $value;
        } elseif ( ! is_array( $value ) ) {
            // Non-array entries (strings etc.) are safe to keep
            $clean[ $key ] = $value;
        }
        // Drop arrays without @type -- these are the broken schema records
    }
    return $clean;
}, 999, 2 );

/* ── One-time: delete ALL bad schema postmeta from DB (runs on both frontend + admin) ── */
function fs_cleanup_bad_schema() {
    if ( get_option( 'fs_schema_cleanup_v3' ) ) return;
    global $wpdb;
    // Nuke every rank_math_schema* meta from fs_tool posts
    $wpdb->query( "
        DELETE pm FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE p.post_type = 'fs_tool'
          AND pm.meta_key LIKE '%rank_math_schema%'
    " );
    // Also clean up from any other post (VA loan special page etc.)
    $wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key = 'rank_math_schema'" );
    $wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key = 'rank_math_schema_WebPage'" );
    $wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key = 'rank_math_schema_WebApplication'" );
    delete_option( 'fs_schema_cleanup_v1' );
    delete_option( 'fs_schema_cleanup_v2' );
    update_option( 'fs_schema_cleanup_v3', true );
}
add_action( 'init',       'fs_cleanup_bad_schema', 1 );   // frontend + admin
add_action( 'admin_init', 'fs_cleanup_bad_schema', 1 );

/* ── Enqueue Chart.js on tool pages only ── */
function fs_enqueue_tool_scripts() {
    if ( is_singular( 'fs_tool' ) ) {
        wp_enqueue_script( 'chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js', [], '4.4.0', true );
        wp_enqueue_script( 'jspdf',   'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js',  [], '2.5.1', true );
    }
    // Blog nav dropdown toggle (all pages)
    wp_add_inline_script( 'jquery', "
(function(){
    document.querySelectorAll('.fs-nav-has-dropdown .fs-nav-dropdown-toggle').forEach(function(toggle){
        toggle.addEventListener('click', function(e){
            var li = this.closest('.fs-nav-has-dropdown');
            var isOpen = li.classList.contains('is-open');
            document.querySelectorAll('.fs-nav-has-dropdown.is-open').forEach(function(el){ el.classList.remove('is-open'); });
            if(!isOpen){ li.classList.add('is-open'); e.preventDefault(); }
        });
    });
    document.addEventListener('click', function(e){
        if(!e.target.closest('.fs-nav-has-dropdown')){
            document.querySelectorAll('.fs-nav-has-dropdown.is-open').forEach(function(el){ el.classList.remove('is-open'); });
        }
    });
})();
" );
}
add_action( 'wp_enqueue_scripts', 'fs_enqueue_tool_scripts' );

/* =========================================================
   AI FINANCIAL ADVISOR -- Real-Time Claude API Chat
   ========================================================= */

// Admin settings page to store API key
function fs_ai_settings_menu() {
    add_options_page( 'FinanceSpots AI Settings', 'FS AI Settings', 'manage_options', 'fs-ai-settings', 'fs_ai_settings_page' );
}
add_action( 'admin_menu', 'fs_ai_settings_menu' );

function fs_ai_settings_page() {
    if ( isset( $_POST['fs_ai_api_key'] ) && check_admin_referer( 'fs_ai_save' ) ) {
        update_option( 'fs_ai_api_key', sanitize_text_field( $_POST['fs_ai_api_key'] ) );
        echo '<div class="notice notice-success"><p>API key saved!</p></div>';
    }
    $key = get_option( 'fs_ai_api_key', '' );
    ?>
    <div class="wrap">
        <h1>FinanceSpots AI Settings</h1>
        <p>Enter your <strong>Anthropic Claude API key</strong> to enable real AI-powered financial advice in the dashboard chat.</p>
        <p>Get your key at: <a href="https://console.anthropic.com" target="_blank">console.anthropic.com</a></p>
        <form method="post">
            <?php wp_nonce_field( 'fs_ai_save' ); ?>
            <table class="form-table">
                <tr>
                    <th>Claude API Key</th>
                    <td>
                        <input type="password" name="fs_ai_api_key" value="<?php echo esc_attr( $key ); ?>" style="width:420px" placeholder="sk-ant-api03-..." />
                        <p class="description">Your key is stored securely in the database and never exposed to visitors.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button( 'Save API Key' ); ?>
        </form>
    </div>
    <?php
}

// AJAX handler -- called from the dashboard AI chat
function fs_ai_chat_handler() {
    check_ajax_referer( 'fs_ai_chat_nonce', 'nonce' );

    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
    if ( empty( $message ) ) {
        wp_send_json_error( [ 'reply' => 'Please type a question.' ] );
    }

    $api_key = get_option( 'fs_ai_api_key', '' );
    if ( empty( $api_key ) ) {
        // Fallback: smart rule-based response
        wp_send_json_success( [ 'reply' => fs_ai_fallback_response( $message ) ] );
    }

    $body = wp_json_encode( [
        'model'      => 'claude-haiku-4-5-20261001',
        'max_tokens' => 400,
        'system'     => 'You are an expert personal finance advisor for FinanceSpots.com, a free financial tools website owned by Abdul Rahman. Give concise, actionable, and accurate financial advice. Focus on: budgeting, saving, investing, debt payoff, taxes, retirement, and emergency funds. Use US context (2026 tax laws). Keep answers under 120 words. Do not give legal advice. Start responses with a relevant emoji.',
        'messages'   => [
            [ 'role' => 'user', 'content' => $message ],
        ],
    ] );

    $response = wp_remote_post( 'https://api.anthropic.com/v1/messages', [
        'timeout' => 20,
        'headers' => [
            'x-api-key'         => $api_key,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ],
        'body' => $body,
    ] );

    if ( is_wp_error( $response ) ) {
        wp_send_json_success( [ 'reply' => fs_ai_fallback_response( $message ) ] );
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );
    $reply = isset( $data['content'][0]['text'] ) ? $data['content'][0]['text'] : fs_ai_fallback_response( $message );

    wp_send_json_success( [ 'reply' => $reply ] );
}
add_action( 'wp_ajax_fs_ai_chat',        'fs_ai_chat_handler' );
add_action( 'wp_ajax_nopriv_fs_ai_chat', 'fs_ai_chat_handler' );

// Rule-based fallback when no API key is set
function fs_ai_fallback_response( $msg ) {
    $msg = strtolower( $msg );
    $responses = [
        'emergency'   => '&#128737;&#65039; Your emergency fund should cover 3-6 months of essential expenses (rent, food, utilities). Keep it in a high-yield savings account earning 4-5% APY. Never invest it in stocks -- it must stay liquid and safe.',
        'budget'      => '&#128179; Use the 50/30/20 rule: 50% for needs (rent, food), 30% for wants (dining, entertainment), 20% for savings and debt payoff. Track every dollar for 30 days to find leaks in your spending.',
        'debt'        => '&#128279; Two proven strategies: Avalanche (pay highest interest rate first -- saves the most money) or Snowball (pay smallest balance first -- builds momentum). Always pay at least the minimum on all debts.',
        'invest'      => '&#128200; Start with: (1) Get your full employer 401k match, (2) Max your HSA if eligible, (3) Max Roth IRA ($7,000/yr), (4) Max 401k ($23,500/yr). Use low-cost index funds like VTI or VTSAX.',
        'tax'         => '&#129534; Biggest tax savers: 401k contributions reduce taxable income dollar-for-dollar. HSA gives triple tax benefit. Max both before April 15. At a 22% bracket, every $1,000 in 401k saves you $220 in taxes.',
        'retirement'  => '&#127958;&#65039; The 4% rule: At retirement, you can withdraw 4% of your portfolio yearly. To replace $5,000/month in retirement, you need $1.5M saved. Start early -- $300/month at 25 becomes $1M by 65 at 7% returns.',
        'savings'     => '&#128176; Automate your savings the day your paycheck arrives -- pay yourself first. Even 1% more savings rate makes a huge difference over decades. Use a HYSA for short-term goals (4-5% APY).',
        'credit'      => '&#128179; Improve your credit score: Pay on time (35% of score), keep utilization below 30% (30%), keep old accounts open (15%), limit new applications (10%). Check your free report at AnnualCreditReport.com.',
        'mortgage'    => '&#127968; Rule of thumb: Your mortgage payment should be under 28% of gross monthly income. Put 20% down to avoid PMI. At 7% rate, $300k mortgage = ~$2,000/month. Use our mortgage calculator for exact numbers.',
        'crypto'      => '₿ Treat crypto as high-risk speculation -- never invest more than 5-10% of your portfolio. It has no earnings, dividends, or intrinsic value. Stick to Bitcoin and Ethereum if you invest. Never invest emergency funds in crypto.',
        'default'     => '&#129302; Great question! For personalized numbers, use the panels in this dashboard. Key principles: (1) Build a 3-6 month emergency fund first, (2) Get your full employer 401k match, (3) Pay off high-rate debt over 7%, (4) Max tax-advantaged accounts, (5) Invest in index funds. Which area do you want to explore?',
    ];
    foreach ( $responses as $key => $reply ) {
        if ( $key !== 'default' && strpos( $msg, $key ) !== false ) {
            return $reply;
        }
    }
    return $responses['default'];
}

// Inject AJAX URL + nonce for AI chat on tool pages
function fs_ai_chat_localize() {
    if ( is_singular( 'fs_tool' ) || is_front_page() ) {
        wp_add_inline_script( 'jquery', '
            var fsAiAjax = ' . wp_json_encode( [
                'url'   => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'fs_ai_chat_nonce' ),
            ] ) . ';
        ', 'before' );
    }
}
add_action( 'wp_enqueue_scripts', 'fs_ai_chat_localize', 20 );

/* ── RankMath: Auto title for fs_tool if blank ── */
function fs_rankmath_tool_title( $title, $post_id ) {
    if ( get_post_type( $post_id ) !== 'fs_tool' ) return $title;
    if ( $title ) return $title;
    return get_the_title( $post_id ) . ' -- Free Online Calculator 2026 | FinanceSpots';
}
add_filter( 'rank_math/title', 'fs_rankmath_tool_title', 10, 2 );

/* ── RankMath: Auto description for fs_tool if blank ── */
function fs_rankmath_tool_desc( $desc, $post_id ) {
    if ( get_post_type( $post_id ) !== 'fs_tool' ) return $desc;
    if ( $desc ) return $desc;
    $excerpt = get_post_field( 'post_excerpt', $post_id );
    return $excerpt ?: 'Use our free ' . get_the_title( $post_id ) . ' instantly. No signup required. PDF export included.';
}
add_filter( 'rank_math/description', 'fs_rankmath_tool_desc', 10, 2 );

/* ── Blog Publisher ── */
require_once get_template_directory() . '/inc/blog-publisher.php';

/* ── Trust Strip Admin Panel ── */
require_once get_template_directory() . '/inc/trust-strip-admin.php';

/* ── Leads & Auth System ── */
require_once get_template_directory() . '/inc/leads-system.php';

/* ── PRO System ── */
require_once get_template_directory() . '/inc/pro-system.php';

/* ── PRO Tools Gate ── */
require_once get_template_directory() . '/inc/pro-tools-gate.php';

/* =========================================================
   GOOGLE ADSENSE + COOKIE CONSENT + SEO
   ========================================================= */

/* ── AdSense verification meta tag ── */
add_action( 'wp_head', function() {
    $adsense_id = get_option('fs_adsense_publisher_id', '');
    if ( $adsense_id ) {
        echo '<meta name="google-adsense-account" content="' . esc_attr($adsense_id) . '">' . "\n";
        echo '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . esc_attr($adsense_id) . '" crossorigin="anonymous"></script>' . "\n";
    }
}, 1 );

/* ── Cookie Consent Banner ── */
add_action( 'wp_footer', function() {
    if ( is_admin() ) return;
    ?>
    <div class="fs-cookie-banner" id="fs-cookie-banner" role="dialog" aria-label="Cookie consent" style="display:none;">
        <div class="fs-cookie-banner__inner">
            <div class="fs-cookie-banner__text">
                <span class="fs-cookie-banner__icon">&#127850;</span>
                <div>
                    <strong>We use cookies</strong>
                    <span>We use cookies to improve your experience and show relevant ads. By continuing you agree to our <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a>.</span>
                </div>
            </div>
            <div class="fs-cookie-banner__actions">
                <button class="fs-cookie-btn fs-cookie-btn--accept" id="fs-cookie-accept">Accept All</button>
                <button class="fs-cookie-btn fs-cookie-btn--decline" id="fs-cookie-decline">Decline</button>
            </div>
        </div>
    </div>
    <style>
    .fs-cookie-banner{position:fixed;bottom:0;left:0;right:0;z-index:99997;padding:0 16px 16px;animation:fsCookieIn .4s ease;}
    @keyframes fsCookieIn{from{transform:translateY(100%);}to{transform:translateY(0);}}
    .fs-cookie-banner__inner{background:#1E2840;border:1px solid rgba(255,255,255,.1);border-radius:16px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;gap:20px;max-width:900px;margin:0 auto;box-shadow:0 -4px 40px rgba(0,0,0,.4);flex-wrap:wrap;}
    .fs-cookie-banner__text{display:flex;align-items:center;gap:14px;flex:1;}
    .fs-cookie-banner__icon{font-size:1.8rem;flex-shrink:0;}
    .fs-cookie-banner__text strong{display:block;color:#fff;font-size:.9rem;margin-bottom:3px;}
    .fs-cookie-banner__text span{color:#64748B;font-size:.82rem;line-height:1.5;}
    .fs-cookie-banner__text a{color:#10B981;text-decoration:none;}
    .fs-cookie-banner__actions{display:flex;gap:8px;flex-shrink:0;}
    .fs-cookie-btn{padding:9px 20px;border-radius:10px;font-size:.85rem;font-weight:700;cursor:pointer;border:none;font-family:inherit;transition:all .2s;}
    .fs-cookie-btn--accept{background:#10B981;color:#fff;}
    .fs-cookie-btn--accept:hover{background:#059669;}
    .fs-cookie-btn--decline{background:rgba(255,255,255,.08);color:#94A3B8;}
    .fs-cookie-btn--decline:hover{background:rgba(255,255,255,.12);}
    </style>
    <script>
    (function(){
        var banner = document.getElementById('fs-cookie-banner');
        var consent = localStorage.getItem('fs_cookie_consent');
        if(!consent) setTimeout(function(){ banner.style.display='block'; }, 1500);
        document.getElementById('fs-cookie-accept').addEventListener('click', function(){
            localStorage.setItem('fs_cookie_consent','accepted');
            banner.style.display='none';
        });
        document.getElementById('fs-cookie-decline').addEventListener('click', function(){
            localStorage.setItem('fs_cookie_consent','declined');
            banner.style.display='none';
        });
    })();
    </script>
    <?php
}, 99 );

/* ── AdSense Admin Settings ── */
add_action( 'admin_menu', function() {
    add_submenu_page( 'fs-pro', 'AdSense Settings', 'AdSense', 'manage_options', 'fs-adsense', function() {
        if ( isset($_POST['fs_save_adsense']) && check_admin_referer('fs_adsense') ) {
            update_option('fs_adsense_publisher_id', sanitize_text_field($_POST['fs_adsense_id']));
            update_option('fs_adsense_slot_sidebar', sanitize_text_field($_POST['fs_adsense_slot_sidebar']));
            update_option('fs_adsense_slot_inline',  sanitize_text_field($_POST['fs_adsense_slot_inline']));
            echo '<div class="notice notice-success"><p>AdSense settings saved!</p></div>';
        }
        ?>
        <div class="wrap" style="font-family:-apple-system,sans-serif;max-width:650px;">
            <h1 style="font-size:1.5rem;margin-bottom:6px;">&#128176; Google AdSense Settings</h1>
            <p style="color:#64748B;margin-bottom:20px;">Connect your AdSense account to start earning from ads.</p>
            <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:10px;padding:14px 18px;margin-bottom:24px;font-size:.875rem;">
                <strong>How to get approved by Google AdSense:</strong><br>
                1. Site must have original content (&#9989; 130+ tools + 11 blog posts)<br>
                2. Privacy Policy page must exist (&#9989; done)<br>
                3. Contact page must exist (&#9989; done)<br>
                4. Site must be live on a real domain (not localhost)<br>
                5. Apply at <a href="https://adsense.google.com" target="_blank">adsense.google.com</a>
            </div>
            <form method="post" style="background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:28px;">
                <?php wp_nonce_field('fs_adsense'); ?>
                <table style="width:100%;border-collapse:collapse;">
                    <?php foreach([
                        ['fs_adsense_id',           'Publisher ID',         'ca-pub-XXXXXXXXXXXXXXXX', 'fs_adsense_publisher_id'],
                        ['fs_adsense_slot_sidebar',  'Ad Slot -- Sidebar',    'XXXXXXXXXX',              'fs_adsense_slot_sidebar'],
                        ['fs_adsense_slot_inline',   'Ad Slot -- Inline',     'XXXXXXXXXX',              'fs_adsense_slot_inline'],
                    ] as [$name,$label,$ph,$opt]): ?>
                    <tr style="border-bottom:1px solid #F1F5F9;">
                        <td style="padding:14px 0;width:200px;font-size:.875rem;font-weight:600;color:#374151;"><?php echo $label; ?></td>
                        <td style="padding:14px 0;">
                            <input type="text" name="<?php echo $name; ?>" value="<?php echo esc_attr(get_option($opt,'')); ?>" placeholder="<?php echo $ph; ?>" style="width:100%;padding:8px 12px;border:1.5px solid #E2E8F0;border-radius:8px;font-size:.875rem;font-family:monospace;">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <button name="fs_save_adsense" style="margin-top:20px;background:#10B981;color:#fff;border:none;padding:11px 24px;border-radius:8px;font-weight:700;cursor:pointer;">Save AdSense Settings</button>
            </form>
        </div>
        <?php
    });
});

/* =========================================================
   CLEAN UP JUNK PAGES — delete old off-topic posts,
   fix Rank Math noindex on search/author/feeds, remove
   search placeholder URL from sitemap. Version: v1.
   ========================================================= */
function fs_clean_junk_pages() {
    if ( get_option( 'fs_clean_junk_v1' ) ) return;

    /* 1. Delete old off-topic real-estate posts */
    $old_slugs = [
        'the-ultimate-guide-to-benefits-of-virtual-tours-in-real-estate',
        '5g-impact-on-real-estate',
    ];
    foreach ( $old_slugs as $slug ) {
        $post = get_page_by_path( $slug, OBJECT, 'post' );
        if ( $post ) wp_delete_post( $post->ID, true );
    }

    /* 2. Delete all posts in old real-estate-technology category then remove it */
    $re_cat = get_term_by( 'slug', 'real-estate-technology', 'category' );
    if ( $re_cat ) {
        $re_posts = get_posts( [ 'category' => $re_cat->term_id, 'numberposts' => -1, 'post_status' => 'any', 'fields' => 'ids' ] );
        foreach ( $re_posts as $pid ) wp_delete_post( $pid, true );
        wp_delete_term( $re_cat->term_id, 'category' );
    }

    /* 3. Noindex uncategorized category */
    $uncat = get_term_by( 'slug', 'uncategorized', 'category' );
    if ( $uncat ) {
        update_term_meta( $uncat->term_id, 'rank_math_robots', [ 'noindex' ] );
    }

    /* 4. Rank Math: ensure search, author, 404 are noindex */
    $titles = get_option( 'rank-math-options-titles', [] );
    $titles['search_robots'] = [ 'noindex' ];
    $titles['404_robots']    = [ 'noindex' ];
    $titles['author_robots'] = [ 'noindex' ];
    /* exclude /page/ archive pagination from sitemap */
    $titles['paginate_archive_disable'] = 'on';
    update_option( 'rank-math-options-titles', $titles );

    /* 5. Remove search-form placeholder URL from Rank Math sitemap */
    $sitemap = get_option( 'rank-math-options-sitemap', [] );
    $sitemap['exclude_posts'] = isset( $sitemap['exclude_posts'] ) ? $sitemap['exclude_posts'] : '';
    update_option( 'rank-math-options-sitemap', $sitemap );

    /* 6. Block feeds and search from Rank Math sitemap */
    add_filter( 'rank_math/sitemap/exclude_post', function( $exclude, $post ) {
        if ( isset( $post->url ) && (
            strpos( $post->url, '/feed/' ) !== false ||
            strpos( $post->url, '?s=' )    !== false ||
            strpos( $post->url, '{search_term' ) !== false
        ) ) return true;
        return $exclude;
    }, 10, 2 );

    update_option( 'fs_clean_junk_v1', true );
}
add_action( 'admin_init', 'fs_clean_junk_pages', 8 );
add_action( 'init',       'fs_clean_junk_pages', 8 );

/* =========================================================
   DELETE OLD V1 BLOG POSTS + FIX CATEGORIES
   Old posts still live alongside new v3 posts — wastes
   crawl budget and confuses Google. Version-gated: v1.
   ========================================================= */
function fs_delete_old_blog_posts() {
    if ( get_option( 'fs_delete_old_blogs_v1' ) ) return;

    /* Old v1 slugs — still live, thin content, replaced by v3 posts */
    $old_slugs = [
        'budget-planner-guide',
        'compound-interest-guide',
        'crypto-profit-guide',
        'emergency-fund-guide',
        'investment-calculator-guide',
        'mortgage-calculator-guide',
        'pay-off-debt-fast-guide',
        'personal-loan-guide',
        'retirement-planning-guide',
        'tax-calculator-guide-2026',
    ];

    foreach ( $old_slugs as $slug ) {
        $post = get_page_by_path( $slug, OBJECT, 'post' );
        if ( $post ) wp_delete_post( $post->ID, true );
    }

    /* Noindex thin category archives */
    $noindex_cats = [ 'budgeting', 'investing', 'loans', 'uncategorized' ];
    foreach ( $noindex_cats as $cat_slug ) {
        $term = get_term_by( 'slug', $cat_slug, 'category' );
        if ( $term ) {
            update_term_meta( $term->term_id, 'rank_math_robots', [ 'noindex' ] );
        }
    }

    /* Noindex /pro-success/ page (thin upgrade page) */
    $pro_page = get_page_by_path( 'pro-success', OBJECT, 'page' );
    if ( $pro_page ) update_post_meta( $pro_page->ID, 'rank_math_robots', [ 'noindex' ] );

    /* Force sitemap regeneration */
    delete_transient( 'rank_math_sitemap_cache' );
    do_action( 'rank_math/sitemap/clear_cache' );

    update_option( 'fs_delete_old_blogs_v1', true );
}
add_action( 'admin_init', 'fs_delete_old_blog_posts', 9 );
add_action( 'init',       'fs_delete_old_blog_posts', 9 );

/* ── Permanently block feed/search URLs from Rank Math sitemap ── */
add_filter( 'rank_math/sitemap/entry', function( $url_data ) {
    if ( ! isset( $url_data['loc'] ) ) return $url_data;
    $loc = $url_data['loc'];
    if (
        strpos( $loc, '/feed'           ) !== false ||
        strpos( $loc, '?s='             ) !== false ||
        strpos( $loc, '{search_term'    ) !== false ||
        strpos( $loc, '/author/'        ) !== false ||
        strpos( $loc, '/page/'          ) !== false ||
        strpos( $loc, 'wp-emoji'        ) !== false
    ) return false;
    return $url_data;
}, 10, 1 );
