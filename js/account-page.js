/**
 * FEATHER FITS — DEDICATED MEMBER MINI DASHBOARD CONTROLLER
 * Controls account.html:
 * - Executive KPI summary cards
 * - Real-time package milestone tracking
 * - Address book management
 * - Feather Coins wallet & transactions
 * - Size & Fit profile auto-sync with PDP
 * - WhatsApp & SMS notifications toggle
 */

(function () {
  "use strict";

  const STORAGE_KEYS = {
    CART: "ff_cart_v1",
    WISHLIST: "ff_wishlist_v1",
    USER: "ff_user_v1",
    ORDERS: "ff_orders_v1",
  };

  const defaultMember = {
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
  };

  let currentUser = JSON.parse(localStorage.getItem(STORAGE_KEYS.USER) || "null") || defaultMember;
  let userOrders = JSON.parse(localStorage.getItem(STORAGE_KEYS.ORDERS) || "[]");
  let wishlist = JSON.parse(localStorage.getItem(STORAGE_KEYS.WISHLIST) || "[]");

  // Ensure sample live order exists
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
  let activeTab = "tracking";
  let activeTrackingNum = userOrders[0]?.orderNumber || "FF-89214";
  let showAddAddress = false;

  function initAccountPage() {
    const root = document.getElementById("account-page-root");
    if (!root) return;

    // Check URL search params for ?tab=...
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get("tab");
    if (tabParam && ["tracking", "addresses", "coins", "fit", "settings"].includes(tabParam)) {
      activeTab = tabParam;
    }
    const orderParam = urlParams.get("order");
    if (orderParam) {
      activeTrackingNum = orderParam;
      activeTab = "tracking";
    }

    renderDashboard();
  }

  function saveUser() {
    localStorage.setItem(STORAGE_KEYS.USER, JSON.stringify(currentUser));
  }

  function renderDashboard() {
    const root = document.getElementById("account-page-root");
    if (!root) return;

    if (!currentUser) {
      renderSignInView(root);
      return;
    }

    const u = currentUser;
    const activeOrder = userOrders.find((o) => o.orderNumber.toUpperCase() === activeTrackingNum.toUpperCase()) || userOrders[0];

    root.innerHTML = `
      <div class="account-dashboard-wrapper">
        
        <!-- Profile Hero Banner -->
        <div class="dashboard-hero-banner">
          <div class="dashboard-hero-left">
            <div class="dashboard-avatar-circle">
              ${(u.firstName?.[0] || "A") + (u.lastName?.[0] || "K")}
            </div>
            <div class="dashboard-profile-info">
              <div class="dashboard-name-row">
                <h1>${u.firstName} ${u.lastName}</h1>
                <span class="dashboard-vip-pill">★ ${u.tier || "VIP Member"}</span>
              </div>
              <p class="dashboard-email-row">${u.email} &bull; +91 ${u.phone || "98765 43210"} &bull; Member since ${u.memberSince || "2024"}</p>
            </div>
          </div>
          <div class="dashboard-hero-actions">
            <button type="button" class="dashboard-signout-btn" onclick="window.FeatherAccount.signOut()">
              Sign Out
            </button>
          </div>
        </div>

        <!-- 4 Executive KPI Metric Cards (Mini Dashboard Highlights) -->
        <div class="dashboard-kpi-grid">
          
          <!-- KPI 1: Coins -->
          <div class="dashboard-kpi-card" onclick="window.FeatherAccount.switchTab('coins')">
            <div class="kpi-icon-wrap gold">
              🪙
            </div>
            <div class="kpi-details">
              <span class="kpi-label">Feather Coins Balance</span>
              <div class="kpi-value gold">₹${u.coins || 450}</div>
              <span class="kpi-subtext">450 coins ready to redeem at checkout</span>
            </div>
          </div>

          <!-- KPI 2: Active Delivery -->
          <div class="dashboard-kpi-card" onclick="window.FeatherAccount.switchTab('tracking')">
            <div class="kpi-icon-wrap blue">
              🚚
            </div>
            <div class="kpi-details">
              <span class="kpi-label">Live Delivery Status</span>
              <div class="kpi-value">${activeOrder ? activeOrder.status : 'No Active'}</div>
              <span class="kpi-subtext">${activeOrder ? `${activeOrder.carrier} &bull; ${activeOrder.estimatedDelivery}` : 'Browse new drops'}</span>
            </div>
          </div>

          <!-- KPI 3: Size Profile -->
          <div class="dashboard-kpi-card" onclick="window.FeatherAccount.switchTab('fit')">
            <div class="kpi-icon-wrap purple">
              📏
            </div>
            <div class="kpi-details">
              <span class="kpi-label">Smart Fit Preference</span>
              <div class="kpi-value">Size ${u.preferredSize || 'L'}</div>
              <span class="kpi-subtext">${u.preferredFit || 'Oversized Streetwear'} &bull; Auto-syncs</span>
            </div>
          </div>

          <!-- KPI 4: Wishlist -->
          <div class="dashboard-kpi-card" onclick="window.FeatherApp && window.FeatherApp.openWishlistDrawer()">
            <div class="kpi-icon-wrap pink">
              💖
            </div>
            <div class="kpi-details">
              <span class="kpi-label">Saved Wishlist</span>
              <div class="kpi-value">${wishlist.length} Items</div>
              <span class="kpi-subtext">Click to view exclusive saved drops</span>
            </div>
          </div>

        </div>

        <!-- Main Mini Dashboard Tabs & Workspace -->
        <div class="dashboard-main-card">
          
          <!-- Tab Navigation Bar -->
          <div class="dashboard-tabs-bar">
            <button type="button" class="dash-tab-btn ${activeTab === 'tracking' ? 'active' : ''}" onclick="window.FeatherAccount.switchTab('tracking')">
              <span>🚚 Live Tracking & Orders</span>
            </button>
            <button type="button" class="dash-tab-btn ${activeTab === 'addresses' ? 'active' : ''}" onclick="window.FeatherAccount.switchTab('addresses')">
              <span>📍 Saved Addresses (${(u.addresses || []).length})</span>
            </button>
            <button type="button" class="dash-tab-btn ${activeTab === 'coins' ? 'active' : ''}" onclick="window.FeatherAccount.switchTab('coins')">
              <span>🪙 Coins & Wallet</span>
            </button>
            <button type="button" class="dash-tab-btn ${activeTab === 'fit' ? 'active' : ''}" onclick="window.FeatherAccount.switchTab('fit')">
              <span>📏 Size & Fit (${u.preferredSize || 'L'})</span>
            </button>
            <button type="button" class="dash-tab-btn ${activeTab === 'settings' ? 'active' : ''}" onclick="window.FeatherAccount.switchTab('settings')">
              <span>⚙️ WhatsApp & Alerts</span>
            </button>
          </div>

          <!-- Tab Content Display -->
          <div class="dashboard-tab-panel">
            ${renderTabContent(activeTab)}
          </div>

        </div>

      </div>
    `;
  }

  function renderTabContent(tab) {
    switch (tab) {
      case "tracking":
        return renderTrackingTab();
      case "addresses":
        return renderAddressesTab();
      case "coins":
        return renderCoinsTab();
      case "fit":
        return renderFitTab();
      case "settings":
        return renderSettingsTab();
      default:
        return renderTrackingTab();
    }
  }

  // -------------------------------------------------------------
  // TAB 1: Live Tracking & Orders
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

              <!-- Items in order -->
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

              <!-- Order Action Buttons -->
              <div class="order-actions-bar">
                <button type="button" class="btn-order-action primary" onclick="window.FeatherAccount.searchOrder('${o.orderNumber}')">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                  <span>Track Live Package</span>
                </button>
                <button type="button" class="btn-order-action" onclick="window.FeatherApp.showInvoice('${o.orderNumber}')">
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
  // TAB 2: Saved Delivery Addresses
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
  // TAB 3: Feather Coins & Wallet Rewards
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
              <div style="font-size: 0.95rem; opacity: 0.9;">Total Value: <strong>₹${coins}</strong> in Feather Credits</div>
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
  // TAB 4: Smart Fit & Size Profile
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

        <div style="background: var(--color-bg); padding: 1rem 1.25rem; border-radius: var(--radius-md); border: 1px dashed var(--color-border); font-size: 0.85rem; color: var(--color-text-main);">
          💡 <strong>Auto-Fit Engine:</strong> When you browse any T-Shirt, Shirt, or Set on Feather Fits, size <strong>${currentSize}</strong> will be pre-selected for 1-tap checkout.
        </div>
      </div>
    `;
  }

  // -------------------------------------------------------------
  // TAB 5: WhatsApp & Notifications Settings
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

        <div style="margin-top: 2rem; border-top: 1px solid var(--color-border); padding-top: 1.5rem;">
          <h4 style="font-size: 1rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.5rem;">Account Security</h4>
          <p style="font-size: 0.82rem; color: var(--color-text-muted); margin-bottom: 1rem;">Logged in as ${currentUser.email}</p>
          <button type="button" class="btn-order-action" onclick="window.FeatherAccount.signOut()">
            Sign Out of Feather Fits
          </button>
        </div>
      </div>
    `;
  }

  // Sign In fallback view
  function renderSignInView(root) {
    root.innerHTML = `
      <div style="max-width: 480px; margin: 3rem auto; padding: 2.5rem 2rem; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-lg); box-shadow: 0 10px 30px rgba(0,0,0,0.06); text-align: center;">
        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--color-secondary), var(--color-accent)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem auto; color: #fff; font-size: 1.5rem;">
          👤
        </div>
        <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--color-primary);">Sign In to Member Dashboard</h2>
        <p style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0.5rem 0 1.5rem 0;">
          Access your live package tracking, 450 Feather Coins, and saved addresses.
        </p>

        <form onsubmit="window.FeatherAccount.handleLogin(event)">
          <div style="text-align: left; margin-bottom: 1rem;">
            <label style="font-size: 0.8rem; font-weight: 700;">Email</label>
            <input type="email" id="login-email" class="form-input" required value="arun.kumar@featherfits.com" />
          </div>
          <div style="text-align: left; margin-bottom: 1.5rem;">
            <label style="font-size: 0.8rem; font-weight: 700;">Password</label>
            <input type="password" id="login-pass" class="form-input" required value="confidence2026" />
          </div>
          <button type="submit" class="btn-primary" style="width: 100%; height: 48px; justify-content: center;">
            Sign In to Dashboard
          </button>
        </form>
      </div>
    `;
  }

  // -------------------------------------------------------------
  // CONTROLLER METHODS EXPOSED TO WINDOW
  // -------------------------------------------------------------
  window.FeatherAccount = {
    switchTab(tab) {
      activeTab = tab;
      renderDashboard();
    },
    searchOrder(orderNum) {
      if (!orderNum) return;
      activeTrackingNum = orderNum.trim();
      activeTab = "tracking";
      renderDashboard();
      if (window.FeatherApp) {
        window.FeatherApp.showToast(`Found tracking timeline for #${activeTrackingNum}`);
      }
    },
    requestExchange(orderNumber) {
      if (window.FeatherApp) {
        window.FeatherApp.showToast(`Exchange ticket #EX-${Math.floor(10000 + Math.random() * 90000)} initiated for Order ${orderNumber}! Blue Dart courier will pick up item for free size replacement.`);
      }
    },
    toggleAddressForm() {
      showAddAddress = !showAddAddress;
      renderDashboard();
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
      renderDashboard();

      if (window.FeatherApp) {
        window.FeatherApp.showToast("New delivery address saved!");
      }
    },
    setDefaultAddress(addrId) {
      if (!currentUser.addresses) return;
      currentUser.addresses.forEach((a) => {
        a.isDefault = a.id === addrId;
      });
      saveUser();
      renderDashboard();
      if (window.FeatherApp) {
        window.FeatherApp.showToast("Default delivery address updated.");
      }
    },
    deleteAddress(addrId) {
      if (!currentUser.addresses) return;
      currentUser.addresses = currentUser.addresses.filter((a) => a.id !== addrId);
      saveUser();
      renderDashboard();
      if (window.FeatherApp) {
        window.FeatherApp.showToast("Address removed.");
      }
    },
    updateSize(size) {
      currentUser.preferredSize = size;
      saveUser();
      renderDashboard();
      if (window.FeatherApp) {
        window.FeatherApp.showToast(`Preferred size updated to ${size} (synced with catalog & PDP)`);
      }
    },
    updateFit(fit) {
      currentUser.preferredFit = fit;
      saveUser();
      renderDashboard();
      if (window.FeatherApp) {
        window.FeatherApp.showToast(`Style aesthetic updated to ${fit}`);
      }
    },
    toggleWhatsApp(checked) {
      currentUser.whatsappUpdates = checked;
      saveUser();
      if (window.FeatherApp) {
        window.FeatherApp.showToast(checked ? "WhatsApp delivery alerts enabled." : "WhatsApp alerts paused.");
      }
    },
    toggleSMS(checked) {
      currentUser.smsAlerts = checked;
      saveUser();
      if (window.FeatherApp) {
        window.FeatherApp.showToast(checked ? "SMS dispatch updates enabled." : "SMS updates paused.");
      }
    },
    handleLogin(e) {
      if (e) e.preventDefault();
      currentUser = { ...defaultMember };
      saveUser();
      renderDashboard();
      if (window.FeatherApp) {
        window.FeatherApp.showToast("Signed in to Member Dashboard.");
      }
    },
    signOut() {
      currentUser = null;
      saveUser();
      renderDashboard();
      if (window.FeatherApp) {
        window.FeatherApp.showToast("You have been signed out.");
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
