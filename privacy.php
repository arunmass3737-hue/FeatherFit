<?php
require_once __DIR__ . '/data/products.php';

$pageTitle = "Privacy Policy & Data Protection | Feather Fits";
$pageDescription = "Feather Fits Privacy Policy. Learn how your data is encrypted, processed, and protected under Indian IT Act and Digital Personal Data Protection guidelines.";
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
            <div class="legal-badge">Compliance &amp; Consumer Trust</div>
            <h1 class="legal-title">Privacy Policy</h1>
            <p class="legal-subtitle">Effective Date: January 1, 2026 &bull; Last Revised: October 2026</p>
        </section>

        <div class="legal-content-wrap">
            <article class="legal-section">
                <h2>1. Introduction &amp; Commitment</h2>
                <p>Feather Fits Apparel Co. ("Feather Fits", "we", "us", or "our") respects your digital privacy. This Privacy Policy outlines our transparent data handling practices when you explore our streetwear collections, utilize our size recommendations, place orders, or subscribe to drop alerts.</p>
                <p>We comply fully with the Information Technology Act, 2000, and the Digital Personal Data Protection Act (DPDP Act, 2023).</p>
            </article>

            <article class="legal-section">
                <h2>2. Information We Collect</h2>
                <p>We only collect information essential for fulfilling your streetwear orders and enhancing your browsing journey:</p>
                <ul>
                    <li><strong>Contact &amp; Shipping Identifiers:</strong> Name, delivery street address, phone number, and email address.</li>
                    <li><strong>Order &amp; Transaction Details:</strong> Products purchased, variant selections (size, color, graphic edition), and payment method tokens (we never store raw card numbers).</li>
                    <li><strong>Fit &amp; Preference Data:</strong> Saved sizing preferences and wishlist items stored locally or within your account.</li>
                    <li><strong>Technical Device Telemetry:</strong> Anonymized browser type, screen resolution, and IP address used strictly for fraud prevention.</li>
                </ul>
            </article>

            <article class="legal-section">
                <h2>3. Payment Security &amp; Tokenization</h2>
                <p>All electronic transactions are processed through PCI-DSS Level 1 certified payment gateways. When you enter card or UPI credentials, they are encrypted end-to-end via 256-bit TLS/SSL and tokenized directly at the gateway. Feather Fits never views or stores CVV codes or payment passwords.</p>
            </article>

            <article class="legal-section">
                <h2>4. Grievance Officer &amp; Inquiries</h2>
                <p>For any questions regarding your data or to request account deletion, reach out to our appointed Grievance Officer:</p>
                <p style="color: #c084fc; font-weight: 600;">privacy@featherfits.com &bull; Feather Fits HQ, T. Nagar, Chennai - 600017</p>
            </article>

            <div class="back-nav">
                <a href="index.php">&larr; Return to Storefront</a>
                <a href="terms.php">Read Terms of Service &rarr;</a>
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
