<?php
// setup_db.php
try {
    // Create or open the SQLite database file
    $db = new PDO('sqlite:' . __DIR__ . '/cosmoguide.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Users Table
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        full_name TEXT,
        email TEXT UNIQUE,
        password TEXT
    )");

    // Add full_name column if upgrading an existing database
    $columns = $db->query("PRAGMA table_info(users)")->fetchAll(PDO::FETCH_ASSOC);
    $hasFullName = false;
    foreach ($columns as $column) {
        if ($column['name'] === 'full_name') {
            $hasFullName = true;
            break;
        }
    }
    if (!$hasFullName) {
        $db->exec("ALTER TABLE users ADD COLUMN full_name TEXT");
    }

    // Insert a test user (Password is 'password123')
    $passwordHash = password_hash('password123', PASSWORD_DEFAULT);
    $db->exec("INSERT OR IGNORE INTO users (full_name, email, password) VALUES ('Test User', 'test@cosmoguide.com', '$passwordHash')");

    // Create Planets Table
    $db->exec("CREATE TABLE IF NOT EXISTS planets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        type TEXT,
        atmosphere TEXT,
        distance_from_sun REAL
    )");

    // Clear existing data to avoid duplicates on re-run
    $db->exec("DELETE FROM planets");

    // Insert sample planet data
    $planets = [
        ['Mercury', 'Planet', 'None', 0.39],
        ['Venus', 'Planet', 'Carbon Dioxide', 0.72],
        ['Earth', 'Planet', 'Nitrogen/Oxygen', 1.0],
        ['Pluto', 'dwarf planet', 'Nitrogen', 39.48],
        ['Europa', 'moon', 'Oxygen', 5.2]
    ];

    $stmt = $db->prepare("INSERT INTO planets (name, type, atmosphere, distance_from_sun) VALUES (?, ?, ?, ?)");
    foreach ($planets as $planet) {
        $stmt->execute($planet);
    }

    echo "<h3>Database setup complete.</h3>";
    echo "<p>Test user: test@cosmoguide.com / password123</p>";

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>