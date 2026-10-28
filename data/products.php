<?php
// Feather Fits PHP Product Catalog Data Provider

function getFeatherProducts() {
    static $products = null;
    if ($products !== null) {
        return $products;
    }

    $jsonFile = __DIR__ . '/products.json';
    if (file_exists($jsonFile)) {
        $products = json_decode(file_get_contents($jsonFile), true);
        if (is_array($products)) {
            return $products;
        }
    }

    return [];
}

function getFeatherProductById($id) {
    $products = getFeatherProducts();
    foreach ($products as $p) {
        if (($p['id'] ?? '') === $id) {
            return $p;
        }
    }
    return $products[0] ?? null;
}

function getFeatherNewArrivals($dept = 'men') {
    static $arrivals = null;
    if ($arrivals !== null) {
        return $arrivals[$dept] ?? $arrivals['men'] ?? [];
    }

    $jsonFile = __DIR__ . '/arrivals.json';
    if (file_exists($jsonFile)) {
        $arrivals = json_decode(file_get_contents($jsonFile), true);
        if (is_array($arrivals)) {
            return $arrivals[$dept] ?? $arrivals['men'] ?? [];
        }
    }

    return [];
}

function getFeatherBanners() {
    static $banners = null;
    if ($banners !== null) {
        return $banners;
    }

    $jsonFile = __DIR__ . '/banners.json';
    if (file_exists($jsonFile)) {
        $banners = json_decode(file_get_contents($jsonFile), true);
        if (is_array($banners)) {
            return $banners;
        }
    }

    return [];
}
