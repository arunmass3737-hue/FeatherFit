<?php
$pageTitle = "PCI-DSS Security Center | Feather Fits";
$pageDescription = "Feather Fits security protocols: PCI-DSS SAQ A compliance, 256-bit encryption, tokenized cards, and dynamic UPI.";
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20">
    <div class="text-center max-w-2xl mx-auto mb-12" data-aos="fade-down">
        <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-950/60 text-emerald-400 border border-emerald-800/40 rounded-full text-xs font-bold uppercase tracking-widest mb-4">
            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
            PCI-DSS SAQ A Certified Security Architecture
        </span>
        <h1 class="text-3xl sm:text-5xl font-black font-cinzel text-white tracking-tight">Security & Trust Center</h1>
        <p class="text-xs sm:text-sm text-gray-400 mt-3 leading-relaxed">
            How Feather Fits safeguards customer transactions, digital identities, and payment security with bank-grade defenses.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8" data-aos="fade-up">
        <div class="glass-panel p-6 rounded-2xl border border-white/10 text-center">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center mx-auto mb-3">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <h3 class="text-sm font-bold text-white">PCI-DSS Level 1</h3>
            <p class="text-xs text-gray-400 mt-1">Zero plaintext card storage. Direct gateway vault tokenization.</p>
        </div>

        <div class="glass-panel p-6 rounded-2xl border border-white/10 text-center">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center mx-auto mb-3">
                <i data-lucide="lock" class="w-6 h-6"></i>
            </div>
            <h3 class="text-sm font-bold text-white">256-bit TLS/SSL</h3>
            <p class="text-xs text-gray-400 mt-1">Encrypted browser communications using modern TLS 1.3 ciphers.</p>
        </div>

        <div class="glass-panel p-6 rounded-2xl border border-white/10 text-center">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center mx-auto mb-3">
                <i data-lucide="zap" class="w-6 h-6"></i>
            </div>
            <h3 class="text-sm font-bold text-white">Instant UPI Dynamic QR</h3>
            <p class="text-xs text-gray-400 mt-1">Authenticated doorstep UPI handover and 3D Secure 2.0.</p>
        </div>
    </div>

    <div class="glass-panel p-6 sm:p-10 rounded-3xl border border-white/10 space-y-8 text-xs sm:text-sm text-gray-300 leading-relaxed" data-aos="fade-up">
        
        <section class="space-y-3">
            <h2 class="text-base sm:text-lg font-bold font-cinzel text-white flex items-center gap-2">
                <i data-lucide="shield" class="w-4 h-4 text-purple-400"></i>
                <span>1. Cardholder Data Environment (CDE) Isolation</span>
            </h2>
            <p>
                Under PCI-DSS SAQ A scope, our web servers are strictly isolated from credit and debit card PAN data. Input fields load in isolated sandboxed iframes hosted directly by PCI Level-1 service providers (Stripe, Razorpay, Cashfree).
            </p>
        </section>

        <section class="space-y-3">
            <h2 class="text-base sm:text-lg font-bold font-cinzel text-white flex items-center gap-2">
                <i data-lucide="package-check" class="w-4 h-4 text-purple-400"></i>
                <span>2. Doorstep COD Fraud Prevention</span>
            </h2>
            <p>
                Every cash or doorstep UPI order requires SMS OTP verification to prevent fake bookings. Delivery agents utilize tamper-proof packaging and dynamic digital receipts on parcel delivery.
            </p>
        </section>

        <section class="space-y-3">
            <h2 class="text-base sm:text-lg font-bold font-cinzel text-white flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-purple-400"></i>
                <span>3. Responsible Security Disclosure</span>
            </h2>
            <p>
                We welcome ethical hackers to report vulnerabilities to <strong class="text-purple-300">security@featherfits.com</strong>. We acknowledge valid reports within 24 hours.
            </p>
        </section>

    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
