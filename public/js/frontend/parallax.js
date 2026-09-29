/**
 * parallax.js
 * Efek background bergerak lebih lambat dari scroll (parallax).
 * Cara pakai di HTML/Blade:
 *   <section class="page-hero" data-parallax data-parallax-speed="0.4">
 *
 * data-parallax-speed: 0 = diam total, 1 = ikut scroll normal (tanpa efek).
 * Makin kecil angkanya, makin "ketinggalan" background-nya -> efek makin terasa.
 * Nilai wajar: 0.2 - 0.5
 */
document.addEventListener('DOMContentLoaded', function () {
    const elements = document.querySelectorAll('[data-parallax]');
    if (elements.length === 0) return;

    let ticking = false;

    function updateParallax() {
        const scrollY = window.scrollY;

        elements.forEach(function (el) {
            const speed = parseFloat(el.getAttribute('data-parallax-speed')) || 0.3;
            // Cuma proses elemen yang lagi kelihatan di layar (hemat performa)
            const rect = el.getBoundingClientRect();
            if (rect.bottom < 0 || rect.top > window.innerHeight) return;

            const offset = scrollY * speed;
            el.style.backgroundPosition = 'center calc(50% + ' + offset + 'px)';
        });

        ticking = false;
    }

    window.addEventListener('scroll', function () {
        if (!ticking) {
            requestAnimationFrame(updateParallax);
            ticking = true;
        }
    });

    updateParallax(); // jalankan sekali di awal
});
