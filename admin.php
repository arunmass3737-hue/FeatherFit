<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/data/products.php';

$isAdminLoggedIn = !empty($_SESSION['admin_user']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Feather Fits — Admin Command Center | White, Black & Purple</title>
  <meta name="description" content="Feather Fits Luxury E-Commerce Admin Dashboard. Manage products, live orders, revenue analytics, and VIP customers." />

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="assets/images/feather-fits-logo.png" />

  <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

  <!-- Admin Stylesheet -->
  <link rel="stylesheet" href="css/admin.css" />
  <script src="js/lottie.min.js"></script>
  <script src="js/lottie-icons.js"></script>
</head>
<body class="admin-body <?php echo !$isAdminLoggedIn ? 'is-logged-out' : ''; ?>">

<?php if (!$isAdminLoggedIn): ?>
  <!-- ========================================================================
       ADMIN LOGIN RESTRICTED SCREEN
       ======================================================================== -->
  <div class="admin-login-screen">
    <div class="admin-login-card">
      <div class="admin-login-logo">
        <div class="admin-logo-mark">⚡</div>
        <h2>FEATHER FITS</h2>
        <span>ADMIN COMMAND CENTER</span>
      </div>
      
      <p class="admin-login-subtitle">
        Restricted Executive Management Portal.<br />Enter your super admin credentials to proceed.
      </p>

      <div id="admin-login-alert" class="admin-login-alert" style="display: none;"></div>

      <form id="admin-login-form" onsubmit="window.handleAdminLoginSubmit(event)">
        <div class="form-group" style="margin-bottom: 1rem;">
          <label for="admin-login-user">Username or Email</label>
          <input 
            type="text" 
            id="admin-login-user" 
            class="form-control" 
            placeholder="admin or admin@featherfits.com" 
            value="admin" 
            required 
            autocomplete="username"
          />
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
          <label for="admin-login-pass">Password</label>
          <input 
            type="password" 
            id="admin-login-pass" 
            class="form-control" 
            placeholder="••••••••" 
            value="admin123" 
            required 
            autocomplete="current-password"
          />
        </div>

        <button type="submit" id="admin-login-btn" class="btn-cta-crimson" style="width: 100%; height: 48px; font-size: 0.95rem; justify-content: center; gap: 0.6rem;">
          <span class="lottie-icon" data-lottie-icon="storefront" data-lottie-primary="#FFFFFF" data-lottie-secondary="#FFFFFF" style="width: 18px; height: 18px;"></span>
          <span>Authorize & Enter Dashboard</span>
        </button>

        <div class="admin-login-hint">
          <span>⚡ Default Super Admin:</span>
          <code>admin / admin123</code>
        </div>

        <div style="text-align: center; margin-top: 1.5rem;">
          <a href="index.php" style="color: var(--color-coral); font-size: 0.85rem; font-weight: 700; text-decoration: none;">&larr; Return to Customer Storefront</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    window.handleAdminLoginSubmit = function(e) {
      e.preventDefault();
      const user = document.getElementById('admin-login-user').value.trim();
      const pass = document.getElementById('admin-login-pass').value;
      const alertEl = document.getElementById('admin-login-alert');
      const btn = document.getElementById('admin-login-btn');

      btn.disabled = true;
      btn.innerHTML = '<span>Verifying Credentials...</span>';

      fetch('api/auth.php?action=admin_login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username: user, password: pass })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          window.location.reload();
        } else {
          alertEl.textContent = data.error || 'Invalid credentials. Access denied.';
          alertEl.style.display = 'flex';
          btn.disabled = false;
          btn.innerHTML = '<span>Authorize & Enter Dashboard</span>';
        }
      })
      .catch(err => {
        alertEl.textContent = 'Connection error. Please try again.';
        alertEl.style.display = 'flex';
        btn.disabled = false;
        btn.innerHTML = '<span>Authorize & Enter Dashboard</span>';
      });
    };
  </script>

