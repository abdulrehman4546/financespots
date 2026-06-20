/**
 * FinanceSpots Theme — Main JavaScript
 * Version: 1.0.0
 */

(function () {
    'use strict';

    /* ============================================================
       1. DOM READY
       ============================================================ */
    document.addEventListener('DOMContentLoaded', function () {
        initHeader();
        initMobileMenu();
        initSearch();
        initTicker();
        initScrollAnimations();
        initCounters();
        initParticles();
        initPortfolioChart();
        initMiniCharts();
        initAIInsightRotator();
        initHeroCardAnimation();
        initCategoryFilter();
        initTestimonialsCarousel();
        initProgressBar();
        initBackToTop();
        initSmoothScroll();
        initToolCardSave();
        initAIStreamAnimation();
        initCarouselDots();
    });

    /* ============================================================
       2. HEADER — sticky + scroll class
       ============================================================ */
    function initHeader() {
        var header = document.getElementById('fs-header');
        if (!header) return;

        var lastScrollY = 0;
        var ticking = false;

        function onScroll() {
            lastScrollY = window.scrollY;
            if (!ticking) {
                requestAnimationFrame(function () {
                    if (lastScrollY > 20) {
                        header.classList.add('scrolled');
                    } else {
                        header.classList.remove('scrolled');
                    }
                    ticking = false;
                });
                ticking = true;
            }
        }

        window.addEventListener('scroll', onScroll, { passive: true });
    }

    /* ============================================================
       3. MOBILE MENU
       ============================================================ */
    function initMobileMenu() {
        var hamburger  = document.getElementById('fs-hamburger');
        var mobileNav  = document.getElementById('fs-mobile-nav');
        var body       = document.body;

        if (!hamburger || !mobileNav) return;

        function openMenu() {
            hamburger.setAttribute('aria-expanded', 'true');
            mobileNav.classList.add('open');
            mobileNav.setAttribute('aria-hidden', 'false');
            body.style.overflow = 'hidden';
        }

        function closeMenu() {
            hamburger.setAttribute('aria-expanded', 'false');
            mobileNav.classList.remove('open');
            mobileNav.setAttribute('aria-hidden', 'true');
            body.style.overflow = '';
        }

        hamburger.addEventListener('click', function () {
            var isOpen = hamburger.getAttribute('aria-expanded') === 'true';
            isOpen ? closeMenu() : openMenu();
        });

        // Close on outside click
        document.addEventListener('click', function (e) {
            if (!hamburger.contains(e.target) && !mobileNav.contains(e.target)) {
                closeMenu();
            }
        });

        // Close on nav link click
        mobileNav.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', closeMenu);
        });

        // Close on Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeMenu();
        });
    }

    /* ============================================================
       4. SCROLL ANIMATIONS — IntersectionObserver
       ============================================================ */
    function initScrollAnimations() {
        // Immediately show all animated elements if reduced motion is preferred
        // (CSS already handles opacity/transform, this removes the delay logic)
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.querySelectorAll('[data-animate]').forEach(function (el) {
                el.classList.add('animated');
            });
            return;
        }

        if (!('IntersectionObserver' in window)) {
            // Fallback: show everything
            document.querySelectorAll('[data-animate]').forEach(function (el) {
                el.classList.add('animated');
            });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var el    = entry.target;
                    var delay = el.getAttribute('data-delay') || 0;
                    setTimeout(function () {
                        el.classList.add('animated');
                    }, parseInt(delay, 10));
                    observer.unobserve(el);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -40px 0px'
        });

        document.querySelectorAll('[data-animate]').forEach(function (el) {
            observer.observe(el);
        });
    }

    /* ============================================================
       5. ANIMATED COUNTERS
       ============================================================ */
    function initCounters() {
        var counters = document.querySelectorAll('.fs-counter');
        if (!counters.length) return;

        function animateCounter(el) {
            var target  = parseInt(el.getAttribute('data-target'), 10) || 0;
            var suffix  = el.getAttribute('data-suffix') || '';
            var duration = 2200;
            var start    = performance.now();

            function easeOutExpo(t) {
                return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
            }

            function update(now) {
                var elapsed  = now - start;
                var progress = Math.min(elapsed / duration, 1);
                var current  = Math.floor(easeOutExpo(progress) * target);

                // Format number with commas
                el.textContent = formatNumber(current) + suffix;

                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    el.textContent = formatNumber(target) + suffix;
                }
            }

            requestAnimationFrame(update);
        }

        function formatNumber(n) {
            return n.toLocaleString('en-US');
        }

        if (!('IntersectionObserver' in window)) {
            counters.forEach(animateCounter);
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(function (el) { observer.observe(el); });
    }

    /* ============================================================
       6. PARTICLE CANVAS — Hero Background (Advanced)
       ============================================================ */
    function initParticles() {
        var canvas = document.getElementById('fs-particles');
        if (!canvas) return;

        // Skip particles on small screens (canvas is hidden anyway at ≤640px)
        // and skip when the user has requested reduced motion.
        var prefersReducedMotion = window.matchMedia &&
            window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReducedMotion || window.innerWidth < 768) {
            canvas.style.display = 'none';
            return;
        }

        var ctx       = canvas.getContext('2d');
        var particles = [];
        var mouse     = { x: null, y: null, radius: 180 };
        var animId;
        var dpr       = Math.min(window.devicePixelRatio || 1, 2);

        function resize() {
            var w = canvas.parentElement.offsetWidth;
            var h = canvas.parentElement.offsetHeight;
            canvas.width  = w * dpr;
            canvas.height = h * dpr;
            canvas.style.width  = w + 'px';
            canvas.style.height = h + 'px';
            ctx.scale(dpr, dpr);
            buildParticles(w, h);
        }

        function buildParticles(w, h) {
            particles = [];
            var count = Math.min(70, Math.floor((w * h) / 14000));
            var colors = ['0,200,150', '59,130,246', '129,140,248', '0,229,176'];
            for (var i = 0; i < count; i++) {
                particles.push({
                    x:      Math.random() * w,
                    y:      Math.random() * h,
                    vx:     (Math.random() - 0.5) * 0.15,
                    vy:     (Math.random() - 0.5) * 0.15,
                    radius: Math.random() * 1.8 + 0.4,
                    alpha:  Math.random() * 0.4 + 0.08,
                    color:  colors[Math.floor(Math.random() * colors.length)],
                    w: w, h: h
                });
            }
        }

        resize();
        window.addEventListener('resize', debounce(resize, 300));

        var heroSection = document.querySelector('.fs-hero');
        if (heroSection) {
            heroSection.addEventListener('mousemove', function (e) {
                var rect = canvas.getBoundingClientRect();
                mouse.x = e.clientX - rect.left;
                mouse.y = e.clientY - rect.top;
            });
            heroSection.addEventListener('mouseleave', function () {
                mouse.x = null;
                mouse.y = null;
            });
        }

        function drawParticle(p) {
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(' + p.color + ',' + p.alpha + ')';
            ctx.fill();
        }

        function drawLine(x1, y1, x2, y2, alpha, color) {
            ctx.beginPath();
            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
            ctx.strokeStyle = 'rgba(' + color + ',' + alpha + ')';
            ctx.lineWidth = 0.5;
            ctx.stroke();
        }

        var W = canvas.parentElement.offsetWidth;
        var H = canvas.parentElement.offsetHeight;

        function animate() {
            W = canvas.parentElement.offsetWidth;
            H = canvas.parentElement.offsetHeight;
            ctx.clearRect(0, 0, W, H);

            for (var a = 0; a < particles.length; a++) {
                var p = particles[a];

                // Mouse repulsion
                if (mouse.x !== null) {
                    var mdx   = p.x - mouse.x;
                    var mdy   = p.y - mouse.y;
                    var mdist = Math.sqrt(mdx * mdx + mdy * mdy);
                    if (mdist < mouse.radius) {
                        var force = (mouse.radius - mdist) / mouse.radius;
                        p.vx += (mdx / mdist) * force * 0.08;
                        p.vy += (mdy / mdist) * force * 0.08;
                    }
                }

                // Velocity damping
                p.vx *= 0.98;
                p.vy *= 0.98;

                p.x += p.vx;
                p.y += p.vy;

                // Wrap
                if (p.x < -5)     p.x = W + 5;
                if (p.x > W + 5)  p.x = -5;
                if (p.y < -5)     p.y = H + 5;
                if (p.y > H + 5)  p.y = -5;

                drawParticle(p);

                // Connections
                for (var b = a + 1; b < particles.length; b++) {
                    var q   = particles[b];
                    var dx  = p.x - q.x;
                    var dy  = p.y - q.y;
                    var d   = Math.sqrt(dx * dx + dy * dy);
                    if (d < 110) {
                        var lineAlpha = 0.06 * (1 - d / 110);
                        drawLine(p.x, p.y, q.x, q.y, lineAlpha, p.color);
                    }
                }

                // Mouse connection
                if (mouse.x !== null) {
                    var mdx2  = p.x - mouse.x;
                    var mdy2  = p.y - mouse.y;
                    var md2   = Math.sqrt(mdx2 * mdx2 + mdy2 * mdy2);
                    if (md2 < 160) {
                        drawLine(p.x, p.y, mouse.x, mouse.y, 0.18 * (1 - md2 / 160), '0,200,150');
                    }
                }
            }

            animId = requestAnimationFrame(animate);
        }
        animate();

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) cancelAnimationFrame(animId);
            else animate();
        });
    }

    /* ============================================================
       6b. HERO CARD — Live Slider Animation (RAF-based, ~15fps throttle)
       ============================================================ */
    function initHeroCardAnimation() {
        var resultEl = document.getElementById('fs-hero-result');
        if (!resultEl) return;

        var fills = document.querySelectorAll('.fs-hero__card .fs-calc-slider-fill');
        if (!fills.length) return;

        // Skip if user prefers reduced motion
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        var tick = 0;
        var lastTime = 0;
        var INTERVAL = 80; // ms between updates (~12fps)

        function loop(now) {
            if (now - lastTime >= INTERVAL) {
                lastTime = now;
                tick++;
                var wave = 75 + Math.sin(tick * 0.08) * 10;
                if (fills[0]) fills[0].style.width = wave.toFixed(1) + '%';

                var price     = 350000 + (wave / 100) * 200000;
                var principal = price * 0.80;
                var rate      = 0.068 / 12;
                var n         = 360;
                var payment   = principal * (rate * Math.pow(1 + rate, n)) / (Math.pow(1 + rate, n) - 1);
                resultEl.textContent = '$' + Math.round(payment).toLocaleString('en-US');
            }
            heroAnimId = requestAnimationFrame(loop);
        }
        var heroAnimId = requestAnimationFrame(loop);
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                cancelAnimationFrame(heroAnimId);
            } else {
                heroAnimId = requestAnimationFrame(loop);
            }
        });
    }

    /* ============================================================
       7. CATEGORY FILTER TABS
       ============================================================ */
    function initCategoryFilter() {
        var tabs = document.querySelectorAll('.fs-filter-tab');
        var grid = document.getElementById('fs-categories-grid');
        if (!tabs.length || !grid) return;

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.forEach(function (t) {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                });
                tab.classList.add('active');
                tab.setAttribute('aria-selected', 'true');

                var filter = tab.getAttribute('data-filter');
                var cards  = grid.querySelectorAll('.fs-cat-card');

                cards.forEach(function (card, i) {
                    var show = filter === 'all' || card.getAttribute('data-category') === filter;

                    if (show) {
                        card.style.display = '';
                        setTimeout(function () {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, i * 60);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        setTimeout(function () {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });
    }

    /* ============================================================
       8. TESTIMONIALS CAROUSEL
       ============================================================ */
    function initTestimonialsCarousel() {
        var track    = document.getElementById('fs-testimonials-track');
        var prevBtn  = document.getElementById('fs-carousel-prev');
        var nextBtn  = document.getElementById('fs-carousel-next');
        var dotsWrap = document.getElementById('fs-carousel-dots');
        if (!track) return;

        var cards    = Array.from(track.querySelectorAll('.fs-testimonial-card'));
        var current  = 0;
        var perPage  = window.innerWidth < 640 ? 1 : 2;
        var total    = Math.ceil(cards.length / perPage);
        var autoTimer;

        // Build dots
        function buildDots() {
            if (!dotsWrap) return;
            dotsWrap.innerHTML = '';
            for (var i = 0; i < total; i++) {
                var dot = document.createElement('button');
                dot.className = 'fs-carousel-dot' + (i === current ? ' active' : '');
                dot.setAttribute('aria-label', 'Testimonial group ' + (i + 1));
                dot.setAttribute('role', 'tab');
                dot.dataset.index = i;
                dot.addEventListener('click', function () {
                    goTo(parseInt(this.dataset.index));
                });
                dotsWrap.appendChild(dot);
            }
        }

        function goTo(index) {
            current = (index + total) % total;
            var offset = current * 100;
            // Use CSS grid gap-aware offset
            cards.forEach(function (card, i) {
                var group = Math.floor(i / perPage);
                if (group === current) {
                    card.style.display = '';
                    setTimeout(function () {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, (i % perPage) * 80);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(function () {
                        card.style.display = 'none';
                    }, 300);
                }
            });

            // Update dots
            if (dotsWrap) {
                dotsWrap.querySelectorAll('.fs-carousel-dot').forEach(function (d, i) {
                    d.classList.toggle('active', i === current);
                });
            }
        }

        // Initial setup
        buildDots();
        // Show first group
        if (total > 1) goTo(0);

        if (prevBtn) prevBtn.addEventListener('click', function () { goTo(current - 1); resetAuto(); });
        if (nextBtn) nextBtn.addEventListener('click', function () { goTo(current + 1); resetAuto(); });

        function startAuto() {
            autoTimer = setInterval(function () { goTo(current + 1); }, 5000);
        }
        function resetAuto() {
            clearInterval(autoTimer);
            startAuto();
        }
        if (total > 1) startAuto();

        // Recalculate on resize
        window.addEventListener('resize', debounce(function () {
            perPage = window.innerWidth < 640 ? 1 : 2;
            total   = Math.ceil(cards.length / perPage);
            current = 0;
            buildDots();
            cards.forEach(function (card) {
                card.style.display = '';
                card.style.opacity = '1';
                card.style.transform = '';
            });
            clearInterval(autoTimer);
            startAuto();
        }, 300));
    }

    /* ============================================================
       9. READING PROGRESS BAR
       ============================================================ */
    function initProgressBar() {
        var bar = document.getElementById('fs-progress-bar');
        if (!bar) return;

        window.addEventListener('scroll', function () {
            var docH    = document.documentElement.scrollHeight - window.innerHeight;
            var scrolled = docH > 0 ? (window.scrollY / docH) * 100 : 0;
            bar.style.width = Math.min(scrolled, 100) + '%';
        }, { passive: true });
    }

    /* ============================================================
       10. BACK TO TOP
       ============================================================ */
    function initBackToTop() {
        var btn = document.getElementById('fs-back-to-top');
        if (!btn) return;

        window.addEventListener('scroll', function () {
            btn.classList.toggle('visible', window.scrollY > 400);
        }, { passive: true });

        btn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ============================================================
       11. SMOOTH SCROLL for anchor links
       ============================================================ */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                var targetId = link.getAttribute('href').slice(1);
                if (!targetId) return;
                var target = document.getElementById(targetId);
                if (!target) return;
                e.preventDefault();
                var headerH = document.getElementById('fs-header')
                    ? document.getElementById('fs-header').offsetHeight
                    : 72;
                var top = target.getBoundingClientRect().top + window.scrollY - headerH - 16;
                window.scrollTo({ top: top, behavior: 'smooth' });
            });
        });
    }

    /* ============================================================
       12. TOOL CARD SAVE / BOOKMARK (local storage)
       ============================================================ */
    function initToolCardSave() {
        var saved = JSON.parse(localStorage.getItem('fs_saved_tools') || '[]');

        document.querySelectorAll('.fs-tool-card__save').forEach(function (btn) {
            var card = btn.closest('.fs-tool-card');
            var name = card ? (card.querySelector('.fs-tool-card__name') || {}).textContent || '' : '';

            function updateState() {
                var isSaved = saved.includes(name);
                btn.style.color = isSaved ? 'var(--fs-primary)' : '';
                btn.style.borderColor = isSaved ? 'var(--fs-primary)' : '';
                btn.setAttribute('aria-pressed', isSaved ? 'true' : 'false');
            }

            updateState();

            btn.addEventListener('click', function () {
                var idx = saved.indexOf(name);
                if (idx === -1) {
                    saved.push(name);
                } else {
                    saved.splice(idx, 1);
                }
                localStorage.setItem('fs_saved_tools', JSON.stringify(saved));
                updateState();

                // Micro-animation
                btn.style.transform = 'scale(1.3)';
                setTimeout(function () { btn.style.transform = ''; }, 250);
            });
        });
    }

    /* ============================================================
       13. AI STREAM TEXT ANIMATION (re-loop)
       ============================================================ */
    function initAIStreamAnimation() {
        var lines = document.querySelectorAll('.fs-ai-stream-line');
        if (!lines.length) return;
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        var idx = 0;
        var texts = [
            'Analyzing your mortgage parameters...',
            'Comparing 847 similar loan profiles...',
            'Checking current market rates...',
            'Generating personalized insights...',
            'Evaluating refinance opportunities...',
            'Running Monte Carlo simulation...',
            'Calculating optimal payment schedule...',
        ];

        function cycleLines() {
            lines.forEach(function (line, i) {
                setTimeout(function () {
                    line.textContent = texts[(idx + i) % texts.length];
                    line.style.animation = 'none';
                    // Force reflow
                    void line.offsetWidth;
                    line.style.animation = '';
                }, i * 800);
            });
            idx = (idx + lines.length) % texts.length;
        }

        setInterval(cycleLines, lines.length * 800 + 3000);
    }

    /* ============================================================
       14. CAROUSEL DOTS INIT (called in initTestimonialsCarousel)
       ============================================================ */
    function initCarouselDots() {
        // Handled inside initTestimonialsCarousel
    }

    /* ============================================================
       15. LIVE TICKER — price simulation & flash
       ============================================================ */
    function initTicker() {
        var track = document.getElementById('fs-ticker-track');
        if (!track) return;

        // Simulated live prices
        var marketData = {
            'S&P 500':  { price: 5284.92,  vol: 8,    fmt: function(v){ return v.toFixed(2); } },
            'NASDAQ':   { price: 16742.39, vol: 15,   fmt: function(v){ return v.toFixed(2); } },
            'DOW':      { price: 41503.10, vol: 20,   fmt: function(v){ return v.toFixed(2); } },
            'BTC/USD':  { price: 103240,   vol: 800,  fmt: function(v){ return '$' + Math.round(v).toLocaleString('en-US'); } },
            'ETH/USD':  { price: 2481,     vol: 40,   fmt: function(v){ return '$' + Math.round(v).toLocaleString('en-US'); } },
            'GOLD':     { price: 3204,     vol: 12,   fmt: function(v){ return '$' + v.toFixed(2); } },
            '10Y BOND': { price: 4.32,     vol: .03,  fmt: function(v){ return v.toFixed(2) + '%'; } },
            'OIL/WTI':  { price: 78.42,    vol: .80,  fmt: function(v){ return '$' + v.toFixed(2); } },
            'EUR/USD':  { price: 1.0821,   vol: .003, fmt: function(v){ return v.toFixed(4); } },
            'GBP/USD':  { price: 1.2654,   vol: .004, fmt: function(v){ return v.toFixed(4); } },
            'JPY/USD':  { price: 0.00643,  vol: .0001,fmt: function(v){ return v.toFixed(5); } },
            'SILVER':   { price: 32.18,    vol: .25,  fmt: function(v){ return '$' + v.toFixed(2); } },
        };

        function updatePrices() {
            var priceEls = track.querySelectorAll('.fs-ticker__price');
            priceEls.forEach(function(el) {
                var sym = el.getAttribute('data-symbol');
                if (!sym || !marketData[sym]) return;

                var d = marketData[sym];
                // Random walk
                var delta = (Math.random() - 0.50) * d.vol * 0.4;
                d.price   = Math.max(0.0001, d.price + delta);

                var newText = d.fmt(d.price);
                if (el.textContent.trim() !== newText) {
                    var wasUp = delta > 0;
                    el.textContent = newText;
                    el.classList.remove('flash-up','flash-down');
                    void el.offsetWidth; // reflow
                    el.classList.add(wasUp ? 'flash-up' : 'flash-down');
                    setTimeout(function(){ el.classList.remove('flash-up','flash-down'); }, 600);
                }
            });
        }

        // Staggered updates
        setInterval(updatePrices, 2500);
    }

    /* ============================================================
       15b. PORTFOLIO CHART — Canvas sparkline
       ============================================================ */
    function initPortfolioChart() {
        var canvas = document.getElementById('fs-portfolio-chart');
        if (!canvas) return;

        var ctx = canvas.getContext('2d');
        var dpr = Math.min(window.devicePixelRatio || 1, 2);

        // Generate initial data — upward trending with noise
        var points  = [];
        var baseVal = 180000;
        for (var i = 0; i < 60; i++) {
            baseVal += (Math.random() - 0.35) * 3000;
            baseVal  = Math.max(150000, baseVal);
            points.push(baseVal);
        }
        // End near 248420
        points[points.length - 1] = 248420;

        var animFrame = 0;
        var animTotal = 90; // frames to draw in
        var drawn     = false;
        var animId;

        function getDisplayW() { return canvas.parentElement.offsetWidth; }
        function getDisplayH() { return canvas.parentElement.offsetHeight || 140; }

        function setupCanvas() {
            var W = getDisplayW();
            var H = getDisplayH();
            canvas.width  = W * dpr;
            canvas.height = H * dpr;
            canvas.style.width  = W + 'px';
            canvas.style.height = H + 'px';
            ctx.scale(dpr, dpr);
        }
        setupCanvas();

        function drawChart(progress) {
            var W = getDisplayW();
            var H = getDisplayH();
            ctx.clearRect(0, 0, W, H);

            var visibleCount = Math.max(2, Math.floor(progress * points.length));
            var visPoints    = points.slice(0, visibleCount);

            var minVal = Math.min.apply(null, visPoints) * 0.96;
            var maxVal = Math.max.apply(null, visPoints) * 1.01;
            var range  = maxVal - minVal || 1;

            var padX = 0, padY = 10;
            var chartW = W - padX * 2;
            var chartH = H - padY * 2;

            function mapX(i) { return padX + (i / (points.length - 1)) * chartW; }
            function mapY(v) { return padY + chartH - ((v - minVal) / range) * chartH; }

            // Gradient fill
            var grad = ctx.createLinearGradient(0, padY, 0, H);
            grad.addColorStop(0, 'rgba(59,130,246,.28)');
            grad.addColorStop(0.6,'rgba(59,130,246,.06)');
            grad.addColorStop(1, 'rgba(59,130,246,0)');

            // Draw area
            ctx.beginPath();
            ctx.moveTo(mapX(0), mapY(visPoints[0]));
            for (var i = 1; i < visPoints.length; i++) {
                var cp1x = mapX(i - 0.5);
                var cp1y = mapY(visPoints[i - 1]);
                var cp2x = mapX(i - 0.5);
                var cp2y = mapY(visPoints[i]);
                ctx.bezierCurveTo(cp1x, cp1y, cp2x, cp2y, mapX(i), mapY(visPoints[i]));
            }
            ctx.lineTo(mapX(visPoints.length - 1), H);
            ctx.lineTo(mapX(0), H);
            ctx.closePath();
            ctx.fillStyle = grad;
            ctx.fill();

            // Draw line
            ctx.beginPath();
            ctx.moveTo(mapX(0), mapY(visPoints[0]));
            for (var j = 1; j < visPoints.length; j++) {
                var c1x = mapX(j - 0.5);
                var c1y = mapY(visPoints[j - 1]);
                var c2x = mapX(j - 0.5);
                var c2y = mapY(visPoints[j]);
                ctx.bezierCurveTo(c1x, c1y, c2x, c2y, mapX(j), mapY(visPoints[j]));
            }
            ctx.strokeStyle = '#3B82F6';
            ctx.lineWidth   = 2;
            ctx.shadowColor = 'rgba(59,130,246,.60)';
            ctx.shadowBlur  = 8;
            ctx.stroke();
            ctx.shadowBlur  = 0;

            // Endpoint dot
            var lastX = mapX(visPoints.length - 1);
            var lastY = mapY(visPoints[visPoints.length - 1]);
            ctx.beginPath();
            ctx.arc(lastX, lastY, 4, 0, Math.PI * 2);
            ctx.fillStyle = '#60A5FA';
            ctx.shadowColor = 'rgba(96,165,250,.80)';
            ctx.shadowBlur  = 12;
            ctx.fill();
            ctx.shadowBlur  = 0;
        }

        // Animate draw-in
        function animateIn() {
            animFrame++;
            var progress = Math.min(animFrame / animTotal, 1);
            var ease     = 1 - Math.pow(1 - progress, 3);
            drawChart(ease);
            if (progress < 1) {
                animId = requestAnimationFrame(animateIn);
            } else {
                drawn = true;
                startLiveUpdates();
            }
        }

        // Intersection trigger
        if ('IntersectionObserver' in window) {
            var obs = new IntersectionObserver(function(entries) {
                if (entries[0].isIntersecting) {
                    animateIn();
                    obs.disconnect();
                }
            }, { threshold: .3 });
            obs.observe(canvas);
        } else {
            animateIn();
        }

        // Live update — shift & push new point
        function startLiveUpdates() {
            setInterval(function() {
                var last  = points[points.length - 1];
                var delta = (Math.random() - 0.42) * 2000;
                var next  = Math.max(200000, last + delta);
                points.push(next);
                points.shift();

                // Update displayed total
                var totalEl = document.getElementById('fs-portfolio-total');
                if (totalEl) {
                    totalEl.textContent = Math.round(next).toLocaleString('en-US');
                }
                drawChart(1);
            }, 3000);
        }

        window.addEventListener('resize', debounce(function() {
            setupCanvas();
            if (drawn) drawChart(1);
        }, 300));
    }

    /* ============================================================
       15c. MINI SPARKLINE CHARTS (BTC card etc.)
       ============================================================ */
    function initMiniCharts() {
        var configs = [
            { id: 'fs-mini-chart-1', color: '#00C896', fill: 'rgba(0,200,150,.15)', up: true },
        ];
        configs.forEach(function(cfg) {
            var c = document.getElementById(cfg.id);
            if (!c) return;
            var ctx = c.getContext('2d');
            var dpr = Math.min(window.devicePixelRatio || 1, 2);
            c.width  = c.offsetWidth  * dpr || 120 * dpr;
            c.height = c.offsetHeight * dpr || 36 * dpr;
            ctx.scale(dpr, dpr);

            var W = c.offsetWidth  || 120;
            var H = c.offsetHeight || 36;

            // Generate data
            var data = [];
            var v    = 100000 + Math.random() * 3000;
            for (var i = 0; i < 20; i++) {
                v += (Math.random() - (cfg.up ? 0.38 : 0.62)) * 2000;
                data.push(v);
            }

            function draw(d) {
                ctx.clearRect(0, 0, W, H);
                var mn = Math.min.apply(null,d) *.99;
                var mx = Math.max.apply(null,d) *1.01;
                var r  = mx - mn || 1;
                var fx = function(i){ return (i/(d.length-1))*W; };
                var fy = function(v){ return H - ((v-mn)/r)*(H-4) - 2; };

                var g = ctx.createLinearGradient(0,0,0,H);
                g.addColorStop(0, cfg.fill);
                g.addColorStop(1,'rgba(0,0,0,0)');
                ctx.beginPath();
                ctx.moveTo(fx(0),fy(d[0]));
                for(var i=1;i<d.length;i++) ctx.lineTo(fx(i),fy(d[i]));
                ctx.lineTo(fx(d.length-1),H); ctx.lineTo(fx(0),H);
                ctx.closePath();
                ctx.fillStyle = g; ctx.fill();

                ctx.beginPath();
                ctx.moveTo(fx(0),fy(d[0]));
                for(var j=1;j<d.length;j++) ctx.lineTo(fx(j),fy(d[j]));
                ctx.strokeStyle = cfg.color;
                ctx.lineWidth   = 1.5;
                ctx.stroke();
            }
            draw(data);

            // Live
            setInterval(function(){
                var last = data[data.length-1];
                data.push(Math.max(50000, last + (Math.random()-.38)*2000));
                data.shift();
                draw(data);

                var priceEl = document.getElementById('fs-btc-price');
                if (priceEl) priceEl.textContent = '$' + Math.round(data[data.length-1]).toLocaleString('en-US');
            }, 2000);
        });
    }

    /* ============================================================
       15d. AI INSIGHT TEXT ROTATOR
       ============================================================ */
    function initAIInsightRotator() {
        var el = document.getElementById('fs-ai-insight-text');
        if (!el) return;
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        var insights = [
            'AI Insight: Your portfolio is outperforming the S&P 500 by 6.3% this month.',
            'AI Alert: Consider rebalancing — tech sector now 42% of your portfolio.',
            'AI Suggestion: Current market conditions favor defensive stocks.',
            'AI Forecast: Based on your risk profile, 70/30 allocation is optimal now.',
            'AI Tip: Dollar-cost averaging into BTC could reduce volatility exposure.',
        ];
        var idx = 0;

        setInterval(function() {
            idx = (idx + 1) % insights.length;
            el.style.opacity = '0';
            el.style.transform = 'translateY(4px)';
            setTimeout(function() {
                el.textContent = insights[idx];
                el.style.opacity   = '1';
                el.style.transform = 'translateY(0)';
            }, 350);
        }, 5000);

        el.style.transition = 'opacity .35s ease, transform .35s ease';
    }

    /* ============================================================
       15e. SEARCH OVERLAY
       ============================================================ */
    function initSearch() {
        var searchBtn     = document.getElementById('fs-search-btn');
        var searchOverlay = document.getElementById('fs-search-overlay');
        var searchClose   = document.getElementById('fs-search-close');
        var searchInput   = document.getElementById('fs-search-input');
        if (!searchBtn || !searchOverlay) return;

        function openSearch() {
            searchOverlay.classList.add('open');
            searchOverlay.setAttribute('aria-hidden', 'false');
            searchBtn.setAttribute('aria-expanded','true');
            if (searchInput) setTimeout(function(){ searchInput.focus(); }, 100);
        }
        function closeSearch() {
            searchOverlay.classList.remove('open');
            searchOverlay.setAttribute('aria-hidden', 'true');
            searchBtn.setAttribute('aria-expanded','false');
        }
        searchBtn.addEventListener('click', openSearch);
        if (searchClose) searchClose.addEventListener('click', closeSearch);
        document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeSearch(); });
    }

    /* ============================================================
       15. UTILITIES
       ============================================================ */
    function debounce(fn, wait) {
        var timer;
        return function () {
            clearTimeout(timer);
            timer = setTimeout(fn, wait);
        };
    }

    /* ============================================================
       16. HERO CTA — Ripple Effect
       ============================================================ */
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.fs-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                var rect   = btn.getBoundingClientRect();
                var ripple = document.createElement('span');
                var size   = Math.max(rect.width, rect.height);
                ripple.style.cssText = [
                    'position:absolute',
                    'border-radius:50%',
                    'background:rgba(255,255,255,.25)',
                    'pointer-events:none',
                    'width:' + size + 'px',
                    'height:' + size + 'px',
                    'left:' + (e.clientX - rect.left - size / 2) + 'px',
                    'top:' + (e.clientY - rect.top - size / 2) + 'px',
                    'transform:scale(0)',
                    'transition:transform .5s ease, opacity .5s ease',
                    'opacity:1',
                ].join(';');

                btn.style.position = 'relative';
                btn.style.overflow = 'hidden';
                btn.appendChild(ripple);

                requestAnimationFrame(function () {
                    ripple.style.transform = 'scale(2.5)';
                    ripple.style.opacity   = '0';
                });

                setTimeout(function () { ripple.remove(); }, 600);
            });
        });
    });

    /* ============================================================
       17. NAVBAR ACTIVE STATE on scroll
       ============================================================ */
    document.addEventListener('DOMContentLoaded', function () {
        var sections = document.querySelectorAll('section[id]');
        var navLinks = document.querySelectorAll('.fs-nav-list a[href^="#"]');
        if (!sections.length || !navLinks.length) return;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var id = '#' + entry.target.id;
                    navLinks.forEach(function (link) {
                        link.parentElement.classList.toggle(
                            'current-menu-item',
                            link.getAttribute('href') === id
                        );
                    });
                }
            });
        }, { threshold: 0.35, rootMargin: '-80px 0px -60% 0px' });

        sections.forEach(function (s) { observer.observe(s); });
    });

})();
