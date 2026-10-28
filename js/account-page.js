/**
 * FEATHER FITS — SIMPLE & CLEAN USER DASHBOARD
 * A straightforward, user-friendly account dashboard:
 * - Clean user profile header with quick stats & logout
 * - 4 Essential tabs: My Orders, Saved Addresses, Feather Coins, Profile Details
 * - Simple sign-in portal when logged out
 */

(function () {
  "use strict";

  const STORAGE_KEYS = {
    CART: "ff_cart_v1",
    WISHLIST: "ff_wishlist_v1",
    USER: "ff_user_v1",
    ORDERS: "ff_orders_v1",
  };

  const defaultUser = {
    id: "usr_feather_091",
    email: "arun.kumar@featherfits.com",
    firstName: "Arun",
    lastName: "Kumar",
    phone: "98765 43210",
    memberSince: "2024",
    tier: "VIP Member",
    coins: 450,
    preferredSize: "L",
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

  let currentUser = JSON.parse(localStorage.getItem(STORAGE_KEYS.USER) || "null") || defaultUser;
  let userOrders = JSON.parse(localStorage.getItem(STORAGE_KEYS.ORDERS) || "[]");

  // Ensure default sample order
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
          size: "L",
          price: 1299.0,
          quantity: 1,
          image: "assets/images/anime-tee-model-front.jpg",
        },
      ],
      total: 1363.95,
    });
    localStorage.setItem(STORAGE_KEYS.ORDERS, JSON.stringify(userOrders));
  }

  // Active state
  let activeTab = "orders"; // 'orders', 'addresses', 'profile'
  let showAddAddress = false;
  let authMode = "login"; // 'login' or 'register'

  function initAccountPage() {
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get("tab");
    if (tabParam && ["orders", "tracking", "addresses", "profile"].includes(tabParam)) {
      activeTab = tabParam === "tracking" ? "orders" : tabParam;
    }
    render();
  }

  function saveUser() {
    localStorage.setItem(STORAGE_KEYS.USER, JSON.stringify(currentUser));
  }

  function render() {
    const root = document.getElementById("account-page-root");
    if (!root) return;

    if (!currentUser) {
      renderSimpleAuth(root);
      return;
    }

    renderSimpleDashboard(root);
  }

  // -------------------------------------------------------------
  // 1. SIMPLE LOGIN / SIGN UP
  // -------------------------------------------------------------
  function renderSimpleAuth(root) {
    root.innerHTML = `
      <div style="max-width: 440px; margin: 3rem auto; background: var(--color-surface); border: 1.5px solid var(--color-border); border-radius: var(--radius-lg); padding: 2.25rem 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.06);">
        
        <div style="text-align: center; margin-bottom: 1.75rem;">
          <div style="width: 58px; height: 58px; background: linear-gradient(135deg, var(--color-secondary), var(--color-accent)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; color: #fff; font-size: 1.5rem;">
            👤
          </div>
          <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--color-primary); margin-bottom: 0.35rem;">
            ${authMode === 'login' ? 'Sign In to Account' : 'Create New Account'}
          </h2>
          <p style="font-size: 0.85rem; color: var(--color-text-muted);">
            ${authMode === 'login' ? 'Access your orders, saved addresses & Feather Coins.' : 'Join Feather Fits VIP and get +200 instant Coins.'}
          </p>
        </div>

        <!-- 1-Click Demo Login Shortcut -->
        ${authMode === 'login' ? `
          <div style="background: #FFF9FC; border: 1px dashed var(--color-secondary); border-radius: var(--radius-md); padding: 0.85rem 1rem; margin-bottom: 1.5rem; text-align: center;">
            <span style="font-size: 0.78rem; font-weight: 700; color: var(--color-secondary); display: block; margin-bottom: 0.5rem;">⚡ Quick 1-Click Demo Login:</span>
            <button type="button" class="btn-primary" style="height: 38px; font-size: 0.82rem; width: 100%; justify-content: center;" onclick="window.FeatherAccount.quickDemoLogin()">
              Login as Arun Kumar (VIP Member)
            </button>
          </div>
        ` : ''}

        <form onsubmit="window.FeatherAccount.handleAuthSubmit(event)">
          ${authMode === 'register' ? `
            <div style="margin-bottom: 1rem;">
              <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.35rem;">Full Name</label>
              <input type="text" id="simple-auth-name" class="form-input" style="height: 44px; width: 100%;" placeholder="Arun Kumar" required />
            </div>
            <div style="margin-bottom: 1rem;">
              <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.35rem;">Mobile Phone</label>
              <input type="tel" id="simple-auth-phone" class="form-input" style="height: 44px; width: 100%;" placeholder="98765 43210" required />
            </div>
          ` : ''}

          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.35rem;">Email Address</label>
            <input type="email" id="simple-auth-email" class="form-input" style="height: 44px; width: 100%;" value="${authMode === 'login' ? 'arun.kumar@featherfits.com' : ''}" placeholder="arun@example.com" required />
          </div>

          <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.35rem;">Password</label>
            <input type="password" id="simple-auth-pass" class="form-input" style="height: 44px; width: 100%;" value="${authMode === 'login' ? 'confidence2026' : ''}" placeholder="••••••••" required />
          </div>

          <button type="submit" class="btn-primary" style="width: 100%; height: 46px; justify-content: center; font-size: 0.95rem;">
            ${authMode === 'login' ? 'Sign In →' : 'Create Account (+200 Coins) →'}
          </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: var(--color-text-muted);">
          ${authMode === 'login' ? `
            Don't have an account? 
            <button type="button" style="color: var(--color-secondary); font-weight: 700; text-decoration: underline;" onclick="window.FeatherAccount.toggleAuthMode('register')">Sign Up</button>
          ` : `
            Already have an account? 
            <button type="button" style="color: var(--color-secondary); font-weight: 700; text-decoration: underline;" onclick="window.FeatherAccount.toggleAuthMode('login')">Sign In</button>
          `}
        </div>

      </div>
    `;
  }

  // -------------------------------------------------------------
  // 2. SIMPLE, CLEAN DASHBOARD
  // -------------------------------------------------------------
  function renderSimpleDashboard(root) {
    const u = currentUser;

    root.innerHTML = `
      <div style="max-width: 980px; margin: 1.5rem auto 3.5rem auto; display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- Clean User Header Banner -->
        <div style="background: var(--color-surface); border: 1.5px solid var(--color-border); border-radius: var(--radius-lg); padding: 1.5rem 1.75rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.25rem; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
          
          <div style="display: flex; align-items: center; gap: 1.15rem;">
            <div style="width: 58px; height: 58px; border-radius: 50%; background: linear-gradient(135deg, var(--color-secondary), var(--color-accent)); color: #fff; font-size: 1.35rem; font-weight: 800; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(142, 22, 88, 0.3);">
              ${(u.firstName?.[0] || "A") + (u.lastName?.[0] || "K")}
            </div>
            <div>
              <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
                <h1 style="font-size: 1.35rem; font-weight: 800; color: var(--color-primary); margin: 0;">${u.firstName} ${u.lastName}</h1>
                <span style="font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 9999px; background: #FEF3C7; color: #B45309;">★ ${u.tier || "VIP Member"}</span>
              </div>
              <div style="font-size: 0.85rem; color: var(--color-text-muted); margin-top: 0.25rem;">
                ${u.email} &bull; +91 ${u.phone || '98765 43210'}
              </div>
            </div>
          </div>

          <!-- Header Right: Logout -->
          <div style="display: flex; align-items: center; gap: 0.85rem;">
            <button type="button" class="btn-order-action" style="height: 40px; padding: 0 1.25rem;" onclick="window.FeatherAccount.signOut()">
              Sign Out
            </button>
          </div>

        </div>

        <!-- 3 Simple Tabs Navigation -->
        <div style="display: flex; gap: 0.5rem; border-bottom: 2px solid var(--color-border); padding-bottom: 0.25rem; overflow-x: auto;">
          <button 
            type="button" 
            class="dash-tab-btn ${activeTab === 'orders' ? 'active' : ''}" 
            onclick="window.FeatherAccount.switchTab('orders')"
            style="font-size: 0.92rem; padding: 0.85rem 1.25rem;"
          >
            📦 My Orders (${userOrders.length})
          </button>
          
          <button 
            type="button" 
            class="dash-tab-btn ${activeTab === 'addresses' ? 'active' : ''}" 
            onclick="window.FeatherAccount.switchTab('addresses')"
            style="font-size: 0.92rem; padding: 0.85rem 1.25rem;"
          >
            📍 Saved Addresses (${(u.addresses || []).length})
          </button>

          <button 
            type="button" 
            class="dash-tab-btn ${activeTab === 'profile' ? 'active' : ''}" 
            onclick="window.FeatherAccount.switchTab('profile')"
            style="font-size: 0.92rem; padding: 0.85rem 1.25rem;"
          >
            👤 Profile Details
          </button>
        </div>

        <!-- Tab Body Content Card -->
        <div style="background: var(--color-surface); border: 1.5px solid var(--color-border); border-radius: var(--radius-lg); padding: 2rem; box-shadow: 0 4px 16px rgba(0,0,0,0.02); min-height: 380px;">
          ${renderActiveTab(activeTab, u)}
        </div>

      </div>
    `;
  }

  function renderActiveTab(tab, u) {
    switch (tab) {
      case "orders":
        return renderSimpleOrders();
      case "addresses":
        return renderSimpleAddresses(u);
      case "profile":
        return renderSimpleProfile(u);
      default:
        return renderSimpleOrders();
    }
  }

  // -------------------------------------------------------------
  // TAB 1: MY ORDERS (Simple & Clean)
  // -------------------------------------------------------------
  function renderSimpleOrders() {
    if (userOrders.length === 0) {
      return `
        <div style="text-align: center; padding: 3rem 1rem;">
          <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">📦</div>
          <h3 style="color: var(--color-primary); font-size: 1.2rem; margin-bottom: 0.4rem;">No Orders Yet</h3>
          <p style="color: var(--color-text-muted); font-size: 0.85rem; margin-bottom: 1.25rem;">Browse our collection and place your first order.</p>
          <a href="index.html#catalog-section" class="btn-primary" style="display: inline-flex; height: 42px; padding: 0 1.5rem;">Explore Drops →</a>
        </div>
      `;
    }

    return `
      <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem;">
          <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--color-primary); margin: 0;">Order History & Tracking</h3>
          <a href="index.html#catalog-section" class="btn-order-action primary">+ Shop More Drops</a>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
          ${userOrders.map((o) => `
            <div style="border: 1.5px solid var(--color-border); border-radius: var(--radius-md); padding: 1.25rem; background: var(--color-surface);">
              
              <!-- Order Header -->
              <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); padding-bottom: 0.85rem; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                <div>
                  <strong style="font-size: 1.05rem; color: var(--color-primary);">${o.orderNumber}</strong>
                  <span style="font-size: 0.8rem; color: var(--color-text-muted); margin-left: 0.5rem;">
                    ${new Date(o.createdAt).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })}
                  </span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                  <span style="font-size: 0.75rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: 9999px; background: #E0E7FF; color: #3730A3;">
                    ${o.status || 'SHIPPED'}
                  </span>
                  <strong style="color: var(--color-secondary); font-size: 1.1rem;">₹${Math.round(o.total).toLocaleString('en-IN')}</strong>
                </div>
              </div>

              <!-- Items in order -->
              <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1rem;">
                ${o.items.map((i) => `
                  <div style="display: flex; gap: 1rem; align-items: center;">
                    <img src="${i.image || 'assets/images/anime-tee-model-front.jpg'}" style="width: 44px; height: 52px; object-fit: cover; border-radius: var(--radius-sm);" alt="${i.name}" />
                    <div style="flex: 1;">
                      <div style="font-weight: 700; font-size: 0.9rem; color: var(--color-primary);">${i.name}</div>
                      <div style="font-size: 0.8rem; color: var(--color-text-muted);">
                        Size: <strong>${i.size}</strong> &bull; Qty: ${i.quantity}
                      </div>
                    </div>
                    <strong style="font-size: 0.9rem;">₹${Math.round((i.price || 1299) * i.quantity).toLocaleString('en-IN')}</strong>
                  </div>
                `).join('')}
              </div>

              <!-- Courier tracking info snippet -->
              <div style="background: var(--color-bg); padding: 0.75rem 1rem; border-radius: var(--radius-sm); font-size: 0.82rem; color: var(--color-text-main); margin-bottom: 1rem; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                <span>🚚 Courier: <strong>${o.carrier || 'Blue Dart Express'}</strong> &bull; AWB: <strong>${o.trackingNumber || 'BD-9402849182'}</strong></span>
                <span style="color: var(--color-success); font-weight: 700;">ETA: ${o.estimatedDelivery || 'In 2 Days'}</span>
              </div>

              <!-- Actions -->
              <div style="display: flex; gap: 0.65rem; flex-wrap: wrap;">
                <button type="button" class="btn-order-action primary" onclick="alert('Tracking status for ${o.orderNumber}: In Transit with Blue Dart. Expected delivery in 2 days.')">
                  Track Package
                </button>
                <button type="button" class="btn-order-action" onclick="window.FeatherApp && window.FeatherApp.showInvoice ? window.FeatherApp.showInvoice('${o.orderNumber}') : alert('Downloaded Tax Invoice for ${o.orderNumber}')">
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
  // TAB 2: SAVED ADDRESSES (Simple & Clean)
  // -------------------------------------------------------------
  function renderSimpleAddresses(u) {
    const addresses = u.addresses || [];

    return `
      <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem;">
          <div>
            <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--color-primary); margin: 0;">Delivery Addresses</h3>
            <span style="font-size: 0.82rem; color: var(--color-text-muted);">Used for auto-fill during checkout</span>
          </div>
          <button type="button" class="btn-primary" style="height: 40px; padding: 0 1.25rem;" onclick="window.FeatherAccount.toggleAddressForm()">
            ${showAddAddress ? 'Cancel' : '+ Add Address'}
          </button>
        </div>

        ${showAddAddress ? `
          <div style="background: var(--color-bg); border: 1.5px solid var(--color-secondary); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.5rem;">
            <h4 style="font-size: 1rem; font-weight: 800; color: var(--color-primary); margin-bottom: 1rem;">Add New Address</h4>
            <form onsubmit="window.FeatherAccount.saveNewAddress(event)">
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                <div>
                  <label style="font-size: 0.75rem; font-weight: 700;">Tag (e.g. Home, Office)</label>
                  <select id="new-addr-tag" class="form-input" style="height: 40px; width: 100%;">
                    <option value="Home">Home</option>
                    <option value="Office">Office</option>
                  </select>
                </div>
                <div>
                  <label style="font-size: 0.75rem; font-weight: 700;">Recipient Name</label>
                  <input type="text" id="new-addr-name" class="form-input" style="height: 40px; width: 100%;" value="${u.firstName} ${u.lastName}" required />
                </div>
              </div>

              <div style="margin-bottom: 0.75rem;">
                <label style="font-size: 0.75rem; font-weight: 700;">Street Address / Flat No.</label>
                <input type="text" id="new-addr-line" class="form-input" style="height: 40px; width: 100%;" placeholder="e.g. 15, Anna Salai" required />
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                <div>
                  <label style="font-size: 0.75rem; font-weight: 700;">City</label>
                  <input type="text" id="new-addr-city" class="form-input" style="height: 40px; width: 100%;" value="Chennai" required />
                </div>
                <div>
                  <label style="font-size: 0.75rem; font-weight: 700;">State</label>
                  <input type="text" id="new-addr-state" class="form-input" style="height: 40px; width: 100%;" value="Tamil Nadu" required />
                </div>
                <div>
                  <label style="font-size: 0.75rem; font-weight: 700;">Pincode</label>
                  <input type="text" id="new-addr-zip" class="form-input" style="height: 40px; width: 100%;" placeholder="600001" required />
                </div>
              </div>

              <button type="submit" class="btn-primary" style="height: 40px; padding: 0 1.25rem;">Save Address</button>
            </form>
          </div>
        ` : ''}

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
          ${addresses.map((a) => `
            <div style="border: 1.5px solid ${a.isDefault ? 'var(--color-secondary)' : 'var(--color-border)'}; border-radius: var(--radius-md); padding: 1.25rem; background: var(--color-surface); position: relative;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <span style="font-size: 0.75rem; font-weight: 800; background: var(--color-bg); padding: 0.2rem 0.6rem; border-radius: 9999px; color: var(--color-primary);">${a.tag}</span>
                ${a.isDefault ? `<span style="font-size: 0.75rem; font-weight: 800; color: var(--color-secondary);">✓ Default</span>` : ''}
              </div>

              <strong style="color: var(--color-primary); font-size: 0.95rem; display: block; margin-bottom: 0.25rem;">${a.name}</strong>
              <p style="font-size: 0.85rem; color: var(--color-text-main); line-height: 1.4; margin-bottom: 0.5rem;">
                ${a.line1}<br>${a.city}, ${a.state} — <strong>${a.postalCode}</strong>
              </p>
              <div style="font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 0.85rem;">
                Phone: ${a.phone}
              </div>

              <div style="display: flex; gap: 0.5rem; border-top: 1px solid var(--color-border); padding-top: 0.75rem;">
                ${!a.isDefault ? `
                  <button type="button" class="btn-order-action primary" onclick="window.FeatherAccount.setDefaultAddress('${a.id}')">
                    Set Default
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
  // TAB 3: PROFILE DETAILS (Simple & Clean)
  // -------------------------------------------------------------
  function renderSimpleProfile(u) {
    return `
      <div>
        <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--color-primary); margin-bottom: 0.35rem;">Edit Profile Details</h3>
        <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 1.5rem;">Update your name, contact phone, and standard size.</p>

        <form onsubmit="window.FeatherAccount.saveProfile(event)" style="max-width: 520px;">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
            <div>
              <label style="font-size: 0.8rem; font-weight: 700; color: var(--color-primary); display: block; margin-bottom: 0.35rem;">First Name</label>
              <input type="text" id="edit-fname" class="form-input" style="height: 42px; width: 100%;" value="${u.firstName}" required />
            </div>
            <div>
              <label style="font-size: 0.8rem; font-weight: 700; color: var(--color-primary); display: block; margin-bottom: 0.35rem;">Last Name</label>
              <input type="text" id="edit-lname" class="form-input" style="height: 42px; width: 100%;" value="${u.lastName}" required />
            </div>
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="font-size: 0.8rem; font-weight: 700; color: var(--color-primary); display: block; margin-bottom: 0.35rem;">Email Address</label>
            <input type="email" id="edit-email" class="form-input" style="height: 42px; width: 100%;" value="${u.email}" required />
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="font-size: 0.8rem; font-weight: 700; color: var(--color-primary); display: block; margin-bottom: 0.35rem;">Mobile Phone</label>
            <input type="tel" id="edit-phone" class="form-input" style="height: 42px; width: 100%;" value="${u.phone || '98765 43210'}" required />
          </div>

          <div style="margin-bottom: 1.5rem;">
            <label style="font-size: 0.8rem; font-weight: 700; color: var(--color-primary); display: block; margin-bottom: 0.35rem;">Preferred Size</label>
            <select id="edit-size" class="form-input" style="height: 42px; width: 100%;">
              <option value="S" ${u.preferredSize === 'S' ? 'selected' : ''}>S — Small</option>
              <option value="M" ${u.preferredSize === 'M' ? 'selected' : ''}>M — Medium</option>
              <option value="L" ${u.preferredSize === 'L' || !u.preferredSize ? 'selected' : ''}>L — Large</option>
              <option value="XL" ${u.preferredSize === 'XL' ? 'selected' : ''}>XL — Extra Large</option>
              <option value="XXL" ${u.preferredSize === 'XXL' ? 'selected' : ''}>XXL — Oversized</option>
            </select>
          </div>

          <button type="submit" class="btn-primary" style="height: 44px; padding: 0 1.5rem;">
            Save Changes
          </button>
        </form>
      </div>
    `;
  }

  // -------------------------------------------------------------
  // CONTROLLER METHODS
  // -------------------------------------------------------------
  window.FeatherAccount = {
    switchTab(tab) {
      activeTab = tab;
      render();
    },

    toggleAuthMode(mode) {
      authMode = mode;
      render();
    },

    quickDemoLogin() {
      currentUser = { ...defaultUser };
      saveUser();
      activeTab = "orders";
      render();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast(`Logged in as Arun Kumar (VIP Member)`);
      }
    },

    handleAuthSubmit(e) {
      if (e) e.preventDefault();
      if (authMode === "login") {
        const email = document.getElementById("simple-auth-email")?.value || "arun.kumar@featherfits.com";
        currentUser = {
          ...defaultUser,
          email,
        };
        saveUser();
        activeTab = "orders";
        render();
        if (window.FeatherApp && window.FeatherApp.showToast) {
          window.FeatherApp.showToast("Welcome back! Signed in successfully.");
        }
      } else {
        const name = document.getElementById("simple-auth-name")?.value || "Member";
        const email = document.getElementById("simple-auth-email")?.value || "member@featherfits.com";
        const phone = document.getElementById("simple-auth-phone")?.value || "9876543210";
        currentUser = {
          ...defaultUser,
          id: "usr_" + Date.now(),
          firstName: name.split(" ")[0] || name,
          lastName: name.split(" ")[1] || "",
          email,
          phone,
          coins: 200,
        };
        saveUser();
        activeTab = "orders";
        render();
        if (window.FeatherApp && window.FeatherApp.showToast) {
          window.FeatherApp.showToast(`Account created! +200 Feather Coins welcome bonus 🎉`);
        }
      }
    },

    saveProfile(e) {
      if (e) e.preventDefault();
      const fname = document.getElementById("edit-fname")?.value.trim();
      const lname = document.getElementById("edit-lname")?.value.trim();
      const email = document.getElementById("edit-email")?.value.trim();
      const phone = document.getElementById("edit-phone")?.value.trim();
      const size = document.getElementById("edit-size")?.value || "L";

      if (!fname || !email) {
        alert("Name and email are required.");
        return;
      }

      currentUser.firstName = fname;
      currentUser.lastName = lname || "";
      currentUser.email = email;
      currentUser.phone = phone || currentUser.phone;
      currentUser.preferredSize = size;

      saveUser();
      render();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast("Profile details updated successfully!");
      }
    },

    toggleAddressForm() {
      showAddAddress = !showAddAddress;
      render();
    },

    saveNewAddress(e) {
      if (e) e.preventDefault();
      const tag = document.getElementById("new-addr-tag")?.value || "Home";
      const name = document.getElementById("new-addr-name")?.value.trim() || currentUser.firstName;
      const line1 = document.getElementById("new-addr-line")?.value.trim();
      const city = document.getElementById("new-addr-city")?.value.trim();
      const state = document.getElementById("new-addr-state")?.value.trim();
      const postalCode = document.getElementById("new-addr-zip")?.value.trim();

      if (!line1 || !city || !postalCode) {
        alert("Please enter street address, city, and pincode.");
        return;
      }

      const newAddr = {
        id: "addr_" + Date.now(),
        tag,
        isDefault: false,
        name,
        phone: currentUser.phone || "+91 98765 43210",
        line1,
        city,
        state,
        postalCode,
      };

      if (!currentUser.addresses) currentUser.addresses = [];
      currentUser.addresses.push(newAddr);
      saveUser();
      showAddAddress = false;
      render();

      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast("New address saved!");
      }
    },

    setDefaultAddress(addrId) {
      if (!currentUser.addresses) return;
      currentUser.addresses.forEach((a) => {
        a.isDefault = a.id === addrId;
      });
      saveUser();
      render();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast("Default address updated.");
      }
    },

    deleteAddress(addrId) {
      if (!currentUser.addresses) return;
      currentUser.addresses = currentUser.addresses.filter((a) => a.id !== addrId);
      saveUser();
      render();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast("Address removed.");
      }
    },

    signOut() {
      currentUser = null;
      localStorage.removeItem(STORAGE_KEYS.USER);
      authMode = "login";
      render();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast("Signed out. Please sign in to view your dashboard.");
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
