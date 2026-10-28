<?php
require_once __DIR__ . '/data/products.php';

$validDepts = ['all', 'men', 'women', 'kids'];
$currentDept = isset($_GET['department']) && in_array($_GET['department'], $validDepts) ? $_GET['department'] : 'all';
$currentPage = 'home';

$deptTitles = [
    'all'   => "Feather Fits — Confidence Fits You | Premium Apparel & Graphic T-Shirts",
    'men'   => "Men's Streetwear & Graphic Apparel | Feather Fits",
    'women' => "Women's Oversized Tees & Co-ords | Feather Fits",
    'kids'  => "Kids' Streetwear & Anime Graphic Collection | Feather Fits"
];

$deptDescriptions = [
    'all'   => "Discover Feather Fits luxury streetwear, pre-shrunk 240 GSM graphic tees, vintage cinema prints, and premium oversized fits.",
    'men'   => "Shop Men's oversized graphic tees, heavyweight cotton drops, anime streetwear, and minimalist wardrobe essentials.",
    'women' => "Explore Women's relaxed fits, crop silhouettes, luxury streetwear aesthetics, and exclusive anime collabs.",
    'kids'  => "Comfortable, durable 100% cotton streetwear and bold graphic tees designed for active boys & girls."
];

$pageTitle = $deptTitles[$currentDept] ?? $deptTitles['all'];
$pageDescription = $deptDescriptions[$currentDept] ?? $deptDescriptions['all'];

$banner = getFeatherBannerByDept($currentDept);
$allProducts = getFeatherProducts();
$subcategories = getFeatherSubcategories($currentDept);
$arrivals = getFeatherNewArrivals($currentDept);

// Filter products for initial server render based on active department
$displayedProducts = [];
foreach ($allProducts as $p) {
    if ($currentDept === 'all' || ($p['department'] ?? 'men') === $currentDept) {
        $displayedProducts[] = $p;
    }
}
if (empty($displayedProducts)) {
    $displayedProducts = $allProducts;
}

