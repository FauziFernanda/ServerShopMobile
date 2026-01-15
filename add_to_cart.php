<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

include_once 'dbconenct.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "status" => "error",
        "message" => "Method not allowed. Use POST."
    ]);
    exit;
}

$user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : null;
$product_id = isset($_POST['product_id']) ? trim($_POST['product_id']) : null;
$quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : '1';

if ($user_id === null || $product_id === null) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "status" => "error",
        "message" => "Missing parameters: user_id and product_id are required."
    ]);
    exit;
}

if (!ctype_digit($user_id) || !ctype_digit($product_id) || !ctype_digit($quantity)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "status" => "error",
        "message" => "Invalid parameters: user_id, product_id and quantity must be integers."
    ]);
    exit;
}

$user_id = (int) $user_id;
$product_id = (int) $product_id;
$quantity = max(1, (int) $quantity);

try {
    $conn->begin_transaction();

    $check = $conn->prepare("SELECT id, quantity FROM cart WHERE product_id = ? AND user_id = ?");
    $check->bind_param("ii", $product_id, $user_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        
        $row = $result->fetch_assoc();
        $newQuantity = $row['quantity'] + $quantity;
        $stat = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stat->bind_param("ii", $newQuantity, $row['id']);
    } else {
        
        $stat = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stat->bind_param("iii", $user_id, $product_id, $quantity);
    }

    if ($stat->execute()) {
        $conn->commit();
        echo json_encode([
            "success" => true,
            "status" => "success",
            "message" => "Product added to cart."
        ]);
    } else {
        $conn->rollback();
        echo json_encode([
            "success" => false,
            "status" => "error",
            "message" => "Database error: " . $conn->error
        ]);
    }
} catch (Exception $e) {
    if ($conn->errno) {
        $conn->rollback();
    }
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "status" => "error",
        "message" => "Server exception: " . $e->getMessage()
    ]);
}
?>