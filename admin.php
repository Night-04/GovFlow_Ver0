<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GovFlow | Admin Portal</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="admin-theme">

<div class="container">
    <aside class="sidebar" id="sidebar">
        <div class="logo-section">
            <div class="logo-content">
                <img src="assets/icons/lgu-logo.png" alt="Logo" class="sidebar-logo" style="width:24px;">
                <span>GovFlow ADMIN</span>
            </div>
            <button class="mobile-close" onclick="toggleMenu()"><i class="ph ph-x"></i></button>
        </div>
        
        <nav class="nav-menu">
            <button class="nav-item active" onclick="loadAdminContent('overview', this)">
                <i class="ph ph-chart-pie-slice"></i>
                <span class="label">Overview</span>
            </button>
            <button class="nav-item" onclick="loadAdminContent('requests', this)">
                <i class="ph ph-user-plus"></i>
                <span class="label">Account Requests</span>
                <span class="badge warning">05</span>
            </button>
            <button class="nav-item" onclick="loadAdminContent('users', this)">
                <i class="ph ph-users-three"></i>
                <span class="label">User Management</span>
            </button>
            <button class="nav-item" onclick="loadAdminContent('master', this)">
                <i class="ph ph-folders"></i>
                <span class="label">Master Records</span>
            </button>
        </nav>
    </aside>

    <main class="main-panel">
        <header class="top-nav">
            <div class="nav-left">
                <button class="menu-toggle" onclick="toggleMenu()"><i class="ph ph-list"></i></button>
                <div class="search-group">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" placeholder="Search Master Records...">
                </div>
            </div>
            <div class="top-tools">
                <button class="tool-btn" id="theme-toggle"><i class="ph ph-moon"></i></button>
                <div class="user-profile">
                    <img src="assets/icons/lgu-logo.png" alt="Admin" class="lgu-logo">
                    <div class="user-info hide-mobile">
                        <span class="dept-name">System Administrator</span>
                    </div>
                </div>
            </div>
        </header>

        <section id="content-display" class="content-area">
            <h2 class="page-title">Welcome, Admin</h2>
            <p>Select an option from the sidebar to manage the municipality workflow.</p>
        </section>
    </main>
</div>

<script src="js/admin.js"></script>
</body>
</html>