require_once __DIR__ . '/includes/header.php';
?>

  <!-- Hero Banner Section (Dynamic Server-Rendered with JS Hook IDs) -->
  <section class="hero-section" id="hero-section" aria-label="Hero Spotlight">
    <div class="hero-banner-wrap">
      <img id="hero-banner-image" src="<?php echo htmlspecialchars($banner['image']); ?>" alt="Feather Fits Lookbook" class="hero-bg-img" />
      <div class="hero-overlay"></div>
      
      <div class="hero-content">
        <div class="hero-brand-badge-pill" id="hero-brand-badge-pill">
          <img src="assets/images/feather-fits-logo.png" alt="Feather Fits Logo" class="hero-mini-logo" />
          <span id="hero-banner-badge-text"><?php echo htmlspecialchars($banner['badge']); ?></span>
        </div>
        <h1 class="hero-title" id="hero-banner-title">
          <?php echo $banner['title']; ?>
        </h1>
        <p class="hero-subtitle" id="hero-banner-subtitle">
          <?php echo htmlspecialchars($banner['subtitle']); ?>
        </p>
        <div class="hero-cta-group" id="hero-banner-cta-group">
          <?php if (!empty($banner['primaryCta'])): ?>
            <a href="index.php?department=<?php echo $banner['primaryCta']['dept'] ?? $currentDept; ?>#catalog-section" class="btn-primary" id="hero-banner-btn-primary" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('<?php echo $banner['primaryCta']['dept'] ?? $currentDept; ?>');}">
              <span><?php echo htmlspecialchars($banner['primaryCta']['label']); ?></span>
            </a>
          <?php endif; ?>
          <?php if (!empty($banner['secondaryCta'])): ?>
            <a href="index.php?department=<?php echo $banner['secondaryCta']['dept'] ?? $currentDept; ?>#catalog-section" class="btn-secondary" id="hero-banner-btn-secondary" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('<?php echo $banner['secondaryCta']['dept'] ?? $currentDept; ?>');}">
              <span><?php echo htmlspecialchars($banner['secondaryCta']['label']); ?></span>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- Department Posters Showcase -->
  <section class="departments-section" id="department-posters-section" aria-label="Department Collections">
    <div class="section-container">
      <div class="section-header" style="margin-bottom: 1.5rem;">
        <div>
          <span class="dept-badge-pill">PREMIUM COLLECTIONS</span>
          <h2 class="section-title" id="dept-posters-title">Shop by Category</h2>
          <p class="section-subtitle">Engineered for pure style, durability, and luxury streetwear comfort</p>
        </div>
      </div>

      <div class="dept-posters-grid" id="department-posters-grid">
        <!-- Men's Apparel Poster Card -->
        <article class="dept-poster-card" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('men');}">
          <div class="dept-poster-img-wrap">
            <img src="assets/images/mens-apparel.jpg" alt="Men's Streetwear Collection" loading="lazy" />
          </div>
          <div class="dept-poster-overlay">
            <span class="dept-poster-tag">MEN'S WARDROBE</span>
            <h3 class="dept-poster-title">Streetwear & Anime Drops</h3>
            <p class="dept-poster-desc">Heavyweight 240 GSM oversized tees, Japanese calligraphy prints, and rugged denim.</p>
            <a href="index.php?department=men#catalog-section" class="dept-poster-btn">Explore Men's Fits →</a>
          </div>
        </article>

        <!-- Women's Apparel Poster Card -->
        <article class="dept-poster-card" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('women');}">
          <div class="dept-poster-img-wrap">
            <img src="assets/images/womens-apparel.jpg" alt="Women's Contemporary Fashion" loading="lazy" />
          </div>
          <div class="dept-poster-overlay">
            <span class="dept-poster-tag">WOMEN'S EDITION</span>
            <h3 class="dept-poster-title">Contemporary Street Elegance</h3>
            <p class="dept-poster-desc">Cropped graphic tees, tailored blazers, relaxed linen overshirts, and co-ord luxury.</p>
            <a href="index.php?department=women#catalog-section" class="dept-poster-btn">Explore Women's Fits →</a>
          </div>
        </article>

        <!-- Kids' Apparel Poster Card -->
        <article class="dept-poster-card" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('kids');}">
          <div class="dept-poster-img-wrap">
            <img src="assets/images/kids-hero-banner.jpg" alt="Kids' Urban Streetwear" loading="lazy" />
          </div>
          <div class="dept-poster-overlay">
            <span class="dept-poster-tag">KIDS' SPECIAL</span>
            <h3 class="dept-poster-title">Anime Graphics & Playwear</h3>
            <p class="dept-poster-desc">Hypoallergenic combed cotton tees, vibrant anime screen prints, and easy utility sets.</p>
            <a href="index.php?department=kids#catalog-section" class="dept-poster-btn">Explore Kids' Fits →</a>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- Dynamic New Arrivals Multi-Carousel Section -->
  <section class="new-arrivals-section" id="new-arrivals-section" aria-label="Fresh Street Arrivals">
    <div class="section-container">
      <div class="arrivals-header-wrap">
        <div>
          <span class="arrivals-tag-pill">🔥 JUST DROPPED</span>
          <h2 class="arrivals-title">New Arrivals</h2>
          <p class="arrivals-subtitle">Fresh street essentials, limited editions & newly added graphics</p>
        </div>

        <!-- Department Carousel Tabs Filter -->
        <div class="arrivals-dept-tabs" role="tablist" aria-label="New Arrivals by Department">
          <button type="button" class="arrivals-tab-btn <?php echo $currentDept === 'men' ? 'active' : ''; ?>" data-dept="men" onclick="if(window.FeatherApp){window.FeatherApp.setNewArrivalsDept('men');}">
            <span>Men</span>
          </button>
          <button type="button" class="arrivals-tab-btn <?php echo $currentDept === 'women' ? 'active' : ''; ?>" data-dept="women" onclick="if(window.FeatherApp){window.FeatherApp.setNewArrivalsDept('women');}">
            <span>Women</span>
          </button>
          <button type="button" class="arrivals-tab-btn <?php echo $currentDept === 'kids' ? 'active' : ''; ?>" data-dept="kids" onclick="if(window.FeatherApp){window.FeatherApp.setNewArrivalsDept('kids');}">
            <span>Kids</span>
          </button>
        </div>

        <!-- Carousel Arrow Navigation -->
        <div class="arrivals-nav-arrows">
          <button type="button" class="carousel-arrow-btn" id="arrivals-prev-btn" onclick="if(window.FeatherApp){window.FeatherApp.scrollNewArrivals('prev');}" aria-label="Previous Arrivals">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
          </button>
          <button type="button" class="carousel-arrow-btn" id="arrivals-next-btn" onclick="if(window.FeatherApp){window.FeatherApp.scrollNewArrivals('next');}" aria-label="Next Arrivals">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
          </button>
        </div>
      </div>

      <!-- Carousel Sliding Track -->
      <div class="arrivals-carousel-viewport">
        <div class="arrivals-carousel-track" id="new-arrivals-track">
          <?php foreach ($arrivals as $arr): 
              $arrImg = getProductPrimaryImage($arr);
              $arrPrice = getProductBasePrice($arr);
              $arrOrig = getProductOriginalPrice($arr);
              $arrDiscount = $arrOrig > $arrPrice ? round((($arrOrig - $arrPrice) / $arrOrig) * 100) : 0;
          ?>
            <div class="arrival-carousel-card" data-product-id="<?php echo htmlspecialchars($arr['id']); ?>">
              <div class="arrival-card-media">
                <img src="<?php echo htmlspecialchars($arrImg); ?>" alt="<?php echo htmlspecialchars($arr['name']); ?>" loading="lazy" />
                <?php if (!empty($arr['badge'])): ?>
                  <span class="arrival-card-badge"><?php echo htmlspecialchars($arr['badge']); ?></span>
                <?php endif; ?>
                <button type="button" class="arrival-quick-cart" onclick="if(window.FeatherApp){window.FeatherApp.addToCart('<?php echo htmlspecialchars($arr['id']); ?>'); event.stopPropagation();}" aria-label="Add to Bag">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                </button>
              </div>
              <div class="arrival-card-details">
                <span class="arrival-dept-tag"><?php echo strtoupper($arr['department'] ?? 'MEN'); ?> &bull; <?php echo htmlspecialchars($arr['categoryName'] ?? 'STREETWEAR'); ?></span>
                <h4 class="arrival-name"><a href="product.php?id=<?php echo htmlspecialchars($arr['id']); ?>"><?php echo htmlspecialchars($arr['name']); ?></a></h4>
                <div class="arrival-price-row">
                  <span class="arrival-price"><?php echo formatPrice($arrPrice); ?></span>
                  <?php if ($arrOrig > $arrPrice): ?>
                    <span class="arrival-orig-price"><?php echo formatPrice($arrOrig); ?></span>
                    <span class="arrival-discount"><?php echo $arrDiscount; ?>% OFF</span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- Product Catalog Section -->
  <main class="category-section" id="catalog-section">
    <!-- Active Department & Subcategory Breadcrumb Navigator -->
    <div class="catalog-dept-nav-bar" id="catalog-dept-nav-bar">
      <div class="catalog-dept-breadcrumbs" id="catalog-dept-breadcrumbs">
        <a href="index.php?department=all#catalog-section" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('all'); return false;}" style="cursor: pointer;">Home</a>
        <span>/</span>
        <span id="breadcrumb-current-dept" style="color: var(--color-primary); font-weight: 700;">
          <?php 
            $deptNames = ['all' => 'All Collections', 'men' => "Men's Collection", 'women' => "Women's Collection", 'kids' => "Kids' Collection"];
            echo $deptNames[$currentDept] ?? 'All Collections';
          ?>
        </span>
        <span id="breadcrumb-subcat-divider" style="display: none;">/</span>
        <span id="breadcrumb-current-subcat" style="color: var(--color-secondary); display: none;"></span>
      </div>
      <button type="button" class="catalog-dept-reset-btn" id="catalog-reset-dept-btn" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('all');}">
        All Collections
      </button>
    </div>

    <div class="section-header">
      <div>
        <h2 class="section-title" id="catalog-section-title"><?php echo ($deptNames[$currentDept] ?? 'All') . ' Collections'; ?></h2>
        <p class="section-subtitle" id="catalog-section-subtitle">Select a subcategory below or filter with search</p>
      </div>

      <!-- Category Filter Tabs (Dynamic) -->
      <div class="category-tabs" id="category-tabs-container">
        <?php foreach ($subcategories as $sc): ?>
          <button type="button" class="category-tab-btn <?php echo $sc['id'] === 'all' ? 'active' : ''; ?>" onclick="if(window.FeatherApp){window.FeatherApp.setSubcategory('<?php echo $sc['id']; ?>');}">
            <?php echo htmlspecialchars($sc['name']); ?>
          </button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Live Search & Sort Toolbar -->
    <div class="catalog-toolbar">
      <div class="search-input-wrap">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input 
          type="search" 
          id="catalog-search-input" 
          class="catalog-search-input" 
          placeholder="Search by graphic name, style, or category..." 
          oninput="if(window.FeatherApp){window.FeatherApp.handleSearchInput(this.value);}" 
        />
      </div>

      <div class="toolbar-controls">
        <select id="catalog-sort-select" class="sort-select" onchange="if(window.FeatherApp){window.FeatherApp.handleSortChange(this.value);}">
          <option value="featured">Sort by: Featured</option>
          <option value="price-low">Price: Low to High</option>
          <option value="price-high">Price: High to Low</option>
          <option value="rating">Top Customer Rated</option>
        </select>

        <button type="button" class="size-guide-trigger" onclick="if(window.FeatherApp){window.FeatherApp.openSizeGuideModal('inches');}">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
            <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
          </svg>
          <span>Size Chart Guide</span>
        </button>
      </div>
    </div>

    <!-- Product Grid: 100% SEO-Rendered in PHP & Interactively Managed by JS -->
    <div class="products-grid" id="products-grid">
      <?php foreach ($displayedProducts as $prod): 
          $pId = $prod['id'];
          $pImg = getProductPrimaryImage($prod);
          $pPrice = getProductBasePrice($prod);
          $pOrig = getProductOriginalPrice($prod);
          $pDiscount = $pOrig > $pPrice ? round((($pOrig - $pPrice) / $pOrig) * 100) : 0;
          $badge = $prod['badge'] ?? '';
      ?>
        <article class="product-card" data-id="<?php echo htmlspecialchars($pId); ?>">
          <a href="product.php?id=<?php echo htmlspecialchars($pId); ?>" class="product-card-link">
            <div class="product-image-wrap">
              <img src="<?php echo htmlspecialchars($pImg); ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" class="product-primary-img" loading="lazy" />
              <?php if (!empty($badge)): ?>
                <span class="product-badge"><?php echo htmlspecialchars($badge); ?></span>
              <?php endif; ?>
              <button type="button" class="quick-view-btn" onclick="if(window.FeatherApp){window.FeatherApp.openProductModal('<?php echo htmlspecialchars($pId); ?>'); event.preventDefault(); event.stopPropagation();}">
                Quick View
              </button>
            </div>
            <div class="product-info">
              <span class="product-brand"><?php echo htmlspecialchars($prod['categoryName'] ?? 'FEATHER STREETWEAR'); ?></span>
              <h3 class="product-title"><?php echo htmlspecialchars($prod['name']); ?></h3>
              <div class="product-price-row">
                <span class="price-current"><?php echo formatPrice($pPrice); ?></span>
                <?php if ($pOrig > $pPrice): ?>
                  <span class="price-original"><?php echo formatPrice($pOrig); ?></span>
                  <span class="discount-pill"><?php echo $pDiscount; ?>% OFF</span>
                <?php endif; ?>
              </div>
            </div>
          </a>
        </article>
      <?php endforeach; ?>
    </div>
  </main>

<?php
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/modals.php';
?>

  <!-- Scripts -->
  <script src="data/products.js"></script>
  <script src="js/app.js"></script>
</body>
</html>
