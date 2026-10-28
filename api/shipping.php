<?php
/**
 * FEATHER FITS — SHIPPING & REAL-TIME DISPATCH ENGINE
 * 100% Hostinger MySQL Database Driven (Zero JSON File Storage)
 * Handles:
 * - Order shipping updates with carrier tracking link generation in MySQL `orders` table
 * - Public customer order tracking with privacy masking & phone verification
 * - Bulk CSV tracking upload directly into MySQL `orders` table
 * - Instant new order creation from checkout directly into MySQL `orders` table
 * - Delayed parcel alerts & status timeline tracking
 */

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/config/db.php';

function getCarriersList() {
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->query("SELECT id, name, url_template AS urlTemplate, is_active FROM carriers WHERE is_active = 1");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("MySQL getCarriersList error: " . $e->getMessage());
        }
    }
    return [];
}

function getShippingOrders() {
    $orders = [];
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // If MySQL orders table is completely empty, auto-seed the initial starter orders once
            if (empty($rows)) {
                $seedFile = dirname(__DIR__) . '/data/orders.json';
                if (file_exists($seedFile)) {
                    $seedData = json_decode(file_get_contents($seedFile), true);
                    if (is_array($seedData) && !empty($seedData)) {
                        $ins = $pdo->prepare("INSERT INTO orders (id, customer_name, customer_email, customer_phone, customer_city, items, subtotal, tax, shipping_fee, total, status, carrier, tracking_number, tracking_url, estimated_delivery, shipped_at, delivered_at, timeline, created_at)
                                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        foreach ($seedData as $o) {
                            $ins->execute([
                                $o['id'],
                                $o['customer']['name'] ?? 'Customer',
                                $o['customer']['email'] ?? 'customer@featherfits.com',
                                $o['customer']['phone'] ?? '+91 9876543210',
                                $o['customer']['city'] ?? 'Chennai',
                                json_encode($o['items'] ?? []),
                                (float)($o['subtotal'] ?? 0),
                                (float)($o['tax'] ?? 0),
                                (float)($o['shippingFee'] ?? 0),
                                (float)($o['total'] ?? 0),
                                $o['status'] ?? 'Pending',
                                $o['carrier'] ?? 'Blue Dart Express',
                                $o['trackingNumber'] ?? null,
                                $o['trackingUrl'] ?? null,
                                $o['estimatedDelivery'] ?? null,
                                !empty($o['shippedAt']) ? date('Y-m-d H:i:s', strtotime($o['shippedAt'])) : null,
                                !empty($o['deliveredAt']) ? date('Y-m-d H:i:s', strtotime($o['deliveredAt'])) : null,
                                json_encode($o['timeline'] ?? []),
                                !empty($o['createdAt']) ? date('Y-m-d H:i:s', strtotime($o['createdAt'])) : date('Y-m-d H:i:s')
                            ]);
                        }
                        // Re-query
                        $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                }
            }

            if (!empty($rows)) {
                foreach ($rows as $r) {
                    $orders[] = [
                        'id' => $r['id'],
                        'customer' => [
                            'name' => $r['customer_name'],
                            'email' => $r['customer_email'],
                            'phone' => $r['customer_phone'],
                            'city' => $r['customer_city']
                        ],
                        'items' => is_string($r['items']) ? json_decode($r['items'], true) : ($r['items'] ?? []),
                        'subtotal' => (float)$r['subtotal'],
                        'tax' => (float)$r['tax'],
                        'shippingFee' => (float)$r['shipping_fee'],
                        'total' => (float)$r['total'],
                        'status' => $r['status'],
                        'carrier' => $r['carrier'],
                        'trackingNumber' => $r['tracking_number'],
                        'trackingUrl' => $r['tracking_url'],
                        'estimatedDelivery' => $r['estimated_delivery'],
                        'shippedAt' => $r['shipped_at'],
                        'deliveredAt' => $r['delivered_at'],
                        'timeline' => is_string($r['timeline']) ? json_decode($r['timeline'], true) : ($r['timeline'] ?? []),
                        'date' => $r['created_at'],
                        'createdAt' => $r['created_at']
                    ];
                }
                return $orders;
            }
        } catch (Exception $e) {
            error_log("MySQL getShippingOrders error: " . $e->getMessage());
        }
    }
    return [];
}

