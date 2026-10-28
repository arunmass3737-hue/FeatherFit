/**
 * FEATHER FITS — PRODUCT DETAIL PAGE (PDP) CONTROLLER
 * Reads URL query param `?id=...` and renders complete product experience
 */

(function () {
  "use strict";

  const urlParams = new URLSearchParams(window.location.search);
  const productId = urlParams.get("id") || "ff-anime-01";

  let activeProduct = null;
  let selectedColor = "";
  let selectedColorHex = "";
  let selectedSize = "M";
  let selectedImage = "";
  let currentQty = 1;

  function initPdp() {
    let products = window.FEATHER_PRODUCTS || [];
    try {
      const adminProds = JSON.parse(localStorage.getItem("ff_admin_products_v1") || "null");
      if (Array.isArray(adminProds) && adminProds.length > 0) {
        products = adminProds;
      }
    } catch(e) {}
    activeProduct = products.find((p) => p.id === productId) || products[0];

    if (!activeProduct) return;

    // Set page title
    document.title = `${activeProduct.name} — Feather Fits`;

    // Set default variant state (synced with user's saved fit profile)
    const savedUser = JSON.parse(localStorage.getItem("ff_user_v1") || "null");
    const userPrefSize = savedUser?.preferredSize || "L";
    selectedColor = activeProduct.colors[0].name;
    selectedColorHex = activeProduct.colors[0].hex;
    selectedSize = activeProduct.sizes.includes(userPrefSize) ? userPrefSize : (activeProduct.sizes.includes("L") ? "L" : activeProduct.sizes[0]);
    selectedImage = activeProduct.images[0];
    currentQty = 1;

    // Update Breadcrumbs
    const catLink = document.getElementById("pdp-category-link");
    const titleCrumb = document.getElementById("pdp-title-crumb");
    if (catLink) {
      catLink.textContent = activeProduct.categoryName;
      catLink.href = `index.php?department=${activeProduct.department || 'men'}&subcategory=${activeProduct.category}#catalog-section`;
      catLink.onclick = (e) => {
        if (window.FeatherApp) {
          window.FeatherApp.setSubcategory(activeProduct.category, activeProduct.department || 'men');
        }
      };
    }
    if (titleCrumb) titleCrumb.textContent = activeProduct.name;

    renderPdpMain();
    renderRelatedProducts();
    initReviews();
  }

  function renderPdpMain() {
    const container = document.getElementById("pdp-container");
    if (!container || !activeProduct) return;

    const p = activeProduct;
    const isWishlisted = (window.FeatherApp?.state?.wishlist || []).includes(p.id) ||
      JSON.parse(localStorage.getItem("ff_wishlist_v1") || "[]").includes(p.id);

    const stockKey = `${selectedColor}-${selectedSize}`;
    const stockQty = p.stock ? p.stock[stockKey] ?? 10 : 10;
    const isOutOfStock = stockQty <= 0;
    const discount = Math.round(((p.originalPrice - p.basePrice) / p.originalPrice) * 100);

    container.innerHTML = `
      <!-- Left: Interactive Gallery -->
      <div class="pdp-gallery-column">
        <div class="pdp-main-image-wrap">
          <img id="pdp-active-img" src="${selectedImage}" alt="${p.name}" class="pdp-main-img" />
          ${p.badge ? `<span class="card-badge" style="top:18px; left:18px; font-size:0.8rem; padding:0.4rem 0.85rem;">${p.badge}</span>` : ""}
          <button class="card-wishlist-btn ${isWishlisted ? "active" : ""}" 
                  style="top:18px; right:18px; width:44px; height:44px;"
                  onclick="window.PdpController.toggleWishlist('${p.id}')"
                  aria-label="Wishlist">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="${isWishlisted ? "currentColor" : "none"}" stroke="currentColor" stroke-width="2">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
          </button>
        </div>

        <div class="pdp-thumbnails-strip">
          ${p.images
            .map(
              (img, idx) => `
              <div class="pdp-thumb-item ${selectedImage === img ? "active" : ""}" 
                   onclick="window.PdpController.setActiveImage('${img}')">
                <img src="${img}" alt="${p.name} view ${idx + 1}" />
              </div>
            `
            )
            .join("")}
        </div>
      </div>

      <!-- Right: Product Information & Commerce Actions -->
      <div class="pdp-info-column">
        <div class="pdp-meta-top">
          <span class="card-category" style="font-size:0.85rem;">${p.categoryName}</span>
          <div class="card-rating">
            <span style="color:#F59E0B;">★★★★★</span>
            <strong style="color:var(--color-primary);">${p.rating.toFixed(1)}</strong>
            <span class="reviews">(${p.reviewsCount} verified customer reviews)</span>
          </div>
        </div>

        <h1 class="pdp-title">${p.name}</h1>

        <div class="pdp-price-row">
          <span class="pdp-current-price">₹${p.basePrice.toLocaleString('en-IN')}</span>
          ${p.originalPrice ? `<span class="pdp-orig-price">₹${p.originalPrice.toLocaleString('en-IN')}</span>` : ""}
          <span class="pdp-discount-pill">${discount}% OFF</span>
        </div>

        <p class="pdp-desc-text">${p.description}</p>

        <!-- Highlights Badges -->
        <div class="pdp-fabric-pills">
          <span class="fabric-pill">🌿 100% Combed Heavy Cotton</span>
          <span class="fabric-pill">⚖️ 240 GSM Fabric</span>
          <span class="fabric-pill">✨ Pre-Shrunk & Thermal Washed</span>
        </div>

        <div class="pdp-divider"></div>

        <!-- Color Selection -->
        <div class="selector-block">
          <div class="selector-label">
            <span>Selected Shade: <strong>${selectedColor}</strong></span>
          </div>
          <div class="color-options" style="flex-wrap: wrap;">
            ${p.colors
              .map(
                (c) => `
                <button type="button" 
                        class="color-option-btn ${selectedColor === c.name ? "active" : ""}"
                        onclick="window.PdpController.setColor('${c.name}', '${c.hex}')">
                  <span class="dot" style="background-color: ${c.hex};"></span>
                  <span>${c.name}</span>
                </button>
              `
              )
              .join("")}
          </div>
        </div>

        <!-- Size Selection -->
        <div class="selector-block">
          <div class="selector-label">
            <span>Select Size: <strong>${selectedSize}</strong></span>
            <button type="button" onclick="window.FeatherApp.openSizeGuideModal('inches')" class="pdp-size-chart-link">
              📐 Size Guide & Chart
            </button>
          </div>
          <div class="size-options">
            ${["XS", "S", "M", "L", "XL", "XXL"]
              .map((size) => {
                const isAvailable = p.sizes.includes(size);
                const sKey = `${selectedColor}-${size}`;
                const curStock = p.stock ? p.stock[sKey] ?? (isAvailable ? 8 : 0) : 8;
                const disabled = !isAvailable || curStock <= 0;
                return `
                  <button type="button" 
                          class="size-chip-btn ${selectedSize === size ? "active" : ""}"
                          ${disabled ? "disabled" : ""}
                          onclick="window.PdpController.setSize('${size}')">
                    ${size}
                  </button>
                `;
              })
              .join("")}
          </div>
        </div>

        <!-- Stock Indicator -->
        <div class="stock-indicator ${isOutOfStock ? "out-of-stock" : "in-stock"}" style="margin-bottom: 1.5rem;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            ${
              isOutOfStock
                ? '<circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line>'
                : '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>'
            }
          </svg>
          <span>${isOutOfStock ? "Out of Stock in this variant" : `Ready to Ship (${stockQty} units remaining)`}</span>
        </div>

        <!-- Quantity & Add to Cart -->
        <div class="pdp-actions-row">
          <div class="qty-counter pdp-qty-selector">
            <button class="qty-btn" type="button" onclick="window.PdpController.updateQty(-1)" aria-label="Decrease quantity">-</button>
            <span class="qty-value" id="pdp-qty-val">${currentQty}</span>
            <button class="qty-btn" type="button" onclick="window.PdpController.updateQty(1)" aria-label="Increase quantity">+</button>
          </div>

          <button type="button" 
                  class="btn-add-cart-lg" 
                  ${isOutOfStock ? "disabled" : ""} 
                  onclick="window.PdpController.handleAddToCart()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span>${isOutOfStock ? "Sold Out" : "Add to Shopping Bag"}</span>
          </button>
        </div>

        <!-- Buy Now Instant Button -->
        <button type="button" 
                class="btn-checkout btn-buy-now-instant" 
                ${isOutOfStock ? "disabled" : ""} 
                onclick="window.PdpController.handleBuyNow()">
          <span>⚡ Buy Now with Instant Checkout</span>
        </button>

        <!-- Trust Mini-Card -->
        <div class="pdp-guarantee-card">
          <div class="guarantee-row">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-secondary)" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
            <span><strong>Complimentary Express Delivery</strong> on orders ₹999+</span>
          </div>
          <div class="guarantee-row">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-secondary)" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span><strong>30-Day Hassle-Free Returns</strong> & instant size exchanges</span>
          </div>
          <div class="guarantee-row">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-secondary)" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            <span><strong>100% Verified Secure</strong> Cash & UPI on Delivery</span>
          </div>
        </div>

      </div>
    `;
  }

  function renderRelatedProducts() {
    const grid = document.getElementById("related-products-grid");
    if (!grid || !activeProduct) return;

    const products = window.FEATHER_PRODUCTS || [];
    const related = products
      .filter((p) => p.category === activeProduct.category && p.id !== activeProduct.id)
      .slice(0, 4);

    if (related.length === 0) {
      grid.innerHTML = products
        .slice(0, 4)
        .map((p) => renderCard(p))
        .join("");
    } else {
      grid.innerHTML = related.map((p) => renderCard(p)).join("");
    }
  }

  function renderCard(product) {
    const isWishlisted = (window.FeatherApp?.state?.wishlist || []).includes(product.id) ||
      JSON.parse(localStorage.getItem("ff_wishlist_v1") || "[]").includes(product.id);

    return `
      <article class="product-card" data-product-id="${product.id}">
        <a href="product.php?id=${product.id}" class="card-img-wrap">
          <img src="${product.images[0]}" alt="${product.name}" class="card-img" loading="lazy" />
          ${product.badge ? `<span class="card-badge">${product.badge}</span>` : ""}
        </a>
        <div class="card-body">
          <span class="card-category">${product.categoryName}</span>
          <a href="product.php?id=${product.id}" class="card-title">${product.name}</a>
          <div class="card-rating">
            <span>★★★★★</span>
            <span>${product.rating.toFixed(1)}</span>
            <span class="reviews">(${product.reviewsCount})</span>
          </div>
          <div class="card-footer">
            <div class="card-price-wrap">
              <span class="card-price">₹${product.basePrice.toLocaleString('en-IN')}</span>
            </div>
            <a href="product.php?id=${product.id}" class="card-quick-add">
              <span>View Fit</span>
            </a>
          </div>
        </div>
      </article>
    `;
  }

  function setActiveImage(img) {
    selectedImage = img;
    const imgEl = document.getElementById("pdp-active-img");
    if (imgEl) imgEl.src = img;
    document.querySelectorAll(".pdp-thumb-item").forEach((thumb) => {
      if (thumb.querySelector("img").src.includes(img)) {
        thumb.classList.add("active");
      } else {
        thumb.classList.remove("active");
      }
    });
  }

  function setColor(colorName, colorHex) {
    selectedColor = colorName;
    selectedColorHex = colorHex;
    renderPdpMain();
  }

  function setSize(size) {
    selectedSize = size;
    renderPdpMain();
  }

  function updateQty(delta) {
    currentQty = Math.max(1, currentQty + delta);
    const valEl = document.getElementById("pdp-qty-val");
    if (valEl) valEl.textContent = currentQty;
  }

  function handleAddToCart() {
    if (!activeProduct) return;
    window.FeatherApp.addToCart(activeProduct.id, selectedColor, selectedSize, currentQty);
  }

  function handleBuyNow() {
    if (!activeProduct) return;
    window.FeatherApp.addToCart(activeProduct.id, selectedColor, selectedSize, currentQty);
    window.FeatherApp.openCheckoutModal();
  }

  function toggleWishlist(productId) {
    window.FeatherApp.toggleWishlist(productId);
    renderPdpMain();
  }

  // -------------------------------------------------------------
  // VERIFIED BUYER REVIEWS CONTROLLER (WRITE, LIKE, COMMENT)
  // -------------------------------------------------------------
  let productReviews = [];
  let openCommentsState = {};

  function getSeedReviews(product) {
    return [
      {
        id: "rev-1",
        author: "Marcus K.",
        verified: true,
        size: "L",
        rating: 5,
        headline: "Ridiculously high fabric quality & drape!",
        text: "The fabric weight on this tee is insane! It feels like a ₹10,000 designer streetwear piece. Print details are super crisp and retained complete sharpness after 3 machine washes.",
        date: "2 days ago",
        likes: 18,
        userLiked: false,
        comments: [
          {
            id: "com-1",
            author: "Feather Fits Team",
            isStore: true,
            date: "1 day ago",
            text: "Thank you Marcus! We use pre-shrunk 240 GSM combed cotton specifically to guarantee that heavyweight drape."
          }
        ]
      },
      {
        id: "rev-2",
        author: "Priya S.",
        verified: true,
        size: "S",
        rating: 5,
        headline: "Confidence Fits You is 100% accurate",
        text: "Feather Fits really means 'Confidence Fits You'. The cut around the chest and arms is flattering without feeling suffocating. Pairs beautifully with high-waist cargo pants.",
        date: "1 week ago",
        likes: 12,
        userLiked: false,
        comments: [
          {
            id: "com-2",
            author: "Ananya M.",
            isStore: false,
            date: "4 days ago",
            text: "Did you take your usual size or size up?"
          },
          {
            id: "com-3",
            author: "Priya S.",
            isStore: false,
            date: "3 days ago",
            text: "I took my usual Size S! It already has a relaxed drop-shoulder cut."
          }
        ]
      },
      {
        id: "rev-3",
        author: "Jordan T.",
        verified: true,
        size: "XL",
        rating: 5,
        headline: "Luxury packaging and compliments all day",
        text: "Fast delivery, came in a luxury plum custom box. Wore it to an anime convention and got so many compliments! 10/10 purchase.",
        date: "2 weeks ago",
        likes: 9,
        userLiked: false,
        comments: []
      }
    ];
  }

  function initReviews() {
    if (!activeProduct) return;
    const storageKey = `ff_reviews_${activeProduct.id}`;
    const saved = localStorage.getItem(storageKey);
    if (saved) {
      try {
        productReviews = JSON.parse(saved);
      } catch (e) {
        productReviews = getSeedReviews(activeProduct);
      }
    } else {
      productReviews = getSeedReviews(activeProduct);
      localStorage.setItem(storageKey, JSON.stringify(productReviews));
    }
    renderReviews();
  }

  function saveReviews() {
    if (!activeProduct) return;
    const storageKey = `ff_reviews_${activeProduct.id}`;
    localStorage.setItem(storageKey, JSON.stringify(productReviews));
  }

  function renderReviews() {
    const grid = document.getElementById("pdp-reviews-grid");
    if (!grid) return;

    // Update count and average rating
    const avgRatingEl = document.getElementById("pdp-reviews-avg-rating");
    const countBadgeEl = document.getElementById("pdp-reviews-count-badge");
    if (productReviews.length > 0) {
      const avg = productReviews.reduce((sum, r) => sum + r.rating, 0) / productReviews.length;
      if (avgRatingEl) avgRatingEl.textContent = `${avg.toFixed(1)} out of 5`;
      if (countBadgeEl) countBadgeEl.textContent = `(Based on ${productReviews.length} verified buyer${productReviews.length > 1 ? "s" : ""})`;
    }

    grid.innerHTML = productReviews
      .map((r) => {
        const isCommentOpen = !!openCommentsState[r.id];
        const comments = r.comments || [];
        const starsHtml = "★".repeat(r.rating) + "☆".repeat(5 - r.rating);

        return `
          <div class="review-card" id="review-card-${r.id}">
            <div class="review-top">
              <span style="color: #F59E0B; font-size: 1.1rem; letter-spacing: 2px;">${starsHtml}</span>
              ${r.verified ? `<span class="verified-badge">✓ Verified Buyer</span>` : ""}
            </div>

            <div class="review-author-row">
              <span class="review-author-name">${r.author}</span>
              ${r.size ? `<span class="review-size-tag">Size: ${r.size}</span>` : ""}
              <span class="review-date">${r.date}</span>
            </div>

            ${r.headline ? `<div class="review-headline">${r.headline}</div>` : ""}
            <p class="review-text">"${r.text}"</p>

            <!-- Action Bar: Like & Comment -->
            <div class="review-actions-bar">
              <button class="review-action-btn like-btn ${r.userLiked ? "liked" : ""}" 
                      onclick="window.PdpController.toggleReviewLike('${r.id}')"
                      title="${r.userLiked ? "Unlike" : "Helpful / Like"}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="${r.userLiked ? "currentColor" : "none"}" stroke="currentColor" stroke-width="2">
                  <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                </svg>
                <span>Helpful (${r.likes || 0})</span>
              </button>

              <button class="review-action-btn comment-btn ${isCommentOpen ? "active" : ""}" 
                      onclick="window.PdpController.toggleComments('${r.id}')"
                      title="View & Post Replies">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <span>Replies (${comments.length})</span>
              </button>
            </div>

            <!-- Threaded Comments / Replies -->
            ${
              isCommentOpen
                ? `
              <div class="comments-thread" id="comments-thread-${r.id}">
                ${
                  comments.length > 0
                    ? `
                  <div class="comments-list">
                    ${comments
                      .map(
                        (c) => `
                      <div class="comment-item">
                        <div class="comment-header">
                          <span class="comment-author">${c.author}</span>
                          ${c.isStore ? `<span class="store-badge">Official Store Reply</span>` : ""}
                          <span class="comment-time">${c.date}</span>
                        </div>
                        <div class="comment-body">${c.text}</div>
                      </div>
                    `
                      )
                      .join("")}
                  </div>
                `
                    : `<div style="font-size:0.8rem; color:var(--color-text-muted); margin-bottom:0.75rem;">No replies yet. Be the first to comment!</div>`
                }

                <!-- Inline Reply Form -->
                <div class="comment-form">
                  <div class="comment-form-row">
                    <input type="text" id="comment-author-${r.id}" placeholder="Your Name" class="form-input comment-name-input" />
                    <input type="text" id="comment-msg-${r.id}" placeholder="Write a reply or question..." class="form-input comment-msg-input" 
                           onkeydown="if(event.key==='Enter') window.PdpController.addReviewComment('${r.id}')" />
                    <button type="button" class="btn-comment-submit" onclick="window.PdpController.addReviewComment('${r.id}')">
                      Reply
                    </button>
                  </div>
                </div>
              </div>
            `
                : ""
            }
          </div>
        `;
      })
      .join("");
  }

  function toggleWriteReviewForm(forceState) {
    const box = document.getElementById("write-review-container");
    if (!box) return;
    const shouldShow = forceState !== undefined ? forceState : box.style.display === "none";
    box.style.display = shouldShow ? "block" : "none";
    if (shouldShow) {
      box.scrollIntoView({ behavior: "smooth", block: "nearest" });
      const nameInput = document.getElementById("review-author-name");
      if (nameInput) nameInput.focus();
    }
  }

  function setReviewStarRating(rating) {
    const ratingInput = document.getElementById("review-rating-val");
    if (ratingInput) ratingInput.value = rating;

    const stars = document.querySelectorAll("#star-rating-picker .star-pick-btn");
    stars.forEach((s) => {
      const starRating = parseInt(s.getAttribute("data-rating"), 10);
      if (starRating <= rating) {
        s.classList.add("active");
      } else {
        s.classList.remove("active");
      }
    });

    const label = document.getElementById("star-rating-text");
    if (label) {
      const labels = [
        "1 Star - Poor Fit",
        "2 Stars - Subpar Quality",
        "3 Stars - Average Comfort",
        "4 Stars - Great Look & Feel",
        "5 Stars - Phenomenal Quality!"
      ];
      label.textContent = labels[rating - 1] || `${rating} Stars`;
    }
  }

  function submitReview(event) {
    event.preventDefault();
    const name = document.getElementById("review-author-name")?.value.trim();
    const size = document.getElementById("review-author-size")?.value || "M";
    const headline = document.getElementById("review-headline")?.value.trim() || "";
    const text = document.getElementById("review-body-text")?.value.trim();
    const rating = parseInt(document.getElementById("review-rating-val")?.value || "5", 10);
    const verified = document.getElementById("review-verified-check")?.checked ?? true;

    if (!name || !text) {
      if (window.FeatherApp?.showToast) {
        window.FeatherApp.showToast("Please enter your name and review details.", "warning");
      } else {
        alert("Please enter your name and review details.");
      }
      return;
    }

    const newReview = {
      id: `rev-${Date.now()}`,
      author: name,
      verified,
      size,
      rating,
      headline,
      text,
      date: "Just now",
      likes: 1,
      userLiked: true,
      comments: []
    };

    productReviews.unshift(newReview);
    saveReviews();
    renderReviews();
    toggleWriteReviewForm(false);

    // Reset Form
    document.getElementById("write-review-form")?.reset();
    setReviewStarRating(5);

    if (window.FeatherApp?.showToast) {
      window.FeatherApp.showToast("Thank you! Your verified review has been published ✨", "success");
    }
  }

  function toggleReviewLike(reviewId) {
    const rev = productReviews.find((r) => r.id === reviewId);
    if (!rev) return;

    if (rev.userLiked) {
      rev.likes = Math.max(0, (rev.likes || 1) - 1);
      rev.userLiked = false;
      if (window.FeatherApp?.showToast) {
        window.FeatherApp.showToast("Removed helpful vote.", "info");
      }
    } else {
      rev.likes = (rev.likes || 0) + 1;
      rev.userLiked = true;
      if (window.FeatherApp?.showToast) {
        window.FeatherApp.showToast("Marked review as helpful 👍", "success");
      }
    }

    saveReviews();
    renderReviews();
  }

  function toggleComments(reviewId) {
    openCommentsState[reviewId] = !openCommentsState[reviewId];
    renderReviews();
    if (openCommentsState[reviewId]) {
      setTimeout(() => {
        const input = document.getElementById(`comment-msg-${reviewId}`);
        if (input) input.focus();
      }, 50);
    }
  }

  function addReviewComment(reviewId) {
    const rev = productReviews.find((r) => r.id === reviewId);
    if (!rev) return;

    const authorInput = document.getElementById(`comment-author-${reviewId}`);
    const msgInput = document.getElementById(`comment-msg-${reviewId}`);

    const author = authorInput?.value.trim() || "Shopper";
    const text = msgInput?.value.trim();

    if (!text) {
      if (window.FeatherApp?.showToast) {
        window.FeatherApp.showToast("Please enter a reply message.", "warning");
      }
      return;
    }

    if (!rev.comments) rev.comments = [];
    rev.comments.push({
      id: `com-${Date.now()}`,
      author,
      isStore: false,
      date: "Just now",
      text
    });

    saveReviews();
    renderReviews();

    if (window.FeatherApp?.showToast) {
      window.FeatherApp.showToast("Reply published successfully! 💬", "success");
    }
  }

  // Global PDP controller exposure
  window.PdpController = {
    setActiveImage,
    setColor,
    setSize,
    updateQty,
    handleAddToCart,
    handleBuyNow,
    toggleWishlist,
    initReviews,
    renderReviews,
    toggleWriteReviewForm,
    setReviewStarRating,
    submitReview,
    toggleReviewLike,
    toggleComments,
    addReviewComment,
  };

  // Tab switching helper
  window.switchPdpTab = function (tabKey, btn) {
    document.querySelectorAll(".pdp-tab-btn").forEach((b) => b.classList.remove("active"));
    document.querySelectorAll(".pdp-tab-content").forEach((c) => (c.style.display = "none"));

    btn.classList.add("active");
    const target = document.getElementById(`tab-${tabKey}`);
    if (target) target.style.display = "block";
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initPdp);
  } else {
    initPdp();
  }
})();
