<?php
/**
 * FEATHER FITS — MODALS & DRAWERS COMPONENT
 */
?>
  <!-- Product Detail Modal (Size & Color Selector Quick View) -->
  <div class="modal-overlay" id="product-detail-modal" role="dialog" aria-modal="true">
    <div class="modal-container">
      <button class="modal-close-btn" onclick="window.FeatherApp.closeModals()" aria-label="Close Modal">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
      <div id="product-modal-body">
        <!-- Injected via JavaScript -->
      </div>
    </div>
  </div>

  <!-- Slide-Over Shopping Cart Drawer -->
  <div class="drawer-overlay" id="cart-drawer-overlay">
    <aside class="drawer" id="cart-drawer" role="dialog" aria-label="Shopping Cart">
      <div class="drawer-header">
        <h3>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
          </svg>
          <span>Your Shopping Bag</span>
        </h3>
        <button class="modal-close-btn" onclick="window.FeatherApp.closeCartDrawer()" aria-label="Close Cart">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>

      <!-- Free Shipping Tracker -->
      <div class="free-shipping-bar">
        <div class="shipping-msg" id="cart-free-shipping-msg">Add ₹999 for FREE Shipping!</div>
        <div class="progress-track">
          <div class="progress-fill" id="cart-shipping-bar" style="width: 0%;"></div>
        </div>
      </div>

      <!-- Items List -->
      <div class="drawer-items-list" id="cart-drawer-items">
        <!-- Rendered by app.js -->
      </div>

      <!-- Cart Drawer Footer -->
      <div class="drawer-footer" id="cart-drawer-footer">
        <!-- Rendered by app.js -->
      </div>
    </aside>
  </div>

  <!-- Slide-Over Wishlist Drawer -->
  <div class="drawer-overlay" id="wishlist-drawer-overlay">
    <aside class="drawer" id="wishlist-drawer" role="dialog" aria-label="Wishlist">
      <div class="drawer-header">
        <h3>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" style="color: var(--color-accent);">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
          </svg>
          <span>Your Saved Wishlist</span>
        </h3>
        <button class="modal-close-btn" onclick="window.FeatherApp.closeWishlistDrawer()" aria-label="Close Wishlist">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>

      <div class="drawer-items-list" id="wishlist-drawer-items">
        <!-- Rendered by app.js -->
      </div>
    </aside>
  </div>

  <!-- Secure Checkout Modal -->
  <div class="modal-overlay" id="checkout-modal" role="dialog" aria-modal="true">
    <div class="modal-container checkout-modal-content">
      <button class="modal-close-btn" onclick="window.FeatherApp.closeModals()" aria-label="Close Checkout">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>

      <div>
        <h2 style="font-size: 1.85rem; font-weight: 800; color: var(--color-primary); margin-bottom: 0.25rem;">
          Secure Instant Checkout
        </h2>
        <p style="color: var(--color-text-muted); font-size: 0.9rem;">
          Tokenized encrypted checkout. Free doorstep express delivery across India.
        </p>
      </div>

      <div class="checkout-grid">
        <!-- Shipping & Payment Form -->
        <form id="checkout-form" onsubmit="window.FeatherApp.handleProcessPayment(event)">
          
          <h3 class="checkout-section-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
            <span>1. Contact & Shipping Address</span>
          </h3>

          <div class="form-group">
            <label for="checkout-email">Email Address (for Receipt & Tracking)</label>
            <input type="email" id="checkout-email" required class="form-input" placeholder="arjun.k@example.com" value="" />
          </div>

          <div class="form-group-row">
            <div class="form-group">
              <label for="checkout-first-name">First Name</label>
              <input type="text" id="checkout-first-name" required class="form-input" placeholder="Arjun" value="" />
            </div>
            <div class="form-group">
              <label for="checkout-last-name">Last Name</label>
              <input type="text" id="checkout-last-name" required class="form-input" placeholder="Kumar" value="" />
            </div>
          </div>

          <div class="form-group">
            <label for="checkout-address">Delivery Street Address</label>
            <input type="text" id="checkout-address" required class="form-input" placeholder="Flat No., Building Name, Street" value="" />
          </div>

          <div class="form-group-row">
            <div class="form-group">
              <label for="checkout-city">City</label>
              <input type="text" id="checkout-city" required class="form-input" placeholder="Chennai" value="" />
            </div>
            <div class="form-group">
              <label for="checkout-state">State</label>
              <input type="text" id="checkout-state" required class="form-input" placeholder="Tamil Nadu" value="" />
            </div>
          </div>

          <div class="form-group">
            <label for="checkout-zip">PIN / Postal Code</label>
            <input type="text" id="checkout-zip" required class="form-input" placeholder="600001" value="" />
          </div>

          <!-- Mobile Number & OTP Verification -->
          <h3 class="checkout-section-title" style="margin-top: 1.5rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
              <line x1="12" y1="18" x2="12.01" y2="18"></line>
            </svg>
            <span>2. Mobile Number & Verification</span>
          </h3>

          <div class="otp-verification-box">
            <div class="otp-box-header">
              <span style="font-size: 0.88rem; font-weight: 700; color: var(--color-primary);">Instant Phone Authentication</span>
              <span class="security-badge">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>SMS OTP Secured</span>
              </span>
            </div>

            <!-- Phone Number Input with Country Code and Send OTP -->
            <div class="form-group" style="margin-bottom: 0.85rem;">
              <label for="checkout-phone">Mobile Phone Number (for delivery verification)</label>
              <div class="phone-input-group">
                <span class="phone-country-code">🇮🇳 +91</span>
                <input type="tel" id="checkout-phone" required class="form-input phone-input" placeholder="Enter 10-digit number" value="" maxlength="15" />
                <button type="button" id="send-otp-btn" class="btn-send-otp" onclick="window.FeatherApp.sendCheckoutOtp()">
                  <span>Send OTP</span>
                </button>
              </div>
            </div>

            <!-- OTP Input -->
            <div class="form-group" style="margin-bottom: 0.5rem;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                <label for="checkout-otp" style="margin-bottom: 0;">Enter 4-Digit Verification Code</label>
                <span id="otp-timer-badge" class="otp-status-hint">SMS Verification</span>
              </div>
              <div class="otp-input-wrapper">
                <input type="text" id="checkout-otp" required class="form-input otp-field" placeholder="• • • •" maxlength="6" value="" />
              </div>
              <div style="margin-top: 6px; display: flex; justify-content: space-between; align-items: center;">
                <a href="checkout.php" style="font-size: 0.78rem; color: var(--color-secondary); font-weight: 600; text-decoration: none;">
                  🚀 Open Fullscreen Checkout Page →
                </a>
              </div>
            </div>

            <div class="otp-trust-notice">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
              </svg>
              <span>Instant order confirmation. Pay via UPI, Card, or Doorstep COD.</span>
            </div>
          </div>

          <button type="submit" id="pay-submit-btn" class="btn-checkout" style="margin-top: 0.5rem;">
            <span>Verify OTP & Place Order</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </button>
        </form>

        <!-- Right Side: Order Summary -->
        <div class="order-summary-card" id="checkout-order-summary">
          <!-- Rendered by app.js -->
        </div>
      </div>
    </div>
  </div>

  <!-- Order Confirmation Success Modal -->
  <div class="modal-overlay" id="order-success-modal" role="dialog" aria-modal="true">
    <div class="modal-container" style="max-width: 580px; padding: 2rem;">
      <button class="modal-close-btn" onclick="window.FeatherApp.closeModals()" aria-label="Close">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
      <div id="order-success-content">
        <!-- Rendered by app.js -->
      </div>
    </div>
  </div>

  <!-- Order Tracking Modal -->
  <div class="modal-overlay" id="tracking-modal" role="dialog" aria-modal="true">
    <div class="modal-container" style="max-width: 680px; padding: 2.25rem;">
      <button class="modal-close-btn" onclick="window.FeatherApp.closeModals()" aria-label="Close Tracking">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>

      <div style="margin-bottom: 1.5rem;">
        <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--color-secondary);">Package Logistics</span>
        <h2 style="font-size: 1.85rem; font-weight: 800; color: var(--color-primary);">Track Your Order</h2>
        <p style="color: var(--color-text-muted); font-size: 0.9rem;">
          Enter your Feather Fits order number or carrier tracking ID to track delivery in real time.
        </p>
      </div>

      <!-- Search Input -->
      <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem;">
        <input type="text" id="tracking-order-input" class="form-input" placeholder="e.g. FF-89214" value="FF-89214" style="flex:1;" />
        <button class="btn-primary" id="track-search-btn" style="padding: 0 1.5rem;">
          Track
        </button>
      </div>

      <!-- Results Display Box -->
      <div id="tracking-result-box">
        <!-- Rendered by app.js -->
      </div>

      <!-- Recent Orders List in Session -->
      <div style="margin-top: 2rem; border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
        <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.75rem;">Recent Orders In This Browser:</h4>
        <div id="recent-orders-list">
          <!-- Rendered by app.js -->
        </div>
      </div>
    </div>
  </div>

  <!-- User Account Modal -->
  <div class="modal-overlay" id="account-modal" role="dialog" aria-modal="true">
    <div class="modal-container account-modal-container">
      <button class="modal-close-btn" onclick="window.FeatherApp.closeModals()" aria-label="Close Account">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
      <div id="account-modal-body">
        <!-- Injected via app.js -->
      </div>
    </div>
  </div>

  <!-- Size Chart & Measurements Modal -->
  <div class="modal-overlay" id="size-guide-modal" role="dialog" aria-modal="true">
    <div class="modal-container" style="max-width: 680px; padding: 2rem;">
      <button class="modal-close-btn" onclick="window.FeatherApp.closeModals()" aria-label="Close Size Guide">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
      <div id="size-guide-content">
        <!-- Rendered by app.js -->
      </div>
    </div>
  </div>

  <!-- Sticky Mobile Bottom Navigation Bar -->
  <nav class="mobile-bottom-nav" aria-label="Mobile Bottom Navigation">
    <a href="index.php#catalog-section" class="mobile-nav-item" onclick="if(window.FeatherApp){window.FeatherApp.setCategory('all');}">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
      </svg>
      <span>Shop</span>
    </a>

    <button type="button" class="mobile-nav-item" onclick="window.FeatherApp.openTrackingModal();">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"></circle>
        <polyline points="12 6 12 12 16 14"></polyline>
      </svg>
      <span>Track</span>
    </button>

    <button type="button" class="mobile-nav-item" onclick="window.FeatherApp.openWishlistDrawer();">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
      </svg>
      <span>Wishlist</span>
    </button>

    <button type="button" class="mobile-nav-item" onclick="window.FeatherApp.openCartDrawer();">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="9" cy="21" r="1"></circle>
        <circle cx="20" cy="21" r="1"></circle>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
      </svg>
      <span>Cart</span>
    </button>

    <a href="account.php" class="mobile-nav-item">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
        <circle cx="12" cy="7" r="4"></circle>
      </svg>
      <span>Account</span>
    </a>
  </nav>
