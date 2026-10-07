document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("publikasi-container");
    const paginationEl = document.getElementById("publikasi-pagination");
    const searchInput = document.getElementById("publikasi-search-input");

    if (!container) return; // guard: script ini cuma jalan di halaman publikasi

    let currentPage = 1;
    let currentSearch = "";
    let debounceTimer = null;

    function escapeHtml(str) {
        const div = document.createElement("div");
        div.textContent = str ?? "";
        return div.innerHTML;
    }

    function renderLoading() {
        container.innerHTML =
            '<div class="col-12"><p class="publikasi-loading">Memuat data...</p></div>';
        paginationEl.innerHTML = "";
    }

    function renderEmpty() {
        container.innerHTML =
            '<div class="col-12"><p class="publikasi-empty">Tidak ada publikasi yang cocok.</p></div>';
        paginationEl.innerHTML = "";
    }

    function renderCards(items) {
        container.innerHTML = items
            .map(function (item) {
                var detail = item.detail_persyaratan && item.detail_persyaratan.length > 0
                    ? item.detail_persyaratan[0]
                    : null;
    
                var downloadButton = detail
                    ? '<a href="storage/' + detail.berkas + '" class="btn btn-custom mt-auto" download>' +
                    '<i class="ti ti-download me-1"></i> Unduh Formulir</a>'
                    : '<span class="text-muted">Berkas belum tersedia</span>';
    
                return (
                    '<div class="col-12">' +
                    '<div class="publikasi-card">' +
                    "<div class='d-flex align-items-center gap-5'>" +
                    '<div class="publikasi-icon"><i class="ti ti-file-type-pdf"></i></div>' +
                    "<h5>" +
                    escapeHtml(item.nama_persyaratan) +
                    "</h5>" +
                    "</div>" +
                    downloadButton +
                    "</div>" +
                    "</div>"
                );
            })
            .join("");
    }

    function renderPagination(currentPageResp, lastPage) {
        if (lastPage <= 1) {
            paginationEl.innerHTML = "";
            return;
        }

        let html = "";

        html +=
            '<button type="button" data-page="' +
            (currentPageResp - 1) +
            '" ' +
            (currentPageResp === 1 ? "disabled" : "") +
            ">" +
            '<i class="ti ti-chevron-left"></i></button>';

        for (let p = 1; p <= lastPage; p++) {
            html +=
                '<button type="button" data-page="' +
                p +
                '" class="' +
                (p === currentPageResp ? "active" : "") +
                '">' +
                p +
                "</button>";
        }

        html +=
            '<button type="button" data-page="' +
            (currentPageResp + 1) +
            '" ' +
            (currentPageResp === lastPage ? "disabled" : "") +
            ">" +
            '<i class="ti ti-chevron-right"></i></button>';

        paginationEl.innerHTML = html;

        // Pasang event klik ke semua tombol halaman
        paginationEl
            .querySelectorAll("button[data-page]")
            .forEach(function (btn) {
                btn.addEventListener("click", function () {
                    const page = parseInt(btn.getAttribute("data-page"), 10);
                    if (!page || page < 1 || page > lastPage) return;
                    currentPage = page;
                    loadData();
                    // Scroll halus ke atas daftar card supaya user lihat hasil halaman baru
                    container.scrollIntoView({
                        behavior: "smooth",
                        block: "start",
                    });
                });
            });
    }

    function loadData() {
        renderLoading();

        const url = new URL(window.formulirDataUrl, window.location.origin);
        url.searchParams.set("page", currentPage);
        url.searchParams.set("search", currentSearch);
        url.searchParams.set("per_page", 6);

        fetch(url)
            .then(function (res) {
                return res.json();
            })
            .then(function (res) {
                if (!res.success || !res.data || res.data.length === 0) {
                    renderEmpty();
                    return;
                }
                renderCards(res.data);
                renderPagination(res.current_page, res.last_page);
            })
            .catch(function (err) {
                console.error("Gagal memuat publikasi:", err);
                container.innerHTML =
                    '<div class="col-12"><p class="publikasi-empty">Terjadi kesalahan memuat data.</p></div>';
            });
    }

    // Search dengan debounce 400ms supaya tidak fetch tiap ketikan huruf
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                currentSearch = searchInput.value.trim();
                currentPage = 1; // reset ke halaman 1 tiap kali search berubah
                loadData();
            }, 400);
        });
    }

    // Load pertama kali halaman dibuka
    loadData();
});
