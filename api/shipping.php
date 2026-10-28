<?php
/**
 * FEATHER FITS — SHIPPING & REAL-TIME DISPATCH ENGINE
 * Handles:
 * - Order shipping updates with carrier tracking link generation
 * - Public customer order tracking with privacy masking & phone verification
 * - Bulk CSV tracking upload
 * - Delayed parcel alerts & status timeline tracking
 */

header('Content-Type: application/json; charset=utf-8');

$ordersPath = dirname(__DIR__) . '/data/orders.json';
$carriersPath = dirname(__DIR__) . '/data/carriers.json';

require_once dirname(__DIR__) . '/config/db.php';

function getShippingOrders() {
    global $ordersPath;
    $orders = [];
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    return readJSONFile($ordersPath, []);
}

function readJSONFile($path, $fallback = []) {
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $data = json_decode($content, true);
        if (is_array($data)) return $data;
    }
    return $fallback;
}

function writeJSONFile($path, $data) {
    return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
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

    // Flag if shipped > 5 days ago and still not delivered
    if ($shippedTime > 0 && (time() - $shippedTime) > (5 * 86400)) {
        return true;
    }
    return false;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$inputJSON = file_get_contents('php://input');
$inputData = json_decode($inputJSON, true) ?? $_POST;

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

        // Apply filter
        $filtered = array_values(array_filter($orders, function($o) use ($filter) {
            $s = strtolower($o['status'] ?? '');
            if ($filter === 'all') return true;
            if ($filter === 'shipped') return $s === 'shipped';
            if ($filter === 'delivered') return $s === 'delivered';
            if ($filter === 'pending') return $s === 'pending' || $s === 'processing';
            if ($filter === 'no_tracking') return ($s === 'shipped' || $s === 'processing') && empty($o['trackingNumber']);
            if ($filter === 'delayed') return !empty($o['isDelayed']);
            if ($filter === 'rto') return $s === 'rto' || strpos($s, 'return') !== false;
            return true;
        }));

        $carriers = readJSONFile($carriersPath, []);

        echo json_encode([
            'success' => true,
            'metrics' => $metrics,
            'orders' => $filtered,
            'carriers' => $carriers
        ]);
        break;

    // =========================================================================
    // 2. UPDATE SHIPPING DETAILS & MARK AS SHIPPED (ADMIN)
    // =========================================================================
    case 'update_shipping':
        $orderId = trim($inputData['orderId'] ?? '');
        $carrier = trim($inputData['carrier'] ?? 'Blue Dart Express');
        $rawTracking = trim($inputData['trackingNumber'] ?? '');
        $trackingNumber = preg_replace('/\s+/', '', $rawTracking); // remove accidental whitespace
        $estDelivery = trim($inputData['estimatedDelivery'] ?? '');
        $adminName = trim($inputData['adminName'] ?? 'Arun Kumar');

        if (empty($orderId)) {
            echo json_encode(['success' => false, 'error' => 'Order ID is required.']);
            exit;
        }

        if (empty($trackingNumber)) {
            echo json_encode(['success' => false, 'error' => 'Tracking Number is required.']);
            exit;
        }

        $orders = readJSONFile($ordersPath, []);
        $carriers = readJSONFile($carriersPath, []);
        $found = false;
        $updatedOrder = null;

        foreach ($orders as &$ord) {
            if (strcasecmp($ord['id'], $orderId) === 0) {
                $ord['carrier'] = $carrier;
                $ord['trackingNumber'] = $trackingNumber;
                $ord['trackingUrl'] = buildTrackingUrl($carrier, $trackingNumber, $carriers);
                $ord['status'] = 'Shipped';
                $ord['shippedAt'] = date('c');
                if (!empty($estDelivery)) {
                    $ord['estimatedDelivery'] = $estDelivery;
                }

                if (!isset($ord['timeline']) || !is_array($ord['timeline'])) {
                    $ord['timeline'] = [];
                }

                $ord['timeline'][] = [
                    'status' => 'Shipped',
                    'time' => date('Y-m-d h:i A'),
                    'note' => "Dispatched via $carrier (AWB: $trackingNumber)",
                    'updatedBy' => $adminName
                ];

                $found = true;
                $updatedOrder = $ord;
                break;
            }
        }
        unset($ord);

        if (!$found) {
            echo json_encode(['success' => false, 'error' => "Order #$orderId not found."]);
            exit;
        }

        writeJSONFile($ordersPath, $orders);

        // Sync to MySQL orders table
        $pdo = getDBConnection();
        if ($pdo) {
            try {
                $sql = "UPDATE orders SET carrier = ?, tracking_number = ?, tracking_url = ?, status = 'Shipped', shipped_at = NOW(), estimated_delivery = ?, timeline = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $carrier,
                    $trackingNumber,
                    $updatedOrder['trackingUrl'],
                    $estDelivery,
                    json_encode($updatedOrder['timeline']),
                    $orderId
                ]);
            } catch (Exception $e) {
                error_log("MySQL update_shipping error: " . $e->getMessage());
            }
        }

        // Pre-build WhatsApp share message
        $custName = $updatedOrder['customer']['name'] ?? 'Customer';
        $custPhone = cleanPhoneDigits($updatedOrder['customer']['phone'] ?? '');
        $trackingLink = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost:3005') . "/track.php?order=" . rawurlencode($orderId) . "&phone=" . rawurlencode($custPhone);

        $whatsappMsg = "Hi $custName, your Feather Fits order #$orderId has been shipped via $carrier!\nTracking Number: $trackingNumber\nTrack live parcel here: $trackingLink\n\nThank you for shopping with Feather Fits! — Confidence Fits You.";
        $whatsappUrl = "https://wa.me/91" . $custPhone . "?text=" . rawurlencode($whatsappMsg);

        echo json_encode([
            'success' => true,
            'message' => "Order #$orderId marked as Shipped via $carrier!",
            'order' => $updatedOrder,
            'trackingUrl' => $updatedOrder['trackingUrl'],
            'publicTrackUrl' => $trackingLink,
            'whatsappUrl' => $whatsappUrl,
            'whatsappMessage' => $whatsappMsg
        ]);
        break;

    // =========================================================================
    // 3. UPDATE ORDER STATUS (DELIVERED, RTO, OUT FOR DELIVERY, ETC.)
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

        $orders = readJSONFile($ordersPath, []);
        $found = false;
        $updatedOrder = null;

        foreach ($orders as &$ord) {
            if (strcasecmp($ord['id'], $orderId) === 0) {
                $ord['status'] = $newStatus;
                if (strcasecmp($newStatus, 'Delivered') === 0) {
                    $ord['deliveredAt'] = date('c');
                }

                if (!isset($ord['timeline']) || !is_array($ord['timeline'])) {
                    $ord['timeline'] = [];
                }

                $ord['timeline'][] = [
                    'status' => $newStatus,
                    'time' => date('Y-m-d h:i A'),
                    'note' => !empty($note) ? $note : "Status updated to $newStatus",
                    'updatedBy' => $adminName
                ];

                $found = true;
                $updatedOrder = $ord;
                break;
            }
        }
        unset($ord);

        if (!$found) {
            echo json_encode(['success' => false, 'error' => "Order #$orderId not found."]);
            exit;
        }

        writeJSONFile($ordersPath, $orders);

        // Sync to MySQL orders table
        $pdo = getDBConnection();
        if ($pdo) {
            try {
                $sql = "UPDATE orders SET status = ?, timeline = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $newStatus,
                    json_encode($updatedOrder['timeline']),
                    $orderId
                ]);
            } catch (Exception $e) {
                error_log("MySQL update_status error: " . $e->getMessage());
            }
        }
        echo json_encode([
            'success' => true,
            'message' => "Order #$orderId status updated to $newStatus.",
            'order' => $updatedOrder
        ]);
        break;

    // =========================================================================
    // 4. BULK CSV TRACKING UPLOAD
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
        $orders = readJSONFile($ordersPath, []);
        $carriers = readJSONFile($carriersPath, []);

        $updatedCount = 0;
        $errors = [];

        foreach ($lines as $lineIndex => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $cols = str_getcsv($line);
            if (count($cols) < 3) continue;

            $colOrderId = trim($cols[0]);
            $colCarrier = trim($cols[1]);
            $colTracking = preg_replace('/\s+/', '', trim($cols[2]));

            // Skip header row if present
            if (stripos($colOrderId, 'order') !== false && stripos($colCarrier, 'carrier') !== false) {
                continue;
            }

            if (empty($colOrderId) || empty($colTracking)) {
                $errors[] = "Line " . ($lineIndex + 1) . ": Missing Order ID or Tracking Number.";
                continue;
            }

            $orderFound = false;
            foreach ($orders as &$ord) {
                if (strcasecmp($ord['id'], $colOrderId) === 0) {
                    $ord['carrier'] = $colCarrier;
                    $ord['trackingNumber'] = $colTracking;
                    $ord['trackingUrl'] = buildTrackingUrl($colCarrier, $colTracking, $carriers);
                    $ord['status'] = 'Shipped';
                    $ord['shippedAt'] = date('c');

                    if (!isset($ord['timeline']) || !is_array($ord['timeline'])) {
                        $ord['timeline'] = [];
                    }

                    $ord['timeline'][] = [
                        'status' => 'Shipped',
                        'time' => date('Y-m-d h:i A'),
                        'note' => "Bulk updated via CSV: $colCarrier (AWB: $colTracking)",
                        'updatedBy' => 'Bulk CSV'
                    ];

                    $updatedCount++;
                    $orderFound = true;
                    break;
                }
            }
            unset($ord);

            if (!$orderFound) {
                $errors[] = "Order #$colOrderId not found in catalog.";
            }
        }

        if ($updatedCount > 0) {
            writeJSONFile($ordersPath, $orders);
        }

        echo json_encode([
            'success' => true,
            'updatedCount' => $updatedCount,
            'errors' => $errors,
            'message' => "Successfully updated $updatedCount orders with tracking details."
        ]);
        break;

    // =========================================================================
    // 5. PUBLIC CUSTOMER TRACKING (CUSTOMER SIDE WITH PRIVACY VERIFICATION)
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

        $orders = getShippingOrders();
        $foundOrder = null;
        $inputDigits = cleanPhoneDigits($phoneOrEmail);

        foreach ($orders as $ord) {
            if (strcasecmp($ord['id'], $orderId) === 0) {
                $custPhone = cleanPhoneDigits($ord['customer']['phone'] ?? '');
                $custEmail = strtolower($ord['customer']['email'] ?? '');

                // Verify phone last 10 digits OR email matches to prevent unauthorized snooping
                $phoneMatch = !empty($inputDigits) && !empty($custPhone) && ($inputDigits === $custPhone);
                $emailMatch = !empty($phoneOrEmail) && ($phoneOrEmail === $custEmail);

                if ($phoneMatch || $emailMatch) {
                    $foundOrder = $ord;
                    break;
                } else {
                    echo json_encode([
                        'success' => false,
                        'error' => 'The phone number or email does not match this order. Please verify your details.'
                    ]);
                    exit;
                }
            }
        }

        if (!$foundOrder) {
            echo json_encode(['success' => false, 'error' => "Order #$orderId was not found. Please check your order confirmation."]);
            exit;
        }

        // Compute progress percentage
        $statusLower = strtolower($foundOrder['status'] ?? '');
        $progress = 20;
        if ($statusLower === 'packed') $progress = 40;
        if ($statusLower === 'shipped') $progress = 65;
        if ($statusLower === 'out for delivery') $progress = 85;
        if ($statusLower === 'delivered') $progress = 100;
        if ($statusLower === 'rto') $progress = 100;

        // Privacy Masking (Do not expose full address or complete phone)
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
