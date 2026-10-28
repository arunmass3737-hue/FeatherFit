/**
 * FEATHER FITS — ULTRA LUXURY ADMIN DASHBOARD & CMS ENGINE
 * Architecture:
 * - Real-time Executive KPI Metric computations
 * - Products Inventory Complete CRUD:
 *     - Create: Add New Apparel Drop Modal with Image File Upload & Dropzone
 *     - Read: Catalog Table with Search & Department Filters
 *     - Update: Comprehensive Edit Product Modal (Image upload, Name, Dept, Price, Stock)
 *     - Delete: Instant Product Removal with LocalStorage Persistence
 * - Advanced Storefront Banners & Live CMS:
 *     - Interactive Real-time Mockup Previews
 *     - Real File Upload (Drag & Drop or browse computer) for Hero Banners
 *     - Live editing of Badges, Headlines, Subtitles & CTA buttons
 *     - Top Announcement Bar Live Editor
 *     - Syncs instantly with index.php storefront
 * - Orders Management & Live Dispatch (Pending, Processing, Shipped, Delivered) + Delete Order
 * - Customers Directory with Lifetime Spend & VIP Badges
 * - Mobile Responsive Drawer & Backdrop Support
 */

(function () {
  "use strict";

  const STORAGE_KEYS = {
    ADMIN_PRODUCTS: "ff_admin_products_v1",
    ORDERS: "ff_orders_v1",
    USER: "ff_user_v1",
    CUSTOM_BANNERS: "ff_custom_banners",
    CUSTOM_ANNOUNCEMENT: "ff_custom_announcement"
  };

  
  // Carrier Quicklink URL Builder
  function buildCarrierTrackingUrl(carrierName, trackingNo) {
    if (!trackingNo || trackingNo === 'Pending') return '';
    const cleanNo = encodeURIComponent(String(trackingNo).trim());
    const c = (carrierName || '').toLowerCase();
    if (c.includes('blue dart') || c.includes('bluedart')) {
      return `https://www.bluedart.com/tracking?trackNumber=${cleanNo}`;
    }
    if (c.includes('delhivery')) {
      return `https://www.delhivery.com/track/package/${cleanNo}`;
    }
    if (c.includes('india post') || c.includes('speed post')) {
      return `https://www.indiapost.gov.in/_layouts/15/dop.portal.tracking/trackconsignment.aspx?consignmentNo=${cleanNo}`;
    }
    if (c.includes('dtdc')) {
      return `https://www.dtdc.in/tracking/shipment-tracking.asp?tracking_no=${cleanNo}`;
    }
    if (c.includes('shadowfax')) {
      return `https://tracker.shadowfax.in/#/track/${cleanNo}`;
    }
    if (c.includes('xpressbees')) {
      return `https://www.xpressbees.com/track?isawb=true&trackid=${cleanNo}`;
    }
    return `https://www.google.com/search?q=${encodeURIComponent((carrierName || '') + ' tracking ' + cleanNo)}`;
  }

  function isOrderDelayed(o) {
    if (o.status !== 'Shipped') return false;
    if (o.deliveredAt) return false;
    const shippedTime = o.shippedAt ? new Date(o.shippedAt).getTime() : (o.date ? new Date(o.date).getTime() : 0);
    if (!shippedTime) return false;
    return (Date.now() - shippedTime) > (5 * 86400 * 1000);
  }

  // Seed default demonstration orders if none exist
  const defaultOrders = [
    {
      id: "FF-90214",
      customer: { name: "Arun Kumar", email: "arun.kumar@featherfits.com", phone: "+91 98765 43210", city: "Chennai" },
      items: [
        { name: "Cyberpunk Ronin Oversized Graphic Tee", size: "L", qty: 1, price: 1299 }
      ],
      total: 1363.95,
      date: "2026-10-28 09:15 AM",
      status: "Shipped",
      carrier: "Blue Dart Express",
      trackingNumber: "BD-9402849182"
    },
    {
      id: "FF-90213",
      customer: { name: "Priya Sundaram", email: "priya.s@gmail.com", phone: "+91 98401 23456", city: "Bengaluru" },
      items: [
        { name: "Confidence Cropped Luxe Cotton T-Shirt", size: "M", qty: 2, price: 999 }
      ],
      total: 2097.90,
      date: "2026-10-28 08:30 AM",
      status: "Processing",
      carrier: "Delhivery",
      trackingNumber: "DEL-8401928"
    },
    {
      id: "FF-90212",
      customer: { name: "Vikram Rathore", email: "vikram.r@outlook.com", phone: "+91 97112 34567", city: "Mumbai" },
      items: [
        { name: "Demon Slayer Tanjiro Graphic Tee", size: "XL", qty: 1, price: 1299 },
        { name: "Relaxed Summer Linen Long Sleeve Shirt", size: "XL", qty: 1, price: 1799 }
      ],
      total: 3252.90,
      date: "2026-10-27 06:45 PM",
      status: "Delivered",
      carrier: "Blue Dart Express",
      trackingNumber: "BD-9401773612"
    },
    {
      id: "FF-90211",
      customer: { name: "Ananya Iyer", email: "ananya.iyer@yahoo.com", phone: "+91 94441 89012", city: "Coimbatore" },
      items: [
        { name: "Kids' Naruto Spirit Anime Streetwear Tee", size: "S", qty: 1, price: 799 }
      ],
      total: 838.95,
      date: "2026-10-27 02:10 PM",
      status: "Delivered",
      carrier: "India Post Speed",
      trackingNumber: "IPS-77192830"
    },
    {
      id: "FF-90210",
      customer: { name: "Rahul Sharma", email: "rahul.sharma@techcorp.in", phone: "+91 98110 55432", city: "Hyderabad" },
      items: [
        { name: "Tokyo Drift Midnight Club Vintage Tee", size: "L", qty: 2, price: 1299 }
      ],
      total: 2727.90,
      date: "2026-10-27 10:20 AM",
      status: "Pending",
      carrier: "Pending Assignment",
      trackingNumber: "Pending"
    }
  ];

  // Seed demo customers
  const demoCustomers = [
    { name: "Arun Kumar", email: "arun.kumar@featherfits.com", phone: "+91 98765 43210", city: "Chennai", orders: 14, spent: 18450, tier: "VIP Elite" },
    { name: "Priya Sundaram", email: "priya.s@gmail.com", phone: "+91 98401 23456", city: "Bengaluru", orders: 6, spent: 7890, tier: "Member" },
    { name: "Vikram Rathore", email: "vikram.r@outlook.com", phone: "+91 97112 34567", city: "Mumbai", orders: 9, spent: 14200, tier: "VIP Elite" },
    { name: "Ananya Iyer", email: "ananya.iyer@yahoo.com", phone: "+91 94441 89012", city: "Coimbatore", orders: 3, spent: 3450, tier: "Member" },
    { name: "Rahul Sharma", email: "rahul.sharma@techcorp.in", phone: "+91 98110 55432", city: "Hyderabad", orders: 5, spent: 6890, tier: "Member" },
    { name: "Kavitha Rajan", email: "kavitha.r@gmail.com", phone: "+91 99400 11223", city: "Madurai", orders: 8, spent: 11200, tier: "VIP Elite" }
  ];

  // Admin State
  const state = {
    products: [],
    orders: [],
    customers: demoCustomers,
    customBanners: {},
    activeSection: "overview",
    activeBannerTab: "all",
    productDeptFilter: "all",
    orderStatusFilter: "all",
    globalSearchQuery: "",
    tempUploadedImages: {
      add: null,
      edit: null,
      banner: null
    }
  };

  // Initialize
  function initAdmin() {
    loadProducts();
    loadOrders();
    loadCustomBanners();
    renderKpiStats();
    renderRecentOrdersTable();
    renderProductsTable();
    renderOrdersFullTable();
    renderCustomersTable();
    renderPerformanceChart();
    renderBannerCustomizer();
    attachEventListeners();
  }

  // Load products (Base catalog + LocalStorage overrides)
  function loadProducts() {
    const local = localStorage.getItem(STORAGE_KEYS.ADMIN_PRODUCTS);
    if (local) {
      try {
        state.products = JSON.parse(local);
        return;
      } catch (e) {
        console.error("Failed to parse local admin products", e);
      }
    }
    const base = window.FEATHER_PRODUCTS || [];
    state.products = JSON.parse(JSON.stringify(base));
  }

  function saveProducts(productToSync = null, isDelete = false) {
    localStorage.setItem(STORAGE_KEYS.ADMIN_PRODUCTS, JSON.stringify(state.products));
    localStorage.setItem("ff_sync_trigger", Date.now().toString());
    renderProductsTable();
    renderKpiStats();

    // Direct sync to server JSON storage
    if (productToSync) {
      if (isDelete) {
        fetch('api/admin.php?action=delete_product', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: productToSync.id || productToSync })
        }).catch(err => console.warn('Server sync error', err));
      } else {
        fetch('api/admin.php?action=save_product', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ product: productToSync })
        }).catch(err => console.warn('Server sync error', err));
      }
    }
  }

  // Load Custom Banners
  function loadCustomBanners() {
    const local = localStorage.getItem(STORAGE_KEYS.CUSTOM_BANNERS);
    let banners = {};
    if (local) {
      try {
        banners = JSON.parse(local);
      } catch (e) {
        banners = {};
      }
    }
    const base = window.FEATHER_DEPARTMENT_BANNERS || {};
    state.customBanners = Object.assign({}, JSON.parse(JSON.stringify(base)), banners);
  }

  function saveCustomBanners(dept = null) {
    localStorage.setItem(STORAGE_KEYS.CUSTOM_BANNERS, JSON.stringify(state.customBanners));
    localStorage.setItem("ff_sync_trigger", Date.now().toString());

    // Direct sync to server JSON storage
    const targetDept = dept || state.activeBannerTab;
    if (state.customBanners && state.customBanners[targetDept]) {
      fetch('api/admin.php?action=save_banner', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ dept: targetDept, banner: state.customBanners[targetDept] })
      }).catch(err => console.warn('Server sync error', err));
    }
  }

  // Dynamically update order filter pills and transit radar badges
  function updateOrderFilterBadges() {
    const orders = state.orders || [];
    let inTransit = 0;
    let delayed = 0;
    let delivered = 0;
    let pending = 0;
    let noTracking = 0;
    let rto = 0;

    orders.forEach(o => {
      const s = (o.status || '').toLowerCase();
      const isDel = isOrderDelayed(o);
      if (s === 'shipped' || s === 'out for delivery') inTransit++;
      if (isDel) delayed++;
      if (s === 'delivered') delivered++;
      if (s === 'pending' || s === 'processing') pending++;
      if ((s === 'shipped' || s === 'out for delivery') && (!o.trackingNumber || o.trackingNumber === 'Pending' || !o.trackingNumber.trim())) noTracking++;
      if (s === 'rto') rto++;
    });

    const elAll = document.getElementById('count-filter-all');
    const elShipped = document.getElementById('count-filter-shipped');
    const elPending = document.getElementById('count-filter-pending');
    const elDelivered = document.getElementById('count-filter-delivered');
    const elNoTrack = document.getElementById('count-filter-notracking');
    const elDelayed = document.getElementById('count-filter-delayed');
    const elRto = document.getElementById('count-filter-rto');

    if (elAll) elAll.textContent = orders.length;
    if (elShipped) elShipped.textContent = inTransit;
    if (elPending) elPending.textContent = pending;
    if (elDelivered) elDelivered.textContent = delivered;
    if (elNoTrack) elNoTrack.textContent = noTracking;
    if (elDelayed) elDelayed.textContent = delayed;
    if (elRto) elRto.textContent = rto;

    const kpiTransit = document.getElementById('kpi-transit-count');
    const kpiDelayed = document.getElementById('kpi-delayed-count');
    if (kpiTransit) kpiTransit.textContent = inTransit;
    if (kpiDelayed) kpiDelayed.textContent = delayed;
  }

  // Load orders
  function loadOrders() {
    const local = localStorage.getItem(STORAGE_KEYS.ORDERS);
    let ordersList = [];
    if (local) {
      try {
        ordersList = JSON.parse(local);
      } catch (e) {
        ordersList = [];
      }
    }
    if (ordersList.length === 0) {
      ordersList = defaultOrders;
      localStorage.setItem(STORAGE_KEYS.ORDERS, JSON.stringify(ordersList));
    }
    state.orders = ordersList;
    updateOrderFilterBadges();

    // Sync live from server backend (data/orders.json)
    fetch('api/shipping.php?action=get_orders')
      .then(res => res.json())
      .then(data => {
        if (data && data.success && Array.isArray(data.orders) && data.orders.length > 0) {
          state.orders = data.orders;
          localStorage.setItem(STORAGE_KEYS.ORDERS, JSON.stringify(data.orders));
          updateOrderFilterBadges();
          renderKpiStats();
          renderRecentOrdersTable();
          renderOrdersFullTable();
        }
      })
      .catch(err => console.warn('Could not sync live orders from backend', err));
  }

  function saveOrders() {
    localStorage.setItem(STORAGE_KEYS.ORDERS, JSON.stringify(state.orders));
    updateOrderFilterBadges();
    renderRecentOrdersTable();
    renderOrdersFullTable();
    renderKpiStats();
  }

  // Calculate & Render Executive KPI Stats
  function renderKpiStats() {
    const totalRev = state.orders.reduce((sum, o) => sum + (Number(o.total) || 0), 0);
    const totalOrdersCount = state.orders.length;
    const productsCount = state.products.length;
    const aov = totalOrdersCount > 0 ? Math.round(totalRev / totalOrdersCount) : 0;

    const revEl = document.getElementById("kpi-total-revenue");
    const ordersEl = document.getElementById("kpi-total-orders");
    const prodsEl = document.getElementById("kpi-total-products");
    const aovEl = document.getElementById("kpi-aov");

    if (revEl) revEl.textContent = `₹${Math.round(totalRev).toLocaleString("en-IN")}`;
    if (ordersEl) ordersEl.textContent = `${totalOrdersCount}`;
    if (prodsEl) prodsEl.textContent = `${productsCount} Fits`;
    if (aovEl) aovEl.textContent = `₹${aov.toLocaleString("en-IN")}`;

    const prodBadge = document.getElementById("badge-nav-products");
    const orderBadge = document.getElementById("badge-nav-orders");
    if (prodBadge) prodBadge.textContent = productsCount;
    if (orderBadge) orderBadge.textContent = totalOrdersCount;

    const mobProdBadge = document.getElementById("mobile-badge-products");
    if (mobProdBadge) mobProdBadge.textContent = productsCount;
    const mobOrderBadge = document.getElementById("mobile-badge-orders");
    if (mobOrderBadge) mobOrderBadge.textContent = totalOrdersCount;

    const menCount = state.products.filter(p => p.department === "men").length;
    const womenCount = state.products.filter(p => p.department === "women").length;
    const kidsCount = state.products.filter(p => p.department === "kids").length;

    const total = productsCount || 1;
    const menPct = Math.round((menCount / total) * 100);
    const womenPct = Math.round((womenCount / total) * 100);
    const kidsPct = Math.round((kidsCount / total) * 100);

    const fillMen = document.getElementById("cat-fill-men");
    const fillWomen = document.getElementById("cat-fill-women");
    const fillKids = document.getElementById("cat-fill-kids");

    if (fillMen) fillMen.style.width = `${menPct}%`;
    if (fillWomen) fillWomen.style.width = `${womenPct}%`;
    if (fillKids) fillKids.style.width = `${kidsPct}%`;

    const countMen = document.getElementById("cat-count-men");
    const countWomen = document.getElementById("cat-count-women");
    const countKids = document.getElementById("cat-count-kids");

    if (countMen) countMen.textContent = `${menCount} Fits (${menPct}%)`;
    if (countWomen) countWomen.textContent = `${womenCount} Fits (${womenPct}%)`;
    if (countKids) countKids.textContent = `${kidsCount} Fits (${kidsPct}%)`;
  }

  // Render Mini Bar Chart (Weekly Revenue)
  function renderPerformanceChart() {
    const chartWrap = document.getElementById("chart-bars-wrap");
    if (!chartWrap) return;

    const weeklyData = [
      { day: "Mon", rev: "₹24.5k", height: "65%" },
      { day: "Tue", rev: "₹18.2k", height: "45%" },
      { day: "Wed", rev: "₹32.0k", height: "82%" },
      { day: "Thu", rev: "₹29.4k", height: "74%" },
      { day: "Fri", rev: "₹38.8k", height: "92%" },
      { day: "Sat", rev: "₹45.2k", height: "100%" },
      { day: "Sun", rev: "₹35.6k", height: "88%" }
    ];

    chartWrap.innerHTML = weeklyData.map(d => `
      <div class="bar-col">
        <div class="bar-fill" style="height: ${d.height};">
          <span class="bar-tooltip">${d.rev}</span>
        </div>
        <span class="bar-label">${d.day}</span>
      </div>
    `).join("");
  }

  // Render Recent Orders in Overview tab
  function renderRecentOrdersTable() {
    const tbody = document.getElementById("recent-orders-tbody");
    if (!tbody) return;

    const recent = state.orders.slice(0, 5);
    tbody.innerHTML = recent.map(o => createOrderRowHtml(o)).join("");
  }

  // Render Full Orders Tab
  function renderOrdersFullTable() {
    const tbody = document.getElementById("all-orders-tbody");
    if (!tbody) return;

    let filtered = [...state.orders];

    if (state.orderStatusFilter !== "all") {
      const f = state.orderStatusFilter.toLowerCase();
      if (f === 'no_tracking') {
        filtered = filtered.filter(o => o.status === 'Shipped' && (!o.trackingNumber || o.trackingNumber === 'Pending' || o.trackingNumber.trim() === ''));
      } else if (f === 'delayed') {
        filtered = filtered.filter(o => isOrderDelayed(o));
      } else if (f === 'rto') {
        filtered = filtered.filter(o => (o.status || '').toLowerCase() === 'rto');
      } else {
        filtered = filtered.filter(o => (o.status || '').toLowerCase() === f);
      }
    }

    if (state.globalSearchQuery) {
      const q = state.globalSearchQuery.toLowerCase();
      filtered = filtered.filter(o => 
        (o.id && o.id.toLowerCase().includes(q)) ||
        (o.customer?.name && o.customer.name.toLowerCase().includes(q)) ||
        (o.customer?.city && o.customer.city.toLowerCase().includes(q))
      );
    }

    if (filtered.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="7" style="text-align: center; padding: 2.5rem; color: var(--admin-text-muted);">
            No orders found matching current filter criteria.
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = filtered.map(o => createOrderRowHtml(o)).join("");
  }

  function createOrderRowHtml(o) {
    const statusBadges = {
      "Pending": "badge-warning",
      "Processing": "badge-info",
      "Packed": "badge-info",
      "Shipped": "badge-purple",
      "Out for Delivery": "badge-purple",
      "Delivered": "badge-success",
      "Cancelled": "badge-danger",
      "RTO": "badge-danger"
    };
    const badgeClass = statusBadges[o.status] || "badge-purple";
    const itemCount = o.items ? o.items.reduce((s, i) => s + (i.qty || 1), 0) : 1;
    const firstItemName = o.items && o.items[0] ? o.items[0].name : "Luxury Apparel Drop";
    const orderKey = o.id || o.orderNumber;
    const delayed = isOrderDelayed(o);
    const hasTracking = o.trackingNumber && o.trackingNumber !== "Pending" && o.trackingNumber.trim() !== "";
    const trackingLink = hasTracking ? (o.trackingUrl || buildCarrierTrackingUrl(o.carrier, o.trackingNumber)) : "";
    const custPhone = o.customer?.phone || "+91 98765 43210";
    const cleanPhoneDigits = custPhone.replace(/[^0-9]/g, '');
    const cleanPhone10 = cleanPhoneDigits.length >= 10 ? cleanPhoneDigits.slice(-10) : cleanPhoneDigits;
    const custName = o.customer?.name || "Customer";
    const publicTrackUrl = window.location.origin + '/track.php?order=' + encodeURIComponent(orderKey) + '&phone=' + cleanPhone10;

    const waMessage = `Hi ${custName}, unga order #${orderKey} ship aayiduchu! 🚀\nCarrier: ${o.carrier || 'Express Courier'}\nAWB Tracking: ${o.trackingNumber}\nTrack live parcel here: ${publicTrackUrl}\nEstimated Delivery: ${o.estimatedDelivery || 'In 2-3 Days'}\n\nThank you for choosing Feather Fits!`;
    const waUrl = `https://wa.me/91${cleanPhone10}?text=${encodeURIComponent(waMessage)}`;

    return `
      <tr class="${delayed ? 'order-row-delayed' : ''}">
        <td>
          <a href="track.php?order=${encodeURIComponent(orderKey)}&phone=${cleanPhone10}" target="_blank" style="color: var(--admin-purple-primary); font-family: monospace; font-weight: 800; text-decoration: none;" title="Open Public Customer Tracking Portal">
            ${orderKey} ↗
          </a>
          <div style="font-size: 0.72rem; color: var(--admin-text-faint);">${o.date || (o.createdAt ? new Date(o.createdAt).toLocaleDateString() : 'Today')}</div>
        </td>
        <td>
          <div style="font-weight: 700; color: var(--admin-black);">${custName}</div>
          <div style="font-size: 0.75rem; color: var(--admin-text-faint);">${custPhone} &bull; ${o.customer?.city || "Chennai"}</div>
        </td>
        <td>
          <div style="max-width: 170px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.82rem;" title="${firstItemName}">
            ${firstItemName}
          </div>
          <div style="font-size: 0.75rem; color: var(--admin-text-faint);">${itemCount} item(s) &bull; <strong style="color: var(--admin-black);">₹${Number(o.total).toLocaleString("en-IN")}</strong></div>
        </td>
        <td>
          ${hasTracking ? `
            <div style="display: flex; flex-direction: column; gap: 0.25rem;">
              <div style="display: flex; align-items: center; gap: 0.4rem;">
                <span class="carrier-badge">${o.carrier || 'Express'}</span>
              </div>
              <div style="display: flex; align-items: center; gap: 0.4rem; font-family: monospace; font-size: 0.82rem; font-weight: 700; color: #3B0F4A;">
                <span>📦 ${o.trackingNumber}</span>
                <a href="${trackingLink}" target="_blank" class="track-icon-btn" title="Track in Carrier Site (Opens in New Tab)">↗</a>
                <a href="${waUrl}" target="_blank" class="whatsapp-icon-btn" title="Send Tracking Link via WhatsApp">💬</a>
              </div>
            </div>
          ` : `
            <button type="button" class="btn-needs-tracking" onclick="window.FeatherAdmin.openShippingModal('${orderKey}')" title="Click to add carrier & tracking number">
              ⚠️ Needs Tracking
            </button>
          `}
        </td>
        <td>
          <div style="display: flex; flex-direction: column; gap: 0.25rem; align-items: flex-start;">
            <span class="badge ${badgeClass}">${o.status}</span>
            ${delayed ? `<span class="badge badge-delayed">🚨 Delayed > 5d</span>` : ''}
            ${(o.status === 'Shipped' || o.status === 'Out for Delivery') ? `
              <button type="button" class="btn-quick-deliver" onclick="window.FeatherAdmin.markAsDelivered('${orderKey}')" title="Click to manually mark order as Delivered">
                ✓ Mark Delivered
              </button>
            ` : ''}
          </div>
        </td>
        <td>
          <div class="table-actions" style="gap: 0.45rem;">
            <button type="button" class="btn-dispatch-action" onclick="window.FeatherAdmin.openShippingModal('${orderKey}')" title="Shipping & Dispatch Details">
              <span>🚚 Dispatch</span>
            </button>
            <button type="button" class="btn-icon-action danger" title="Delete Order" onclick="window.FeatherAdmin.deleteOrder('${orderKey}')">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
              </svg>
            </button>
          </div>
        </td>
      </tr>
    `;
  }

  // Render Products Table
  function renderProductsTable() {
    const tbody = document.getElementById("products-table-tbody");
    if (!tbody) return;

    let filtered = [...state.products];

    if (state.productDeptFilter !== "all") {
      filtered = filtered.filter(p => p.department === state.productDeptFilter);
    }

    if (state.globalSearchQuery) {
      const q = state.globalSearchQuery.toLowerCase();
      filtered = filtered.filter(p => 
        p.name.toLowerCase().includes(q) ||
        p.categoryName.toLowerCase().includes(q) ||
        p.id.toLowerCase().includes(q)
      );
    }

    if (filtered.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="7" style="text-align: center; padding: 2.5rem; color: var(--admin-text-muted);">
            No apparel products found matching current criteria.
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = filtered.map(p => {
      const imageSrc = p.images && p.images[0] ? p.images[0] : "assets/images/anime-tee.jpg";
      const deptBadge = p.department === "men" ? "👔 Men" : (p.department === "women" ? "👗 Women" : "🧒 Kids");

      return `
        <tr>
          <td>
            <div class="product-cell">
              <img src="${imageSrc}" alt="${p.name}" class="product-thumb" />
              <div class="product-meta">
                <h4>${p.name}</h4>
                <span>ID: ${p.id}</span>
              </div>
            </div>
          </td>
          <td>
            <span class="badge badge-purple">${deptBadge}</span>
          </td>
          <td><span style="font-size: 0.85rem; color: var(--admin-text-muted); font-weight: 600;">${p.categoryName}</span></td>
          <td><strong style="color: var(--admin-black); font-size: 0.95rem;">₹${p.basePrice.toLocaleString("en-IN")}</strong></td>
          <td>
            ${p.originalPrice ? `<span style="text-decoration: line-through; color: var(--admin-text-faint); font-size: 0.82rem;">₹${p.originalPrice.toLocaleString("en-IN")}</span>` : "—"}
          </td>
          <td>
            <span class="badge badge-success">In Stock</span>
          </td>
          <td>
            <div class="table-actions">
              <button type="button" class="btn-icon-action edit-btn" title="Edit Product Details" onclick="window.FeatherAdmin.openEditProductModal('${p.id}')">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
              </button>
              <a href="product.php?id=${p.id}" target="_blank" class="btn-icon-action" title="Preview on Store">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" y1="14" x2="21" y2="3"></line>
                </svg>
              </a>
              <button type="button" class="btn-icon-action danger" title="Delete Product" onclick="window.FeatherAdmin.deleteProduct('${p.id}')">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
              </button>
            </div>
          </td>
        </tr>
      `;
    }).join("");
  }

  // Render Customers Table
  function renderCustomersTable() {
    const tbody = document.getElementById("customers-table-tbody");
    if (!tbody) return;

    tbody.innerHTML = state.customers.map(c => `
      <tr>
        <td>
          <div style="font-weight: 700; color: var(--admin-black);">${c.name}</div>
          <span style="font-size: 0.75rem; color: var(--admin-text-faint);">${c.email}</span>
        </td>
        <td>${c.phone}</td>
        <td>${c.city}</td>
        <td><strong style="color: var(--admin-purple-primary);">${c.orders} Orders</strong></td>
        <td><strong style="color: var(--admin-black);">₹${c.spent.toLocaleString("en-IN")}</strong></td>
        <td>
          <span class="badge ${c.tier === "VIP Elite" ? "badge-purple" : "badge-info"}">${c.tier}</span>
        </td>
      </tr>
    `).join("");
  }

  // ==========================================================================
  // ADVANCED STOREFRONT BANNERS & LIVE CMS
  // ==========================================================================
  function setBannerTab(tab) {
    state.activeBannerTab = tab;
    document.querySelectorAll(".filter-btn[data-banner-tab]").forEach(btn => {
      btn.classList.toggle("active", btn.getAttribute("data-banner-tab") === tab);
    });
    renderBannerCustomizer();
  }

  function renderBannerCustomizer() {
    const container = document.getElementById("banner-config-container");
    if (!container) return;

    if (state.activeBannerTab === "announcement") {
      const currentText = localStorage.getItem(STORAGE_KEYS.CUSTOM_ANNOUNCEMENT) || 
        "Complimentary Standard Shipping on all orders over ₹999 &bull; Use code <strong>FEATHER15</strong> for 15% off";

      container.innerHTML = `
        <div class="banner-config-card" style="grid-template-columns: 1fr;">
          <div class="banner-form-wrap">
            <div class="banner-form-header">
              <h3>Top Announcement Bar Customizer</h3>
              <span class="banner-dept-tag">📢 Site-wide Header Bar</span>
            </div>

            <!-- Live Bar Preview -->
            <div style="margin: 0.5rem 0;">
              <span style="font-size: 0.78rem; font-weight: 700; text-transform: uppercase; color: var(--admin-text-faint); display: block; margin-bottom: 0.4rem;">Live Preview on Store:</span>
              <div style="background: #111827; color: #fff; padding: 0.65rem 1rem; border-radius: var(--radius-sm); font-size: 0.85rem; display: flex; align-items: center; gap: 0.75rem; box-shadow: var(--admin-shadow-sm);">
                <span style="background: var(--admin-purple-primary); padding: 0.15rem 0.55rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 800;">Free Delivery</span>
                <span id="announcement-live-preview">${currentText}</span>
              </div>
            </div>

            <div class="form-group">
              <label for="announcement-text-input">Announcement Message HTML *</label>
              <textarea id="announcement-text-input" class="form-control" rows="3" oninput="document.getElementById('announcement-live-preview').innerHTML = this.value">${currentText}</textarea>
              <span style="font-size: 0.75rem; color: var(--admin-text-faint);">HTML tags like &lt;strong&gt; and &bull; are supported.</span>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 0.5rem;">
              <button type="button" class="btn-secondary-light" onclick="window.FeatherAdmin.resetAnnouncementDefault()">Reset Default</button>
              <button type="button" class="btn-primary-purple" onclick="window.FeatherAdmin.saveAnnouncementBar()">Save Announcement</button>
            </div>
          </div>
        </div>
      `;
      return;
    }

    const dept = state.activeBannerTab;
    const banner = state.customBanners[dept] || state.customBanners.all || {};
    const deptLabels = {
      all: "✨ Home (All Drops) Hero",
      men: "👔 Men's Fashion Hero",
      women: "👗 Women's Collection Hero",
      kids: "🧒 Kids' Streetwear Hero"
    };

    container.innerHTML = `
      <div class="banner-config-card">
        <!-- Live Mockup Box -->
        <div class="banner-preview-mockup" id="banner-mockup-wrap">
          <img src="${banner.image}" id="banner-mockup-img" class="mockup-bg" alt="Hero Banner Preview" />
          <div class="banner-preview-overlay"></div>
          <div class="banner-preview-content">
            <span class="banner-preview-badge" id="banner-mockup-badge">${banner.badge || 'CONFIDENCE FITS YOU'}</span>
            <h2 class="banner-preview-title" id="banner-mockup-title">${banner.title || 'Confidence Fits You.'}</h2>
            <p class="banner-preview-desc" id="banner-mockup-desc">${banner.subtitle || 'Browse our newest apparel drops.'}</p>
            <div class="banner-preview-cta" id="banner-mockup-cta">
              <span>${banner.primaryCta ? banner.primaryCta.label : 'Explore Drops'}</span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </div>
        </div>

        <!-- Banner Form Configuration -->
        <div class="banner-form-wrap">
          <div class="banner-form-header">
            <h3>Configure ${deptLabels[dept]}</h3>
            <span class="banner-dept-tag">Active Live Banner</span>
          </div>

          <!-- File Upload Dropzone for Banner -->
          <div class="form-group">
            <label>Upload New Banner Photo</label>
            <div class="upload-dropzone">
              <input type="file" accept="image/*" onchange="window.FeatherAdmin.handleBannerFileUpload(event)" />
              <div class="upload-content">
                <div class="upload-icon-circle">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                  </svg>
                </div>
                <div class="upload-title">Drop banner image here, or <span>choose file</span></div>
                <div class="upload-hint">Recommended: 1920x800 or high resolution landscape photo</div>
              </div>
            </div>
            <input type="hidden" id="banner-img-url" value="${banner.image || ''}" />
          </div>

          <!-- Badge text -->
          <div class="form-group">
            <label for="banner-badge-input">Badge Headline</label>
            <input type="text" id="banner-badge-input" class="form-control" value="${banner.badge || ''}" oninput="window.FeatherAdmin.updateBannerPreviewField('badge', this.value)" />
          </div>

          <!-- Main Title with HTML -->
          <div class="form-group">
            <label for="banner-title-input">Hero Main Title (HTML supported for &lt;span&gt;)</label>
            <input type="text" id="banner-title-input" class="form-control" value="${banner.title || ''}" oninput="window.FeatherAdmin.updateBannerPreviewField('title', this.value)" />
          </div>

          <!-- Subtitle -->
          <div class="form-group">
            <label for="banner-sub-input">Hero Description / Subtitle</label>
            <textarea id="banner-sub-input" class="form-control" rows="2" oninput="window.FeatherAdmin.updateBannerPreviewField('desc', this.value)">${banner.subtitle || ''}</textarea>
          </div>

          <!-- CTA label -->
          <div class="form-group">
            <label for="banner-cta-input">Primary Button Label</label>
            <input type="text" id="banner-cta-input" class="form-control" value="${banner.primaryCta ? banner.primaryCta.label : 'Explore Fits'}" oninput="window.FeatherAdmin.updateBannerPreviewField('cta', this.value)" />
          </div>

          <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 0.5rem;">
            <button type="button" class="btn-primary-purple" onclick="window.FeatherAdmin.saveActiveBanner()">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
              </svg>
              <span>Publish Banner to Store</span>
            </button>
          </div>
        </div>
      </div>
    `;
  }

  // Real-time Banner Preview Updates as user types
  function updateBannerPreviewField(field, val) {
    if (field === "badge") {
      const el = document.getElementById("banner-mockup-badge");
      if (el) el.textContent = val;
    } else if (field === "title") {
      const el = document.getElementById("banner-mockup-title");
      if (el) el.innerHTML = val;
    } else if (field === "desc") {
      const el = document.getElementById("banner-mockup-desc");
      if (el) el.textContent = val;
    } else if (field === "cta") {
      const el = document.getElementById("banner-mockup-cta");
      if (el) el.innerHTML = `<span>${val}</span> <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>`;
    }
  }

  // Handle Banner Image File Upload via FileReader
  function handleBannerFileUpload(event) {
    const file = event.target.files && event.target.files[0];
    if (!file) return;

    if (!file.type.startsWith("image/")) {
      showToast("Please choose a valid image file (PNG, JPG, WebP).", "warning");
      return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
      const dataUrl = e.target.result;
      const mockupImg = document.getElementById("banner-mockup-img");
      const hiddenInput = document.getElementById("banner-img-url");

      if (mockupImg) mockupImg.src = dataUrl;
      if (hiddenInput) hiddenInput.value = dataUrl;

      showToast("Banner image loaded! Click 'Publish Banner' to save.", "success");
    };
    reader.readAsDataURL(file);
  }

  // Save Banner Changes
  function saveActiveBanner() {
    const dept = state.activeBannerTab;
    const imgUrl = document.getElementById("banner-img-url").value;
    const badge = document.getElementById("banner-badge-input").value.trim();
    const title = document.getElementById("banner-title-input").value.trim();
    const subtitle = document.getElementById("banner-sub-input").value.trim();
    const ctaLabel = document.getElementById("banner-cta-input").value.trim();

    if (!state.customBanners[dept]) {
      state.customBanners[dept] = {};
    }

    state.customBanners[dept].image = imgUrl;
    state.customBanners[dept].badge = badge;
    state.customBanners[dept].title = title;
    state.customBanners[dept].subtitle = subtitle;
    if (!state.customBanners[dept].primaryCta) state.customBanners[dept].primaryCta = {};
    state.customBanners[dept].primaryCta.label = ctaLabel;

    saveCustomBanners(dept);
    showToast(`${dept.toUpperCase()} Hero Banner successfully published to live store!`, "success");
  }

  function resetBannersToDefault() {
    if (confirm("Reset all hero banners back to original catalog defaults?")) {
      localStorage.removeItem(STORAGE_KEYS.CUSTOM_BANNERS);
      loadCustomBanners();
      renderBannerCustomizer();
      showToast("Banners reset to original defaults!", "warning");
    }
  }

  function saveAnnouncementBar() {
    const val = document.getElementById("announcement-text-input").value.trim();
    localStorage.setItem(STORAGE_KEYS.CUSTOM_ANNOUNCEMENT, val);
    localStorage.setItem("ff_sync_trigger", Date.now().toString());

    fetch('api/admin.php?action=save_announcement', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ announcement: val })
    }).catch(err => console.warn('Server sync error', err));

    showToast("Announcement bar updated across all pages!", "success");
  }

  function resetAnnouncementDefault() {
    localStorage.removeItem(STORAGE_KEYS.CUSTOM_ANNOUNCEMENT);
    renderBannerCustomizer();
    showToast("Announcement bar reset to default!", "warning");
  }

  // ==========================================================================
  // REAL PRODUCT IMAGE UPLOAD HANDLERS
  // ==========================================================================
  function handleProductFileUpload(event, mode) {
    const file = event.target.files && event.target.files[0];
    if (!file) return;

    if (!file.type.startsWith("image/")) {
      showToast("Please choose a valid image file.", "warning");
      return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
      const dataUrl = e.target.result;
      const previewBox = document.getElementById(`${mode}-prod-preview-box`);
      const previewImg = document.getElementById(`${mode}-prod-preview-img`);
      const previewName = document.getElementById(`${mode}-prod-preview-name`);
      const previewSize = document.getElementById(`${mode}-prod-preview-size`);
      const hiddenInput = document.getElementById(`${mode}-prod-img-data`);

      if (previewImg) previewImg.src = dataUrl;
      if (previewName) previewName.textContent = file.name;
      if (previewSize) previewSize.textContent = `${(file.size / 1024).toFixed(1)} KB (Uploaded File)`;
      if (previewBox) previewBox.style.display = "flex";
      if (hiddenInput) hiddenInput.value = dataUrl;

      showToast("Product image uploaded and ready!", "success");
    };
    reader.readAsDataURL(file);
  }

  function selectPresetImage(path, mode) {
    const previewBox = document.getElementById(`${mode}-prod-preview-box`);
    const previewImg = document.getElementById(`${mode}-prod-preview-img`);
    const previewName = document.getElementById(`${mode}-prod-preview-name`);
    const previewSize = document.getElementById(`${mode}-prod-preview-size`);
    const hiddenInput = document.getElementById(`${mode}-prod-img-data`);

    if (previewImg) previewImg.src = path;
    if (previewName) previewName.textContent = path.split("/").pop();
    if (previewSize) previewSize.textContent = "Store Preset";
    if (previewBox) previewBox.style.display = "flex";
    if (hiddenInput) hiddenInput.value = path;
  }

  function clearUploadedImage(mode) {
    const defaultPath = mode === "add" ? "assets/images/anime-tee-model-wine.jpg" : "";
    selectPresetImage(defaultPath, mode);
  }

  // Update Order Status
  function updateOrderStatus(orderId, newStatus) {
    const order = state.orders.find(o => (o.id === orderId || o.orderNumber === orderId));
    if (!order) return;

    order.status = newStatus;
    saveOrders();
    showToast(`Order #${orderId} marked as ${newStatus}!`);
  }

  // Delete Order
  function deleteOrder(orderId) {
    if (confirm(`Are you sure you want to delete order #${orderId}?`)) {
      state.orders = state.orders.filter(o => (o.id !== orderId && o.orderNumber !== orderId));
      saveOrders();
      showToast(`Order #${orderId} deleted successfully!`, "warning");
    }
  }

  // Open Edit Product Modal
  function openEditProductModal(productId) {
    const p = state.products.find(x => x.id === productId);
    if (!p) return;

    const idInput = document.getElementById("edit-prod-id");
    const nameInput = document.getElementById("edit-prod-name");
    const deptInput = document.getElementById("edit-prod-dept");
    const catInput = document.getElementById("edit-prod-category");
    const priceInput = document.getElementById("edit-prod-price");
    const origPriceInput = document.getElementById("edit-prod-orig-price");
    const badgeInput = document.getElementById("edit-prod-badge");
    const hiddenImg = document.getElementById("edit-prod-img-data");
    const previewBox = document.getElementById("edit-prod-preview-box");
    const previewImg = document.getElementById("edit-prod-preview-img");
    const previewName = document.getElementById("edit-prod-preview-name");

    if (idInput) idInput.value = p.id;
    if (nameInput) nameInput.value = p.name;
    if (deptInput) deptInput.value = p.department || "men";
    if (catInput) catInput.value = p.category || "men-tshirts";
    if (priceInput) priceInput.value = p.basePrice;
    if (origPriceInput) origPriceInput.value = p.originalPrice || "";
    if (badgeInput) badgeInput.value = p.badge || "";

    const currentImg = (p.images && p.images[0]) ? p.images[0] : "assets/images/anime-tee.jpg";
    if (hiddenImg) hiddenImg.value = currentImg;
    if (previewImg) previewImg.src = currentImg;
    if (previewName) previewName.textContent = p.name + " Image";
    if (previewBox) previewBox.style.display = "flex";

    openModal("edit-product-modal");
  }

  // Handle Edit Product Form Submit
  function handleEditProductSubmit(e) {
    e.preventDefault();
    const id = document.getElementById("edit-prod-id").value;
    const name = document.getElementById("edit-prod-name").value.trim();
    const dept = document.getElementById("edit-prod-dept").value;
    const catSelect = document.getElementById("edit-prod-category");
    const price = Number(document.getElementById("edit-prod-price").value);
    const origPrice = Number(document.getElementById("edit-prod-orig-price").value) || null;
    const badge = document.getElementById("edit-prod-badge").value.trim();
    const image = document.getElementById("edit-prod-img-data").value;

    const prod = state.products.find(x => x.id === id);
    if (!prod) return;

    prod.name = name;
    prod.department = dept;
    prod.category = catSelect.value;
    prod.categoryName = catSelect.options[catSelect.selectedIndex].text;
    prod.basePrice = price;
    prod.originalPrice = origPrice;
    prod.badge = badge;
    if (image) prod.images = [image];

    saveProducts(prod);
    closeModal("edit-product-modal");
    showToast(`Updated "${name}" details successfully!`);
  }

  // Add New Product
  function handleAddProductSubmit(e) {
    e.preventDefault();
    const nameInput = document.getElementById("add-prod-name");
    const deptInput = document.getElementById("add-prod-dept");
    const catInput = document.getElementById("add-prod-category");
    const priceInput = document.getElementById("add-prod-price");
    const origPriceInput = document.getElementById("add-prod-orig-price");
    const imgDataInput = document.getElementById("add-prod-img-data");
    const badgeInput = document.getElementById("add-prod-badge");

    const dept = deptInput.value;
    const name = nameInput.value.trim();
    const price = Number(priceInput.value);
    const origPrice = Number(origPriceInput.value) || Math.round(price * 1.35);

    if (!name || isNaN(price) || price <= 0) {
      showToast("Please enter a valid product name and price.", "warning");
      return;
    }

    const newId = `ff-${dept}-${Date.now().toString().slice(-4)}`;
    const imageSrc = (imgDataInput && imgDataInput.value) ? imgDataInput.value : "assets/images/anime-tee-model-wine.jpg";

    const newProduct = {
      id: newId,
      name: name,
      department: dept,
      category: catInput.value || `${dept}-tshirts`,
      categoryName: catInput.options[catInput.selectedIndex].text,
      badge: badgeInput.value || "⚡ NEW ARRIVAL",
      basePrice: price,
      originalPrice: origPrice,
      rating: 4.9,
      reviewCount: 12,
      images: [imageSrc],
      colors: [{ name: "Obsidian Black", hex: "#1A1A1A" }],
      sizes: ["S", "M", "L", "XL", "XXL"],
      description: "Crafted with 100% pre-shrunk combed cotton for maximum streetwear durability and comfort.",
      features: ["240 GSM Heavy Cotton", "Bio-washed", "Pre-shrunk"]
    };

    state.products.unshift(newProduct);
    saveProducts(newProduct);
    closeModal("add-product-modal");
    showToast(`Added "${name}" to store catalog!`);
    e.target.reset();

    const previewBox = document.getElementById("add-prod-preview-box");
    if (previewBox) previewBox.style.display = "none";
  }

  // Delete product
  function deleteProduct(productId) {
    const prod = state.products.find(p => p.id === productId);
    if (!prod) return;

    if (confirm(`Are you sure you want to delete "${prod.name}" from the store catalog? This action cannot be undone.`)) {
      state.products = state.products.filter(p => p.id !== productId);
      saveProducts(prod, true);
      showToast(`Deleted "${prod.name}" from catalog.`, "warning");
    }
  }

  // View order details
  function viewOrderDetails(orderId) {
    const order = state.orders.find(o => (o.id === orderId || o.orderNumber === orderId));
    if (!order) return;

    const modalBody = document.getElementById("order-details-modal-body");
    const modalTitle = document.getElementById("order-details-modal-title");
    if (!modalBody) return;

    if (modalTitle) modalTitle.textContent = `Order Details — #${order.id || order.orderNumber}`;

    const itemsHtml = order.items ? order.items.map(i => `
      <div style="display: flex; justify-content: space-between; padding: 0.65rem 0; border-bottom: 1px solid var(--admin-border);">
        <div>
          <div style="font-weight: 700; color: var(--admin-black);">${i.name}</div>
          <span style="font-size: 0.78rem; color: var(--admin-text-faint);">Size: ${i.size || 'L'} &bull; Qty: ${i.qty || 1}</span>
        </div>
        <div style="font-weight: 800; color: var(--admin-purple-primary);">₹${((i.price || 1299) * (i.qty || 1)).toLocaleString("en-IN")}</div>
      </div>
    `).join("") : "<p>No item details</p>";

    modalBody.innerHTML = `
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
        <div style="background: var(--admin-surface-light); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--admin-border);">
          <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--admin-text-faint); font-weight: 700;">Customer Info</span>
          <h4 style="color: var(--admin-black); margin-top: 0.25rem;">${order.customer?.name || order.shippingAddress?.firstName || "Valued Customer"}</h4>
          <p style="font-size: 0.82rem; color: var(--admin-text-muted);">${order.customer?.email || "customer@featherfits.com"}</p>
          <p style="font-size: 0.82rem; color: var(--admin-text-muted);">${order.customer?.phone || "+91 98765 43210"}</p>
        </div>
        <div style="background: var(--admin-surface-light); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--admin-border);">
          <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--admin-text-faint); font-weight: 700;">Shipping Status</span>
          <div style="margin-top: 0.25rem;"><span class="badge badge-purple">${order.status}</span></div>
          <p style="font-size: 0.82rem; color: var(--admin-text-muted); margin-top: 0.35rem;">Carrier: ${order.carrier || "Blue Dart"}</p>
          <p style="font-size: 0.82rem; color: var(--admin-text-muted);">Tracking: <span style="font-family: monospace; color: var(--admin-purple-primary);">${order.trackingNumber || "BD-9402849182"}</span></p>
        </div>
      </div>

      <h4 style="font-size: 0.95rem; font-weight: 800; color: var(--admin-black); margin-bottom: 0.5rem;">Ordered Fits</h4>
      <div style="margin-bottom: 1.25rem;">
        ${itemsHtml}
      </div>

      <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 0.75rem; border-top: 2px dashed var(--admin-border);">
        <span style="font-weight: 800; color: var(--admin-black); font-size: 1.1rem;">Total Amount Paid</span>
        <span style="font-weight: 800; color: var(--admin-purple-primary); font-size: 1.3rem;">₹${Number(order.total).toLocaleString("en-IN")}</span>
      </div>
    `;

    openModal("order-details-modal");
  }

  // Switch sections
  function setSection(sectionId) {
    state.activeSection = sectionId;

    // Sidebar desktop links
    document.querySelectorAll(".admin-nav-link[data-section]").forEach(link => {
      const match = link.getAttribute("data-section") === sectionId;
      link.classList.toggle("active", match);
    });

    // Mobile bottom nav buttons
    document.querySelectorAll(".admin-mobile-nav-item[data-admin-nav]").forEach(btn => {
      const match = btn.getAttribute("data-admin-nav") === sectionId;
      btn.classList.toggle("active", match);
    });

    document.querySelectorAll(".admin-section").forEach(sec => {
      sec.classList.remove("active");
    });
    const target = document.getElementById(`section-${sectionId}`);
    if (target) target.classList.add("active");

    closeMobileSidebar();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // Filter products by department
  function setProductDeptFilter(dept) {
    state.productDeptFilter = dept;
    document.querySelectorAll(".filter-btn[data-dept-filter]").forEach(btn => {
      btn.classList.toggle("active", btn.getAttribute("data-dept-filter") === dept);
    });
    renderProductsTable();
  }

  // Filter orders by status
  function setOrderStatusFilter(status) {
    state.orderStatusFilter = status;
    document.querySelectorAll(".filter-btn[data-order-filter]").forEach(btn => {
      btn.classList.toggle("active", btn.getAttribute("data-order-filter") === status);
    });
    renderOrdersFullTable();
  }

  // Global search input
  function handleGlobalSearch(query) {
    state.globalSearchQuery = query.trim();
    renderProductsTable();
    renderOrdersFullTable();
  }

  // Toast Notification
  function showToast(message, type = "success") {
    let container = document.getElementById("admin-toast-container");
    if (!container) {
      container = document.createElement("div");
      container.id = "admin-toast-container";
      container.className = "admin-toast-container";
      document.body.appendChild(container);
    }

    const toast = document.createElement("div");
    toast.className = `admin-toast`;
    toast.innerHTML = `
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${type === 'warning' ? '#DC2626' : '#7C3AED'}" stroke-width="2.5">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
        <polyline points="22 4 12 14.01 9 11.01"></polyline>
      </svg>
      <span>${message}</span>
    `;
    container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = "0";
      toast.style.transform = "translateY(10px)";
      toast.style.transition = "all 0.25s ease";
      setTimeout(() => toast.remove(), 250);
    }, 3200);
  }

  // Modal helpers
  function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add("active");
  }

  function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.remove("active");
  }

  function toggleMobileSidebar() {
    const sidebar = document.getElementById("admin-sidebar");
    const backdrop = document.getElementById("sidebar-backdrop");
    if (!sidebar) return;

    const isOpen = sidebar.classList.toggle("mobile-open");
    if (backdrop) backdrop.classList.toggle("active", isOpen);
  }

  function closeMobileSidebar() {
    const sidebar = document.getElementById("admin-sidebar");
    const backdrop = document.getElementById("sidebar-backdrop");
    if (sidebar) sidebar.classList.remove("mobile-open");
    if (backdrop) backdrop.classList.remove("active");
  }

  // Event Listeners
  function attachEventListeners() {
    const addProductForm = document.getElementById("add-product-form");
    if (addProductForm) {
      addProductForm.addEventListener("submit", handleAddProductSubmit);
    }

    const editProductForm = document.getElementById("edit-product-form");
    if (editProductForm) {
      editProductForm.addEventListener("submit", handleEditProductSubmit);
    }

    const searchInput = document.getElementById("admin-search-input");
    if (searchInput) {
      searchInput.addEventListener("input", (e) => handleGlobalSearch(e.target.value));
    }

    document.querySelectorAll(".admin-modal-overlay").forEach(overlay => {
      overlay.addEventListener("click", (e) => {
        if (e.target === overlay) {
          overlay.classList.remove("active");
        }
      });
    });
  }

  
  // Active Order being edited in shipping modal
  let currentShippingOrderId = null;

  function openShippingModal(orderId) {
    const order = state.orders.find(o => (o.id === orderId || o.orderNumber === orderId));
    if (!order) {
      showToast('Order not found.', 'danger');
      return;
    }
    currentShippingOrderId = orderId;

    const titleEl = document.getElementById('ship-modal-order-id');
    const carrierEl = document.getElementById('ship-carrier-select');
    const trackingEl = document.getElementById('ship-tracking-input');
    const estEl = document.getElementById('ship-est-delivery');
    const statusEl = document.getElementById('ship-update-status');
    const quicklinkEl = document.getElementById('ship-quicklink-preview');
    const actionsBar = document.getElementById('ship-actions-bar');
    const trackBtn = document.getElementById('btn-track-parcel');
    const waBtn = document.getElementById('btn-whatsapp-notify');
    const timelineEl = document.getElementById('ship-modal-timeline');

    if (titleEl) titleEl.textContent = `Order #${orderId} — ${order.customer?.name || 'Customer'}`;
    if (carrierEl) carrierEl.value = order.carrier || 'Blue Dart Express';
    if (trackingEl) trackingEl.value = (order.trackingNumber && order.trackingNumber !== 'Pending') ? order.trackingNumber : '';
    if (estEl) estEl.value = order.estimatedDelivery || '';
    if (statusEl) statusEl.value = order.status || 'Shipped';

    function updateModalQuicklink() {
      const carrier = carrierEl ? carrierEl.value : '';
      const tracking = trackingEl ? trackingEl.value.trim() : '';
      if (tracking) {
        const link = buildCarrierTrackingUrl(carrier, tracking);
        if (quicklinkEl) quicklinkEl.value = link;
        if (actionsBar) actionsBar.style.display = 'flex';
        if (trackBtn) trackBtn.href = link;

        const custPhone = order.customer?.phone || '+91 98765 43210';
        const cleanPhoneDigits = custPhone.replace(/[^0-9]/g, '');
        const cleanPhone10 = cleanPhoneDigits.length >= 10 ? cleanPhoneDigits.slice(-10) : cleanPhoneDigits;
        const custName = order.customer?.name || 'Customer';
        const publicTrackUrl = window.location.origin + '/track.php?order=' + encodeURIComponent(orderId) + '&phone=' + cleanPhone10;
        const waMsg = `Hi ${custName}, unga order #${orderId} ship aayiduchu! 🚀\nCarrier: ${carrier}\nAWB Tracking: ${tracking}\nTrack live parcel here: ${publicTrackUrl}\nEstimated Delivery: ${estEl ? estEl.value : 'In 2-3 Days'}\n\nFeather Fits support team.`;
        if (waBtn) waBtn.href = `https://wa.me/91${cleanPhone10}?text=${encodeURIComponent(waMsg)}`;
      } else {
        if (quicklinkEl) quicklinkEl.value = '';
        if (actionsBar) actionsBar.style.display = 'none';
      }
    }

    if (carrierEl) carrierEl.onchange = updateModalQuicklink;
    if (trackingEl) trackingEl.oninput = updateModalQuicklink;
    updateModalQuicklink();

    // Render Timeline History
    if (timelineEl) {
      const timeline = order.timeline && Array.isArray(order.timeline) ? order.timeline : [
        { status: 'Order Placed', time: order.date || '2026-10-27 09:15 AM', note: 'Payment verified via Razorpay / UPI', updatedBy: 'System' },
        { status: 'Packed', time: '2026-10-27 12:45 PM', note: 'Passed luxury QA check', updatedBy: 'Warehouse' },
        { status: order.status || 'Shipped', time: 'Today', note: `Status: ${order.status}`, updatedBy: 'Admin' }
      ];

      timelineEl.innerHTML = timeline.map((step, idx) => `
        <div class="timeline-step">
          <div class="timeline-dot"></div>
          <div class="timeline-content">
            <div style="display: flex; justify-content: space-between; align-items: baseline; gap: 0.5rem; flex-wrap: wrap;">
              <strong style="color: #3B0F4A; font-size: 0.88rem;">${step.status}</strong>
              <span style="font-size: 0.72rem; color: #724E79;">${step.time}</span>
            </div>
            <div style="font-size: 0.8rem; color: #724E79; margin-top: 0.2rem;">${step.note || ''}</div>
            <div style="font-size: 0.7rem; color: #B5258F; margin-top: 0.15rem; font-weight: 700;">By ${step.updatedBy || 'Staff'}</div>
          </div>
        </div>
      `).join('');
    }

    // Attach form submit handler
    const form = document.getElementById('order-shipping-form');
    if (form) {
      form.onsubmit = function(e) {
        e.preventDefault();
        const carrier = carrierEl.value;
        const tracking = trackingEl.value.trim();
        const estDelivery = estEl.value;
        const newStatus = statusEl.value;
        const saveBtn = document.getElementById('ship-save-btn');

        if (!tracking) {
          showToast('Please enter a tracking number.', 'danger');
          return;
        }

        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span>Saving & Marking as Shipped...</span>';

        fetch('api/shipping.php?action=update_shipping', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            orderId: currentShippingOrderId,
            carrier: carrier,
            trackingNumber: tracking,
            estimatedDelivery: estDelivery,
            status: newStatus
          })
        })
        .then(res => res.json())
        .then(data => {
          saveBtn.disabled = false;
          saveBtn.innerHTML = '<span>💾 Save & Mark as Shipped</span>';
          if (data.success) {
            showToast(`Order #${currentShippingOrderId} updated to ${newStatus}!`, 'success');
            loadOrders();
            updateModalQuicklink();
            closeModal('order-shipping-modal');
          } else {
            showToast(data.error || 'Failed to update shipping.', 'danger');
          }
        })
        .catch(err => {
          saveBtn.disabled = false;
          saveBtn.innerHTML = '<span>💾 Save & Mark as Shipped</span>';
          showToast('Network error while saving tracking.', 'danger');
        });
      };
    }

    openModal('order-shipping-modal');
  }

  function markAsDelivered(orderId) {
    if (!confirm(`Are you sure you want to mark Order #${orderId} as DELIVERED?`)) return;

    fetch('api/shipping.php?action=update_status', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        orderId: orderId,
        status: 'Delivered',
        note: 'Customer delivered confirmation (Marked via Admin Dashboard)'
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        showToast(`Order #${orderId} marked as DELIVERED! ✓`, 'success');
        loadOrders();
      } else {
        showToast(data.error || 'Failed to update status.', 'danger');
      }
    })
    .catch(() => showToast('Network error updating status.', 'danger'));
  }

  function copyCustomerTrackingLink() {
    if (!currentShippingOrderId) return;
    const order = state.orders.find(o => o.id === currentShippingOrderId || o.orderNumber === currentShippingOrderId);
    const custPhone = order?.customer?.phone || '9876543210';
    const cleanDigits = custPhone.replace(/[^0-9]/g, '');
    const clean10 = cleanDigits.length >= 10 ? cleanDigits.slice(-10) : cleanDigits;
    const url = window.location.origin + '/track.php?order=' + encodeURIComponent(currentShippingOrderId) + '&phone=' + clean10;

    navigator.clipboard.writeText(url).then(() => {
      showToast('Public tracking link copied to clipboard! 📋', 'success');
    }).catch(() => {
      showToast('Copied: ' + url, 'info');
    });
  }

  function processBulkCsv() {
    const input = document.getElementById('bulk-csv-input');
    const resultEl = document.getElementById('bulk-csv-result');
    const btn = document.getElementById('btn-process-csv');
    if (!input || !input.value.trim()) {
      showToast('Please paste CSV rows to process.', 'danger');
      return;
    }

    btn.disabled = true;
    btn.textContent = 'Processing CSV...';

    fetch('api/shipping.php?action=bulk_upload_csv', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ csv: input.value.trim() })
    })
    .then(res => res.json())
    .then(data => {
      btn.disabled = false;
      btn.textContent = 'Apply Bulk Updates →';
      if (data.success) {
        resultEl.style.display = 'block';
        resultEl.style.background = '#ECFDF5';
        resultEl.style.color = '#065F46';
        resultEl.innerHTML = `✓ ${data.message}`;
        showToast(`Successfully updated ${data.updatedCount} orders!`, 'success');
        loadOrders();
        setTimeout(() => {
          closeModal('bulk-csv-modal');
          resultEl.style.display = 'none';
          input.value = '';
        }, 1600);
      } else {
        resultEl.style.display = 'block';
        resultEl.style.background = '#FEF2F2';
        resultEl.style.color = '#991B1B';
        resultEl.textContent = data.error || 'Failed to process CSV.';
      }
    })
    .catch(err => {
      btn.disabled = false;
      btn.textContent = 'Apply Bulk Updates →';
      showToast('Error uploading CSV data.', 'danger');
    });
  }

  
  function handleCreateAdminSubmit(e) {
    e.preventDefault();
    const name = document.getElementById('new-adm-name').value.trim();
    const email = document.getElementById('new-adm-email').value.trim();
    const username = document.getElementById('new-adm-user').value.trim();
    const role = document.getElementById('new-adm-role').value;
    const password = document.getElementById('new-adm-pass').value;
    const alertEl = document.getElementById('create-admin-alert');
    const btn = document.getElementById('btn-save-new-admin');

    btn.disabled = true;
    btn.textContent = 'Creating Administrator...';

    fetch('api/auth.php?action=create_admin', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ name, email, username, role, password })
    })
    .then(res => res.json())
    .then(data => {
      btn.disabled = false;
      btn.textContent = 'Create Administrator →';
      if (data.success) {
        showToast(data.message, 'success');
        document.getElementById('create-admin-form').reset();
        closeModal('add-admin-modal');
      } else {
        alertEl.style.display = 'block';
        alertEl.style.background = '#FEF2F2';
        alertEl.style.color = '#991B1B';
        alertEl.textContent = data.error || 'Failed to create admin.';
      }
    })
    .catch(() => {
      btn.disabled = false;
      btn.textContent = 'Create Administrator →';
      showToast('Network error creating administrator.', 'danger');
    });
  }

  // Expose Global Admin API
  window.FeatherAdmin = {
    logout: function() {
      if (confirm('Are you sure you want to end your Super Admin session?')) {
        fetch('api/auth.php?action=admin_logout')
          .then(() => window.location.reload());
      }
    },
    setSection,
    setProductDeptFilter,
    setOrderStatusFilter,
    handleGlobalSearch,
    updateOrderStatus,
    deleteOrder,
    openEditProductModal,
    deleteProduct,
    viewOrderDetails,
    openModal,
    closeModal,
    toggleMobileSidebar,
    closeMobileSidebar,
    showToast,
    handleCreateAdminSubmit,
    openShippingModal,
    markAsDelivered,
    copyCustomerTrackingLink,
    processBulkCsv,
    // Advanced Image & Banner CMS methods
    setBannerTab,
    updateBannerPreviewField,
    handleBannerFileUpload,
    saveActiveBanner,
    resetBannersToDefault,
    saveAnnouncementBar,
    resetAnnouncementDefault,
    handleProductFileUpload,
    selectPresetImage,
    clearUploadedImage
  };

  // Run on DOM ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAdmin);
  } else {
    initAdmin();
  }
})();
