/**
 * FEATHER FITS — VIP MEMBER DASHBOARD & AUTHENTICATION PORTAL
 * Architecture:
 * - New User Registration with MANDATORY Email ID, 10-digit Phone Number, and OTP verification
 * - Secure User Login (Email / Phone + Password OR Instant OTP Login)
 * - 4 Essential Tabs: My Orders & Live Tracking, Saved Addresses, Feather Coins Wallet, Profile Details
 * - Live connection to api/auth.php backend
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
    name: "Arun Kumar",
    phone: "9876543210",
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
      }
    ],
  };

  // Read stored user from localStorage
  let currentUser = JSON.parse(localStorage.getItem(STORAGE_KEYS.USER) || "null");
  let userOrders = JSON.parse(localStorage.getItem(STORAGE_KEYS.ORDERS) || "[]");

  // Ensure default demo order
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

  // State
  let activeTab = "orders"; // 'orders', 'addresses', 'profile'
  let showAddAddress = false;
  let authMode = "login"; // 'login' or 'register'
  
  // Registration OTP State
  let registerStep = "form"; // 'form' or 'otp'
  let pendingRegisterData = null;
  let activeDemoOtp = "";
  let otpCountdown = 60;
  let otpTimerInterval = null;

  function initAccountPage() {
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get("tab");
    if (tabParam && ["orders", "tracking", "addresses", "profile"].includes(tabParam)) {
      activeTab = tabParam === "tracking" ? "orders" : tabParam;
    }
    
    // Check backend session on load
    fetch('api/auth.php?action=get_session')
      .then(res => res.json())
      .then(data => {
        if (data.user) {
          currentUser = data.user;
          localStorage.setItem(STORAGE_KEYS.USER, JSON.stringify(currentUser));
        }
        render();
      })
      .catch(() => {
        render();
      });
  }

  function saveUser() {
    if (currentUser) {
      localStorage.setItem(STORAGE_KEYS.USER, JSON.stringify(currentUser));
    } else {
      localStorage.removeItem(STORAGE_KEYS.USER);
    }
  }

  function render() {
    const root = document.getElementById("account-page-root");
    if (!root) return;

    if (!currentUser) {
      renderAuthPortal(root);
      return;
    }

    renderDashboard(root);
  }

  // =========================================================================
  // 1. VIP AUTHENTICATION & OTP VERIFICATION PORTAL
  // =========================================================================
  function renderAuthPortal(root) {
    root.innerHTML = `
      <div class="auth-portal-card">
        
        <!-- Brand Header -->
        <div style="text-align: center; margin-bottom: 1.5rem;">
          <div style="width: 58px; height: 58px; background: linear-gradient(135deg, #6A1B6E, #E0245E); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem auto; color: #fff; font-size: 1.5rem; box-shadow: 0 8px 20px rgba(106, 27, 110, 0.25);">
            <span class="lottie-icon" data-lottie-icon="customers" data-lottie-primary="#FFFFFF" data-lottie-secondary="#FFFFFF" style="width: 28px; height: 28px;"></span>
          </div>
          <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.6rem; font-weight: 800; color: #3B0F4A; margin-bottom: 0.25rem;">
            VIP Member Portal
          </h2>
          <p style="font-size: 0.85rem; color: #724E79;">
            Confidence Fits You &bull; Track orders, redeem coins & access exclusive drops
          </p>
        </div>

        <!-- Mode Navigation Tabs: Sign In / Create Account -->
        <div class="auth-nav-tabs">
          <button type="button" class="auth-nav-tab ${authMode === 'login' ? 'active' : ''}" onclick="window.FeatherAccount.switchAuthMode('login')">
            Member Sign In
          </button>
          <button type="button" class="auth-nav-tab ${authMode === 'register' ? 'active' : ''}" onclick="window.FeatherAccount.switchAuthMode('register')">
            Create Account (+100 Coins)
          </button>
        </div>

        <div id="auth-alert-box" style="display: none; background: #FDF2F4; border: 1px solid #F87171; color: #DC2626; padding: 0.75rem 1rem; border-radius: var(--radius-sm); font-size: 0.82rem; font-weight: 600; margin-bottom: 1.25rem;"></div>

        <!-- SIGN IN FORM -->
        ${authMode === 'login' ? renderLoginForm() : renderRegisterFlow()}

      </div>
    `;

    // Re-initialize any Lottie icons in the rendered template
    if (window.FeatherLottie && window.FeatherLottie.init) {
      window.FeatherLottie.init();
    }
  }

  function renderLoginForm() {
    return `
      <!-- 1-Click Demo Shortcut -->
      <div style="background: #FDF7FB; border: 1px dashed #E0245E; border-radius: var(--radius-sm); padding: 0.75rem 1rem; margin-bottom: 1.25rem; text-align: center;">
        <span style="font-size: 0.75rem; font-weight: 700; color: #E0245E; display: block; margin-bottom: 0.4rem;">⚡ Quick Demo Login:</span>
        <button type="button" class="btn-primary" style="height: 36px; font-size: 0.8rem; width: 100%; justify-content: center; background: linear-gradient(135deg, #6A1B6E, #B5258F);" onclick="window.FeatherAccount.quickDemoLogin()">
          Sign In as Arun Kumar (VIP Member)
        </button>
      </div>

      <form onsubmit="window.FeatherAccount.handleUserLogin(event)">
        <div style="margin-bottom: 1rem;">
          <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.35rem;">
            Registered Email ID or Mobile Number
          </label>
          <input 
            type="text" 
            id="auth-login-identifier" 
            class="form-input" 
            style="height: 44px; width: 100%;" 
            placeholder="arun.kumar@featherfits.com or 9876543210" 
            value="arun.kumar@featherfits.com"
            required 
          />
        </div>

        <div style="margin-bottom: 1.5rem;">
          <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.35rem;">
            Password
          </label>
          <input 
            type="password" 
            id="auth-login-pass" 
            class="form-input" 
            style="height: 44px; width: 100%;" 
            value="user123"
            placeholder="••••••••" 
            required 
          />
        </div>

        <button type="submit" id="auth-login-submit-btn" class="btn-primary" style="width: 100%; height: 46px; justify-content: center; font-size: 0.95rem; background: linear-gradient(135deg, #6A1B6E, #E0245E);">
          <span>Sign In to VIP Dashboard →</span>
        </button>
      </form>

      <div style="text-align: center; margin-top: 1.5rem; font-size: 0.82rem; color: #724E79;">
        New to Feather Fits? 
        <button type="button" style="color: #E0245E; font-weight: 800; text-decoration: underline;" onclick="window.FeatherAccount.switchAuthMode('register')">
          Register with Mobile & OTP
        </button>
      </div>
    `;
  }

  function renderRegisterFlow() {
    if (registerStep === 'otp') {
      return renderOtpVerificationStep();
    }

    return `
      <form onsubmit="window.FeatherAccount.handleSendOtp(event)">
        <div style="margin-bottom: 1rem;">
          <label style="display: flex; align-items: center; justify-content: space-between; font-size: 0.8rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.35rem;">
            <span>Full Name</span>
          </label>
          <input 
            type="text" 
            id="reg-name" 
            class="form-input" 
            style="height: 44px; width: 100%;" 
            placeholder="e.g. Priya Sundaram" 
            required 
          />
        </div>

        <div style="margin-bottom: 1rem;">
          <label style="display: flex; align-items: center; justify-content: space-between; font-size: 0.8rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.35rem;">
            <span>Email Address <span class="field-requirement-badge">MUST</span></span>
            <span style="font-size: 0.72rem; color: #724E79;">For invoice & tracking</span>
          </label>
          <input 
            type="email" 
            id="reg-email" 
            class="form-input" 
            style="height: 44px; width: 100%;" 
            placeholder="priya@example.com" 
            required 
          />
        </div>

        <div style="margin-bottom: 1rem;">
          <label style="display: flex; align-items: center; justify-content: space-between; font-size: 0.8rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.35rem;">
            <span>Mobile Phone Number <span class="field-requirement-badge">MUST</span></span>
            <span style="font-size: 0.72rem; color: #724E79;">10 digits</span>
          </label>
          <div style="display: flex; align-items: center; gap: 0.5rem;">
            <span style="padding: 0.65rem 0.85rem; background: #FAF0F6; border: 1px solid #F0D7E7; border-radius: var(--radius-sm); font-size: 0.85rem; font-weight: 800; color: #3B0F4A;">
              +91
            </span>
            <input 
              type="tel" 
              id="reg-phone" 
              class="form-input" 
              style="height: 44px; flex: 1;" 
              placeholder="98401 23456" 
              maxlength="10"
              pattern="[0-9]{10}"
              required 
            />
          </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
          <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.35rem;">
            Create Password
          </label>
          <input 
            type="password" 
            id="reg-password" 
            class="form-input" 
            style="height: 44px; width: 100%;" 
            placeholder="Min 6 characters" 
            minlength="6"
            required 
          />
        </div>

        <button type="submit" id="reg-submit-btn" class="btn-primary" style="width: 100%; height: 46px; justify-content: center; font-size: 0.95rem; background: linear-gradient(135deg, #E0245E, #F26D6D);">
          <span>Send Verification Code (OTP) →</span>
        </button>
      </form>

      <div style="text-align: center; margin-top: 1.5rem; font-size: 0.82rem; color: #724E79;">
        Already registered? 
        <button type="button" style="color: #6A1B6E; font-weight: 800; text-decoration: underline;" onclick="window.FeatherAccount.switchAuthMode('login')">
          Sign In
        </button>
      </div>
    `;
  }

  function renderOtpVerificationStep() {
    const email = pendingRegisterData?.email || "";
    const phone = pendingRegisterData?.phone || "";

    return `
      <div style="text-align: center; margin-bottom: 1rem;">
        <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 999px; background: #FDF2F4; color: #E0245E; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; margin-bottom: 0.5rem;">
          STEP 2: OTP VERIFICATION
        </span>
        <h3 style="font-size: 1.15rem; font-weight: 800; color: #3B0F4A; margin-bottom: 0.25rem;">
          Enter 6-Digit Code
        </h3>
        <p style="font-size: 0.8rem; color: #724E79;">
          We dispatched a verification code to:<br />
          <strong>${email}</strong> & <strong>+91 ${phone}</strong>
        </p>
      </div>

      <!-- Demo OTP Notification Hint -->
      ${activeDemoOtp ? `
        <div class="otp-demo-alert">
          <div>
            <span style="font-size: 0.72rem; text-transform: uppercase; font-weight: 800; display: block; color: #724E79;">
              ⚡ Live Simulated SMS / Mail OTP:
            </span>
            <strong>${activeDemoOtp}</strong>
          </div>
          <button type="button" style="font-size: 0.75rem; padding: 0.3rem 0.6rem; border-radius: 4px; background: #E0245E; color: #fff; font-weight: 700; border: none; cursor: pointer;" onclick="document.getElementById('otp-code-input').value = '${activeDemoOtp}'">
            Auto-Fill
          </button>
        </div>
      ` : ''}

      <form onsubmit="window.FeatherAccount.handleVerifyOtpAndRegister(event)">
        <div style="margin-bottom: 1.25rem;">
          <input 
            type="text" 
            id="otp-code-input" 
            class="otp-input-field" 
            placeholder="••••••" 
            maxlength="6" 
            value="${activeDemoOtp}"
            pattern="[0-9]{6}" 
            required 
            autofocus 
          />
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; font-size: 0.8rem; color: #724E79;">
          <span>Code valid for 10 minutes</span>
          <button type="button" id="resend-otp-btn" style="color: #6A1B6E; font-weight: 700; background: none; border: none; cursor: pointer; text-decoration: underline;" onclick="window.FeatherAccount.resendOtp()">
            Resend Code
          </button>
        </div>

        <button type="submit" id="verify-otp-submit-btn" class="btn-primary" style="width: 100%; height: 48px; justify-content: center; font-size: 0.95rem; background: linear-gradient(135deg, #6A1B6E, #E0245E);">
          <span>Verify OTP & Activate Membership (+100 Coins) →</span>
        </button>

        <div style="text-align: center; margin-top: 1.25rem;">
          <button type="button" style="font-size: 0.8rem; color: #724E79; background: none; border: none; cursor: pointer;" onclick="window.FeatherAccount.editRegisterDetails()">
            &larr; Change Email or Phone Number
          </button>
        </div>
      </form>
    `;
  }

  // =========================================================================
  // 2. MEMBER DASHBOARD (WHEN LOGGED IN)
  // =========================================================================
  function renderDashboard(root) {
    const u = currentUser;

    root.innerHTML = `
      <div style="max-width: 980px; margin: 1.5rem auto 3.5rem auto; display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- User Header Banner -->
        <div style="background: var(--color-surface); border: 1.5px solid var(--color-border); border-radius: var(--radius-lg); padding: 1.5rem 1.75rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.25rem; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
          
          <div style="display: flex; align-items: center; gap: 1.15rem;">
            <div style="width: 58px; height: 58px; border-radius: 50%; background: linear-gradient(135deg, #6A1B6E, #E0245E); color: #fff; font-size: 1.35rem; font-weight: 800; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(106, 27, 110, 0.3);">
              ${(u.firstName?.[0] || u.name?.[0] || "U").toUpperCase() + (u.lastName?.[0] || "").toUpperCase()}
            </div>
            <div>
              <div style="display: flex; align-items: center; gap: 0.6rem;">
                <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.35rem; font-weight: 800; color: #3B0F4A; margin: 0;">
                  ${u.name || (u.firstName + " " + (u.lastName || ""))}
                </h2>
                <span style="font-size: 0.72rem; font-weight: 800; background: #FDF2F4; color: #E0245E; border: 1px solid #F87171; padding: 0.15rem 0.55rem; border-radius: 999px;">
                  ${u.tier || "VIP Member"}
                </span>
              </div>
              <p style="font-size: 0.82rem; color: #724E79; margin: 0.15rem 0 0 0;">
                ${u.email} &bull; ${u.phone || "No mobile"}
              </p>
            </div>
          </div>

          <div style="display: flex; align-items: center; gap: 0.75rem;">
            <!-- Coins Badge -->
            <div style="display: flex; align-items: center; gap: 0.5rem; background: #FDF7FB; border: 1px solid #F0D7E7; padding: 0.45rem 0.85rem; border-radius: 999px; font-size: 0.82rem; font-weight: 700; color: #3B0F4A;">
              <span>🪙</span>
              <span>${u.coins || 100} Coins</span>
            </div>

            <!-- Sign Out Button -->
            <button type="button" class="btn-secondary" style="height: 38px; font-size: 0.82rem; padding: 0 1rem;" onclick="window.FeatherAccount.signOut()">
              Sign Out
            </button>
          </div>
        </div>

        <!-- 3 Primary Tabs Navigation -->
        <div style="display: flex; gap: 0.5rem; border-bottom: 2px solid var(--color-border); padding-bottom: 0.25rem;">
          <button type="button" class="tab-btn ${activeTab === 'orders' ? 'active' : ''}" style="padding: 0.65rem 1.25rem; font-weight: 700; border-radius: var(--radius-sm); border: none; cursor: pointer; background: ${activeTab === 'orders' ? '#6A1B6E' : 'transparent'}; color: ${activeTab === 'orders' ? '#fff' : '#724E79'};" onclick="window.FeatherAccount.setTab('orders')">
            📦 My Orders & Live Tracking
          </button>
          <button type="button" class="tab-btn ${activeTab === 'addresses' ? 'active' : ''}" style="padding: 0.65rem 1.25rem; font-weight: 700; border-radius: var(--radius-sm); border: none; cursor: pointer; background: ${activeTab === 'addresses' ? '#6A1B6E' : 'transparent'}; color: ${activeTab === 'addresses' ? '#fff' : '#724E79'};" onclick="window.FeatherAccount.setTab('addresses')">
            📍 Saved Addresses
          </button>
          <button type="button" class="tab-btn ${activeTab === 'profile' ? 'active' : ''}" style="padding: 0.65rem 1.25rem; font-weight: 700; border-radius: var(--radius-sm); border: none; cursor: pointer; background: ${activeTab === 'profile' ? '#6A1B6E' : 'transparent'}; color: ${activeTab === 'profile' ? '#fff' : '#724E79'};" onclick="window.FeatherAccount.setTab('profile')">
            👤 Profile & Preferences
          </button>
        </div>

        <!-- Active Tab Content -->
        <div style="background: var(--color-surface); border: 1.5px solid var(--color-border); border-radius: var(--radius-lg); padding: 1.75rem; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
          ${activeTab === 'orders' ? renderOrdersTab() : ''}
          ${activeTab === 'addresses' ? renderAddressesTab() : ''}
          ${activeTab === 'profile' ? renderProfileTab() : ''}
        </div>

      </div>
    `;

    if (window.FeatherLottie && window.FeatherLottie.init) {
      window.FeatherLottie.init();
    }
  }

  function renderOrdersTab() {
    if (userOrders.length === 0) {
      return `
        <div style="text-align: center; padding: 3rem 1rem;">
          <div style="font-size: 3rem; margin-bottom: 0.5rem;">📦</div>
          <h3 style="font-size: 1.25rem; font-weight: 800; color: #3B0F4A; margin-bottom: 0.5rem;">No Orders Yet</h3>
          <p style="font-size: 0.85rem; color: #724E79; margin-bottom: 1.5rem;">You haven't placed any orders yet. Discover our latest oversized anime and vintage streetwear drops!</p>
          <a href="index.php" class="btn-primary" style="display: inline-flex; height: 42px; padding: 0 1.5rem; justify-content: center;">
            Explore New Arrivals →
          </a>
        </div>
      `;
    }

    const stages = ['Placed', 'Packed', 'Shipped', 'Out for Delivery', 'Delivered'];

    function getStepIndex(status) {
      const s = (status || '').toLowerCase();
      if (s === 'delivered') return 4;
      if (s === 'out for delivery') return 3;
      if (s === 'shipped') return 2;
      if (s === 'packed' || s === 'processing') return 1;
      return 0;
    }

    function buildCarrierUrl(carrier, tracking) {
      if (!tracking || tracking === 'Pending') return '';
      const t = encodeURIComponent(String(tracking).trim());
      const c = (carrier || '').toLowerCase();
      if (c.includes('blue dart')) return `https://www.bluedart.com/tracking?trackNumber=${t}`;
      if (c.includes('delhivery')) return `https://www.delhivery.com/track/package/${t}`;
      if (c.includes('post')) return `https://www.indiapost.gov.in/_layouts/15/dop.portal.tracking/trackconsignment.aspx?consignmentNo=${t}`;
      if (c.includes('dtdc')) return `https://www.dtdc.in/tracking/shipment-tracking.asp?tracking_no=${t}`;
      return `https://www.google.com/search?q=${encodeURIComponent((carrier || '') + ' tracking ' + t)}`;
    }

    const phone10 = (currentUser?.phone || '9876543210').replace(/[^0-9]/g, '').slice(-10);

    return `
      <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
          <div>
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #3B0F4A; margin: 0;">
              My Orders & Live Tracking
            </h3>
            <span style="font-size: 0.78rem; color: #724E79;">Real-time progress, carrier dispatch details & estimated delivery</span>
          </div>

          <a href="track.php" class="btn-secondary" style="height: 36px; font-size: 0.78rem; padding: 0 0.95rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; border-color: #6A1B6E; color: #6A1B6E;">
            <span>🔍 Public Track Portal</span>
          </a>
        </div>

        <!-- Notification Note: Order confirmation notice -->
        <div style="background: #FAF0F6; border: 1px solid #F0D7E7; border-radius: var(--radius-sm); padding: 0.75rem 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.65rem;">
          <span style="font-size: 1.1rem;">🔔</span>
          <span style="font-size: 0.8rem; color: #3B0F4A; line-height: 1.4;">
            <strong>Tracking Notice:</strong> Tracking details ship aana udane SMS & WhatsApp la live quicklink oda anuppuvom. "Where is my order?" calls thevai illa!
          </span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1.75rem;">
          ${userOrders.map(order => {
            const currentStepIdx = getStepIndex(order.status);
            const orderId = order.orderNumber || order.id || 'FF-89214';
            const carrier = order.carrier || 'Blue Dart Express';
            const trackingNo = order.trackingNumber || 'Pending';
            const hasTracking = trackingNo && trackingNo !== 'Pending';
            const carrierTrackUrl = buildCarrierUrl(carrier, trackingNo);
            const publicTrackUrl = `track.php?order=${encodeURIComponent(orderId)}&phone=${phone10}`;
            const waHelpMsg = encodeURIComponent(`Hi Feather Fits team, en order #${orderId} pathi status update thevai paduthu. Please help!`);

            return `
              <div style="border: 1.5px solid #F0D7E7; border-radius: var(--radius-md); padding: 1.5rem; background: #FFFFFF; box-shadow: 0 4px 14px rgba(106, 27, 110, 0.04);">
                
                <!-- Order Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid #F0D7E7; padding-bottom: 1rem; margin-bottom: 1.25rem;">
                  <div>
                    <span style="font-size: 0.72rem; text-transform: uppercase; font-weight: 800; color: #724E79; display: block;">ORDER NUMBER</span>
                    <strong style="font-size: 1.1rem; color: #3B0F4A; font-family: monospace;">#${orderId}</strong>
                  </div>
                  <div>
                    <span style="font-size: 0.72rem; text-transform: uppercase; font-weight: 800; color: #724E79; display: block;">ESTIMATED DELIVERY</span>
                    <strong style="font-size: 0.92rem; color: #059669;">${order.estimatedDelivery || 'In 2-3 Days'}</strong>
                  </div>
                  <div>
                    <span style="font-size: 0.72rem; text-transform: uppercase; font-weight: 800; color: #724E79; display: block;">TOTAL PAID</span>
                    <strong style="font-size: 1.15rem; color: #E0245E;">₹${Number(order.total).toLocaleString('en-IN')}</strong>
                  </div>
                </div>

                <!-- 5-Stage Stepper Progress Bar -->
                <div style="margin-bottom: 1.5rem; padding: 0.5rem 0.25rem;">
                  <div style="display: flex; justify-content: space-between; position: relative; margin-bottom: 0.65rem;">
                    <!-- Track Line Behind -->
                    <div style="position: absolute; top: 12px; left: 20px; right: 20px; height: 3px; background: #F0D7E7; z-index: 1;"></div>
                    <div style="position: absolute; top: 12px; left: 20px; width: ${Math.min(100, Math.max(0, (currentStepIdx / 4) * 100))}%; height: 3px; background: linear-gradient(90deg, #6A1B6E, #E0245E); z-index: 2; transition: width 0.3s ease;"></div>

                    ${stages.map((stg, i) => {
                      const isCompleted = i <= currentStepIdx;
                      const isCurrent = i === currentStepIdx;
                      return `
                        <div style="position: relative; z-index: 3; display: flex; flex-direction: column; align-items: center; width: 60px;">
                          <div style="width: 26px; height: 26px; border-radius: 50%; background: ${isCompleted ? (isCurrent ? '#E0245E' : '#6A1B6E') : '#FFFFFF'}; border: 2px solid ${isCompleted ? (isCurrent ? '#E0245E' : '#6A1B6E') : '#CBD5E1'}; color: ${isCompleted ? '#fff' : '#94A3B8'}; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800; box-shadow: ${isCurrent ? '0 0 0 3px rgba(224, 36, 94, 0.25)' : 'none'};">
                            ${isCompleted ? '✓' : (i + 1)}
                          </div>
                          <span style="font-size: 0.68rem; font-weight: ${isCompleted ? '800' : '600'}; color: ${isCompleted ? '#3B0F4A' : '#94A3B8'}; margin-top: 0.35rem; text-align: center; line-height: 1.2;">
                            ${stg}
                          </span>
                        </div>
                      `;
                    }).join('')}
                  </div>
                </div>

                <!-- Carrier & Tracking Info Box -->
                <div style="background: #FDF7FB; border: 1px solid #F0D7E7; border-radius: var(--radius-sm); padding: 1rem 1.25rem; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                  <div>
                    <span style="font-size: 0.7rem; font-weight: 800; color: #724E79; text-transform: uppercase;">CARRIER & AWB TRACKING</span>
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.2rem;">
                      <span style="font-weight: 800; color: #3B0F4A; font-size: 0.88rem;">${carrier}</span>
                      <code style="background: #FFFFFF; border: 1px solid #F0D7E7; padding: 0.2rem 0.55rem; border-radius: 4px; font-weight: 800; color: #6A1B6E; font-size: 0.82rem;">${trackingNo}</code>
                    </div>
                  </div>

                  <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                    ${hasTracking ? `
                      <button type="button" class="btn-secondary" style="height: 34px; font-size: 0.75rem; padding: 0 0.85rem;" onclick="navigator.clipboard.writeText('${trackingNo}').then(() => alert('AWB Tracking number copied: ${trackingNo}'));">
                        📋 Copy AWB
                      </button>
                      <a href="${carrierTrackUrl}" target="_blank" class="btn-primary" style="height: 34px; font-size: 0.75rem; padding: 0 0.95rem; background: #6A1B6E; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                        <span>🚀 Track Parcel ↗</span>
                      </a>
                    ` : `
                      <span style="font-size: 0.78rem; color: #D97706; font-weight: 700;">⏳ Packing in progress &bull; Tracking will update soon</span>
                    `}
                    <a href="${publicTrackUrl}" target="_blank" class="btn-secondary" style="height: 34px; font-size: 0.75rem; padding: 0 0.85rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; color: #B5258F; border-color: #F0D7E7;">
                      <span>Live Tracker</span>
                    </a>
                  </div>
                </div>

                <!-- Items in Order -->
                <div style="display: flex; flex-direction: column; gap: 0.75rem; border-bottom: 1px dashed #F0D7E7; padding-bottom: 1rem; margin-bottom: 1rem;">
                  ${(order.items || []).map(item => `
                    <div style="display: flex; align-items: center; gap: 1rem;">
                      <img src="${item.image || 'assets/images/anime-tee-model-front.jpg'}" style="width: 54px; height: 54px; border-radius: var(--radius-xs); object-fit: cover; border: 1px solid var(--color-border);" />
                      <div style="flex: 1;">
                        <div style="font-size: 0.9rem; font-weight: 800; color: #3B0F4A;">${item.name}</div>
                        <div style="font-size: 0.78rem; color: #724E79;">Size: ${item.size || 'L'} &bull; Qty: ${item.quantity || item.qty || 1} &bull; ₹${item.price || 1299}</div>
                      </div>
                    </div>
                  `).join('')}
                </div>

                <!-- Footer Help concierge -->
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                  <span style="font-size: 0.78rem; color: #724E79;">
                    Parcel update aagala? Direct support available 24/7.
                  </span>
                  <a href="https://wa.me/919876543210?text=${waHelpMsg}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.78rem; font-weight: 800; color: #25D366; text-decoration: none;">
                    <span>💬 WhatsApp Support (Order #${orderId})</span>
                  </a>
                </div>

              </div>
            `;
          }).join('')}
        </div>
      </div>
    `;
  }

  function renderAddressesTab() {
    const addresses = currentUser.addresses || [];

    return `
      <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
          <h3 style="font-size: 1.15rem; font-weight: 800; color: #3B0F4A; margin: 0;">Saved Delivery Addresses</h3>
          <button type="button" class="btn-primary" style="height: 38px; font-size: 0.82rem; padding: 0 1rem; background: #6A1B6E;" onclick="window.FeatherAccount.toggleAddressForm()">
            ${showAddAddress ? 'Cancel' : '+ Add New Address'}
          </button>
        </div>

        ${showAddAddress ? `
          <form onsubmit="window.FeatherAccount.saveNewAddress(event)" style="background: #FDF7FB; border: 1px solid #F0D7E7; border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.5rem;">
            <h4 style="font-size: 0.95rem; font-weight: 800; color: #3B0F4A; margin-bottom: 1rem;">New Address Details</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
              <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.3rem;">Address Tag</label>
                <select id="new-addr-tag" class="form-input" style="height: 40px; width: 100%;">
                  <option value="Home">Home</option>
                  <option value="Office">Office</option>
                  <option value="Other">Other</option>
                </select>
              </div>
              <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.3rem;">Full Name</label>
                <input type="text" id="new-addr-name" class="form-input" style="height: 40px; width: 100%;" value="${currentUser.name || currentUser.firstName}" required />
              </div>
            </div>
            <div style="margin-bottom: 1rem;">
              <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.3rem;">Street Address / Flat No.</label>
              <input type="text" id="new-addr-line" class="form-input" style="height: 40px; width: 100%;" placeholder="e.g. 42, Gandhi Road, T. Nagar" required />
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
              <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.3rem;">City</label>
                <input type="text" id="new-addr-city" class="form-input" style="height: 40px; width: 100%;" value="Chennai" required />
              </div>
              <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.3rem;">State</label>
                <input type="text" id="new-addr-state" class="form-input" style="height: 40px; width: 100%;" value="Tamil Nadu" required />
              </div>
              <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.3rem;">PIN Code</label>
                <input type="text" id="new-addr-zip" class="form-input" style="height: 40px; width: 100%;" placeholder="600017" required />
              </div>
            </div>
            <button type="submit" class="btn-primary" style="height: 40px; font-size: 0.85rem; padding: 0 1.5rem; background: #E0245E;">Save Address</button>
          </form>
        ` : ''}

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
          ${addresses.map(addr => `
            <div style="border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 1.25rem; background: #FFFFFF; position: relative;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.6rem;">
                <span style="font-size: 0.75rem; font-weight: 800; padding: 0.15rem 0.55rem; border-radius: 4px; background: #FAF0F6; color: #6A1B6E;">${addr.tag}</span>
                ${addr.isDefault ? '<span style="font-size: 0.7rem; font-weight: 800; color: #059669;">Default</span>' : ''}
              </div>
              <div style="font-weight: 800; color: #3B0F4A; font-size: 0.95rem; margin-bottom: 0.25rem;">${addr.name}</div>
              <div style="font-size: 0.82rem; color: #724E79; line-height: 1.4; margin-bottom: 0.75rem;">
                ${addr.line1}<br />
                ${addr.city}, ${addr.state} - ${addr.postalCode}<br />
                Phone: ${addr.phone}
              </div>
              <div style="display: flex; gap: 0.75rem;">
                ${!addr.isDefault ? `<button type="button" style="font-size: 0.75rem; font-weight: 700; color: #6A1B6E; background: none; border: none; cursor: pointer; text-decoration: underline;" onclick="window.FeatherAccount.setDefaultAddress('${addr.id}')">Set as Default</button>` : ''}
                <button type="button" style="font-size: 0.75rem; font-weight: 700; color: #DC2626; background: none; border: none; cursor: pointer; text-decoration: underline;" onclick="window.FeatherAccount.deleteAddress('${addr.id}')">Delete</button>
              </div>
            </div>
          `).join('')}
        </div>
      </div>
    `;
  }

  function renderProfileTab() {
    const u = currentUser;

    return `
      <div>
        <h3 style="font-size: 1.15rem; font-weight: 800; color: #3B0F4A; margin-bottom: 1.25rem;">
          VIP Profile & Sizing Preferences
        </h3>
        <form onsubmit="window.FeatherAccount.saveProfile(event)" style="max-width: 580px;">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
            <div>
              <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.35rem;">Full Name</label>
              <input type="text" id="edit-name" class="form-input" style="height: 42px; width: 100%;" value="${u.name || (u.firstName + ' ' + (u.lastName || ''))}" required />
            </div>
            <div>
              <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.35rem;">Preferred Size</label>
              <select id="edit-size" class="form-input" style="height: 42px; width: 100%;">
                <option value="S" ${u.preferredSize === 'S' ? 'selected' : ''}>S - Small</option>
                <option value="M" ${u.preferredSize === 'M' ? 'selected' : ''}>M - Medium</option>
                <option value="L" ${u.preferredSize === 'L' ? 'selected' : ''}>L - Large (Oversized)</option>
                <option value="XL" ${u.preferredSize === 'XL' ? 'selected' : ''}>XL - Extra Large</option>
                <option value="XXL" ${u.preferredSize === 'XXL' ? 'selected' : ''}>XXL - 2X Large</option>
              </select>
            </div>
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.35rem;">Email ID</label>
            <input type="email" id="edit-email" class="form-input" style="height: 42px; width: 100%;" value="${u.email}" required />
          </div>

          <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #3B0F4A; margin-bottom: 0.35rem;">Phone Number</label>
            <input type="tel" id="edit-phone" class="form-input" style="height: 42px; width: 100%;" value="${u.phone}" required />
          </div>

          <button type="submit" class="btn-primary" style="height: 44px; padding: 0 1.5rem; font-size: 0.88rem; background: #6A1B6E;">
            Save Profile Changes
          </button>
        </form>
      </div>
    `;
  }

  function showAuthAlert(msg) {
    const box = document.getElementById("auth-alert-box");
    if (box) {
      box.textContent = msg;
      box.style.display = "block";
    } else {
      alert(msg);
    }
  }

  // =========================================================================
  // 3. PUBLIC ACCOUNT API HANDLERS
  // =========================================================================
  window.FeatherAccount = {
    setTab(t) {
      activeTab = t;
      render();
    },

    switchAuthMode(mode) {
      authMode = mode;
      registerStep = "form";
      activeDemoOtp = "";
      render();
    },

    quickDemoLogin() {
      currentUser = defaultUser;
      saveUser();
      render();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast("Signed in as Arun Kumar (VIP Member)!");
      }
    },

    // User Login (Email / Phone + Password)
    handleUserLogin(e) {
      e.preventDefault();
      const identifier = document.getElementById("auth-login-identifier").value.trim();
      const pass = document.getElementById("auth-login-pass").value;
      const btn = document.getElementById("auth-login-submit-btn");

      btn.disabled = true;
      btn.innerHTML = "<span>Authenticating...</span>";

      fetch("api/auth.php?action=user_login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ identifier, password: pass })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          currentUser = data.user;
          saveUser();
          render();
          if (window.FeatherApp && window.FeatherApp.showToast) {
            window.FeatherApp.showToast(data.message || "Signed in successfully!");
          }
        } else {
          showAuthAlert(data.error || "Login failed.");
          btn.disabled = false;
          btn.innerHTML = "<span>Sign In to VIP Dashboard →</span>";
        }
      })
      .catch(err => {
        showAuthAlert("Connection error. Please try again.");
        btn.disabled = false;
        btn.innerHTML = "<span>Sign In to VIP Dashboard →</span>";
      });
    },

    // Step 1: Send OTP for New Registration
    handleSendOtp(e) {
      e.preventDefault();
      const name = document.getElementById("reg-name").value.trim();
      const email = document.getElementById("reg-email").value.trim();
      const phone = document.getElementById("reg-phone").value.trim();
      const password = document.getElementById("reg-password").value;
      const btn = document.getElementById("reg-submit-btn");

      if (!email || !email.includes("@")) {
        showAuthAlert("Valid Email Address is mandatory.");
        return;
      }

      if (!phone || phone.length !== 10) {
        showAuthAlert("10-Digit Mobile Number is mandatory.");
        return;
      }

      pendingRegisterData = { name, email, phone, password };

      btn.disabled = true;
      btn.innerHTML = "<span>Sending OTP Code...</span>";

      fetch("api/auth.php?action=send_otp", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, phone, type: "register" })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          activeDemoOtp = data.demoOtp || "";
          registerStep = "otp";
          render();
          if (window.FeatherApp && window.FeatherApp.showToast) {
            window.FeatherApp.showToast("OTP sent to " + email + " and +91 " + phone);
          }
        } else {
          showAuthAlert(data.error || "Failed to send OTP.");
          btn.disabled = false;
          btn.innerHTML = "<span>Send Verification Code (OTP) →</span>";
        }
      })
      .catch(err => {
        showAuthAlert("Connection error. Please try again.");
        btn.disabled = false;
        btn.innerHTML = "<span>Send Verification Code (OTP) →</span>";
      });
    },

    // Step 2: Verify OTP & Activate Account
    handleVerifyOtpAndRegister(e) {
      e.preventDefault();
      const otp = document.getElementById("otp-code-input").value.trim();
      const btn = document.getElementById("verify-otp-submit-btn");

      if (!otp || otp.length !== 6) {
        showAuthAlert("Please enter the complete 6-digit verification code.");
        return;
      }

      btn.disabled = true;
      btn.innerHTML = "<span>Verifying & Activating...</span>";

      fetch("api/auth.php?action=verify_otp_and_register", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          name: pendingRegisterData.name,
          email: pendingRegisterData.email,
          phone: pendingRegisterData.phone,
          password: pendingRegisterData.password,
          otp: otp
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          currentUser = data.user;
          saveUser();
          registerStep = "form";
          pendingRegisterData = null;
          activeDemoOtp = "";
          render();
          if (window.FeatherApp && window.FeatherApp.showToast) {
            window.FeatherApp.showToast("Account verified & activated! +100 Welcome Coins added.", "success");
          }
        } else {
          showAuthAlert(data.error || "OTP verification failed.");
          btn.disabled = false;
          btn.innerHTML = "<span>Verify OTP & Activate Membership (+100 Coins) →</span>";
        }
      })
      .catch(err => {
        showAuthAlert("Connection error. Please try again.");
        btn.disabled = false;
        btn.innerHTML = "<span>Verify OTP & Activate Membership (+100 Coins) →</span>";
      });
    },

    resendOtp() {
      if (!pendingRegisterData) return;
      const btn = document.getElementById("resend-otp-btn");
      if (btn) btn.textContent = "Sending...";

      fetch("api/auth.php?action=send_otp", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          email: pendingRegisterData.email,
          phone: pendingRegisterData.phone,
          type: "register"
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          activeDemoOtp = data.demoOtp || "";
          render();
          if (window.FeatherApp && window.FeatherApp.showToast) {
            window.FeatherApp.showToast("New OTP sent: " + activeDemoOtp);
          }
        } else {
          showAuthAlert(data.error || "Could not resend OTP.");
        }
      });
    },

    editRegisterDetails() {
      registerStep = "form";
      render();
    },

    saveProfile(e) {
      if (e) e.preventDefault();
      const name = document.getElementById("edit-name")?.value.trim();
      const email = document.getElementById("edit-email")?.value.trim();
      const phone = document.getElementById("edit-phone")?.value.trim();
      const size = document.getElementById("edit-size")?.value || "L";

      if (!name || !email) {
        alert("Name and email are required.");
        return;
      }

      currentUser.name = name;
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
      const name = document.getElementById("new-addr-name")?.value.trim() || currentUser.name;
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
      saveUser();
      fetch('api/auth.php?action=user_logout');
      authMode = "login";
      registerStep = "form";
      render();
      if (window.FeatherApp && window.FeatherApp.showToast) {
        window.FeatherApp.showToast("Signed out from VIP Account.");
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
