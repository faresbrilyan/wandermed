/* ========================================================= */
/* FILE: public/js/script-wisatawan.js                       */
/* PROJECT: WanderMed - Hear & Build Studio                  */
/* DESCRIPTION: Main Logic for Navigation, Theme, & UI       */
/* ========================================================= */

document.addEventListener("DOMContentLoaded", function () {
    // --- 1. INISIALISASI ---
    var body     = document.body;
    var html     = document.documentElement;
    var navbar   = document.getElementById("mainNavbar");
    var scrollLinks = document.querySelectorAll(".scroll-link");
    var allNavLinks  = document.querySelectorAll(".wm-nav-link, .nav-pill-link");
    var sections  = document.querySelectorAll(".section-scroll, #page-top");

    var getNavbarHeight = function () { return navbar ? navbar.offsetHeight : 68; };

    // =========================================================
    // 2. DARK / LIGHT MODE TOGGLE
    // =========================================================
    var themeToggleBtn = document.getElementById("themeToggle");
    var themeIcon      = document.getElementById("themeIcon");

    var updateThemeUI = function () {
        if (body.classList.contains("light-mode")) {
            if (themeIcon) {
                themeIcon.classList.remove("fa-sun");
                themeIcon.classList.add("fa-moon");
            }
        } else {
            if (themeIcon) {
                themeIcon.classList.remove("fa-moon");
                themeIcon.classList.add("fa-sun");
            }
        }
    };
    updateThemeUI();

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener("click", function (e) {
            e.preventDefault();
            body.classList.toggle("light-mode");
            html.classList.toggle("light-mode");
            var currentTheme = body.classList.contains("light-mode") ? "light" : "dark";
            localStorage.setItem("wanderMedTheme", currentTheme);
            updateThemeUI();
            
            // Notify other components (like Leaflet map) of the theme change
            window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: currentTheme } }));
        });
    }

    // =========================================================
    // 3. SMOOTH SCROLL saat klik nav link
    // =========================================================
    scrollLinks.forEach(function (link) {
        link.addEventListener("click", function (e) {
            var href = this.getAttribute("href");
            if (href && href.includes("#")) {
                var targetId      = href.substring(href.indexOf("#"));
                var targetElement = document.querySelector(targetId);

                if (targetElement && (window.location.pathname === '/' || window.location.pathname === '')) {
                    e.preventDefault();
                    var targetPosition = targetElement.offsetTop - getNavbarHeight() - 10;

                    if (typeof $ !== 'undefined') {
                        $('html, body').animate({ scrollTop: targetPosition }, 800);
                    } else {
                        window.scrollTo({ top: targetPosition, behavior: "smooth" });
                    }

                    // Tutup mobile menu jika terbuka
                    var navbarCollapse = document.getElementById("navbarNav");
                    if (navbarCollapse && navbarCollapse.classList.contains("show")) {
                        if (typeof $ !== 'undefined') { $(navbarCollapse).collapse("hide"); }
                    }
                }
            }
        });
    });

    // =========================================================
    // 4. SCROLL SPY + SCROLL INDICATOR HIDE
    // =========================================================
    var ticking = false;

    function onScroll() {
        var scrollY = window.pageYOffset || document.documentElement.scrollTop;

        // --- Scroll Spy: highlight .wm-nav-link aktif ---
        var currentSectionId = "";
        sections.forEach(function (section) {
            if (scrollY >= section.offsetTop - getNavbarHeight() - 80) {
                currentSectionId = section.getAttribute("id") || "";
            }
        });

        allNavLinks.forEach(function (link) {
            link.classList.remove("active");
            var href = link.getAttribute("href") || "";
            if (currentSectionId && href.includes("#" + currentSectionId)) {
                link.classList.add("active");
            }
        });

        // --- Sembunyikan scroll indicator saat scroll mulai ---
        var scrollInd = document.getElementById("heroScrollIndicator");
        if (scrollInd) {
            if (scrollY > 80) {
                scrollInd.style.opacity = "0";
                scrollInd.style.pointerEvents = "none";
            } else {
                scrollInd.style.opacity = "0.6";
                scrollInd.style.pointerEvents = "auto";
            }
        }

        ticking = false;
    }

    window.addEventListener("scroll", function () {
        if (!ticking) {
            requestAnimationFrame(onScroll);
            ticking = true;
        }
    }, { passive: true });

    // Jalankan sekali saat load
    onScroll();

    // =========================================================
    // 5. SHOW/HIDE PASSWORD (Login Page)
    // =========================================================
    var btnToggle     = document.getElementById("btnToggle");
    var inputPassword = document.getElementById("inputPassword");
    var ikonMata      = document.getElementById("ikonMata");

    if (btnToggle && inputPassword && ikonMata) {
        btnToggle.addEventListener("click", function () {
            if (inputPassword.type === "password") {
                inputPassword.type = "text";
                ikonMata.classList.remove("fa-eye");
                ikonMata.classList.add("fa-eye-slash");
            } else {
                inputPassword.type = "password";
                ikonMata.classList.remove("fa-eye-slash");
                ikonMata.classList.add("fa-eye");
            }
        });
    }
});
