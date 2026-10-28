<?php
require_once __DIR__ . '/data/products.php';

$productId = $_GET['id'] ?? 'ff-anime-01';
$product = getFeatherProductById($productId);
if (!$product) {
    $all = getFeatherProducts();
    $product = $all[0] ?? null;
}

if (!$product) {
    header("Location: index.php");
    exit;
}

$pageTitle = htmlspecialchars($product['name']) . " — Feather Fits";
$pageDescription = htmlspecialchars($product['description'] ?? "Discover premium streetwear and graphic t-shirts from Feather Fits. True-to-size combed cotton and iconic anime & cinema designs.");
$currentDept = $product['department'] ?? 'men';
$currentPage = 'product';

$primaryImg = getProductPrimaryImage($product);
$images = !empty($product['images']) ? $product['images'] : [$primaryImg];
$basePrice = getProductBasePrice($product);
$origPrice = getProductOriginalPrice($product);
$discountPercent = $origPrice > $basePrice ? round((($origPrice - $basePrice) / $origPrice) * 100) : 0;
$colors = $product['colors'] ?? [['name' => 'Midnight Black', 'hex' => '#121214']];
$sizes = $product['sizes'] ?? ['S', 'M', 'L', 'XL', 'XXL'];

require_once __DIR__ . '/includes/header.php';
?>

  <!-- Breadcrumbs -->
  <nav class="breadcrumbs-container" aria-label="Breadcrumb">
    <div class="breadcrumbs-wrap">
      <a href="index.php">Home</a>
      <span>&rsaquo;</span>
      <a href="index.php?department=<?php echo urlencode($currentDept); ?>&subcategory=<?php echo urlencode($product['category'] ?? ''); ?>#catalog-section" id="pdp-category-link">
        <?php echo htmlspecialchars($product['categoryName'] ?? 'Collections'); ?>
      </a>
      <span>&rsaquo;</span>
      <span class="active-crumb" id="pdp-title-crumb"><?php echo htmlspecialchars($product['name']); ?></span>
    </div>
  </nav>

  <!-- Main Product Detail Section -->
  <main class="pdp-main-section">
    <div class="pdp-layout" id="pdp-container">
      
      <!-- Left: Interactive Gallery -->
      <div class="pdp-gallery-column">
        <div class="pdp-main-image-wrap">
          <img id="pdp-active-img" src="<?php echo htmlspecialchars($primaryImg); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="pdp-main-img" />
          <?php if (!empty($product['badge'])): ?>
            <span class="card-badge" style="top:18px; left:18px; font-size:0.8rem; padding:0.4rem 0.85rem;"><?php echo htmlspecialchars($product['badge']); ?></span>
          <?php endif; ?>
          <button class="card-wishlist-btn" 
                  style="top:18px; right:18px; width:44px; height:44px;"
                  onclick="if(window.PdpController){window.PdpController.toggleWishlist('<?php echo htmlspecialchars($product['id']); ?>');}"
                  aria-label="Wishlist">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
          </button>
        </div>

        <div class="pdp-thumbnails-strip">
          <?php foreach ($images as $idx => $img): ?>
            <div class="pdp-thumb-item <?php echo $idx === 0 ? 'active' : ''; ?>" 
                 onclick="if(window.PdpController){window.PdpController.setActiveImage('<?php echo htmlspecialchars($img); ?>');}">
              <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($product['name']); ?> view <?php echo $idx + 1; ?>" />
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Right: Product Information & Commerce Actions -->
      <div class="pdp-info-column">
        <div class="pdp-meta-top">
          <span class="card-category" style="font-size:0.85rem;"><?php echo htmlspecialchars($product['categoryName'] ?? ''); ?></span>
          <div class="card-rating">
            <span style="color:#F59E0B;">★★★★★</span>
            <strong style="color:var(--color-primary);"><?php echo number_format($product['rating'] ?? 4.9, 1); ?></strong>
            <span class="reviews">(<?php echo intval($product['reviewsCount'] ?? 128); ?> verified customer reviews)</span>
          </div>
        </div>

        <h1 class="pdp-title"><?php echo htmlspecialchars($product['name']); ?></h1>

        <div class="pdp-price-row">
          <span class="pdp-current-price">₹<?php echo number_format($basePrice, 0, '.', ','); ?></span>
          <?php if ($origPrice > $basePrice): ?>
            <span class="pdp-orig-price">₹<?php echo number_format($origPrice, 0, '.', ','); ?></span>
            <span class="pdp-discount-pill"><?php echo $discountPercent; ?>% OFF</span>
          <?php endif; ?>
        </div>

        <p class="pdp-desc-text"><?php echo htmlspecialchars($product['description'] ?? ''); ?></p>

        <!-- Highlights Badges -->
        <div class="pdp-fabric-pills">
          <span class="fabric-pill">🌿 100% Combed Heavy Cotton</span>
          <span class="fabric-pill">⚖️ 240 GSM Fabric</span>
          <span class="fabric-pill">✨ Pre-Shrunk &amp; Thermal Washed</span>
        </div>

        <div class="pdp-divider"></div>

        <!-- Color Selection -->
        <div class="selector-block">
          <div class="selector-label">
            <span>Selected Shade: <strong><?php echo htmlspecialchars($colors[0]['name'] ?? 'Classic'); ?></strong></span>
          </div>
          <div class="color-options" style="flex-wrap: wrap;">
            <?php foreach ($colors as $cIdx => $c): ?>
              <button type="button" 
                      class="color-option-btn <?php echo $cIdx === 0 ? 'active' : ''; ?>"
                      onclick="if(window.PdpController){window.PdpController.setColor('<?php echo htmlspecialchars($c['name']); ?>', '<?php echo htmlspecialchars($c['hex']); ?>');}">
                <span class="dot" style="background-color: <?php echo htmlspecialchars($c['hex']); ?>;"></span>
                <span><?php echo htmlspecialchars($c['name']); ?></span>
              </button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Size Selection -->
        <div class="selector-block">
          <div class="selector-label">
            <span>Select Size: <strong>L</strong></span>
            <button type="button" onclick="if(window.FeatherApp){window.FeatherApp.openSizeGuideModal('inches');}" class="pdp-size-chart-link">
              📐 Size Guide &amp; Chart
            </button>
          </div>
          <div class="size-options">
            <?php foreach (["XS", "S", "M", "L", "XL", "XXL"] as $size): 
                $isAvail = in_array($size, $sizes);
            ?>
              <button type="button" 
                      class="size-chip-btn <?php echo $size === 'L' ? 'active' : ''; ?>"
                      <?php echo !$isAvail ? 'disabled' : ''; ?>
                      onclick="if(window.PdpController){window.PdpController.setSize('<?php echo htmlspecialchars($size); ?>');}">
                <?php echo htmlspecialchars($size); ?>
              </button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Stock Indicator -->
        <div class="stock-indicator in-stock" style="margin-bottom: 1.5rem;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
          <span>Ready to Ship (In Stock)</span>
        </div>

        <!-- Quantity & Add to Cart -->
        <div class="pdp-actions-row">
          <div class="qty-counter pdp-qty-selector">
            <button class="qty-btn" type="button" onclick="if(window.PdpController){window.PdpController.updateQty(-1);}" aria-label="Decrease quantity">-</button>
            <span class="qty-value" id="pdp-qty-val">1</span>
            <button class="qty-btn" type="button" onclick="if(window.PdpController){window.PdpController.updateQty(1);}" aria-label="Increase quantity">+</button>
          </div>

          <button type="button" class="btn-primary pdp-cta-btn" onclick="if(window.PdpController){window.PdpController.addToCart();}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span>Add to Shopping Bag</span>
          </button>

          <button type="button" class="btn-secondary pdp-cta-btn" style="flex: 1;" onclick="if(window.PdpController){window.PdpController.buyNow();}">
            Buy Now ⚡
          </button>
        </div>

        <!-- Guarantees & Shipping Info -->
        <div class="pdp-guarantee-card">
          <div class="guarantee-row">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
            <div>
              <strong>Complimentary Blue Dart Express Delivery</strong>
              <p>Delivery in 2-4 business days across all verified pin codes in India.</p>
            </div>
          </div>
          <div class="guarantee-row">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
            <div>
              <strong>30-Day Doorstep Fit Exchange</strong>
              <p>Free exchange if the size isn't 100% perfect. Courier picks up from your doorstep.</p>
            </div>
          </div>
        </div>

      </div>

    </div>

    <!-- Product Tabs: Specifications, Care, Delivery -->
    <section class="pdp-specs-section">
      <div class="pdp-tabs-header">
        <button class="pdp-tab-btn active" onclick="switchPdpTab('specs', this)">Fabric &amp; Specifications</button>
        <button class="pdp-tab-btn" onclick="switchPdpTab('care', this)">Wash &amp; Care Guide</button>
        <button class="pdp-tab-btn" onclick="switchPdpTab('shipping', this)">Delivery &amp; 30-Day Returns</button>
      </div>

      <div class="pdp-tab-content active" id="tab-specs">
        <div class="specs-grid">
          <div class="spec-card">
            <h4>100% Combed Heavy Cotton</h4>
            <p>Engineered with 240 GSM dense cotton fibers offering luxury structural drape and zero transparency.</p>
          </div>
          <div class="spec-card">
            <h4>Silkscreen High-Definition Print</h4>
            <p>High-density eco-friendly inks that resist fading, cracking, and peeling through 50+ wash cycles.</p>
          </div>
          <div class="spec-card">
            <h4>Reinforced Double-Stitching</h4>
            <p>Twin-needle stitched hem and ribbed 1.25" crewneck collar designed to retain shape permanently.</p>
          </div>
          <div class="spec-card">
            <h4>Pre-Shrunk &amp; Colorfast</h4>
            <p>Thermal pre-washing prevents any surprise shrinkage or color bleeding after first wash.</p>
          </div>
        </div>
      </div>

      <div class="pdp-tab-content" id="tab-care" style="display: none;">
        <div class="care-instructions-box">
          <ul>
            <li>💧 Machine wash cold (max 30°C / 86°F) inside-out with like colors.</li>
            <li>🌿 Use mild, bleach-free detergent to protect organic combed fibers.</li>
            <li>💨 Tumble dry low or hang dry in the shade to maintain print vibrancy.</li>
            <li>⚡ Warm iron inside-out if needed. <strong>Do not iron directly over graphic prints.</strong></li>
          </ul>
        </div>
      </div>

      <div class="pdp-tab-content" id="tab-shipping" style="display: none;">
        <div class="shipping-info-box">
          <p><strong>Free Standard Delivery:</strong> On all domestic orders over ₹999. Dispatched within 24-48 hours via Blue Dart / Delhivery Express.</p>
          <p style="margin-top: 0.75rem;"><strong>30-Day Risk-Free Return Guarantee:</strong> If the fit isn't 100% confidence-inspiring, return or exchange unwashed items with tags within 30 days for an instant replacement.</p>
        </div>
      </div>
    </section>

    <!-- Verified Customer Reviews Section -->
    <section class="pdp-reviews-section" id="pdp-reviews-section">
      <div class="reviews-header">
        <div>
          <h3>Verified Buyer Reviews</h3>
          <div class="card-rating" style="font-size: 1.1rem; margin-top: 0.25rem;">
            <span style="color: #F59E0B;">★★★★★</span>
            <strong id="pdp-reviews-avg-rating" style="color: var(--color-primary); font-size: 1.1rem;">4.9 out of 5</strong>
            <span id="pdp-reviews-count-badge" class="reviews" style="color: var(--color-text-muted);">(Based on verified buyers)</span>
          </div>
        </div>
        <button id="toggle-write-review-btn" class="btn-primary" onclick="if(window.PdpController){window.PdpController.toggleWriteReviewForm();}">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
          </svg>
          <span>Write a Review</span>
        </button>
      </div>

      <!-- Write a Review Collapsible Form -->
      <div id="write-review-container" class="write-review-container" style="display: none;">
        <div class="write-review-header">
          <div>
            <h4 style="font-size: 1.15rem; font-weight: 800; color: var(--color-primary); margin-bottom: 0.25rem;">Write a Customer Review</h4>
            <p style="font-size: 0.82rem; color: var(--color-text-muted);">Share your feedback on fit, fabric weight, drape, and overall quality.</p>
          </div>
          <button class="modal-close-btn" style="position: static;" onclick="window.PdpController.toggleWriteReviewForm(false)" aria-label="Close">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>

        <form id="write-review-form" onsubmit="window.PdpController.submitReview(event)">
          <div class="form-group" style="margin-bottom: 1.25rem;">
            <label style="font-weight: 700; font-size: 0.85rem;">Overall Rating *</label>
            <div class="star-rating-picker" id="star-rating-picker">
              <span class="star-pick-btn active" data-rating="1" onclick="window.PdpController.setReviewStarRating(1)">★</span>
              <span class="star-pick-btn active" data-rating="2" onclick="window.PdpController.setReviewStarRating(2)">★</span>
              <span class="star-pick-btn active" data-rating="3" onclick="window.PdpController.setReviewStarRating(3)">★</span>
              <span class="star-pick-btn active" data-rating="4" onclick="window.PdpController.setReviewStarRating(4)">★</span>
              <span class="star-pick-btn active" data-rating="5" onclick="window.PdpController.setReviewStarRating(5)">★</span>
              <span id="star-rating-text" class="star-rating-label">5 Stars - Phenomenal Quality!</span>
            </div>
            <input type="hidden" id="review-rating-val" value="5" />
          </div>

          <div class="form-group-row">
            <div class="form-group">
              <label for="review-author-name">Your Full Name *</label>
              <input type="text" id="review-author-name" required class="form-input" placeholder="e.g. Vignesh Kumar" />
            </div>
            <div class="form-group">
              <label for="review-author-size">Size Purchased</label>
              <select id="review-author-size" class="form-input">
                <option value="S">Size: Small (S)</option>
                <option value="M" selected>Size: Medium (M)</option>
                <option value="L">Size: Large (L)</option>
                <option value="XL">Size: Extra Large (XL)</option>
                <option value="XXL">Size: XXL</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="review-headline">Review Headline</label>
            <input type="text" id="review-headline" class="form-input" placeholder="e.g. Best graphic tee fit I've worn this year!" />
          </div>

          <div class="form-group">
            <label for="review-body-text">Detailed Review / Fit Notes *</label>
            <textarea id="review-body-text" required rows="3" class="form-input" style="height: auto; padding: 0.75rem;" placeholder="Tell other shoppers how this fits (true to size, oversized), the cotton weight, wash durability..."></textarea>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-top: 1rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer; color: var(--color-primary); font-weight: 600;">
              <input type="checkbox" id="review-verified-check" checked style="accent-color: var(--color-secondary); width: 16px; height: 16px;" />
              <span>Verified Buyer (Purchased via Feather Fits)</span>
            </label>

            <div style="display: flex; gap: 0.75rem;">
              <button type="button" class="btn-secondary" style="border-color: var(--color-border); color: var(--color-text-main);" onclick="window.PdpController.toggleWriteReviewForm(false)">
                Cancel
              </button>
              <button type="submit" class="btn-primary">
                <span>Publish Verified Review</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <line x1="22" y1="2" x2="11" y2="13"></line>
                  <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Reviews Feed Container -->
      <div class="reviews-grid" id="pdp-reviews-grid">
        <!-- Rendered by window.PdpController.renderReviews() -->
      </div>
    </section>

    <!-- Related Products -->
    <section class="pdp-related-section">
      <div class="section-header">
        <div>
          <h2 class="section-title">You Might Also Love</h2>
          <p class="section-subtitle">Complementary designs from our flagship streetwear collection</p>
        </div>
      </div>
      <div class="products-grid" id="related-products-grid">
        <!-- Rendered dynamically -->
      </div>
    </section>
  </main>

  <script>
    function switchPdpTab(tabName, el) {
      document.querySelectorAll('.pdp-tab-btn').forEach(btn => btn.classList.remove('active'));
      document.querySelectorAll('.pdp-tab-content').forEach(c => c.style.display = 'none');
      el.classList.add('active');
      const target = document.getElementById('tab-' + tabName);
      if (target) target.style.display = 'block';
    }
  </script>

<?php
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/modals.php';
?>

  <!-- Scripts -->
  <script src="data/products.js"></script>
  <script src="js/app.js"></script>
  <script src="js/pdp.js"></script>
</body>
</html>
