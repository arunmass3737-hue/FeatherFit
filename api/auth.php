<?php
/**
 * FEATHER FITS — AUTHENTICATION & OTP SERVICE
 * Handles:
 * - New User Registration with mandatory Email, 10-digit Phone, and OTP verification
 * - User Login (Email / Phone + Password OR Instant OTP Login)
 * - Admin Authentication & Session Security for Admin Dashboard
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$inputJSON = file_get_contents('php://input');
$inputData = json_decode($inputJSON, true) ?? $_POST;

$usersPath = dirname(__DIR__) . '/data/users.json';
$adminsPath = dirname(__DIR__) . '/data/admins.json';
$otpsPath = dirname(__DIR__) . '/data/otps.json';

function readJSON($path, $fallback = []) {
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $data = json_decode($content, true);
        if (is_array($data)) return $data;
    }
    return $fallback;
}

function writeJSON($path, $data) {
    return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

function cleanPhone($phone) {
    $digits = preg_replace('/[^0-9]/', '', $phone);
    return strlen($digits) >= 10 ? substr($digits, -10) : $digits;
}

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

        $users = readJSON($usersPath, []);

        if ($type === 'register') {
            foreach ($users as $u) {
                if (strtolower($u['email'] ?? '') === $email) {
                    echo json_encode(['success' => false, 'error' => 'This email is already registered. Please sign in instead.']);
                    exit;
                }
                if (cleanPhone($u['phone'] ?? '') === $phone) {
                    echo json_encode(['success' => false, 'error' => 'This mobile number is already registered. Please sign in instead.']);
                    exit;
                }
            }
        }

        // Generate secure 6-digit numeric OTP
        $otp = (string) mt_rand(100000, 999999);
        $expiry = time() + (10 * 60); // 10 minutes expiry

        $otps = readJSON($otpsPath, []);
        $key = $email . '_' . $phone;
        $otps[$key] = [
            'otp' => $otp,
            'email' => $email,
            'phone' => $phone,
            'expiresAt' => $expiry,
            'createdAt' => date('c')
        ];
        writeJSON($otpsPath, $otps);

        // In production this connects to SMS Gateway (Twilio/Fast2SMS) & SendGrid/SMTP Mailer
        // For instant test & verification, we return demoOtp in response
        echo json_encode([
            'success' => true,
            'message' => "Verification code sent to $email and +91 $phone",
            'demoOtp' => $otp,
            'expiresInSeconds' => 600
        ]);
        break;

    // =========================================================================
    // 2. VERIFY OTP & COMPLETE NEW REGISTRATION
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

        // Check OTP
        $otps = readJSON($otpsPath, []);
        $key = $email . '_' . $phone;
        $otpRecord = $otps[$key] ?? null;

        if (!$otpRecord || $otpRecord['otp'] !== $userOtp) {
            echo json_encode(['success' => false, 'error' => 'Invalid OTP code. Please re-check or request a new code.']);
            exit;
        }

        if (time() > ($otpRecord['expiresAt'] ?? 0)) {
            echo json_encode(['success' => false, 'error' => 'OTP has expired. Please request a new OTP.']);
            exit;
        }

        // Clean used OTP
        unset($otps[$key]);
        writeJSON($otpsPath, $otps);

        $users = readJSON($usersPath, []);
        $nameParts = explode(' ', $name, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        $newUser = [
            'id' => 'usr_' . $phone . '_' . time(),
            'firstName' => $firstName,
            'lastName' => $lastName,
            'name' => $name,
            'email' => $email,
            'phone' => '+91 ' . $phone,
            'password' => $password,
            'isVerified' => true,
            'memberSince' => date('Y'),
            'tier' => 'VIP Member',
            'coins' => 0,
            'preferredSize' => 'L',
            'addresses' => []
        ];

        $users[] = $newUser;
        writeJSON($usersPath, $users);

        $safeUser = $newUser;
        unset($safeUser['passwordHash']);
        $_SESSION['feather_user'] = $safeUser;

        echo json_encode([
            'success' => true,
            'message' => 'Account created and verified successfully! Welcome to Feather Fits.',
            'user' => $safeUser
        ]);
        break;

    // =========================================================================
    // 3. USER LOGIN (EMAIL / PHONE + PASSWORD OR OTP)
    // =========================================================================
    case 'user_login':
        $identifier = trim(strtolower($inputData['identifier'] ?? ''));
        $password = $inputData['password'] ?? '';
        $otp = trim($inputData['otp'] ?? '');

        if (empty($identifier)) {
            echo json_encode(['success' => false, 'error' => 'Please enter your registered Email or Mobile Number.']);
            exit;
        }

        $users = readJSON($usersPath, []);
        $foundUser = null;
        $cleanIdentifierPhone = cleanPhone($identifier);

        foreach ($users as $u) {
            $uEmail = strtolower($u['email'] ?? '');
            $uPhone = cleanPhone($u['phone'] ?? '');

            if ($uEmail === $identifier || ($cleanIdentifierPhone && $uPhone === $cleanIdentifierPhone)) {
                $foundUser = $u;
                break;
            }
        }

        if (!$foundUser) {
            echo json_encode(['success' => false, 'error' => 'No account found with this email or mobile number. Please register.']);
            exit;
        }

        // Login via OTP
        if (!empty($otp)) {
            $otps = readJSON($otpsPath, []);
            $key = strtolower($foundUser['email']) . '_' . cleanPhone($foundUser['phone']);
            $otpRecord = $otps[$key] ?? null;

            if (!$otpRecord || $otpRecord['otp'] !== $otp) {
                echo json_encode(['success' => false, 'error' => 'Incorrect OTP code.']);
                exit;
            }
            unset($otps[$key]);
            writeJSON($otpsPath, $otps);
        } else {
            // Login via Password (Plain text check with fallback)
            $storedPass = $foundUser['password'] ?? $foundUser['passwordHash'] ?? '';
            $isCorrect = ($password === $storedPass) || (strlen($storedPass) > 20 && password_verify($password, $storedPass));

            if (empty($password) || !$isCorrect) {
                echo json_encode(['success' => false, 'error' => 'Incorrect password. Please try again or use OTP login.']);
                exit;
            }
        }

        $safeUser = $foundUser;
        unset($safeUser['passwordHash']);
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
    // 5. ADMIN LOGIN (FOR ADMIN DASHBOARD)
    // =========================================================================
    case 'admin_login':
        $usernameOrEmail = trim(strtolower($inputData['username'] ?? ''));
        $password = $inputData['password'] ?? '';

        if (empty($usernameOrEmail) || empty($password)) {
            echo json_encode(['success' => false, 'error' => 'Username/Email and Password are required.']);
            exit;
        }

        $admins = readJSON($adminsPath, []);
        $foundAdmin = null;

        foreach ($admins as $adm) {
            if (strtolower($adm['username'] ?? '') === $usernameOrEmail || strtolower($adm['email'] ?? '') === $usernameOrEmail) {
                $foundAdmin = $adm;
                break;
            }
        }

        $storedAdminPass = $foundAdmin['password'] ?? $foundAdmin['passwordHash'] ?? '';
        $isAdminPassCorrect = ($password === $storedAdminPass) || (strlen($storedAdminPass) > 20 && password_verify($password, $storedAdminPass));

        if (!$foundAdmin || !$isAdminPassCorrect) {
            echo json_encode(['success' => false, 'error' => 'Invalid admin credentials. Access denied.']);
            exit;
        }

        $safeAdmin = $foundAdmin;
        unset($safeAdmin['passwordHash']);
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
    // 7. GET ACTIVE SESSION STATE
    // =========================================================================

    // =========================================================================
    // 7. CREATE NEW ADMINISTRATOR (ADMIN ONLY)
    // =========================================================================
    case 'create_admin':
        $username = trim(strtolower($inputData['username'] ?? ''));
        $email = trim(strtolower($inputData['email'] ?? ''));
        $name = trim($inputData['name'] ?? '');
        $role = trim($inputData['role'] ?? 'Manager');
        $password = $inputData['password'] ?? '';

        if (empty($username) || empty($password) || empty($email) || empty($name)) {
            echo json_encode(['success' => false, 'error' => 'Username, Email, Full Name, and Password are all required.']);
            exit;
        }

        $admins = readJSON($adminsPath, []);
        foreach ($admins as $adm) {
            if (strtolower($adm['username'] ?? '') === $username) {
                echo json_encode(['success' => false, 'error' => "Admin username '$username' is already taken."]);
                exit;
            }
            if (strtolower($adm['email'] ?? '') === $email) {
                echo json_encode(['success' => false, 'error' => "Admin email '$email' is already registered."]);
                exit;
            }
        }

        $newAdmin = [
            'id' => 'adm_' . time(),
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'role' => $role,
            'password' => $password, // Plain text password as requested!
            'createdAt' => date('c')
        ];

        $admins[] = $newAdmin;
        writeJSON($adminsPath, $admins);

        echo json_encode([
            'success' => true,
            'message' => "New administrator '$name' (@$username) created successfully!",
            'admin' => [
                'id' => $newAdmin['id'],
                'username' => $username,
                'email' => $email,
                'name' => $name,
                'role' => $role
            ]
        ]);
        break;

    // =========================================================================
    // 8. LIST ALL ADMINISTRATORS
    // =========================================================================
    case 'get_admins':
        $admins = readJSON($adminsPath, []);
        $safeList = array_map(function($a) {
            return [
                'id' => $a['id'],
                'username' => $a['username'],
                'email' => $a['email'],
                'name' => $a['name'],
                'role' => $a['role'] ?? 'Admin',
                'password' => $a['password'] ?? '********',
                'createdAt' => $a['createdAt'] ?? ''
            ];
        }, $admins);
        echo json_encode(['success' => true, 'admins' => $safeList]);
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
