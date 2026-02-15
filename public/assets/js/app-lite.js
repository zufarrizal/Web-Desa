(function () {
    function appendLinkToken() {
        var meta = document.querySelector('meta[name="app-link-token"]');
        if (!meta) {
            return;
        }

        var token = (meta.getAttribute('content') || '').trim();
        if (!token) {
            return;
        }

        var anchors = document.querySelectorAll('a[href]');
        anchors.forEach(function (anchor) {
            var href = anchor.getAttribute('href');
            if (!href) {
                return;
            }

            if (
                href.charAt(0) === '#' ||
                href.indexOf('javascript:') === 0 ||
                href.indexOf('mailto:') === 0 ||
                href.indexOf('tel:') === 0 ||
                anchor.hasAttribute('download') ||
                anchor.hasAttribute('data-no-token')
            ) {
                return;
            }

            try {
                var url = new URL(href, window.location.href);
                if (url.origin !== window.location.origin) {
                    return;
                }
                if (!url.searchParams.has('_lt')) {
                    url.searchParams.set('_lt', token);
                }
                anchor.setAttribute('href', url.pathname + url.search + url.hash);
            } catch (e) {
                // Ignore malformed URLs.
            }
        });
    }

    function runConfirm(message, onYes) {
        if (window.Swal && typeof window.Swal.fire === 'function') {
            window.Swal.fire({
                title: 'Konfirmasi',
                text: message || 'Lanjutkan aksi ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjut',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(function (result) {
                if (result.isConfirmed) {
                    onYes();
                }
            });
            return;
        }

        if (window.confirm(message || 'Lanjutkan aksi ini?')) {
            onYes();
        }
    }

    function initJsNavigation() {
        var anchors = document.querySelectorAll('a[href]');
        if (!anchors.length) {
            return;
        }

        anchors.forEach(function (anchor) {
            var href = anchor.getAttribute('href');
            if (!href) {
                return;
            }

            var target = (anchor.getAttribute('target') || '').toLowerCase();
            if (
                href.charAt(0) === '#' ||
                href.indexOf('javascript:') === 0 ||
                href.indexOf('mailto:') === 0 ||
                href.indexOf('tel:') === 0 ||
                anchor.hasAttribute('download') ||
                anchor.hasAttribute('data-confirm') ||
                anchor.hasAttribute('data-no-js-nav')
            ) {
                return;
            }

            var isExternal = false;
            if (/^https?:\/\//i.test(href)) {
                try {
                    var linkUrl = new URL(href, window.location.href);
                    isExternal = linkUrl.origin !== window.location.origin;
                } catch (e) {
                    isExternal = true;
                }
            }

            if (isExternal) {
                return;
            }

            anchor.setAttribute('data-nav-url', href);
            anchor.setAttribute('data-nav-target', target);
            anchor.setAttribute('href', 'javascript:void(0)');
            anchor.removeAttribute('target');

            anchor.addEventListener('click', function (event) {
                // Keep default behavior for special mouse/keyboard combinations.
                if (event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) {
                    return;
                }

                event.preventDefault();
                var navUrl = anchor.getAttribute('data-nav-url');
                if (navUrl) {
                    if (anchor.getAttribute('data-nav-target') === '_blank') {
                        window.open(navUrl, '_blank', 'noopener');
                        return;
                    }
                    window.location.assign(navUrl);
                }
            });
        });
    }

    function initConfirmActions() {
        document.addEventListener('click', function (event) {
            var trigger = event.target.closest('[data-confirm]');
            if (!trigger) {
                return;
            }

            var message = trigger.getAttribute('data-confirm') || 'Lanjutkan aksi ini?';

            if (trigger.tagName === 'A') {
                event.preventDefault();
                var href = trigger.getAttribute('href') || trigger.getAttribute('data-nav-url');
                if (!href || href === '#') {
                    return;
                }

                runConfirm(message, function () {
                    var target = (trigger.getAttribute('target') || '').toLowerCase();
                    if (target === '_blank') {
                        window.open(href, '_blank', 'noopener');
                        return;
                    }
                    window.location.assign(href);
                });
                return;
            }

            if (trigger.tagName === 'BUTTON' && (trigger.type || '').toLowerCase() === 'submit') {
                var form = trigger.form || trigger.closest('form');
                if (!form || trigger.dataset.confirmed === '1') {
                    return;
                }

                event.preventDefault();
                runConfirm(message, function () {
                    trigger.dataset.confirmed = '1';
                    form.submit();
                });
            }
        });
    }

    function autoDismissAlerts() {
        var alerts = document.querySelectorAll('.alert');
        if (!alerts.length) {
            return;
        }

        var host = document.querySelector('.floating-alert-container');
        if (!host) {
            host = document.createElement('div');
            host.className = 'floating-alert-container';
            document.body.appendChild(host);
        }

        alerts.forEach(function (el) {
            if (el.closest('.floating-alert-container')) {
                return;
            }
            if (el.hasAttribute('data-static-alert')) {
                return;
            }
            el.classList.add('floating-alert');
            host.appendChild(el);
        });

        var timeoutMs = 5000;
        setTimeout(function () {
            alerts.forEach(function (el) {
                el.classList.add('opacity-0');
                setTimeout(function () {
                    if (el && el.parentNode) {
                        el.parentNode.removeChild(el);
                    }
                }, 350);
            });
        }, timeoutMs);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            appendLinkToken();
            initJsNavigation();
            initConfirmActions();
            autoDismissAlerts();
        });
    } else {
        appendLinkToken();
        initJsNavigation();
        initConfirmActions();
        autoDismissAlerts();
    }
})();
