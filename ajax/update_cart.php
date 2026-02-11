<?php
session_start();
require_once '../config/config.php';

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$cart_id = isset($data['cart_id']) ? intval($data['cart_id']) : 0;
$quantity = isset($data['quantity']) ? intval($data['quantity']) : 1;
$user_id = $_SESSION['user_id'];

if($cart_id <= 0 || $quantity < 1){
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit();
}

$verify_stmt = $conn->prepare("SELECT id FROM cart WHERE id = ? AND user_id = ?");
$verify_stmt->bind_param("ii", $cart_id, $user_id);
$verify_stmt->execute();
$result = $verify_stmt->get_result();

if($result->num_rows === 0){
    echo json_encode(['success' => false, 'message' => 'Cart item not found']);
    exit();
}
$verify_stmt->close();

$stmt = $conn->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
$stmt->bind_param("iii", $quantity, $cart_id, $user_id);

if($stmt->execute()){
    echo json_encode(['success' => true, 'message' => 'Cart updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update cart']);
}

$stmt->close();
$conn->close();
?>