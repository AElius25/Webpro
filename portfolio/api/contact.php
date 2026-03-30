<?php
/**
 * api/contact.php
 * Endpoint AJAX untuk menerima dan memproses form kontak
 * 
 * Method: POST
 * Body (JSON): name, email, subject, message
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Hanya terima AJAX
if (
    empty($_SERVER['HTTP_X_REQUESTED_WITH']) ||
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden.']);
    exit;
}

// Baca JSON body
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Format data tidak valid.']);
    exit;
}

// ===========================
// VALIDASI
// ===========================
$errors = [];

$name    = isset($data['name'])    ? trim($data['name'])    : '';
$email   = isset($data['email'])   ? trim($data['email'])   : '';
$subject = isset($data['subject']) ? trim($data['subject']) : '';
$message = isset($data['message']) ? trim($data['message']) : '';

if (empty($name)) {
    $errors['name'] = 'Nama tidak boleh kosong.';
} elseif (strlen($name) > 100) {
    $errors['name'] = 'Nama terlalu panjang (max 100 karakter).';
}

if (empty($email)) {
    $errors['email'] = 'Email tidak boleh kosong.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Format email tidak valid.';
}

if (empty($message)) {
    $errors['message'] = 'Pesan tidak boleh kosong.';
} elseif (strlen($message) < 10) {
    $errors['message'] = 'Pesan minimal 10 karakter.';
} elseif (strlen($message) > 2000) {
    $errors['message'] = 'Pesan terlalu panjang (max 2000 karakter).';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'message' => 'Data tidak valid.',
        'errors'  => $errors,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ===========================
// SANITIZE
// ===========================
$name    = htmlspecialchars($name,    ENT_QUOTES, 'UTF-8');
$email   = filter_var($email, FILTER_SANITIZE_EMAIL);
$subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

// ===========================
// PROSES (Simpan ke file log / kirim email)
// ===========================

// ---- Opsi A: Simpan ke log file ----
$log_dir  = __DIR__ . '/../storage/';
$log_file = $log_dir . 'messages.json';

if (!is_dir($log_dir)) {
    mkdir($log_dir, 0755, true);
}

$new_message = [
    'id'         => uniqid('msg_', true),
    'name'       => $name,
    'email'      => $email,
    'subject'    => $subject ?: 'Tidak ada subjek',
    'message'    => $message,
    'ip'         => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
    'timestamp'  => date('Y-m-d H:i:s'),
];

$messages = [];
if (file_exists($log_file)) {
    $existing = file_get_contents($log_file);
    $messages = json_decode($existing, true) ?: [];
}
$messages[] = $new_message;

$saved = file_put_contents(
    $log_file,
    json_encode($messages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
);

// ---- Opsi B: Kirim Email (uncomment jika mail server tersedia) ----
/*
$to      = 'raka@example.com';
$subj    = '[Portofolio] Pesan baru dari ' . $name . ' — ' . ($subject ?: 'Tidak ada subjek');
$body    = "Nama    : {$name}\nEmail   : {$email}\nSubjek  : {$subject}\n\nPesan:\n{$message}";
$headers = "From: noreply@portofolio.com\r\nReply-To: {$email}\r\nContent-Type: text/plain; charset=UTF-8";
mail($to, $subj, $body, $headers);
*/

// ===========================
// RESPONSE
// ===========================
if ($saved !== false) {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Pesan berhasil dikirim! Saya akan segera menghubungi Anda.',
        'id'      => $new_message['id'],
    ], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan pesan. Coba lagi nanti.',
    ], JSON_UNESCAPED_UNICODE);
}
