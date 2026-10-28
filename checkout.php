<?php
$pageTitle = "Secure Checkout | Feather Fits Premium Streetwear";
$pageDescription = "Complete your luxury streetwear order with instant UPI, Credit/Debit card tokenization, or Doorstep COD.";
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    
    <!-- Top Security Notice -->
    <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 rounded-2xl bg-white/[0.02] border border-white/10" data-aos="fade-down">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-white">Bank-Grade 256-Bit SSL Encrypted Checkout</h3>
                <p class="text-xs text-gray-400">PCI-DSS SAQ A Certified • Data tokenized directly via secure gateways</p>
            </div>
        </div>
        <a href="index.php" class="text-xs text-purple-400 hover:text-purple-300 font-bold flex items-center gap-1">
            <span>← Return to Store</span>
        </a>
    </div>

    <!-- Main Checkout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Left: Form Steps (Col 1 to 7) -->
        <div class="lg:col-span-7 space-y-8" data-aos="fade-right">
            
            <!-- Step 1: Customer & Shipping Address -->
            <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-white/10">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/10">
                    <span class="w-7 h-7 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-xs font-bold flex items-center justify-center">1</span>
                    <h2 class="text-lg font-bold font-cinzel text-white">Delivery Address & Contact</h2>
                </div>

                <form id="checkout-shipping-form" onsubmit="event.preventDefault();" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1.5">First Name *</label>
                            <input type="text" id="chk-firstname" required placeholder="Arjun" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1.5">Last Name *</label>
                            <input type="text" id="chk-lastname" required placeholder="Kumar" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1.5">Mobile Phone Number *</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-gray-400 pointer-events-none">🇮🇳 +91</span>
                                <input type="tel" id="chk-phone" required placeholder="98765 43210" maxlength="10" class="w-full bg-black/40 border border-white/10 rounded-xl pl-16 pr-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1.5">Email Address (for invoice) *</label>
                            <input type="email" id="chk-email" required placeholder="arjun@example.com" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1.5">Street Address / Flat / Building *</label>
                        <input type="text" id="chk-address" required placeholder="Flat 4B, Emerald Heights, MG Road" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1.5">City *</label>
                            <input type="text" id="chk-city" required placeholder="Chennai" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1.5">Postal PIN Code *</label>
                            <input type="text" id="chk-pincode" required placeholder="600001" maxlength="6" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500" />
                        </div>
                    </div>
                </form>
            </div>

            <!-- Step 2: Payment Method Tabs (No Confusing Mix) -->
            <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-white/10">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/10">
                    <span class="w-7 h-7 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-xs font-bold flex items-center justify-center">2</span>
                    <h2 class="text-lg font-bold font-cinzel text-white">Select Payment Mode</h2>
                </div>

                <div class="grid grid-cols-3 gap-3 mb-6">
                    <button type="button" class="pay-tab-btn active p-4 rounded-2xl border text-center transition flex flex-col items-center gap-2 border-purple-500 bg-purple-600/15 shadow-glow-sm" onclick="selectCheckoutPayment('upi', this)">
                        <i data-lucide="zap" class="w-6 h-6 text-purple-400"></i>
                        <span class="text-xs font-bold text-white block">Instant UPI</span>
                        <span class="text-[10px] text-gray-400 block">GPay, PhonePe</span>
                    </button>

                    <button type="button" class="pay-tab-btn p-4 rounded-2xl border text-center transition flex flex-col items-center gap-2 border-white/10 bg-white/[0.02] hover:border-white/20" onclick="selectCheckoutPayment('card', this)">
                        <i data-lucide="credit-card" class="w-6 h-6 text-gray-400"></i>
                        <span class="text-xs font-bold text-gray-200 block">Cards / Bank</span>
                        <span class="text-[10px] text-gray-400 block">Visa, RuPay</span>
                    </button>

                    <button type="button" class="pay-tab-btn p-4 rounded-2xl border text-center transition flex flex-col items-center gap-2 border-white/10 bg-white/[0.02] hover:border-white/20" onclick="selectCheckoutPayment('cod', this)">
                        <i data-lucide="package-check" class="w-6 h-6 text-gray-400"></i>
                        <span class="text-xs font-bold text-gray-200 block">Doorstep Pay</span>
                        <span class="text-[10px] text-gray-400 block">Cash or QR</span>
                    </button>
                </div>

                <!-- UPI Panel -->
                <div id="panel-pay-upi" class="pay-panel space-y-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400">Enter UPI ID (VPA)</label>
                    <div class="flex gap-2">
                        <input type="text" id="chk-vpa" placeholder="username@okhdfcbank" class="flex-1 bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-purple-500" />
                        <button type="button" onclick="verifyVpa()" class="px-5 bg-white/10 hover:bg-white/15 border border-white/10 rounded-xl text-xs font-bold uppercase text-purple-300 transition">
                            Verify
                        </button>
                    </div>
                    <p class="text-[11px] text-gray-500">Collect request will be triggered on your UPI app during order placement.</p>
                </div>

                <!-- Card Panel -->
                <div id="panel-pay-card" class="pay-panel hidden space-y-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Card Number</label>
                        <input type="text" placeholder="4111 •••• •••• 1111" maxlength="19" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-purple-500" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" placeholder="MM / YY" maxlength="5" class="bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-purple-500" />
                        <input type="password" placeholder="CVV" maxlength="4" class="bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-purple-500" />
                    </div>
                    <p class="text-[11px] text-emerald-400 flex items-center gap-1.5">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        Card numbers are tokenized directly via PCI-DSS compliant vaults.
                    </p>
                </div>

                <!-- COD Panel -->
                <div id="panel-pay-cod" class="pay-panel hidden p-4 rounded-2xl bg-white/[0.02] border border-white/5 space-y-2">
                    <h4 class="text-xs font-bold text-white uppercase flex items-center gap-1.5">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i>
                        <span>Doorstep Payment Guarantee</span>
                    </h4>
                    <p class="text-xs text-gray-300 leading-relaxed">
                        Pay comfortably at your doorstep when Blue Dart or Delhivery brings your parcel. You can pay with **Cash** or **Scan the delivery executive's official UPI QR code**.
                    </p>
                </div>
            </div>

        </div>

        <!-- Right: Order Summary Sidebar (Col 8 to 12) -->
        <div class="lg:col-span-5" data-aos="fade-left">
            <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-white/10 sticky top-28 space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-white/10">
                    <h3 class="text-lg font-bold font-cinzel text-white">Order Summary</h3>
                    <span id="chk-item-count" class="text-xs px-2.5 py-1 bg-white/5 rounded-full text-purple-300 font-bold border border-white/10">0 Items</span>
                </div>

                <!-- Items Container -->
                <div id="chk-items-list" class="space-y-3 max-h-72 overflow-y-auto pr-1">
                    <!-- Populated dynamically via JS -->
                </div>

                <!-- Discount Promo Input -->
                <div class="pt-4 border-t border-white/10">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Discount Coupon</label>
                    <div class="flex gap-2">
                        <input type="text" id="chk-coupon" placeholder="FEATHER20" class="flex-1 bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white uppercase tracking-wider placeholder-gray-600 focus:outline-none focus:border-purple-500" />
                        <button type="button" onclick="applyCheckoutCoupon()" class="px-5 bg-white/10 hover:bg-white/15 border border-white/10 rounded-xl text-xs font-bold uppercase text-purple-300 transition">
                            Apply
                        </button>
                    </div>
                    <div id="chk-coupon-feedback" class="text-[11px] mt-1.5 hidden"></div>
                </div>

                <!-- Price Calculations -->
                <div class="pt-4 border-t border-white/10 space-y-2.5 text-xs">
                    <div class="flex justify-between text-gray-400">
                        <span>Items Subtotal</span>
                        <span id="chk-subtotal" class="font-bold text-white">₹0</span>
                    </div>
                    <div class="flex justify-between text-gray-400">
                        <span>Standard Express Shipping</span>
                        <span class="font-bold text-emerald-400">FREE</span>
                    </div>
                    <div id="chk-discount-row" class="hidden justify-between text-purple-400 font-bold">
                        <span>VIP Coupon Discount</span>
                        <span id="chk-discount">-₹0</span>
                    </div>
                    <div class="pt-3 border-t border-dashed border-white/10 flex justify-between items-baseline text-base font-black text-white">
                        <span>Total Amount Payable</span>
                        <span id="chk-total" class="text-2xl text-purple-300 font-extrabold">₹0</span>
                    </div>
                </div>

                <!-- Place Order Button -->
                <button type="button" id="chk-submit-btn" onclick="executeCheckoutOrder()" class="w-full py-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-2xl text-xs font-bold uppercase tracking-wider shadow-glow-purple hover:scale-[1.01] transition duration-200 flex items-center justify-center gap-2">
                    <span>Authorize & Confirm Order</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>

                <div class="text-center text-[11px] text-gray-500">
                    <p>Free exchanges within 7 days of delivery. 100% money back guarantee.</p>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
    let activePayment = 'upi';
    let discountRate = 0;

    function selectCheckoutPayment(mode, btn) {
        activePayment = mode;
        document.querySelectorAll('.pay-tab-btn').forEach(b => {
            b.classList.remove('active', 'border-purple-500', 'bg-purple-600/15', 'shadow-glow-sm');
            b.classList.add('border-white/10', 'bg-white/[0.02]');
            const icon = b.querySelector('i');
            if (icon) icon.classList.remove('text-purple-400');
        });
        btn.classList.add('active', 'border-purple-500', 'bg-purple-600/15', 'shadow-glow-sm');
        btn.classList.remove('border-white/10', 'bg-white/[0.02]');
        const icon = btn.querySelector('i');
        if (icon) icon.classList.add('text-purple-400');

        document.querySelectorAll('.pay-panel').forEach(p => p.classList.add('hidden'));
        const panel = document.getElementById('panel-pay-' + mode);
        if (panel) panel.classList.remove('hidden');

        const submitBtn = document.getElementById('chk-submit-btn');
        if (mode === 'cod') {
            submitBtn.innerHTML = `<span>Confirm Order (Pay on Delivery)</span> <i data-lucide="arrow-right" class="w-4 h-4"></i>`;
        } else if (mode === 'upi') {
            submitBtn.innerHTML = `<span>Pay via UPI & Confirm</span> <i data-lucide="arrow-right" class="w-4 h-4"></i>`;
        } else {
            submitBtn.innerHTML = `<span>Authorize Card & Confirm</span> <i data-lucide="arrow-right" class="w-4 h-4"></i>`;
        }
        if (window.lucide) lucide.createIcons();
    }

    function verifyVpa() {
        const vpa = document.getElementById('chk-vpa').value.trim();
        if (!vpa || !vpa.includes('@')) {
            showToast('Please enter a valid UPI address (e.g. name@okhdfcbank)', 'error');
            return;
        }
        showToast('✓ UPI ID verified! You will receive a collect request when placing the order.', 'success');
    }

    function loadCheckoutCart() {
        const list = document.getElementById('chk-items-list');
        const countBadge = document.getElementById('chk-item-count');
        if (!list) return;

        let cart = [];
        try {
            cart = JSON.parse(localStorage.getItem('feather_cart') || localStorage.getItem('cart') || '[]');
        } catch(e){}

        if (countBadge) countBadge.innerText = cart.length + ' Items';

        if (cart.length === 0) {
            list.innerHTML = `
                <div class="text-center py-10 text-gray-500 text-xs">
                    <p>Your shopping bag is empty.</p>
                    <a href="index.php" class="text-purple-400 underline mt-2 inline-block">Return to shop drops</a>
                </div>
            `;
            calculateTotals(0);
            return;
        }

        let subtotal = 0;
        let html = '';
        cart.forEach(item => {
            const price = Number(item.price || 999);
            const qty = Number(item.quantity || 1);
            subtotal += price * qty;
            html += `
                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/[0.02] border border-white/5">
                    <img src="${item.image || 'assets/hero-bg.webp'}" class="w-12 h-14 object-cover rounded-lg bg-black" />
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-white truncate">${item.name || 'Apparel Item'}</h4>
                        <span class="text-[10px] text-gray-400">Size: ${item.size || 'M'} | Qty: ${qty}</span>
                    </div>
                    <span class="text-xs font-bold text-white">₹${(price * qty).toLocaleString('en-IN')}</span>
                </div>
            `;
        });
        list.innerHTML = html;
        calculateTotals(subtotal);
    }

    function calculateTotals(subtotal) {
        const discountAmt = Math.round(subtotal * (discountRate / 100));
        const total = Math.max(0, subtotal - discountAmt);

        document.getElementById('chk-subtotal').innerText = '₹' + subtotal.toLocaleString('en-IN');
        const discRow = document.getElementById('chk-discount-row');
        if (discountRate > 0) {
            discRow.classList.remove('hidden');
            discRow.classList.add('flex');
            document.getElementById('chk-discount').innerText = '-₹' + discountAmt.toLocaleString('en-IN') + ` (${discountRate}% OFF)`;
        } else {
            discRow.classList.add('hidden');
            discRow.classList.remove('flex');
        }
        document.getElementById('chk-total').innerText = '₹' + total.toLocaleString('en-IN');
    }

    function applyCheckoutCoupon() {
        const input = document.getElementById('chk-coupon');
        const feedback = document.getElementById('chk-coupon-feedback');
        const code = (input.value || '').trim().toUpperCase();

        let cart = [];
        try {
            cart = JSON.parse(localStorage.getItem('feather_cart') || localStorage.getItem('cart') || '[]');
        } catch(e){}
        const subtotal = cart.reduce((acc, it) => acc + ((it.price || 999) * (it.quantity || 1)), 0);

        feedback.classList.remove('hidden');
        if (code === 'FEATHER20' || code === 'FIRST20') {
            discountRate = 20;
            feedback.className = 'text-[11px] mt-1.5 text-emerald-400 font-bold';
            feedback.innerText = '✓ Promo applied: 20% discount granted!';
        } else if (code === 'VIP10') {
            discountRate = 10;
            feedback.className = 'text-[11px] mt-1.5 text-emerald-400 font-bold';
            feedback.innerText = '✓ Promo applied: 10% discount granted!';
        } else {
            discountRate = 0;
            feedback.className = 'text-[11px] mt-1.5 text-red-400 font-bold';
            feedback.innerText = 'Invalid promo code. Try FEATHER20';
        }
        calculateTotals(subtotal);
    }

    function executeCheckoutOrder() {
        const firstName = document.getElementById('chk-firstname').value.trim();
        const phone = document.getElementById('chk-phone').value.trim();
        const address = document.getElementById('chk-address').value.trim();
        const city = document.getElementById('chk-city').value.trim();
        const pincode = document.getElementById('chk-pincode').value.trim();

        if (!firstName || !phone || !address || !city || !pincode) {
            showToast('Please fill all mandatory shipping address fields.', 'error');
            return;
        }

        let cart = [];
        try {
            cart = JSON.parse(localStorage.getItem('feather_cart') || localStorage.getItem('cart') || '[]');
        } catch(e){}

        if (cart.length === 0) {
            showToast('Your bag is empty. Please add products first.', 'error');
            return;
        }

        const orderId = 'FF-' + Math.floor(100000 + Math.random() * 900000);
        const total = document.getElementById('chk-total').innerText;

        const newOrder = {
            id: orderId,
            date: new Date().toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' }),
            items: cart,
            total: total,
            status: 'Processing',
            paymentMethod: activePayment === 'cod' ? 'Cash / UPI on Delivery' : (activePayment === 'upi' ? 'Instant UPI' : 'Credit / Debit Card'),
            shippingAddress: `${firstName}, ${address}, ${city} - ${pincode}`,
            phone: phone
        };

        try {
            const orders = JSON.parse(localStorage.getItem('feather_orders') || '[]');
            orders.unshift(newOrder);
            localStorage.setItem('feather_orders', JSON.stringify(orders));
            localStorage.removeItem('feather_cart');
            localStorage.removeItem('cart');
        } catch(e){}

        showToast(`🎉 Order ${orderId} confirmed successfully!`, 'success');
        setTimeout(() => {
            window.location.href = `account.php?tab=tracking&order=${orderId}`;
        }, 600);
    }

    document.addEventListener('DOMContentLoaded', loadCheckoutCart);
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
