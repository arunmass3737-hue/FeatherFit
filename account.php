<?php
$pageTitle = "My Account & Orders | Feather Fits";
$pageDescription = "Manage your Feather Fits orders, live parcel tracking, saved delivery addresses, and VIP membership perks.";
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';

$activeTab = $_GET['tab'] ?? 'orders';
$searchOrder = $_GET['order'] ?? '';
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    
    <!-- User Overview Card -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-white/10 mb-10 flex flex-col sm:flex-row items-center justify-between gap-6" data-aos="fade-down">
        <div class="flex items-center gap-4 text-center sm:text-left">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-600 flex items-center justify-center text-white text-2xl font-black font-cinzel shadow-glow-sm">
                AK
            </div>
            <div>
                <div class="flex items-center gap-2 justify-center sm:justify-start">
                    <h1 class="text-xl sm:text-2xl font-black font-cinzel text-white">Arun Kumar</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-purple-600/30 text-purple-300 border border-purple-500/40">VIP Tier</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">arun.kumar@featherfits.com • Member since 2024 • 450 Feather Coins</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-950/40 border border-emerald-800/40 text-emerald-400 text-xs font-bold">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                Verified VIP Customer
            </span>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-white/10 pb-4 mb-8 overflow-x-auto text-xs font-bold uppercase tracking-wider">
        <button type="button" class="tab-btn px-4 py-2 rounded-xl transition flex items-center gap-2 <?= ($activeTab === 'orders') ? 'bg-purple-600 text-white shadow-glow-sm' : 'text-gray-400 hover:text-white hover:bg-white/5' ?>" onclick="switchAccountTab('orders', this)">
            <i data-lucide="package" class="w-4 h-4"></i>
            <span>Order History</span>
        </button>
        <button type="button" class="tab-btn px-4 py-2 rounded-xl transition flex items-center gap-2 <?= ($activeTab === 'tracking') ? 'bg-purple-600 text-white shadow-glow-sm' : 'text-gray-400 hover:text-white hover:bg-white/5' ?>" onclick="switchAccountTab('tracking', this)">
            <i data-lucide="truck" class="w-4 h-4"></i>
            <span>Live Tracking</span>
        </button>
        <button type="button" class="tab-btn px-4 py-2 rounded-xl transition flex items-center gap-2 <?= ($activeTab === 'addresses') ? 'bg-purple-600 text-white shadow-glow-sm' : 'text-gray-400 hover:text-white hover:bg-white/5' ?>" onclick="switchAccountTab('addresses', this)">
            <i data-lucide="map-pin" class="w-4 h-4"></i>
            <span>Addresses</span>
        </button>
        <button type="button" class="tab-btn px-4 py-2 rounded-xl transition flex items-center gap-2 <?= ($activeTab === 'fit') ? 'bg-purple-600 text-white shadow-glow-sm' : 'text-gray-400 hover:text-white hover:bg-white/5' ?>" onclick="switchAccountTab('fit', this)">
            <i data-lucide="ruler" class="w-4 h-4"></i>
            <span>Fit Preference</span>
        </button>
    </div>

    <!-- Tab 1: Orders -->
    <div id="tab-content-orders" class="tab-pane <?= ($activeTab === 'orders') ? '' : 'hidden' ?>" data-aos="fade-up">
        <div id="orders-container" class="space-y-4">
            <!-- Dynamically populated or default orders -->
        </div>
    </div>

    <!-- Tab 2: Live Tracking Timeline -->
    <div id="tab-content-tracking" class="tab-pane <?= ($activeTab === 'tracking') ? '' : 'hidden' ?>" data-aos="fade-up">
        <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-white/10 max-w-3xl mx-auto space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/10">
                <div>
                    <h3 class="text-lg font-bold font-cinzel text-white">Live Logistics Tracker</h3>
                    <p class="text-xs text-gray-400">Official Blue Dart Air Express integration</p>
                </div>
                <div class="flex gap-2">
                    <input type="text" id="track-order-num" value="<?= htmlspecialchars($searchOrder ?: 'FF-892104') ?>" placeholder="e.g. FF-892104" class="bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white uppercase focus:outline-none focus:border-purple-500" />
                    <button type="button" onclick="searchTracking()" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 rounded-xl text-xs font-bold uppercase text-white shadow-glow-sm transition">Track</button>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/5 flex items-center justify-between">
                <div>
                    <span class="text-[10px] uppercase font-bold text-gray-400">Order ID: <span id="track-id-label" class="text-purple-300">FF-892104</span></span>
                    <h4 class="text-sm font-bold text-white mt-0.5">Sukuna Domain Expansion + Gojo Satoru Boxy Tees</h4>
                </div>
                <span class="px-3 py-1 bg-purple-600/20 text-purple-300 border border-purple-500/30 rounded-full text-xs font-bold">In Transit</span>
            </div>

            <!-- Milestone Progress Steps -->
            <div class="relative pl-6 sm:pl-8 space-y-8 before:absolute before:left-2 sm:before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-gradient-to-b before:from-purple-500 before:via-purple-500 before:to-gray-700">
                <div class="relative flex items-start gap-3">
                    <div class="absolute -left-6 sm:-left-8 mt-1 w-4 h-4 rounded-full bg-purple-500 ring-4 ring-purple-950 flex items-center justify-center"></div>
                    <div>
                        <h5 class="text-xs font-bold text-white">Order Confirmed & Payment Authorized</h5>
                        <p class="text-[11px] text-gray-400">Feather Warehouse Chennai • Oct 27, 10:30 AM</p>
                    </div>
                </div>

                <div class="relative flex items-start gap-3">
                    <div class="absolute -left-6 sm:-left-8 mt-1 w-4 h-4 rounded-full bg-purple-500 ring-4 ring-purple-950 flex items-center justify-center"></div>
                    <div>
                        <h5 class="text-xs font-bold text-white">Quality Checked & Vacuum Sealed</h5>
                        <p class="text-[11px] text-gray-400">240 GSM Combed Cotton Sanitized • Oct 27, 03:15 PM</p>
                    </div>
                </div>

                <div class="relative flex items-start gap-3">
                    <div class="absolute -left-6 sm:-left-8 mt-1 w-4 h-4 rounded-full bg-purple-500 ring-4 ring-purple-950 flex items-center justify-center animate-pulse"></div>
                    <div>
                        <h5 class="text-xs font-bold text-purple-300">Out for Local Delivery (Arriving Today)</h5>
                        <p class="text-[11px] text-gray-400">Blue Dart Van #TN09-4412 • Expected before 6:00 PM</p>
                    </div>
                </div>

                <div class="relative flex items-start gap-3 opacity-40">
                    <div class="absolute -left-6 sm:-left-8 mt-1 w-4 h-4 rounded-full bg-gray-600 flex items-center justify-center"></div>
                    <div>
                        <h5 class="text-xs font-bold text-gray-300">Package Delivered to Handover</h5>
                        <p class="text-[11px] text-gray-500">OTP confirmation required at doorstep</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 3: Saved Addresses -->
    <div id="tab-content-addresses" class="tab-pane <?= ($activeTab === 'addresses') ? '' : 'hidden' ?>" data-aos="fade-up">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-3xl">
            <div class="glass-panel p-6 rounded-2xl border border-purple-500/40 bg-purple-950/10 relative">
                <span class="absolute top-4 right-4 text-[10px] font-bold text-purple-400 bg-purple-500/20 px-2 py-0.5 rounded-full border border-purple-500/30">DEFAULT</span>
                <span class="text-xs font-bold uppercase tracking-wider text-purple-300 block mb-1">🏠 Home</span>
                <h4 class="text-sm font-bold text-white">Arun Kumar (+91 98765 43210)</h4>
                <p class="text-xs text-gray-300 mt-1 leading-relaxed">42, Gandhi Road, T. Nagar, Chennai, Tamil Nadu — 600017</p>
            </div>

            <div class="glass-panel p-6 rounded-2xl border border-white/10">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400 block mb-1">💼 Office</span>
                <h4 class="text-sm font-bold text-white">Arun Kumar (+91 98765 43210)</h4>
                <p class="text-xs text-gray-300 mt-1 leading-relaxed">Unit 402, RMZ Millenia Business Park, Perungudi, Chennai — 600096</p>
            </div>
        </div>
    </div>

    <!-- Tab 4: Fit Preferences -->
    <div id="tab-content-fit" class="tab-pane <?= ($activeTab === 'fit') ? '' : 'hidden' ?>" data-aos="fade-up">
        <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-white/10 max-w-xl space-y-4">
            <h3 class="text-base font-bold font-cinzel text-white">Your Tailored Fit Profile</h3>
            <p class="text-xs text-gray-400">Save your preferred streetwear sizing so product pages automatically preselect your perfect fit.</p>
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Preferred Size</label>
                <div class="flex gap-2">
                    <button class="px-4 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold shadow-glow-sm">L (Chest 42")</button>
                    <button class="px-4 py-2 rounded-xl bg-white/5 text-gray-400 text-xs font-bold hover:text-white">XL (Chest 44")</button>
                </div>
            </div>

            <div class="pt-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Aesthetic Style</label>
                <div class="flex gap-2">
                    <button class="px-4 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold shadow-glow-sm">Boxy Oversized</button>
                    <button class="px-4 py-2 rounded-xl bg-white/5 text-gray-400 text-xs font-bold hover:text-white">Standard Regular</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function switchAccountTab(tab, btn) {
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('bg-purple-600', 'text-white', 'shadow-glow-sm');
            b.classList.add('text-gray-400');
        });
        btn.classList.add('bg-purple-600', 'text-white', 'shadow-glow-sm');
        btn.classList.remove('text-gray-400');

        document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
        const pane = document.getElementById('tab-content-' + tab);
        if (pane) pane.classList.remove('hidden');
    }

    function loadAccountOrders() {
        const container = document.getElementById('orders-container');
        if (!container) return;

        let orders = [];
        try {
            orders = JSON.parse(localStorage.getItem('feather_orders') || '[]');
        } catch(e){}

        if (orders.length === 0) {
            orders = [
                {
                    id: 'FF-892104',
                    date: '26 Oct 2026',
                    total: '₹2,648',
                    status: 'In Transit',
                    paymentMethod: 'Instant UPI (Verified)',
                    items: [
                        { name: 'Sukuna Domain Expansion Heavyweight Tee', size: 'L', quantity: 1, price: 1299, image: 'assets/images/anime-front-black.png' },
                        { name: 'Gojo Satoru Hollow Purple Boxy Fit Tee', size: 'L', quantity: 1, price: 1349, image: 'assets/images/movie-vintage-godfather.png' }
                    ]
                }
            ];
        }

        let html = '';
        orders.forEach(o => {
            html += `
                <div class="glass-panel p-6 rounded-2xl border border-white/10 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-white/10">
                        <div>
                            <span class="text-xs uppercase font-extrabold text-purple-300">Order #${o.id}</span>
                            <span class="text-xs text-gray-400 ml-2">Placed on ${o.date}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 bg-purple-600/20 text-purple-300 border border-purple-500/30 rounded-full text-xs font-bold">${o.status || 'Processing'}</span>
                            <span class="text-sm font-bold text-white">${o.total}</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        ${(o.items || []).map(it => `
                            <div class="flex items-center gap-3">
                                <img src="${it.image || 'assets/hero-bg.webp'}" class="w-12 h-14 object-cover rounded-lg bg-black" />
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-xs font-bold text-white truncate">${it.name}</h5>
                                    <span class="text-[11px] text-gray-400">Size: ${it.size || 'L'} | Qty: ${it.quantity || 1}</span>
                                </div>
                                <span class="text-xs font-bold text-purple-300">₹${Number(it.price || 999).toLocaleString('en-IN')}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
        if (window.lucide) lucide.createIcons();
    }

    function searchTracking() {
        const val = document.getElementById('track-order-num').value.trim();
        if (val) {
            document.getElementById('track-id-label').innerText = val;
            showToast(`Tracking status refreshed for #${val}`);
        }
    }

    document.addEventListener('DOMContentLoaded', loadAccountOrders);
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
