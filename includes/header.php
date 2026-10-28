<?php
/**
 * FEATHER FITS — HEADER & NAVIGATION COMPONENT
 * Variables expected:
 * $pageTitle (string)
 * $pageDescription (string)
 * $currentDept (string: 'all', 'men', 'women', 'kids')
 * $currentPage (string: 'home', 'product', 'checkout', 'account', 'admin', 'legal')
 */

$currentDept = $currentDept ?? 'all';
$currentPage = $currentPage ?? 'home';
$pageTitle = $pageTitle ?? 'Feather Fits — Confidence Fits You | Premium Apparel & Graphic T-Shirts';
$pageDescription = $pageDescription ?? 'Shop premium graphic T-shirts and luxury apparel for men and women. Discover Anime, Movie, and Plain minimalist designs crafted with superior comfort.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>" />
  <meta name="theme-color" content="#2D0B34" />

  <!-- Favicon & Brand Icons -->
  <link rel="icon" type="image/png" href="assets/images/feather-fits-logo.png" />

  <!-- Fonts & Styles -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <script src="js/lottie.min.js" defer></script>
  <script src="js/lottie-icons.js" defer></script>
</head>
<body class="<?php echo htmlspecialchars($bodyClass ?? ''); ?>">

  <!-- Top VIP Promo Announcement Bar -->
  <aside class="announcement-bar" role="complementary">
    <span class="pill">Free Delivery</span>
    <span id="announcement-bar-text"><?php echo function_exists('getFeatherAnnouncement') ? getFeatherAnnouncement() : 'Complimentary Standard Shipping on all orders over ₹999 &bull; Use code <strong>FEATHER20</strong> for 20% off'; ?></span>
  </aside>

  <!-- Sticky Site Header -->
  <header class="site-header" id="site-header">
    <div class="nav-container">
      
      <!-- Mobile Hamburger Button & Brand Logo -->
      <div style="display: flex; align-items: center; gap: 0.75rem;">
        <button type="button" class="hamburger-btn" id="mobile-menu-toggle" onclick="window.FeatherApp.toggleMobileMenu()" aria-label="Open Navigation Menu">
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
        </button>
        <a href="index.php" class="brand-logo" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('all');}" aria-label="Feather Fits Home">
          <img src="assets/images/feather-fits-logo.png" alt="Feather Fits — Confidence Fits You" />
        </a>
      </div>

      <!-- Navigation Categories (Home, Men, Women, Kids) -->
      <nav class="main-navigation" aria-label="Primary Navigation">
        <ul class="nav-links">
          <li>
            <a href="index.php?department=all#catalog-section" class="nav-link <?php echo $currentDept === 'all' && $currentPage === 'home' ? 'active' : ''; ?>" data-dept="all" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('all'); event.preventDefault();}">
              <span>Home</span>
            </a>
          </li>
          <li>
            <a href="index.php?department=men#catalog-section" class="nav-link <?php echo $currentDept === 'men' ? 'active' : ''; ?>" data-dept="men" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('men'); event.preventDefault();}">
              <span>Men</span>
            </a>
          </li>
          <li>
            <a href="index.php?department=women#catalog-section" class="nav-link <?php echo $currentDept === 'women' ? 'active' : ''; ?>" data-dept="women" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('women'); event.preventDefault();}">
              <span>Women</span>
            </a>
          </li>
          <li>
            <a href="index.php?department=kids#catalog-section" class="nav-link <?php echo $currentDept === 'kids' ? 'active' : ''; ?>" data-dept="kids" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('kids'); event.preventDefault();}">
              <span>Kids</span>
              <span class="nav-pill-badge">New</span>
            </a>
          </li>
        </ul>
      </nav>

      <!-- Action Buttons -->
      <div class="nav-actions">

        <!-- Wishlist Icon -->
        <button type="button" class="action-btn" id="wishlist-action-btn" onclick="window.FeatherApp.openWishlistDrawer()" aria-label="View Wishlist">
          <span class="lottie-icon" data-lottie-icon="wishlist" data-lottie-primary="#E0245E" data-lottie-secondary="#F26D6D" style="width: 22px; height: 22px;">
            <svg class="fallback-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
          </span>
          <span class="badge-count" id="wishlist-badge-count" style="display: none;">0</span>
        </button>

        <!-- Shopping Cart Icon -->
        <button type="button" class="action-btn" id="cart-action-btn" onclick="window.FeatherApp.openCartDrawer()" aria-label="View Cart">
          <span class="lottie-icon" data-lottie-icon="cart" data-lottie-primary="#2D1432" data-lottie-secondary="#E0245E" style="width: 22px; height: 22px;">
            <svg class="fallback-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
          </span>
          <span class="badge-count" id="cart-badge-count" style="display: none;">0</span>
        </button>

        <!-- User Account Icon -->
        <a href="account.php" class="action-btn <?php echo $currentPage === 'account' ? 'active' : ''; ?>" id="user-action-btn" aria-label="User Account Dashboard">
          <span class="lottie-icon" data-lottie-icon="customers" data-lottie-primary="#2D1432" data-lottie-secondary="#6A1B6E" style="width: 22px; height: 22px;">
            <svg class="fallback-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
          </span>
        </a>
      </div>

    </div>
  </header>

  <!-- Mobile Navigation Slide-Out Drawer -->
  <div class="drawer-overlay" id="mobile-menu-overlay">
    <aside class="drawer mobile-nav-drawer" id="mobile-menu-drawer" role="dialog" aria-label="Mobile Navigation">
      <div class="drawer-header">
        <div class="brand-logo">
          <img src="assets/images/feather-fits-logo.png" alt="Feather Fits" style="height: 38px;" />
        </div>
        <button class="modal-close-btn" onclick="window.FeatherApp.toggleMobileMenu()" aria-label="Close Navigation">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      <div class="mobile-drawer-body">
        <div class="mobile-drawer-search">
          <input 
            type="search" 
            placeholder="Search fits, anime, vintage..." 
            class="catalog-search-input" 
            style="padding-left: 1rem;"
            oninput="window.FeatherApp.handleSearchInput(this.value)" 
          />
        </div>
        <span class="mobile-drawer-heading">Browse Collections</span>
        <ul class="mobile-drawer-nav">
          <li><a href="index.php?department=all#catalog-section" class="mobile-nav-link <?php echo $currentDept === 'all' ? 'active' : ''; ?>" data-dept="all" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('all'); window.FeatherApp.toggleMobileMenu();}">🏠 Home & All Drops</a></li>
          <li><a href="index.php?department=men#catalog-section" class="mobile-nav-link <?php echo $currentDept === 'men' ? 'active' : ''; ?>" data-dept="men" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('men'); window.FeatherApp.toggleMobileMenu();}">👔 Men's Collection</a></li>
          <li><a href="index.php?department=women#catalog-section" class="mobile-nav-link <?php echo $currentDept === 'women' ? 'active' : ''; ?>" data-dept="women" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('women'); window.FeatherApp.toggleMobileMenu();}">👗 Women's Collection</a></li>
          <li><a href="index.php?department=kids#catalog-section" class="mobile-nav-link <?php echo $currentDept === 'kids' ? 'active' : ''; ?>" data-dept="kids" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('kids'); window.FeatherApp.toggleMobileMenu();}">🧒 Kids' Streetwear <span class="nav-pill-badge" style="margin-left: 6px;">New</span></a></li>
        </ul>
        <span class="mobile-drawer-heading">Quick Actions</span>
        <div class="mobile-drawer-grid">
          <a href="account.php?tab=tracking" class="mobile-shortcut-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            <span>Track Order</span>
          </a>
          <button class="mobile-shortcut-btn" onclick="window.FeatherApp.openSizeGuideModal('inches'); window.FeatherApp.toggleMobileMenu();">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
            <span>Size Chart</span>
          </button>
          <button class="mobile-shortcut-btn" onclick="window.FeatherApp.openWishlistDrawer(); window.FeatherApp.toggleMobileMenu();">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            <span>Wishlist</span>
          </button>
          <a href="account.php" class="mobile-shortcut-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span>My Dashboard</span>
          </a>
        </div>
      </div>
    </aside>
  </div>