<?php else: ?>

  <!-- Mobile Backdrop Overlay -->
  <div class="admin-sidebar-backdrop" id="sidebar-backdrop" onclick="window.FeatherAdmin.toggleMobileSidebar()"></div>

  <!-- ==========================================================================
       SIDEBAR NAVIGATION
       ========================================================================== -->
  <aside class="admin-sidebar" id="admin-sidebar">
    <div class="admin-sidebar-header">
      <a href="admin.php" class="admin-brand">
        <div class="admin-logo-mark">⚡</div>
        <div class="admin-brand-info">
          <h1>FEATHER FITS</h1>
          <span>ADMIN COMMAND</span>
        </div>
      </a>
    </div>

    <!-- Navigation Menu -->
    <nav class="admin-nav" aria-label="Admin Navigation">
      <span class="admin-nav-heading">Main Modules</span>
      
      <a class="admin-nav-link active" data-section="overview" onclick="window.FeatherAdmin.setSection('overview')">
        <span class="lottie-icon" data-lottie-icon="overview" data-lottie-primary="#FFFFFF" data-lottie-secondary="#F26D6D">
          <svg class="fallback-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="9"></rect>
            <rect x="14" y="3" width="7" height="5"></rect>
            <rect x="14" y="12" width="7" height="9"></rect>
            <rect x="3" y="16" width="7" height="5"></rect>
          </svg>
        </span>
        <span>Overview</span>
      </a>

      <a class="admin-nav-link" data-section="banners" onclick="window.FeatherAdmin.setSection('banners')">
        <span class="lottie-icon" data-lottie-icon="banners" data-lottie-primary="#FFFFFF" data-lottie-secondary="#F26D6D">
          <svg class="fallback-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <circle cx="8.5" cy="8.5" r="1.5"></circle>
            <polyline points="21 15 16 10 5 21"></polyline>
          </svg>
        </span>
        <span>Banners & CMS</span>
        <span class="admin-nav-badge badge-live">LIVE</span>
      </a>

      <a class="admin-nav-link" data-section="products" onclick="window.FeatherAdmin.setSection('products')">
        <span class="lottie-icon" data-lottie-icon="products" data-lottie-primary="#FFFFFF" data-lottie-secondary="#F26D6D">
          <svg class="fallback-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
            <line x1="12" y1="22.08" x2="12" y2="12"></line>
          </svg>
        </span>
        <span>Products</span>
        <span class="admin-nav-badge" id="badge-nav-products">17</span>
      </a>

      <a class="admin-nav-link" data-section="orders" onclick="window.FeatherAdmin.setSection('orders')">
        <span class="lottie-icon" data-lottie-icon="orders" data-lottie-primary="#FFFFFF" data-lottie-secondary="#F26D6D">
          <svg class="fallback-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
          </svg>
        </span>
        <span>Orders</span>
        <span class="admin-nav-badge" id="badge-nav-orders">5</span>
      </a>

      <a class="admin-nav-link" data-section="customers" onclick="window.FeatherAdmin.setSection('customers')">
        <span class="lottie-icon" data-lottie-icon="customers" data-lottie-primary="#FFFFFF" data-lottie-secondary="#F26D6D">
          <svg class="fallback-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
          </svg>
        </span>
        <span>Customers</span>
      </a>

      <a class="admin-nav-link" data-section="analytics" onclick="window.FeatherAdmin.setSection('analytics')">
        <span class="lottie-icon" data-lottie-icon="analytics" data-lottie-primary="#FFFFFF" data-lottie-secondary="#F26D6D">
          <svg class="fallback-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="20" x2="18" y2="10"></line>
            <line x1="12" y1="20" x2="12" y2="4"></line>
            <line x1="6" y1="20" x2="6" y2="14"></line>
          </svg>
        </span>
        <span>Analytics</span>
      </a>

      <span class="admin-nav-heading" style="margin-top: 1rem;">Live Storefront</span>
      <a href="index.php" target="_blank" class="btn-storefront-link">
        <span class="lottie-icon" data-lottie-icon="storefront" data-lottie-primary="#FFFFFF" data-lottie-secondary="#F26D6D" style="width: 18px; height: 18px;">
          <svg class="fallback-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
            <polyline points="15 3 21 3 21 9"></polyline>
            <line x1="10" y1="14" x2="21" y2="3"></line>
          </svg>
        </span>
        <span>Open Storefront</span>
      </a>
    </nav>

    <!-- Sidebar Profile Footer -->
    <div class="admin-sidebar-footer">
      <div class="admin-profile-snippet" style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
          <div class="admin-avatar"><?php echo strtoupper(substr($_SESSION['admin_user']['name'] ?? 'AK', 0, 2)); ?></div>
          <div class="admin-user-details">
            <div class="admin-user-name"><?php echo htmlspecialchars($_SESSION['admin_user']['name'] ?? 'Arun Kumar'); ?></div>
            <div class="admin-user-role"><?php echo htmlspecialchars($_SESSION['admin_user']['role'] ?? 'Super Admin'); ?></div>
          </div>
        </div>
        <button type="button" class="btn-icon-action danger" onclick="window.FeatherAdmin.logout()" title="Sign Out of Admin" aria-label="Sign Out" style="width: 32px; height: 32px; border-radius: var(--radius-xs);">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
        </button>
      </div>
    </div>
  </aside>

  <!-- ==========================================================================
       MAIN VIEWPORT CONTAINER
       ========================================================================== -->
  <main class="admin-main-wrapper">

    <!-- Top Sticky Header Bar -->
    <header class="admin-topbar">
      <div class="admin-topbar-left">
        <button type="button" class="sidebar-toggle-btn" onclick="window.FeatherAdmin.toggleMobileSidebar()" aria-label="Toggle Navigation">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>

        <div class="admin-search-wrap">
          <span class="lottie-icon" data-lottie-icon="search" data-lottie-primary="#A686AD" data-lottie-secondary="#6A1B6E" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px;">
            <svg class="fallback-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </span>
          <input type="text" id="admin-search-input" class="admin-search-input" placeholder="Search orders, products, customers..." />
        </div>
      </div>

      <div class="admin-topbar-right">
        <div class="live-indicator-pill">
          <span class="pulse-circle"></span>
          <span>Store Live &bull; Port 3005</span>
        </div>

        <button type="button" class="btn-primary-purple btn-add-product" onclick="window.FeatherAdmin.openModal('add-product-modal')">
          <span class="lottie-icon" data-lottie-icon="add" data-lottie-primary="#FFFFFF" data-lottie-secondary="#FFFFFF" style="width: 18px; height: 18px;">
            <svg class="fallback-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </span>
          <span>Add Product</span>
        </button>
      </div>
    </header>

    <!-- Content Sections Body -->
    <div class="admin-content-body">

      <!-- ====================================================================
           SECTION 1: OVERVIEW & DASHBOARD METRICS
           ==================================================================== -->
      <section class="admin-section active" id="section-overview">
        <div class="section-header-row">
          <div>
            <h2>Dashboard Overview</h2>
            <p>Welcome back, Arun! Here is a live pulse of your store's performance today.</p>
          </div>
          <button type="button" class="btn-secondary-light" onclick="window.FeatherAdmin.setSection('products')">
            <span>Manage Catalog</span>
          </button>
        </div>

        <!-- 5 KPI Metrics Grid including Live Transit Radar -->
        <div class="admin-kpi-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
          <!-- Total Revenue -->
          <div class="admin-kpi-card">
            <div class="kpi-top">
              <span class="kpi-title">Total Revenue</span>
              <div class="kpi-icon-pill">
                <span class="lottie-icon lottie-icon-lg" data-lottie-icon="analytics" data-lottie-primary="#6A1B6E" data-lottie-secondary="#E0245E">
                  <svg class="fallback-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                  </svg>
                </span>
              </div>
            </div>
            <div class="kpi-value" id="kpi-total-revenue">₹2,14,350</div>
            <div class="kpi-trend positive">
              <span>+18.4% this month</span>
            </div>
          </div>

          <!-- Total Orders -->
          <div class="admin-kpi-card">
            <div class="kpi-top">
              <span class="kpi-title">Total Orders</span>
              <div class="kpi-icon-pill">
                <span class="lottie-icon lottie-icon-lg" data-lottie-icon="orders" data-lottie-primary="#6A1B6E" data-lottie-secondary="#F26D6D">
                  <svg class="fallback-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                  </svg>
                </span>
              </div>
            </div>
            <div class="kpi-value" id="kpi-total-orders">143</div>
            <div class="kpi-trend positive">
              <span>+12 today</span>
            </div>
          </div>

          <!-- Active Fits -->
          <div class="admin-kpi-card">
            <div class="kpi-top">
              <span class="kpi-title">Active Fits</span>
              <div class="kpi-icon-pill">
                <span class="lottie-icon lottie-icon-lg" data-lottie-icon="products" data-lottie-primary="#6A1B6E" data-lottie-secondary="#B5258F">
                  <svg class="fallback-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                  </svg>
                </span>
              </div>
            </div>
            <div class="kpi-value" id="kpi-total-products">17 Fits</div>
            <div class="kpi-trend neutral">
              <span>3 Collections Live</span>
            </div>
          </div>

          <!-- Average Order Value -->
          <div class="admin-kpi-card">
            <div class="kpi-top">
              <span class="kpi-title">Avg Order Value</span>
              <div class="kpi-icon-pill">
                <span class="lottie-icon lottie-icon-lg" data-lottie-icon="analytics" data-lottie-primary="#6A1B6E" data-lottie-secondary="#E0245E">
                  <svg class="fallback-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                  </svg>
                </span>
              </div>
            </div>
            <div class="kpi-value" id="kpi-aov">₹1,302</div>
            <div class="kpi-trend positive">
              <span>+6.2% high value</span>
            </div>
          </div>

          <!-- Problem Alerts Card: Live Transit Radar -->
          <div class="admin-kpi-card" style="border: 1.5px solid #F0D7E7; background: linear-gradient(135deg, #FFFFFF 0%, #FDF7FB 100%); cursor: pointer;" onclick="window.FeatherAdmin.setOrderStatusFilter('delayed'); window.FeatherAdmin.setSection('orders');" title="Click to view Delayed Shipments">
            <div class="kpi-top">
              <span class="kpi-title" style="color: #6A1B6E; font-weight: 800;">⚡ Transit Radar</span>
              <div class="kpi-icon-pill" style="background: #FDF2F4;">
                <span class="lottie-icon lottie-icon-lg" data-lottie-icon="orders" data-lottie-primary="#E0245E" data-lottie-secondary="#DC2626">
                  <svg class="fallback-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="3" width="15" height="13"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                  </svg>
                </span>
              </div>
            </div>
            <div class="kpi-value" style="font-size: 1.25rem; font-weight: 800; display: flex; align-items: baseline; gap: 0.5rem; flex-wrap: wrap;">
              <span>In transit: <span id="kpi-transit-count" style="color: #6A1B6E;">2</span></span>
              <span style="color: #CBD5E1;">|</span>
              <span style="color: #DC2626;">Delayed: <span id="kpi-delayed-count">1</span></span>
            </div>
            <div class="kpi-trend" style="color: #DC2626; font-size: 0.78rem;">
              <span>🚨 Click to inspect delayed shipments &rarr;</span>
            </div>
          </div>
        </div>

        <!-- Charts & Category Distribution Row -->
        <div class="admin-charts-grid">
          <!-- Weekly Revenue Chart -->
          <div class="admin-card">
            <div class="admin-card-header">
              <h3 class="admin-card-title">Weekly Revenue Pulse (₹)</h3>
              <span class="badge badge-purple">Mon - Sun</span>
            </div>
            <div class="chart-bars-wrap" id="chart-bars-wrap">
              <!-- Rendered via JS -->
            </div>
          </div>

          <!-- Department Distribution -->
          <div class="admin-card">
            <div class="admin-card-header">
              <h3 class="admin-card-title">Catalog Distribution</h3>
              <span class="badge badge-purple">3 Depts</span>
            </div>

            <div class="category-progress-list">
              <div class="category-progress-item">
                <div class="cat-label-row">
                  <span>👔 Men's Drops</span>
                  <span id="cat-count-men">9 Fits (53%)</span>
                </div>
                <div class="progress-track">
                  <div class="progress-fill fill-men" id="cat-fill-men" style="width: 53%;"></div>
                </div>
              </div>

              <div class="category-progress-item">
                <div class="cat-label-row">
                  <span>👗 Women's Collection</span>
                  <span id="cat-count-women">4 Fits (27%)</span>
                </div>
                <div class="progress-track">
                  <div class="progress-fill fill-women" id="cat-fill-women" style="width: 27%;"></div>
                </div>
              </div>

              <div class="category-progress-item">
                <div class="cat-label-row">
                  <span>🧒 Kids' Streetwear</span>
                  <span id="cat-count-kids">4 Fits (20%)</span>
                </div>
                <div class="progress-track">
                  <div class="progress-fill fill-kids" id="cat-fill-kids" style="width: 20%;"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Orders Snapshot -->
        <div class="admin-card">
          <div class="admin-card-header">
            <h3 class="admin-card-title">Recent Customer Orders</h3>
            <button class="btn-secondary-light" style="padding: 0.45rem 0.85rem; font-size: 0.8rem;" onclick="window.FeatherAdmin.setSection('orders')">
              <span>View All Orders</span>
            </button>
          </div>

          <div class="admin-table-wrapper">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Customer & Destination</th>
                  <th>Items</th>
                  <th>Total</th>
                  <th>Tracking & Carrier</th>
                  <th>Status</th>
                  <th>Dispatch Actions</th>
                </tr>
              </thead>
              <tbody id="recent-orders-tbody">
                <!-- Rendered by JS -->
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- ====================================================================
           SECTION 2: PRODUCTS INVENTORY MANAGER
           ==================================================================== -->
      <section class="admin-section" id="section-products">
        <div class="section-header-row">
          <div>
            <h2>Products Inventory</h2>
            <p>Manage apparel catalog, edit details, update prices, inspect stock, and add fresh drops.</p>
          </div>

          <button type="button" class="btn-primary-purple" onclick="window.FeatherAdmin.openModal('add-product-modal')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Add New Fit</span>
          </button>
        </div>

        <!-- Controls: Department Filter Pills -->
        <div class="admin-table-controls">
          <div class="filter-pill-group">
            <button type="button" class="filter-btn active" data-dept-filter="all" onclick="window.FeatherAdmin.setProductDeptFilter('all')">All Products</button>
            <button type="button" class="filter-btn" data-dept-filter="men" onclick="window.FeatherAdmin.setProductDeptFilter('men')">👔 Men</button>
            <button type="button" class="filter-btn" data-dept-filter="women" onclick="window.FeatherAdmin.setProductDeptFilter('women')">👗 Women</button>
            <button type="button" class="filter-btn" data-dept-filter="kids" onclick="window.FeatherAdmin.setProductDeptFilter('kids')">🧒 Kids</button>
          </div>
        </div>

        <!-- Products Table -->
        <div class="admin-table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Department</th>
                <th>Category</th>
                <th>Selling Price</th>
                <th>Original</th>
                <th>Stock</th>
                <th>Actions (Edit / Delete / Preview)</th>
              </tr>
            </thead>
            <tbody id="products-table-tbody">
              <!-- Rendered via JS -->
            </tbody>
          </table>
        </div>
      </section>


      <!-- ====================================================================
           SECTION: HERO BANNERS & STOREFRONT CUSTOMIZER
           ==================================================================== -->
      <section class="admin-section" id="section-banners">
        <div class="section-header-row">
          <div>
            <h2>Storefront Banners & Live CMS</h2>
            <p>Upload custom images and change hero banners for Home, Men, Women & Kids with real-time live preview.</p>
          </div>

          <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <button type="button" class="btn-secondary-light" onclick="window.FeatherAdmin.resetBannersToDefault()">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M23 4v6h-6"></path>
                <path d="M1 20v-6h6"></path>
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
              </svg>
              <span>Reset to Defaults</span>
            </button>
            <a href="index.php" target="_blank" class="btn-primary-purple">
              <span>View Live Storefront</span>
            </a>
          </div>
        </div>

        <!-- Banner Department Selector -->
        <div class="admin-table-controls">
          <div class="filter-pill-group">
            <button type="button" class="filter-btn active" data-banner-tab="all" onclick="window.FeatherAdmin.setBannerTab('all')">✨ Home (All Drops)</button>
            <button type="button" class="filter-btn" data-banner-tab="men" onclick="window.FeatherAdmin.setBannerTab('men')">👔 Men's Banner</button>
            <button type="button" class="filter-btn" data-banner-tab="women" onclick="window.FeatherAdmin.setBannerTab('women')">👗 Women's Banner</button>
            <button type="button" class="filter-btn" data-banner-tab="kids" onclick="window.FeatherAdmin.setBannerTab('kids')">🧒 Kids' Banner</button>
            <button type="button" class="filter-btn" data-banner-tab="announcement" onclick="window.FeatherAdmin.setBannerTab('announcement')">📢 Announcement Bar</button>
          </div>
        </div>

        <!-- Active Banner Editor Container -->
        <div id="banner-config-container">
          <!-- Rendered dynamically by js/admin.js -->
        </div>
      </section>

      <!-- ====================================================================
           SECTION 3: ORDERS MANAGEMENT
           ==================================================================== -->
      <section class="admin-section" id="section-orders">
        <div class="section-header-row">
          <div>
            <h2>Orders & Live Dispatch Radar</h2>
            <p>Manage carrier assignments, tracking links, WhatsApp updates, and resolve delayed shipments.</p>
          </div>
          <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <button type="button" class="btn-secondary-light" onclick="window.FeatherAdmin.openModal('bulk-csv-modal')" style="border-color: #6A1B6E; color: #6A1B6E;">
              <span>📥 Bulk CSV Tracking Upload</span>
            </button>
          </div>
        </div>

        <!-- Filter Pills -->
        <div class="admin-table-controls">
          <div class="filter-pill-group" style="flex-wrap: wrap;">
            <button type="button" class="filter-btn active" data-order-filter="all" onclick="window.FeatherAdmin.setOrderStatusFilter('all')">All Orders (<span id="count-filter-all">6</span>)</button>
            <button type="button" class="filter-btn" data-order-filter="shipped" onclick="window.FeatherAdmin.setOrderStatusFilter('shipped')">🚚 In Transit (<span id="count-filter-shipped">2</span>)</button>
            <button type="button" class="filter-btn" data-order-filter="pending" onclick="window.FeatherAdmin.setOrderStatusFilter('pending')">Pending (<span id="count-filter-pending">2</span>)</button>
            <button type="button" class="filter-btn" data-order-filter="delivered" onclick="window.FeatherAdmin.setOrderStatusFilter('delivered')">✓ Delivered (<span id="count-filter-delivered">2</span>)</button>
            <button type="button" class="filter-btn" data-order-filter="no_tracking" onclick="window.FeatherAdmin.setOrderStatusFilter('no_tracking')" style="color: #D97706;">⚠️ Needs Tracking (<span id="count-filter-notracking">1</span>)</button>
            <button type="button" class="filter-btn" data-order-filter="delayed" onclick="window.FeatherAdmin.setOrderStatusFilter('delayed')" style="color: #DC2626;">🚨 Delayed > 5d (<span id="count-filter-delayed">1</span>)</button>
            <button type="button" class="filter-btn" data-order-filter="rto" onclick="window.FeatherAdmin.setOrderStatusFilter('rto')">↩️ RTO (<span id="count-filter-rto">0</span>)</button>
          </div>
        </div>

        <!-- Orders Table -->
        <div class="admin-table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Customer & Destination</th>
                <th>Items & Total</th>
                <th>Tracking & Carrier</th>
                <th>Status & Age</th>
                <th>Dispatch Actions</th>
              </tr>
            </thead>
            <tbody id="all-orders-tbody">
              <!-- Rendered via JS -->
            </tbody>
          </table>
        </div>
      </section>

      <!-- ====================================================================
           SECTION 4: CUSTOMERS DIRECTORY
           ==================================================================== -->
      <section class="admin-section" id="section-customers">
        <div class="section-header-row">
          <div>
            <h2>Customers & VIP Members</h2>
            <p>Registered Feather Fits members, order volumes, and lifetime spend history.</p>
          </div>
        </div>

        <div class="admin-table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Customer</th>
                <th>Phone Number</th>
                <th>City</th>
                <th>Total Orders</th>
                <th>Lifetime Spent</th>
                <th>Member Tier</th>
              </tr>
            </thead>
            <tbody id="customers-table-tbody">
              <!-- Rendered via JS -->
            </tbody>
          </table>
        </div>
      </section>

      <!-- ====================================================================
           SECTION 5: REVENUE & ANALYTICS
           ==================================================================== -->
      <section class="admin-section" id="section-analytics">
        <div class="section-header-row">
          <div>
            <h2>Store Analytics & Performance</h2>
            <p>Deep-dive insights into order volumes, fulfillment carriers, and revenue channels.</p>
          </div>
        </div>

        <div class="admin-charts-grid">
          <div class="admin-card">
            <div class="admin-card-header">
              <h3 class="admin-card-title">Fulfillment Carrier Performance</h3>
              <span class="badge badge-success">99.4% On-Time</span>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem; padding-top: 0.5rem;">
              <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--admin-border); padding-bottom: 0.75rem;">
                <div>
                  <strong style="color: var(--admin-black);">Blue Dart Express</strong>
                  <div style="font-size: 0.78rem; color: var(--admin-text-faint);">Next-Day Metro Air Express</div>
                </div>
                <span class="badge badge-purple">74% Share</span>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--admin-border); padding-bottom: 0.75rem;">
                <div>
                  <strong style="color: var(--admin-black);">Delhivery Surface</strong>
                  <div style="font-size: 0.78rem; color: var(--admin-text-faint);">Pan-India 3-4 Days</div>
                </div>
                <span class="badge badge-info">20% Share</span>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                  <strong style="color: var(--admin-black);">India Post Speed</strong>
                  <div style="font-size: 0.78rem; color: var(--admin-text-faint);">Remote Postal Delivery</div>
                </div>
                <span class="badge badge-warning">6% Share</span>
              </div>
            </div>
          </div>

          <div class="admin-card">
            <div class="admin-card-header">
              <h3 class="admin-card-title">Payment Methods</h3>
              <span class="badge badge-purple">100% Secure</span>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem; padding-top: 0.5rem;">
              <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--admin-border); padding-bottom: 0.75rem;">
                <span style="color: var(--admin-black);">UPI & GPay</span>
                <strong style="color: var(--admin-purple-primary);">68%</strong>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--admin-border); padding-bottom: 0.75rem;">
                <span style="color: var(--admin-black);">Credit / Debit Cards</span>
                <strong style="color: var(--admin-purple-primary);">24%</strong>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: var(--admin-black);">Net Banking</span>
                <strong style="color: var(--admin-purple-primary);">8%</strong>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div>
  </main>

  <!-- ==========================================================================
       MODALS: ADD PRODUCT MODAL
       ========================================================================== -->
  <div class="admin-modal-overlay" id="add-product-modal">
    <div class="admin-modal">
      <div class="admin-modal-header">
        <h3 class="admin-modal-title">Add New Apparel Drop</h3>
        <button type="button" class="admin-modal-close" onclick="window.FeatherAdmin.closeModal('add-product-modal')" aria-label="Close Modal">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>

      <form id="add-product-form">
        <div class="admin-modal-body">
          <div class="form-group">
            <label for="add-prod-name">Product Name *</label>
            <input type="text" id="add-prod-name" class="form-control" placeholder="e.g. Vintage Jujutsu Kaisen Graphic Tee" required />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="add-prod-dept">Department *</label>
              <select id="add-prod-dept" class="form-control" required>
                <option value="men">👔 Men's Collection</option>
                <option value="women">👗 Women's Collection</option>
                <option value="kids">🧒 Kids' Streetwear</option>
              </select>
            </div>

            <div class="form-group">
              <label for="add-prod-category">Category *</label>
              <select id="add-prod-category" class="form-control" required>
                <option value="men-tshirts">T-Shirts & Anime</option>
                <option value="men-shirts">Casual & Linen Shirts</option>
                <option value="men-bottoms">Cargos & Bottoms</option>
                <option value="women-tshirts">Crop Tops & Tees</option>
                <option value="women-shirts">Relaxed Shirts & Blouses</option>
                <option value="kids-tshirts">Kids Anime Graphic Tees</option>
                <option value="kids-sets">Play Sets & Joggers</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="add-prod-price">Selling Price (₹) *</label>
              <input type="number" id="add-prod-price" class="form-control" placeholder="1299" min="100" required />
            </div>

            <div class="form-group">
              <label for="add-prod-orig-price">Original Price (₹)</label>
              <input type="number" id="add-prod-orig-price" class="form-control" placeholder="1899" min="100" />
            </div>
          </div>

          <div class="form-group">
            <label>Product Image Upload *</label>
            <div class="image-upload-wrapper">
              <div class="upload-dropzone" id="add-prod-dropzone">
                <input type="file" id="add-prod-file" accept="image/*" onchange="window.FeatherAdmin.handleProductFileUpload(event, 'add')" />
                <div class="upload-content">
                  <div class="upload-icon-circle">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                      <polyline points="17 8 12 3 7 8"></polyline>
                      <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                  </div>
                  <div class="upload-title">Drop your image here, or <span>browse from computer</span></div>
                  <div class="upload-hint">Supports PNG, JPG, JPEG, WebP (Max 5MB)</div>
                </div>
              </div>

              <!-- Live Preview Box -->
              <div class="upload-preview-container" id="add-prod-preview-box" style="display: none;">
                <img src="" id="add-prod-preview-img" class="upload-preview-thumbnail" alt="Product Preview" />
                <div class="upload-preview-meta">
                  <div class="upload-preview-filename" id="add-prod-preview-name">image.jpg</div>
                  <div class="upload-preview-filesize" id="add-prod-preview-size">Ready for store</div>
                </div>
                <button type="button" class="upload-remove-btn" onclick="window.FeatherAdmin.clearUploadedImage('add')">Remove</button>
              </div>

              <input type="hidden" id="add-prod-img-data" value="assets/images/anime-tee-model-wine.jpg" />

              <div style="font-size: 0.78rem; color: var(--admin-text-faint); margin-top: 0.25rem;">
                Or select an existing store preset:
                <select id="add-prod-img-preset" class="form-control" style="margin-top: 0.35rem; padding: 0.45rem 0.75rem;" onchange="window.FeatherAdmin.selectPresetImage(this.value, 'add')">
                  <option value="assets/images/anime-tee-model-wine.jpg">Wine Red Graphic Tee Model</option>
                  <option value="assets/images/anime-tee-front.jpg">Anime Tee Front</option>
                  <option value="assets/images/demon-slayer-tanjiro-model.jpg">Demon Slayer Tanjiro Model</option>
                  <option value="assets/images/mens-apparel.jpg">Men's Apparel Poster</option>
                  <option value="assets/images/womens-apparel.jpg">Women's Apparel Poster</option>
                  <option value="assets/images/kids-hero-banner.jpg">Kids Hero Poster</option>
                  <option value="assets/images/anime-tee.jpg">Cyberpunk Anime Tee</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="add-prod-badge">Badge Text</label>
            <input type="text" id="add-prod-badge" class="form-control" placeholder="⚡ NEW DROP / 🔥 TRENDING" value="⚡ NEW DROP" />
          </div>
        </div>

        <div class="admin-modal-footer">
          <button type="button" class="btn-secondary-light" onclick="window.FeatherAdmin.closeModal('add-product-modal')">Cancel</button>
          <button type="submit" class="btn-primary-purple">Publish to Store</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ==========================================================================
       MODALS: EDIT / UPDATE PRODUCT MODAL
       ========================================================================== -->
  <div class="admin-modal-overlay" id="edit-product-modal">
    <div class="admin-modal">
      <div class="admin-modal-header">
        <h3 class="admin-modal-title">Edit & Update Fit Details</h3>
        <button type="button" class="admin-modal-close" onclick="window.FeatherAdmin.closeModal('edit-product-modal')" aria-label="Close Modal">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>

      <form id="edit-product-form">
        <input type="hidden" id="edit-prod-id" />
        <div class="admin-modal-body">
          <div class="form-group">
            <label for="edit-prod-name">Product Name *</label>
            <input type="text" id="edit-prod-name" class="form-control" required />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="edit-prod-dept">Department *</label>
              <select id="edit-prod-dept" class="form-control" required>
                <option value="men">👔 Men's Collection</option>
                <option value="women">👗 Women's Collection</option>
                <option value="kids">🧒 Kids' Streetwear</option>
              </select>
            </div>

            <div class="form-group">
              <label for="edit-prod-category">Category *</label>
              <select id="edit-prod-category" class="form-control" required>
                <option value="men-tshirts">T-Shirts & Anime</option>
                <option value="men-shirts">Casual & Linen Shirts</option>
                <option value="men-bottoms">Cargos & Bottoms</option>
                <option value="women-tshirts">Crop Tops & Tees</option>
                <option value="women-shirts">Relaxed Shirts & Blouses</option>
                <option value="kids-tshirts">Kids Anime Graphic Tees</option>
                <option value="kids-sets">Play Sets & Joggers</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="edit-prod-price">Selling Price (₹) *</label>
              <input type="number" id="edit-prod-price" class="form-control" min="100" required />
            </div>

            <div class="form-group">
              <label for="edit-prod-orig-price">Original Price (₹)</label>
              <input type="number" id="edit-prod-orig-price" class="form-control" min="100" />
            </div>
          </div>

          <div class="form-group">
            <label for="edit-prod-badge">Badge Text</label>
            <input type="text" id="edit-prod-badge" class="form-control" placeholder="⚡ NEW DROP / 🔥 TRENDING" />
          </div>

          <div class="form-group">
            <label>Update Product Image *</label>
            <div class="image-upload-wrapper">
              <div class="upload-dropzone" id="edit-prod-dropzone">
                <input type="file" id="edit-prod-file" accept="image/*" onchange="window.FeatherAdmin.handleProductFileUpload(event, 'edit')" />
                <div class="upload-content">
                  <div class="upload-icon-circle">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                      <polyline points="17 8 12 3 7 8"></polyline>
                      <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                  </div>
                  <div class="upload-title">Drop new image, or <span>browse from computer</span></div>
                  <div class="upload-hint">Upload any image to replace current product photo</div>
                </div>
              </div>

              <!-- Live Preview Box -->
              <div class="upload-preview-container" id="edit-prod-preview-box">
                <img src="" id="edit-prod-preview-img" class="upload-preview-thumbnail" alt="Product Preview" />
                <div class="upload-preview-meta">
                  <div class="upload-preview-filename" id="edit-prod-preview-name">current-image.jpg</div>
                  <div class="upload-preview-filesize" id="edit-prod-preview-size">Active Store Image</div>
                </div>
                <button type="button" class="upload-remove-btn" onclick="window.FeatherAdmin.clearUploadedImage('edit')">Reset</button>
              </div>

              <input type="hidden" id="edit-prod-img-data" value="" />

              <div style="font-size: 0.78rem; color: var(--admin-text-faint); margin-top: 0.25rem;">
                Or select an existing store preset:
                <select id="edit-prod-img-preset" class="form-control" style="margin-top: 0.35rem; padding: 0.45rem 0.75rem;" onchange="window.FeatherAdmin.selectPresetImage(this.value, 'edit')">
                  <option value="assets/images/anime-tee-model-wine.jpg">Wine Red Graphic Tee Model</option>
                  <option value="assets/images/anime-tee-front.jpg">Anime Tee Front</option>
                  <option value="assets/images/demon-slayer-tanjiro-model.jpg">Demon Slayer Tanjiro Model</option>
                  <option value="assets/images/mens-apparel.jpg">Men's Apparel Poster</option>
                  <option value="assets/images/womens-apparel.jpg">Women's Apparel Poster</option>
                  <option value="assets/images/kids-hero-banner.jpg">Kids Hero Poster</option>
                  <option value="assets/images/anime-tee.jpg">Cyberpunk Anime Tee</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="admin-modal-footer">
          <button type="button" class="btn-secondary-light" onclick="window.FeatherAdmin.closeModal('edit-product-modal')">Cancel</button>
          <button type="submit" class="btn-primary-purple">Save Changes</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ==========================================================================
       MODALS: VIEW ORDER DETAILS MODAL
       ========================================================================== -->
  <div class="admin-modal-overlay" id="order-details-modal">
    <div class="admin-modal">
      <div class="admin-modal-header">
        <h3 class="admin-modal-title" id="order-details-modal-title">Order Details</h3>
        <button type="button" class="admin-modal-close" onclick="window.FeatherAdmin.closeModal('order-details-modal')" aria-label="Close Modal">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      <div class="admin-modal-body" id="order-details-modal-body">
        <!-- Rendered via JS -->
      </div>
      <div class="admin-modal-footer">
        <button type="button" class="btn-secondary-light" onclick="window.FeatherAdmin.closeModal('order-details-modal')">Close</button>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="data/products.js"></script>
  <script src="js/admin.js"></script>

  <!-- ==========================================================================
       MODAL 1: ORDER SHIPPING, DISPATCH & TIMELINE MODAL
       ========================================================================== -->
  <div class="admin-modal-overlay" id="order-shipping-modal">
    <div class="admin-modal" style="max-width: 680px;">
      <div class="admin-modal-header">
        <div>
          <h3 class="admin-modal-title" id="ship-modal-title">Order Dispatch & Live Tracking</h3>
          <span style="font-size: 0.78rem; color: #724E79;" id="ship-modal-subtitle">Configure carrier details and activity milestones</span>
        </div>
        <button type="button" class="admin-modal-close" onclick="window.FeatherAdmin.closeModal('order-shipping-modal')">&times;</button>
      </div>

      <div class="admin-modal-body" style="padding: 1.5rem;">
        
        <!-- Order Summary Strip -->
        <div id="ship-modal-order-summary" style="background: #FAF0F6; border-radius: var(--radius-sm); padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
          <!-- Injected via JS -->
        </div>

        <!-- 1. Live Shipping Box -->
        <div class="shipping-box">
          <div class="shipping-box-header">
            <div>
              <h4 style="font-size: 1.05rem; font-weight: 800; color: #3B0F4A; margin: 0;">Courier & Shipping Configuration</h4>
              <span style="font-size: 0.75rem; color: #724E79;">Saves carrier, tracking number, and auto-generates tracking quicklinks</span>
            </div>
            <span class="badge badge-purple" id="ship-modal-status-badge">SHIPPED</span>
          </div>

          <form id="shipping-details-form" onsubmit="window.FeatherAdmin.handleSaveShippingDetails(event)">
            <input type="hidden" id="ship-order-id" />

            <div class="form-row" style="margin-bottom: 1rem;">
              <div class="form-group">
                <label for="ship-carrier-select">Select Carrier Partner</label>
                <select id="ship-carrier-select" class="form-control" onchange="window.FeatherAdmin.handleCarrierChange(this.value)">
                  <option value="Blue Dart Express">Blue Dart Express</option>
                  <option value="Delhivery">Delhivery</option>
                  <option value="India Post Speed">India Post Speed Post</option>
                  <option value="DTDC Express">DTDC Express</option>
                  <option value="Shadowfax">Shadowfax</option>
                  <option value="Xpressbees">Xpressbees</option>
                  <option value="Other Courier">Other Courier</option>
                </select>
              </div>

              <div class="form-group">
                <label for="ship-tracking-number">AWB / Tracking Number</label>
                <input type="text" id="ship-tracking-number" class="form-control" placeholder="e.g. BD-9402849182" required />
              </div>
            </div>

            <div class="form-row" style="margin-bottom: 1.25rem;">
              <div class="form-group">
                <label for="ship-est-delivery">Estimated Delivery Date</label>
                <input type="date" id="ship-est-delivery" class="form-control" />
              </div>
              <div class="form-group">
                <label for="ship-update-status">Change Order Status</label>
                <select id="ship-update-status" class="form-control">
                  <option value="Shipped">Shipped (In Transit)</option>
                  <option value="Out for Delivery">Out for Delivery</option>
                  <option value="Delivered">Delivered</option>
                  <option value="RTO">Return to Origin (RTO)</option>
                </select>
              </div>
            </div>

            <button type="submit" id="ship-save-btn" class="btn-cta-crimson" style="width: 100%; height: 44px; justify-content: center; font-size: 0.92rem;">
              <span>💾 Save & Mark as Shipped</span>
            </button>
          </form>

          <!-- Action Buttons Strip -->
          <div class="shipping-actions-bar" id="ship-actions-bar" style="display: none;">
            <a id="btn-track-parcel" href="#" target="_blank" class="btn-primary" style="height: 38px; font-size: 0.82rem; background: #6A1B6E; text-decoration: none;">
              🚀 Track Parcel in Carrier Site
            </a>

            <button type="button" class="btn-secondary-light" style="height: 38px; font-size: 0.82rem;" onclick="window.FeatherAdmin.copyCustomerTrackingLink()">
              📋 Copy Link for Customer
            </button>

            <a id="btn-whatsapp-notify" href="#" target="_blank" class="btn-whatsapp-action">
              💬 Send Tracking on WhatsApp
            </a>
          </div>
        </div>

        <!-- 2. Status History (Timeline) -->
        <div>
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <h4 style="font-size: 1.05rem; font-weight: 800; color: #3B0F4A; margin: 0;">Status History & Activity Log</h4>
          </div>

          <div class="timeline-stepper" id="ship-modal-timeline">
            <!-- Rendered via JS -->
          </div>
        </div>

      </div>

      <div class="admin-modal-footer">
        <button type="button" class="btn-secondary-light" onclick="window.FeatherAdmin.closeModal('order-shipping-modal')">Close</button>
      </div>
    </div>
  </div>

  <!-- ==========================================================================
       MODAL 2: BULK CSV TRACKING UPLOAD
       ========================================================================== -->
  <div class="admin-modal-overlay" id="bulk-csv-modal">
    <div class="admin-modal" style="max-width: 580px;">
      <div class="admin-modal-header">
        <div>
          <h3 class="admin-modal-title">Bulk CSV Tracking Upload</h3>
          <span style="font-size: 0.78rem; color: #724E79;">Update 20+ orders simultaneously with tracking numbers</span>
        </div>
        <button type="button" class="admin-modal-close" onclick="window.FeatherAdmin.closeModal('bulk-csv-modal')">&times;</button>
      </div>

      <div class="admin-modal-body" style="padding: 1.5rem;">
        <p style="font-size: 0.85rem; color: #724E79; line-height: 1.45; margin-bottom: 1rem;">
          Paste your CSV lines below or upload a .csv file. The columns must be: <br />
          <code style="background: #FDF2F4; color: #E0245E; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700;">order_id, carrier, tracking_number</code>
        </p>

        <div style="margin-bottom: 1rem;">
          <textarea id="bulk-csv-input" class="form-control" rows="6" placeholder="FF-90214, Blue Dart, BD-9402849182&#10;FF-90213, Delhivery, DEL-8401928&#10;FF-90210, India Post, IPS-77192830" style="font-family: monospace; font-size: 0.85rem;"></textarea>
        </div>

        <div id="bulk-csv-result" style="display: none; padding: 0.75rem 1rem; border-radius: var(--radius-sm); font-size: 0.85rem; font-weight: 700; margin-bottom: 1rem;"></div>

        <div style="background: #FAF0F6; border-radius: var(--radius-sm); padding: 0.75rem 1rem; font-size: 0.78rem; color: #3B0F4A;">
          💡 <strong>Tip:</strong> Daily dispatches can be exported directly from Blue Dart or Delhivery Excel sheet and pasted here in 1 click!
        </div>
      </div>

      <div class="admin-modal-footer">
        <button type="button" class="btn-secondary-light" onclick="window.FeatherAdmin.closeModal('bulk-csv-modal')">Cancel</button>
        <button type="button" id="btn-process-csv" class="btn-cta-crimson" onclick="window.FeatherAdmin.processBulkCsv()">Apply Bulk Updates →</button>
      </div>
    </div>
  </div>


  <!-- ==========================================================================
       FIXED MOBILE BOTTOM NAVIGATION BAR FOR ADMIN COMMAND
       ========================================================================== -->
  <nav class="admin-mobile-bottom-nav" aria-label="Admin Mobile Navigation">
    <button type="button" class="admin-mobile-nav-item active" data-admin-nav="overview" onclick="window.FeatherAdmin.setSection('overview')">
      <span class="lottie-icon" data-lottie-icon="overview" data-lottie-primary="#6A1B6E" data-lottie-secondary="#E0245E" style="width: 20px; height: 20px;">
        <svg class="fallback-svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="7" height="9"></rect>
          <rect x="14" y="3" width="7" height="5"></rect>
          <rect x="14" y="12" width="7" height="9"></rect>
          <rect x="3" y="16" width="7" height="5"></rect>
        </svg>
      </span>
      <span>Overview</span>
    </button>

    <button type="button" class="admin-mobile-nav-item" data-admin-nav="orders" onclick="window.FeatherAdmin.setSection('orders')">
      <span class="lottie-icon" data-lottie-icon="orders" data-lottie-primary="#6A1B6E" data-lottie-secondary="#E0245E" style="width: 20px; height: 20px;">
        <svg class="fallback-svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="9" cy="21" r="1"></circle>
          <circle cx="20" cy="21" r="1"></circle>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
      </span>
      <span class="admin-mobile-badge" id="mobile-badge-orders">6</span>
      <span>Orders</span>
    </button>

    <button type="button" class="admin-mobile-nav-item" data-admin-nav="products" onclick="window.FeatherAdmin.setSection('products')">
      <span class="lottie-icon" data-lottie-icon="products" data-lottie-primary="#6A1B6E" data-lottie-secondary="#E0245E" style="width: 20px; height: 20px;">
        <svg class="fallback-svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
        </svg>
      </span>
      <span class="admin-mobile-badge" id="mobile-badge-products">17</span>
      <span>Catalog</span>
    </button>

    <button type="button" class="admin-mobile-nav-item" data-admin-nav="banners" onclick="window.FeatherAdmin.setSection('banners')">
      <span class="lottie-icon" data-lottie-icon="banners" data-lottie-primary="#6A1B6E" data-lottie-secondary="#E0245E" style="width: 20px; height: 20px;">
        <svg class="fallback-svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
          <circle cx="8.5" cy="8.5" r="1.5"></circle>
          <polyline points="21 15 16 10 5 21"></polyline>
        </svg>
      </span>
      <span>Banners</span>
    </button>

    <button type="button" class="admin-mobile-nav-item" onclick="window.FeatherAdmin.toggleMobileSidebar()">
      <span class="lottie-icon" data-lottie-icon="customers" data-lottie-primary="#6A1B6E" data-lottie-secondary="#B5258F" style="width: 20px; height: 20px;">
        <svg class="fallback-svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
      </span>
      <span>More</span>
    </button>
  </nav>

<?php endif; ?>
</body>
</html>
