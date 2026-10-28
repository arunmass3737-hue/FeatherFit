<?php
/**
 * FEATHER FITS — AUTHENTICATION & OTP SERVICE
 * 100% Hostinger MySQL Database Driven (Zero JSON File Storage)
 * Handles:
 * - New User Registration with Email, Phone, and OTP verification (Stored in MySQL users & otps)
 * - User Login (Email / Phone + Password OR Instant OTP Login)
 * - Admin Registration & Authentication (Stored in MySQL admins)
 * - User Profiles & Sessions
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$inputJSON = file_get_contents('php://input');
$inputData = json_decode($inputJSON, true) ?? $_POST;

require_once dirname(__DIR__) . '/config/db.php';

function cleanPhone($phone) {
    $digits = preg_replace('/[^0-9]/', '', (string)$phone);
    return strlen($digits) >= 10 ? substr($digits, -10) : $digits;
}

$pdo = getDBConnection();

switch ($action) {

    // =========================================================================
    // 1. SEND OTP FOR NEW REGISTRATION OR INSTANT LOGIN
    // =========================================================================
    case 'send_otp':
        $email = trim(strtolower($inputData['email'] ?? ''));
        $rawPhone = trim($inputData['phone'] ?? '');
        $phone = cleanPhone($rawPhone);
        $type = $inputData['type'] ?? 'register'; // 'register' or 'login'

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'error' => 'Valid email address is mandatory.']);
            exit;
        }

        if (empty($phone) || strlen($phone) < 10) {
            echo json_encode(['success' => false, 'error' => '10-digit mobile phone number is mandatory.']);
            exit;
        }

        if ($pdo) {
            if ($type === 'register') {
                $checkStmt = $pdo->prepare("SELECT id, email, phone FROM users WHERE LOWER(email) = ? OR phone = ? OR phone = ? LIMIT 1");
                $checkStmt->execute([$email, $phone, '+91 ' . $phone]);
                $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
                if ($existing) {
                    if (strtolower($existing['email']) === $email) {
                        echo json_encode(['success' => false, 'error' => 'This email is already registered. Please sign in instead.']);
                        exit;
                    } else {
                        echo json_encode(['success' => false, 'error' => 'This mobile number is already registered. Please sign in instead.']);
                        exit;
                    }
                }
            }
        }

        // Generate secure 6-digit numeric OTP
        $otp = (string) mt_rand(100000, 999999);
        $target = $email . '_' . $phone;

        // Store OTP in MySQL otps table (Expires in 10 minutes)
        if ($pdo) {
            try {
                $delStmt = $pdo->prepare("DELETE FROM otps WHERE target = ?");
                $delStmt->execute([$target]);

                $otpStmt = $pdo->prepare("INSERT INTO otps (target, otp_code, expires_at, is_used) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE), 0)");
                $otpStmt->execute([$target, $otp]);
            } catch (Exception $e) {
                error_log("MySQL send_otp error: " . $e->getMessage());
            }
        }

        echo json_encode([
            'success' => true,
            'message' => "Verification code sent to $email and +91 $phone",
            'demoOtp' => $otp,
            'expiresInSeconds' => 600
        ]);
        break;

    // =========================================================================
    // 2. VERIFY OTP & COMPLETE NEW REGISTRATION (STORE IN MYSQL USERS)
    // =========================================================================
    case 'verify_otp_and_register':
        $name = trim($inputData['name'] ?? '');
        $email = trim(strtolower($inputData['email'] ?? ''));
        $rawPhone = trim($inputData['phone'] ?? '');
        $phone = cleanPhone($rawPhone);
        $userOtp = trim($inputData['otp'] ?? '');
        $password = $inputData['password'] ?? '';

        if (empty($name)) {
            echo json_encode(['success' => false, 'error' => 'Full Name is required.']);
            exit;
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'error' => 'Valid email address is mandatory.']);
            exit;
        }
        if (empty($phone) || strlen($phone) < 10) {
            echo json_encode(['success' => false, 'error' => '10-digit mobile number is mandatory.']);
            exit;
        }
        if (empty($userOtp)) {
            echo json_encode(['success' => false, 'error' => 'OTP verification code is required.']);
            exit;
        }
        if (empty($password) || strlen($password) < 6) {
            echo json_encode(['success' => false, 'error' => 'Password must be at least 6 characters.']);
            exit;
        }

        $target = $email . '_' . $phone;

        // Verify OTP directly in MySQL otps table
        if ($pdo) {
            $otpQuery = $pdo->prepare("SELECT id, otp_code, expires_at, is_used FROM otps WHERE target = ? AND otp_code = ? ORDER BY id DESC LIMIT 1");
            $otpQuery->execute([$target, $userOtp]);
            $otpRecord = $otpQuery->fetch(PDO::FETCH_ASSOC);

            if (!$otpRecord) {
                echo json_encode(['success' => false, 'error' => 'Invalid OTP code. Please re-check or request a new code.']);
                exit;
            }
            if ($otpRecord['is_used'] == 1) {
                echo json_encode(['success' => false, 'error' => 'This OTP has already been used. Please request a new OTP.']);
                exit;
            }
            if (strtotime($otpRecord['expires_at']) < time()) {
                echo json_encode(['success' => false, 'error' => 'OTP has expired. Please request a new OTP.']);
                exit;
            }

            // Mark OTP as used
            $markUsed = $pdo->prepare("UPDATE otps SET is_used = 1 WHERE id = ?");
            $markUsed->execute([$otpRecord['id']]);
        }

        $nameParts = explode(' ', $name, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';
        $userId = 'usr_' . $phone . '_' . time();

        $newUser = [
            'id' => $userId,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'name' => $name,
            'email' => $email,
            'phone' => '+91 ' . $phone,
            'password' => $password,
            'isVerified' => true,
            'memberSince' => date('Y'),
            'tier' => 'VIP Member',
            'coins' => 100,
            'preferredSize' => 'L',
            'addresses' => []
        ];

        // Store user directly in MySQL users table
        if ($pdo) {
            try {
                $sql = "INSERT INTO users (id, first_name, last_name, name, email, phone, password, is_verified, member_since, tier, coins, preferred_size, addresses)
                        VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, 'VIP Member', 100, 'L', '[]')
                        ON DUPLICATE KEY UPDATE name=VALUES(name), password=VALUES(password)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $userId,
                    $firstName,
                    $lastName,
                    $name,
                    $email,
                    '+91 ' . $phone,
                    $password,
                    date('Y')
                ]);
            } catch (Exception $e) {
                error_log("MySQL register user error: " . $e->getMessage());
                echo json_encode(['success' => false, 'error' => 'Database error while saving user: ' . $e->getMessage()]);
                exit;
            }
        }

        $safeUser = $newUser;
        unset($safeUser['password']);
        $_SESSION['feather_user'] = $safeUser;

        echo json_encode([
            'success' => true,
            'message' => 'Account created and verified successfully! Welcome to Feather Fits.',
            'user' => $safeUser
        ]);
        break;

    // =========================================================================
    // 3. USER LOGIN (READ FROM MYSQL USERS)
    // =========================================================================
    case 'user_login':
        $identifier = trim(strtolower($inputData['identifier'] ?? ''));
        $password = $inputData['password'] ?? '';
        $otp = trim($inputData['otp'] ?? '');

        if (empty($identifier)) {
            echo json_encode(['success' => false, 'error' => 'Please enter your registered Email or Mobile Number.']);
            exit;
        }

        $cleanIdentifierPhone = cleanPhone($identifier);
        $foundUser = null;

        // Query MySQL users table directly
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE LOWER(email) = ? OR phone = ? OR phone = ? LIMIT 1");
                $stmt->execute([$identifier, $cleanIdentifierPhone, '+91 ' . $cleanIdentifierPhone]);
                $dbUser = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($dbUser) {
                    $foundUser = [
                        'id' => $dbUser['id'],
                        'firstName' => $dbUser['first_name'],
                        'lastName' => $dbUser['last_name'],
                        'name' => $dbUser['name'],
                        'email' => $dbUser['email'],
                        'phone' => $dbUser['phone'],
                        'password' => $dbUser['password'],
                        'isVerified' => (bool)$dbUser['is_verified'],
                        'memberSince' => $dbUser['member_since'],
                        'tier' => $dbUser['tier'],
                        'coins' => (int)$dbUser['coins'],
                        'preferredSize' => $dbUser['preferred_size'],
                        'addresses' => is_string($dbUser['addresses']) ? json_decode($dbUser['addresses'], true) : ($dbUser['addresses'] ?? [])
                    ];
                }
            } catch (Exception $e) {
                error_log("MySQL user_login error: " . $e->getMessage());
            }
        }

        if (!$foundUser) {
            echo json_encode(['success' => false, 'error' => 'No account found with this email or mobile number. Please register.']);
            exit;
        }

        // Login via OTP
        if (!empty($otp)) {
            if ($pdo) {
                $target = strtolower($foundUser['email']) . '_' . cleanPhone($foundUser['phone']);
                $otpQuery = $pdo->prepare("SELECT id, expires_at, is_used FROM otps WHERE target = ? AND otp_code = ? ORDER BY id DESC LIMIT 1");
                $otpQuery->execute([$target, $otp]);
                $otpRow = $otpQuery->fetch(PDO::FETCH_ASSOC);

                if (!$otpRow || $otpRow['is_used'] == 1 || strtotime($otpRow['expires_at']) < time()) {
                    echo json_encode(['success' => false, 'error' => 'Incorrect or expired OTP code.']);
                    exit;
                }
                $markUsed = $pdo->prepare("UPDATE otps SET is_used = 1 WHERE id = ?");
                $markUsed->execute([$otpRow['id']]);
            }
        } else {
            // Login via Password
            $storedPass = $foundUser['password'] ?? '';
            $isCorrect = ($password === $storedPass) || (strlen($storedPass) > 20 && password_verify($password, $storedPass));

            if (empty($password) || !$isCorrect) {
                echo json_encode(['success' => false, 'error' => 'Incorrect password. Please try again or use OTP login.']);
                exit;
            }
        }

        $safeUser = $foundUser;
        unset($safeUser['password']);
        $_SESSION['feather_user'] = $safeUser;

        echo json_encode([
            'success' => true,
            'message' => 'Signed in successfully. Welcome back, ' . htmlspecialchars($safeUser['firstName']) . '!',
            'user' => $safeUser
        ]);
        break;

    // =========================================================================
    // 4. USER LOGOUT
    // =========================================================================
    case 'user_logout':
        unset($_SESSION['feather_user']);
        echo json_encode(['success' => true, 'message' => 'User logged out.']);
        break;

    // =========================================================================
    // 5. ADMIN LOGIN (STORED & READ FROM MYSQL ADMINS)
    // =========================================================================
    case 'admin_login':
        $usernameOrEmail = trim(strtolower($inputData['username'] ?? ''));
        $password = $inputData['password'] ?? '';

        if (empty($usernameOrEmail) || empty($password)) {
            echo json_encode(['success' => false, 'error' => 'Username/Email and Password are required.']);
            exit;
        }

        $foundAdmin = null;

        if ($pdo) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM admins WHERE LOWER(username) = ? OR LOWER(email) = ? LIMIT 1");
                $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
                $foundAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                error_log("MySQL admin_login error: " . $e->getMessage());
            }
        }

        $storedAdminPass = $foundAdmin['password'] ?? '';
        $isAdminPassCorrect = ($password === $storedAdminPass) || (strlen($storedAdminPass) > 20 && password_verify($password, $storedAdminPass));

        if (!$foundAdmin || !$isAdminPassCorrect) {
            echo json_encode(['success' => false, 'error' => 'Invalid admin credentials. Access denied.']);
            exit;
        }

        $safeAdmin = $foundAdmin;
        unset($safeAdmin['password']);
        $_SESSION['admin_user'] = $safeAdmin;

        echo json_encode([
            'success' => true,
            'message' => 'Admin authenticated successfully.',
            'admin' => $safeAdmin
        ]);
        break;

    // =========================================================================
    // 6. ADMIN LOGOUT
    // =========================================================================
    case 'admin_logout':
        unset($_SESSION['admin_user']);
        echo json_encode(['success' => true, 'message' => 'Admin session terminated.']);
        break;

    // =========================================================================
    // 7. CREATE OR REGISTER NEW ADMINISTRATOR (STORE IN MYSQL ADMINS)
    // =========================================================================
    case 'admin_register':
    case 'create_admin':
        $username = trim(strtolower($inputData['username'] ?? ''));
        $email = trim(strtolower($inputData['email'] ?? ''));
        $name = trim($inputData['name'] ?? '');
        $role = trim($inputData['role'] ?? 'Super Admin');
        $password = $inputData['password'] ?? '';
        $autoLogin = !empty($inputData['auto_login']) || ($action === 'admin_register');

        if (empty($username) || empty($password) || empty($email) || empty($name)) {
            echo json_encode(['success' => false, 'error' => 'Username, Email, Full Name, and Password are all required.']);
            exit;
        }

        if (strlen($password) < 4) {
            echo json_encode(['success' => false, 'error' => 'Password must be at least 4 characters long.']);
            exit;
        }

        if ($pdo) {
            try {
                $checkStmt = $pdo->prepare("SELECT id, username, email FROM admins WHERE LOWER(username) = ? OR LOWER(email) = ? LIMIT 1");
                $checkStmt->execute([$username, $email]);
                $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
                if ($existing) {
                    if (strtolower($existing['username']) === $username) {
                        echo json_encode(['success' => false, 'error' => "Admin username '$username' is already taken."]);
                        exit;
                    }
                    if (strtolower($existing['email']) === $email) {
                        echo json_encode(['success' => false, 'error' => "Admin email '$email' is already registered."]);
                        exit;
                    }
                }

                $adminId = 'adm_' . time();
                $sql = "INSERT INTO admins (id, username, email, name, role, password) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $adminId,
                    $username,
                    $email,
                    $name,
                    $role,
                    $password
                ]);

                $safeAdmin = [
                    'id' => $adminId,
                    'username' => $username,
                    'email' => $email,
                    'name' => $name,
                    'role' => $role
                ];

                if ($autoLogin) {
                    $_SESSION['admin_user'] = $safeAdmin;
                }

                echo json_encode([
                    'success' => true,
                    'message' => "Administrator '$name' (@$username) registered successfully in MySQL!",
                    'admin' => $safeAdmin,
                    'autoLoggedIn' => $autoLogin
                ]);
                exit;

            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'MySQL admin_register error: ' . $e->getMessage()]);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Database connection failed. Unable to register administrator.']);
            exit;
        }
        break;

    // =========================================================================
    // 8. LIST ALL ADMINISTRATORS (READ DIRECTLY FROM MYSQL ADMINS)
    // =========================================================================
    case 'get_admins':
        $safeList = [];
        if ($pdo) {
            try {
                $stmt = $pdo->query("SELECT id, username, email, name, role, password, created_at FROM admins ORDER BY created_at ASC");
                $safeList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                error_log("MySQL get_admins error: " . $e->getMessage());
            }
        }
        echo json_encode(['success' => true, 'admins' => $safeList]);
        break;

    // =========================================================================
    // 9. LIST ALL REGISTERED USERS (READ DIRECTLY FROM MYSQL USERS)
    // =========================================================================
    case 'get_users':
        $usersList = [];
        if ($pdo) {
            try {
                $stmt = $pdo->query("SELECT id, first_name, last_name, name, email, phone, is_verified, member_since, tier, coins, preferred_size, addresses, created_at FROM users ORDER BY created_at DESC");
                $usersList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                error_log("MySQL get_users error: " . $e->getMessage());
            }
        }
        echo json_encode(['success' => true, 'users' => $usersList]);
        break;

    // =========================================================================
    // 10. UPDATE USER PROFILE (STORE DIRECTLY IN MYSQL USERS)
    // =========================================================================
    case 'update_profile':
        $userId = trim($inputData['userId'] ?? ($inputData['id'] ?? ''));
        $name = trim($inputData['name'] ?? '');
        $prefSize = trim($inputData['preferredSize'] ?? 'L');
        $addresses = $inputData['addresses'] ?? null;

        if (empty($userId) || empty($name)) {
            echo json_encode(['success' => false, 'error' => 'User ID and Name are required.']);
            exit;
        }

        $nameParts = explode(' ', $name, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        if ($pdo) {
            try {
                $sql = "UPDATE users SET name = ?, first_name = ?, last_name = ?, preferred_size = ?" . ($addresses !== null ? ", addresses = ?" : "") . " WHERE id = ?";
                $params = [$name, $firstName, $lastName, $prefSize];
                if ($addresses !== null) {
                    $params[] = json_encode($addresses);
                }
                $params[] = $userId;

                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);

                if (!empty($_SESSION['feather_user']) && $_SESSION['feather_user']['id'] === $userId) {
                    $_SESSION['feather_user']['name'] = $name;
                    $_SESSION['feather_user']['firstName'] = $firstName;
                    $_SESSION['feather_user']['lastName'] = $lastName;
                    $_SESSION['feather_user']['preferredSize'] = $prefSize;
                    if ($addresses !== null) {
                        $_SESSION['feather_user']['addresses'] = $addresses;
                    }
                }

                echo json_encode(['success' => true, 'message' => 'Profile updated successfully in MySQL!']);
                exit;
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'MySQL update_profile error: ' . $e->getMessage()]);
                exit;
            }
        }
        break;

    case 'get_session':
    default:
        echo json_encode([
            'success' => true,
            'user' => $_SESSION['feather_user'] ?? null,
            'admin' => $_SESSION['admin_user'] ?? null
        ]);
        break;
}
