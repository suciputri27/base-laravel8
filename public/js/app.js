(function () {
    'use strict';

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function jsonHeaders() {
        return {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken()
        };
    }

    function escapeHtml(value) {
        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function debounce(fn, wait) {
        var timer;

        return function () {
            var args = arguments;
            var ctx = this;

            clearTimeout(timer);
            timer = setTimeout(function () {
                fn.apply(ctx, args);
            }, wait || 400);
        };
    }

    function showAlert(type, message) {
        if (window.Swal) {
            var icon = type === 'success' ? 'success' : (type === 'warning' ? 'warning' : 'error');

            Swal.fire({
                icon: icon,
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: function (toast) {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            return;
        }

        var box = document.getElementById('globalAlert');
        if (!box) {
            return;
        }

        box.className = 'alert alert-' + type;
        box.textContent = message;
        box.classList.remove('d-none');

        window.setTimeout(function () {
            box.classList.add('d-none');
        }, 5000);
    }

    function redirect(url) {
        if (window.NProgress) {
            NProgress.start();
        }

        if (url) {
            window.location.href = url;
        }
    }

    function validationMessages(payload) {
        if (!payload || payload.errors === null || typeof payload.errors !== 'object' || Array.isArray(payload.errors)) {
            return [];
        }

        var messages = [];

        Object.keys(payload.errors).forEach(function (field) {
            var errors = payload.errors[field];

            if (Array.isArray(errors)) {
                errors.forEach(function (message) {
                    if (typeof message === 'string' && message) {
                        messages.push(message);
                    }
                });
            } else if (typeof errors === 'string' && errors) {
                messages.push(errors);
            }
        });

        return messages;
    }

    function handleResponse(response, options) {
        return response.json().then(function (payload) {
            if (response.ok && payload.success) {
                if (options.onSuccess) {
                    options.onSuccess(payload);
                }
                return;
            }

            var messages = validationMessages(payload);
            var message = messages.length
                ? messages.join('\n')
                : (payload.message || 'Terjadi kesalahan.');

            if (options.onError) {
                payload.message = message;
                options.onError(payload, response.status);
            } else {
                showAlert('danger', message);
            }
        });
    }

    function setButtonLoading(button, loading, label) {
        if (!button) {
            return;
        }

        if (loading) {
            button.dataset.original = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + (label || 'Memproses...');
        } else {
            button.disabled = false;
            if (button.dataset.original) {
                button.innerHTML = button.dataset.original;
            }
        }
    }

    window.App = {
        escapeHtml: escapeHtml,
        alert: showAlert,
        redirect: redirect,
        debounce: debounce,

        submit: function (form, options) {
            var settings = options || {};
            var url = form.getAttribute('action') || window.location.href;
            var data = new FormData(form);
            var button = form.querySelector('button[type="submit"]');

            setButtonLoading(button, true, settings.loadingText || 'Memproses...');

            fetch(url, {
                method: 'POST',
                body: data,
                headers: jsonHeaders()
            })
                .then(function (response) {
                    return handleResponse(response, settings);
                })
                .catch(function () {
                    showAlert('danger', 'Terjadi kesalahan jaringan.');
                })
                .then(function () {
                    setButtonLoading(button, false);
                });
        },

        submitData: function (options) {
            var settings = options || {};
            var url = settings.url;
            var method = (settings.method || 'POST').toUpperCase();
            var data = settings.data || {};
            var headers = jsonHeaders();
            var body;
            var button = settings.button || null;

            if (data instanceof FormData) {
                body = data;
            } else {
                headers['Content-Type'] = 'application/json';
                body = JSON.stringify(data);
            }

            setButtonLoading(button, true, settings.loadingText || 'Memproses...');

            fetch(url, { method: method, headers: headers, body: body })
                .then(function (response) {
                    return handleResponse(response, settings);
                })
                .catch(function () {
                    showAlert('danger', 'Terjadi kesalahan jaringan.');
                })
                .then(function () {
                    setButtonLoading(button, false);
                });
        },

        infiniteScroll: function (options) {
            var settings = options || {};
            var container = typeof settings.container === 'string'
                ? document.querySelector(settings.container)
                : settings.container;
            var body = typeof settings.body === 'string'
                ? document.querySelector(settings.body)
                : settings.body;
            var endpoint = settings.endpoint;
            var params = settings.params || {};
            var rowRenderer = settings.rowRenderer;
            var pageSize = settings.pageSize || 10;
            var onComplete = settings.onComplete;
            var extraParams = settings.extraParams || {};
            var emptyMessage = settings.emptyMessage || 'Belum ada data.';
            var emptySearchMessage = settings.emptySearchMessage || 'Data tidak ditemukan.';
            var errorMessage = settings.errorMessage || 'Gagal memuat data.';

            var nextCursor = null;
            var hasMore = true;
            var loading = false;
            var search = '';
            var controller = null;

            var statusEl = document.createElement('div');
            statusEl.className = 'table-status';
            container.appendChild(statusEl);

            function colSpan() {
                var ths = container.querySelectorAll('thead th');
                return ths.length || 1;
            }

            function emptyRow(message) {
                return '<tr class="table-state-row"><td colspan="' + colSpan() + '"><i class="fas fa-inbox"></i> ' + escapeHtml(message || emptyMessage) + '</td></tr>';
            }

            function setStatus(html, state) {
                statusEl.classList.remove('d-none');
                statusEl.className = 'table-status' + (state ? ' table-status-' + state : '');
                statusEl.innerHTML = html;
            }

            function hideStatus() {
                statusEl.classList.add('d-none');
            }

            function showLoadingStatus() {
                setStatus('<i class="fas fa-spinner fa-spin"></i> Memuat data...', 'loading');
            }

            function showMoreStatus() {
                setStatus('Gulir ke bawah untuk memuat data berikutnya', 'more');
            }

            function showEndStatus() {
                setStatus('<i class="fas fa-check-circle"></i> Semua data sudah dimuat', 'end');
            }

            function showEmptyStatus() {
                hideStatus();
            }

            function showErrorStatus() {
                setStatus('<i class="fas fa-triangle-exclamation"></i> ' + escapeHtml(errorMessage) + ' <button type="button" class="btn btn-secondary btn-sm" id="tableRetry">Muat ulang</button>', 'error');
                var retry = document.getElementById('tableRetry');
                if (retry) {
                    retry.addEventListener('click', function () {
                        load(true);
                    });
                }
            }

            function load(reset) {
                if (loading || !hasMore) {
                    return;
                }

                loading = true;

                if (controller) {
                    controller.abort();
                }

                controller = new AbortController();

                if (reset) {
                    body.innerHTML = '';
                }

                showLoadingStatus();

                var query = {};
                Object.keys(params).forEach(function (key) {
                    query[key] = params[key];
                });
                Object.keys(extraParams).forEach(function (key) {
                    query[key] = extraParams[key];
                });

                query.per_page = pageSize;
                query.cursor = reset ? null : nextCursor;
                query.search = search;

                var queryString = Object.keys(query).map(function (key) {
                    if (query[key] === null || query[key] === undefined || query[key] === '') {
                        return null;
                    }

                    return encodeURIComponent(key) + '=' + encodeURIComponent(query[key]);
                }).filter(Boolean).join('&');

                var url = endpoint + (queryString ? '?' + queryString : '');

                fetch(url, { headers: jsonHeaders(), signal: controller.signal })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        if (reset) {
                            body.innerHTML = '';
                        }

                        var rows = data.data || [];

                        if (rows.length === 0 && reset) {
                            body.innerHTML = emptyRow(search ? emptySearchMessage : emptyMessage);
                            showEmptyStatus();
                        } else {
                            rows.forEach(function (item) {
                                body.insertAdjacentHTML('beforeend', rowRenderer(item));
                            });

                            if (data.has_more_pages) {
                                showMoreStatus();
                            } else {
                                showEndStatus();
                            }
                        }

                        nextCursor = data.next_cursor;
                        hasMore = data.has_more_pages;
                        loading = false;
                        controller = null;

                        if (typeof onComplete === 'function') {
                            onComplete(data);
                        }
                    })
                    .catch(function (err) {
                        if (err && err.name === 'AbortError') {
                            return;
                        }

                        loading = false;
                        controller = null;

                        if (reset) {
                            body.innerHTML = '';
                        }

                        showErrorStatus();

                        if (typeof onComplete === 'function') {
                            onComplete(null);
                        }
                    });
            }

            container.addEventListener('scroll', function () {
                if (container.scrollTop + container.clientHeight >= container.scrollHeight - 50) {
                    load(false);
                }
            });

            var debouncedSearch = debounce(function (value) {
                search = value || '';
                nextCursor = null;
                hasMore = true;
                load(true);
            }, 400);

            return {
                load: load,
                search: debouncedSearch,
                reset: function () {
                    search = '';
                    nextCursor = null;
                    hasMore = true;
                    load(true);
                }
            };
        }
    };
})();