function cleanPhoneDigits($phone) {
    $digits = preg_replace('/[^0-9]/', '', (string)$phone);
    return strlen($digits) >= 10 ? substr($digits, -10) : $digits;
}

function buildTrackingUrl($carrierName, $trackingNo, $carriersList) {
    $trackingNo = trim($trackingNo);
    if (empty($trackingNo)) return '';

    foreach ($carriersList as $c) {
        if (strcasecmp($c['name'], $carrierName) === 0 || strcasecmp($c['id'], $carrierName) === 0) {
            if (!empty($c['urlTemplate'])) {
                return str_replace('{TRACKING}', rawurlencode($trackingNo), $c['urlTemplate']);
            }
        }
    }

    // Heuristics fallback
    $carrierLower = strtolower($carrierName);
    if (strpos($carrierLower, 'blue dart') !== false || strpos($carrierLower, 'bluedart') !== false) {
        return "https://www.bluedart.com/tracking?trackNumber=" . rawurlencode($trackingNo);
    }
    if (strpos($carrierLower, 'delhivery') !== false) {
        return "https://www.delhivery.com/track/package/" . rawurlencode($trackingNo);
    }
    if (strpos($carrierLower, 'post') !== false) {
        return "https://www.indiapost.gov.in/_layouts/15/dop.portal.tracking/trackconsignment.aspx?consignmentNo=" . rawurlencode($trackingNo);
    }
    if (strpos($carrierLower, 'dtdc') !== false) {
        return "https://www.dtdc.in/tracking/shipment-tracking.asp?tracking_no=" . rawurlencode($trackingNo);
    }
    if (strpos($carrierLower, 'shadowfax') !== false) {
        return "https://tracker.shadowfax.in/#/track/" . rawurlencode($trackingNo);
    }
    if (strpos($carrierLower, 'xpress') !== false) {
        return "https://www.xpressbees.com/track?isawb=true&trackid=" . rawurlencode($trackingNo);
    }

    return '';
}

