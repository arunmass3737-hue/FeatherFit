<?php
/**
 * FEATHER FITS — FOOTER COMPONENT
 */
?>
  <!-- Footer -->
  <footer class="site-footer">
    <div class="footer-container">
      
      <div class="footer-brand">
        <a href="index.php" style="display: inline-block;">
          <img src="assets/images/feather-fits-logo.png" alt="Feather Fits" style="height: 52px; filter: brightness(0) invert(1);" />
        </a>
        <p>
          Feather Fits is dedicated to confidence, comfort, and uncompromising self-expression through luxury streetwear, iconic anime art, cinema graphics, and minimalist essentials.
        </p>
      </div>

      <div class="footer-col">
        <h4>Browse Departments</h4>
        <ul>
          <li><a href="index.php?department=all#catalog-section" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('all');}">Home & All Drops</a></li>
          <li><a href="index.php?department=men#catalog-section" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('men');}">Men's Collection</a></li>
          <li><a href="index.php?department=women#catalog-section" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('women');}">Women's Collection</a></li>
          <li><a href="index.php?department=kids#catalog-section" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('kids');}">Kids' Streetwear</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Customer Care</h4>
        <ul>
          <li><a href="track.php">Track Your Order</a></li>
          <li><a href="javascript:void(0)" onclick="if(window.FeatherApp){window.FeatherApp.openWishlistDrawer();}">Saved Wishlist</a></li>
          <li><a href="javascript:void(0)" onclick="if(window.FeatherApp){window.FeatherApp.openCartDrawer();}">Shopping Bag</a></li>
          <li><a href="account.php">My Account & Orders</a></li>
          <li><a href="javascript:void(0)" onclick="if(window.FeatherApp){window.FeatherApp.openSizeGuideModal('inches');}">Size & Fit Guide</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Confidence Fits You</h4>
        <p style="font-size: 0.88rem; margin-bottom: 1rem; color: #B5A4B2;">
          Subscribe to VIP drop alerts for early access to limited edition anime & movie series releases.
        </p>
        <div style="display: flex; gap: 0.5rem;">
          <input type="email" id="footer-newsletter-email" placeholder="Your email address" class="form-input" style="height: 42px; background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.2); color: #fff;" />
          <button class="btn-primary" style="padding: 0 1.25rem; height: 42px; font-size: 0.82rem; font-weight: 700;" onclick="if(window.FeatherApp){window.FeatherApp.handleNewsletterSubscribe();}">Join</button>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <div>&copy; <?php echo date('Y'); ?> Feather Fits Apparel Co. All rights reserved. &bull; CONFIDENCE FITS YOU.</div>
      <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
        <a href="privacy.php">Privacy Policy</a>
        <a href="terms.php">Terms of Service</a>
        <a href="security.php">PCI-DSS SAQ A Security</a>
      </div>
    </div>
  </footer>
