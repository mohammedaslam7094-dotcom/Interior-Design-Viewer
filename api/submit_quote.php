<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['material_id'], $input['name'], $input['phone'], $input['area'], $input['total'])) {

    $stmt = $conn->prepare("INSERT INTO quotes (material_id, user_name, user_phone, area_sqft, total_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issdd", $input['material_id'], $input['name'], $input['phone'], $input['area'], $input['total']);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Quote requested successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}
?>