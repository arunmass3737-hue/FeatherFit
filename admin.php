<?php
require_once __DIR__ . '/data/products.php';
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
<body class="admin-body">

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
        <span class="admin-nav-badge" style="background: rgba(242, 109, 109, 0.2); color: #F26D6D; font-weight: 800;">LIVE</span>
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
      <div class="admin-profile-snippet">
        <div class="admin-avatar">AK</div>
        <div class="admin-user-details">
          <div class="admin-user-name">Arun Kumar</div>
          <div class="admin-user-role">Super Admin</div>
        </div>
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

        <!-- 4 KPI Metrics Grid -->
        <div class="admin-kpi-grid">
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
            <div class="kpi-value" id="kpi-aov">₹1,302</div>
            <div class="kpi-trend positive">
              <span>+6.2% high value</span>
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
                  <th>Customer</th>
                  <th>Primary Item</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>Update Status</th>
                  <th>Actions</th>
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
            <h2>Orders & Shipments</h2>
            <p>Track customer purchases, update live delivery statuses, and delete or inspect invoices.</p>
          </div>
        </div>

        <!-- Filter Pills -->
        <div class="admin-table-controls">
          <div class="filter-pill-group">
            <button type="button" class="filter-btn active" data-order-filter="all" onclick="window.FeatherAdmin.setOrderStatusFilter('all')">All Orders</button>
            <button type="button" class="filter-btn" data-order-filter="Pending" onclick="window.FeatherAdmin.setOrderStatusFilter('Pending')">Pending</button>
            <button type="button" class="filter-btn" data-order-filter="Processing" onclick="window.FeatherAdmin.setOrderStatusFilter('Processing')">Processing</button>
            <button type="button" class="filter-btn" data-order-filter="Shipped" onclick="window.FeatherAdmin.setOrderStatusFilter('Shipped')">Shipped</button>
            <button type="button" class="filter-btn" data-order-filter="Delivered" onclick="window.FeatherAdmin.setOrderStatusFilter('Delivered')">Delivered</button>
          </div>
        </div>

        <!-- Orders Table -->
        <div class="admin-table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Primary Item</th>
                <th>Total</th>
                <th>Status</th>
                <th>Update Status</th>
                <th>Actions</th>
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
</body>
</html>
