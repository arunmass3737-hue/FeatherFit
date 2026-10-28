<?php
/**
 * FEATHER FITS — ADMIN MANAGEMENT API
 * 100% Hostinger MySQL Database Driven (Zero JSON File Storage)
 * Handles:
 * - Product catalog CRUD directly in MySQL `products` table
 * - Hero banner CMS directly in MySQL `banners` table
 * - Announcement bar updates directly in MySQL `announcements` table
 */

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$inputJSON = file_get_contents('php://input');
$inputData = json_decode($inputJSON, true) ?? $_POST;

require_once dirname(__DIR__) . '/config/db.php';

$pdo = getDBConnection();

switch ($action) {
    case 'save_banner':
        $dept = $inputData['dept'] ?? $inputData['department'] ?? 'all';
        $banner = $inputData['banner'] ?? null;
        if (!$banner || !is_array($banner)) {
            echo json_encode(['success' => false, 'error' => 'Invalid banner data']);
            exit;
        }

        if ($pdo) {
            try {
                $sql = "INSERT INTO banners (department, badge, title, subtitle, image, primary_cta, secondary_cta)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE 
                        badge=VALUES(badge), title=VALUES(title), subtitle=VALUES(subtitle), 
                        image=VALUES(image), primary_cta=VALUES(primary_cta), secondary_cta=VALUES(secondary_cta)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $dept,
                    $banner['badge'] ?? '',
                    $banner['title'] ?? '',
                    $banner['subtitle'] ?? '',
                    $banner['image'] ?? 'assets/images/hero-banner.jpg',
                    json_encode($banner['primaryCta'] ?? ['label' => 'Shop Now', 'dept' => 'men']),
                    json_encode($banner['secondaryCta'] ?? ['label' => 'Explore', 'dept' => 'women'])
                ]);

                // Return all current banners from MySQL
                $bStmt = $pdo->query("SELECT * FROM banners");
                $rows = $bStmt->fetchAll(PDO::FETCH_ASSOC);
                $allBanners = [];
                foreach ($rows as $r) {
                    $d = $r['department'] ?? $r['dept'] ?? 'all';
                    $allBanners[$d] = [
                        'badge' => $r['badge'] ?? '',
                        'title' => $r['title'] ?? '',
                        'subtitle' => $r['subtitle'] ?? '',
                        'image' => $r['image'] ?? 'assets/images/hero-banner.jpg',
                        'primaryCta' => is_string($r['primary_cta'] ?? null) ? json_decode($r['primary_cta'], true) : ($r['primary_cta'] ?? ['label' => 'Shop Now', 'dept' => 'men']),
                        'secondaryCta' => is_string($r['secondary_cta'] ?? null) ? json_decode($r['secondary_cta'], true) : ($r['secondary_cta'] ?? ['label' => 'Explore', 'dept' => 'women'])
                    ];
                }

                echo json_encode(['success' => true, 'dept' => $dept, 'banners' => $allBanners]);
                exit;
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'MySQL save_banner error: ' . $e->getMessage()]);
                exit;
            }
        }
        break;

    case 'save_product':
        $product = $inputData['product'] ?? null;
        if (!$product || empty($product['name'])) {
            echo json_encode(['success' => false, 'error' => 'Product name is required']);
            exit;
        }

        $pId = $product['id'] ?? ('ff-' . ($product['department'] ?? 'men') . '-' . time());
        $product['id'] = $pId;

        // Store directly in MySQL products table
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

                $cntStmt = $pdo->query("SELECT COUNT(*) FROM products");
                $count = (int)$cntStmt->fetchColumn();

                echo json_encode(['success' => true, 'product' => $product, 'count' => $count]);
                exit;
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'MySQL save_product error: ' . $e->getMessage()]);
                exit;
            }
        }
        break;

    case 'delete_product':
        $pId = $inputData['id'] ?? '';
        if (empty($pId)) {
            echo json_encode(['success' => false, 'error' => 'Product ID is required']);
            exit;
        }

        // Delete from MySQL products table
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                $stmt->execute([$pId]);

                $cntStmt = $pdo->query("SELECT COUNT(*) FROM products");
                $count = (int)$cntStmt->fetchColumn();

                echo json_encode(['success' => true, 'deletedId' => $pId, 'count' => $count]);
                exit;
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'MySQL delete_product error: ' . $e->getMessage()]);
                exit;
            }
        }
        break;

    case 'save_announcement':
        $text = $inputData['announcement'] ?? '';

        // Store directly in MySQL announcements table
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("INSERT INTO announcements (id, text, is_active) VALUES (1, ?, 1) ON DUPLICATE KEY UPDATE text=VALUES(text), updated_at=NOW()");
                $stmt->execute([$text]);

                echo json_encode(['success' => true, 'text' => $text]);
                exit;
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'MySQL save_announcement error: ' . $e->getMessage()]);
                exit;
            }
        }
        break;

    case 'get_all':
    default:
        $products = [];
        $banners = [];
        $announcementText = '';

        if ($pdo) {
            try {
                // Products from MySQL
                $pStmt = $pdo->query("SELECT * FROM products ORDER BY id ASC");
                $pRows = $pStmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($pRows as $r) {
                    $products[] = [
                        'id' => $r['id'],
                        'department' => $r['department'],
                        'category' => $r['category'],
                        'categoryName' => $r['category_name'],
                        'name' => $r['name'],
                        'basePrice' => (float)$r['base_price'],
                        'originalPrice' => (float)$r['original_price'],
                        'rating' => (float)($r['rating'] ?? 4.9),
                        'reviewsCount' => (int)($r['reviews_count'] ?? 50),
                        'badge' => $r['badge'] ?? '',
                        'description' => $r['description'] ?? '',
                        'images' => is_string($r['images']) ? json_decode($r['images'], true) : ($r['images'] ?? []),
                        'colors' => is_string($r['colors']) ? json_decode($r['colors'], true) : ($r['colors'] ?? []),
                        'sizes' => is_string($r['sizes']) ? json_decode($r['sizes'], true) : ($r['sizes'] ?? []),
                        'stock' => is_string($r['stock']) ? json_decode($r['stock'], true) : ($r['stock'] ?? [])
                    ];
                }

                // Banners from MySQL
                $bStmt = $pdo->query("SELECT * FROM banners");
                $bRows = $bStmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($bRows as $r) {
                    $d = $r['department'] ?? $r['dept'] ?? 'all';
                    $banners[$d] = [
                        'badge' => $r['badge'] ?? '',
                        'title' => $r['title'] ?? '',
                        'subtitle' => $r['subtitle'] ?? '',
                        'image' => $r['image'] ?? 'assets/images/hero-banner.jpg',
                        'primaryCta' => is_string($r['primary_cta'] ?? null) ? json_decode($r['primary_cta'], true) : ($r['primary_cta'] ?? ['label' => 'Shop Now', 'dept' => 'men']),
                        'secondaryCta' => is_string($r['secondary_cta'] ?? null) ? json_decode($r['secondary_cta'], true) : ($r['secondary_cta'] ?? ['label' => 'Explore', 'dept' => 'women'])
                    ];
                }

                // Announcement from MySQL
                $aStmt = $pdo->query("SELECT text FROM announcements WHERE is_active = 1 ORDER BY id DESC LIMIT 1");
                $annRow = $aStmt->fetch(PDO::FETCH_ASSOC);
                if ($annRow) {
                    $announcementText = $annRow['text'];
                }
            } catch (Exception $e) {
                error_log("MySQL get_all admin error: " . $e->getMessage());
            }
        }

        echo json_encode([
            'success' => true,
            'products' => $products,
            'banners' => $banners,
            'announcement' => $announcementText
        ]);
        break;
}
