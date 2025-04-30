<?php
session_start();
$host = 'localhost';
$db   = 'u555781181_Deep42025';
$user = 'u555781181_Deep42025';
$pass = 'Deep42025';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Admin authentication check
function checkAdminAuth() {
    if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
        header('Location: admin/index.php');
        exit();
    }
}

// Add to config.php
function checkCandidateAuth() {
    if (!isset($_SESSION['candidate_logged_in']) || !$_SESSION['candidate_logged_in']) {
        header('Location: index.php');
        exit();
    }
}

if (isset($_SESSION['candidate_id'])) {
    $stmt = $pdo->prepare("
        SELECT c.*, d.document_path AS profile_photo 
        FROM candidates c
        LEFT JOIN documents d ON c.id = d.candidate_id AND d.type = 'profile_photo'
        WHERE c.id = ?
    ");
    $stmt->execute([$_SESSION['candidate_id']]);
    $candidate = $stmt->fetch();
    $_SESSION['candidate_photo'] = $candidate['profile_photo'];
    $_SESSION['candidate_name'] = $candidate['name'];
}
?>