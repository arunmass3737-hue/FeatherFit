<?php
require_once __DIR__ . '/data/products.php';

$productId = $_GET['id'] ?? 'p_anime_01';
$product = getFeatherProductById($productId);

if (!$product) {
    header("Location: index.php");
    exit;
}

$pageTitle = htmlspecialchars($product['name']) . " | Feather Fits Luxury Streetwear";
$pageDescription = "Buy " . htmlspecialchars($product['name']) . " online at Feather Fits. 100% Bio-Washed Combed Cotton 240 GSM with High-Density Graphic Screenprint.";

$allProducts = getFeatherProducts();
$relatedProducts = array_filter($allProducts, function($p) use ($product) {
    return $p['id'] !== $product['id'] && ($p['department'] ?? '') === ($product['department'] ?? 'men');
});
if (empty($relatedProducts)) {
    $relatedProducts = array_slice($allProducts, 0, 4);
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-gray-400 mb-8" data-aos="fade-up">
        <a href="index.php" class="hover:text-white transition">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-600"></i>
        <a href="index.php?department=<?= urlencode($product['department'] ?? 'men') ?>" class="hover:text-white transition capitalize">
            <?= htmlspecialchars($product['department'] ?? 'men') ?>
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-600"></i>
        <span class="text-purple-300 truncate max-w-xs font-semibold"><?= htmlspecialchars($product['name']) ?></span>
    </div>

    <!-- Product Grid: Gallery + Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14">
        
        <!-- Left: Product Swiper Image Gallery -->
        <div data-aos="fade-right">
            <div class="sticky top-28 space-y-4">
                <div class="swiper productGallerySwiper rounded-3xl overflow-hidden aspect-[4/5] bg-black border border-white/10 shadow-2xl">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover" id="main-product-img" />
                        </div>
                        <?php if (!empty($product['hoverImage'])): ?>
                        <div class="swiper-slide">
                            <img src="<?= htmlspecialchars($product['hoverImage']) ?>" alt="<?= htmlspecialchars($product['name']) ?> Back View" class="w-full h-full object-cover" />
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>

                <!-- Features Under Gallery -->
                <div class="grid grid-cols-3 gap-3 pt-2 text-center text-xs text-gray-400">
                    <div class="p-3 rounded-2xl bg-white/[0.02] border border-white/5 flex flex-col items-center gap-1.5">
                        <i data-lucide="feather" class="w-5 h-5 text-purple-400"></i>
                        <span class="font-bold text-gray-300">240 GSM</span>
                        <span>Heavy Cotton</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/[0.02] border border-white/5 flex flex-col items-center gap-1.5">
                        <i data-lucide="refresh-cw" class="w-5 h-5 text-purple-400"></i>
                        <span class="font-bold text-gray-300">7 Days</span>
                        <span>Free Return</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/[0.02] border border-white/5 flex flex-col items-center gap-1.5">
                        <i data-lucide="shield-check" class="w-5 h-5 text-purple-400"></i>
                        <span class="font-bold text-gray-300">PCI-DSS</span>
                        <span>Safe Payment</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Purchasing Controls -->
        <div class="space-y-6" data-aos="fade-left">
            <div>
                <span class="inline-block px-3 py-1 bg-purple-600/20 border border-purple-500/30 text-purple-300 text-xs font-bold uppercase tracking-widest rounded-full mb-3">
                    <?= htmlspecialchars($product['brand'] ?? 'FEATHER FITS ORIGINAL') ?>
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold font-cinzel text-white leading-tight">
                    <?= htmlspecialchars($product['name']) ?>
                </h1>
                
                <!-- Rating and Reviews -->
                <div class="flex items-center gap-3 mt-3">
                    <div class="flex items-center gap-1 text-amber-400 font-bold text-sm">
                        <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                        <span><?= $product['rating'] ?? '4.9' ?></span>
                    </div>
                    <span class="text-gray-500">•</span>
                    <span class="text-xs text-gray-400 font-medium">128 verified ratings</span>
                    <span class="text-gray-500">•</span>
                    <span class="text-xs text-emerald-400 font-bold flex items-center gap-1">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> In Stock & Ready to Ship
                    </span>
                </div>
            </div>

            <!-- Price Breakdown -->
            <div class="p-5 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center justify-between">
                <div>
                    <div class="flex items-baseline gap-3">
                        <span class="text-3xl sm:text-4xl font-black text-white" id="p-price-display">₹<?= number_format($product['price']) ?></span>
                        <?php if (!empty($product['originalPrice'])): ?>
                        <span class="text-base text-gray-500 line-through">₹<?= number_format($product['originalPrice']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($product['discount'])): ?>
                        <span class="px-2 py-0.5 bg-emerald-950 text-emerald-400 text-xs font-bold rounded-full border border-emerald-800/40">
                            <?= htmlspecialchars($product['discount']) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Inclusive of all taxes • Free express doorstep delivery</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-purple-400 bg-purple-950/60 border border-purple-800/40 px-3 py-1.5 rounded-full">
                        🔥 High Demand Drop
                    </span>
                </div>
            </div>

            <!-- Select Size -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="text-xs uppercase tracking-wider text-gray-300 font-bold">Select Apparel Size</label>
                    <button type="button" onclick="openSizeGuideModal()" class="text-xs text-purple-400 hover:text-purple-300 font-semibold flex items-center gap-1">
                        <i data-lucide="ruler" class="w-3.5 h-3.5"></i>
                        <span>Size Chart</span>
                    </button>
                </div>
                <div class="grid grid-cols-5 gap-3" id="size-selector-group">
                    <?php foreach (['S', 'M', 'L', 'XL', 'XXL'] as $size): ?>
                    <button type="button" class="size-btn py-3 rounded-xl border text-xs font-bold uppercase transition <?= ($size === 'L') ? 'border-purple-500 bg-purple-600/20 text-white shadow-glow-sm' : 'border-white/10 bg-white/5 text-gray-300 hover:border-white/30' ?>" onclick="selectSize('<?= $size ?>', this)">
                        <?= $size ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Actions: Add to Bag & Buy Now -->
            <div class="space-y-3 pt-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <button type="button" onclick="addToCartCurrent()" class="py-4 bg-white/10 hover:bg-white/15 border border-white/20 text-white font-bold text-xs uppercase tracking-wider rounded-2xl flex items-center justify-center gap-2 transition duration-200">
                        <i data-lucide="shopping-bag" class="w-4 h-4 text-purple-400"></i>
                        <span>Add To Bag</span>
                    </button>
                    <button type="button" onclick="buyNowCurrent()" class="py-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-2xl shadow-glow-purple flex items-center justify-center gap-2 transition duration-200">
                        <i data-lucide="zap" class="w-4 h-4"></i>
                        <span>Express Checkout</span>
                    </button>
                </div>
                <p class="text-[11px] text-center text-gray-500 flex items-center justify-center gap-1.5">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-400"></i>
                    <span>Guaranteed genuine streetwear • Doorstep Cash or UPI QR on handover</span>
                </p>
            </div>

            <!-- Description & Specs Accordion -->
            <div class="pt-6 border-t border-white/10 space-y-4">
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-2">Design & Craftsmanship</h3>
                    <p class="text-xs sm:text-sm text-gray-400 leading-relaxed">
                        <?= htmlspecialchars($product['description'] ?? "Engineered from ultra-heavy 240 GSM 100% bio-washed combed cotton. Features seamless tubular body construction, double-needle stitched ribbed collar, and durable fade-resistant screen printing.") ?>
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-2 text-xs text-gray-300">
                    <div class="p-3 bg-white/[0.02] border border-white/5 rounded-xl">
                        <span class="text-gray-500 block text-[10px] uppercase font-bold">Fit Profile</span>
                        <span class="font-semibold text-white">Boxy Streetwear Oversized</span>
                    </div>
                    <div class="p-3 bg-white/[0.02] border border-white/5 rounded-xl">
                        <span class="text-gray-500 block text-[10px] uppercase font-bold">Fabric Composition</span>
                        <span class="font-semibold text-white">100% Combed Bio Cotton</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Related Products Swiper Slider -->
    <div class="mt-24 pt-16 border-t border-white/10" data-aos="fade-up">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-xl sm:text-3xl font-black font-cinzel text-white">You May Also Vibe With</h3>
                <p class="text-xs text-gray-400 mt-1">Curated complementary street fits</p>
            </div>
            <a href="index.php?department=<?= urlencode($product['department'] ?? 'men') ?>" class="text-xs text-purple-400 hover:text-purple-300 font-bold flex items-center gap-1">
                <span>View Full Lineup</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            <?php foreach (array_slice($relatedProducts, 0, 4) as $rel): ?>
            <div class="glass-card rounded-2xl overflow-hidden group flex flex-col justify-between">
                <a href="product.php?id=<?= urlencode($rel['id']) ?>" class="block relative aspect-[4/5] bg-black overflow-hidden">
                    <img src="<?= htmlspecialchars($rel['image']) ?>" alt="<?= htmlspecialchars($rel['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                </a>
                <div class="p-4">
                    <h4 class="text-xs font-bold text-white truncate"><?= htmlspecialchars($rel['name']) ?></h4>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-sm font-bold text-white">₹<?= number_format($rel['price']) ?></span>
                        <a href="product.php?id=<?= urlencode($rel['id']) ?>" class="text-xs text-purple-400 font-semibold flex items-center gap-1">
                            <span>Inspect</span>
                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<script>
    let selectedSize = 'L';

    function selectSize(size, btn) {
        selectedSize = size;
        document.querySelectorAll('.size-btn').forEach(b => {
            b.classList.remove('border-purple-500', 'bg-purple-600/20', 'text-white', 'shadow-glow-sm');
            b.classList.add('border-white/10', 'bg-white/5', 'text-gray-300');
        });
        btn.classList.add('border-purple-500', 'bg-purple-600/20', 'text-white', 'shadow-glow-sm');
        btn.classList.remove('border-white/10', 'bg-white/5', 'text-gray-300');
    }

    function addToCartCurrent() {
        let cart = [];
        try {
            cart = JSON.parse(localStorage.getItem('feather_cart') || localStorage.getItem('cart') || '[]');
        } catch(e){}

        const item = {
            id: '<?= $product['id'] ?>',
            name: '<?= addslashes($product['name']) ?>',
            price: <?= (int)$product['price'] ?>,
            image: '<?= $product['image'] ?>',
            size: selectedSize,
            quantity: 1
        };

        const existing = cart.find(it => it.id === item.id && it.size === selectedSize);
        if (existing) {
            existing.quantity = (existing.quantity || 1) + 1;
        } else {
            cart.push(item);
        }

        localStorage.setItem('feather_cart', JSON.stringify(cart));
        localStorage.setItem('cart', JSON.stringify(cart));
        updateDrawerBadges();
        showToast(`✓ Added "${item.name}" (Size ${selectedSize}) to Bag!`, 'success');
        openCartDrawer();
    }

    function buyNowCurrent() {
        addToCartCurrent();
        setTimeout(() => {
            window.location.href = 'checkout.php';
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.productGallerySwiper', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
