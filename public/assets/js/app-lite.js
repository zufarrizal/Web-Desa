(function () {
    var prefetched = {};

    function navigateTo(url) {
        if (!url) {
            return;
        }

        document.body.classList.add('is-page-leaving');
        window.setTimeout(function () {
            window.location.assign(url);
        }, 80);
    }

    function normalizePrefetchUrl(rawUrl) {
        if (!rawUrl || rawUrl.charAt(0) === '#') {
            return '';
        }

        if (
            rawUrl.indexOf('javascript:') === 0 ||
            rawUrl.indexOf('mailto:') === 0 ||
            rawUrl.indexOf('tel:') === 0
        ) {
            return '';
        }

        try {
            var url = new URL(rawUrl, window.location.href);
            if (url.origin !== window.location.origin) {
                return '';
            }

            var path = (url.pathname || '').toLowerCase().replace(/^\/+/, '');
            var blockedPaths = [
                'documents/generate/',
                'documents/store-manual/',
                'documents/status/',
                'documents/delete/',
                'complaints/delete/',
                'users/delete/',
                'programs/delete/',
            ];

            // Never prefetch state-changing/sensitive endpoints.
            if (
                path === 'logout' ||
                path.indexOf('logout/') === 0 ||
                path === 'login' ||
                path === 'register' ||
                path === 'forgot-password' ||
                path.indexOf('reset-password/') === 0
            ) {
                return '';
            }
            for (var i = 0; i < blockedPaths.length; i += 1) {
                if (path.indexOf(blockedPaths[i]) === 0) {
                    return '';
                }
            }

            return url.pathname + url.search;
        } catch (e) {
            return '';
        }
    }

    function prefetchUrl(rawUrl) {
        var url = normalizePrefetchUrl(rawUrl);
        if (!url || prefetched[url]) {
            return;
        }

        prefetched[url] = true;
        var link = document.createElement('link');
        link.rel = 'prefetch';
        link.as = 'document';
        link.href = url;
        document.head.appendChild(link);
    }

    function initLinkPrefetch() {
        var anchors = document.querySelectorAll('a[data-nav-url], a[href]');
        if (!anchors.length) {
            return;
        }

        anchors.forEach(function (anchor) {
            if (
                anchor.hasAttribute('download') ||
                anchor.hasAttribute('data-confirm') ||
                anchor.hasAttribute('data-no-js-nav') ||
                anchor.hasAttribute('data-no-prefetch')
            ) {
                return;
            }

            var triggerPrefetch = function () {
                var candidate = anchor.getAttribute('data-nav-url') || anchor.getAttribute('href') || '';
                prefetchUrl(candidate);
            };

            anchor.addEventListener('mouseenter', triggerPrefetch, { passive: true });
            anchor.addEventListener('focus', triggerPrefetch, { passive: true });
            anchor.addEventListener('touchstart', triggerPrefetch, { passive: true });
        });

        // No auto prefetch loop. Prefetch only when user interacts with links.
    }

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
                    navigateTo(navUrl);
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
                    navigateTo(href);
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

    function initTablePagination() {
        var tables = document.querySelectorAll('table[data-table-paginate]');
        if (!tables.length) {
            return;
        }

        function createPagerButton(label, disabled, active, onClick) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'table-pager-btn' + (active ? ' active' : '');
            btn.textContent = label;
            btn.disabled = !!disabled;
            btn.addEventListener('click', onClick);
            return btn;
        }

        tables.forEach(function (table) {
            var tableId = table.getAttribute('id');
            if (!tableId) {
                return;
            }

            var rows = Array.prototype.slice.call(table.querySelectorAll('tbody tr'));
            if (!rows.length) {
                return;
            }

            var sizeSelect = document.querySelector('[data-page-size-for="' + tableId + '"]');
            var pagerHost = document.querySelector('[data-pager-for="' + tableId + '"]');
            var statusFilter = document.querySelector('[data-status-filter-for="' + tableId + '"]');
            var currentPage = 1;

            function getPageSize() {
                if (!sizeSelect) {
                    return 10;
                }
                var value = parseInt(sizeSelect.value || '10', 10);
                if (isNaN(value) || value < 1) {
                    return 10;
                }
                return value;
            }

            function getFilteredRows() {
                if (!statusFilter) {
                    return rows.slice();
                }

                var selected = (statusFilter.value || 'all').toLowerCase();
                if (selected === 'all') {
                    return rows.slice();
                }

                return rows.filter(function (row) {
                    var status = (row.getAttribute('data-status') || '').toLowerCase();
                    return status === selected;
                });
            }

            function render() {
                var filtered = getFilteredRows();
                var pageSize = getPageSize();
                var totalRows = filtered.length;
                var totalPages = Math.max(1, Math.ceil(totalRows / pageSize));
                if (currentPage > totalPages) {
                    currentPage = totalPages;
                }
                if (currentPage < 1) {
                    currentPage = 1;
                }

                var startIndex = (currentPage - 1) * pageSize;
                var endIndex = startIndex + pageSize;

                rows.forEach(function (row) {
                    row.style.display = 'none';
                });

                filtered.forEach(function (row, index) {
                    var isVisible = index >= startIndex && index < endIndex;
                    row.style.display = isVisible ? '' : 'none';
                    if (isVisible) {
                        var noCell = row.querySelector('[data-row-no]');
                        if (noCell) {
                            noCell.textContent = String(index + 1);
                        }
                    }
                });

                if (!pagerHost) {
                    return;
                }

                pagerHost.innerHTML = '';
                if (totalRows <= 0) {
                    var empty = document.createElement('span');
                    empty.className = 'table-pager-empty';
                    empty.textContent = 'Tidak ada data';
                    pagerHost.appendChild(empty);
                    return;
                }

                pagerHost.appendChild(createPagerButton('Prev', currentPage === 1, false, function () {
                    if (currentPage > 1) {
                        currentPage -= 1;
                        render();
                    }
                }));

                for (var p = 1; p <= totalPages; p += 1) {
                    (function (pageNum) {
                        pagerHost.appendChild(createPagerButton(String(pageNum), false, currentPage === pageNum, function () {
                            currentPage = pageNum;
                            render();
                        }));
                    })(p);
                }

                pagerHost.appendChild(createPagerButton('Next', currentPage === totalPages, false, function () {
                    if (currentPage < totalPages) {
                        currentPage += 1;
                        render();
                    }
                }));
            }

            if (sizeSelect) {
                sizeSelect.addEventListener('change', function () {
                    currentPage = 1;
                    render();
                });
            }
            if (statusFilter) {
                statusFilter.addEventListener('change', function () {
                    currentPage = 1;
                    render();
                });
            }

            render();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            appendLinkToken();
            initJsNavigation();
            initLinkPrefetch();
            initConfirmActions();
            autoDismissAlerts();
            initTablePagination();
        });
    } else {
        appendLinkToken();
        initJsNavigation();
        initLinkPrefetch();
        initConfirmActions();
        autoDismissAlerts();
        initTablePagination();
    }
})();
