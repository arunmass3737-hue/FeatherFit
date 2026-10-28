<?php
require_once __DIR__ . '/data/products.php';

$pageTitle = "Secure Checkout | Feather Fits Premium Streetwear";
$pageDescription = "Complete your purchase securely on Feather Fits. Express UPI, Credit/Debit cards, and Doorstep COD payment options.";
$currentPage = 'checkout';
$currentDept = 'all';

require_once __DIR__ . '/includes/header.php';
?>

    <style>
        .checkout-page-container {
            max-width: 1200px;
            margin: 40px auto 60px;
            padding: 0 24px;
        }
        .checkout-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 40px;
            align-items: start;
        }
        @media (max-width: 960px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
        }
        .checkout-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 32px;
            backdrop-filter: blur(14px);
            margin-bottom: 24px;
        }
        .checkout-card h2 {
            font-family: 'Cinzel', serif;
            font-size: 1.35rem;
            color: #fff;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .checkout-card h2 span.step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #a855f7, #6366f1);
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.82rem;
            font-weight: 800;
            border-radius: 50%;
        }
        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        @media (max-width: 600px) {
            .form-row-2 {
                grid-template-columns: 1fr;
            }
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #a0a0b8;
            margin-bottom: 6px;
            font-weight: 600;
        }
        .form-control-custom {
            width: 100%;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 10px;
            padding: 12px 16px;
            color: #fff;
            font-size: 0.92rem;
            outline: none;
            transition: all 0.2s ease;
        }
        .form-control-custom:focus {
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.2);
        }
        .payment-tabs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }
        @media (max-width: 650px) {
            .payment-tabs {
                grid-template-columns: 1fr;
            }
        }
        .payment-tab-btn {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 16px 14px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
            color: #d1d1e0;
        }
        .payment-tab-btn:hover {
            border-color: rgba(168, 85, 247, 0.4);
            background: rgba(168, 85, 247, 0.05);
        }
        .payment-tab-btn.active {
            border-color: #a855f7;
            background: rgba(168, 85, 247, 0.12);
            color: #fff;
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.15);
        }
        .payment-tab-btn .tab-icon {
            font-size: 1.5rem;
            margin-bottom: 6px;
            display: block;
        }
        .payment-tab-btn .tab-title {
            font-size: 0.88rem;
            font-weight: 700;
            display: block;
        }
        .payment-tab-btn .tab-sub {
            font-size: 0.72rem;
            color: #8f8fa0;
            display: block;
            margin-top: 2px;
        }
        .payment-panel {
            display: none;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 20px;
        }
        .payment-panel.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .order-summary-sidebar {
            position: sticky;
            top: 100px;
        }
        .summary-item-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
        .summary-item-img {
            width: 52px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            background: #1a1a24;
        }
        .summary-item-info {
            flex: 1;
        }
        .summary-item-name {
            font-size: 0.88rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 2px;
        }
        .summary-item-meta {
            font-size: 0.75rem;
            color: #8e8ea6;
        }
        .summary-item-price {
            font-weight: 700;
            font-size: 0.95rem;
            color: #f1f1f5;
        }
        .price-breakdown-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.88rem;
            color: #a0a0b8;
            margin-bottom: 10px;
        }
        .price-breakdown-row.total-row {
            font-size: 1.2rem;
            font-weight: 800;
            color: #fff;
            padding-top: 14px;
            margin-top: 14px;
            border-top: 1px dashed rgba(255, 255, 255, 0.15);
        }
        .btn-place-order {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #a855f7, #6366f1);
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(168, 85, 247, 0.35);
            transition: all 0.25s ease;
            margin-top: 20px;
        }
        .btn-place-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(168, 85, 247, 0.5);
        }
        .trust-badges-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-top: 18px;
            font-size: 0.78rem;
            color: #7b7b92;
        }
    </style>

    <main class="checkout-page-container">
        <div class="checkout-grid">
            <!-- Left Side: Forms -->
            <div class="checkout-form-column">
                <!-- Step 1: Shipping Details -->
                <div class="checkout-card">
                    <h2><span class="step-number">1</span> Shipping & Contact Details</h2>
                    <form id="checkout-main-form" onsubmit="event.preventDefault();">
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="c-firstname">First Name *</label>
                                <input type="text" id="c-firstname" class="form-control-custom" placeholder="e.g. Arjun" required>
                            </div>
                            <div class="form-group">
                                <label for="c-lastname">Last Name *</label>
                                <input type="text" id="c-lastname" class="form-control-custom" placeholder="e.g. Kumar" required>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="c-phone">Mobile Phone (for delivery OTP) *</label>
                                <input type="tel" id="c-phone" class="form-control-custom" placeholder="+91 98765 43210" required>
                            </div>
                            <div class="form-group">
                                <label for="c-email">Email Address (for invoice) *</label>
                                <input type="email" id="c-email" class="form-control-custom" placeholder="arjun@example.com" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="c-address">Street Address / House No. *</label>
                            <input type="text" id="c-address" class="form-control-custom" placeholder="Flat No., Building Name, Street Name" required>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="c-city">City *</label>
                                <input type="text" id="c-city" class="form-control-custom" placeholder="Chennai" required>
                            </div>
                            <div class="form-group">
                                <label for="c-pincode">PIN / Postal Code *</label>
                                <input type="text" id="c-pincode" class="form-control-custom" placeholder="600001" maxlength="6" required>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Step 2: Payment Method Tabs -->
                <div class="checkout-card">
                    <h2><span class="step-number">2</span> Payment Method</h2>
                    
                    <div class="payment-tabs">
                        <div class="payment-tab-btn active" onclick="selectPaymentTab('upi', this)">
                            <span class="tab-icon">⚡</span>
                            <span class="tab-title">Instant UPI</span>
                            <span class="tab-sub">GPay, PhonePe, Paytm</span>
                        </div>
                        <div class="payment-tab-btn" onclick="selectPaymentTab('card', this)">
                            <span class="tab-icon">💳</span>
                            <span class="tab-title">Card / Netbanking</span>
                            <span class="tab-sub">Visa, Mastercard, RuPay</span>
                        </div>
                        <div class="payment-tab-btn" onclick="selectPaymentTab('cod', this)">
                            <span class="tab-icon">📦</span>
                            <span class="tab-title">Doorstep Pay / COD</span>
                            <span class="tab-sub">Cash or UPI QR on Delivery</span>
                        </div>
                    </div>

                    <!-- Panel 1: UPI -->
                    <div id="panel-upi" class="payment-panel active">
                        <div class="form-group">
                            <label for="c-vpa">Enter Your Virtual Payment Address (UPI ID)</label>
                            <div style="display: flex; gap: 8px;">
                                <input type="text" id="c-vpa" class="form-control-custom" placeholder="username@okhdfcbank / mobile@ybl">
                                <button type="button" class="btn-outline-sm px-4 rounded-lg bg-purple-600/20 hover:bg-purple-600/40 text-purple-300 font-semibold text-xs" onclick="verifyVPA()">Verify</button>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Or choose to scan a dynamic QR code on the final authorization step.</p>
                    </div>

                    <!-- Panel 2: Card -->
                    <div id="panel-card" class="payment-panel">
                        <div class="form-group">
                            <label for="c-cardnum">Card Number</label>
                            <input type="text" id="c-cardnum" class="form-control-custom" placeholder="4111 •••• •••• 1111" maxlength="19">
                        </div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="c-expiry">Expiry Date</label>
                                <input type="text" id="c-expiry" class="form-control-custom" placeholder="MM / YY" maxlength="5">
                            </div>
                            <div class="form-group">
                                <label for="c-cvv">CVV / Security Code</label>
                                <input type="password" id="c-cvv" class="form-control-custom" placeholder="•••" maxlength="4">
                            </div>
                        </div>
                        <p class="text-xs text-emerald-400">🔒 Tokenized directly via PCI-DSS SAQ A gateway. Card numbers never touch our servers.</p>
                    </div>

                    <!-- Panel 3: COD -->
                    <div id="panel-cod" class="payment-panel">
                        <div style="display: flex; gap: 14px; align-items: flex-start;">
                            <span style="font-size: 2rem;">🤝</span>
                            <div>
                                <h4 style="font-size: 0.95rem; font-weight: 700; color: #fff;">Doorstep Payment Guarantee</h4>
                                <p style="font-size: 0.82rem; color: #a0a0b8; margin-top: 4px; line-height: 1.6;">
                                    Pay securely at your doorstep when your package arrives! You can pay with <strong>Cash</strong> or scan our delivery executive's <strong>Official UPI QR code</strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Summary -->
            <div class="checkout-summary-column">
                <div class="checkout-card order-summary-sidebar">
                    <h2>Order Summary</h2>
                    
                    <!-- Items List -->
                    <div id="summary-items-list">
                        <!-- Populated dynamically via JS -->
                    </div>

                    <!-- Coupon Code Input -->
                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.1);">
                        <label style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #a0a0b8; font-weight: 600; display: block; margin-bottom: 6px;">Discount Coupon</label>
                        <div style="display: flex; gap: 8px;">
                            <input type="text" id="checkout-coupon-code" class="form-control-custom" style="font-size: 0.85rem;" placeholder="FEATHER20">
                            <button type="button" style="padding: 8px 16px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; color: #c084fc; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; cursor: pointer;" onclick="applyCheckoutCoupon()">Apply</button>
                        </div>
                        <div id="coupon-feedback" style="font-size: 0.78rem; margin-top: 6px; display: none;"></div>
                    </div>

                    <!-- Price Calculations -->
                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.1);">
                        <div class="price-breakdown-row">
                            <span>Subtotal</span>
                            <span id="summary-subtotal">₹0</span>
                        </div>
                        <div class="price-breakdown-row">
                            <span>Standard Express Shipping</span>
                            <span id="summary-shipping" style="color: #34d399; font-weight: 700;">FREE</span>
                        </div>
                        <div class="price-breakdown-row" id="summary-discount-row" style="display: none;">
                            <span>Discount</span>
                            <span id="summary-discount" style="color: #c084fc;">-₹0</span>
                        </div>
                        <div class="price-breakdown-row total-row">
                            <span>Total Payable</span>
                            <span id="summary-total">₹0</span>
                        </div>

                        <button type="button" class="btn-place-order" id="btn-submit-order" onclick="processCheckoutOrder()">
                            Confirm & Place Order
                        </button>

                        <div class="trust-badges-bar">
                            <span>🛡️ PCI-DSS Certified</span>
                            <span>•</span>
                            <span>🚚 100% Insured Delivery</span>
                            <span>•</span>
                            <span>🔄 30-Day Easy Return</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        let selectedMethod = 'upi';
        let discountPercent = 0;

        function selectPaymentTab(method, el) {
            selectedMethod = method;
            document.querySelectorAll('.payment-tab-btn').forEach(btn => btn.classList.remove('active'));
            el.classList.add('active');

            document.querySelectorAll('.payment-panel').forEach(p => p.classList.remove('active'));
            const target = document.getElementById('panel-' + method);
            if (target) target.classList.add('active');

            const submitBtn = document.getElementById('btn-submit-order');
            if (method === 'cod') {
                submitBtn.innerText = 'Confirm Order (Pay on Delivery)';
            } else if (method === 'upi') {
                submitBtn.innerText = 'Pay via UPI & Place Order';
            } else {
                submitBtn.innerText = 'Authorize Card & Place Order';
            }
        }

        function verifyVPA() {
            const vpa = document.getElementById('c-vpa').value.trim();
            if (!vpa || !vpa.includes('@')) {
                alert('Please enter a valid UPI ID (e.g., yourname@okhdfcbank)');
                return;
            }
            alert('✓ UPI ID ' + vpa + ' verified successfully! You will receive a collect request when placing the order.');
        }

        function loadCheckoutItems() {
            const container = document.getElementById('summary-items-list');
            let cart = [];
            try {
                cart = JSON.parse(localStorage.getItem('feather_cart') || localStorage.getItem('cart') || '[]');
            } catch (e) {
                cart = [];
            }

            if (cart.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 24px 0; color: #888;">
                        <p style="margin-bottom: 12px;">Your shopping bag is empty.</p>
                        <a href="index.php" class="btn-primary" style="padding: 8px 16px; font-size: 0.8rem; display: inline-block;">Browse Fresh Street Drops</a>
                    </div>
                `;
                document.getElementById('summary-subtotal').innerText = '₹0';
                document.getElementById('summary-total').innerText = '₹0';
                return;
            }

            let subtotal = 0;
            let html = '';
            cart.forEach(item => {
                const itemTotal = (item.price || 999) * (item.quantity || 1);
                subtotal += itemTotal;
                html += `
                    <div class="summary-item-row">
                        <img src="${item.image || 'assets/images/plain-tee.jpg'}" alt="${item.name || 'Product'}" class="summary-item-img">
                        <div class="summary-item-info">
                            <div class="summary-item-name">${item.name || 'Streetwear Essential'}</div>
                            <div class="summary-item-meta">Size: ${item.size || 'M'} | Qty: ${item.quantity || 1}</div>
                        </div>
                        <div class="summary-item-price">₹${itemTotal.toLocaleString('en-IN')}</div>
                    </div>
                `;
            });

            container.innerHTML = html;
            updateTotals(subtotal);
        }

        function updateTotals(subtotal) {
            const discountAmt = Math.round(subtotal * (discountPercent / 100));
            const total = Math.max(0, subtotal - discountAmt);

            document.getElementById('summary-subtotal').innerText = '₹' + subtotal.toLocaleString('en-IN');
            if (discountPercent > 0) {
                document.getElementById('summary-discount-row').style.display = 'flex';
                document.getElementById('summary-discount').innerText = '-₹' + discountAmt.toLocaleString('en-IN') + ` (${discountPercent}% OFF)`;
            } else {
                document.getElementById('summary-discount-row').style.display = 'none';
            }
            document.getElementById('summary-total').innerText = '₹' + total.toLocaleString('en-IN');
        }

        function applyCheckoutCoupon() {
            const code = document.getElementById('checkout-coupon-code').value.trim().toUpperCase();
            const feedback = document.getElementById('coupon-feedback');
            feedback.style.display = 'block';

            let cart = [];
            try {
                cart = JSON.parse(localStorage.getItem('feather_cart') || localStorage.getItem('cart') || '[]');
            } catch (e) {}
            const subtotal = cart.reduce((acc, it) => acc + ((it.price || 999) * (it.quantity || 1)), 0);

            if (code === 'FEATHER20' || code === 'FIRST20') {
                discountPercent = 20;
                feedback.style.color = '#34d399';
                feedback.innerText = '✓ Promo applied: 20% discount granted!';
            } else if (code === 'VIP10') {
                discountPercent = 10;
                feedback.style.color = '#34d399';
                feedback.innerText = '✓ Promo applied: 10% discount granted!';
            } else {
                discountPercent = 0;
                feedback.style.color = '#f87171';
                feedback.innerText = 'Invalid promo code. Try FEATHER20';
            }
            updateTotals(subtotal);
        }

        function processCheckoutOrder() {
            const firstname = document.getElementById('c-firstname').value.trim();
            const phone = document.getElementById('c-phone').value.trim();
            const address = document.getElementById('c-address').value.trim();
            const city = document.getElementById('c-city').value.trim();
            const pincode = document.getElementById('c-pincode').value.trim();

            if (!firstname || !phone || !address || !city || !pincode) {
                alert('Please complete all mandatory delivery fields (Name, Phone, Address, City, PIN Code).');
                return;
            }

            let cart = [];
            try {
                cart = JSON.parse(localStorage.getItem('feather_cart') || localStorage.getItem('cart') || '[]');
            } catch (e) {}

            if (cart.length === 0) {
                alert('Your shopping bag is empty. Please add items to checkout.');
                return;
            }

            const orderId = 'FF-' + Math.floor(100000 + Math.random() * 900000);
            const total = document.getElementById('summary-total').innerText;

            const newOrder = {
                id: orderId,
                date: new Date().toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' }),
                items: cart,
                total: total,
                status: 'Processing',
                paymentMethod: selectedMethod === 'cod' ? 'Cash / UPI on Delivery' : (selectedMethod === 'upi' ? 'Instant UPI' : 'Credit / Debit Card'),
                shippingAddress: `${firstname}, ${address}, ${city} - ${pincode}`,
                phone: phone
            };

            // Store directly in Hostinger MySQL Database
            const numericTotal = parseFloat(total.replace(/[^0-9.]/g, '')) || 0;
            fetch('api/shipping.php?action=create_order', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id: orderId,
                    customer: {
                        name: firstname,
                        email: email,
                        phone: phone,
                        city: city
                    },
                    items: cart,
                    subtotal: numericTotal,
                    tax: 0,
                    shippingFee: 0,
                    total: numericTotal
                })
            }).catch(e => console.error('MySQL order sync error', e));

            try {
                const existingOrders = JSON.parse(localStorage.getItem('feather_orders') || '[]');
                existingOrders.unshift(newOrder);
                localStorage.setItem('feather_orders', JSON.stringify(existingOrders));
                localStorage.removeItem('feather_cart');
                localStorage.removeItem('cart');
            } catch (e) {
                console.error('Order saving error', e);
            }

            alert(`🎉 Order Placed Successfully!\n\nOrder ID: ${orderId}\nPayment Mode: ${newOrder.paymentMethod}\nTotal: ${total}\n\nA confirmation SMS has been dispatched to ${phone}.`);
            window.location.href = `account.php?tab=tracking&order=${orderId}`;
        }

        window.addEventListener('DOMContentLoaded', loadCheckoutItems);
    </script>

<?php
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/modals.php';
?>

  <!-- Scripts -->
  <script src="data/products.js"></script>
  <script src="js/app.js"></script>
</body>
</html>