function computeDelayed($order) {
    if (strcasecmp($order['status'] ?? '', 'Shipped') !== 0) return false;
    if (!empty($order['deliveredAt'])) return false;

    $shippedTime = !empty($order['shippedAt']) ? strtotime($order['shippedAt']) : 0;
    if ($shippedTime === 0 && !empty($order['createdAt'])) {
        $shippedTime = strtotime($order['createdAt']);
    }

    if ($shippedTime > 0 && (time() - $shippedTime) > (5 * 86400)) {
        return true;
    }
    return false;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$inputJSON = file_get_contents('php://input');
$inputData = json_decode($inputJSON, true) ?? $_POST;
$pdo = getDBConnection();

switch ($action) {

    // =========================================================================
    // 1. GET ORDERS WITH SHIPPING METRICS & FILTERS (ADMIN)
    // =========================================================================
    case 'get_orders':
        $orders = getShippingOrders();
        $filter = strtolower($_GET['filter'] ?? 'all');

        $metrics = [
            'total' => count($orders),
            'inTransit' => 0,
            'delayed' => 0,
            'delivered' => 0,
            'pending' => 0,
            'noTracking' => 0,
            'rto' => 0
        ];

        foreach ($orders as &$ord) {
            $status = strtolower($ord['status'] ?? '');
            $isDelayed = computeDelayed($ord);
            $ord['isDelayed'] = $isDelayed;

            if ($status === 'shipped' || $status === 'out for delivery') {
                $metrics['inTransit']++;
            }
            if ($isDelayed) {
                $metrics['delayed']++;
            }
            if ($status === 'delivered') {
                $metrics['delivered']++;
            }
            if ($status === 'pending' || $status === 'processing') {
                $metrics['pending']++;
            }
            if (($status === 'shipped' || $status === 'processing') && empty($ord['trackingNumber'])) {
                $metrics['noTracking']++;
            }
            if ($status === 'rto' || strpos($status, 'return') !== false) {
                $metrics['rto']++;
            }
        }
        unset($ord);

        $filtered = array_values(array_filter($orders, function($o) use ($filter) {
            $st = strtolower($o['status'] ?? '');
            if ($filter === 'all') return true;
            if ($filter === 'transit') return ($st === 'shipped' || $st === 'out for delivery');
            if ($filter === 'delivered') return ($st === 'delivered');
            if ($filter === 'pending') return ($st === 'pending' || $st === 'processing');
            if ($filter === 'delayed') return !empty($o['isDelayed']);
            if ($filter === 'rto') return ($st === 'rto' || strpos($st, 'return') !== false);
            return true;
        }));

        $carriers = getCarriersList();

        echo json_encode([
            'success' => true,
            'metrics' => $metrics,
            'orders' => $filtered,
            'carriers' => $carriers
        ]);
        break;

    // =========================================================================
    // 2. CREATE NEW ORDER (STORE DIRECTLY IN MYSQL ORDERS)
    // =========================================================================
    case 'create_order':
    case 'save_order':
        $order = $inputData['order'] ?? $inputData;
        $orderId = trim($order['id'] ?? ('FF-' . mt_rand(100000, 999999)));
        $cust = $order['customer'] ?? [];
        $custName = trim($cust['name'] ?? ($order['name'] ?? 'Customer'));
        $custEmail = trim(strtolower($cust['email'] ?? ($order['email'] ?? 'customer@featherfits.com')));
        $custPhone = trim($cust['phone'] ?? ($order['phone'] ?? ''));
        $custCity = trim($cust['city'] ?? ($order['city'] ?? 'Chennai'));
        $items = $order['items'] ?? [];
        $subtotal = (float)($order['subtotal'] ?? 0);
        $tax = (float)($order['tax'] ?? 0);
        $shippingFee = (float)($order['shippingFee'] ?? 0);
        $total = (float)($order['total'] ?? 0);

        $initialTimeline = [
            [
                'status' => 'Order Placed',
                'time' => date('Y-m-d h:i A'),
                'note' => 'Order placed successfully via Feather Fits Checkout',
                'updatedBy' => 'Customer'
            ]
        ];

        if ($pdo) {
            try {
                $sql = "INSERT INTO orders (id, customer_name, customer_email, customer_phone, customer_city, items, subtotal, tax, shipping_fee, total, status, carrier, tracking_number, tracking_url, estimated_delivery, timeline, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', 'Blue Dart Express', NULL, NULL, DATE_ADD(NOW(), INTERVAL 3 DAY), ?, NOW())
                        ON DUPLICATE KEY UPDATE status=VALUES(status)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $orderId,
                    $custName,
                    $custEmail,
                    $custPhone,
                    $custCity,
                    json_encode($items),
                    $subtotal,
                    $tax,
                    $shippingFee,
                    $total,
                    json_encode($initialTimeline)
                ]);

                echo json_encode([
                    'success' => true,
                    'message' => "Order #$orderId stored directly in Hostinger MySQL!",
                    'orderId' => $orderId
                ]);
                exit;
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'MySQL create_order error: ' . $e->getMessage()]);
                exit;
            }
        }
        break;

    // =========================================================================
    // 3. UPDATE ORDER DISPATCH & SHIPPING (STORE IN MYSQL ORDERS)
    // =========================================================================
    case 'update_shipping':
        $orderId = trim($inputData['orderId'] ?? '');
        $carrier = trim($inputData['carrier'] ?? 'Blue Dart Express');
        $trackingNumber = preg_replace('/\s+/', '', trim($inputData['trackingNumber'] ?? ''));
        $estDelivery = trim($inputData['estimatedDelivery'] ?? '');
        $adminName = trim($inputData['adminName'] ?? 'Warehouse Dispatch');

        if (empty($orderId)) {
            echo json_encode(['success' => false, 'error' => 'Order ID is required.']);
            exit;
        }
        if (empty($trackingNumber)) {
            echo json_encode(['success' => false, 'error' => 'Tracking Number is required.']);
            exit;
        }

        $carriers = getCarriersList();
        $trackingUrl = buildTrackingUrl($carrier, $trackingNumber, $carriers);

        if ($pdo) {
            try {
                $findStmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
                $findStmt->execute([$orderId]);
                $ord = $findStmt->fetch(PDO::FETCH_ASSOC);

                if (!$ord) {
                    echo json_encode(['success' => false, 'error' => "Order #$orderId not found in MySQL."]);
                    exit;
                }

                $timeline = is_string($ord['timeline']) ? json_decode($ord['timeline'], true) : ($ord['timeline'] ?? []);
                if (!is_array($timeline)) $timeline = [];

                $timeline[] = [
                    'status' => 'Shipped',
                    'time' => date('Y-m-d h:i A'),
                    'note' => "Dispatched via $carrier (AWB: $trackingNumber)",
                    'updatedBy' => $adminName
                ];

                $sql = "UPDATE orders SET carrier = ?, tracking_number = ?, tracking_url = ?, status = 'Shipped', shipped_at = NOW(), estimated_delivery = ?, timeline = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $carrier,
                    $trackingNumber,
                    $trackingUrl,
                    $estDelivery,
                    json_encode($timeline),
                    $orderId
                ]);

                $custPhone = cleanPhoneDigits($ord['customer_phone'] ?? '');
                $trackingLink = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost:3005') . "/track.php?order=" . rawurlencode($orderId) . "&phone=" . rawurlencode($custPhone);
                $whatsappMsg = "Hi {$ord['customer_name']}, your Feather Fits order #$orderId has been shipped via $carrier!\nTracking Number: $trackingNumber\nTrack live parcel here: $trackingLink\n\nThank you for shopping with Feather Fits! — Confidence Fits You.";
                $whatsappUrl = "https://wa.me/91" . $custPhone . "?text=" . rawurlencode($whatsappMsg);

                echo json_encode([
                    'success' => true,
                    'message' => "Order #$orderId marked as Shipped via $carrier in MySQL!",
                    'trackingUrl' => $trackingUrl,
                    'publicTrackUrl' => $trackingLink,
                    'whatsappUrl' => $whatsappUrl
                ]);
                exit;

            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'MySQL update_shipping error: ' . $e->getMessage()]);
                exit;
            }
        }
        break;

    // =========================================================================
    // 4. UPDATE ORDER STATUS (DELIVERED, RTO, OUT FOR DELIVERY, ETC.)
    // =========================================================================
    case 'update_status':
        $orderId = trim($inputData['orderId'] ?? '');
        $newStatus = trim($inputData['status'] ?? '');
        $note = trim($inputData['note'] ?? '');
        $adminName = trim($inputData['adminName'] ?? 'Admin');

        if (empty($orderId) || empty($newStatus)) {
            echo json_encode(['success' => false, 'error' => 'Order ID and Status are required.']);
            exit;
        }

        if ($pdo) {
            try {
                $findStmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
                $findStmt->execute([$orderId]);
                $ord = $findStmt->fetch(PDO::FETCH_ASSOC);

                if (!$ord) {
                    echo json_encode(['success' => false, 'error' => "Order #$orderId not found in MySQL."]);
                    exit;
                }

                $timeline = is_string($ord['timeline']) ? json_decode($ord['timeline'], true) : ($ord['timeline'] ?? []);
                if (!is_array($timeline)) $timeline = [];

                $timeline[] = [
                    'status' => $newStatus,
                    'time' => date('Y-m-d h:i A'),
                    'note' => !empty($note) ? $note : "Status updated to $newStatus",
                    'updatedBy' => $adminName
                ];

                $delivClause = (strcasecmp($newStatus, 'Delivered') === 0) ? ", delivered_at = NOW()" : "";
                $sql = "UPDATE orders SET status = ?, timeline = ? $delivClause WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $newStatus,
                    json_encode($timeline),
                    $orderId
                ]);

                echo json_encode([
                    'success' => true,
                    'message' => "Order #$orderId status updated to $newStatus in MySQL."
                ]);
                exit;

            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'MySQL update_status error: ' . $e->getMessage()]);
                exit;
            }
        }
        break;

    // =========================================================================
    // 5. BULK CSV TRACKING UPLOAD DIRECTLY IN MYSQL
    // =========================================================================
    case 'bulk_upload_csv':
        $csvText = $inputData['csv'] ?? '';

        if (empty($csvText) && !empty($_FILES['csv_file']['tmp_name'])) {
            $csvText = file_get_contents($_FILES['csv_file']['tmp_name']);
        }

        if (empty($csvText)) {
            echo json_encode(['success' => false, 'error' => 'CSV data is required.']);
            exit;
        }

        $lines = explode("\n", str_replace("\r", "", $csvText));
        $carriers = getCarriersList();

        $updatedCount = 0;
        $errors = [];

        if ($pdo) {
            $upd = $pdo->prepare("UPDATE orders SET carrier = ?, tracking_number = ?, tracking_url = ?, status = 'Shipped', shipped_at = NOW() WHERE id = ?");

            foreach ($lines as $lineIndex => $line) {
                $line = trim($line);
                if (empty($line)) continue;

                $cols = str_getcsv($line);
                if (count($cols) < 3) continue;

                $colOrderId = trim($cols[0]);
                $colCarrier = trim($cols[1]);
                $colTracking = preg_replace('/\s+/', '', trim($cols[2]));

                if (stripos($colOrderId, 'order') !== false && stripos($colCarrier, 'carrier') !== false) {
                    continue;
                }

                if (empty($colOrderId) || empty($colTracking)) {
                    $errors[] = "Line " . ($lineIndex + 1) . ": Missing Order ID or Tracking Number.";
                    continue;
                }

                $trkUrl = buildTrackingUrl($colCarrier, $colTracking, $carriers);
                $upd->execute([$colCarrier, $colTracking, $trkUrl, $colOrderId]);
                if ($upd->rowCount() > 0) {
                    $updatedCount++;
                } else {
                    $errors[] = "Order #$colOrderId not found in MySQL.";
                }
            }
        }

        echo json_encode([
            'success' => true,
            'updatedCount' => $updatedCount,
            'errors' => $errors,
            'message' => "Successfully updated $updatedCount orders directly in MySQL."
        ]);
        break;

    // =========================================================================
    // 6. PUBLIC CUSTOMER TRACKING (READ DIRECTLY FROM MYSQL ORDERS)
    // =========================================================================
    case 'track_public':
        $orderId = trim($_GET['orderId'] ?? $_GET['order_id'] ?? $_GET['order'] ?? $inputData['orderId'] ?? $inputData['order_id'] ?? '');
        $phoneOrEmail = trim(strtolower($_GET['phone'] ?? $_GET['email'] ?? $inputData['phone'] ?? $inputData['email'] ?? ''));

        if (empty($orderId)) {
            echo json_encode(['success' => false, 'error' => 'Please enter your Order ID (e.g. FF-90214).']);
            exit;
        }

        if (empty($phoneOrEmail)) {
            echo json_encode(['success' => false, 'error' => 'Please enter your 10-digit Phone Number or Email for privacy verification.']);
            exit;
        }

        $inputDigits = cleanPhoneDigits($phoneOrEmail);
        $foundOrder = null;

        if ($pdo) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
                $stmt->execute([$orderId]);
                $r = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($r) {
                    $custPhone = cleanPhoneDigits($r['customer_phone'] ?? '');
                    $custEmail = strtolower($r['customer_email'] ?? '');

                    $phoneMatch = !empty($inputDigits) && !empty($custPhone) && ($inputDigits === $custPhone);
                    $emailMatch = !empty($phoneOrEmail) && ($phoneOrEmail === $custEmail);

                    if ($phoneMatch || $emailMatch) {
                        $foundOrder = [
                            'id' => $r['id'],
                            'customer' => [
                                'name' => $r['customer_name'],
                                'email' => $r['customer_email'],
                                'phone' => $r['customer_phone'],
                                'city' => $r['customer_city']
                            ],
                            'items' => is_string($r['items']) ? json_decode($r['items'], true) : ($r['items'] ?? []),
                            'status' => $r['status'],
                            'carrier' => $r['carrier'],
                            'trackingNumber' => $r['tracking_number'],
                            'trackingUrl' => $r['tracking_url'],
                            'estimatedDelivery' => $r['estimated_delivery'],
                            'shippedAt' => $r['shipped_at'],
                            'deliveredAt' => $r['delivered_at'],
                            'timeline' => is_string($r['timeline']) ? json_decode($r['timeline'], true) : ($r['timeline'] ?? [])
                        ];
                    } else {
                        echo json_encode([
                            'success' => false,
                            'error' => 'The phone number or email does not match this order. Please verify your details.'
                        ]);
                        exit;
                    }
                }
            } catch (Exception $e) {
                error_log("MySQL track_public error: " . $e->getMessage());
            }
        }

        if (!$foundOrder) {
            echo json_encode(['success' => false, 'error' => "Order #$orderId was not found in MySQL. Please check your order confirmation."]);
            exit;
        }

        $statusLower = strtolower($foundOrder['status'] ?? '');
        $progress = 20;
        if ($statusLower === 'packed') $progress = 40;
        if ($statusLower === 'shipped') $progress = 65;
        if ($statusLower === 'out for delivery') $progress = 85;
        if ($statusLower === 'delivered') $progress = 100;
        if ($statusLower === 'rto') $progress = 100;

        $rawCustName = $foundOrder['customer']['name'] ?? 'Customer';
        $nameParts = explode(' ', $rawCustName);
        $maskedName = $nameParts[0] . ' ' . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) . '.' : '');

        $rawCustPhone = cleanPhoneDigits($foundOrder['customer']['phone'] ?? '');
        $maskedPhone = !empty($rawCustPhone) ? '******' . substr($rawCustPhone, -4) : '';
        $city = $foundOrder['customer']['city'] ?? 'India';

        $safeOrder = [
            'id' => $foundOrder['id'],
            'status' => $foundOrder['status'],
            'progress' => $progress,
            'carrier' => $foundOrder['carrier'] ?: 'Pending Assignment',
            'trackingNumber' => $foundOrder['trackingNumber'],
            'trackingUrl' => $foundOrder['trackingUrl'],
            'estimatedDelivery' => $foundOrder['estimatedDelivery'] ?? '2 - 3 Days',
            'shippedAt' => $foundOrder['shippedAt'],
            'deliveredAt' => $foundOrder['deliveredAt'],
            'timeline' => $foundOrder['timeline'] ?? [],
            'items' => array_map(function($item) {
                return [
                    'name' => $item['name'] ?? '',
                    'size' => $item['size'] ?? '',
                    'qty' => $item['qty'] ?? 1,
                    'image' => $item['image'] ?? 'assets/images/anime-tee-model-front.jpg'
                ];
            }, $foundOrder['items'] ?? []),
            'destination' => [
                'recipient' => $maskedName,
                'phone' => $maskedPhone,
                'city' => $city
            ]
        ];

        echo json_encode([
            'success' => true,
            'order' => $safeOrder
        ]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Invalid shipping action.']);
        break;
}