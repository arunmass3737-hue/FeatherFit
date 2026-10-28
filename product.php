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

$pageTitle = htmlspecialchars($product['name']) . " | Feather Fits Luxury Streetwear";
$pageDescription = htmlspecialchars($product['description'] ?? "Premium heavy-weight 240 GSM combed cotton streetwear from Feather Fits.");
$currentDept = $product['department'] ?? 'men';
$currentPage = 'product';

$primaryImg = getProductPrimaryImage($product);
$images = !empty($product['images']) ? $product['images'] : [$primaryImg];
$basePrice = getProductBasePrice($product);
$origPrice = getProductOriginalPrice($product);
$discountPercent = $origPrice > $basePrice ? round((($origPrice - $basePrice) / $origPrice) * 100) : 0;
$colors = $product['colors'] ?? [['name' => 'Midnight Black', 'hex' => '#121214']];
$sizes = $product['sizes'] ?? ['S', 'M', 'L', 'XL', 'XXL'];

// Related products
$allProducts = getFeatherProducts();
$related = [];
foreach ($allProducts as $p) {
    if ($p['id'] !== $product['id'] && ($p['department'] ?? '') === $product['department']) {
        $related[] = $p;
        if (count($related) >= 4) break;
    }
}
if (count($related) < 4) {
    foreach ($allProducts as $p) {
        if ($p['id'] !== $product['id'] && !in_array($p, $related)) {
            $related[] = $p;
            if (count($related) >= 4) break;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

  <!-- Breadcrumbs -->
  <nav class="breadcrumbs-container" aria-label="Breadcrumb">
    <div class="breadcrumbs-wrap">
      <a href="index.php">Home</a>
      <span>&rsaquo;</span>
      <a href="index.php?department=<?php echo urlencode($currentDept); ?>#catalog-section" id="pdp-category-link">
        <?php echo htmlspecialchars($product['categoryName'] ?? ucfirst($currentDept) . "'s Fits"); ?>
      </a>
      <span>&rsaquo;</span>
      <span class="active-crumb" id="pdp-title-crumb"><?php echo htmlspecialchars($product['name']); ?></span>
    </div>
  </nav>

  <!-- Main Product Detail Section -->
  <main class="pdp-main-section">
    <div class="pdp-layout" id="pdp-container">
      
      <!-- Left Column: Gallery Media -->
      <div class="pdp-gallery-column">
        <div class="pdp-main-image-wrap">
          <img id="pdp-main-image" src="<?php echo htmlspecialchars($primaryImg); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="pdp-main-img" />
          <?php if (!empty($product['badge'])): ?>
            <span class="pdp-badge"><?php echo htmlspecialchars($product['badge']); ?></span>
          <?php endif; ?>
        </div>

        <?php if (count($images) > 1): ?>
          <div class="pdp-thumbnails-strip" id="pdp-thumbnails">
            <?php foreach ($images as $idx => $img): ?>
              <button type="button" class="pdp-thumb-btn <?php echo $idx === 0 ? 'active' : ''; ?>" onclick="if(window.PdpController){window.PdpController.switchGalleryImage('<?php echo htmlspecialchars($img); ?>', this);} else { document.getElementById('pdp-main-image').src='<?php echo htmlspecialchars($img); ?>'; }">
                <img src="<?php echo htmlspecialchars($img); ?>" alt="View angle <?php echo $idx + 1; ?>" loading="lazy" />
              </button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Right Column: Product Info & Actions -->
      <div class="pdp-details-column">
        <span class="pdp-brand-tag">FEATHER FITS &bull; <?php echo strtoupper($product['categoryName'] ?? 'LUXURY STREETWEAR'); ?></span>
        <h1 class="pdp-title" id="pdp-product-title"><?php echo htmlspecialchars($product['name']); ?></h1>

        <!-- Rating & Reviews Header -->
        <div class="pdp-rating-row">
          <div class="star-rating">
            <span style="color: #F59E0B;">★★★★★</span>
            <span class="rating-val"><?php echo number_format($product['rating'] ?? 4.9, 1); ?></span>
          </div>
          <span class="reviews-count">(<?php echo intval($product['reviewsCount'] ?? 128); ?> Verified Customer Reviews)</span>
          <span class="divider">&bull;</span>
          <span class="pdp-in-stock-tag">✓ In Stock &amp; Ready to Ship</span>
        </div>

        <!-- Pricing Card -->
        <div class="pdp-price-box">
          <div class="pdp-price-primary">
            <span class="price-current" id="pdp-current-price"><?php echo formatPrice($basePrice); ?></span>
            <?php if ($origPrice > $basePrice): ?>
              <span class="price-original" id="pdp-original-price"><?php echo formatPrice($origPrice); ?></span>
              <span class="price-discount-tag" id="pdp-discount-tag"><?php echo $discountPercent; ?>% OFF</span>
            <?php endif; ?>
          </div>
          <div class="price-tax-note">Inclusive of all taxes &bull; Pre-shrunk 240 GSM Luxury Combed Cotton</div>
        </div>

        <!-- Color Selector -->
        <div class="pdp-option-section">
          <div class="option-header">
            <label>Color: <strong id="pdp-selected-color-name"><?php echo htmlspecialchars($colors[0]['name'] ?? 'Classic'); ?></strong></label>
          </div>
          <div class="color-swatches-group" id="pdp-color-swatches">
            <?php foreach ($colors as $cIdx => $c): ?>
              <button type="button" class="color-swatch-btn <?php echo $cIdx === 0 ? 'active' : ''; ?>" style="background-color: <?php echo htmlspecialchars($c['hex']); ?>;" title="<?php echo htmlspecialchars($c['name']); ?>" onclick="if(window.PdpController){window.PdpController.selectColor('<?php echo htmlspecialchars($c['name']); ?>', this);}"></button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Size Selector -->
        <div class="pdp-option-section">
          <div class="option-header">
            <label>Select Size:</label>
            <button type="button" class="btn-text-size-guide" onclick="if(window.FeatherApp){window.FeatherApp.openSizeGuideModal('inches');}">
              📏 Size &amp; Fit Guide
            </button>
          </div>
          <div class="size-buttons-group" id="pdp-size-buttons">
            <?php foreach ($sizes as $sIdx => $s): ?>
              <button type="button" class="size-pill-btn <?php echo $s === 'L' || ($sIdx === 0 && !in_array('L', $sizes)) ? 'active' : ''; ?>" onclick="if(window.PdpController){window.PdpController.selectSize('<?php echo htmlspecialchars($s); ?>', this);}">
                <?php echo htmlspecialchars($s); ?>
              </button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Quantity & Add to Cart -->
        <div class="pdp-actions-row">
          <div class="pdp-quantity-stepper">
            <button type="button" class="qty-btn" onclick="if(window.PdpController){window.PdpController.decrementQty();} else { var el=document.getElementById('pdp-qty-val'); var v=parseInt(el.innerText)-1; if(v>=1) el.innerText=v; }">-</button>
            <span id="pdp-qty-val" class="qty-display">1</span>
            <button type="button" class="qty-btn" onclick="if(window.PdpController){window.PdpController.incrementQty();} else { var el=document.getElementById('pdp-qty-val'); var v=parseInt(el.innerText)+1; if(v<=10) el.innerText=v; }">+</button>
          </div>

          <button type="button" class="btn-add-to-cart" id="pdp-add-cart-btn" onclick="if(window.PdpController){window.PdpController.addToCart();} else if(window.FeatherApp){window.FeatherApp.addToCart('<?php echo htmlspecialchars($product['id']); ?>');}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span>Add to Shopping Bag</span>
          </button>

          <button type="button" class="btn-buy-now" onclick="if(window.PdpController){window.PdpController.buyNow();} else { window.location.href='checkout.php'; }">
            Buy Now ⚡
          </button>
        </div>

        <!-- Trust Badges Strip -->
        <div class="pdp-trust-highlights">
          <div class="trust-highlight-item">
            <span class="icon">🚚</span>
            <div>
              <strong>Free Express Shipping</strong>
              <p>On orders above ₹999 across all India PIN codes</p>
            </div>
          </div>
          <div class="trust-highlight-item">
            <span class="icon">📦</span>
            <div>
              <strong>Doorstep UPI / COD Available</strong>
              <p>Pay cash or scan courier QR on delivery</p>
            </div>
          </div>
          <div class="trust-highlight-item">
            <span class="icon">🔄</span>
            <div>
              <strong>30-Day Easy Exchange</strong>
              <p>Hassle-free size replacement at your doorstep</p>
            </div>
          </div>
        </div>

        <!-- Description Text -->
        <div class="pdp-description-wrap">
          <h3>Design &amp; Silhouette</h3>
          <p><?php echo nl2br(htmlspecialchars($product['description'] ?? '')); ?></p>
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
            <span id="pdp-reviews-count-badge" class="reviews" style="color: var(--color-text-muted);">(Based on <?php echo intval($product['reviewsCount'] ?? 128); ?> verified buyers)</span>
          </div>
        </div>
        <button id="toggle-write-review-btn" class="btn-primary" onclick="window.PdpController.toggleWriteReviewForm()">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
          </svg>
          <span>Write a Review</span>
        </button>
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
        <?php foreach ($related as $rel): 
            $relImg = getProductPrimaryImage($rel);
            $relPrice = getProductBasePrice($rel);
            $relOrig = getProductOriginalPrice($rel);
            $relDiscount = $relOrig > $relPrice ? round((($relOrig - $relPrice) / $relOrig) * 100) : 0;
        ?>
          <article class="product-card" data-id="<?php echo htmlspecialchars($rel['id']); ?>">
            <a href="product.php?id=<?php echo htmlspecialchars($rel['id']); ?>" class="product-card-link">
              <div class="product-image-wrap">
                <img src="<?php echo htmlspecialchars($relImg); ?>" alt="<?php echo htmlspecialchars($rel['name']); ?>" class="product-primary-img" loading="lazy" />
                <?php if (!empty($rel['badge'])): ?>
                  <span class="product-badge"><?php echo htmlspecialchars($rel['badge']); ?></span>
                <?php endif; ?>
              </div>
              <div class="product-info">
                <span class="product-brand"><?php echo htmlspecialchars($rel['categoryName'] ?? 'FEATHER FIT'); ?></span>
                <h3 class="product-title"><?php echo htmlspecialchars($rel['name']); ?></h3>
                <div class="product-price-row">
                  <span class="price-current"><?php echo formatPrice($relPrice); ?></span>
                  <?php if ($relOrig > $relPrice): ?>
                    <span class="price-original"><?php echo formatPrice($relOrig); ?></span>
                    <span class="discount-pill"><?php echo $relDiscount; ?>% OFF</span>
                  <?php endif; ?>
                </div>
              </div>
            </a>
          </article>
        <?php endforeach; ?>
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
