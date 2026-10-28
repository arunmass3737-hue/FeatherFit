<?php
require_once __DIR__ . '/data/products.php';

$currentDept = $_GET['department'] ?? 'all';
$pageTitle = "Feather Fits — Luxury Streetwear & Graphic Drops | Confidence Fits You";
$pageDescription = "Discover Feather Fits luxury streetwear, pre-shrunk 240 GSM graphic tees, vintage cinema prints, and premium oversized fits.";

if ($currentDept === 'men') {
    $pageTitle = "Men's Streetwear & Graphic Apparel | Feather Fits";
    $pageDescription = "Shop Men's oversized graphic tees, heavyweight cotton drops, anime streetwear, and minimalist wardrobe essentials.";
} elseif ($currentDept === 'women') {
    $pageTitle = "Women's Oversized Tees & Co-ords | Feather Fits";
    $pageDescription = "Explore Women's relaxed fits, crop silhouettes, luxury streetwear aesthetics, and exclusive anime collabs.";
} elseif ($currentDept === 'kids') {
    $pageTitle = "Kids' Streetwear & Anime Graphic Collection | Feather Fits";
    $pageDescription = "Comfortable, durable 100% cotton streetwear and bold graphic tees designed for active boys & girls.";
}

$allProducts = getFeatherProducts();
$banners = getFeatherBanners();
$newArrivalsMen = getFeatherNewArrivals('men');
$newArrivalsWomen = getFeatherNewArrivals('women');
$newArrivalsKids = getFeatherNewArrivals('kids');

