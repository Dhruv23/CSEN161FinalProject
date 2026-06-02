<?php
// explore.php
header('Content-Type: application/json');

try {
    $db = new PDO('sqlite:cosmoguide.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM planets WHERE 1=1";
    $params = [];

    // Filter by Type (checkbox array)
    if (!empty($_GET['types'])) {
        $types = explode(',', $_GET['types']);
        $placeholders = str_repeat('?,', count($types) - 1) . '?';
        $query .= " AND type IN ($placeholders)";
        $params = array_merge($params, $types);
    }

    // Filter by Atmosphere
    if (!empty($_GET['atmosphere']) && $_GET['atmosphere'] !== 'All') {
        $query .= " AND atmosphere = ?";
        $params[] = $_GET['atmosphere'];
    }

    // Filter by Max Distance
    if (!empty($_GET['max_distance'])) {
        $query .= " AND distance_from_sun <= ?";
        $params[] = $_GET['max_distance'];
    }

    // Filter by Search Term
    if (!empty($_GET['search'])) {
        $query .= " AND name LIKE ?";
        $params[] = '%' . $_GET['search'] . '%';
    }

    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>