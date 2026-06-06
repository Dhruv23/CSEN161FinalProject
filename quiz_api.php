<?php
// quiz_api.php
header('Content-Type: application/json');

try {
    $db = new PDO('sqlite:' . __DIR__ . '/cosmoguide.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get requested category, default to General Astronomy
    $category = $_GET['category'] ?? 'General Astronomy';
    
    $query = "SELECT * FROM quiz_questions ";
    $params = [];

    // Allow fetching from 'All Categories' if needed
    if ($category !== 'All Categories') {
        $query .= "WHERE category = ? ";
        $params[] = $category;
    }

    // Pull 10 random questions
    $query .= "ORDER BY RANDOM() LIMIT 10";
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $formattedQuestions = [];
    foreach ($results as $row) {
        $formattedQuestions[] = [
            'question' => $row['question'],
            'options' => [$row['option_0'], $row['option_1'], $row['option_2'], $row['option_3']],
            'correctIndex' => (int)$row['correct_index']
        ];
    }

    echo json_encode($formattedQuestions);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>