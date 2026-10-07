document.addEventListener("DOMContentLoaded", function () {
    const navbar = document.getElementById("mainNavbar");
    if (!navbar) return;

    window.addEventListener("scroll", function () {
        if (window.scrollY > 50) {
            navbar.classList.remove("navbar-transparent");
            navbar.classList.add("navbar-scrolled");
        } else {
            navbar.classList.remove("navbar-scrolled");
            navbar.classList.add("navbar-transparent");
        }
    });
});

function showToast(type, message, delay = 4000) {
    const container = document.getElementById("toast-container");
    if (!container) {
        console.warn(
            "showToast: #toast-container tidak ditemukan di halaman ini.",
        );
        return;
    }

    const config = {
        success: { bg: "bg-success", icon: "ti-circle-check" },
        error: { bg: "bg-danger", icon: "ti-alert-circle" },
        warning: { bg: "bg-warning", icon: "ti-alert-triangle" },
        info: { bg: "bg-primary", icon: "ti-info-circle" },
    };
    const { bg, icon } = config[type] || config.info;

    const toastEl = document.createElement("div");
    toastEl.className = "toast align-items-center text-white border-0 " + bg;
    toastEl.setAttribute("role", "alert");
    toastEl.setAttribute("aria-live", "assertive");
    toastEl.setAttribute("aria-atomic", "true");
    toastEl.innerHTML =
        '<div class="d-flex">' +
        '<div class="toast-body"><i class="ti ' +
        icon +
        ' me-2"></i>' +
        message +
        "</div>" +
        '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
        "</div>";

    container.appendChild(toastEl);

    const toast = new bootstrap.Toast(toastEl, { delay: delay });
    toast.show();

    // Bersihkan elemen dari DOM setelah toast hilang, biar nggak numpuk
    toastEl.addEventListener("hidden.bs.toast", function () {
        toastEl.remove();
    });
}

window.showToast = showToast;
