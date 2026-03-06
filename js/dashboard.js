// Toggle Sidebar for Mobile
function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}

// SPA-like Content Loading
function loadContent(view, element) {
    const display = document.getElementById('content-display');
    
    if (window.innerWidth <= 850) toggleMenu();

    document.querySelectorAll('.nav-item').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');

    // Replace the if/else logic in your loadContent function with this:
    if (view === 'documents') {
            display.innerHTML = `
                <h2 class="page-title">Documents</h2>
                <div class="doc-grid">
                    <button class="doc-card primary">
                        <div class="card-icon"><i class="ph-duotone ph-envelope-simple"></i></div>
                        <span class="card-label">General Request</span>
                        <span class="card-count">08</span>
                    </button>

                    <button class="doc-card">
                        <div class="card-icon"><i class="ph-duotone ph-shopping-cart"></i></div>
                        <span class="card-label">Purchase Request</span>
                        <span class="card-count">15</span>
                    </button>

                    <button class="doc-card">
                        <div class="card-icon"><i class="ph-duotone ph-gavel"></i></div>
                        <span class="card-label">BAC Resolution</span>
                        <span class="card-count">06</span>
                    </button>

                    <button class="doc-card">
                        <div class="card-icon"><i class="ph-duotone ph-list-checks"></i></div>
                        <span class="card-label">Abstract of Canvass</span>
                        <span class="card-count">04</span>
                    </button>

                    <button class="doc-card">
                        <div class="card-icon"><i class="ph-duotone ph-package"></i></div>
                        <span class="card-label">Purchase Order</span>
                        <span class="card-count">09</span>
                    </button>

                    <button class="doc-card">
                        <div class="card-icon"><i class="ph-duotone ph-megaphone"></i></div>
                        <span class="card-label">Notice to Proceed</span>
                        <span class="card-count">02</span>
                    </button>

                    <button class="doc-card">
                        <div class="card-icon"><i class="ph-duotone ph-currency-circle-dollar"></i></div>
                        <span class="card-label">Obligation Request</span>
                        <span class="card-count">04</span>
                    </button>

                    <button class="doc-card">
                        <div class="card-icon"><i class="ph-duotone ph-receipt"></i></div>
                        <span class="card-label">Disbursement Voucher</span>
                        <span class="card-count">12</span>
                    </button>

                    <button class="doc-card">
                        <div class="card-icon"><i class="ph-duotone ph-money"></i></div>
                        <span class="card-label">Checks</span>
                        <span class="card-count">03</span>
                    </button>

                    <button class="doc-card">
                        <div class="card-icon"><i class="ph-duotone ph-wallet"></i></div>
                        <span class="card-label">Petty Cash Voucher</span>
                        <span class="card-count">01</span>
                    </button>

                    <button class="doc-card">
                        <div class="card-icon"><i class="ph-duotone ph-folders"></i></div>
                        <span class="card-label">Other Documents</span>
                        <span class="card-count">22</span>
                    </button>
                </div>`;
        } else if (view === 'transactions') {
        display.innerHTML = `
            <h2 class="page-title">Active Transactions</h2>
            <div class="doc-grid">
                <button class="doc-card">
                    <div class="card-icon"><i class="ph-duotone ph-clock"></i></div>
                    <span class="card-label">Pending Review</span>
                    <span class="card-count">05</span>
                </button>
                <button class="doc-card">
                    <div class="card-icon"><i class="ph-duotone ph-arrows-left-right"></i></div>
                    <span class="card-label">In Transit</span>
                    <span class="card-count">02</span>
                </button>
            </div>`;
    } else {
        display.innerHTML = `<h2 class="page-title">${view.charAt(0).toUpperCase() + view.slice(1)}</h2>
                             <p>You are viewing the ${view} management panel.</p>`;
    }
}

// Theme Toggle Logic
const themeBtn = document.getElementById('theme-toggle');
themeBtn.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    const icon = themeBtn.querySelector('i');
    icon.classList.toggle('ph-moon');
    icon.classList.toggle('ph-sun');
});