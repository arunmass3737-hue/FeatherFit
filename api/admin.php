<?php
header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$inputJSON = file_get_contents('php://input');
$inputData = json_decode($inputJSON, true) ?? $_POST;

$productsPath = dirname(__DIR__) . '/data/products.json';
$bannersPath = dirname(__DIR__) . '/data/banners.json';
$announcementPath = dirname(__DIR__) . '/data/announcement.json';

// Helper to read JSON
function readJSONFile($path, $fallback = []) {
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $data = json_decode($content, true);
        if (is_array($data)) return $data;
    }
    return $fallback;
}

// Helper to write JSON
function writeJSONFile($path, $data) {
    return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

require_once dirname(__DIR__) . '/config/db.php';

switch ($action) {
    case 'save_banner':
        $dept = $inputData['dept'] ?? 'all';
        $banner = $inputData['banner'] ?? null;
        if (!$banner || !is_array($banner)) {
            echo json_encode(['success' => false, 'error' => 'Invalid banner data']);
            exit;
        }

        $allBanners = readJSONFile($bannersPath, []);
        $allBanners[$dept] = array_merge($allBanners[$dept] ?? [], $banner);
        writeJSONFile($bannersPath, $allBanners);

        // Sync to MySQL banners table
        $pdo = getDBConnection();
        if ($pdo) {
            try {
                $sql = "INSERT INTO banners (dept, badge, title, subtitle, image, primary_cta_label, primary_cta_dept, secondary_cta_label, secondary_cta_dept)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE 
                        badge=VALUES(badge), title=VALUES(title), subtitle=VALUES(subtitle), 
                        image=VALUES(image), primary_cta_label=VALUES(primary_cta_label), primary_cta_dept=VALUES(primary_cta_dept),
                        secondary_cta_label=VALUES(secondary_cta_label), secondary_cta_dept=VALUES(secondary_cta_dept)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $dept,
                    $banner['badge'] ?? '',
                    $banner['title'] ?? '',
                    $banner['subtitle'] ?? '',
                    $banner['image'] ?? 'assets/images/hero-banner.jpg',
                    $banner['primaryCta']['label'] ?? 'Shop Now',
                    $banner['primaryCta']['dept'] ?? 'men',
                    $banner['secondaryCta']['label'] ?? 'Explore',
                    $banner['secondaryCta']['dept'] ?? 'women'
                ]);
            } catch (Exception $e) {
                error_log("MySQL save_banner error: " . $e->getMessage());
            }
        }

        echo json_encode(['success' => true, 'dept' => $dept, 'banners' => $allBanners]);
        break;

    case 'save_product':
        $product = $inputData['product'] ?? null;
        if (!$product || empty($product['name'])) {
            echo json_encode(['success' => false, 'error' => 'Product name is required']);
            exit;
        }

        $products = readJSONFile($productsPath, []);
        $pId = $product['id'] ?? ('ff-' . ($product['department'] ?? 'men') . '-' . time());
        $product['id'] = $pId;

        $found = false;
        foreach ($products as $i => $existing) {
            if ($existing['id'] === $pId) {
                $products[$i] = array_merge($existing, $product);
                $found = true;
                break;
            }
        }

        if (!$found) {
            array_unshift($products, $product);
        }

        writeJSONFile($productsPath, $products);

        // Sync to MySQL products table
        $pdo = getDBConnection();
        if ($pdo) {
            try {
                $sql = "INSERT INTO products (id, department, category, category_name, name, base_price, original_price, rating, reviews_count, badge, description, images, colors, sizes, stock)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE 
                        department=VALUES(department), category=VALUES(category), category_name=VALUES(category_name), 
                        name=VALUES(name), base_price=VALUES(base_price), original_price=VALUES(original_price), 
                        badge=VALUES(badge), description=VALUES(description), images=VALUES(images), 
                        colors=VALUES(colors), sizes=VALUES(sizes), stock=VALUES(stock)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $pId,
                    $product['department'] ?? 'men',
                    $product['category'] ?? 'men-tshirts',
                    $product['categoryName'] ?? "Men's Apparel",
                    $product['name'],
                    (float)($product['basePrice'] ?? 999),
                    (float)($product['originalPrice'] ?? round(($product['basePrice'] ?? 999) * 1.35)),
                    (float)($product['rating'] ?? 4.9),
                    (int)($product['reviewCount'] ?? $product['reviewsCount'] ?? 25),
                    $product['badge'] ?? '',
                    $product['description'] ?? '',
                    json_encode($product['images'] ?? []),
                    json_encode($product['colors'] ?? []),
                    json_encode($product['sizes'] ?? []),
                    json_encode($product['stock'] ?? [])
                ]);
            } catch (Exception $e) {
                error_log("MySQL save_product error: " . $e->getMessage());
            }
        }

        echo json_encode(['success' => true, 'product' => $product, 'count' => count($products)]);
        break;

    case 'delete_product':
        $pId = $inputData['id'] ?? '';
        if (empty($pId)) {
            echo json_encode(['success' => false, 'error' => 'Product ID is required']);
            exit;
        }

        $products = readJSONFile($productsPath, []);
        $filtered = array_values(array_filter($products, function($p) use ($pId) {
            return $p['id'] !== $pId;
        }));

        writeJSONFile($productsPath, $filtered);

        // Delete from MySQL products table
        $pdo = getDBConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                $stmt->execute([$pId]);
            } catch (Exception $e) {
                error_log("MySQL delete_product error: " . $e->getMessage());
            }
        }

        echo json_encode(['success' => true, 'deletedId' => $pId, 'count' => count($filtered)]);
        break;

    case 'save_announcement':
        $text = $inputData['announcement'] ?? '';
        writeJSONFile($announcementPath, ['text' => $text, 'updatedAt' => date('c')]);

        // Sync to MySQL announcements table
        $pdo = getDBConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("INSERT INTO announcements (id, text) VALUES (1, ?) ON DUPLICATE KEY UPDATE text=VALUES(text), updated_at=NOW()");
                $stmt->execute([$text]);
            } catch (Exception $e) {
                error_log("MySQL save_announcement error: " . $e->getMessage());
            }
        }

        echo json_encode(['success' => true, 'text' => $text]);
        break;

    case 'get_all':
    default:
        $products = readJSONFile($productsPath, []);
        $banners = readJSONFile($bannersPath, []);
        $announcement = readJSONFile($announcementPath, ['text' => '']);
        echo json_encode([
            'success' => true,
            'products' => $products,
            'banners' => $banners,
            'announcement' => $announcement['text'] ?? ''
        ]);
        break;
}
