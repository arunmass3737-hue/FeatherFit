<?php
/**
 * FEATHER FITS — PHP DATA HELPER & CATALOG ENGINE
 * Provides clean access to products, banners, departments, and arrivals.
 */

require_once dirname(__DIR__) . '/config/db.php';

function mapDbProductRow($r) {
    return [
        'id' => $r['id'],
        'department' => $r['department'],
        'category' => $r['category'],
        'categoryName' => $r['category_name'],
        'name' => $r['name'],
        'basePrice' => (float)$r['base_price'],
        'originalPrice' => (float)$r['original_price'],
        'rating' => (float)($r['rating'] ?? 4.9),
        'reviewsCount' => (int)($r['reviews_count'] ?? 100),
        'badge' => $r['badge'] ?? '',
        'description' => $r['description'] ?? '',
        'images' => is_string($r['images']) ? json_decode($r['images'], true) : ($r['images'] ?? []),
        'colors' => is_string($r['colors']) ? json_decode($r['colors'], true) : ($r['colors'] ?? []),
        'sizes' => is_string($r['sizes']) ? json_decode($r['sizes'], true) : ($r['sizes'] ?? []),
        'stock' => is_string($r['stock']) ? json_decode($r['stock'], true) : ($r['stock'] ?? [])
    ];
}

function getFeatherProducts() {
    static $products = null;
    if ($products !== null) {
        return $products;
    }

    // 1. Live Query from Hostinger MySQL Database
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->query("SELECT * FROM products ORDER BY id ASC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                $mapped = [];
                foreach ($rows as $r) {
                    $mapped[] = mapDbProductRow($r);
                }
                $products = $mapped;
                return $products;
            }
        } catch (Exception $e) {
            error_log("MySQL getFeatherProducts Error: " . $e->getMessage());
        }
    }

    // 2. Fallback to products.json if MySQL is offline or not yet configured
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

    // 1. Direct MySQL Query
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return mapDbProductRow($row);
            }
        } catch (Exception $e) {
            error_log("MySQL getFeatherProductById Error: " . $e->getMessage());
        }
    }

    // 2. Fallback to array iteration
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

    // 1. Live Query from Hostinger MySQL Database
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->query("SELECT * FROM banners");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                $result = [];
                foreach ($rows as $r) {
                    $dept = $r['department'] ?? $r['dept'] ?? 'all';
                    $primCta = is_string($r['primary_cta'] ?? null) ? json_decode($r['primary_cta'], true) : ($r['primary_cta'] ?? null);
                    if (!$primCta || !is_array($primCta)) {
                        $primCta = [
                            'label' => $r['primary_cta_label'] ?? 'Shop Now',
                            'dept' => $r['primary_cta_dept'] ?? 'men'
                        ];
                    }
                    $secCta = is_string($r['secondary_cta'] ?? null) ? json_decode($r['secondary_cta'], true) : ($r['secondary_cta'] ?? null);
                    if (!$secCta || !is_array($secCta)) {
                        $secCta = [
                            'label' => $r['secondary_cta_label'] ?? 'Explore',
                            'dept' => $r['secondary_cta_dept'] ?? 'women'
                        ];
                    }

                    $result[$dept] = [
                        'badge' => $r['badge'] ?? '',
                        'title' => $r['title'] ?? '',
                        'subtitle' => $r['subtitle'] ?? '',
                        'image' => $r['image'] ?? 'assets/images/hero-banner.jpg',
                        'primaryCta' => $primCta,
                        'secondaryCta' => $secCta
                    ];
                }
                $banners = $result;
                return $banners;
            }
        } catch (Exception $e) {
            error_log("MySQL getFeatherBanners Error: " . $e->getMessage());
        }
    }

    // 2. Fallback to banners.json
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

function getFeatherAnnouncement() {
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->query("SELECT text FROM announcements ORDER BY id ASC LIMIT 1");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!empty($row['text'])) return $row['text'];
        } catch (Exception $e) {}
    }

    $annPath = __DIR__ . '/announcement.json';
    if (file_exists($annPath)) {
        $data = json_decode(file_get_contents($annPath), true);
        if (!empty($data['text'])) return $data['text'];
    }
    return "USE CODE <strong>FEATHER20</strong> FOR 20% OFF ALL OVERSIZED STREETWEAR • FREE EXPRESS SHIPPING OVER ₹999";
}
