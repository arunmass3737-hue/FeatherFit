/**
 * FEATHER FITS — PRODUCTION E-COMMERCE ENGINE
 * Features:
 * - 5 Distinct Categories (Men's, Women's, Anime, Plain, Movie)
 * - Interactive Variant Selectors (Size, Color, Stock checking)
 * - Persistent Cart with Dynamic Pricing & Free Shipping Threshold
 * - Wishlist with instant sync
 * - Secure Checkout Simulation (Stripe-styled card inputs & validation)
 * - Order Tracking with live visual milestone timeline
 * - User Accounts & Authentication State (Order History, Addresses)
 */

(function () {
  "use strict";

  // -------------------------------------------------------------
  // STATE MANAGEMENT & LOCAL STORAGE
  // -------------------------------------------------------------
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

  const state = {
    products: window.FEATHER_PRODUCTS || [],
    categories: window.FEATHER_CATEGORIES || [],
    currentDepartment: "all",
    currentSubcategory: "all",
    currentCategory: "all",
    newArrivalsDept: "men",
    searchQuery: "",
    sortBy: "featured",
    activePromo: null,
    sizeGuideUnit: "inches",
    cart: JSON.parse(localStorage.getItem(STORAGE_KEYS.CART) || "[]"),
    wishlist: JSON.parse(localStorage.getItem(STORAGE_KEYS.WISHLIST) || "[]"),
    user: JSON.parse(localStorage.getItem(STORAGE_KEYS.USER) || "null") || defaultMember,
    orders: JSON.parse(localStorage.getItem(STORAGE_KEYS.ORDERS) || "[]"),
    activeModalProduct: null,
    activeAccountTab: "tracking",
    activeTrackingOrderNum: "FF-89214",
    showAddAddressForm: false,
    selectedVariant: {
      colorName: "",
      size: "L",
      image: "",
    },
  };

  // Seed default sample order if none exists for instant tracking demonstration
  if (state.orders.length === 0) {
    state.orders.push({
      orderNumber: "FF-89214",
      createdAt: new Date(Date.now() - 36 * 3600 * 1000).toISOString(),
      status: "SHIPPED",
      carrier: "Blue Dart Express",
      trackingNumber: "BD-9402849182",
      estimatedDelivery: "In 2 Days",
      items: [
        {
          name: "Cyberpunk Ronin Oversized Graphic Tee",
          color: "Obsidian Black",
          size: "L",
          price: 1299.0,
          quantity: 1,
          image: "assets/images/anime-tee.jpg",
        },
      ],
      shippingAddress: {
        firstName: "Alexander",
        lastName: "Wright",
        line1: "42, Residency Road, MG Layout",
        city: "Bengaluru",
        state: "KA",
        postalCode: "560025",
      },
      subtotal: 1299.0,
      shippingFee: 0.0,
      tax: 64.95,
      total: 1363.95,
    });
    localStorage.setItem(STORAGE_KEYS.ORDERS, JSON.stringify(state.orders));
  }

  function saveState() {
    localStorage.setItem(STORAGE_KEYS.CART, JSON.stringify(state.cart));
    localStorage.setItem(STORAGE_KEYS.WISHLIST, JSON.stringify(state.wishlist));
    localStorage.setItem(STORAGE_KEYS.USER, JSON.stringify(state.user));
    localStorage.setItem(STORAGE_KEYS.ORDERS, JSON.stringify(state.orders));
    updateBadges();
  }

  // -------------------------------------------------------------
  // TOAST NOTIFICATIONS
  // -------------------------------------------------------------
  function showToast(message, type = "success") {
    let container = document.getElementById("toast-container");
    if (!container) {
      container = document.createElement("div");
      container.id = "toast-container";
      container.className = "toast-container";
      document.body.appendChild(container);
    }

    const toast = document.createElement("div");
    toast.className = `toast ${type}`;
    toast.innerHTML = `
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
        <polyline points="22 4 12 14.01 9 11.01"></polyline>
      </svg>
      <span>${message}</span>
    `;

    container.appendChild(toast);
    setTimeout(() => {
      toast.style.opacity = "0";
      toast.style.transform = "translateY(-15px)";
      setTimeout(() => toast.remove(), 300);
    }, 3200);
  }

  // -------------------------------------------------------------
  // BADGES & HEADER STATUS
  // -------------------------------------------------------------
  function updateBadges() {
    const cartCountEl = document.getElementById("cart-badge-count");
    const wishlistCountEl = document.getElementById("wishlist-badge-count");
    const userBtn = document.getElementById("user-action-btn");

    const totalCartQty = state.cart.reduce((sum, item) => sum + item.quantity, 0);
    if (cartCountEl) {
      cartCountEl.textContent = totalCartQty;
      cartCountEl.style.display = totalCartQty > 0 ? "flex" : "none";
    }

    const totalWishlistCount = state.wishlist.length;
    if (wishlistCountEl) {
      wishlistCountEl.textContent = totalWishlistCount;
      wishlistCountEl.style.display = totalWishlistCount > 0 ? "flex" : "none";
    }

    if (userBtn) {
      if (state.user) {
        userBtn.title = `Logged in as ${state.user.firstName}`;
        userBtn.style.borderColor = "var(--color-accent)";
      } else {
        userBtn.title = "Account";
        userBtn.style.borderColor = "var(--color-border)";
      }
    }
  }

  // -------------------------------------------------------------
  // DEPARTMENTS, POSTERS & PRODUCT RENDERING
  // -------------------------------------------------------------
  function setDepartment(deptId) {
    if (!["all", "men", "women", "kids"].includes(deptId)) {
      deptId = "all";
    }

    state.currentDepartment = deptId;
    state.currentSubcategory = "all";
    state.currentCategory = "all";

    // If currently on product.html or another page without catalog grid, redirect to index.html with query parameter
    if (!document.getElementById("products-grid")) {
      window.location.href = `index.html?department=${deptId}#catalog-section`;
      return;
    }

    // Sync active nav links in header and mobile drawer
    document.querySelectorAll(".nav-link[data-dept], .mobile-nav-link[data-dept]").forEach((link) => {
      if (link.getAttribute("data-dept") === deptId) {
        link.classList.add("active");
      } else {
        link.classList.remove("active");
      }
    });

    renderHeroBanner(deptId);
    renderDepartmentPosters(deptId);
    if (["men", "women", "kids"].includes(deptId)) { setNewArrivalsDept(deptId); }
    renderBreadcrumbs();
    renderCategoryTabs();
    renderProducts();

    // Update URL query param without full page reload
    if (window.history && window.history.replaceState) {
      const url = new URL(window.location);
      url.searchParams.set("department", deptId);
      url.searchParams.delete("subcategory");
      url.searchParams.delete("category");
      window.history.replaceState({}, "", url);
    }
  }

  function setSubcategory(subcatId, deptId) {
    if (deptId && deptId !== state.currentDepartment) {
      state.currentDepartment = deptId;
      document.querySelectorAll(".nav-link[data-dept], .mobile-nav-link[data-dept]").forEach((link) => {
        if (link.getAttribute("data-dept") === deptId) {
          link.classList.add("active");
        } else {
          link.classList.remove("active");
        }
      });
      renderHeroBanner(deptId);
      renderDepartmentPosters(deptId);
    }

    state.currentSubcategory = subcatId || "all";
    state.currentCategory = subcatId || "all";

    // If not on catalog page, redirect to index.html
    if (!document.getElementById("products-grid")) {
      window.location.href = `index.html?department=${state.currentDepartment}&subcategory=${state.currentSubcategory}#catalog-section`;
      return;
    }

    renderBreadcrumbs();
    renderCategoryTabs();
    renderProducts();

    if (window.history && window.history.replaceState) {
      const url = new URL(window.location);
      url.searchParams.set("department", state.currentDepartment);
      url.searchParams.set("subcategory", state.currentSubcategory);
      window.history.replaceState({}, "", url);
    }

    // Smooth scroll down to catalog section
    const section = document.getElementById("catalog-section");
    if (section) {
      section.scrollIntoView({ behavior: "smooth" });
    }
  }

  function renderHeroBanner(deptId) {
    const banners = window.FEATHER_DEPARTMENT_BANNERS || {};
    const banner = banners[deptId] || banners.all;
    if (!banner) return;

    const imgEl = document.getElementById("hero-banner-image");
    const badgeTextEl = document.getElementById("hero-banner-badge-text");
    const titleEl = document.getElementById("hero-banner-title");
    const subtitleEl = document.getElementById("hero-banner-subtitle");
    const ctasEl = document.getElementById("hero-banner-ctas");

    if (imgEl && banner.image) {
      imgEl.src = banner.image;
    }
    if (badgeTextEl && banner.badge) {
      badgeTextEl.textContent = banner.badge;
    }
    if (titleEl && banner.title) {
      titleEl.innerHTML = banner.title;
    }
    if (subtitleEl && banner.subtitle) {
      subtitleEl.textContent = banner.subtitle;
    }

    if (ctasEl && banner.primaryCta && banner.secondaryCta) {
      const pCta = banner.primaryCta;
      const sCta = banner.secondaryCta;

      const pAction = pCta.dept
        ? `window.FeatherApp.setDepartment('${pCta.dept}')`
        : `window.FeatherApp.setSubcategory('${pCta.subcat}', '${deptId}')`;

      const sAction = sCta.dept
        ? `window.FeatherApp.setDepartment('${sCta.dept}')`
        : `window.FeatherApp.setSubcategory('${sCta.subcat}', '${deptId}')`;

      ctasEl.innerHTML = `
        <button class="btn-primary" onclick="${pAction};">
          <span>${pCta.label}</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </button>
        <button class="btn-secondary" onclick="${sAction};">
          <span>${sCta.label}</span>
        </button>
      `;
    }
  }

  
  // -------------------------------------------------------------
  // NEW ARRIVALS CATEGORIZED CAROUSEL (MEN, WOMEN, KIDS)
  // -------------------------------------------------------------
  function setNewArrivalsDept(dept) {
    if (!["men", "women", "kids"].includes(dept)) dept = "men";
    state.newArrivalsDept = dept;

    // Update Tab UI
    document.querySelectorAll(".dept-carousel-tab").forEach((tab) => {
      const isMatch = tab.id === `new-arr-tab-${dept}`;
      tab.classList.toggle("active", isMatch);
      tab.setAttribute("aria-selected", isMatch ? "true" : "false");
    });

    renderNewArrivalsCarousel();
  }

  function renderNewArrivalsCarousel() {
    const track = document.getElementById("new-arrivals-carousel-track");
    if (!track) return;

    const allProducts = window.FEATHER_PRODUCTS || state.products || [];
    const deptProducts = allProducts.filter((p) => p.department === state.newArrivalsDept);

    if (deptProducts.length === 0) {
      track.innerHTML = `
        <div class="carousel-empty-state">
          <p>No new arrivals currently available for this category.</p>
        </div>
      `;
      return;
    }

    track.innerHTML = deptProducts
      .map((product) => {
        const isWishlisted = state.wishlist.includes(product.id);
        const discount = product.originalPrice
          ? Math.round(((product.originalPrice - product.basePrice) / product.originalPrice) * 100)
          : null;

        return `
          <article class="carousel-card" data-product-id="${product.id}">
            <div class="carousel-card-img-wrap">
              <a href="product.html?id=${product.id}" class="carousel-card-img-link" aria-label="${product.name}">
                <img src="${product.images[0]}" alt="${product.name}" class="carousel-card-img" loading="lazy" />
              </a>
              ${product.badge ? `<span class="carousel-badge">${product.badge}</span>` : `<span class="carousel-badge new-tag">⚡ NEW</span>`}
              <button class="carousel-wishlist-btn ${isWishlisted ? "active" : ""}" 
                      onclick="event.preventDefault(); event.stopPropagation(); window.FeatherApp.toggleWishlist('${product.id}')"
                      aria-label="Wishlist">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="${isWishlisted ? "currentColor" : "none"}" stroke="currentColor" stroke-width="2">
                  <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
              </button>
            </div>

            <div class="carousel-card-body">
              <span class="carousel-card-category">${product.categoryName}</span>
              <h3 class="carousel-card-title">
                <a href="product.html?id=${product.id}">${product.name}</a>
              </h3>

              <div class="carousel-card-footer">
                <div class="carousel-price-wrap">
                  <span class="carousel-price">₹${product.basePrice.toLocaleString("en-IN")}</span>
                  ${product.originalPrice ? `<span class="carousel-orig-price">₹${product.originalPrice.toLocaleString("en-IN")}</span>` : ""}
                  ${discount ? `<span class="carousel-discount-tag">${discount}% OFF</span>` : ""}
                </div>

                <a href="product.html?id=${product.id}" class="carousel-quick-btn" title="View Fit Details">
                  <span>View</span>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="9 18 15 12 9 6"></polyline>
                  </svg>
                </a>
              </div>
            </div>
          </article>
        `;
      })
      .join("");

    track.scrollTo({ left: 0, behavior: "smooth" });
  }

  function scrollNewArrivals(direction) {
    const track = document.getElementById("new-arrivals-carousel-track");
    if (!track) return;
    const scrollAmount = 300;
    if (direction === "prev") {
      track.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    } else {
      track.scrollBy({ left: scrollAmount, behavior: "smooth" });
    }
  }

  function renderDepartmentPosters(deptId) {
    const grid = document.getElementById("department-posters-grid");
    if (!grid) return;

    const allPosters = window.FEATHER_DEPARTMENT_POSTERS || {};
    // When on home (all), posters are the 3 department cards (Men, Women, Kids); when on Men/Women/Kids, show their category posters!
    const posters = allPosters[deptId] || allPosters.men || [];

    // Update section header
    const titleEl = document.getElementById("posters-section-title");
    const subEl = document.getElementById("posters-section-subtitle");
    const pillBtns = document.querySelectorAll(".posters-dept-pill-btn");

    pillBtns.forEach((btn) => {
      if (btn.getAttribute("data-dept") === deptId) {
        btn.classList.add("active");
      } else {
        btn.classList.remove("active");
      }
    });

    if (titleEl) {
      if (deptId === "men") {
        titleEl.innerHTML = `Explore <span>Men's Categories</span>`;
      } else if (deptId === "women") {
        titleEl.innerHTML = `Explore <span>Women's Categories</span>`;
      } else if (deptId === "kids") {
        titleEl.innerHTML = `Explore <span>Kids' Categories</span>`;
      } else {
        titleEl.innerHTML = `Featured <span>Departments</span>`;
      }
    }

    if (subEl) {
      if (deptId === "all") {
        subEl.textContent = "Select Men, Women, or Kids to view dedicated category posters and exclusive drops";
      } else {
        subEl.textContent = "Click any category poster below to view that collection immediately";
      }
    }

    grid.innerHTML = posters
      .map((p) => {
        const clickAction =
          p.targetSubcat && p.targetSubcat !== "all"
            ? `window.FeatherApp.setSubcategory('${p.targetSubcat}', '${p.targetDept}')`
            : `window.FeatherApp.setDepartment('${p.targetDept}')`;

        return `
          <div class="dept-poster-card" onclick="${clickAction}">
            <div class="dept-poster-img-wrap">
              <img src="${p.image}" alt="${p.title}" class="dept-poster-img" loading="lazy" />
            </div>
            <div class="dept-poster-overlay"></div>
            ${p.badge ? `<span class="dept-poster-badge">${p.badge}</span>` : ""}
            <div class="dept-poster-content">
              <h3 class="dept-poster-title">${p.title}</h3>
              <p class="dept-poster-sub">${p.subtitle}</p>
              <span class="dept-poster-btn">
                <span>${p.actionText || "Shop Now"}</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
              </span>
            </div>
          </div>
        `;
      })
      .join("");
  }

  function renderBreadcrumbs() {
    const deptEl = document.getElementById("breadcrumb-current-dept");
    const subcatDivider = document.getElementById("breadcrumb-subcat-divider");
    const subcatEl = document.getElementById("breadcrumb-current-subcat");
    const catalogTitle = document.getElementById("catalog-section-title");

    const deptNames = {
      all: "All Collections",
      men: "Men's Collection",
      women: "Women's Collection",
      kids: "Kids' Collection"
    };

    const subcatMap = {
      "all": "All Fits",
      "men-tshirts": "Men's T-Shirts & Anime",
      "men-shirts": "Men's Casual & Linen Shirts",
      "men-bottoms": "Men's Cargos & Bottoms",
      "women-tshirts": "Women's T-Shirts & Crop Tops",
      "women-shirts": "Women's Oversized Shirts",
      "women-bottoms": "Women's Blazers & Co-Ords",
      "kids-tshirts": "Kids' Anime Graphic Tees",
      "kids-shirts": "Kids' Casual Overshirts",
      "kids-sets": "Kids' Play Sets & Joggers"
    };

    if (deptEl) {
      deptEl.textContent = deptNames[state.currentDepartment] || "All Collections";
    }

    if (state.currentSubcategory && state.currentSubcategory !== "all") {
      if (subcatDivider) subcatDivider.style.display = "inline";
      if (subcatEl) {
        subcatEl.style.display = "inline";
        subcatEl.textContent = subcatMap[state.currentSubcategory] || state.currentSubcategory;
      }
      if (catalogTitle) {
        catalogTitle.textContent = subcatMap[state.currentSubcategory] || "Curated Fits";
      }
    } else {
      if (subcatDivider) subcatDivider.style.display = "none";
      if (subcatEl) subcatEl.style.display = "none";
      if (catalogTitle) {
        catalogTitle.textContent = `${deptNames[state.currentDepartment] || "All"} Collections`;
      }
    }
  }

  function renderCategoryTabs() {
    const container = document.getElementById("category-tabs-container");
    if (!container) return;

    const subcats = (window.FEATHER_SUBCATEGORIES && window.FEATHER_SUBCATEGORIES[state.currentDepartment]) || [
      { id: "all", name: "All Fits" }
    ];

    container.innerHTML = subcats
      .map((cat) => {
        const isActive = state.currentSubcategory === cat.id;
        let count = 0;
        if (cat.id === "all") {
          count = state.currentDepartment === "all"
            ? state.products.length
            : state.products.filter((p) => p.department === state.currentDepartment).length;
        } else {
          count = state.products.filter((p) => p.category === cat.id).length;
        }

        return `
          <button type="button" class="tab-btn ${isActive ? "active" : ""}" data-subcat="${cat.id}">
            <span>${cat.name}</span>
            <span class="tab-count">${count}</span>
          </button>
        `;
      })
      .join("");

    container.querySelectorAll(".tab-btn").forEach((btn) => {
      btn.addEventListener("click", () => {
        const subcatId = btn.getAttribute("data-subcat");
        setSubcategory(subcatId, state.currentDepartment);
      });
    });
  }

  // Backward-compatible category method
  function setCategory(catId) {
    if (catId === "all" || catId === "home") {
      setDepartment("all");
    } else if (["men", "mens-fashion"].includes(catId)) {
      setDepartment("men");
    } else if (["women", "womens-fashion"].includes(catId)) {
      setDepartment("women");
    } else if (["kids", "kids-fashion"].includes(catId)) {
      setDepartment("kids");
    } else if (["anime-tshirts", "movie-tshirts", "plain-tshirts", "men-tshirts"].includes(catId)) {
      setSubcategory("men-tshirts", "men");
    } else if (["men-shirts"].includes(catId)) {
      setSubcategory("men-shirts", "men");
    } else if (["men-bottoms"].includes(catId)) {
      setSubcategory("men-bottoms", "men");
    } else if (["women-tshirts", "women-shirts", "women-bottoms"].includes(catId)) {
      setSubcategory(catId, "women");
    } else if (["kids-tshirts", "kids-shirts", "kids-sets"].includes(catId)) {
      setSubcategory(catId, "kids");
    } else {
      setDepartment("all");
    }
  }

  function renderProducts() {
    const grid = document.getElementById("products-grid");
    if (!grid) return;

    let filtered = [...state.products];

    // Filter by department
    if (state.currentDepartment && state.currentDepartment !== "all") {
      filtered = filtered.filter((p) => p.department === state.currentDepartment);
    }

    // Filter by subcategory
    if (state.currentSubcategory && state.currentSubcategory !== "all") {
      filtered = filtered.filter((p) => p.category === state.currentSubcategory);
    }

    // Apply live search
    if (state.searchQuery) {
      const q = state.searchQuery.toLowerCase();
      filtered = filtered.filter(
        (p) =>
          p.name.toLowerCase().includes(q) ||
          p.description.toLowerCase().includes(q) ||
          p.categoryName.toLowerCase().includes(q)
      );
    }

    // Apply sorting
    if (state.sortBy === "price-low") {
      filtered.sort((a, b) => a.basePrice - b.basePrice);
    } else if (state.sortBy === "price-high") {
      filtered.sort((a, b) => b.basePrice - a.basePrice);
    } else if (state.sortBy === "rating") {
      filtered.sort((a, b) => b.rating - a.rating);
    }

    if (filtered.length === 0) {
      grid.innerHTML = `
        <div style="grid-column: 1/-1; text-align: center; padding: 4rem 1rem;">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--color-secondary)" stroke-width="2" style="margin-bottom: 0.75rem;">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <h3 style="color: var(--color-primary); font-size: 1.4rem;">No fits found matching "${state.searchQuery || state.currentCategory}"</h3>
          <p style="color: var(--color-text-muted); margin-top: 0.5rem;">Try another keyword or reset the category filter.</p>
          <button class="btn-primary" style="margin-top: 1.25rem;" onclick="window.FeatherApp.clearSearch()">
            <span>Show All Fits</span>
          </button>
        </div>
      `;
      return;
    }

    grid.innerHTML = filtered
      .map((product) => {
        const isWishlisted = state.wishlist.includes(product.id);
        const discount = Math.round(
          ((product.originalPrice - product.basePrice) / product.originalPrice) * 100
        );

        return `
          <article class="product-card" data-product-id="${product.id}">
            <a href="product.html?id=${product.id}" class="card-img-wrap">
              <img src="${product.images[0]}" alt="${product.name}" class="card-img" loading="lazy" />
              ${product.badge ? `<span class="card-badge">${product.badge}</span>` : ""}
              <button class="card-wishlist-btn ${isWishlisted ? "active" : ""}" 
                      onclick="event.preventDefault(); event.stopPropagation(); window.FeatherApp.toggleWishlist('${product.id}')"
                      aria-label="Wishlist">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="${isWishlisted ? "currentColor" : "none"}" stroke="currentColor" stroke-width="2">
                  <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
              </button>
            </a>
            
            <div class="card-body">
              <span class="card-category">${product.categoryName}</span>
              <h3 class="card-title">
                <a href="product.html?id=${product.id}">${product.name}</a>
              </h3>

              <div class="card-footer">
                <div class="card-price-wrap">
                  <span class="card-price">₹${product.basePrice.toLocaleString('en-IN')}</span>
                  ${product.originalPrice ? `<span class="card-original-price">₹${product.originalPrice.toLocaleString('en-IN')}</span>` : ""}
                </div>

                <a href="product.html?id=${product.id}" class="card-quick-add">
                  <span>View Details</span>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                  </svg>
                </a>
              </div>
            </div>
          </article>
        `;
      })
      .join("");
  }

  // -------------------------------------------------------------
  // PRODUCT DETAIL MODAL (SIZE & COLOR SELECTION)
  // -------------------------------------------------------------
  function openProductModal(productId) {
    const product = state.products.find((p) => p.id === productId);
    if (!product) return;

    state.activeModalProduct = product;
    state.selectedVariant = {
      colorName: product.colors[0].name,
      colorHex: product.colors[0].hex,
      size: product.sizes.includes("M") ? "M" : product.sizes[0],
      image: product.images[0],
    };

    renderProductModal();
    const modal = document.getElementById("product-detail-modal");
    if (modal) modal.classList.add("active");
  }

  function renderProductModal() {
    const p = state.activeModalProduct;
    if (!p) return;

    const modalBody = document.getElementById("product-modal-body");
    if (!modalBody) return;

    const isWishlisted = state.wishlist.includes(p.id);
    const stockKey = `${state.selectedVariant.colorName}-${state.selectedVariant.size}`;
    const stockQty = p.stock ? p.stock[stockKey] ?? 10 : 10;
    const isOutOfStock = stockQty <= 0;

    modalBody.innerHTML = `
      <div class="product-detail-grid">
        <div class="detail-gallery">
          <div class="detail-main-img">
            <img id="detail-active-img" src="${state.selectedVariant.image}" alt="${p.name}" />
          </div>
          <div class="detail-thumbs">
            ${p.images
              .map(
                (img, idx) => `
                <div class="detail-thumb ${state.selectedVariant.image === img ? "active" : ""}" 
                     onclick="window.FeatherApp.setModalImage('${img}')">
                  <img src="${img}" alt="${p.name} angle ${idx + 1}" />
                </div>
              `
              )
              .join("")}
          </div>
        </div>

        <div class="detail-info">
          <span class="detail-category">${p.categoryName}</span>
          <h2 class="detail-title">${p.name}</h2>
          
          <div class="detail-price-box">
            <span class="detail-price">₹${p.basePrice.toLocaleString('en-IN')}</span>
            ${p.originalPrice ? `<span class="detail-orig-price">₹${p.originalPrice.toLocaleString('en-IN')}</span>` : ""}
            <span class="detail-discount-tag">Save ₹${(p.originalPrice - p.basePrice).toLocaleString('en-IN')}</span>
          </div>

          <p class="detail-desc">${p.description}</p>

          <!-- Color Selector -->
          <div class="selector-block">
            <div class="selector-label">
              <span>Color: <strong>${state.selectedVariant.colorName}</strong></span>
            </div>
            <div class="color-options">
              ${p.colors
                .map(
                  (c) => `
                  <button type="button" 
                          class="color-option-btn ${state.selectedVariant.colorName === c.name ? "active" : ""}"
                          onclick="window.FeatherApp.setModalColor('${c.name}', '${c.hex}')">
                    <span class="dot" style="background-color: ${c.hex};"></span>
                    <span>${c.name}</span>
                  </button>
                `
                )
                .join("")}
            </div>
          </div>

          <!-- Size Selector -->
          <div class="selector-block">
            <div class="selector-label">
              <span>Size: <strong>${state.selectedVariant.size}</strong></span>
              <button type="button" onclick="window.FeatherApp.openSizeGuideModal('inches')" style="color: var(--color-accent); font-size: 0.78rem; font-weight: 700; text-decoration: underline; background:none; border:none; cursor:pointer;">
                📐 Size Guide & Chart
              </button>
            </div>
            <div class="size-options">
              ${["XS", "S", "M", "L", "XL", "XXL"]
                .map((size) => {
                  const isAvailable = p.sizes.includes(size);
                  const sKey = `${state.selectedVariant.colorName}-${size}`;
                  const currentStock = p.stock ? p.stock[sKey] ?? (isAvailable ? 5 : 0) : 5;
                  const disabled = !isAvailable || currentStock <= 0;
                  return `
                    <button type="button" 
                            class="size-chip-btn ${state.selectedVariant.size === size ? "active" : ""}"
                            ${disabled ? "disabled" : ""}
                            onclick="window.FeatherApp.setModalSize('${size}')">
                      ${size}
                    </button>
                  `;
                })
                .join("")}
            </div>
          </div>

          <!-- Stock Level Indicator -->
          <div class="stock-indicator ${isOutOfStock ? "out-of-stock" : "in-stock"}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              ${
                isOutOfStock
                  ? '<circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line>'
                  : '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>'
              }
            </svg>
            <span>${isOutOfStock ? "Sold out in this size/color combination" : `In Stock (${stockQty} units available)`}</span>
          </div>

          <!-- Modal Actions -->
          <div class="detail-actions">
            <button type="button" 
                    class="btn-add-cart-lg" 
                    ${isOutOfStock ? "disabled" : ""} 
                    onclick="window.FeatherApp.addModalToCart()">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
              </svg>
              <span>${isOutOfStock ? "Out of Stock" : "Add to Shopping Cart"}</span>
            </button>

            <button type="button" 
                    class="btn-wishlist-lg ${isWishlisted ? "active" : ""}"
                    onclick="window.FeatherApp.toggleWishlist('${p.id}', true)">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="${isWishlisted ? "currentColor" : "none"}" stroke="currentColor" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    `;
  }

  function setModalColor(colorName, colorHex) {
    state.selectedVariant.colorName = colorName;
    state.selectedVariant.colorHex = colorHex;
    renderProductModal();
  }

  function setModalSize(size) {
    state.selectedVariant.size = size;
    renderProductModal();
  }

  function setModalImage(img) {
    state.selectedVariant.image = img;
    const imgEl = document.getElementById("detail-active-img");
    if (imgEl) imgEl.src = img;
    document.querySelectorAll(".detail-thumb").forEach((t) => {
      if (t.querySelector("img").src.includes(img)) {
        t.classList.add("active");
      } else {
        t.classList.remove("active");
      }
    });
  }

  function closeModals() {
    document.querySelectorAll(".modal-overlay").forEach((m) => m.classList.remove("active"));
  }

  // -------------------------------------------------------------
  // CART OPERATIONS & DRAWER
  // -------------------------------------------------------------
  function addToCart(productId, colorName, size, quantity = 1) {
    const product = state.products.find((p) => p.id === productId);
    if (!product) return;

    const cartItemId = `${productId}-${colorName}-${size}`;
    const existingIndex = state.cart.findIndex((item) => item.id === cartItemId);

    if (existingIndex > -1) {
      state.cart[existingIndex].quantity += quantity;
    } else {
      state.cart.push({
        id: cartItemId,
        productId: product.id,
        name: product.name,
        categoryName: product.categoryName,
        price: product.basePrice,
        colorName,
        size,
        image: product.images[0],
        quantity,
      });
    }

    saveState();
    showToast(`Added "${product.name}" (${size} / ${colorName}) to cart!`);
    openCartDrawer();
  }

  function addModalToCart() {
    if (!state.activeModalProduct) return;
    addToCart(
      state.activeModalProduct.id,
      state.selectedVariant.colorName,
      state.selectedVariant.size,
      1
    );
    closeModals();
  }

  function updateCartQuantity(cartItemId, delta) {
    const item = state.cart.find((i) => i.id === cartItemId);
    if (!item) return;

    item.quantity += delta;
    if (item.quantity <= 0) {
      state.cart = state.cart.filter((i) => i.id !== cartItemId);
      showToast("Item removed from cart.");
    }

    saveState();
    renderCartDrawer();
  }

  function removeCartItem(cartItemId) {
    state.cart = state.cart.filter((i) => i.id !== cartItemId);
    saveState();
    renderCartDrawer();
    showToast("Item removed from cart.");
  }

  function calculateCartTotals() {
    const subtotal = state.cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    let discountAmount = 0;
    if (state.activePromo) {
      discountAmount = +(subtotal * (state.activePromo.discountPercent / 100)).toFixed(2);
    }
    const discountedSubtotal = Math.max(0, subtotal - discountAmount);
    // Free shipping threshold: ₹999.00
    const shippingFee = discountedSubtotal >= 999 || discountedSubtotal === 0 ? 0 : 99.0;
    const tax = +(discountedSubtotal * 0.05).toFixed(2);
    const total = +(discountedSubtotal + shippingFee + tax).toFixed(2);

    return { subtotal, discountAmount, shippingFee, tax, total };
  }

  function applyPromoCode(inputCode) {
    const code = (inputCode || "").trim().toUpperCase();
    if (!code) return;

    if (code === "FEATHER15") {
      state.activePromo = { code: "FEATHER15", discountPercent: 15, label: "VIP 15% OFF" };
      showToast("Promo code 'FEATHER15' applied! 15% discount active.");
    } else if (code === "CONFIDENCE") {
      state.activePromo = { code: "CONFIDENCE", discountPercent: 25, label: "Confidence VIP 25% OFF" };
      showToast("Promo code 'CONFIDENCE' applied! 25% discount active.");
    } else {
      showToast("Invalid promo code. Try using 'FEATHER15'.", "warning");
      return;
    }

    renderCartDrawer();
    renderCheckoutSummary();
  }

  function removePromoCode() {
    state.activePromo = null;
    showToast("Promo discount removed.");
    renderCartDrawer();
    renderCheckoutSummary();
  }

  function openCartDrawer() {
    renderCartDrawer();
    const overlay = document.getElementById("cart-drawer-overlay");
    if (overlay) overlay.classList.add("active");
  }

  function closeCartDrawer() {
    const overlay = document.getElementById("cart-drawer-overlay");
    if (overlay) overlay.classList.remove("active");
  }

  function renderCartDrawer() {
    const container = document.getElementById("cart-drawer-items");
    const footer = document.getElementById("cart-drawer-footer");
    const shippingProgress = document.getElementById("cart-free-shipping-msg");
    const shippingBar = document.getElementById("cart-shipping-bar");

    if (!container || !footer) return;

    const { subtotal, discountAmount, shippingFee, tax, total } = calculateCartTotals();

    // Free shipping calculation
    if (shippingProgress && shippingBar) {
      const activeBase = subtotal - discountAmount;
      if (activeBase >= 999) {
        shippingProgress.innerHTML = `🎉 <strong>Congratulations!</strong> You qualify for <strong>FREE Shipping!</strong>`;
        shippingBar.style.width = "100%";
      } else {
        const remaining = Math.max(0, Math.round(999 - activeBase));
        const percent = Math.min(100, Math.round((activeBase / 999) * 100));
        shippingProgress.innerHTML = `Add <strong>₹${remaining}</strong> more for <strong>FREE Delivery!</strong>`;
        shippingBar.style.width = `${percent}%`;
      }
    }

    if (state.cart.length === 0) {
      container.innerHTML = `
        <div class="drawer-empty">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
          </svg>
          <p>Your shopping bag is empty.</p>
          <button class="btn-primary" onclick="window.FeatherApp.closeCartDrawer(); window.FeatherApp.setCategory('all');">
            Explore All Collections
          </button>
        </div>
      `;
      footer.style.display = "none";
      return;
    }

    footer.style.display = "block";
    container.innerHTML = state.cart
      .map((item) => {
        return `
          <div class="drawer-item">
            <div class="item-thumb">
              <img src="${item.image}" alt="${item.name}" />
            </div>
            <div class="item-details">
              <h4 class="item-name">${item.name}</h4>
              <span class="item-variant-label">Size: <strong>${item.size}</strong> | Color: <strong>${item.colorName}</strong></span>
              
              <div class="item-price-row">
                <span class="item-price">₹${Math.round(item.price * item.quantity).toLocaleString('en-IN')}</span>
                
                <div class="qty-counter">
                  <button class="qty-btn" onclick="window.FeatherApp.updateCartQuantity('${item.id}', -1)">-</button>
                  <span class="qty-value">${item.quantity}</span>
                  <button class="qty-btn" onclick="window.FeatherApp.updateCartQuantity('${item.id}', 1)">+</button>
                </div>

                <button class="item-remove-btn" onclick="window.FeatherApp.removeCartItem('${item.id}')" title="Remove">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        `;
      })
      .join("");

    footer.innerHTML = `
      <!-- Promo Code Box -->
      <div class="promo-box">
        ${
          state.activePromo
            ? `
            <div class="applied-discount-row">
              <span>Promo: <strong>${state.activePromo.code}</strong> (-${state.activePromo.discountPercent}%)</span>
              <button onclick="window.FeatherApp.removePromoCode()" style="color:var(--color-danger); font-size:0.75rem; text-decoration:underline;">Remove</button>
            </div>
          `
            : `
            <div class="promo-form">
              <input type="text" id="cart-promo-input" class="promo-input" placeholder="Promo code (e.g. FEATHER15)" value="FEATHER15" />
              <button class="btn-apply-promo" onclick="window.FeatherApp.applyPromoCode(document.getElementById('cart-promo-input').value)">Apply</button>
            </div>
          `
        }
      </div>

      <div class="drawer-summary-row" style="margin-top: 1rem;">
        <span>Subtotal</span>
        <span>₹${Math.round(subtotal).toLocaleString('en-IN')}</span>
      </div>
      ${
        discountAmount > 0
          ? `
        <div class="drawer-summary-row" style="color: var(--color-success); font-weight: 700;">
          <span>Discount (${state.activePromo.code})</span>
          <span>-₹${Math.round(discountAmount).toLocaleString('en-IN')}</span>
        </div>
      `
          : ""
      }
      <div class="drawer-summary-row">
        <span>Estimated Shipping</span>
        <span>${shippingFee === 0 ? '<strong style="color:var(--color-success)">FREE</strong>' : `₹${shippingFee.toLocaleString('en-IN')}`}</span>
      </div>
      <div class="drawer-summary-row">
        <span>Estimated GST (5%)</span>
        <span>₹${tax.toLocaleString('en-IN')}</span>
      </div>
      <div class="drawer-summary-row total">
        <span>Total</span>
        <span>₹${total.toLocaleString('en-IN')}</span>
      </div>
      <button class="btn-checkout" onclick="window.FeatherApp.openCheckoutModal()">
        <span>Secure Checkout</span>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
          <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
        </svg>
      </button>
    `;
  }

  // -------------------------------------------------------------
  // WISHLIST OPERATIONS & DRAWER
  // -------------------------------------------------------------
  function toggleWishlist(productId, updateModal = false) {
    const index = state.wishlist.indexOf(productId);
    const product = state.products.find((p) => p.id === productId);

    if (index > -1) {
      state.wishlist.splice(index, 1);
      showToast(`Removed from your wishlist.`);
    } else {
      state.wishlist.push(productId);
      showToast(`Added to your wishlist!`);
    }

    saveState();
    renderProducts();
    renderNewArrivalsCarousel();

    if (updateModal && state.activeModalProduct?.id === productId) {
      renderProductModal();
    }
  }

  function openWishlistDrawer() {
    renderWishlistDrawer();
    const overlay = document.getElementById("wishlist-drawer-overlay");
    if (overlay) overlay.classList.add("active");
  }

  function closeWishlistDrawer() {
    const overlay = document.getElementById("wishlist-drawer-overlay");
    if (overlay) overlay.classList.remove("active");
  }

  function renderWishlistDrawer() {
    const container = document.getElementById("wishlist-drawer-items");
    if (!container) return;

    if (state.wishlist.length === 0) {
      container.innerHTML = `
        <div class="drawer-empty">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
          </svg>
          <p>Your wishlist is empty.</p>
          <button class="btn-primary" onclick="window.FeatherApp.closeWishlistDrawer(); window.FeatherApp.setCategory('all');">
            Discover Styles
          </button>
        </div>
      `;
      return;
    }

    const wishlistedProducts = state.products.filter((p) => state.wishlist.includes(p.id));

    container.innerHTML = wishlistedProducts
      .map((item) => {
        return `
          <div class="drawer-item">
            <a href="product.html?id=${item.id}" class="item-thumb" onclick="window.FeatherApp.closeWishlistDrawer()" style="cursor:pointer">
              <img src="${item.images[0]}" alt="${item.name}" />
            </a>
            <div class="item-details">
              <h4 class="item-name">
                <a href="product.html?id=${item.id}" onclick="window.FeatherApp.closeWishlistDrawer()">${item.name}</a>
              </h4>
              <span class="item-variant-label">${item.categoryName}</span>
              
              <div class="item-price-row">
                <span class="item-price">₹${item.basePrice.toLocaleString('en-IN')}</span>
                
                <a href="product.html?id=${item.id}" class="card-quick-add" onclick="window.FeatherApp.closeWishlistDrawer()">
                  <span>Select & Buy</span>
                </a>

                <button class="item-remove-btn" onclick="window.FeatherApp.toggleWishlist('${item.id}')" title="Remove">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        `;
      })
      .join("");
  }

  // -------------------------------------------------------------
  // SECURE CHECKOUT & PAYMENT INTEGRATION
  // -------------------------------------------------------------
  function openCheckoutModal() {
    if (state.cart.length === 0) {
      showToast("Your cart is empty.", "warning");
      return;
    }

    closeCartDrawer();
    renderCheckoutSummary();

    // Auto-fill customer info if logged in
    if (state.user) {
      const emailInput = document.getElementById("checkout-email");
      const fnInput = document.getElementById("checkout-first-name");
      const lnInput = document.getElementById("checkout-last-name");
      const phoneInput = document.getElementById("checkout-phone");
      const addrInput = document.getElementById("checkout-address");
      const cityInput = document.getElementById("checkout-city");
      const stateInput = document.getElementById("checkout-state");
      const zipInput = document.getElementById("checkout-zip");

      if (emailInput) emailInput.value = state.user.email || "";
      if (fnInput) fnInput.value = state.user.firstName || "";
      if (lnInput) lnInput.value = state.user.lastName || "";
      if (phoneInput && state.user.phone) phoneInput.value = state.user.phone.replace("+91 ", "").trim();

      const defaultAddr = (state.user.addresses || []).find((a) => a.isDefault) || (state.user.addresses || [])[0];
      if (defaultAddr) {
        if (addrInput) addrInput.value = defaultAddr.line1;
        if (cityInput) cityInput.value = defaultAddr.city;
        if (stateInput) stateInput.value = defaultAddr.state;
        if (zipInput) zipInput.value = defaultAddr.postalCode;
      }
    }

    const modal = document.getElementById("checkout-modal");
    if (modal) modal.classList.add("active");
  }

  function renderCheckoutSummary() {
    const summaryContainer = document.getElementById("checkout-order-summary");
    if (!summaryContainer) return;

    const { subtotal, discountAmount, shippingFee, tax, total } = calculateCartTotals();

    summaryContainer.innerHTML = `
      <h3 class="checkout-section-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="9" cy="21" r="1"></circle>
          <circle cx="20" cy="21" r="1"></circle>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
        <span>Order Summary (${state.cart.reduce((a, b) => a + b.quantity, 0)} Items)</span>
      </h3>

      <div style="max-height: 220px; overflow-y: auto; margin-bottom: 1.25rem;">
        ${state.cart
          .map(
            (item) => `
            <div style="display: flex; gap: 0.75rem; align-items: center; margin-bottom: 0.75rem;">
              <img src="${item.image}" style="width: 44px; height: 54px; object-fit: cover; border-radius: 4px;" alt="${item.name}" />
              <div style="flex:1; font-size: 0.82rem;">
                <div style="font-weight: 700; color: var(--color-primary);">${item.name}</div>
                <div style="color: var(--color-text-muted);">${item.size} / ${item.colorName} × ${item.quantity}</div>
              </div>
              <div style="font-weight: 700; font-size: 0.9rem;">₹${Math.round(item.price * item.quantity).toLocaleString('en-IN')}</div>
            </div>
          `
          )
          .join("")}
      </div>

      <div class="drawer-summary-row">
        <span>Subtotal</span>
        <span>₹${Math.round(subtotal).toLocaleString('en-IN')}</span>
      </div>
      ${
        discountAmount > 0
          ? `
        <div class="drawer-summary-row" style="color: var(--color-success); font-weight: 700;">
          <span>Discount (${state.activePromo.code})</span>
          <span>-₹${Math.round(discountAmount).toLocaleString('en-IN')}</span>
        </div>
      `
          : ""
      }
      <div class="drawer-summary-row">
        <span>Shipping</span>
        <span>${shippingFee === 0 ? '<strong style="color:var(--color-success)">FREE</strong>' : `₹${shippingFee.toLocaleString('en-IN')}`}</span>
      </div>
      <div class="drawer-summary-row">
        <span>Estimated GST (5%)</span>
        <span>₹${tax.toLocaleString('en-IN')}</span>
      </div>
      <div class="drawer-summary-row total">
        <span>Total Due</span>
        <span style="color: var(--color-accent); font-size: 1.4rem;">₹${total.toLocaleString('en-IN')}</span>
      </div>
    `;
  }

  let otpCountdownTimer = null;

  function sendCheckoutOtp() {
    const phoneInput = document.getElementById("checkout-phone");
    const sendOtpBtn = document.getElementById("send-otp-btn");
    const otpBadge = document.getElementById("otp-timer-badge");
    const otpInput = document.getElementById("checkout-otp");

    const phone = phoneInput ? phoneInput.value.trim() : "";
    if (!phone || phone.length < 8) {
      showToast("Please enter a valid mobile number first.", "warning");
      if (phoneInput) phoneInput.focus();
      return;
    }

    if (sendOtpBtn) {
      sendOtpBtn.disabled = true;
      let secondsLeft = 30;
      sendOtpBtn.innerHTML = `<span>Resend (${secondsLeft}s)</span>`;

      if (otpCountdownTimer) clearInterval(otpCountdownTimer);
      otpCountdownTimer = setInterval(() => {
        secondsLeft--;
        if (secondsLeft <= 0) {
          clearInterval(otpCountdownTimer);
          sendOtpBtn.disabled = false;
          sendOtpBtn.innerHTML = `<span>Resend OTP</span>`;
        } else {
          sendOtpBtn.innerHTML = `<span>Resend (${secondsLeft}s)</span>`;
        }
      }, 1000);
    }

    if (otpBadge) {
      otpBadge.textContent = "OTP code sent to +91 " + phone;
      otpBadge.style.color = "var(--color-success)";
    }

    if (otpInput) {
      otpInput.value = "8921";
      otpInput.focus();
    }

    showToast(`Verification OTP sent to +91 ${phone}! Demo Code: 8921`, "success");
  }

  function fillDemoOtp() {
    const phoneInput = document.getElementById("checkout-phone");
    const otpInput = document.getElementById("checkout-otp");
    const otpBadge = document.getElementById("otp-timer-badge");

    if (phoneInput && !phoneInput.value.trim()) {
      phoneInput.value = "98765 43210";
    }
    if (otpInput) {
      otpInput.value = "8921";
    }
    if (otpBadge) {
      otpBadge.textContent = "Demo OTP 8921 ready";
      otpBadge.style.color = "var(--color-success)";
    }
    showToast("Demo OTP code 8921 applied!", "info");
  }

  function fillTestCard() {
    fillDemoOtp();
  }

  function handleProcessPayment(event) {
    event.preventDefault();

    const form = document.getElementById("checkout-form");
    if (!form) return;

    const email = document.getElementById("checkout-email")?.value.trim() || "";
    const firstName = document.getElementById("checkout-first-name")?.value.trim() || "";
    const lastName = document.getElementById("checkout-last-name")?.value.trim() || "";
    const address1 = document.getElementById("checkout-address")?.value.trim() || "";
    const city = document.getElementById("checkout-city")?.value.trim() || "";
    const stateVal = document.getElementById("checkout-state")?.value.trim() || "";
    const zip = document.getElementById("checkout-zip")?.value.trim() || "";

    const phoneInput = document.getElementById("checkout-phone");
    const otpInput = document.getElementById("checkout-otp");

    const phone = phoneInput ? phoneInput.value.trim() : "";
    const otp = otpInput ? otpInput.value.trim() : "";

    if (!email || !firstName || !address1 || !city || !zip) {
      showToast("Please fill in all required shipping fields.", "warning");
      return;
    }

    if (!phone || phone.length < 8) {
      showToast("Please enter a valid mobile number.", "warning");
      if (phoneInput) phoneInput.focus();
      return;
    }

    if (!otp || otp.length < 4) {
      showToast("Please enter the 4-digit OTP sent to your phone (Demo: 8921).", "warning");
      if (otpInput) otpInput.focus();
      return;
    }

    const payBtn = document.getElementById("pay-submit-btn");
    const originalBtnText = payBtn ? payBtn.innerHTML : "";
    if (payBtn) {
      payBtn.disabled = true;
      payBtn.innerHTML = `
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spin">
          <line x1="12" y1="2" x2="12" y2="6"></line>
          <line x1="12" y1="18" x2="12" y2="22"></line>
          <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
          <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
          <line x1="2" y1="12" x2="6" y2="12"></line>
          <line x1="18" y1="12" x2="22" y2="12"></line>
          <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
          <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
        </svg>
        <span>Verifying OTP & Securing Order...</span>
      `;
    }

    setTimeout(() => {
      const { subtotal, shippingFee, tax, total } = calculateCartTotals();
      const orderNumber = `FF-${Math.floor(10000 + Math.random() * 90000)}`;

      const newOrder = {
        orderNumber,
        createdAt: new Date().toISOString(),
        status: "PROCESSING",
        paymentStatus: "OTP_VERIFIED",
        paymentMethod: "Cash / UPI on Delivery (Mobile OTP Verified)",
        customerPhone: phone,
        carrier: "FedEx Standard Priority",
        trackingNumber: `FX-${Math.floor(1000000000 + Math.random() * 9000000000)}`,
        estimatedDelivery: "3-4 Business Days",
        customerEmail: email,
        shippingAddress: {
          firstName,
          lastName,
          line1: address1,
          city,
          state: stateVal,
          postalCode: zip,
        },
        items: [...state.cart],
        subtotal,
        shippingFee,
        tax,
        total,
      };

      // Record Order in State
      state.orders.unshift(newOrder);
      // Clear Cart
      state.cart = [];
      saveState();

      if (payBtn) {
        payBtn.disabled = false;
        payBtn.innerHTML = originalBtnText;
      }

      closeModals();
      showOrderSuccessModal(newOrder);
    }, 1200);
  }

  function showOrderSuccessModal(order) {
    const modal = document.getElementById("order-success-modal");
    const content = document.getElementById("order-success-content");
    if (!modal || !content) return;

    content.innerHTML = `
      <div style="text-align: center; padding: 1rem 0;">
        <div style="width: 72px; height: 72px; border-radius: 50%; background: #ecfdf5; color: var(--color-success); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
        </div>
        <h2 style="font-size: 2rem; font-weight: 800; color: var(--color-primary); margin-bottom: 0.5rem;">Order Confirmed!</h2>
        <p style="color: var(--color-text-muted); font-size: 0.95rem; margin-bottom: 1.5rem;">
          Thank you for trusting Feather Fits. Your order is confirmed via mobile OTP and digital receipt is sent to <strong>${order.customerEmail}</strong>.
        </p>

        <div style="background: var(--color-bg); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 1.25rem; text-align: left; margin-bottom: 1.5rem;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.88rem;">
            <span>Order Number:</span>
            <strong style="color: var(--color-primary);">${order.orderNumber}</strong>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.88rem;">
            <span>Tracking Number:</span>
            <span style="font-family: monospace; font-weight: 700;">${order.trackingNumber}</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.88rem;">
            <span>OTP Verified Mobile:</span>
            <strong style="color: var(--color-success); font-family: monospace;">+91 ${order.customerPhone || '98765 43210'}</strong>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.88rem;">
            <span>Payment Mode:</span>
            <span style="font-weight: 700; color: var(--color-primary);">Pay on Delivery (COD / UPI)</span>
          </div>
          <div style="display: flex; justify-content: space-between; font-size: 0.88rem;">
            <span>Total Payable:</span>
            <strong style="color: var(--color-accent); font-size: 1.15rem;">₹${order.total.toLocaleString('en-IN')}</strong>
          </div>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: center;">
          <button class="btn-primary" onclick="window.FeatherApp.closeModals(); window.FeatherApp.trackOrder('${order.orderNumber}')">
            <span>Track Order Now</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
          </button>
          <button class="btn-secondary" style="color: var(--color-text-main); border-color: var(--color-border);" onclick="window.FeatherApp.closeModals()">
            Continue Shopping
          </button>
        </div>
      </div>
    `;

    modal.classList.add("active");
  }

  // -------------------------------------------------------------
  // ORDER TRACKING SYSTEM
  // -------------------------------------------------------------
  function openTrackingModal() {
    const modal = document.getElementById("tracking-modal");
    if (modal) modal.classList.add("active");
    renderRecentOrdersList();
  }

  function trackOrder(orderNumber) {
    const searchInput = document.getElementById("tracking-order-input");
    if (searchInput) searchInput.value = orderNumber;
    executeTrackingSearch(orderNumber);
    const modal = document.getElementById("tracking-modal");
    if (modal) modal.classList.add("active");
  }

  function executeTrackingSearch(query) {
    const resultBox = document.getElementById("tracking-result-box");
    if (!resultBox) return;

    const trimmed = (query || "").trim().toUpperCase();
    if (!trimmed) {
      resultBox.innerHTML = `<p style="text-align: center; color: var(--color-text-muted);">Please enter an order number (e.g., FF-89214).</p>`;
      return;
    }

    const order = state.orders.find(
      (o) => o.orderNumber.toUpperCase() === trimmed || o.trackingNumber?.toUpperCase() === trimmed
    );

    if (!order) {
      resultBox.innerHTML = `
        <div style="text-align: center; padding: 2rem 1rem;">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--color-danger)" stroke-width="2" style="margin-bottom: 0.75rem;">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
          </svg>
          <h4>Order "${trimmed}" not found</h4>
          <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-top: 0.4rem;">
            Please double check your confirmation email receipt or choose from recent orders below.
          </p>
        </div>
      `;
      return;
    }

    // Step states: 1. Confirmed, 2. Processing, 3. Shipped, 4. Delivered
    const statusMap = {
      PENDING_PAYMENT: 1,
      PAID: 1,
      PROCESSING: 2,
      SHIPPED: 3,
      OUT_FOR_DELIVERY: 3,
      DELIVERED: 4,
    };
    const currentStep = statusMap[order.status] || 2;

    resultBox.innerHTML = `
      <div style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 1.5rem; margin-top: 1.25rem;">
        <div style="display: flex; justify-content: space-between; align-items: baseline; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
          <div>
            <span style="font-size: 0.78rem; text-transform: uppercase; color: var(--color-secondary); font-weight: 700;">Order Status</span>
            <h3 style="font-size: 1.4rem; font-weight: 800; color: var(--color-primary);">${order.orderNumber}</h3>
          </div>
          <span class="security-badge" style="font-size: 0.85rem; padding: 0.3rem 0.8rem;">${order.status}</span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; background: var(--color-bg); padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-size: 0.82rem;">
          <div>
            <div style="color: var(--color-text-muted);">Courier Carrier:</div>
            <strong>${order.carrier || "FedEx Express"}</strong>
          </div>
          <div>
            <div style="color: var(--color-text-muted);">Tracking ID:</div>
            <strong style="font-family: monospace;">${order.trackingNumber}</strong>
          </div>
          <div>
            <div style="color: var(--color-text-muted);">Estimated Delivery:</div>
            <strong style="color: var(--color-success);">${order.estimatedDelivery || "In Transit"}</strong>
          </div>
        </div>

        <!-- Visual Timeline -->
        <div class="tracking-timeline">
          <div class="timeline-step ${currentStep >= 1 ? "completed" : ""}">
            <div class="timeline-step-dot">✓</div>
            <div class="timeline-title">Order Placed & Verified</div>
            <div class="timeline-desc">${new Date(order.createdAt).toLocaleDateString()} — Payment secured via 256-bit encryption</div>
          </div>

          <div class="timeline-step ${currentStep > 2 ? "completed" : currentStep === 2 ? "active" : ""}">
            <div class="timeline-step-dot">${currentStep > 2 ? "✓" : "2"}</div>
            <div class="timeline-title">Fulfillment & Custom Quality Check</div>
            <div class="timeline-desc">Tailoring team inspecting fabric, seams & graphic print quality</div>
          </div>

          <div class="timeline-step ${currentStep > 3 ? "completed" : currentStep === 3 ? "active" : ""}">
            <div class="timeline-step-dot">${currentStep > 3 ? "✓" : "3"}</div>
            <div class="timeline-title">Dispatched & In Transit</div>
            <div class="timeline-desc">Carrier scan recorded at Regional Logistics Hub</div>
          </div>

          <div class="timeline-step ${currentStep >= 4 ? "completed" : ""}">
            <div class="timeline-step-dot">${currentStep >= 4 ? "✓" : "4"}</div>
            <div class="timeline-title">Delivered to Destination</div>
            <div class="timeline-desc">Signed & handed to recipient</div>
          </div>
        </div>

        <!-- Package Contents -->
        <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.75rem;">Package Items:</h4>
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
          ${order.items
            .map(
              (i) => `
              <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.4rem;">
                <span><strong>${i.quantity}x</strong> ${i.name} (${i.size} / ${i.colorName || i.color})</span>
                <strong>₹${Math.round(((i.price || i.unitPrice) * i.quantity)).toLocaleString('en-IN')}</strong>
              </div>
            `
            )
            .join("")}
        </div>
      </div>
    `;
  }

  function renderRecentOrdersList() {
    const listContainer = document.getElementById("recent-orders-list");
    if (!listContainer) return;

    if (state.orders.length === 0) {
      listContainer.innerHTML = `<p style="font-size: 0.85rem; color: var(--color-text-muted);">No past orders found in your session.</p>`;
      return;
    }

    listContainer.innerHTML = state.orders
      .map(
        (o) => `
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.65rem 0.85rem; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm); margin-bottom: 0.5rem; font-size: 0.85rem;">
          <div>
            <strong style="color: var(--color-primary);">${o.orderNumber}</strong>
            <div style="font-size: 0.75rem; color: var(--color-text-muted);">${new Date(o.createdAt).toLocaleDateString()} • ₹${o.total.toLocaleString('en-IN')}</div>
          </div>
          <button class="track-order-btn" onclick="window.FeatherApp.trackOrder('${o.orderNumber}')">
            Track
          </button>
        </div>
      `
      )
      .join("");
  }

  // -------------------------------------------------------------
  // USER ACCOUNT DASHBOARD & PROFILE SYSTEM
  // -------------------------------------------------------------
  function openAccountModal(tab = "tracking", orderNumber = null) {
    const targetUrl = `account.html?tab=${tab || 'tracking'}${orderNumber ? '&order=' + encodeURIComponent(orderNumber) : ''}`;
    window.location.href = targetUrl;
  }

  function setAccountTab(tabName) {
    state.activeAccountTab = tabName;
    renderProfileView();
  }

  function renderAuthView() {
    const body = document.getElementById("account-modal-body");
    if (!body) return;

    body.innerHTML = `
      <div style="padding: 2rem;">
        <div style="text-align: center; margin-bottom: 1.5rem;">
          <div style="width: 56px; height: 56px; background: linear-gradient(135deg, var(--color-secondary), var(--color-accent)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; color: #fff;">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
          </div>
          <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--color-primary);">Sign In to Feather Fits</h2>
          <p style="color: var(--color-text-muted); font-size: 0.88rem; margin-top: 0.35rem;">
            Access live order tracking, saved delivery addresses, and Feather Coins.
          </p>
        </div>

        <form id="auth-form" onsubmit="window.FeatherApp.handleAuthSubmit(event)">
          <div class="form-group" style="margin-bottom: 1rem;">
            <label style="font-size: 0.85rem; font-weight: 700; color: var(--color-primary);">Email Address</label>
            <input type="email" id="auth-email" required class="form-input" placeholder="you@example.com" value="arun.kumar@featherfits.com" />
          </div>

          <div class="form-group" style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.85rem; font-weight: 700; color: var(--color-primary);">Password</label>
            <input type="password" id="auth-password" required class="form-input" placeholder="••••••••" value="confidence2026" />
          </div>

          <button type="submit" class="btn-checkout" style="width: 100%; height: 50px;">
            <span>Sign In to Member Dashboard</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
              <polyline points="10 17 15 12 10 7"></polyline>
              <line x1="15" y1="12" x2="3" y2="12"></line>
            </svg>
          </button>
        </form>

        <div style="background: var(--color-bg); padding: 0.85rem; border-radius: var(--radius-sm); text-align: center; margin-top: 1.25rem; font-size: 0.82rem; color: var(--color-text-muted); border: 1px dashed var(--color-border);">
          💡 <strong>Demo Account:</strong> Click "Sign In" with pre-filled test credentials.
        </div>
      </div>
    `;
  }

  function handleAuthSubmit(event) {
    if (event) event.preventDefault();
    const email = document.getElementById("auth-email")?.value.trim() || "arun.kumar@featherfits.com";

    state.user = {
      ...defaultMember,
      email,
    };

    saveState();
    showToast(`Welcome back, ${state.user.firstName}! Member Dashboard Ready.`);
    renderProfileView();
  }

  function renderProfileView() {
    const body = document.getElementById("account-modal-body");
    if (!body || !state.user) return;

    const u = state.user;
    const activeTab = state.activeAccountTab || "tracking";

    body.innerHTML = `
      <div class="account-dashboard-wrap">
        <!-- Dashboard Header -->
        <div class="account-dashboard-header">
          <div class="account-user-meta">
            <div class="account-avatar">
              ${(u.firstName?.[0] || "A") + (u.lastName?.[0] || "K")}
            </div>
            <div class="account-user-info">
              <h2>${u.firstName} ${u.lastName}</h2>
              <div class="sub-text">${u.email} • +91 ${u.phone || '98765 43210'}</div>
              <div class="account-badge-group">
                <span class="account-badge vip">★ ${u.tier || 'VIP Member'}</span>
                <span class="account-badge coins">🪙 ${u.coins || 450} Feather Coins (₹${u.coins || 450})</span>
              </div>
            </div>
          </div>
          <button class="btn-order-action" onclick="window.FeatherApp.handleLogout()" style="background: rgba(255,255,255,0.12); color:#FFFFFF; border-color: rgba(255,255,255,0.25);">
            Sign Out
          </button>
        </div>

        <!-- Dashboard Navigation Tabs -->
        <div class="account-nav-tabs">
          <button class="account-tab-btn ${activeTab === 'tracking' ? 'active' : ''}" onclick="window.FeatherApp.setAccountTab('tracking')">
            <span>🚚 Live Tracking & Orders</span>
          </button>
          <button class="account-tab-btn ${activeTab === 'addresses' ? 'active' : ''}" onclick="window.FeatherApp.setAccountTab('addresses')">
            <span>📍 Saved Addresses (${(u.addresses || []).length})</span>
          </button>
          <button class="account-tab-btn ${activeTab === 'coins' ? 'active' : ''}" onclick="window.FeatherApp.setAccountTab('coins')">
            <span>🪙 Rewards & Wallet</span>
          </button>
          <button class="account-tab-btn ${activeTab === 'fit' ? 'active' : ''}" onclick="window.FeatherApp.setAccountTab('fit')">
            <span>📏 Size & Fit (${u.preferredSize || 'L'})</span>
          </button>
          <button class="account-tab-btn ${activeTab === 'settings' ? 'active' : ''}" onclick="window.FeatherApp.setAccountTab('settings')">
            <span>⚙️ WhatsApp & Alerts</span>
          </button>
        </div>

        <!-- Dashboard Tab Content -->
        <div class="account-tab-content">
          ${renderAccountTabBody(activeTab)}
        </div>
      </div>
    `;
  }

  function renderAccountTabBody(tab) {
    const u = state.user;

    switch (tab) {
      case "tracking":
        return renderAccountTrackingTab();
      case "addresses":
        return renderAccountAddressesTab();
      case "coins":
        return renderAccountCoinsTab();
      case "fit":
        return renderAccountFitTab();
      case "settings":
        return renderAccountSettingsTab();
      default:
        return renderAccountTrackingTab();
    }
  }

  // TAB 1: Live Tracking & Orders
  function renderAccountTrackingTab() {
    const activeOrderNum = state.activeTrackingOrderNum || (state.orders[0]?.orderNumber || "FF-89214");
    const activeOrder = state.orders.find((o) => o.orderNumber.toUpperCase() === activeOrderNum.toUpperCase()) || state.orders[0];

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
        <!-- Search Any Package Inside Account -->
        <div class="account-tracking-box">
          <span style="font-size: 0.8rem; font-weight: 700; color: var(--color-secondary); text-transform: uppercase;">Track Live Package</span>
          <div style="display: flex; gap: 0.65rem; margin-top: 0.5rem; flex-wrap: wrap;">
            <input type="text" id="account-track-input" class="form-input" style="flex: 1; min-width: 180px; height: 44px;" placeholder="Enter Order # (e.g. FF-89214)" value="${activeOrderNum}" />
            <button class="btn-primary" style="height: 44px; padding: 0 1.25rem;" onclick="window.FeatherApp.trackOrderInsideAccount(document.getElementById('account-track-input').value)">
              Track Delivery
            </button>
          </div>
        </div>

        ${activeOrder ? `
          <!-- Active Milestone Visual Timeline -->
          <div style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
              <div>
                <span style="font-size: 0.76rem; color: var(--color-text-muted); text-transform: uppercase; font-weight: 700;">Live Package Status</span>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--color-primary);">${activeOrder.orderNumber}</h3>
              </div>
              <span class="security-badge" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;">${activeOrder.status}</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 0.75rem; background: var(--color-bg); padding: 0.85rem; border-radius: var(--radius-sm); margin-bottom: 1.25rem; font-size: 0.82rem;">
              <div>
                <div style="color: var(--color-text-muted);">Courier Carrier:</div>
                <strong>${activeOrder.carrier || "Blue Dart Express"}</strong>
              </div>
              <div>
                <div style="color: var(--color-text-muted);">AWB Tracking ID:</div>
                <strong style="font-family: monospace;">${activeOrder.trackingNumber || 'BD-9402849182'}</strong>
              </div>
              <div>
                <div style="color: var(--color-text-muted);">Estimated Delivery:</div>
                <strong style="color: var(--color-success);">${activeOrder.estimatedDelivery || "In 2 Days"}</strong>
              </div>
            </div>

            <!-- Milestone Tracker -->
            <div class="tracking-timeline" style="margin: 1rem 0;">
              <div class="timeline-step ${currentStep >= 1 ? 'completed' : ''}">
                <div class="timeline-step-dot">✓</div>
                <div class="timeline-title">Order Placed & OTP Verified</div>
                <div class="timeline-desc">${new Date(activeOrder.createdAt).toLocaleDateString()} — Secured via Pay on Delivery</div>
              </div>

              <div class="timeline-step ${currentStep > 2 ? 'completed' : currentStep === 2 ? 'active' : ''}">
                <div class="timeline-step-dot">${currentStep > 2 ? '✓' : '2'}</div>
                <div class="timeline-title">Quality Inspected & Packed</div>
                <div class="timeline-desc">240 GSM pre-shrunk cotton checked for print perfection</div>
              </div>

              <div class="timeline-step ${currentStep > 3 ? 'completed' : currentStep === 3 ? 'active' : ''}">
                <div class="timeline-step-dot">${currentStep > 3 ? '✓' : '3'}</div>
                <div class="timeline-title">In Transit with Blue Dart</div>
                <div class="timeline-desc">Dispatched from Chennai Hub • Next Stop Destination Delivery</div>
              </div>

              <div class="timeline-step ${currentStep >= 4 ? 'completed' : ''}">
                <div class="timeline-step-dot">${currentStep >= 4 ? '✓' : '4'}</div>
                <div class="timeline-title">Delivered to Recipient</div>
                <div class="timeline-desc">Delivered to door with 30-day easy exchange guarantee</div>
              </div>
            </div>
          </div>
        ` : ''}

        <!-- All Past Orders List with Actions -->
        <h4 style="font-size: 1.05rem; font-weight: 700; color: var(--color-primary); margin-bottom: 1rem;">
          Your Order History (${state.orders.length})
        </h4>

        ${state.orders.map((o) => `
          <div class="order-card-item">
            <div class="order-card-header">
              <div>
                <strong style="font-size: 1rem; color: var(--color-primary);">${o.orderNumber}</strong>
                <span style="font-size: 0.78rem; color: var(--color-text-muted); margin-left: 0.5rem;">${new Date(o.createdAt).toLocaleDateString()}</span>
              </div>
              <div>
                <span style="color: var(--color-accent); font-weight: 800; font-size: 1.05rem;">₹${o.total.toLocaleString('en-IN')}</span>
              </div>
            </div>

            <!-- Items -->
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
              ${o.items.map((i) => `
                <div style="display: flex; gap: 0.75rem; align-items: center; font-size: 0.85rem;">
                  <img src="${i.image || 'assets/images/anime-tee.jpg'}" style="width: 40px; height: 50px; object-fit: cover; border-radius: 4px; object-position: top center;" alt="${i.name}" />
                  <div style="flex: 1;">
                    <div style="font-weight: 700; color: var(--color-primary);">${i.name}</div>
                    <div style="font-size: 0.78rem; color: var(--color-text-muted);">Size: <strong>${i.size}</strong> | Color: <strong>${i.colorName || i.color || 'Standard'}</strong> × ${i.quantity}</div>
                  </div>
                  <strong>₹${Math.round((i.price || 1299) * i.quantity).toLocaleString('en-IN')}</strong>
                </div>
              `).join('')}
            </div>

            <!-- Action Buttons: Track, Invoice, Return -->
            <div class="order-actions-bar">
              <button class="btn-order-action primary" onclick="window.FeatherApp.trackOrderInsideAccount('${o.orderNumber}')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span>Live Tracking</span>
              </button>
              <button class="btn-order-action" onclick="window.FeatherApp.showInvoice('${o.orderNumber}')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                <span>View Invoice</span>
              </button>
              <button class="btn-order-action" onclick="window.FeatherApp.requestExchange('${o.orderNumber}')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                <span>Exchange Size</span>
              </button>
            </div>
          </div>
        `).join('')}
      </div>
    `;
  }

  // TAB 2: Saved Delivery Addresses
  function renderAccountAddressesTab() {
    const u = state.user;
    const addresses = u.addresses || [];

    return `
      <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
          <div>
            <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--color-primary);">Saved Delivery Addresses</h3>
            <p style="font-size: 0.82rem; color: var(--color-text-muted);">Default address will automatically fill at checkout.</p>
          </div>
          <button class="btn-primary" style="padding: 0.5rem 1rem; font-size: 0.82rem;" onclick="window.FeatherApp.toggleAddAddressForm()">
            + Add New Address
          </button>
        </div>

        ${state.showAddAddressForm ? `
          <!-- Inline New Address Form -->
          <div style="background: var(--color-bg); border: 1.5px solid var(--color-secondary); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.5rem; animation: fadeIn 0.2s ease;">
            <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.85rem;">Add New Address</h4>
            <form onsubmit="window.FeatherApp.handleAddAddress(event)">
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem; margin-bottom: 0.75rem;">
                <div>
                  <label style="font-size: 0.78rem; font-weight: 700;">Address Tag</label>
                  <select id="addr-tag" class="form-input" style="height: 38px;">
                    <option value="Home">Home</option>
                    <option value="Office">Office / Work</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <div>
                  <label style="font-size: 0.78rem; font-weight: 700;">Recipient Name</label>
                  <input type="text" id="addr-name" required class="form-input" style="height: 38px;" placeholder="Full Name" value="${u.firstName} ${u.lastName}" />
                </div>
                <div>
                  <label style="font-size: 0.78rem; font-weight: 700;">Mobile (+91)</label>
                  <input type="tel" id="addr-phone" required class="form-input" style="height: 38px;" placeholder="98765 43210" value="${u.phone || '98765 43210'}" />
                </div>
              </div>

              <div style="margin-bottom: 0.75rem;">
                <label style="font-size: 0.78rem; font-weight: 700;">Street Address, Flat / House No.</label>
                <input type="text" id="addr-line" required class="form-input" style="height: 38px;" placeholder="e.g. 15, Anna Salai" />
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                <div>
                  <label style="font-size: 0.78rem; font-weight: 700;">City</label>
                  <input type="text" id="addr-city" required class="form-input" style="height: 38px;" placeholder="Chennai" />
                </div>
                <div>
                  <label style="font-size: 0.78rem; font-weight: 700;">State</label>
                  <input type="text" id="addr-state" required class="form-input" style="height: 38px;" placeholder="Tamil Nadu" />
                </div>
                <div>
                  <label style="font-size: 0.78rem; font-weight: 700;">Pincode</label>
                  <input type="text" id="addr-zip" required class="form-input" style="height: 38px;" placeholder="600001" />
                </div>
              </div>

              <div style="display: flex; gap: 0.75rem;">
                <button type="submit" class="btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;">Save Delivery Address</button>
                <button type="button" class="btn-order-action" onclick="window.FeatherApp.toggleAddAddressForm()">Cancel</button>
              </div>
            </form>
          </div>
        ` : ''}

        <div class="addresses-grid">
          ${addresses.map((a) => `
            <div class="address-card ${a.isDefault ? 'default' : ''}">
              <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <span class="address-tag-pill ${a.tag.toLowerCase()}">${a.tag}</span>
                ${a.isDefault ? `<span style="font-size: 0.75rem; font-weight: 700; color: var(--color-secondary);">✓ Default</span>` : ''}
              </div>

              <strong style="font-size: 0.95rem; color: var(--color-primary); display: block; margin-bottom: 0.25rem;">${a.name}</strong>
              <div style="font-size: 0.85rem; color: var(--color-text-main); line-height: 1.5; margin-bottom: 0.5rem;">
                ${a.line1}<br>
                ${a.city}, ${a.state} — <strong>${a.postalCode}</strong>
              </div>
              <div style="font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 1rem;">
                Phone: <strong>${a.phone}</strong>
              </div>

              <div style="display: flex; gap: 0.5rem; border-top: 1px solid var(--color-border); padding-top: 0.75rem;">
                ${!a.isDefault ? `
                  <button class="btn-order-action" style="font-size: 0.75rem; padding: 0.35rem 0.65rem;" onclick="window.FeatherApp.setDefaultAddress('${a.id}')">
                    Set Default
                  </button>
                ` : ''}
                ${addresses.length > 1 ? `
                  <button class="btn-order-action" style="font-size: 0.75rem; padding: 0.35rem 0.65rem; color: var(--color-danger);" onclick="window.FeatherApp.deleteAddress('${a.id}')">
                    Delete
                  </button>
                ` : ''}
              </div>
            </div>
          `).join('')}
        </div>
      </div>
    `;
  }

  // TAB 3: Feather Coins & Wallet
  function renderAccountCoinsTab() {
    const u = state.user;
    const coins = u.coins || 450;

    return `
      <div>
        <!-- Wallet Hero Card -->
        <div class="wallet-hero-card">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 0.65rem;">
              <span style="font-size: 1.6rem;">🪙</span>
              <div>
                <div style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; opacity: 0.85;">Feather Rewards Wallet</div>
                <strong style="font-size: 1.1rem; color: #fff;">VIP Loyalty Balance</strong>
              </div>
            </div>
            <span class="account-badge vip" style="background: rgba(255,215,0,0.25);">Active</span>
          </div>

          <div class="wallet-balance-row">
            <div>
              <div class="wallet-coins-val">${coins} Coins</div>
              <div style="font-size: 0.95rem; opacity: 0.9;">Total Cash Value: <strong>₹${coins}</strong></div>
            </div>
            <div style="text-align: right; font-size: 0.82rem; opacity: 0.85;">
              1 Feather Coin = ₹1.00 Discount<br>
              Auto-redeemable at Checkout
            </div>
          </div>
        </div>

        <!-- How to Earn More -->
        <h4 style="font-size: 1.05rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.85rem;">How to Earn More Coins</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.85rem; margin-bottom: 1.75rem;">
          <div style="background: var(--color-bg); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
            <div style="font-size: 1.3rem; margin-bottom: 0.35rem;">🛍️</div>
            <strong style="font-size: 0.88rem; color: var(--color-primary); display: block;">Shop Apparel</strong>
            <span style="font-size: 0.78rem; color: var(--color-text-muted);">Get 5% back in Coins on every streetwear drop purchase.</span>
          </div>
          <div style="background: var(--color-bg); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
            <div style="font-size: 1.3rem; margin-bottom: 0.35rem;">⭐</div>
            <strong style="font-size: 0.88rem; color: var(--color-primary); display: block;">Write Reviews</strong>
            <span style="font-size: 0.78rem; color: var(--color-text-muted);">Earn +50 Coins for every verified product feedback review.</span>
          </div>
          <div style="background: var(--color-bg); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
            <div style="font-size: 1.3rem; margin-bottom: 0.35rem;">🎁</div>
            <strong style="font-size: 0.88rem; color: var(--color-primary); display: block;">Refer Friends</strong>
            <span style="font-size: 0.78rem; color: var(--color-text-muted);">Give ₹150, get 100 Coins when your friends order their first fit.</span>
          </div>
        </div>

        <!-- Recent Ledger -->
        <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.65rem;">Recent Coin Activity</h4>
        <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.85rem;">
          <div style="display: flex; justify-content: space-between; padding: 0.65rem 0.85rem; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            <div><strong>Welcome VIP Bonus</strong><div style="font-size: 0.75rem; color: var(--color-text-muted);">Account registration gift</div></div>
            <strong style="color: var(--color-success);">+100 Coins</strong>
          </div>
          <div style="display: flex; justify-content: space-between; padding: 0.65rem 0.85rem; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            <div><strong>Order FF-89214 Cashback</strong><div style="font-size: 0.75rem; color: var(--color-text-muted);">Completed purchase reward</div></div>
            <strong style="color: var(--color-success);">+200 Coins</strong>
          </div>
          <div style="display: flex; justify-content: space-between; padding: 0.65rem 0.85rem; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            <div><strong>Verified Buyer Review</strong><div style="font-size: 0.75rem; color: var(--color-text-muted);">Review on Ronin Graphic Tee</div></div>
            <strong style="color: var(--color-success);">+150 Coins</strong>
          </div>
        </div>
      </div>
    `;
  }

  // TAB 4: Size & Fit Preference
  function renderAccountFitTab() {
    const u = state.user;
    const prefSize = u.preferredSize || "L";
    const prefFit = u.preferredFit || "Oversized Streetwear";

    return `
      <div>
        <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--color-primary); margin-bottom: 0.25rem;">My Fit & Size Profile</h3>
        <p style="font-size: 0.82rem; color: var(--color-text-muted); margin-bottom: 1.5rem;">
          Save your size once — product pages will automatically pre-select your perfect fit.
        </p>

        <!-- Preferred Size Picker -->
        <div style="margin-bottom: 1.5rem;">
          <label style="font-size: 0.88rem; font-weight: 700; color: var(--color-primary); display: block; margin-bottom: 0.5rem;">
            Preferred T-Shirt Size
          </label>
          <div class="size-options">
            ${["XS", "S", "M", "L", "XL", "XXL"].map((sz) => `
              <button type="button" class="size-chip-btn ${prefSize === sz ? 'active' : ''}" onclick="window.FeatherApp.setPreferredFit('${sz}', '${prefFit}')">
                ${sz}
              </button>
            `).join('')}
          </div>
        </div>

        <!-- Preferred Fit Style -->
        <div style="margin-bottom: 1.75rem;">
          <label style="font-size: 0.88rem; font-weight: 700; color: var(--color-primary); display: block; margin-bottom: 0.5rem;">
            Preferred Fit Style
          </label>
          <div class="fit-options-group">
            <button type="button" class="fit-card-btn ${prefFit.includes('Oversized') ? 'active' : ''}" onclick="window.FeatherApp.setPreferredFit('${prefSize}', 'Oversized Streetwear')">
              <strong style="font-size: 0.95rem; color: var(--color-primary); display: block; margin-bottom: 0.25rem;">👕 Oversized Streetwear</strong>
              <span style="font-size: 0.8rem; color: var(--color-text-muted);">Boxy cut, dropped shoulders, relaxed streetwear aesthetic. (Recommended)</span>
            </button>
            <button type="button" class="fit-card-btn ${prefFit.includes('Regular') ? 'active' : ''}" onclick="window.FeatherApp.setPreferredFit('${prefSize}', 'Regular Tailored Fit')">
              <strong style="font-size: 0.95rem; color: var(--color-primary); display: block; margin-bottom: 0.25rem;">👔 Regular Tailored Fit</strong>
              <span style="font-size: 0.8rem; color: var(--color-text-muted);">Classic straight fit hugging the chest and arms naturally.</span>
            </button>
          </div>
        </div>

        <div style="background: var(--color-bg); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border); font-size: 0.85rem; color: var(--color-text-main);">
          ✓ <strong>Smart Fit Active:</strong> Size <strong>${prefSize} (${prefFit})</strong> is set as your default.
        </div>
      </div>
    `;
  }

  // TAB 5: Settings & WhatsApp Alerts
  function renderAccountSettingsTab() {
    const u = state.user;

    return `
      <div>
        <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--color-primary); margin-bottom: 0.25rem;">Notifications & Account Preferences</h3>
        <p style="font-size: 0.82rem; color: var(--color-text-muted); margin-bottom: 1.5rem;">
          Manage real-time delivery alerts and login security.
        </p>

        <!-- WhatsApp Toggle Card -->
        <div class="whatsapp-pref-card">
          <div style="display: flex; gap: 0.85rem; align-items: center;">
            <div style="width: 44px; height: 44px; border-radius: 50%; background: #22C55E; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
              💬
            </div>
            <div>
              <strong style="font-size: 0.95rem; color: #14532D; display: block;">WhatsApp Order & Tracking Alerts</strong>
              <div style="font-size: 0.8rem; color: #166534;">
                Receive instant OTP, dispatch tracking link & out-for-delivery WhatsApp alerts to <strong>+91 ${u.phone || '98765 43210'}</strong>.
              </div>
            </div>
          </div>

          <label class="toggle-switch">
            <input type="checkbox" ${u.whatsappUpdates !== false ? 'checked' : ''} onchange="window.FeatherApp.toggleWhatsAppAlerts(this.checked)" />
            <span class="toggle-slider"></span>
          </label>
        </div>

        <!-- Phone Verification Box -->
        <div style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.5rem;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
              <span style="font-size: 0.78rem; text-transform: uppercase; color: var(--color-text-muted); font-weight: 700;">Verified Mobile</span>
              <div style="font-size: 1rem; font-weight: 800; font-family: monospace; color: var(--color-primary); margin-top: 0.2rem;">
                +91 ${u.phone || '98765 43210'}
              </div>
            </div>
            <span class="security-badge" style="background: rgba(34, 197, 94, 0.12); color: #15803D; border-color: rgba(34, 197, 94, 0.3);">
              ✓ OTP Verified
            </span>
          </div>
        </div>

        <button class="btn-order-action" style="color: var(--color-danger); border-color: var(--color-border); padding: 0.65rem 1.25rem;" onclick="window.FeatherApp.handleLogout()">
          Sign Out of Feather Fits
        </button>
      </div>
    `;
  }

  // Interactive Account Actions
  function trackOrderInsideAccount(query) {
    if (!query) return;
    state.activeTrackingOrderNum = query.trim();
    renderProfileView();
  }

  function toggleAddAddressForm() {
    state.showAddAddressForm = !state.showAddAddressForm;
    renderProfileView();
  }

  function handleAddAddress(event) {
    if (event) event.preventDefault();
    const tag = document.getElementById("addr-tag")?.value || "Home";
    const name = document.getElementById("addr-name")?.value || `${state.user.firstName} ${state.user.lastName}`;
    const phone = document.getElementById("addr-phone")?.value || state.user.phone;
    const line1 = document.getElementById("addr-line")?.value || "";
    const city = document.getElementById("addr-city")?.value || "";
    const stateName = document.getElementById("addr-state")?.value || "";
    const postalCode = document.getElementById("addr-zip")?.value || "";

    if (!line1 || !city || !postalCode) {
      showToast("Please fill all required address fields.", "warning");
      return;
    }

    const newAddr = {
      id: `addr_${Date.now()}`,
      tag,
      isDefault: false,
      name,
      phone: phone.startsWith("+91") ? phone : `+91 ${phone}`,
      line1,
      city,
      state: stateName,
      postalCode,
    };

    if (!state.user.addresses) state.user.addresses = [];
    state.user.addresses.push(newAddr);
    state.showAddAddressForm = false;
    saveState();
    showToast(`New address "${tag}" saved successfully!`);
    renderProfileView();
  }

  function setDefaultAddress(addrId) {
    if (!state.user?.addresses) return;
    state.user.addresses.forEach((a) => {
      a.isDefault = a.id === addrId;
    });
    saveState();
    showToast("Default delivery address updated!");
    renderProfileView();
  }

  function deleteAddress(addrId) {
    if (!state.user?.addresses) return;
    state.user.addresses = state.user.addresses.filter((a) => a.id !== addrId);
    saveState();
    showToast("Address removed.");
    renderProfileView();
  }

  function setPreferredFit(size, fit) {
    if (!state.user) return;
    state.user.preferredSize = size;
    state.user.preferredFit = fit;
    state.selectedVariant.size = size;
    saveState();
    showToast(`Preferred fit saved: Size ${size} (${fit})`);
    renderProfileView();
  }

  function toggleWhatsAppAlerts(enabled) {
    if (!state.user) return;
    state.user.whatsappUpdates = enabled;
    saveState();
    showToast(enabled ? "WhatsApp delivery alerts enabled! ✓" : "WhatsApp alerts turned off.");
  }

  // Invoice Download Generator
  function showInvoice(orderNumber) {
    const order = state.orders.find((o) => o.orderNumber.toUpperCase() === orderNumber.toUpperCase()) || state.orders[0];
    if (!order) return;

    let invoiceModal = document.getElementById("invoice-modal");
    if (!invoiceModal) {
      invoiceModal = document.createElement("div");
      invoiceModal.id = "invoice-modal";
      invoiceModal.className = "modal-overlay";
      document.body.appendChild(invoiceModal);
    }

    invoiceModal.innerHTML = `
      <div class="modal-container" style="max-width: 620px; padding: 2rem; background: #fff; color: #1F2937;">
        <button class="modal-close-btn" onclick="document.getElementById('invoice-modal').classList.remove('active')">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>

        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #E5E7EB; padding-bottom: 1.25rem; margin-bottom: 1.5rem;">
          <div>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: #2D0B34; margin-bottom: 0.2rem;">FEATHER FITS APPAREL</h2>
            <div style="font-size: 0.8rem; color: #6B7280;">GSTIN: 33AAACF4921M1Z5 • Registered Tax Invoice</div>
            <div style="font-size: 0.8rem; color: #6B7280;">Logistics Partner: Blue Dart Express</div>
          </div>
          <div style="text-align: right;">
            <span class="security-badge" style="background:#ECFDF5; color:#065F46; border:1px solid #A7F3D0;">PAID IN FULL</span>
            <div style="font-size: 0.85rem; font-weight: 700; color: #111827; margin-top: 0.35rem;">INV-${order.orderNumber}</div>
            <div style="font-size: 0.78rem; color: #6B7280;">${new Date(order.createdAt).toLocaleDateString()}</div>
          </div>
        </div>

        <div style="margin-bottom: 1.5rem; font-size: 0.85rem;">
          <div style="color: #6B7280; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Billed & Shipped To:</div>
          <strong style="color: #111827;">${order.shippingAddress?.firstName || state.user?.firstName || 'Arun'} ${order.shippingAddress?.lastName || state.user?.lastName || 'Kumar'}</strong><br>
          ${order.shippingAddress?.line1 || '42, Gandhi Road, T. Nagar'}<br>
          ${order.shippingAddress?.city || 'Chennai'}, ${order.shippingAddress?.state || 'Tamil Nadu'} — ${order.shippingAddress?.postalCode || '600017'}<br>
          Mobile: +91 ${order.customerPhone || state.user?.phone || '98765 43210'}
        </div>

        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; margin-bottom: 1.5rem;">
          <thead>
            <tr style="border-bottom: 1.5px solid #E5E7EB; text-align: left; color: #4B5563;">
              <th style="padding: 0.5rem 0;">Item Description</th>
              <th style="padding: 0.5rem; text-align: center;">Qty</th>
              <th style="padding: 0.5rem; text-align: right;">Price</th>
              <th style="padding: 0.5rem 0; text-align: right;">Total</th>
            </tr>
          </thead>
          <tbody>
            ${order.items.map((it) => `
              <tr style="border-bottom: 1px solid #F3F4F6;">
                <td style="padding: 0.65rem 0;">
                  <strong>${it.name}</strong><br>
                  <span style="font-size: 0.75rem; color: #6B7280;">Variant: ${it.size} / ${it.colorName || it.color || 'Standard'}</span>
                </td>
                <td style="padding: 0.65rem; text-align: center;">${it.quantity}</td>
                <td style="padding: 0.65rem; text-align: right;">₹${Math.round(it.price || 1299).toLocaleString('en-IN')}</td>
                <td style="padding: 0.65rem 0; text-align: right; font-weight: 700;">₹${Math.round((it.price || 1299) * it.quantity).toLocaleString('en-IN')}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>

        <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
          <div style="width: 240px; font-size: 0.85rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;"><span>Subtotal:</span><span>₹${Math.round(order.subtotal || 1299).toLocaleString('en-IN')}</span></div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;"><span>GST (5%):</span><span>₹${Math.round(order.tax || 65).toLocaleString('en-IN')}</span></div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;"><span>Express Shipping:</span><span style="color: #059669; font-weight: 700;">FREE</span></div>
            <div style="display: flex; justify-content: space-between; border-top: 1.5px solid #E5E7EB; padding-top: 0.5rem; font-size: 1.05rem; font-weight: 800; color: #2D0B34;">
              <span>Total Paid:</span><span>₹${Math.round(order.total || 1364).toLocaleString('en-IN')}</span>
            </div>
          </div>
        </div>

        <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
          <button class="btn-primary" onclick="window.print();">🖨️ Print / Save PDF</button>
          <button class="btn-order-action" onclick="document.getElementById('invoice-modal').classList.remove('active')">Close</button>
        </div>
      </div>
    `;

    invoiceModal.classList.add("active");
  }

  // 30-Day Easy Exchange / Return Request
  function requestExchange(orderNumber) {
    showToast(`Exchange ticket #EX-${Math.floor(10000 + Math.random() * 90000)} initiated! Blue Dart courier will pick up item for free replacement.`);
  }

  function handleLogout() {
    state.user = null;
    saveState();
    showToast("You have been signed out.");
    renderAuthView();
  }

  // -------------------------------------------------------------
  // INITIALIZATION & EVENT LISTENERS
  // -------------------------------------------------------------
  function init() {
    // Check URL search params for department or subcategory (e.g. ?department=men&subcategory=men-shirts)
    const urlParams = new URLSearchParams(window.location.search);
    const deptQuery = urlParams.get("department");
    const subcatQuery = urlParams.get("subcategory") || urlParams.get("category");

    if (deptQuery && ["all", "men", "women", "kids"].includes(deptQuery)) {
      state.currentDepartment = deptQuery;
    }

    if (subcatQuery) {
      if (["anime-tshirts", "movie-tshirts", "plain-tshirts"].includes(subcatQuery)) {
        state.currentDepartment = "men";
        state.currentSubcategory = "men-tshirts";
      } else if (["mens-fashion"].includes(subcatQuery)) {
        state.currentDepartment = "men";
      } else if (["womens-fashion"].includes(subcatQuery)) {
        state.currentDepartment = "women";
      } else if (["kids-fashion"].includes(subcatQuery)) {
        state.currentDepartment = "kids";
      } else {
        state.currentSubcategory = subcatQuery;
      }
    }

    renderHeroBanner(state.currentDepartment);
    renderDepartmentPosters(state.currentDepartment);
    renderNewArrivalsCarousel();
    renderBreadcrumbs();
    renderCategoryTabs();
    renderProducts();
    updateBadges();

    // Sync active nav pills on load
    document.querySelectorAll(".nav-link[data-dept], .mobile-nav-link[data-dept]").forEach((link) => {
      if (link.getAttribute("data-dept") === state.currentDepartment) {
        link.classList.add("active");
      } else {
        link.classList.remove("active");
      }
    });

    // Attach search trigger in Tracking Modal
    const trackSearchBtn = document.getElementById("track-search-btn");
    const trackSearchInput = document.getElementById("tracking-order-input");
    if (trackSearchBtn && trackSearchInput) {
      trackSearchBtn.addEventListener("click", () => {
        executeTrackingSearch(trackSearchInput.value);
      });
      trackSearchInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") executeTrackingSearch(trackSearchInput.value);
      });
    }

    // Close buttons on overlays
    document.querySelectorAll(".modal-close-btn").forEach((btn) => {
      btn.addEventListener("click", closeModals);
    });

    document.querySelectorAll(".modal-overlay").forEach((overlay) => {
      overlay.addEventListener("click", (e) => {
        if (e.target === overlay) closeModals();
      });
    });

    // Close Drawers when clicking overlays
    const cartOverlay = document.getElementById("cart-drawer-overlay");
    if (cartOverlay) {
      cartOverlay.addEventListener("click", (e) => {
        if (e.target === cartOverlay) closeCartDrawer();
      });
    }

    const wishlistOverlay = document.getElementById("wishlist-drawer-overlay");
    if (wishlistOverlay) {
      wishlistOverlay.addEventListener("click", (e) => {
        if (e.target === wishlistOverlay) closeWishlistDrawer();
      });
    }

    const mobileOverlay = document.getElementById("mobile-menu-overlay");
    if (mobileOverlay) {
      mobileOverlay.addEventListener("click", (e) => {
        if (e.target === mobileOverlay) toggleMobileMenu();
      });
    }
  }

  function toggleMobileMenu() {
    const overlay = document.getElementById("mobile-menu-overlay");
    if (!overlay) return;
    overlay.classList.toggle("active");
  }

  // -------------------------------------------------------------
  // SIZE GUIDE & MEASUREMENTS MODAL
  // -------------------------------------------------------------
  function openSizeGuideModal(unit = "inches") {
    state.sizeGuideUnit = unit;
    renderSizeGuideModal();
    const modal = document.getElementById("size-guide-modal");
    if (modal) modal.classList.add("active");
  }

  function setSizeGuideUnit(unit) {
    state.sizeGuideUnit = unit;
    renderSizeGuideModal();
  }

  function renderSizeGuideModal() {
    const content = document.getElementById("size-guide-content");
    if (!content) return;

    const data = window.FEATHER_SIZE_GUIDE ? window.FEATHER_SIZE_GUIDE[state.sizeGuideUnit] : [];
    const isCm = state.sizeGuideUnit === "cm";

    content.innerHTML = `
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
        <div>
          <span style="font-size: 0.8rem; font-weight: 700; color: var(--color-secondary); text-transform: uppercase;">Confidence Fits You</span>
          <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--color-primary);">Apparel Size & Measurement Chart</h2>
        </div>
        <div class="unit-toggle-group">
          <button class="unit-btn ${!isCm ? "active" : ""}" onclick="window.FeatherApp.setSizeGuideUnit('inches')">Inches</button>
          <button class="unit-btn ${isCm ? "active" : ""}" onclick="window.FeatherApp.setSizeGuideUnit('cm')">Centimeters (CM)</button>
        </div>
      </div>
      <p style="font-size: 0.88rem; color: var(--color-text-muted); margin-bottom: 1rem;">
        All measurements reflect standard tailored apparel sizing. For an oversized streetwear aesthetic, we recommend ordering one size up.
      </p>
      <div style="overflow-x: auto;">
        <table class="size-chart-table">
          <thead>
            <tr>
              <th>Size</th>
              <th>Chest Circumference (${isCm ? "cm" : "in"})</th>
              <th>Body Length (${isCm ? "cm" : "in"})</th>
              <th>Shoulder Width (${isCm ? "cm" : "in"})</th>
            </tr>
          </thead>
          <tbody>
            ${data.map(row => `
              <tr>
                <td><strong>${row.size}</strong></td>
                <td>${row.chest}</td>
                <td>${row.length}</td>
                <td>${row.shoulder}</td>
              </tr>
            `).join("")}
          </tbody>
        </table>
      </div>
      <div style="background: var(--color-bg); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border); font-size: 0.82rem; color: var(--color-text-muted); margin-top: 1rem;">
        💡 <strong>Fit Advice:</strong> All Feather Fits cotton tees are pre-shrunk 240 GSM. Hand wash or gentle machine wash in cold water for optimal graphic print preservation.
      </div>
    `;
  }

  function handleSearchInput(query) {
    state.searchQuery = query.trim();
    renderProducts();
  }

  function handleSortChange(sortBy) {
    state.sortBy = sortBy;
    renderProducts();
  }

  function clearSearch() {
    state.searchQuery = "";
    state.sortBy = "featured";
    state.currentDepartment = "all";
    state.currentSubcategory = "all";
    state.currentCategory = "all";
    const input = document.getElementById("catalog-search-input");
    if (input) input.value = "";
    const sortSelect = document.getElementById("catalog-sort-select");
    if (sortSelect) sortSelect.value = "featured";
    renderHeroBanner("all");
    renderDepartmentPosters("all");
    renderBreadcrumbs();
    renderCategoryTabs();
    renderProducts();
  }

  // Expose Global App API
  window.FeatherApp = {
    setDepartment,
    setNewArrivalsDept,
    renderNewArrivalsCarousel,
    scrollNewArrivals,
    setSubcategory,
    setCategory,
    handleSearchInput,
    handleSortChange,
    clearSearch,
    toggleMobileMenu,
    openSizeGuideModal,
    setSizeGuideUnit,
    applyPromoCode,
    removePromoCode,
    openProductModal,
    setModalColor,
    setModalSize,
    setModalImage,
    addModalToCart,
    addToCart,
    updateCartQuantity,
    removeCartItem,
    openCartDrawer,
    closeCartDrawer,
    toggleWishlist,
    openWishlistDrawer,
    closeWishlistDrawer,
    openCheckoutModal,
    sendCheckoutOtp,
    fillDemoOtp,
    fillTestCard,
    handleProcessPayment,
    trackOrder,
    openTrackingModal,
    openAccountModal,
    setAccountTab,
    trackOrderInsideAccount,
    toggleAddAddressForm,
    handleAddAddress,
    setDefaultAddress,
    deleteAddress,
    setPreferredFit,
    toggleWhatsAppAlerts,
    showInvoice,
    requestExchange,
    handleAuthSubmit,
    handleLogout,
    closeModals,
  };

  // Run on DOM ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
