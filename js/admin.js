/**
 * GovFlow Admin Application Logic
 * Handles dynamic content loading and administrative actions
 */

// 1. Core Content Loader
function loadAdminContent(view, element) {
    const display = document.getElementById('content-display');
    
    // Close mobile menu if open
    if (window.innerWidth <= 850 && typeof toggleMenu === 'function') {
        toggleMenu();
    }

    // Update Sidebar Active State
    document.querySelectorAll('.nav-item').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');

    // View Switching Logic
    switch(view) {
        case 'overview':
            renderOverview(display);
            break;
        case 'requests':
            renderRequests(display);
            break;
        case 'users':
            renderUsers(display);
            break;
        case 'master':
            renderMasterRecords(display);
            break;
        default:
            display.innerHTML = `<h2>View not found</h2>`;
    }
}

// 2. View: Executive Overview
function renderOverview(container) {
    container.innerHTML = `
        <h2 class="page-title">Executive Overview</h2>
        <div class="admin-stats-grid" id="stats-summary">
            <div class="stat-card"><h3>Active Transactions</h3><p class="stat-num" id="total-active">--</p></div>
            <div class="stat-card"><h3>In-Transit</h3><p class="stat-num warning" id="total-transit">--</p></div>
            <div class="stat-card"><h3>Completed (MTD)</h3><p class="stat-num success" id="total-completed">--</p></div>
        </div>
        <div class="overview-section">
            <h3 class="section-subtitle">Departmental Document Load</h3>
            <table class="data-table">
                <thead>
                    <tr><th>Department</th><th>Current Holding</th><th>Pending Receipt</th><th>Status</th></tr>
                </thead>
                <tbody id="dept-load-body">
                    <tr><td colspan="4">Loading analytics...</td></tr>
                </tbody>
            </table>
        </div>`;
    
    fetchOverviewData();
}

// 3. View: Account Requests (The Approval Queue)
function renderRequests(container) {
    container.innerHTML = `
        <h2 class="page-title">Account Requests</h2>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Request Code</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="request-list">
                    <tr><td colspan="5">Checking for new requests...</td></tr>
                </tbody>
            </table>
        </div>`;
    
    fetchPendingRequests();
}

// 4. View: User Management
function renderUsers(container) {
    container.innerHTML = `
        <h2 class="page-title">User Management</h2>
        <table class="data-table">
            <thead>
                <tr><th>User ID</th><th>Name</th><th>Department</th><th>Role</th><th>Status</th></tr>
            </thead>
            <tbody id="user-list">
                <tr><td colspan="5">Loading user directory...</td></tr>
            </tbody>
        </table>`;
    
    fetchUserDirectory();
}

// 5. View: Master Records
function renderMasterRecords(container, searchQuery = "") {
    container.innerHTML = `
        <h2 class="page-title">${searchQuery ? 'Search Results' : 'Master Document Records'}</h2>
        <table class="data-table">
            <thead>
                <tr><th>Tracking No.</th><th>Type</th><th>Current Office</th><th>Originator</th><th>Status</th></tr>
            </thead>
            <tbody id="master-record-list">
                <tr><td colspan="5">Searching database...</td></tr>
            </tbody>
        </table>`;
    
    fetchMasterRecords(searchQuery);
}

// --- DATA FETCHING FUNCTIONS (API CALLS) ---

async function fetchPendingRequests() {
    try {
        const response = await fetch('process/get_requests_api.php');
        const data = await response.json();
        const tbody = document.getElementById('request-list');
        
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding: 2rem;">No pending requests found.</td></tr>';
            return;
        }

        tbody.innerHTML = data.map(req => {
            // Safely format the middle initial/name and extension so it doesn't print "null"
            const middle = req.middle_name ? ` ${req.middle_name} ` : ' ';
            const ext = req.name_extension ? ` ${req.name_extension}` : '';
            const fullName = `${req.first_name}${middle}${req.surname}${ext}`;

            return `
            <tr>
                <td>REQ-${String(req.request_id).padStart(4, '0')}</td>
                <td>${fullName}</td>
                <td>
                    ${req.department_name}<br>
                    <span style="font-size: 0.8rem; color: #64748b; text-transform: capitalize;">Role: ${req.requested_role}</span>
                </td>
                <td id="requested-role">
                    <strong>${req.request_code}<strong>
                </td>
                <td>
                    <button class="btn-approve" onclick="approveUser(${req.request_id})">Accept</button>
                    <button class="btn-reject" onclick="rejectUser(${req.request_id})">Reject</button>
                    <button class="btn-block" onclick="blockUser(${req.request_id})" style="background-color: #dc2626; color: white;">Block</button>
                </td>
            </tr>
            `;
        }).join('');
    } catch (error) {
        console.error("Error loading requests:", error);
    }
}

async function fetchOverviewData() {
    // This calls the departments and current document counts
    try {
        const response = await fetch('process/get_overview_api.php');
        const data = await response.json();
        
        const tbody = document.getElementById('dept-load-body');
        tbody.innerHTML = data.departments.map(dept => `
            <tr onclick="viewDeptDocs('${dept.name}')" style="cursor:pointer">
                <td>${dept.name}</td>
                <td>${dept.holding}</td>
                <td>${dept.pending}</td>
                <td><span class="status-pill ${dept.holding > 20 ? 'orange' : 'blue'}">
                    ${dept.holding > 20 ? 'High Load' : 'Active'}
                </span></td>
            </tr>
        `).join('');
    } catch (e) { console.log("Overview fetch failed", e); }
}

// --- ACTION FUNCTIONS ---

function approveUser(requestId) {
    if(confirm('Are you sure you want to approve this account? This will create their official credentials.')) {
        window.location.href = `approve_request.php?id=${requestId}`;
    }
}

function rejectUser(requestId) {
    // Ask the Admin why they are rejecting the user
    const reason = prompt("Enter the reason for rejection (e.g., 'Request code does not match'):");
    
    // If they click 'Cancel', do nothing. If they click 'OK' (even if empty), proceed.
    if (reason !== null) {
        window.location.href = `process/reject_request.php?id=${requestId}&remarks=${encodeURIComponent(reason)}`;
    }
}

function blockUser(requestId) {
    if(confirm('WARNING: Are you sure you want to completely block this user? They will not be able to request an account again.')) {
        // You can send this to a dedicated block script, or handle it in a master process script
        window.location.href = `process/block_request.php?id=${requestId}`;
    }
}

// Search Bar Event Listener
const searchBar = document.querySelector('.search-group input');
if (searchBar) {
    searchBar.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            const query = this.value.trim();
            const masterBtn = document.querySelectorAll('.nav-item')[3]; // Adjust index if needed
            loadAdminContent('master', masterBtn, query);
        }
    });
}

// Initialize Dashboard on Load
window.onload = () => {
    const firstNavItem = document.querySelector('.nav-item');
    if (firstNavItem) loadAdminContent('overview', firstNavItem);
};