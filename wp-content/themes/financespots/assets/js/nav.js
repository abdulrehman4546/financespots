/* Primary navigation: Calculators mega menu (hover, click, keyboard) + mobile accordion. */
(function () {
    'use strict';
    var item = document.querySelector('.fs-nav-item--mega');
    var btn = document.getElementById('fs-mega-btn');
    var closeTimer;

    function setOpen(open) {
        if (!item || !btn) return;
        item.classList.toggle('is-open', open);
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    }
    if (item && btn) {
        var canHover = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            setOpen(!item.classList.contains('is-open'));
        });
        if (canHover) {
            item.addEventListener('mouseenter', function () { clearTimeout(closeTimer); setOpen(true); });
            item.addEventListener('mouseleave', function () { closeTimer = setTimeout(function () { setOpen(false); }, 160); });
        }
        document.addEventListener('click', function (e) { if (!item.contains(e.target)) setOpen(false); });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && item.classList.contains('is-open')) { setOpen(false); btn.focus(); }
        });
        item.addEventListener('focusout', function (e) {
            if (e.relatedTarget && !item.contains(e.relatedTarget)) setOpen(false);
        });
        window.addEventListener('scroll', function () { if (item.classList.contains('is-open') && window.scrollY > 120) setOpen(false); }, { passive: true });
    }

    /* mobile accordion */
    document.querySelectorAll('.fs-mnav__toggle, .fs-mnav__subtoggle').forEach(function (t) {
        t.addEventListener('click', function () {
            var open = t.getAttribute('aria-expanded') === 'true';
            t.setAttribute('aria-expanded', open ? 'false' : 'true');
            var panel = document.getElementById(t.getAttribute('aria-controls'));
            if (panel) panel.hidden = open;
        });
    });
})();
