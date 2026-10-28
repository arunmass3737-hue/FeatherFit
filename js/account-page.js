/**
 * FEATHER FITS — EXECUTIVE MEMBER DASHBOARD & AUTH SYSTEM
 * Controls account.html:
 * - Dedicated User Authentication Portal (Sign In / Sign Up / One-click Demo Users)
 * - True Executive Dashboard with Left Navigation Sidebar + Rich Main Workspace
 * - Overview Hub with Live Package Spotlight & KPI Cards
 * - Order Tracking & Milestone Timeline with Blue Dart integration
 * - Saved Delivery Addresses management
 * - Wishlist direct grid with Add-to-Cart
 * - Feather Coins wallet & transaction history
 * - Smart Size & Fit aesthetic auto-sync
 * - Profile details editing & Notification preferences
 */

(function () {
  "use strict";

  const STORAGE_KEYS = {
    CART: "ff_cart_v1",
    WISHLIST: "ff_wishlist_v1",
    USER: "ff_user_v1",
    ORDERS: "ff_orders_v1",
  };

  const DEMO_USERS = {
    arun: {
      id: "usr_feather_091",
      email: "arun.kumar@featherfits.com",
      firstName: "Arun",
      lastName: "Kumar",
      phone: "98765 43210",
      memberSince: "2024",
      tier: "Feather VIP Member",
      coins: 450,
      preferredSize: "L",
      preferredFit: "Oversized Streetwear",
      whatsappUpdates: true,
      smsAlerts: true,
      addresses: [
        {
          id: "addr_1",
          tag: "Home",
          isDefault: true,
          name: "Arun Kumar",
          phone: "+91 98765 43210",
          line1: "42, Gandhi Road, T. Nagar",
          city: "Chennai",
          state: "Tamil Nadu",
          postalCode: "600017",
        },
        {
          id: "addr_2",
          tag: "Office",
          isDefault: false,
          name: "Arun Kumar",
          phone: "+91 98765 43210",
          line1: "Unit 402, RMZ Millenia Business Park, Perungudi",
          city: "Chennai",
          state: "Tamil Nadu",
          postalCode: "600096",
        },
      ],
    },
    priya: {
      id: "usr_feather_108",
      email: "priya.sharma@featherfits.com",
      firstName: "Priya",
      lastName: "Sharma",
      phone: "98401 23456",
      memberSince: "2025",
      tier: "Gold Street Member",
      coins: 600,
      preferredSize: "M",
      preferredFit: "Relaxed Boxy Fit",
      whatsappUpdates: true,
      smsAlerts: true,
      addresses: [
        {
          id: "addr_p1",
          tag: "Home",
          isDefault: true,
          name: "Priya Sharma",
          phone: "+91 98401 23456",
          line1: "Flat 3B, Ceebros Heights, Velachery",
          city: "Chennai",
          state: "Tamil Nadu",
          postalCode: "600042",
        }
      ]
    }
  };

  // State
  let currentUser = JSON.parse(localStorage.getItem(STORAGE_KEYS.USER) || "null") || DEMO_USERS.arun;
  let userOrders = JSON.parse(localStorage.getItem(STORAGE_KEYS.ORDERS) || "[]");
  let wishlist = JSON.parse(localStorage.getItem(STORAGE_KEYS.WISHLIST) || "[]");

  // Ensure initial sample order exists if none present
  if (userOrders.length === 0) {
    userOrders.push({
      orderNumber: "FF-89214",
      createdAt: new Date(Date.now() - 36 * 3600 * 1000).toISOString(),
      status: "SHIPPED",
      carrier: "Blue Dart Express",
      trackingNumber: "BD-9402849182",
      estimatedDelivery: "In 2 Days",
      items: [
        {
          id: "ff-anime-01",
          name: "Demon Slayer Tanjiro Oversized Graphic Tee",
          color: "Obsidian Black",
          size: "L",
          price: 1299.0,
          quantity: 1,
          image: "assets/images/anime-tee-model-front.jpg",
        },
      ],
      shippingAddress: {
        firstName: "Arun",
        lastName: "Kumar",
        line1: "42, Gandhi Road, T. Nagar",
        city: "Chennai",
        state: "Tamil Nadu",
        postalCode: "600017",
      },
      subtotal: 1299.0,
      shippingFee: 0.0,
      tax: 64.95,
      total: 1363.95,
    });
    localStorage.setItem(STORAGE_KEYS.ORDERS, JSON.stringify(userOrders));
  }

  // Active state
  let activeTab = "overview";
  let activeTrackingNum = userOrders[0]?.orderNumber || "FF-89214";
  let showAddAddress = false;
  let authMode = "signin"; // 'signin' or 'signup'

  function initAccountPage() {
    const root = document.getElementById("account-page-root");
    if (!root) return;

    // Check URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get("tab");
    if (tabParam && ["overview", "tracking", "wishlist", "addresses", "coins", "fit", "profile", "settings"].includes(tabParam)) {
      activeTab = tabParam;
    }
    const orderParam = urlParams.get("order");
    if (orderParam) {
      activeTrackingNum = orderParam;
      activeTab = "tracking";
    }

    renderView();
  }

  function saveUser() {
    localStorage.setItem(STORAGE_KEYS.USER, JSON.stringify(currentUser));
  }

  function renderView() {
    const root = document.getElementById("account-page-root");
    if (!root) return;

    // If user is logged out, render the dedicated authentication screen
    if (!currentUser) {
      renderAuthView(root);
      return;
    }

    // Render full executive dashboard
    renderDashboard(root);
  }

  // -------------------------------------------------------------
  // DEDICATED USER AUTHENTICATION VIEW ("user thaniya aa vanum")
  // -------------------------------------------------------------
  function renderAuthView(root) {
    root.innerHTML = `
      <div class="account-auth-container">
        
        <!-- Left Visual Side: Brand & VIP Perks -->
        <div class="auth-visual-side">
          <div class="auth-visual-header">
            <img src="assets/images/feather-fits-logo.png" alt="Feather Fits" class="auth-brand-logo" />
            <h1 class="auth-visual-title">Confidence Fits You. <span>Join The VIP Club.</span></h1>
            <p class="auth-visual-subtitle">
              Sign in to manage your drops, track live Blue Dart shipments, redeem Feather Coins, and sync your smart size profile across devices.
            </p>
          </div>

          <div class="auth-perks-list">
            <div class="auth-perk-item">
              <div class="auth-perk-icon">🪙</div>
              <div class="auth-perk-text">
                <strong>+200 Feather Coins Welcome Bonus</strong>
                <span>Instant ₹200 store credit applied on signup</span>
              </div>
            </div>
            <div class="auth-perk-item">
              <div class="auth-perk-icon">🚚</div>
              <div class="auth-perk-text">
                <strong>Live Package Radar & Milestone Updates</strong>
                <span>Real-time dispatch tracking with Blue Dart courier integration</span>
              </div>
            </div>
            <div class="auth-perk-item">
              <div class="auth-perk-icon">📏</div>
              <div class="auth-perk-text">
                <strong>1-Tap Smart Size Profile</strong>
                <span>Auto-selects your tailored size across Men, Women & Kids drops</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Form Side: Login / Register / Demo Switcher -->
        <div class="auth-form-side">
          
          <!-- Tab Toggle (Sign In / Create Account) -->
          <div class="auth-tabs-toggle">
            <button 
              type="button" 
              class="auth-tab-pill ${authMode === 'signin' ? 'active' : ''}" 
              onclick="window.FeatherAccount.setAuthMode('signin')"
            >
              Sign In to Account
            </button>
            <button 
              type="button" 
              class="auth-tab-pill ${authMode === 'signup' ? 'active' : ''}" 
              onclick="window.FeatherAccount.setAuthMode('signup')"
            >
              Create New Account
            </button>
          </div>

          <!-- Quick 1-Click Demo Profiles -->
          <div class="auth-demo-accounts-box">
            <div class="auth-demo-accounts-title">
              <span>⚡ One-Click Instant Sign-In:</span>
            </div>
            <div class="auth-demo-chips">
              <button type="button" class="auth-demo-chip" onclick="window.FeatherAccount.loginDemo('arun')">
                <span>👤 Arun Kumar (VIP • 450 Coins)</span>
              </button>
              <button type="button" class="auth-demo-chip" onclick="window.FeatherAccount.loginDemo('priya')">
                <span>👤 Priya Sharma (Gold • 600 Coins)</span>
              </button>
            </div>
          </div>

          ${authMode === 'signin' ? `
            <!-- Sign In Form -->
            <form onsubmit="window.FeatherAccount.handleLogin(event)">
              <div class="auth-form-group">
                <label class="auth-form-label">Email or Mobile Number</label>
                <input 
                  type="text" 
                  id="auth-login-email" 
                  class="auth-form-input" 
                  placeholder="name@featherfits.com or 9876543210" 
                  value="arun.kumar@featherfits.com" 
                  required 
                />
              </div>

              <div class="auth-form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                  <label class="auth-form-label" style="margin-bottom: 0;">Password</label>
                  <a href="#" onclick="alert('Demo password is auto-filled! Click Sign In.'); return false;" style="font-size: 0.78rem; color: var(--color-secondary); font-weight: 700;">Forgot?</a>
                </div>
                <input 
                  type="password" 
                  id="auth-login-pass" 
                  class="auth-form-input" 
                  placeholder="••••••••" 
                  value="confidence2026" 
                  required 
                />
              </div>

              <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem;">
                <input type="checkbox" id="auth-remember" checked style="accent-color: var(--color-secondary); cursor: pointer;" />
                <label for="auth-remember" style="font-size: 0.82rem; color: var(--color-text-muted); cursor: pointer;">Remember this browser session</label>
              </div>

              <button type="submit" class="auth-submit-btn">
                <span>Sign In to Member Dashboard →</span>
              </button>
            </form>
          ` : `
            <!-- Create Account Form -->
            <form onsubmit="window.FeatherAccount.handleRegister(event)">
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                <div>
                  <label class="auth-form-label">First Name</label>
                  <input type="text" id="reg-fname" class="auth-form-input" placeholder="Arun" required />
                </div>
                <div>
                  <label class="auth-form-label">Last Name</label>
                  <input type="text" id="reg-lname" class="auth-form-input" placeholder="Kumar" required />
                </div>
              </div>

              <div class="auth-form-group">
                <label class="auth-form-label">Email Address</label>
                <input type="email" id="reg-email" class="auth-form-input" placeholder="arun@example.com" required />
              </div>

              <div class="auth-form-group">
                <label class="auth-form-label">Mobile Number</label>
                <input type="tel" id="reg-phone" class="auth-form-input" placeholder="98765 43210" required />
              </div>

              <div class="auth-form-group">
                <label class="auth-form-label">Preferred Standard Size</label>
                <select id="reg-size" class="auth-form-input" style="height: 48px;">
                  <option value="S">S — Small</option>
                  <option value="M">M — Medium</option>
                  <option value="L" selected>L — Large (Standard Relaxed)</option>
                  <option value="XL">XL — Extra Large</option>
                  <option value="XXL">XXL — Oversized</option>
                </select>
              </div>

              <div class="auth-form-group">
                <label class="auth-form-label">Password</label>
                <input type="password" id="reg-pass" class="auth-form-input" placeholder="At least 6 characters" required />
              </div>

              <button type="submit" class="auth-submit-btn">
                <span>Create VIP Account (+200 Coins) →</span>
              </button>
            </form>
          `}

        </div>
      </div>
    `;
  }

  // -------------------------------------------------------------
  // DEDICATED EXECUTIVE DASHBOARD ("dash board mari erknum")
  // -------------------------------------------------------------
  function renderDashboard(root) {
    const u = currentUser;
    const activeOrder = userOrders.find((o) => o.orderNumber.toUpperCase() === activeTrackingNum.toUpperCase()) || userOrders[0];
    const userWishlist = JSON.parse(localStorage.getItem(STORAGE_KEYS.WISHLIST) || "[]");

    root.innerHTML = `
      <div class="dash-app-layout">
        
        <!-- ============================================== -->
        <!-- LEFT SIDEBAR: User Card & Navigation Menu     -->
        <!-- ============================================== -->
        <aside class="dash-sidebar" aria-label="Dashboard Navigation">
          
          <!-- User Profile Card in Sidebar -->
          <div class="dash-user-card">
            <div class="dash-avatar-ring">
              ${(u.firstName?.[0] || "A") + (u.lastName?.[0] || "K")}
            </div>
            <div class="dash-user-meta">
              <div class="dash-user-name">${u.firstName} ${u.lastName}</div>
              <span class="dash-vip-tag">★ ${u.tier || "VIP Member"}</span>
              <div class="dash-user-coins-pill">
                <span>🪙 <strong>₹${u.coins || 450}</strong> Coins Available</span>
              </div>
            </div>
          </div>

          <!-- Main Navigation Links with Badges -->
          <nav class="dash-nav-menu" role="navigation">
            
            <button 
              type="button" 
              class="dash-nav-item ${activeTab === 'overview' ? 'active' : ''}" 
              onclick="window.FeatherAccount.switchTab('overview')"
            >
              <div class="dash-nav-item-left">
                <span class="dash-nav-item-icon">📊</span>
                <span>Dashboard Hub</span>
              </div>
            </button>

            <button 
              type="button" 
              class="dash-nav-item ${activeTab === 'tracking' ? 'active' : ''}" 
              onclick="window.FeatherAccount.switchTab('tracking')"
            >
              <div class="dash-nav-item-left">
                <span class="dash-nav-item-icon">📦</span>
                <span>Orders & Live Tracking</span>
              </div>
              <span class="dash-nav-badge">${userOrders.length}</span>
            </button>

            <button 
              type="button" 
              class="dash-nav-item ${activeTab === 'wishlist' ? 'active' : ''}" 
              onclick="window.FeatherAccount.switchTab('wishlist')"
            >
              <div class="dash-nav-item-left">
                <span class="dash-nav-item-icon">💖</span>
                <span>Saved Wishlist</span>
              </div>
              <span class="dash-nav-badge">${userWishlist.length}</span>
            </button>

            <button 
              type="button" 
              class="dash-nav-item ${activeTab === 'addresses' ? 'active' : ''}" 
              onclick="window.FeatherAccount.switchTab('addresses')"
            >
              <div class="dash-nav-item-left">
                <span class="dash-nav-item-icon">📍</span>
                <span>Saved Addresses</span>
              </div>
              <span class="dash-nav-badge">${(u.addresses || []).length}</span>
            </button>

            <button 
              type="button" 
              class="dash-nav-item ${activeTab === 'coins' ? 'active' : ''}" 
              onclick="window.FeatherAccount.switchTab('coins')"
            >
              <div class="dash-nav-item-left">
                <span class="dash-nav-item-icon">🪙</span>
                <span>Coins & Wallet</span>
              </div>
              <span class="dash-nav-badge">₹${u.coins || 450}</span>
            </button>

            <button 
              type="button" 
              class="dash-nav-item ${activeTab === 'fit' ? 'active' : ''}" 
              onclick="window.FeatherAccount.switchTab('fit')"
            >
              <div class="dash-nav-item-left">
                <span class="dash-nav-item-icon">📏</span>
                <span>Smart Size & Fit</span>
              </div>
              <span class="dash-nav-badge">${u.preferredSize || 'L'}</span>
            </button>

            <button 
              type="button" 
              class="dash-nav-item ${activeTab === 'profile' ? 'active' : ''}" 
              onclick="window.FeatherAccount.switchTab('profile')"
            >
              <div class="dash-nav-item-left">
                <span class="dash-nav-item-icon">👤</span>
                <span>Profile & Security</span>
              </div>
            </button>

            <button 
              type="button" 
              class="dash-nav-item ${activeTab === 'settings' ? 'active' : ''}" 
              onclick="window.FeatherAccount.switchTab('settings')"
            >
              <div class="dash-nav-item-left">
                <span class="dash-nav-item-icon">🔔</span>
                <span>WhatsApp & Alerts</span>
              </div>
            </button>

          </nav>

          <!-- Sidebar Footer: Switch User / Sign Out -->
          <div class="dash-sidebar-footer">
            <button type="button" class="dash-signout-btn" onclick="window.FeatherAccount.signOut()">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
              </svg>
              <span>Switch User / Sign Out</span>
            </button>
          </div>

        </aside>

        <!-- ============================================== -->
        <!-- RIGHT MAIN CONTENT PANEL                      -->
        <!-- ============================================== -->
        <main class="dash-main-content">
          
          <!-- Top Greeting & Context Bar -->
          <div class="dash-header-bar">
            <div>
              <h1 class="dash-header-title">
                ${activeTab === 'overview' ? `Welcome back, ${u.firstName}! 👋` : getTabTitle(activeTab)}
              </h1>
              <p class="dash-header-subtitle">
                ${getTabSubtitle(activeTab, u)}
              </p>
            </div>

            <div class="dash-header-actions">
              <a href="index.html#catalog-section" class="btn-primary" style="height: 44px; padding: 0 1.25rem;">
                <span>Shop New Drops →</span>
              </a>
            </div>
          </div>

          <!-- Dynamic Tab Content Panel -->
          <div class="dashboard-main-card">
            <div class="dashboard-tab-panel">
              ${renderTabContent(activeTab)}
            </div>
          </div>

        </main>

      </div>
    `;
  }

  function getTabTitle(tab) {
    const titles = {
      overview: "Member Dashboard Overview",
      tracking: "My Orders & Live Tracking",
      wishlist: "Saved Wishlist",
      addresses: "Delivery Addresses",
      coins: "Feather Coins & Wallet Rewards",
      fit: "Smart Fit & Size Profile",
      profile: "Personal Information & Account",
      settings: "WhatsApp & SMS Notifications",
    };
    return titles[tab] || "Member Dashboard";
  }

  function getTabSubtitle(tab, u) {
    const subtitles = {
      overview: `VIP Member #${u.id} • Registered ${u.memberSince || '2024'} • Everything at a glance.`,
      tracking: "Track real-time Blue Dart courier milestones and access GST tax invoices.",
      wishlist: "Your curated list of premium streetwear, anime graphics, and casual fits.",
      addresses: "Manage default shipping addresses for fast 1-tap Blue Dart deliveries.",
      coins: "Earn 5% cashback on prepaid orders & redeem directly during checkout.",
      fit: "Your selected size auto-syncs with product pages for instant fits.",
      profile: "Update your personal details, email, contact number, and login credentials.",
      settings: "Stay in the loop with instant WhatsApp dispatch alerts and delivery OTPs.",
    };
    return subtitles[tab] || "";
  }

  function renderTabContent(tab) {
    switch (tab) {
      case "overview":
        return renderOverviewTab();
      case "tracking":
        return renderTrackingTab();
      case "wishlist":
        return renderWishlistTab();
      case "addresses":
        return renderAddressesTab();
      case "coins":
        return renderCoinsTab();
      case "fit":
        return renderFitTab();
      case "profile":
        return renderProfileTab();
      case "settings":
        return renderSettingsTab();
      default:
        return renderOverviewTab();
    }
  }

  // -------------------------------------------------------------
  // TAB 1: OVERVIEW HUB (Executive Dashboard Center)
  // -------------------------------------------------------------
  function renderOverviewTab() {
    const u = currentUser;
    const activeOrder = userOrders.find((o) => o.orderNumber.toUpperCase() === activeTrackingNum.toUpperCase()) || userOrders[0];
    const userWishlist = JSON.parse(localStorage.getItem(STORAGE_KEYS.WISHLIST) || "[]");

    return `
      <div>
        
        <!-- 4 High-Impact KPI Stat Cards -->
        <div class="dashboard-kpi-grid" style="margin-bottom: 2rem;">
          
          <!-- Card 1: Feather Coins -->
          <div class="dashboard-kpi-card" onclick="window.FeatherAccount.switchTab('coins')">
            <div class="kpi-icon-wrap gold">🪙</div>
            <div class="kpi-details">
              <span class="kpi-label">Wallet Balance</span>
              <div class="kpi-value gold">₹${u.coins || 450}</div>
              <span class="kpi-subtext">450 Coins ready to redeem</span>
            </div>
          </div>

          <!-- Card 2: Live Shipment -->
          <div class="dashboard-kpi-card" onclick="window.FeatherAccount.switchTab('tracking')">
            <div class="kpi-icon-wrap blue">🚚</div>
            <div class="kpi-details">
              <span class="kpi-label">Active Delivery</span>
              <div class="kpi-value">${activeOrder ? activeOrder.status : 'None'}</div>
              <span class="kpi-subtext">${activeOrder ? activeOrder.estimatedDelivery : 'No active orders'}</span>
            </div>
          </div>

          <!-- Card 3: Saved Wishlist -->
          <div class="dashboard-kpi-card" onclick="window.FeatherAccount.switchTab('wishlist')">
            <div class="kpi-icon-wrap pink">💖</div>
            <div class="kpi-details">
              <span class="kpi-label">Saved Wishlist</span>
              <div class="kpi-value">${userWishlist.length} Items</div>
              <span class="kpi-subtext">Click to view drops</span>
            </div>
          </div>

          <!-- Card 4: Smart Fit -->
          <div class="dashboard-kpi-card" onclick="window.FeatherAccount.switchTab('fit')">
            <div class="kpi-icon-wrap purple">📏</div>
            <div class="kpi-details">
              <span class="kpi-label">Smart Size Fit</span>
              <div class="kpi-value">Size ${u.preferredSize || 'L'}</div>
              <span class="kpi-subtext">${u.preferredFit || 'Oversized Streetwear'}</span>
            </div>
          </div>

        </div>

        ${activeOrder ? `
          <!-- Active Shipment Spotlight Card -->
          <div class="dash-spotlight-box" style="margin-bottom: 2rem;">
            <div class="dash-spotlight-top">
              <div>
                <span class="dash-spotlight-badge">🚚 Live In-Transit Shipment</span>
                <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--color-primary); margin-top: 0.35rem;">
                  Order ${activeOrder.orderNumber} &bull; ${activeOrder.items[0]?.name || 'Apparel Order'}
                </h3>
              </div>
              <button type="button" class="btn-primary" onclick="window.FeatherAccount.switchTab('tracking')" style="height: 42px; padding: 0 1.25rem;">
                <span>View Full Timeline →</span>
              </button>
            </div>

            <div class="dash-courier-stats" style="margin-bottom: 1.25rem;">
              <div class="stat-cell">
                <span class="stat-label">Courier Partner:</span>
                <strong>${activeOrder.carrier || "Blue Dart Express"}</strong>
              </div>
              <div class="stat-cell">
                <span class="stat-label">AWB Tracking Number:</span>
                <strong style="font-family: monospace;">${activeOrder.trackingNumber || "BD-9402849182"}</strong>
              </div>
              <div class="stat-cell">
                <span class="stat-label">Estimated Delivery:</span>
                <strong style="color: var(--color-success);">${activeOrder.estimatedDelivery || "In 2 Days"}</strong>
              </div>
              <div class="stat-cell">
                <span class="stat-label">Delivery Destination:</span>
                <strong>${activeOrder.shippingAddress?.city || 'Chennai'}, ${activeOrder.shippingAddress?.postalCode || '600017'}</strong>
              </div>
            </div>

            <!-- Mini Progress Bar -->
            <div style="background: var(--color-border); height: 8px; border-radius: 9999px; overflow: hidden;">
              <div style="width: 75%; height: 100%; background: linear-gradient(90deg, var(--color-secondary), var(--color-accent)); border-radius: 9999px;"></div>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 0.78rem; color: var(--color-text-muted); margin-top: 0.45rem;">
              <span>Verified</span>
              <span>Packed & Inspected</span>
              <span style="font-weight: 800; color: var(--color-secondary);">In Transit (Chennai Hub)</span>
              <span>Delivered</span>
            </div>
          </div>
        ` : ''}

        <!-- 4 Quick Actions Grid -->
        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-primary); margin-bottom: 1rem;">
          Quick Dashboard Shortcuts
        </h3>
        
        <div class="dash-quick-grid" style="margin-bottom: 2rem;">
          
          <div class="dash-quick-card" onclick="window.location.href='index.html#catalog-section'">
            <div class="dash-quick-icon">🔥</div>
            <div class="dash-quick-title">Explore New Drops</div>
            <div class="dash-quick-desc">Discover the latest limited edition heavyweight anime & streetwear collections.</div>
            <span class="dash-quick-cta">Browse Store →</span>
          </div>

          <div class="dash-quick-card" onclick="window.FeatherAccount.switchTab('addresses')">
            <div class="dash-quick-icon">📍</div>
            <div class="dash-quick-title">Manage Addresses</div>
            <div class="dash-quick-desc">Add new home or office delivery points for instant express checkout.</div>
            <span class="dash-quick-cta">Address Book →</span>
          </div>

          <div class="dash-quick-card" onclick="window.FeatherAccount.switchTab('fit')">
            <div class="dash-quick-icon">📏</div>
            <div class="dash-quick-title">Tune Smart Fit</div>
            <div class="dash-quick-desc">Current: Size ${u.preferredSize || 'L'}. Auto-syncs across T-shirts, Shirts & Sets.</div>
            <span class="dash-quick-cta">Update Size →</span>
          </div>

          <div class="dash-quick-card" onclick="window.FeatherAccount.switchTab('coins')">
            <div class="dash-quick-icon">🎁</div>
            <div class="dash-quick-title">Earn More Coins</div>
            <div class="dash-quick-desc">Get 5% cashback on prepaid orders & invite friends for 200 free coins.</div>
            <span class="dash-quick-cta">View Rewards →</span>
          </div>

        </div>

        <!-- Recent Orders Snapshot -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
          <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-primary); margin: 0;">
            Recent Orders Snapshot
          </h3>
          <button type="button" class="btn-order-action primary" onclick="window.FeatherAccount.switchTab('tracking')">
            View All Orders (${userOrders.length}) →
          </button>
        </div>

        <div class="dash-orders-list">
          ${userOrders.slice(0, 2).map((o) => `
            <div class="order-card-item">
              <div class="order-card-header">
                <div>
                  <strong style="font-size: 1.05rem; color: var(--color-primary);">${o.orderNumber}</strong>
                  <span style="font-size: 0.8rem; color: var(--color-text-muted); margin-left: 0.75rem;">
                    ${new Date(o.createdAt).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })}
                  </span>
                </div>
                <div>
                  <span style="color: var(--color-accent); font-weight: 800; font-size: 1.15rem;">₹${Math.round(o.total).toLocaleString('en-IN')}</span>
                </div>
              </div>

              <div style="display: flex; flex-direction: column; gap: 0.75rem; margin: 0.75rem 0;">
                ${o.items.map((i) => `
                  <div style="display: flex; gap: 1rem; align-items: center; font-size: 0.88rem;">
                    <img src="${i.image || 'assets/images/anime-tee-model-front.jpg'}" style="width: 44px; height: 52px; object-fit: cover; border-radius: var(--radius-sm); object-position: top center;" alt="${i.name}" />
                    <div style="flex: 1;">
                      <div style="font-weight: 700; color: var(--color-primary);">${i.name}</div>
                      <div style="font-size: 0.8rem; color: var(--color-text-muted);">
                        Size: <strong>${i.size}</strong> &bull; Qty: ${i.quantity}
                      </div>
                    </div>
                    <strong>₹${Math.round((i.price || 1299) * i.quantity).toLocaleString('en-IN')}</strong>
                  </div>
                `).join('')}
              </div>

              <div class="order-actions-bar">
                <button type="button" class="btn-order-action primary" onclick="window.FeatherAccount.searchOrder('${o.orderNumber}')">
                  Track Package
                </button>
                <button type="button" class="btn-order-action" onclick="window.FeatherApp && window.FeatherApp.showInvoice ? window.FeatherApp.showInvoice('${o.orderNumber}') : alert('Invoice FF-89214 downloaded')">
                  GST Invoice
                </button>
              </div>
            </div>
          `).join('')}
        </div>

      </div>
    `;
  }

  // -------------------------------------------------------------
  // TAB 2: LIVE TRACKING & ORDERS
  // -------------------------------------------------------------
  function renderTrackingTab() {
    const activeOrder = userOrders.find((o) => o.orderNumber.toUpperCase() === activeTrackingNum.toUpperCase()) || userOrders[0];

    const statusMap = {
      PENDING_PAYMENT: 1,
      PAID: 1,
      PROCESSING: 2,
      SHIPPED: 3,
      OUT_FOR_DELIVERY: 3,
      DELIVERED: 4,
    };
    const currentStep = activeOrder ? (statusMap[activeOrder.status] || 3) : 3;

    return `
      <div>
        <!-- Order Lookup Search Bar -->
        <div class="dash-section-box">
          <span class="dash-box-label">Search Live Package by Order Number</span>
          <div class="dash-search-row">
            <input 
              type="text" 
              id="dash-order-input" 
              class="form-input" 
              placeholder="e.g. FF-89214" 
              value="${activeTrackingNum}" 
            />
            <button type="button" class="btn-primary" onclick="window.FeatherAccount.searchOrder(document.getElementById('dash-order-input').value)">
              <span>Track Live Package</span>
            </button>
          </div>
        </div>

        ${activeOrder ? `
          <!-- Active Milestone Visual Timeline -->
          <div class="dash-milestone-box">
            <div class="dash-milestone-header">
              <div>
                <span class="dash-box-label">Live Dispatch Timeline</span>
                <h3 class="dash-order-title">${activeOrder.orderNumber}</h3>
              </div>
              <span class="security-badge">${activeOrder.status}</span>
            </div>

            <div class="dash-courier-stats">
              <div class="stat-cell">
                <span class="stat-label">Courier Carrier:</span>
                <strong>${activeOrder.carrier || "Blue Dart Express"}</strong>
              </div>
              <div class="stat-cell">
                <span class="stat-label">AWB Tracking Number:</span>
                <strong style="font-family: monospace;">${activeOrder.trackingNumber || 'BD-9402849182'}</strong>
              </div>
              <div class="stat-cell">
                <span class="stat-label">Estimated Delivery:</span>
                <strong style="color: var(--color-success);">${activeOrder.estimatedDelivery || "In 2 Days"}</strong>
              </div>
            </div>

            <!-- Milestone Steps -->
            <div class="tracking-timeline" style="margin: 1.5rem 0 1rem 0;">
              <div class="timeline-step ${currentStep >= 1 ? 'completed' : ''}">
                <div class="timeline-step-dot">✓</div>
                <div class="timeline-title">Order Placed & Verified</div>
                <div class="timeline-desc">${new Date(activeOrder.createdAt).toLocaleDateString()} — Pay on Delivery Verified</div>
              </div>

              <div class="timeline-step ${currentStep > 2 ? 'completed' : currentStep === 2 ? 'active' : ''}">
                <div class="timeline-step-dot">${currentStep > 2 ? '✓' : '2'}</div>
                <div class="timeline-title">Quality Hand-Inspected & Packed</div>
                <div class="timeline-desc">240 GSM pre-shrunk combed cotton verified for print precision</div>
              </div>

              <div class="timeline-step ${currentStep > 3 ? 'completed' : currentStep === 3 ? 'active' : ''}">
                <div class="timeline-step-dot">${currentStep > 3 ? '✓' : '3'}</div>
                <div class="timeline-title">In Transit with Blue Dart</div>
                <div class="timeline-desc">Dispatched from Chennai Central Hub • Heading to Local Facility</div>
              </div>

              <div class="timeline-step ${currentStep >= 4 ? 'completed' : ''}">
                <div class="timeline-step-dot">${currentStep >= 4 ? '✓' : '4'}</div>
                <div class="timeline-title">Delivered to Doorstep</div>
                <div class="timeline-desc">Includes 30-day hassle-free size exchange guarantee</div>
              </div>
            </div>
          </div>
        ` : ''}

        <!-- Order History List -->
        <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--color-primary); margin: 2rem 0 1rem 0;">
          All Past Orders (${userOrders.length})
        </h3>

        <div class="dash-orders-list">
          ${userOrders.map((o) => `
            <div class="order-card-item">
              <div class="order-card-header">
                <div>
                  <strong style="font-size: 1.05rem; color: var(--color-primary);">${o.orderNumber}</strong>
                  <span style="font-size: 0.8rem; color: var(--color-text-muted); margin-left: 0.75rem;">
                    ${new Date(o.createdAt).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })}
                  </span>
                </div>
                <div>
                  <span style="color: var(--color-accent); font-weight: 800; font-size: 1.15rem;">₹${Math.round(o.total).toLocaleString('en-IN')}</span>
                </div>
              </div>

              <div style="display: flex; flex-direction: column; gap: 0.75rem; margin: 0.75rem 0;">
                ${o.items.map((i) => `
                  <div style="display: flex; gap: 1rem; align-items: center; font-size: 0.88rem;">
                    <img src="${i.image || 'assets/images/anime-tee-model-front.jpg'}" style="width: 46px; height: 56px; object-fit: cover; border-radius: var(--radius-sm); object-position: top center;" alt="${i.name}" />
                    <div style="flex: 1;">
                      <div style="font-weight: 700; color: var(--color-primary);">${i.name}</div>
                      <div style="font-size: 0.8rem; color: var(--color-text-muted);">
                        Size: <strong>${i.size}</strong> &bull; Color: <strong>${i.colorName || i.color || 'Standard'}</strong> &bull; Qty: ${i.quantity}
                      </div>
                    </div>
                    <strong>₹${Math.round((i.price || 1299) * i.quantity).toLocaleString('en-IN')}</strong>
                  </div>
                `).join('')}
              </div>

              <div class="order-actions-bar">
                <button type="button" class="btn-order-action primary" onclick="window.FeatherAccount.searchOrder('${o.orderNumber}')">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                  <span>Track Live Package</span>
                </button>
                <button type="button" class="btn-order-action" onclick="window.FeatherApp && window.FeatherApp.showInvoice ? window.FeatherApp.showInvoice('${o.orderNumber}') : alert('GST Invoice for #${o.orderNumber}')">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                  <span>Download GST Invoice</span>
                </button>
                <button type="button" class="btn-order-action" onclick="window.FeatherAccount.requestExchange('${o.orderNumber}')">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                  <span>30-Day Size Exchange</span>
                </button>
              </div>
            </div>
          `).join('')}
        </div>
      </div>
    `;
  }

  // -------------------------------------------------------------
  // TAB 3: SAVED WISHLIST DIRECT GRID
  // -------------------------------------------------------------
  function renderWishlistTab() {
    const userWishlist = JSON.parse(localStorage.getItem(STORAGE_KEYS.WISHLIST) || "[]");
    const productsCatalog = window.FEATHER_PRODUCTS || [];

    // Map wishlist IDs to actual products if available
    let wishlistItems = [];
    if (userWishlist.length > 0) {
      wishlistItems = userWishlist.map(id => {
        const prod = productsCatalog.find(p => p.id === id);
        if (prod) return prod;
        return {
          id,
          name: "Feather Fits Graphic Streetwear Drop",
          basePrice: 1299,
          images: ["assets/images/anime-tee-model-front.jpg"]
        };
      });
    }

    if (wishlistItems.length === 0) {
      return `
        <div style="text-align: center; padding: 3rem 1rem;">
          <div style="font-size: 3rem; margin-bottom: 1rem;">💖</div>
          <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--color-primary); margin-bottom: 0.5rem;">Your Wishlist is Empty</h3>
          <p style="color: var(--color-text-muted); font-size: 0.9rem; max-width: 400px; margin: 0 auto 1.5rem auto;">
            Save your favorite anime graphics, linen overshirts, and oversized tees for quick ordering later.
          </p>
          <a href="index.html#catalog-section" class="btn-primary" style="display: inline-flex; height: 46px; padding: 0 1.5rem;">
            Explore Collections →
          </a>
        </div>
      `;
    }

    return `
      <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
          <div>
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--color-primary);">Saved Drops (${wishlistItems.length})</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted);">Items saved to your VIP wishlist. Ready for instant checkout.</p>
          </div>
          <a href="index.html#catalog-section" class="btn-order-action primary">
            + Discover More Drops
          </a>
        </div>

        <div class="dash-wishlist-grid">
          ${wishlistItems.map(item => `
            <div class="dash-wishlist-card">
              <div class="dash-wishlist-img-wrap">
                <img src="${(item.images && item.images[0]) || 'assets/images/anime-tee-model-front.jpg'}" alt="${item.name}" />
                <button 
                  type="button" 
                  class="dash-wishlist-remove-btn" 
                  title="Remove from wishlist" 
                  onclick="window.FeatherAccount.removeFromWishlist('${item.id}')"
                >
                  ✕
                </button>
              </div>
              <div class="dash-wishlist-body">
                <h4 class="dash-wishlist-title">${item.name}</h4>
                <div class="dash-wishlist-price">₹${item.basePrice || 1299}</div>
                <button 
                  type="button" 
                  class="btn-primary" 
                  style="width: 100%; height: 40px; font-size: 0.85rem;" 
                  onclick="window.FeatherAccount.moveToCart('${item.id}')"
                >
                  Move to Shopping Bag
                </button>
              </div>
            </div>
          `).join('')}
        </div>
      </div>
    `;
  }

  // -------------------------------------------------------------
  // TAB 4: SAVED DELIVERY ADDRESSES
  // -------------------------------------------------------------
  function renderAddressesTab() {
    const addresses = currentUser.addresses || [];

    return `
      <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
          <div>
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--color-primary);">Saved Delivery Addresses</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted);">Your default delivery address will be auto-filled during quick checkout.</p>
          </div>
          <button type="button" class="btn-primary" onclick="window.FeatherAccount.toggleAddressForm()">
            + Add New Address
          </button>
        </div>

        ${showAddAddress ? `
          <div class="dash-inline-form-card">
            <h4 style="font-size: 1.05rem; font-weight: 700; color: var(--color-primary); margin-bottom: 1rem;">Add New Address</h4>
            <form onsubmit="window.FeatherAccount.saveNewAddress(event)">
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.75rem; margin-bottom: 0.75rem;">
                <div>
                  <label style="font-size: 0.78rem; font-weight: 700;">Address Tag</label>
                  <select id="new-addr-tag" class="form-input" style="height: 40px;">
                    <option value="Home">Home</option>
                    <option value="Office">Office / Work</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <div>
                  <label style="font-size: 0.78rem; font-weight: 700;">Recipient Name</label>
                  <input type="text" id="new-addr-name" required class="form-input" style="height: 40px;" placeholder="Full Name" value="${currentUser.firstName} ${currentUser.lastName}" />
                </div>
                <div>
                  <label style="font-size: 0.78rem; font-weight: 700;">Mobile Phone</label>
                  <input type="tel" id="new-addr-phone" required class="form-input" style="height: 40px;" placeholder="98765 43210" value="${currentUser.phone || '98765 43210'}" />
                </div>
              </div>

              <div style="margin-bottom: 0.75rem;">
                <label style="font-size: 0.78rem; font-weight: 700;">Street Address, Flat / Door No.</label>
                <input type="text" id="new-addr-line" required class="form-input" style="height: 40px;" placeholder="e.g. 15, Anna Salai" />
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem; margin-bottom: 1.25rem;">
                <div>
                  <label style="font-size: 0.78rem; font-weight: 700;">City</label>
                  <input type="text" id="new-addr-city" required class="form-input" style="height: 40px;" placeholder="Chennai" />
                </div>
                <div>
                  <label style="font-size: 0.78rem; font-weight: 700;">State</label>
                  <input type="text" id="new-addr-state" required class="form-input" style="height: 40px;" placeholder="Tamil Nadu" />
                </div>
                <div>
                  <label style="font-size: 0.78rem; font-weight: 700;">Pincode</label>
                  <input type="text" id="new-addr-zip" required class="form-input" style="height: 40px;" placeholder="600001" />
                </div>
              </div>

              <div style="display: flex; gap: 0.75rem;">
                <button type="submit" class="btn-primary" style="height: 42px; padding: 0 1.5rem;">Save Delivery Address</button>
                <button type="button" class="btn-order-action" style="height: 42px;" onclick="window.FeatherAccount.toggleAddressForm()">Cancel</button>
              </div>
            </form>
          </div>
        ` : ''}

        <div class="addresses-grid">
          ${addresses.map((a) => `
            <div class="address-card ${a.isDefault ? 'default' : ''}">
              <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <span class="address-tag-pill ${a.tag.toLowerCase()}">${a.tag}</span>
                ${a.isDefault ? `<span style="font-size: 0.78rem; font-weight: 800; color: var(--color-secondary);">✓ Default Address</span>` : ''}
              </div>

              <strong style="font-size: 1rem; color: var(--color-primary); display: block; margin: 0.4rem 0 0.25rem 0;">${a.name}</strong>
              <div style="font-size: 0.88rem; color: var(--color-text-main); line-height: 1.5; margin-bottom: 0.6rem;">
                ${a.line1}<br>
                ${a.city}, ${a.state} — <strong>${a.postalCode}</strong>
              </div>
              <div style="font-size: 0.82rem; color: var(--color-text-muted); margin-bottom: 1rem;">
                Phone: <strong>${a.phone}</strong>
              </div>

              <div style="display: flex; gap: 0.5rem; border-top: 1px solid var(--color-border); padding-top: 0.85rem;">
                ${!a.isDefault ? `
                  <button type="button" class="btn-order-action primary" onclick="window.FeatherAccount.setDefaultAddress('${a.id}')">
                    Set as Default
                  </button>
                ` : ''}
                <button type="button" class="btn-order-action" onclick="window.FeatherAccount.deleteAddress('${a.id}')">
                  Delete
                </button>
              </div>
            </div>
          `).join('')}
        </div>
      </div>
    `;
  }

  // -------------------------------------------------------------
  // TAB 5: FEATHER COINS & REWARDS WALLET
  // -------------------------------------------------------------
  function renderCoinsTab() {
    const coins = currentUser.coins || 450;

    return `
      <div>
        <div class="wallet-hero-card">
          <div class="wallet-balance-row">
            <div>
              <span style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.08em; opacity: 0.85;">Feather Member Wallet</span>
              <div class="wallet-coins-val">🪙 ${coins} Coins</div>
              <div style="font-size: 0.95rem; opacity: 0.9;">Total Value: <strong>₹${coins}</strong> in Feather Store Credits</div>
            </div>
            <div>
              <a href="index.html#catalog-section" class="btn-primary" style="background: #FFFFFF; color: var(--color-primary); font-weight: 800;">
                Shop Drops with Coins
              </a>
            </div>
          </div>
        </div>

        <h4 style="font-size: 1.1rem; font-weight: 800; color: var(--color-primary); margin-bottom: 1rem;">
          How to Earn & Use Feather Coins:
        </h4>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
          <div class="dash-benefit-card">
            <div style="font-size: 1.8rem; margin-bottom: 0.5rem;">🎉</div>
            <strong>+200 Coins on Signup</strong>
            <p style="font-size: 0.82rem; color: var(--color-text-muted); margin-top: 0.3rem;">Instant welcome gift credited upon creating your Feather Fits member profile.</p>
          </div>
          <div class="dash-benefit-card">
            <div style="font-size: 1.8rem; margin-bottom: 0.5rem;">💳</div>
            <strong>5% Cashback on Prepaid</strong>
            <p style="font-size: 0.82rem; color: var(--color-text-muted); margin-top: 0.3rem;">Earn 5% of order value back in Coins on UPI, Netbanking, or Card checkouts.</p>
          </div>
          <div class="dash-benefit-card">
            <div style="font-size: 1.8rem; margin-bottom: 0.5rem;">⚡</div>
            <strong>100% Usable on Drops</strong>
            <p style="font-size: 0.82rem; color: var(--color-text-muted); margin-top: 0.3rem;">1 Coin = ₹1. Auto-apply directly on checkout for instant price discounts.</p>
          </div>
        </div>

        <!-- Ledger -->
        <h4 style="font-size: 1rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.85rem;">Transaction Ledger</h4>
        <div style="border: 1px solid var(--color-border); border-radius: var(--radius-md); overflow: hidden; background: var(--color-surface);">
          <div style="display: flex; justify-content: space-between; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--color-border); font-size: 0.85rem;">
            <div>
              <strong>VIP Member Signup Bonus</strong>
              <div style="font-size: 0.75rem; color: var(--color-text-muted);">Account registration welcome credit</div>
            </div>
            <strong style="color: var(--color-success);">+200 Coins</strong>
          </div>
          <div style="display: flex; justify-content: space-between; padding: 0.85rem 1.25rem; font-size: 0.85rem;">
            <div>
              <strong>Order Reward — #FF-89214</strong>
              <div style="font-size: 0.75rem; color: var(--color-text-muted);">Cashback from Demon Slayer Tanjiro Graphic Tee purchase</div>
            </div>
            <strong style="color: var(--color-success);">+250 Coins</strong>
          </div>
        </div>
      </div>
    `;
  }

  // -------------------------------------------------------------
  // TAB 6: SMART FIT & SIZE PROFILE
  // -------------------------------------------------------------
  function renderFitTab() {
    const currentSize = currentUser.preferredSize || "L";
    const currentFit = currentUser.preferredFit || "Oversized Streetwear";

    const sizes = ["XS", "S", "M", "L", "XL", "XXL"];
    const fitStyles = [
      { id: "Oversized Streetwear", title: "Oversized Streetwear (Recommended)", desc: "Relaxed drop-shoulder, boxy chest drape, engineered for bold streetwear aesthetics." },
      { id: "Tailored Regular Fit", title: "Tailored Regular Fit", desc: "Classic shoulder alignment and tailored waist for smart casual daily wear." },
      { id: "Slim Fit", title: "Slim Athletic Fit", desc: "Body-skimming cut with stretch flexibility for active movement." }
    ];

    return `
      <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--color-primary); margin-bottom: 0.35rem;">Smart Fit & Size Profile</h3>
        <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 1.5rem;">
          Your selected fit auto-syncs with every product page. We'll automatically select your preferred size when you open any tee or shirt.
        </p>

        <!-- Size Selection -->
        <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.75rem;">Your Standard Size</h4>
        <div style="display: flex; gap: 0.65rem; margin-bottom: 2rem; flex-wrap: wrap;">
          ${sizes.map((s) => `
            <button 
              type="button" 
              class="dash-size-chip ${currentSize === s ? 'active' : ''}" 
              onclick="window.FeatherAccount.updateSize('${s}')"
            >
              ${s}
            </button>
          `).join('')}
        </div>

        <!-- Fit Style Selection -->
        <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.75rem;">Your Fit Aesthetic</h4>
        <div class="fit-options-group">
          ${fitStyles.map((f) => `
            <div 
              class="fit-card-btn ${currentFit === f.id ? 'active' : ''}" 
              onclick="window.FeatherAccount.updateFit('${f.id}')"
            >
              <strong style="color: var(--color-primary); font-size: 0.95rem; display: block; margin-bottom: 0.35rem;">${f.title}</strong>
              <p style="font-size: 0.82rem; color: var(--color-text-muted); line-height: 1.45;">${f.desc}</p>
            </div>
          `).join('')}
        </div>

        <div style="background: var(--color-bg); padding: 1rem 1.25rem; border-radius: var(--radius-md); border: 1px dashed var(--color-border); font-size: 0.85rem; color: var(--color-text-main); margin-top: 1.5rem;">
          💡 <strong>Auto-Fit Engine:</strong> When you browse any T-Shirt, Shirt, or Set on Feather Fits, size <strong>${currentSize}</strong> will be pre-selected for 1-tap checkout.
        </div>
      </div>
    `;
  }

  // -------------------------------------------------------------
  // TAB 7: PROFILE & SECURITY (Edit Details)
  // -------------------------------------------------------------
  function renderProfileTab() {
    const u = currentUser;

    return `
      <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--color-primary); margin-bottom: 0.35rem;">My Profile & Personal Details</h3>
        <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 1.5rem;">
          Update your contact details, delivery identity, and security preferences.
        </p>

        <form onsubmit="window.FeatherAccount.saveProfileDetails(event)">
          <div class="dash-profile-grid">
            <div>
              <label class="auth-form-label">First Name</label>
              <input type="text" id="prof-fname" class="auth-form-input" value="${u.firstName}" required />
            </div>
            <div>
              <label class="auth-form-label">Last Name</label>
              <input type="text" id="prof-lname" class="auth-form-input" value="${u.lastName}" required />
            </div>
          </div>

          <div class="dash-profile-grid">
            <div>
              <label class="auth-form-label">Email Address</label>
              <input type="email" id="prof-email" class="auth-form-input" value="${u.email}" required />
            </div>
            <div>
              <label class="auth-form-label">Mobile Number</label>
              <input type="tel" id="prof-phone" class="auth-form-input" value="${u.phone || '98765 43210'}" required />
            </div>
          </div>

          <div class="dash-profile-grid">
            <div>
              <label class="auth-form-label">Member Tier</label>
              <input type="text" class="auth-form-input" value="${u.tier || 'Feather VIP Member'}" disabled style="background: var(--color-bg); opacity: 0.8;" />
            </div>
            <div>
              <label class="auth-form-label">Member ID</label>
              <input type="text" class="auth-form-input" value="${u.id || 'usr_feather_091'}" disabled style="background: var(--color-bg); opacity: 0.8; font-family: monospace;" />
            </div>
          </div>

          <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="btn-primary" style="height: 48px; padding: 0 1.75rem;">
              Save Profile Changes
            </button>
            <button type="button" class="btn-order-action" style="height: 48px;" onclick="window.FeatherAccount.switchTab('overview')">
              Cancel
            </button>
          </div>
        </form>

        <div style="margin-top: 2.5rem; border-top: 1px solid var(--color-border); padding-top: 1.5rem;">
          <h4 style="font-size: 1rem; font-weight: 800; color: #DC2626; margin-bottom: 0.35rem;">Sign Out Session</h4>
          <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 1rem;">
            Signing out will switch your session back to the sign-in portal. Your orders and coins will remain safely stored.
          </p>
          <button type="button" class="dash-signout-btn" style="max-width: 220px;" onclick="window.FeatherAccount.signOut()">
            Log Out of Feather Fits
          </button>
        </div>
      </div>
    `;
  }

  // -------------------------------------------------------------
  // TAB 8: WHATSAPP & SMS SETTINGS
  // -------------------------------------------------------------
  function renderSettingsTab() {
    return `
      <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--color-primary); margin-bottom: 0.35rem;">Notification Preferences</h3>
        <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 1.5rem;">
          Control how and when you receive order milestone updates, delivery alerts, and VIP early access.
        </p>

        <!-- WhatsApp Toggle -->
        <div class="whatsapp-pref-card">
          <div style="display: flex; gap: 1rem; align-items: center;">
            <div style="width: 44px; height: 44px; border-radius: 50%; background: #25D366; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.3rem;">
              💬
            </div>
            <div>
              <strong style="color: #065F46; font-size: 0.95rem;">WhatsApp Live Delivery Alerts</strong>
              <p style="font-size: 0.8rem; color: #047857; margin-top: 0.2rem;">Get Blue Dart tracking milestones, driver contact, and dispatch alerts directly to WhatsApp.</p>
            </div>
          </div>
          <label class="toggle-switch">
            <input type="checkbox" id="whatsapp-toggle" ${currentUser.whatsappUpdates ? 'checked' : ''} onchange="window.FeatherAccount.toggleWhatsApp(this.checked)" />
            <span class="toggle-slider"></span>
          </label>
        </div>

        <!-- SMS Toggle -->
        <div class="whatsapp-pref-card" style="background: #F8FAFC; border-color: #CBD5E1;">
          <div style="display: flex; gap: 1rem; align-items: center;">
            <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--color-primary); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem;">
              📱
            </div>
            <div>
              <strong style="color: var(--color-primary); font-size: 0.95rem;">SMS Order Notifications</strong>
              <p style="font-size: 0.8rem; color: var(--color-text-muted); margin-top: 0.2rem;">Receive invoice SMS and out-for-delivery OTP to +91 ${currentUser.phone || '98765 43210'}.</p>
            </div>
          </div>
          <label class="toggle-switch">
            <input type="checkbox" id="sms-toggle" ${currentUser.smsAlerts ? 'checked' : ''} onchange="window.FeatherAccount.toggleSMS(this.checked)" />
            <span class="toggle-slider"></span>
          </label>
        </div>
      </div>
    `;
  }

  // -------------------------------------------------------------
  // CONTROLLER METHODS EXPOSED TO WINDOW
  // -------------------------------------------------------------
  window.FeatherAccount = {
    setAuthMode(mode) {
      authMode = mode;
      renderView();
    },

    loginDemo(profileKey) {
      if (DEMO_USERS[profileKey]) {
        currentUser = JSON.parse(JSON.stringify(DEMO_USERS[profileKey]));
        saveUser();
        activeTab = "overview";
        renderView();
        if (window.FeatherApp && window.FeatherApp.showToast) {
          window.FeatherApp.showToast(`Logged in as ${currentUser.firstName} ${currentUser.lastName} (${currentUser.tier})`);
        }
      }
    },

    handleLogin(e) {
      if (e) e.preventDefault();
      const email = document.getElementById("auth-login-email")?.value.trim() || "arun.kumar@featherfits.com";
      currentUser = {
        ...DEMO_USERS.arun,
        email: email,
        firstName: email.includes("priya") ? "Priya" : "Arun",
        lastName: email.includes("priya") ? "Sharma" : "Kumar",
      };
      saveUser();
      activeTab = "overview";
      renderView();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast(`Welcome back, ${currentUser.firstName}! Dashboard ready.`);
      }
    },

    handleRegister(e) {
      if (e) e.preventDefault();
      const fname = document.getElementById("reg-fname")?.value.trim() || "New";
      const lname = document.getElementById("reg-lname")?.value.trim() || "Member";
      const email = document.getElementById("reg-email")?.value.trim() || "member@featherfits.com";
      const phone = document.getElementById("reg-phone")?.value.trim() || "9876543210";
      const size = document.getElementById("reg-size")?.value || "L";

      currentUser = {
        id: "usr_feather_" + Math.floor(100 + Math.random() * 900),
        email,
        firstName: fname,
        lastName: lname,
        phone,
        memberSince: "2026",
        tier: "Feather VIP Member",
        coins: 200, // Welcome gift!
        preferredSize: size,
        preferredFit: "Oversized Streetwear",
        whatsappUpdates: true,
        smsAlerts: true,
        addresses: [
          {
            id: "addr_reg1",
            tag: "Home",
            isDefault: true,
            name: `${fname} ${lname}`,
            phone: `+91 ${phone}`,
            line1: "10, Express Avenue Way",
            city: "Chennai",
            state: "Tamil Nadu",
            postalCode: "600002"
          }
        ]
      };

      saveUser();
      activeTab = "overview";
      renderView();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast(`Welcome to Feather Fits VIP, ${fname}! +200 Coins credited 🎉`);
      }
    },

    saveProfileDetails(e) {
      if (e) e.preventDefault();
      const fname = document.getElementById("prof-fname")?.value.trim();
      const lname = document.getElementById("prof-lname")?.value.trim();
      const email = document.getElementById("prof-email")?.value.trim();
      const phone = document.getElementById("prof-phone")?.value.trim();

      if (!fname || !email) {
        alert("First name and email are required.");
        return;
      }

      currentUser.firstName = fname;
      currentUser.lastName = lname || "";
      currentUser.email = email;
      currentUser.phone = phone || currentUser.phone;

      saveUser();
      renderView();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast("Personal profile details updated successfully!");
      }
    },

    switchTab(tab) {
      activeTab = tab;
      renderView();
      window.scrollTo({ top: 100, behavior: 'smooth' });
    },

    searchOrder(orderNum) {
      if (!orderNum) return;
      activeTrackingNum = orderNum.trim();
      activeTab = "tracking";
      renderView();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast(`Found tracking timeline for #${activeTrackingNum}`);
      }
    },

    requestExchange(orderNumber) {
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast(`Exchange ticket #EX-${Math.floor(10000 + Math.random() * 90000)} initiated for Order ${orderNumber}! Free Blue Dart doorstep replacement.`);
      }
    },

    toggleAddressForm() {
      showAddAddress = !showAddAddress;
      renderView();
    },

    saveNewAddress(e) {
      if (e) e.preventDefault();
      const tag = document.getElementById("new-addr-tag")?.value || "Home";
      const name = document.getElementById("new-addr-name")?.value.trim() || currentUser.firstName;
      const phone = document.getElementById("new-addr-phone")?.value.trim() || currentUser.phone;
      const line1 = document.getElementById("new-addr-line")?.value.trim() || "";
      const city = document.getElementById("new-addr-city")?.value.trim() || "";
      const state = document.getElementById("new-addr-state")?.value.trim() || "";
      const postalCode = document.getElementById("new-addr-zip")?.value.trim() || "";

      if (!line1 || !city || !postalCode) {
        alert("Please enter street address, city and pincode.");
        return;
      }

      const newAddr = {
        id: "addr_" + Date.now(),
        tag,
        isDefault: false,
        name,
        phone,
        line1,
        city,
        state,
        postalCode,
      };

      if (!currentUser.addresses) currentUser.addresses = [];
      currentUser.addresses.push(newAddr);
      saveUser();
      showAddAddress = false;
      renderView();

      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast("New delivery address saved!");
      }
    },

    setDefaultAddress(addrId) {
      if (!currentUser.addresses) return;
      currentUser.addresses.forEach((a) => {
        a.isDefault = a.id === addrId;
      });
      saveUser();
      renderView();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast("Default delivery address updated.");
      }
    },

    deleteAddress(addrId) {
      if (!currentUser.addresses) return;
      currentUser.addresses = currentUser.addresses.filter((a) => a.id !== addrId);
      saveUser();
      renderView();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast("Address removed.");
      }
    },

    removeFromWishlist(prodId) {
      let wl = JSON.parse(localStorage.getItem(STORAGE_KEYS.WISHLIST) || "[]");
      wl = wl.filter(id => id !== prodId);
      localStorage.setItem(STORAGE_KEYS.WISHLIST, JSON.stringify(wl));
      renderView();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast("Item removed from wishlist.");
      }
    },

    moveToCart(prodId) {
      const products = window.FEATHER_PRODUCTS || [];
      const product = products.find(p => p.id === prodId) || {
        id: prodId,
        name: "Demon Slayer Tanjiro Oversized Graphic Tee",
        basePrice: 1299,
        images: ["assets/images/anime-tee-model-front.jpg"]
      };

      if (window.FeatherApp && window.FeatherApp.addToCart) {
        window.FeatherApp.addToCart(product.id, currentUser.preferredSize || "L", product.colors?.[0]?.name || "Standard", 1);
      } else {
        // Fallback cart add
        let cart = JSON.parse(localStorage.getItem(STORAGE_KEYS.CART) || "[]");
        cart.push({
          productId: product.id,
          name: product.name,
          price: product.basePrice || 1299,
          size: currentUser.preferredSize || "L",
          color: "Standard",
          quantity: 1,
          image: product.images?.[0] || "assets/images/anime-tee-model-front.jpg"
        });
        localStorage.setItem(STORAGE_KEYS.CART, JSON.stringify(cart));
      }

      this.removeFromWishlist(prodId);
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast(`Moved ${product.name} to your cart! 🛍️`);
      }
    },

    updateSize(size) {
      currentUser.preferredSize = size;
      saveUser();
      renderView();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast(`Preferred size updated to ${size} (auto-synced with catalog & PDP)`);
      }
    },

    updateFit(fit) {
      currentUser.preferredFit = fit;
      saveUser();
      renderView();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast(`Style aesthetic updated to ${fit}`);
      }
    },

    toggleWhatsApp(checked) {
      currentUser.whatsappUpdates = checked;
      saveUser();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast(checked ? "WhatsApp delivery alerts enabled." : "WhatsApp alerts paused.");
      }
    },

    toggleSMS(checked) {
      currentUser.smsAlerts = checked;
      saveUser();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast(checked ? "SMS dispatch updates enabled." : "SMS updates paused.");
      }
    },

    signOut() {
      currentUser = null;
      localStorage.removeItem(STORAGE_KEYS.USER);
      authMode = "signin";
      renderView();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast("You have been signed out. Please sign in or create an account.");
      }
    }
  };

  // Run on DOM ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAccountPage);
  } else {
    initAccountPage();
  }
})();
