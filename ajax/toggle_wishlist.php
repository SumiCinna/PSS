<?php
session_start();
require_once '../config/config.php';

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$product_id = intval($data['product_id']);
$user_id = $_SESSION['user_id'];

$check_stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
$check_stmt->bind_param("ii", $user_id, $product_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if($result->num_rows > 0){
    $delete_stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $delete_stmt->bind_param("ii", $user_id, $product_id);
    $delete_stmt->execute();
    echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Removed from wishlist']);
} else {
    $insert_stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id, created_at) VALUES (?, ?, NOW())");
    $insert_stmt->bind_param("ii", $user_id, $product_id);
    $insert_stmt->execute();
    echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Added to wishlist']);
}
?>