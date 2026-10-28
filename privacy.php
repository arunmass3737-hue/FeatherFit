<?php
$pageTitle = "Privacy Policy | Feather Fits India";
$pageDescription = "Feather Fits privacy policy regarding data collection, encryption, cookies, and customer rights.";
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20">
    <div class="text-center max-w-2xl mx-auto mb-12" data-aos="fade-down">
        <span class="inline-flex items-center gap-2 px-3 py-1 bg-purple-600/20 text-purple-300 border border-purple-500/30 rounded-full text-xs font-bold uppercase tracking-widest mb-4">
            <i data-lucide="shield" class="w-3.5 h-3.5"></i>
            User Privacy & Data Protection
        </span>
        <h1 class="text-3xl sm:text-5xl font-black font-cinzel text-white tracking-tight">Privacy Policy</h1>
        <p class="text-xs sm:text-sm text-gray-400 mt-3 leading-relaxed">
            Effective Date: January 1, 2026 • Feather Fits Apparel Co. is committed to protecting your personal information and privacy rights under Indian IT Act 2000.
        </p>
    </div>

    <div class="glass-panel p-6 sm:p-10 rounded-3xl border border-white/10 space-y-8 text-xs sm:text-sm text-gray-300 leading-relaxed" data-aos="fade-up">
        
        <section class="space-y-3">
            <h2 class="text-base sm:text-lg font-bold font-cinzel text-white flex items-center gap-2">
                <i data-lucide="lock" class="w-4 h-4 text-purple-400"></i>
                <span>1. Information We Collect</span>
            </h2>
            <p>We collect essential information required to deliver your luxury streetwear orders efficiently:</p>
            <ul class="list-disc pl-5 space-y-1.5 text-gray-400">
                <li><strong>Contact Information:</strong> Customer full name, delivery address, phone number, and email address.</li>
                <li><strong>Order History:</strong> Product SKUs, apparel sizing selections, and delivery status logs.</li>
                <li><strong>Device & Browsing Metrics:</strong> Anonymous telemetry, session identifiers, and IP locations to prevent bot submissions.</li>
            </ul>
        </section>

        <section class="space-y-3">
            <h2 class="text-base sm:text-lg font-bold font-cinzel text-white flex items-center gap-2">
                <i data-lucide="credit-card" class="w-4 h-4 text-purple-400"></i>
                <span>2. Payment Data & PCI-DSS SAQ A Zero-Storage</span>
            </h2>
            <p>
                Feather Fits does <strong>NOT</strong> collect, view, or store full credit/debit card numbers, CVVs, or bank netbanking passwords. All online card transactions execute directly inside regulated bank iframes tokenized via PCI Level-1 gateway infrastructure.
            </p>
        </section>

        <section class="space-y-3">
            <h2 class="text-base sm:text-lg font-bold font-cinzel text-white flex items-center gap-2">
                <i data-lucide="truck" class="w-4 h-4 text-purple-400"></i>
                <span>3. Logistics & Third-Party Sharing</span>
            </h2>
            <p>
                Your phone number and delivery location are strictly shared only with our official verified logistics courier partners (Blue Dart, Delhivery) for live parcel dispatch and OTP delivery confirmation. We never sell customer information to third-party advertisers.
            </p>
        </section>

        <section class="space-y-3">
            <h2 class="text-base sm:text-lg font-bold font-cinzel text-white flex items-center gap-2">
                <i data-lucide="mail" class="w-4 h-4 text-purple-400"></i>
                <span>4. Grievance Redressal Officer</span>
            </h2>
            <p>For any privacy requests or data deletion petitions, contact our designated Grievance Officer:</p>
            <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/5 text-xs text-gray-400 space-y-1">
                <p><strong class="text-white">Officer:</strong> Arun Kumar (Chief Privacy Officer)</p>
                <p><strong class="text-white">Email:</strong> privacy@featherfits.com</p>
                <p><strong class="text-white">Address:</strong> Feather Fits Apparel Co., 42 Gandhi Road, T. Nagar, Chennai 600017</p>
            </div>
        </section>

    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
