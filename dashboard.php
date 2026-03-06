<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GovTrack | Municipal Workflow</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>

<div class="container">
    <aside class="sidebar" id="sidebar">
        <div class="logo-section">
            <div class="logo-content">
                <i class="ph-fill ph-buildings"></i>
                <span>GovFlow</span>
            </div>
            <button class="mobile-close" onclick="toggleMenu()"><i class="ph ph-x"></i></button>
        </div>
        
        <nav class="nav-menu">
            <button class="nav-item active" onclick="loadContent('documents', this)">
                <i class="ph ph-file-text"></i>
                <span class="label">Documents</span>
                <span class="badge">12</span>
            </button>
            <button class="nav-item" onclick="loadContent('transactions', this)">
                <i class="ph ph-arrows-left-right"></i>
                <span class="label">Transactions</span>
                <span class="badge">5</span>
            </button>
            <button class="nav-item" onclick="loadContent('receive', this)">
                <i class="ph ph-download-simple"></i>
                <span class="label">Receive</span>
                <span class="badge warning">3</span>
            </button>
            <button class="nav-item" onclick="loadContent('transmittals', this)">
                <i class="ph ph-paper-plane-tilt"></i>
                <span class="label">Transmittals</span>
                <span class="badge">8</span>
            </button>
        </nav>
    </aside>

    <main class="main-panel">
        <header class="top-nav">
            <div class="nav-left">
                <button class="menu-toggle" onclick="toggleMenu()">
                    <i class="ph ph-list"></i>
                </button>
                <div class="search-group">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" placeholder="Search...">
                </div>
            </div>
            
            <div class="top-tools">
                <button class="tool-btn hide-mobile"><i class="ph ph-arrows-clockwise"></i></button>
                <button class="tool-btn" id="theme-toggle"><i class="ph ph-moon"></i></button>
                
                <div class="user-profile">
                    <img src="assets/icons/lgu-logo.png" alt="LGU Logo" class="lgu-logo">
                    <div class="user-info hide-mobile">
                        <span class="dept-name">Mayor's Office</span>
                    </div>
                    <i class="ph ph-bell notification-icon"></i>
                </div>
            </div>
        </header>

        <section id="content-display" class="content-area">
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
                    <div class="card-icon"><i class="ph-duotone ph-wallet"></i></div>
                    <span class="card-label">Petty Cash Voucher</span>
                    <span class="card-count">01</span>
                </button>

                <button class="doc-card">
                    <div class="card-icon"><i class="ph-duotone ph-folders"></i></div>
                    <span class="card-label">Other Documents</span>
                    <span class="card-count">22</span>
                </button>
            </div>
        </section>
    </main>
</div>

<div class="sidebar-overlay" id="overlay" onclick="toggleMenu()"></div>

<script src="js/dashboard.js"></script>
</body>
</html>