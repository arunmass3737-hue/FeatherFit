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
        echo json_encode(['success' => true, 'deletedId' => $pId, 'count' => count($filtered)]);
        break;

    case 'save_announcement':
        $text = $inputData['announcement'] ?? '';
        writeJSONFile($announcementPath, ['text' => $text, 'updatedAt' => date('c')]);
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
