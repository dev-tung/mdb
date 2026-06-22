(function () {

    let activeRequests = 0;

    // =========================
    // CREATE LOADER
    // =========================
    const loader = document.createElement("div");
    loader.style.cssText = `
        position: fixed;
        inset: 0;
        background: rgba(2, 6, 23, 0.57);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999999;
    `;

    loader.innerHTML = `
        <div style="
            width:60px;
            height:60px;
            border:6px solid #444;
            border-top:6px solid #aaa;
            border-radius:50%;
            animation: spin 1s linear infinite;
        "></div>
    `;

    document.addEventListener("DOMContentLoaded", () => {
        document.body.appendChild(loader);
    });

    // =========================
    // SPINNER CSS
    // =========================
    const style = document.createElement("style");
    style.innerHTML = `
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);

    // =========================
    // SHOW / HIDE
    // =========================
    function showLoader() {
        loader.style.display = "flex";
    }

    function hideLoader() {
        loader.style.display = "none";
    }

    // =========================
    // SHOW IMMEDIATELY ON PAGE LOAD START
    // =========================
    showLoader();

    // =========================
    // HIDE WHEN PAGE FULLY LOADED
    // =========================
    window.addEventListener("load", function () {
        setTimeout(() => {
            hideLoader();
        }, 300);
    });

    // =========================
    // FETCH INTERCEPT
    // =========================
    const originalFetch = window.fetch;

    window.fetch = function (...args) {
        activeRequests++;
        showLoader();

        return originalFetch(...args)
            .finally(() => {
                activeRequests--;
                if (activeRequests <= 0) hideLoader();
            });
    };

    // =========================
    // XHR INTERCEPT
    // =========================
    const originalOpen = XMLHttpRequest.prototype.open;

    XMLHttpRequest.prototype.open = function (...args) {
        activeRequests++;

        this.addEventListener("loadend", () => {
            activeRequests--;
            if (activeRequests <= 0) hideLoader();
        });

        return originalOpen.apply(this, args);
    };

})();