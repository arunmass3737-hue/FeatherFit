<?php
$currentDept = $currentDept ?? ($_GET['department'] ?? 'all');
?>
<!-- Announcement Bar -->
<div class="bg-gradient-to-r from-purple-950 via-brand-dark to-purple-950 border-b border-white/5 py-2 px-4 text-center text-xs tracking-wider text-gray-300 font-medium">
    <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-purple-400"></i>
        <span>USE CODE <strong class="text-purple-400 font-bold tracking-widest">FEATHER20</strong> FOR 20% OFF FIRST DROP • FREE EXPRESS DISPATCH OVER ₹999</span>
    </div>
</div>

<!-- Main Navigation Header -->
<header class="sticky top-0 z-50 glass-panel border-b border-white/10 transition-all duration-300" id="main-header">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
        
        <!-- Left: Mobile Menu Toggle & Brand Logo -->
        <div class="flex items-center gap-3">
            <button type="button" class="md:hidden p-2 text-gray-300 hover:text-white rounded-lg hover:bg-white/5 transition" onclick="toggleMobileMenu()" aria-label="Open Navigation Menu">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            
            <a href="index.php" class="flex items-center gap-2 group">
                <img src="assets/images/feather-fits-logo.png" alt="Feather Fits" class="h-9 sm:h-11 w-auto transition-transform group-hover:scale-105 duration-300" />
            </a>
        </div>

        <!-- Center: Desktop Department Navigation -->
        <nav class="hidden md:flex items-center gap-1 lg:gap-2">
            <a href="index.php?department=all" class="px-4 py-2 rounded-full text-xs lg:text-sm font-semibold uppercase tracking-wider transition-all <?= ($currentDept === 'all') ? 'bg-purple-600/20 text-purple-300 border border-purple-500/40 shadow-glow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' ?>">
                Home
            </a>
            <a href="index.php?department=men" class="px-4 py-2 rounded-full text-xs lg:text-sm font-semibold uppercase tracking-wider transition-all <?= ($currentDept === 'men') ? 'bg-purple-600/20 text-purple-300 border border-purple-500/40 shadow-glow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' ?>">
                Men
            </a>
            <a href="index.php?department=women" class="px-4 py-2 rounded-full text-xs lg:text-sm font-semibold uppercase tracking-wider transition-all <?= ($currentDept === 'women') ? 'bg-purple-600/20 text-purple-300 border border-purple-500/40 shadow-glow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' ?>">
                Women
            </a>
            <a href="index.php?department=kids" class="px-4 py-2 rounded-full text-xs lg:text-sm font-semibold uppercase tracking-wider transition-all relative <?= ($currentDept === 'kids') ? 'bg-purple-600/20 text-purple-300 border border-purple-500/40 shadow-glow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' ?>">
                Kids
                <span class="absolute -top-1 -right-1 px-1.5 py-0.5 bg-gradient-to-r from-purple-500 to-indigo-500 text-[9px] font-bold uppercase rounded-full text-white shadow-sm">New</span>
            </a>
        </nav>

        <!-- Right: Actions (Wishlist, Cart, Account) -->
        <div class="flex items-center gap-2 sm:gap-3">
            
            <!-- Quick Size Chart Modal Trigger -->
            <button type="button" class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs text-gray-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition" onclick="openSizeGuideModal()">
                <i data-lucide="ruler" class="w-3.5 h-3.5 text-purple-400"></i>
                <span class="font-medium">Size Guide</span>
            </button>

            <!-- Wishlist Button -->
            <button type="button" class="relative p-2.5 rounded-full text-gray-300 hover:text-white hover:bg-white/5 transition" onclick="openWishlistDrawer()" aria-label="Saved Wishlist">
                <i data-lucide="heart" class="w-5 h-5"></i>
                <span id="nav-wishlist-count" class="hidden absolute top-1 right-1 w-4 h-4 bg-purple-500 text-[10px] font-black rounded-full flex items-center justify-center text-white">0</span>
            </button>

            <!-- Shopping Cart Button -->
            <button type="button" class="relative p-2.5 rounded-full text-gray-300 hover:text-white hover:bg-white/5 transition" onclick="openCartDrawer()" aria-label="Shopping Bag">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                <span id="nav-cart-count" class="hidden absolute top-1 right-1 w-4 h-4 bg-purple-500 text-[10px] font-black rounded-full flex items-center justify-center text-white">0</span>
            </button>

            <!-- User Dashboard Account -->
            <a href="account.php" class="p-2.5 rounded-full text-gray-300 hover:text-white hover:bg-white/5 transition" aria-label="User Account">
                <i data-lucide="user" class="w-5 h-5"></i>
            </a>

            <!-- Express Checkout Button -->
            <a href="checkout.php" class="hidden lg:inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white text-xs font-bold uppercase tracking-wider rounded-full shadow-glow-sm hover:shadow-glow-purple transition duration-200">
                <span>Checkout</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
    </div>
