<?php
require_once __DIR__ . '/data/products.php';

$pageTitle = "Terms of Service & Sales Agreement | Feather Fits";
$pageDescription = "Official terms of service, ordering policies, shipping timelines, doorstep cash-on-delivery rules, and 30-day exchange commitments for Feather Fits.";
$currentPage = 'legal';
$currentDept = 'all';

require_once __DIR__ . '/includes/header.php';
?>

    <style>
        .legal-page-header {
            padding: 60px 24px 36px;
            text-align: center;
            background: linear-gradient(180deg, rgba(142, 68, 173, 0.12) 0%, transparent 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .legal-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: rgba(168, 85, 247, 0.15);
            border: 1px solid rgba(168, 85, 247, 0.3);
            border-radius: 999px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #c084fc;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .legal-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(2rem, 4vw, 3.2rem);
            color: #ffffff;
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }
        .legal-subtitle {
            color: #a0a0b0;
            font-size: 0.95rem;
            max-width: 650px;
            margin: 0 auto;
        }
        .legal-content-wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }
        .legal-section {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
        }
        .legal-section h2 {
            font-family: 'Cinzel', serif;
            font-size: 1.35rem;
            color: #e0d0f5;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .legal-section p, .legal-section li {
            color: #b8b8cc;
            font-size: 0.92rem;
            line-height: 1.75;
        }
        .legal-section ul {
            padding-left: 20px;
            margin: 12px 0;
        }
        .legal-section li {
            margin-bottom: 8px;
        }
        .back-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        .back-nav a {
            color: #c084fc;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .back-nav a:hover {
            color: #ffffff;
        }
    </style>

    <main>
        <section class="legal-page-header">
            <div class="legal-badge">Official Customer Agreement</div>
            <h1 class="legal-title">Terms of Service</h1>
            <p class="legal-subtitle">Standard Sales, Exchange, and Fair-Use Terms governing Feather Fits e-Commerce</p>
        </section>

        <div class="legal-content-wrap">
            <article class="legal-section">
                <h2>1. Acceptance of Terms</h2>
                <p>By browsing, creating an account, or purchasing apparel on the Feather Fits website, you accept and agree to be bound by these Terms of Service. If you do not agree with any part of these terms, please do not use our services.</p>
            </article>

            <article class="legal-section">
                <h2>2. Orders, Pricing &amp; Stock Availability</h2>
                <ul>
                    <li><strong>Accurate Product Data:</strong> We strive to display fabric colors, print scales, and measurements as accurately as possible. Slight color variations may occur due to individual screen calibration.</li>
                    <li><strong>Order Confirmation:</strong> An order confirmation email and SMS notification will be dispatched upon receipt of your order.</li>
                    <li><strong>Pricing &amp; Promotions:</strong> All prices listed are in Indian Rupees (INR) and are inclusive of applicable GST taxes. Promo codes (e.g., FEATHER20) are subject to terms and may be discontinued at our discretion.</li>
                </ul>
            </article>

            <article class="legal-section">
                <h2>3. Shipping, Delivery &amp; Doorstep Verification</h2>
                <ul>
                    <li>Orders are typically dispatched within 24 to 48 business hours via insured courier partners (Blue Dart, Delhivery, FedEx).</li>
                    <li>For Cash on Delivery (COD) and Doorstep UPI orders, phone SMS verification is required at checkout to ensure parcel deliverability.</li>
                    <li>Delivery executives carry dynamic QR codes for instant UPI payment at your doorstep.</li>
                </ul>
            </article>

            <article class="legal-section">
                <h2>4. 30-Day Easy Exchange Policy</h2>
                <p>We want your fit to inspire total confidence. If your apparel doesn't fit exactly as desired, you may request an exchange within 30 days of parcel delivery, provided the item is unwashed, unworn, and retains original tags and packaging.</p>
            </article>

            <div class="back-nav">
                <a href="index.php">&larr; Return to Storefront</a>
                <a href="security.php">View PCI-DSS Security &rarr;</a>
            </div>
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
