<?php
require_once __DIR__ . '/data/products.php';

$pageTitle = "PCI-DSS Security & Trust Center | Feather Fits";
$pageDescription = "Feather Fits Security Architecture, PCI-DSS SAQ A Compliance, and 256-bit Bank-grade Encryption details.";
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
            background: rgba(39, 174, 96, 0.15);
            border: 1px solid rgba(39, 174, 96, 0.4);
            border-radius: 999px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #2ecc71;
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
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }
        .security-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .sec-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 24px;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, border-color 0.3s ease;
        }
        .sec-card:hover {
            transform: translateY(-4px);
            border-color: rgba(142, 68, 173, 0.4);
        }
        .sec-icon {
            font-size: 2rem;
            margin-bottom: 14px;
            display: inline-block;
        }
        .sec-card h3 {
            font-size: 1.15rem;
            color: #ffffff;
            margin-bottom: 8px;
            font-weight: 700;
        }
        .sec-card p {
            color: #9090a2;
            font-size: 0.88rem;
            line-height: 1.6;
            margin: 0;
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
            <div class="legal-badge">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                PCI-DSS Level 1 Compliant Security Architecture
            </div>
            <h1 class="legal-title">Security &amp; Trust Center</h1>
            <p class="legal-subtitle">How Feather Fits protects your transactions, sensitive payment card data, and digital identities with enterprise bank-grade security protocols.</p>
        </section>

        <div class="legal-content-wrap">
            <div class="security-grid">
                <div class="sec-card">
                    <span class="sec-icon">🛡️</span>
                    <h3>PCI-DSS SAQ A Certified</h3>
                    <p>Cardholder data never touches our web servers. Payments execute directly through encrypted, audited PCI Level-1 gateway tokenizers.</p>
                </div>
                <div class="sec-card">
                    <span class="sec-icon">🔐</span>
                    <h3>256-bit TLS/SSL Encryption</h3>
                    <p>All data transmitted between your browser and our servers is secured via ECDSA certificates using high-grade TLS 1.3 encryption.</p>
                </div>
                <div class="sec-card">
                    <span class="sec-icon">⚡</span>
                    <h3>UPI &amp; Tokenized Vaults</h3>
                    <p>Instant UPI dynamic QR and 3D Secure 2.0 multi-factor authentication protect every transaction against unauthorized card usage.</p>
                </div>
            </div>

            <article class="legal-section">
                <h2>1. Payment Card Security Architecture</h2>
                <p>Feather Fits operates under strict compliance with the Payment Card Industry Data Security Standard (PCI-DSS SAQ A). When you purchase apparel on our store:</p>
                <ul>
                    <li><strong>Zero Plaintext Storage:</strong> Feather Fits never stores your full 16-digit debit/credit card numbers, expiry dates, or CVV/CVC codes on our databases or web servers.</li>
                    <li><strong>Direct-to-Gateway Tokenization:</strong> Checkout card details are ingested via iframes hosted directly by regulated payment gateways and tokenized in real time.</li>
                    <li><strong>3D Secure 2.0 (3DS2):</strong> All electronic card payments are verified with your issuing bank via mandatory OTP or biometrics.</li>
                </ul>
            </article>

            <article class="legal-section">
                <h2>2. Doorstep UPI &amp; Cash on Delivery (COD) Safeguards</h2>
                <p>For customers selecting Doorstep Payment / COD:</p>
                <ul>
                    <li>Orders are validated through phone number SMS OTP verification to stop spam and bot submissions.</li>
                    <li>Delivery personnel provide an authorized, tamper-evident dynamic QR code at the time of parcel handover for seamless UPI scan-and-pay.</li>
                    <li>Cash collections are backed by immediate digital SMS receipt generation upon receipt.</li>
                </ul>
            </article>

            <article class="legal-section">
                <h2>3. Network &amp; Infrastructure Defense</h2>
                <p>Our e-commerce infrastructure features defense-in-depth engineering:</p>
                <ul>
                    <li><strong>Enterprise WAF &amp; Rate-Limiting:</strong> Comprehensive Web Application Firewall with real-time DDoS mitigation, rate-limiting, and bad-bot blocking.</li>
                    <li><strong>Automated Vulnerability Audits:</strong> Continuous vulnerability scans and dependencies updates to eliminate zero-day risks.</li>
                    <li><strong>Strict CSP &amp; Security Headers:</strong> Strict Content-Security-Policy (CSP), HSTS, X-Content-Type-Options, and X-Frame-Options headers applied to all responses.</li>
                </ul>
            </article>

            <div class="back-nav">
                <a href="index.php">&larr; Return to Feather Fits Home</a>
                <a href="privacy.php">Review Privacy Policy &rarr;</a>
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