// Filter products based on selected department
$filteredProducts = $allProducts;
if ($currentDept !== 'all') {
    $filteredProducts = array_filter($allProducts, function($p) use ($currentDept) {
        return ($p['department'] ?? '') === $currentDept;
    });
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>

<!-- ========================================== -->
<!-- 1. HERO SWIPER SLIDER (Interactive & Modern) -->
<!-- ========================================== -->
<section class="relative overflow-hidden bg-brand-dark" data-aos="fade">
    <div class="swiper heroSwiper w-full h-[540px] sm:h-[620px]">
        <div class="swiper-wrapper">
            
            <!-- Slide 1: Main Brand Drop -->
            <div class="swiper-slide relative flex items-center">
                <img src="assets/images/hero-banner.jpg" alt="Streetwear Collection" class="absolute inset-0 w-full h-full object-cover object-center scale-105" />
                <div class="absolute inset-0 bg-gradient-to-r from-brand-dark via-brand-dark/80 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-transparent to-transparent"></div>
                
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-12">
                    <div class="max-w-2xl space-y-5" data-aos="fade-up" data-aos-delay="100">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-purple-600/20 border border-purple-500/30 text-purple-300 text-xs font-bold uppercase tracking-widest shadow-glow-sm">
                            <i data-lucide="zap" class="w-3.5 h-3.5 text-purple-400"></i>
                            <span>LIMITED EDITION DROPS • 240 GSM</span>
                        </div>
                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black font-cinzel text-white tracking-tight leading-tight">
                            CONFIDENCE <br /><span class="bg-gradient-to-r from-purple-400 via-pink-300 to-indigo-400 bg-clip-text text-transparent">FITS YOU.</span>
                        </h1>
                        <p class="text-sm sm:text-base text-gray-300 max-w-xl font-normal leading-relaxed">
                            Heavyweight luxury streetwear tailored for effortless attitude. Explore iconic anime art, vintage cinema wash, and minimalist daily essentials.
                        </p>
                        <div class="pt-2 flex flex-wrap gap-4 items-center">
                            <a href="#catalog-section" class="px-7 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-full text-xs font-bold uppercase tracking-wider shadow-glow-purple hover:scale-105 transition duration-200 flex items-center gap-2">
                                <span>Shop Current Drop</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                            <a href="index.php?department=men#catalog-section" class="px-6 py-3.5 bg-white/5 hover:bg-white/10 border border-white/10 text-white rounded-full text-xs font-semibold uppercase tracking-wider hover:border-purple-500/40 transition">
                                Men's Fits
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Men's Streetwear Focus -->
            <div class="swiper-slide relative flex items-center">
                <img src="assets/images/mens-apparel.jpg" alt="Men's Apparel" class="absolute inset-0 w-full h-full object-cover object-center" />
                <div class="absolute inset-0 bg-gradient-to-r from-brand-dark via-brand-dark/85 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-transparent to-transparent"></div>

                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-12">
                    <div class="max-w-2xl space-y-5">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-600/20 border border-blue-500/30 text-blue-300 text-xs font-bold uppercase tracking-widest">
                            <i data-lucide="shirt" class="w-3.5 h-3.5 text-blue-400"></i>
                            <span>MEN'S STREETWEAR ARCHIVE</span>
                        </div>
                        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black font-cinzel text-white tracking-tight leading-tight">
                            OVERSIZED FIT. <br /><span class="text-blue-400">RAW STREET ART.</span>
                        </h2>
                        <p class="text-sm sm:text-base text-gray-300 max-w-xl font-normal leading-relaxed">
                            Bio-washed pre-shrunk cotton tees with high-density anime & cinema screenprints designed to last 100+ washes without cracking.
                        </p>
                        <div class="pt-2 flex flex-wrap gap-4 items-center">
                            <a href="index.php?department=men#catalog-section" class="px-7 py-3.5 bg-blue-600 hover:bg-blue-500 text-white rounded-full text-xs font-bold uppercase tracking-wider shadow-lg hover:scale-105 transition flex items-center gap-2">
                                <span>Browse Men's Drop</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Women's Luxury Street Focus -->
            <div class="swiper-slide relative flex items-center">
                <img src="assets/images/womens-apparel.jpg" alt="Women's Apparel" class="absolute inset-0 w-full h-full object-cover object-center" />
                <div class="absolute inset-0 bg-gradient-to-r from-brand-dark via-brand-dark/85 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-transparent to-transparent"></div>

                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-12">
                    <div class="max-w-2xl space-y-5">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-pink-600/20 border border-pink-500/30 text-pink-300 text-xs font-bold uppercase tracking-widest">
                            <i data-lucide="sparkle" class="w-3.5 h-3.5 text-pink-400"></i>
                            <span>WOMEN'S LUXURY STREETWEAR</span>
                        </div>
                        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black font-cinzel text-white tracking-tight leading-tight">
                            BOX-CUT TEES & <br /><span class="text-pink-400">LINEN OVERLAY.</span>
                        </h2>
                        <p class="text-sm sm:text-base text-gray-300 max-w-xl font-normal leading-relaxed">
                            Breezy drop-shoulder silhouettes, cropped vintage aesthetics, and effortless everyday coordinates.
                        </p>
                        <div class="pt-2 flex flex-wrap gap-4 items-center">
                            <a href="index.php?department=women#catalog-section" class="px-7 py-3.5 bg-pink-600 hover:bg-pink-500 text-white rounded-full text-xs font-bold uppercase tracking-wider shadow-lg hover:scale-105 transition flex items-center gap-2">
                                <span>Explore Women's Fits</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Swiper Controls -->
        <div class="swiper-button-next !right-6"></div>
        <div class="swiper-button-prev !left-6"></div>
        <div class="swiper-pagination !bottom-8"></div>
    </div>
</section>

<!-- ========================================== -->
<!-- 2. NEW ARRIVALS SWIPER CAROUSEL (Smooth Mobile Swipe) -->
<!-- ========================================== -->
<section class="py-16 sm:py-24 bg-brand-card/40 border-y border-white/5" data-aos="fade-up">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header with Department Filter Tabs -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <div class="flex items-center gap-2 text-xs uppercase tracking-widest text-purple-400 font-bold mb-2">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span>Fresh Street Arrivals</span>
                </div>
                <h2 class="text-2xl sm:text-4xl font-black font-cinzel text-white tracking-tight">
                    New In The Rotation
                </h2>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">Swipe through the latest seasonal releases</p>
            </div>

            <!-- Department Selector Tabs -->
            <div class="flex items-center gap-2 p-1.5 bg-white/5 border border-white/10 rounded-full">
                <button type="button" class="tab-arrival-btn active px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-purple-600 text-white shadow-glow-sm" onclick="switchArrivalsTab('men', this)">
                    Men
                </button>
                <button type="button" class="tab-arrival-btn px-4 py-1.5 rounded-full text-xs font-semibold text-gray-400 hover:text-white transition-all" onclick="switchArrivalsTab('women', this)">
                    Women
                </button>
                <button type="button" class="tab-arrival-btn px-4 py-1.5 rounded-full text-xs font-semibold text-gray-400 hover:text-white transition-all" onclick="switchArrivalsTab('kids', this)">
                    Kids
                </button>
            </div>
        </div>

        <!-- Swiper Container for Men's Arrivals -->
        <div id="arrivals-swiper-men" class="arrivals-slider-wrap">
            <div class="swiper arrivalsSwiper w-full">
                <div class="swiper-wrapper py-4">
                    <?php foreach ($newArrivalsMen as $item): ?>
                    <div class="swiper-slide h-auto">
                        <div class="glass-card rounded-2xl overflow-hidden group flex flex-col h-full">
                            <div class="relative overflow-hidden aspect-[4/5] bg-black">
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                                <span class="absolute top-3 left-3 px-2.5 py-1 bg-black/80 backdrop-blur-md text-[10px] font-extrabold uppercase rounded-full text-purple-300 border border-purple-500/30">
                                    <?= htmlspecialchars($item['badge'] ?? 'FRESH DROP') ?>
                                </span>
                                <button type="button" onclick="addToCartDirect('<?= $item['id'] ?>', '<?= addslashes($item['name']) ?>', <?= $item['price'] ?>, '<?= $item['image'] ?>')" class="absolute bottom-3 right-3 p-3 bg-purple-600 hover:bg-purple-500 text-white rounded-full shadow-lg opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300" title="Quick Add">
                                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <div class="p-4 flex flex-col flex-1 justify-between">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider"><?= htmlspecialchars($item['tag'] ?? 'Streetwear') ?></span>
                                    <h3 class="text-sm font-bold text-white mt-1 line-clamp-1 group-hover:text-purple-300 transition">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </h3>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-sm font-bold text-white">₹<?= number_format($item['price']) ?></span>
                                    <a href="product.php?id=<?= $item['id'] ?>" class="text-xs text-purple-400 hover:text-purple-300 font-semibold flex items-center gap-1">
                                        <span>View</span>
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination !-bottom-2"></div>
            </div>
        </div>

        <!-- Swiper Container for Women's Arrivals -->
        <div id="arrivals-swiper-women" class="arrivals-slider-wrap hidden">
            <div class="swiper arrivalsSwiperWomen w-full">
                <div class="swiper-wrapper py-4">
                    <?php foreach ($newArrivalsWomen as $item): ?>
                    <div class="swiper-slide h-auto">
                        <div class="glass-card rounded-2xl overflow-hidden group flex flex-col h-full">
                            <div class="relative overflow-hidden aspect-[4/5] bg-black">
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                                <span class="absolute top-3 left-3 px-2.5 py-1 bg-black/80 backdrop-blur-md text-[10px] font-extrabold uppercase rounded-full text-pink-300 border border-pink-500/30">
                                    <?= htmlspecialchars($item['badge'] ?? 'FRESH DROP') ?>
                                </span>
                                <button type="button" onclick="addToCartDirect('<?= $item['id'] ?>', '<?= addslashes($item['name']) ?>', <?= $item['price'] ?>, '<?= $item['image'] ?>')" class="absolute bottom-3 right-3 p-3 bg-purple-600 hover:bg-purple-500 text-white rounded-full shadow-lg opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300" title="Quick Add">
                                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <div class="p-4 flex flex-col flex-1 justify-between">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider"><?= htmlspecialchars($item['tag'] ?? 'Streetwear') ?></span>
                                    <h3 class="text-sm font-bold text-white mt-1 line-clamp-1 group-hover:text-pink-300 transition">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </h3>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-sm font-bold text-white">₹<?= number_format($item['price']) ?></span>
                                    <a href="product.php?id=<?= $item['id'] ?>" class="text-xs text-pink-400 hover:text-pink-300 font-semibold flex items-center gap-1">
                                        <span>View</span>
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination !-bottom-2"></div>
            </div>
        </div>

        <!-- Swiper Container for Kids' Arrivals -->
        <div id="arrivals-swiper-kids" class="arrivals-slider-wrap hidden">
            <div class="swiper arrivalsSwiperKids w-full">
                <div class="swiper-wrapper py-4">
                    <?php foreach ($newArrivalsKids as $item): ?>
                    <div class="swiper-slide h-auto">
                        <div class="glass-card rounded-2xl overflow-hidden group flex flex-col h-full">
                            <div class="relative overflow-hidden aspect-[4/5] bg-black">
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                                <span class="absolute top-3 left-3 px-2.5 py-1 bg-black/80 backdrop-blur-md text-[10px] font-extrabold uppercase rounded-full text-amber-300 border border-amber-500/30">
                                    <?= htmlspecialchars($item['badge'] ?? 'FRESH DROP') ?>
                                </span>
                                <button type="button" onclick="addToCartDirect('<?= $item['id'] ?>', '<?= addslashes($item['name']) ?>', <?= $item['price'] ?>, '<?= $item['image'] ?>')" class="absolute bottom-3 right-3 p-3 bg-purple-600 hover:bg-purple-500 text-white rounded-full shadow-lg opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300" title="Quick Add">
                                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <div class="p-4 flex flex-col flex-1 justify-between">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider"><?= htmlspecialchars($item['tag'] ?? 'Streetwear') ?></span>
                                    <h3 class="text-sm font-bold text-white mt-1 line-clamp-1 group-hover:text-amber-300 transition">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </h3>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-sm font-bold text-white">₹<?= number_format($item['price']) ?></span>
                                    <a href="product.php?id=<?= $item['id'] ?>" class="text-xs text-amber-400 hover:text-amber-300 font-semibold flex items-center gap-1">
                                        <span>View</span>
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination !-bottom-2"></div>
            </div>
        </div>

    </div>
</section>

<!-- ========================================== -->
<!-- 3. CURATED CATALOG SECTION (Dynamic & Filterable) -->
<!-- ========================================== -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24" id="catalog-section">
    
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs text-gray-400 mb-6" data-aos="fade-up">
        <a href="index.php?department=all" class="hover:text-white transition">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-600"></i>
        <span class="text-purple-300 font-bold uppercase tracking-wider">
            <?= ($currentDept === 'men') ? "Men's Collection" : (($currentDept === 'women') ? "Women's Collection" : (($currentDept === 'kids') ? "Kids' Streetwear" : "All Collections")) ?>
        </span>
    </div>

    <!-- Section Title & Subtitle -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8" data-aos="fade-up">
        <div>
            <h2 class="text-2xl sm:text-4xl font-black font-cinzel text-white tracking-tight">
                <?= ($currentDept === 'men') ? "Men's Streetwear & Tailoring" : (($currentDept === 'women') ? "Women's Contemporary Fits" : (($currentDept === 'kids') ? "Kids' Anime & Playwear" : "The Complete Drop Archive")) ?>
            </h2>
            <p class="text-xs sm:text-sm text-gray-400 mt-1">Pre-shrunk 240 GSM bio-washed heavy combed cotton</p>
        </div>

        <!-- Department Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0">
            <a href="index.php?department=all#catalog-section" class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition <?= ($currentDept === 'all') ? 'bg-purple-600 text-white shadow-glow-sm' : 'bg-white/5 text-gray-300 hover:text-white border border-white/10' ?>">
                All Drops
            </a>
            <a href="index.php?department=men#catalog-section" class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition <?= ($currentDept === 'men') ? 'bg-purple-600 text-white shadow-glow-sm' : 'bg-white/5 text-gray-300 hover:text-white border border-white/10' ?>">
                Men
            </a>
            <a href="index.php?department=women#catalog-section" class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition <?= ($currentDept === 'women') ? 'bg-purple-600 text-white shadow-glow-sm' : 'bg-white/5 text-gray-300 hover:text-white border border-white/10' ?>">
                Women
            </a>
            <a href="index.php?department=kids#catalog-section" class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition <?= ($currentDept === 'kids') ? 'bg-purple-600 text-white shadow-glow-sm' : 'bg-white/5 text-gray-300 hover:text-white border border-white/10' ?>">
                Kids
            </a>
        </div>
    </div>

    <!-- Products Grid with AOS Animations -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        <?php foreach ($filteredProducts as $idx => $prod): ?>
        <article class="glass-card rounded-2xl overflow-hidden group flex flex-col justify-between" data-aos="fade-up" data-aos-delay="<?= ($idx % 4) * 80 ?>">
            <a href="product.php?id=<?= urlencode($prod['id']) ?>" class="block relative aspect-[4/5] bg-black overflow-hidden">
                <img src="<?= htmlspecialchars($prod['image']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                
                <?php if (!empty($prod['badge'])): ?>
                <span class="absolute top-3 left-3 px-2.5 py-1 bg-black/80 backdrop-blur-md text-[10px] font-extrabold uppercase rounded-full text-purple-300 border border-purple-500/30">
                    <?= htmlspecialchars($prod['badge']) ?>
                </span>
                <?php endif; ?>

                <?php if (!empty($prod['discount'])): ?>
                <span class="absolute top-3 right-3 px-2 py-0.5 bg-emerald-950/80 backdrop-blur-md text-[10px] font-bold text-emerald-400 border border-emerald-800/40 rounded-full">
                    <?= htmlspecialchars($prod['discount']) ?>
                </span>
                <?php endif; ?>

                <div class="absolute inset-x-3 bottom-3 flex items-center justify-between opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button type="button" onclick="event.preventDefault(); addToCartDirect('<?= $prod['id'] ?>', '<?= addslashes($prod['name']) ?>', <?= $prod['price'] ?>, '<?= $prod['image'] ?>')" class="w-full py-2.5 bg-purple-600/90 hover:bg-purple-600 text-white text-xs font-bold uppercase rounded-xl backdrop-blur-sm shadow-lg flex items-center justify-center gap-1.5 transition">
                        <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
                        <span>Quick Add</span>
                    </button>
                </div>
            </a>

            <div class="p-4 flex flex-col flex-1 justify-between">
                <div>
                    <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider"><?= htmlspecialchars($prod['category'] ?? 'Apparel') ?></span>
                    <h3 class="text-xs sm:text-sm font-bold text-white mt-1 line-clamp-2 group-hover:text-purple-300 transition">
                        <a href="product.php?id=<?= urlencode($prod['id']) ?>">
                            <?= htmlspecialchars($prod['name']) ?>
                        </a>
                    </h3>
                </div>

                <div class="mt-3 pt-3 border-t border-white/5 flex items-center justify-between">
                    <div class="flex items-baseline gap-2">
                        <span class="text-sm sm:text-base font-extrabold text-white">₹<?= number_format($prod['price']) ?></span>
                        <?php if (!empty($prod['originalPrice'])): ?>
                        <span class="text-xs text-gray-500 line-through">₹<?= number_format($prod['originalPrice']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-1 text-[11px] text-amber-400 font-bold">
                        <i data-lucide="star" class="w-3 h-3 fill-amber-400"></i>
                        <span><?= $prod['rating'] ?? '4.9' ?></span>
                    </div>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</main>

<!-- Initialize Sliders -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Hero Slider
        new Swiper('.heroSwiper', {
            loop: true,
            autoplay: {
                delay: 5500,
                disableOnInteraction: false,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            speed: 800,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });

        // New Arrivals Sliders (Responsive Breakpoints)
        initArrivalsSwiper('.arrivalsSwiper');
        initArrivalsSwiper('.arrivalsSwiperWomen');
        initArrivalsSwiper('.arrivalsSwiperKids');
    });

    function initArrivalsSwiper(selector) {
        new Swiper(selector, {
            slidesPerView: 1.3,
            spaceBetween: 14,
            grabCursor: true,
            pagination: {
                el: selector + ' .swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                540: {
                    slidesPerView: 2.2,
                    spaceBetween: 16,
                },
                768: {
                    slidesPerView: 3.2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 24,
                }
            }
        });
    }

    function switchArrivalsTab(dept, btn) {
        document.querySelectorAll('.tab-arrival-btn').forEach(b => {
            b.classList.remove('active', 'bg-purple-600', 'text-white', 'shadow-glow-sm');
            b.classList.add('text-gray-400');
        });
        btn.classList.add('active', 'bg-purple-600', 'text-white', 'shadow-glow-sm');
        btn.classList.remove('text-gray-400');

        document.querySelectorAll('.arrivals-slider-wrap').forEach(wrap => wrap.classList.add('hidden'));
        const target = document.getElementById('arrivals-swiper-' + dept);
        if (target) {
            target.classList.remove('hidden');
        }
    }
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
