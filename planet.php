<?php
// planet.php
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'No ID provided']);
    exit;
}

try {
    $db = new PDO('sqlite:' . __DIR__ . '/cosmoguide.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("SELECT * FROM planets WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $planet = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($planet) {
        echo json_encode($planet);
    } else {
        echo json_encode(['error' => 'Planet not found']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>