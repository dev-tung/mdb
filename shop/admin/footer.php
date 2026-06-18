<script>
    (function () {
    let activeRequests = 0;

    // ---------- CREATE LOADER ----------
    const loader = document.createElement("div");
    loader.style.position = "fixed";
    loader.style.top = "0";
    loader.style.left = "0";
    loader.style.width = "100vw";
    loader.style.height = "100vh";
    loader.style.background = "rgba(2, 6, 23, 0.85)";
    loader.style.display = "flex";
    loader.style.justifyContent = "center";
    loader.style.alignItems = "center";
    loader.style.zIndex = "9999";
    loader.style.visibility = "hidden";

    const spinner = document.createElement("div");
    spinner.style.width = "60px";
    spinner.style.height = "60px";
    spinner.style.border = "6px solid #383838ff";
    spinner.style.borderTop = "6px solid #a0a0a0ff";
    spinner.style.borderRadius = "50%";
    spinner.style.animation = "spin 1s linear infinite";

    loader.appendChild(spinner);
    document.body.appendChild(loader);

    const style = document.createElement("style");
    style.innerHTML = `
        @keyframes spin {
        to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);

    function showLoader() {
        loader.style.visibility = "visible";
    }

    function hideLoader() {
        loader.style.visibility = "hidden";
    }

    // ---------- FETCH INTERCEPT ----------
    const originalFetch = window.fetch;
    window.fetch = function (...args) {
        activeRequests++;
        showLoader();

        return originalFetch(...args)
        .finally(() => {
            activeRequests--;
            if (activeRequests === 0) hideLoader();
        });
    };

    // ---------- XHR INTERCEPT ----------
    const originalOpen = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function (...args) {
        activeRequests++;
        showLoader();

        this.addEventListener("loadend", () => {
        activeRequests--;
        if (activeRequests === 0) hideLoader();
        });

        originalOpen.apply(this, args);
    };
    })();

</script>

<script src="<?php echo url('script.js'); ?>"></script>

</body>
</html>