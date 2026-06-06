<?php
// user.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['logged_in' => false]);
    exit;
}

try {
    $db = new PDO('sqlite:' . __DIR__ . '/cosmoguide.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("SELECT full_name FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && !empty($user['full_name'])) {
        echo json_encode([
            'logged_in' => true,
            'full_name' => $user['full_name']
        ]);
    } else {
        echo json_encode(['logged_in' => false]);
    }
} catch (PDOException $e) {
    echo json_encode(['logged_in' => false]);
}
