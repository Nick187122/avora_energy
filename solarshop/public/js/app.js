(function () {
    'use strict';

    // Hero slider
    document.querySelectorAll('.hero-slides').forEach(function (wrap) {
        var slides = wrap.querySelectorAll('.hero-slide');
        var dotsWrap = wrap.querySelector('.hero-dots');
        if (slides.length <= 1) return;

        var current = 0;
        var timer = null;

        slides.forEach(function (_, i) {
            var b = document.createElement('button');
            b.type = 'button';
            b.setAttribute('aria-label', 'Go to slide ' + (i + 1));
            if (i === 0) b.classList.add('active');
            b.addEventListener('click', function () {
                go(i);
                restart();
            });
            dotsWrap.appendChild(b);
        });

        var dots = dotsWrap.querySelectorAll('button');

        function go(i) {
            var index = (i + slides.length) % slides.length;
            slides[current].classList.remove('active');
            dots[current].classList.remove('active');
            current = index;
            slides[current].classList.add('active');
            dots[current].classList.add('active');
        }

        function restart() {
            if (timer) clearInterval(timer);
            timer = setInterval(function () { go(current + 1); }, 7000);
        }

        slides[current].classList.add('active');
        restart();
    });

    // Gallery thumbs + main image
    document.querySelectorAll('.gallery-wrap').forEach(function (wrap) {
        var main = wrap.querySelector('.gallery-main img');
        var thumbs = wrap.querySelectorAll('.gallery-thumbs button');
        thumbs.forEach(function (btn) {
            btn.addEventListener('click', function () {
                main.src = btn.getAttribute('data-src');
                thumbs.forEach(function (t) { t.classList.remove('active'); });
                btn.classList.add('active');
            });
        });
    });

    // Tabs
    document.querySelectorAll('.spec-tabs').forEach(function (wrap) {
        var btns = wrap.querySelectorAll('.tabs button');
        var panes = wrap.querySelectorAll('.tab-pane');
        btns.forEach(function (btn, i) {
            btn.addEventListener('click', function () {
                btns.forEach(function (b) { b.classList.remove('active'); });
                panes.forEach(function (p) { p.classList.remove('active'); });
                btn.classList.add('active');
                if (panes[i]) panes[i].classList.add('active');
            });
        });
    });

    // Quantity steppers
    document.querySelectorAll('.qty-box, .cart-qty').forEach(function (box) {
        var minus = box.querySelector('[data-dir="-"]');
        var plus = box.querySelector('[data-dir="+"]');
        var input = box.querySelector('input');
        if (!input) return;

        function apply(form, qty) {
            if (form) form.submit();
        }

        if (minus) minus.addEventListener('click', function () {
            var val = parseInt(input.value, 10) || 1;
            if (val > 1) input.value = val - 1;
            var form = box.closest('.qty-form');
            if (form && form.dataset.auto === '1') apply(form, input.value);
        });
        if (plus) plus.addEventListener('click', function () {
            var val = parseInt(input.value, 10) || 1;
            input.value = val + 1;
            var form = box.closest('.qty-form');
            if (form && form.dataset.auto === '1') apply(form, input.value);
        });
    });

    // Payment option selection
    document.querySelectorAll('.pay-option input').forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.pay-option').forEach(function (o) { o.classList.remove('selected'); });
            radio.closest('.pay-option').classList.add('selected');
        });
    });

    // Auto-dismiss flash messages
    document.querySelectorAll('.alert').forEach(function (alert) {
        setTimeout(function () {
            if (alert && alert.parentNode) {
                alert.style.transition = 'opacity .4s, transform .4s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-8px)';
                setTimeout(function () { if (alert.parentNode) alert.remove(); }, 400);
            }
        }, 5000);
    });

    // Mobile drawer + scrolled compact header + back-to-top
