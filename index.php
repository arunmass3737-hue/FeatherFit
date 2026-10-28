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
    'all'   => "Shop premium graphic T-shirts and luxury apparel for men and women. Discover Anime, Movie, and Plain minimalist designs crafted with superior comfort.",
    'men'   => "Shop Men's oversized graphic tees, heavyweight cotton drops, anime streetwear, and minimalist wardrobe essentials.",
    'women' => "Explore Women's relaxed fits, crop silhouettes, luxury streetwear aesthetics, and exclusive anime collabs.",
    'kids'  => "Comfortable, durable 100% cotton streetwear and bold graphic tees designed for active boys & girls."
];

$pageTitle = $deptTitles[$currentDept] ?? $deptTitles['all'];
$pageDescription = $deptDescriptions[$currentDept] ?? $deptDescriptions['all'];

$banner = getFeatherBannerByDept($currentDept);
$allProducts = getFeatherProducts();

require_once __DIR__ . '/includes/header.php';
?>

  <!-- Hero Banner Section (Dynamically controlled by Department) -->
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
        
        <div class="hero-cta-group" id="hero-banner-ctas">
          <button class="btn-primary" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('men');}">
            <span>Explore Men's Drops</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </button>
          
          <button class="btn-secondary" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('women');}">
            <span>Explore Women's Fits</span>
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- Department Category Posters Section (Dedicated Posters for Men, Women & Kids) -->
  <section class="department-posters-section" id="department-posters-section" aria-label="Curated Category Posters">
    <div class="posters-container">
      <div class="posters-header">
        <div>
          <div class="posters-badge-pill" id="posters-badge-pill">
            <span>Curated Category Posters</span>
          </div>
          <h2 class="posters-title" id="posters-section-title">Explore <span>Men's Categories</span></h2>
          <p class="posters-subtitle" id="posters-section-subtitle">Click any poster below to immediately open that collection</p>
        </div>
        <div class="posters-dept-pills" id="posters-dept-pills">
          <button type="button" class="posters-dept-pill-btn active" data-dept="men" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('men');}">👔 Men</button>
          <button type="button" class="posters-dept-pill-btn" data-dept="women" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('women');}">👗 Women</button>
          <button type="button" class="posters-dept-pill-btn" data-dept="kids" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('kids');}">🧒 Kids</button>
        </div>
      </div>
      
      <!-- Dynamic Category Posters Grid (Rendered by app.js based on active department) -->
      <div class="department-posters-grid" id="department-posters-grid">
        <!-- Rendered by app.js based on active department -->
      </div>
    </div>
  </section>

  <!-- New Arrivals Carousel Section (Categorized: Men, Women, Kids) -->
  <section class="new-arrivals-carousel-section" id="new-arrivals-section" aria-label="New Arrivals Carousel">
    <div class="new-arrivals-container">
      
      <!-- Section Header -->
      <div class="new-arrivals-header">
        <div class="new-arrivals-title-group">
          <div class="new-arrivals-badge">
            <span class="badge-dot"></span>
            <span>⚡ FRESH DROPS &bull; 2026 COLLECTION</span>
          </div>
          <h2 class="new-arrivals-title">
            New <span>Arrivals</span>
          </h2>
          <p class="new-arrivals-desc">
            Explore the latest streetwear drops, anime prints, and relaxed fits tailored for you.
          </p>
        </div>

        <!-- Carousel Navigation Controls (Tabs & Arrows) -->
        <div class="new-arrivals-controls">
          <!-- Department Selector Tabs (Men / Women / Kids) -->
          <div class="new-arrivals-dept-tabs" role="tablist" aria-label="New Arrivals Categories">
            <button type="button" 
                    class="dept-carousel-tab active" 
                    id="new-arr-tab-men" 
                    role="tab" 
                    aria-selected="true"
                    onclick="if(window.FeatherApp){window.FeatherApp.setNewArrivalsDept('men');}">
              <span class="tab-icon">👔</span>
              <span class="tab-name">Men</span>
              <span class="tab-count">9</span>
            </button>
            <button type="button" 
                    class="dept-carousel-tab" 
                    id="new-arr-tab-women" 
                    role="tab" 
                    aria-selected="false"
                    onclick="if(window.FeatherApp){window.FeatherApp.setNewArrivalsDept('women');}">
              <span class="tab-icon">👗</span>
              <span class="tab-name">Women</span>
              <span class="tab-count">4</span>
            </button>
            <button type="button" 
                    class="dept-carousel-tab" 
                    id="new-arr-tab-kids" 
                    role="tab" 
                    aria-selected="false"
                    onclick="if(window.FeatherApp){window.FeatherApp.setNewArrivalsDept('kids');}">
              <span class="tab-icon">🧒</span>
              <span class="tab-name">Kids</span>
              <span class="tab-count">4</span>
            </button>
          </div>

          <!-- Slider Arrows (Desktop & Tablet) -->
          <div class="carousel-nav-arrows">
            <button type="button" 
                    class="carousel-arrow-btn prev" 
                    id="new-arr-prev-btn" 
                    onclick="if(window.FeatherApp){window.FeatherApp.scrollNewArrivals('prev');}" 
                    aria-label="Previous products">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="15 18 9 12 15 6"></polyline>
              </svg>
            </button>
            <button type="button" 
                    class="carousel-arrow-btn next" 
                    id="new-arr-next-btn" 
                    onclick="if(window.FeatherApp){window.FeatherApp.scrollNewArrivals('next');}" 
                    aria-label="Next products">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Carousel Track Container -->
      <div class="new-arrivals-carousel-wrapper">
        <div class="new-arrivals-carousel-track" id="new-arrivals-carousel-track" tabindex="0" aria-label="New Arrivals Product List">
          <!-- Dynamically populated by window.FeatherApp.renderNewArrivalsCarousel() -->
        </div>
      </div>

      <!-- Bottom Hint & Shop All Link -->
      <div class="new-arrivals-footer">
        <span class="swipe-hint">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
          </svg>
          Scroll or swipe horizontally to view all new drops
        </span>
        <a href="#catalog-section" class="btn-link-all" id="new-arr-view-all-link">
          <span>View All in Catalog</span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </a>
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
        <span id="breadcrumb-current-dept" style="color: var(--color-primary); font-weight: 700;">All Collections</span>
        <span id="breadcrumb-subcat-divider" style="display: none;">/</span>
        <span id="breadcrumb-current-subcat" style="color: var(--color-secondary); display: none;"></span>
      </div>
      <button type="button" class="catalog-dept-reset-btn" id="catalog-reset-dept-btn" onclick="if(window.FeatherApp){window.FeatherApp.setDepartment('all');}">
        All Collections
      </button>
    </div>

    <div class="section-header">
      <div>
        <h2 class="section-title" id="catalog-section-title">The Curated Collections</h2>
        <p class="section-subtitle" id="catalog-section-subtitle">Select a subcategory below or filter with search</p>
      </div>

      <!-- Category Filter Tabs (Dynamic) -->
      <div class="category-tabs" id="category-tabs-container">
        <!-- Rendered by app.js based on active department -->
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

    <!-- Product Grid: 100% SEO-Rendered with Semantic Product Cards -->
    <div class="products-grid" id="products-grid">
      <!-- SEO Crawlable Pre-rendered Semantic Fallback -->
      <article class="product-card" data-id="ff-anime-01">
        <a href="product.php?id=ff-anime-01" class="product-card-link">
          <div class="product-image-wrap">
            <img src="assets/images/anime-tee-model-front.jpg" alt="Demon Slayer Tanjiro Oversized Graphic Tee" class="product-primary-img" loading="lazy" />
            <span class="product-badge">Bestseller</span>
          </div>
          <div class="product-info">
            <span class="product-brand">FEATHER ANIME DROP</span>
            <h3 class="product-title">Demon Slayer Tanjiro Oversized Graphic Tee</h3>
            <div class="product-price-row">
              <span class="price-current">₹1,299</span>
              <span class="price-original">₹1,699</span>
              <span class="discount-pill">24% OFF</span>
            </div>
          </div>
        </a>
      </article>

      <article class="product-card" data-id="ff-anime-02">
        <a href="product.php?id=ff-anime-02" class="product-card-link">
          <div class="product-image-wrap">
            <img src="assets/images/anime-tee.jpg" alt="Spirit Guardian Kitsune Streetwear Tee" class="product-primary-img" loading="lazy" />
            <span class="product-badge">Trending</span>
          </div>
          <div class="product-info">
            <span class="product-brand">FEATHER ANIME DROP</span>
            <h3 class="product-title">Spirit Guardian Kitsune Streetwear Tee</h3>
            <div class="product-price-row">
              <span class="price-current">₹1,199</span>
              <span class="price-original">₹1,499</span>
              <span class="discount-pill">20% OFF</span>
            </div>
          </div>
        </a>
      </article>

      <article class="product-card" data-id="ff-plain-01">
        <a href="product.php?id=ff-plain-01" class="product-card-link">
          <div class="product-image-wrap">
            <img src="assets/images/plain-tee.jpg" alt="Feather Signature Heavyweight Plain Tee" class="product-primary-img" loading="lazy" />
            <span class="product-badge">Wardrobe Essential</span>
          </div>
          <div class="product-info">
            <span class="product-brand">MINIMALIST ESSENTIALS</span>
            <h3 class="product-title">Feather Signature Heavyweight Plain Tee</h3>
            <div class="product-price-row">
              <span class="price-current">₹899</span>
              <span class="price-original">₹1,199</span>
              <span class="discount-pill">25% OFF</span>
            </div>
          </div>
        </a>
      </article>

      <article class="product-card" data-id="ff-movie-01">
        <a href="product.php?id=ff-movie-01" class="product-card-link">
          <div class="product-image-wrap">
            <img src="assets/images/movie-tee.jpg" alt="Galactic Starship 1982 Vintage Movie Tee" class="product-primary-img" loading="lazy" />
            <span class="product-badge">Collector's Pick</span>
          </div>
          <div class="product-info">
            <span class="product-brand">CINEMA ARCHIVE</span>
            <h3 class="product-title">Galactic Starship 1982 Vintage Movie Tee</h3>
            <div class="product-price-row">
              <span class="price-current">₹1,399</span>
              <span class="price-original">₹1,799</span>
              <span class="discount-pill">22% OFF</span>
            </div>
          </div>
        </a>
      </article>
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
