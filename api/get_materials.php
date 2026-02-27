<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$category = $_GET['category'] ?? 'all';

$sql = "SELECT m.*, 
        GROUP_CONCAT(c.name SEPARATOR ', ') as category_name, 
        GROUP_CONCAT(c.slug SEPARATOR ', ') as category_slug, 
        GROUP_CONCAT(c.type SEPARATOR ', ') as category_type 
        FROM materials m 
        LEFT JOIN material_categories mc ON m.id = mc.material_id 
        LEFT JOIN categories c ON mc.category_id = c.id 
        WHERE 1=1";

if ($category != 'all') {
    $category = $conn->real_escape_string($category);
    $sql .= " AND m.id IN (
        SELECT mc2.material_id FROM material_categories mc2 
        JOIN categories c2 ON mc2.category_id = c2.id 
        WHERE c2.slug = '$category' OR c2.type = '$category'
    )";
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql .= " AND m.name LIKE '%$search%'";
}

$sql .= " GROUP BY m.id";

$result = $conn->query($sql);

$materials = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $materials[] = $row;
    }
}

echo json_encode($materials);
?>