</header>

<!-- Mobile Slide-Out Drawer -->
<div id="mobile-menu-drawer" class="fixed inset-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out pointer-events-none">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm pointer-events-auto" onclick="toggleMobileMenu()"></div>
    
    <div class="relative w-80 max-w-[85vw] h-full bg-brand-card border-r border-white/10 p-6 flex flex-col justify-between pointer-events-auto overflow-y-auto">
        <div>
            <!-- Header inside drawer -->
            <div class="flex items-center justify-between pb-6 border-b border-white/10">
                <img src="assets/images/feather-fits-logo.png" alt="Feather Fits" class="h-9 w-auto" />
                <button type="button" class="p-2 text-gray-400 hover:text-white rounded-lg hover:bg-white/5 transition" onclick="toggleMobileMenu()">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Department Navigation Links -->
            <div class="py-6">
                <span class="text-[11px] uppercase tracking-widest text-gray-400 font-bold block mb-3">Browse Collections</span>
                <nav class="space-y-1">
                    <a href="index.php?department=all" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition <?= ($currentDept === 'all') ? 'bg-purple-600/20 text-purple-300 border border-purple-500/30' : 'text-gray-300 hover:text-white hover:bg-white/5' ?>">
                        <i data-lucide="sparkles" class="w-4 h-4 text-purple-400"></i>
                        <span>Home & All Drops</span>
                    </a>
                    <a href="index.php?department=men" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition <?= ($currentDept === 'men') ? 'bg-purple-600/20 text-purple-300 border border-purple-500/30' : 'text-gray-300 hover:text-white hover:bg-white/5' ?>">
                        <i data-lucide="shirt" class="w-4 h-4 text-blue-400"></i>
                        <span>Men's Streetwear</span>
                    </a>
                    <a href="index.php?department=women" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition <?= ($currentDept === 'women') ? 'bg-purple-600/20 text-purple-300 border border-purple-500/30' : 'text-gray-300 hover:text-white hover:bg-white/5' ?>">
                        <i data-lucide="sparkle" class="w-4 h-4 text-pink-400"></i>
                        <span>Women's Collection</span>
                    </a>
                    <a href="index.php?department=kids" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition <?= ($currentDept === 'kids') ? 'bg-purple-600/20 text-purple-300 border border-purple-500/30' : 'text-gray-300 hover:text-white hover:bg-white/5' ?>">
                        <div class="flex items-center gap-3">
                            <i data-lucide="smile" class="w-4 h-4 text-amber-400"></i>
                            <span>Kids' Urban Drops</span>
                        </div>
                        <span class="px-2 py-0.5 bg-purple-500/30 text-purple-300 text-[10px] font-bold rounded-full border border-purple-500/40">NEW</span>
                    </a>
                </nav>
            </div>

            <!-- Quick Service Shortcuts -->
            <div class="pt-4 border-t border-white/10">
                <span class="text-[11px] uppercase tracking-widest text-gray-400 font-bold block mb-3">Customer Services</span>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <a href="account.php?tab=tracking" class="p-3 bg-white/5 hover:bg-white/10 border border-white/5 rounded-xl flex flex-col items-center gap-1.5 text-center transition">
                        <i data-lucide="truck" class="w-4 h-4 text-purple-400"></i>
                        <span class="font-medium text-gray-200">Track Order</span>
                    </a>
                    <button type="button" onclick="openSizeGuideModal(); toggleMobileMenu();" class="p-3 bg-white/5 hover:bg-white/10 border border-white/5 rounded-xl flex flex-col items-center gap-1.5 text-center transition">
                        <i data-lucide="ruler" class="w-4 h-4 text-purple-400"></i>
                        <span class="font-medium text-gray-200">Size Chart</span>
                    </button>
                    <button type="button" onclick="openWishlistDrawer(); toggleMobileMenu();" class="p-3 bg-white/5 hover:bg-white/10 border border-white/5 rounded-xl flex flex-col items-center gap-1.5 text-center transition">
                        <i data-lucide="heart" class="w-4 h-4 text-purple-400"></i>
                        <span class="font-medium text-gray-200">Wishlist</span>
                    </button>
                    <a href="checkout.php" class="p-3 bg-purple-600/20 hover:bg-purple-600/30 border border-purple-500/30 rounded-xl flex flex-col items-center gap-1.5 text-center transition">
                        <i data-lucide="credit-card" class="w-4 h-4 text-purple-400"></i>
                        <span class="font-medium text-purple-200">Checkout</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Drawer Footer -->
        <div class="pt-6 border-t border-white/10 text-xs text-gray-500 flex items-center justify-between">
            <span>&copy; 2026 Feather Fits</span>
            <span class="text-emerald-400 flex items-center gap-1 font-medium">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                PCI-DSS Safe
            </span>
        </div>
    </div>
</div>
