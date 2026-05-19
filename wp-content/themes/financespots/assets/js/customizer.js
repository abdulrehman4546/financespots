/**
 * Customizer Live Preview (postMessage transport)
 *
 * @package financespots
 */
(function ($) {
    'use strict';

    var api = wp.customize;

    var textBindings = {
        'fs_hero_badge':       '.fs-hero__badge',
        'fs_hero_title_1':     '.fs-hero__title-line--plain',
        'fs_hero_title_2':     '.fs-hero__title-line--gradient',
        'fs_hero_sub':         '.fs-hero__sub',
        'fs_hero_cta_text':    '.fs-hero .fs-btn--primary',
        'fs_hero_cta2_text':   '.fs-hero .fs-btn--ghost',
        'fs_ai_title':         '.fs-ai-section .fs-section-title',
        'fs_ai_desc':          '.fs-ai-section__desc',
        'fs_nl_title':         '.fs-newsletter__title',
        'fs_nl_desc':          '.fs-newsletter__desc',
        'fs_nl_btn':           '.fs-newsletter .fs-btn',
        'fs_footer_about':     '.fs-footer__about',
        'fs_footer_copyright': '.fs-footer__copyright',
        'fs_badge1_text':      '#fs-badge1-text',
        'fs_badge3_text':      '#fs-badge3-text',
    };

    Object.keys(textBindings).forEach(function (key) {
        api(key, function (value) {
            value.bind(function (to) {
                $(textBindings[key]).text(to);
            });
        });
    });

    var urlBindings = {
        'fs_hero_cta_url':  '.fs-hero .fs-btn--primary',
        'fs_hero_cta2_url': '.fs-hero .fs-btn--ghost',
        'fs_badge1_url':    '#fs-badge1',
        'fs_badge3_url':    '#fs-badge3',
    };

    Object.keys(urlBindings).forEach(function (key) {
        api(key, function (value) {
            value.bind(function (to) {
                $(urlBindings[key]).attr('href', to);
            });
        });
    });

    // Color bindings via CSS variables
    var colorMap = {
        'fs_color_primary':   '--fs-primary',
        'fs_color_secondary': '--fs-secondary',
        'fs_color_gold':      '--fs-gold',
        'fs_color_bg':        '--fs-bg',
        'fs_color_text':      '--fs-text',
        'fs_color_card_bg':   '--fs-card-bg',
    };

    Object.keys(colorMap).forEach(function (key) {
        api(key, function (value) {
            value.bind(function (to) {
                document.documentElement.style.setProperty(colorMap[key], to);
            });
        });
    });

})(jQuery);
