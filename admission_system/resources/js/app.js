import './bootstrap';

/* ── Page Loader ──────────────────────────────────────────────────────────── */
(function () {
    // Build the overlay once the DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        const loader = document.createElement('div');
        loader.id = 'page-loader';
        loader.innerHTML = `
            <div class="psl-ring"></div>
            <div class="psl-dots">
                <div class="psl-dot"></div>
                <div class="psl-dot"></div>
                <div class="psl-dot"></div>
            </div>
            <p class="psl-text">Loading…</p>
        `;
        document.body.appendChild(loader);

        let autoHideTimer;
        const show = () => {
            loader.classList.add('psl-visible');
            clearTimeout(autoHideTimer);
            autoHideTimer = setTimeout(hide, 4000); // cap at 4 s
        };
        const hide = () => {
            clearTimeout(autoHideTimer);
            loader.classList.remove('psl-visible');
        };

        // Show on navigating link clicks
        document.addEventListener('click', e => {
            const link = e.target.closest('a[href]');
            if (!link) return;

            const href = link.getAttribute('href');
            // Skip: anchors, JS, mailto, tel, new-tab, download
            if (!href
                || href.startsWith('#')
                || href.startsWith('javascript:')
                || href.startsWith('mailto:')
                || href.startsWith('tel:')
                || link.target === '_blank'
                || link.hasAttribute('download')) return;

            // Skip external URLs
            try {
                const url = new URL(href, window.location.href);
                if (url.hostname !== window.location.hostname) return;
            } catch (_) { return; }

            show();
        });

        // Show on form submissions (but NOT if the submit was cancelled, e.g. by
        // an onsubmit="return confirm(...)" returning false)
        document.addEventListener('submit', e => {
            if (!e.defaultPrevented) show();
        });

        // Hide when navigating back/forward (bfcache restore)
        window.addEventListener('pageshow', e => {
            if (e.persisted) hide();
        });
    });
})();
