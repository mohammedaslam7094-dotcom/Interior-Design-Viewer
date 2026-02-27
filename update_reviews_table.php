<?php
require_once 'includes/db.php';

$sql = "CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT,
    user_name VARCHAR(100),
    rating INT,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Reviews Table Created Successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
?>