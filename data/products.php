<?php
/**
 * FEATHER FITS — PHP DATA HELPER & CATALOG ENGINE
 * Provides clean access to products, banners, departments, and arrivals.
 */

function getFeatherProducts() {
    static $products = null;
    if ($products !== null) {
        return $products;
    }
    $jsonPath = __DIR__ . '/products.json';
    if (file_exists($jsonPath)) {
        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);
        if (is_array($data)) {
            $products = $data;
            return $products;
        }
    }
    return [];
}

function getFeatherProductById($id) {
    if (empty($id)) return null;
    $products = getFeatherProducts();
    foreach ($products as $p) {
        if (strcasecmp($p['id'], $id) === 0) {
            return $p;
        }
    }
    return null;
}

function getFeatherBanners() {
    static $banners = null;
    if ($banners !== null) {
        return $banners;
    }
    $jsonPath = __DIR__ . '/banners.json';
    if (file_exists($jsonPath)) {
        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);
        if (is_array($data)) {
            $banners = $data;
            return $banners;
        }
    }
    return [
        'all' => [
            'badge' => 'FEATHER FITS • CONFIDENCE FITS YOU',
            'title' => 'Confidence Fits You. <span>Express Every Vibe.</span>',
            'subtitle' => 'Elevate your daily rotation with heavyweight combed cotton tees, iconic anime art, cinematic vintage graphics, and modern street tailoring across Men, Women & Kids.',
            'image' => 'assets/images/hero-banner.jpg',
            'primaryCta' => ['label' => "Explore Men's Drops", 'dept' => 'men'],
            'secondaryCta' => ['label' => "Explore Women's Fits", 'dept' => 'women']
        ]
    ];
}

function getFeatherBannerByDept($dept = 'all') {
    $banners = getFeatherBanners();
    return $banners[$dept] ?? $banners['all'];
}

function getFeatherDepartments() {
    return [
        ['id' => 'all', 'name' => 'Home', 'slug' => 'all', 'icon' => '✨'],
        ['id' => 'men', 'name' => 'Men', 'slug' => 'men', 'icon' => '👔'],
        ['id' => 'women', 'name' => 'Women', 'slug' => 'women', 'icon' => '👗'],
        ['id' => 'kids', 'name' => 'Kids', 'slug' => 'kids', 'icon' => '🧒'],
    ];
}

function getFeatherSubcategories($dept = 'all') {
    $subcategories = [
        'all' => [
            ['id' => 'all', 'name' => 'All Fits'],
            ['id' => 'men-tshirts', 'name' => "Men's Graphic Tees"],
            ['id' => 'men-shirts', 'name' => "Men's Shirts"],
            ['id' => 'women-tshirts', 'name' => "Women's Tees & Tops"],
            ['id' => 'women-shirts', 'name' => "Women's Shirts"],
            ['id' => 'kids-tshirts', 'name' => "Kids' Anime Tees"],
        ],
        'men' => [
            ['id' => 'all', 'name' => 'All Men'],
            ['id' => 'men-tshirts', 'name' => 'T-Shirts & Anime'],
            ['id' => 'men-shirts', 'name' => 'Casual & Linen Shirts'],
            ['id' => 'men-bottoms', 'name' => 'Cargos & Bottoms'],
        ],
        'women' => [
            ['id' => 'all', 'name' => 'All Women'],
            ['id' => 'women-tshirts', 'name' => 'T-Shirts & Crop Tops'],
            ['id' => 'women-shirts', 'name' => 'Oversized Shirts'],
            ['id' => 'women-bottoms', 'name' => 'Blazers & Co-Ords'],
        ],
        'kids' => [
            ['id' => 'all', 'name' => 'All Kids'],
            ['id' => 'kids-tshirts', 'name' => 'Anime Graphic Tees'],
            ['id' => 'kids-shirts', 'name' => 'Casual Overshirts'],
            ['id' => 'kids-sets', 'name' => 'Play Sets & Joggers'],
        ]
    ];
    return $subcategories[$dept] ?? $subcategories['all'];
}

function getFeatherNewArrivals($dept = 'men') {
    $products = getFeatherProducts();
    $arrivals = [];
    foreach ($products as $p) {
        $pDept = $p['department'] ?? 'men';
        if ($dept === 'all' || $pDept === $dept) {
            $badge = strtolower($p['badge'] ?? '');
            if (strpos($badge, 'new') !== false || strpos($badge, 'trending') !== false || strpos($badge, 'bestseller') !== false || count($arrivals) < 6) {
                $arrivals[] = $p;
            }
        }
    }
    return !empty($arrivals) ? array_slice($arrivals, 0, 8) : array_slice($products, 0, 6);
}

function formatPrice($amount) {
    return '₹' . number_format((float)$amount, 0, '.', ',');
}

function getProductPrimaryImage($product) {
    if (!empty($product['images']) && is_array($product['images'])) {
        return $product['images'][0];
    }
    if (!empty($product['image'])) {
        return $product['image'];
    }
    return 'assets/images/plain-tee.jpg';
}

function getProductBasePrice($product) {
    return $product['basePrice'] ?? $product['price'] ?? 999;
}

function getProductOriginalPrice($product) {
    return $product['originalPrice'] ?? round(getProductBasePrice($product) * 1.35);
}
