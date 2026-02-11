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
$user_id = $_SESSION['user_id'];

if($cart_id <= 0){
    echo json_encode(['success' => false, 'message' => 'Invalid cart ID']);
    exit();
}

$stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $cart_id, $user_id);

if($stmt->execute()){
    echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to remove item']);
}

$stmt->close();
$conn->close();
?>