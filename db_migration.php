<?php
require_once 'includes/db.php';

// 1. Create material_categories table
$sql = "CREATE TABLE IF NOT EXISTS material_categories (
    material_id INT,
    category_id INT,
    PRIMARY KEY (material_id, category_id),
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table material_categories created/exists.\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
    exit;
}

// 2. Migrate existing data
$sql = "SELECT id, category_id FROM materials WHERE category_id IS NOT NULL";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Migrating " . $result->num_rows . " materials...\n";
    while ($row = $result->fetch_assoc()) {
        $mid = $row['id'];
        $cid = $row['category_id'];

        // Check if already exists to avoid duplicate key error if run multiple times
        $check = $conn->query("SELECT * FROM material_categories WHERE material_id = $mid AND category_id = $cid");
        if ($check->num_rows == 0) {
            $ins = "INSERT INTO material_categories (material_id, category_id) VALUES ($mid, $cid)";
            if (!$conn->query($ins)) {
                echo "Failed to migrate material $mid: " . $conn->error . "\n";
            }
        }
    }
    echo "Migration completed.\n";
} else {
    echo "No materials to migrate.\n";
}

echo "Database update finished.";
?>