(function () {
    'use strict';

    var toggle = document.querySelector('.menu-toggle');
    var menu = document.getElementById('mobile-menu');
    var overlay = document.getElementById('menu-overlay');
    var closeBtn = menu ? menu.querySelector('.m-close') : null;
    var header = document.querySelector('.site-header');
    var toTop = document.getElementById('back-to-top');

    function openMenu() {
        if (!menu) return;
        menu.classList.add('open');
        menu.setAttribute('aria-hidden', 'false');
        if (toggle) { toggle.classList.add('open'); toggle.setAttribute('aria-expanded', 'true'); }
        if (overlay) overlay.classList.add('open');
        document.body.classList.add('no-scroll');
    }

    function closeMenu() {
        if (!menu) return;
        menu.classList.remove('open');
        menu.setAttribute('aria-hidden', 'true');
        if (toggle) { toggle.classList.remove('open'); toggle.setAttribute('aria-expanded', 'false'); }
        if (overlay) overlay.classList.remove('open');
        document.body.classList.remove('no-scroll');
    }

    if (toggle) toggle.addEventListener('click', function () {
        if (menu.classList.contains('open')) closeMenu(); else openMenu();
    });
    if (closeBtn) closeBtn.addEventListener('click', closeMenu);
    if (overlay) overlay.addEventListener('click', closeMenu);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && menu && menu.classList.contains('open')) closeMenu();
    });

    if (menu) menu.querySelectorAll('.m-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var sub = btn.nextElementSibling;
            if (!sub) return;
            var isOpen = sub.classList.toggle('open');
            btn.classList.toggle('open', isOpen);
            btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    });

    var ticking = false;
    function onScroll() {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(function () {
            var y = window.pageYOffset || document.documentElement.scrollTop;
            if (header) header.classList.toggle('scrolled', y > 60);
            if (toTop) toTop.classList.toggle('show', y > 400);
            ticking = false;
        });
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    if (toTop) toTop.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Touch devices: tapping a parent item opens its dropdown
    if (window.matchMedia('(hover: none)').matches) {
        document.querySelectorAll('.main-nav li.has-sub > a').forEach(function (a) {
            a.addEventListener('click', function (e) {
                var li = a.parentElement;
                if (!li.classList.contains('open')) {
                    e.preventDefault();
                    e.stopPropagation();
                    li.classList.add('open');
                }
            }, true);
        });
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.main-nav li.has-sub')) {
                document.querySelectorAll('.main-nav li.open').forEach(function (li) { li.classList.remove('open'); });
            }
        });
    }
})();

// Mobile bottom bar: highlight current section
(function () {
    'use strict';

    var path = window.location.pathname;
    document.querySelectorAll('.mobile-bottom a').forEach(function (a) {
        var url = new URL(a.getAttribute('href'), window.location.origin).pathname;
        if (url === path) {
            a.classList.add('active');
        } else if (url !== '/' && path.indexOf(url) === 0) {
            a.classList.add('active');
        }
    });

    // Highlight current category / brand in the primary nav
    document.querySelectorAll('.main-nav li.has-sub > a').forEach(function (a) {
        if (new URL(a.getAttribute('href'), window.location.origin).pathname === path) {
            a.parentElement.classList.add('active-cat');
        }
    });
})();

// Scroll-reveal
(function () {
    'use strict';

    var els = document.querySelectorAll('.reveal');
    if (!els.length || !('IntersectionObserver' in window)) {
        els.forEach(function (el) { el.classList.add('visible'); });
        return;
    }
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });
    els.forEach(function (el) { io.observe(el); });
})();

// Contact menu (Call / WhatsApp / Email)
(function () {
    'use strict';

    var menu = document.querySelector('[data-contact-menu]');
    var trigger = menu ? menu.querySelector('.contact-trigger') : null;
    if (!menu || !trigger) return;

    function setOpen(open) {
        menu.classList.toggle('open', open);
        trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        setOpen(!menu.classList.contains('open'));
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('[data-contact-menu]')) setOpen(false);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') setOpen(false);
    });

    setTimeout(function () { setOpen(false); }, 50);
})();

// Close the outer closure opened at the top of this file.
})();