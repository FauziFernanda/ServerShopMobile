<?php
include_once 'dbconenct.php';

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;

if (!$user_id || !$product_id) {
    echo json_encode(["status" => "error", "message" => "Data user_id atau product_id kosong"]);
    exit;
}

$check = $conn->prepare("SELECT id FROM cart WHERE product_id = ? AND user_id = ?");
$check->bind_param("ii", $product_id, $user_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    $stat = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE product_id = ? AND user_id = ?");
    $stat->bind_param("ii", $product_id, $user_id);
} else {
    $stat = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, is_selected) VALUES (?, ?, 1, 0)");
    $stat->bind_param("ii", $user_id, $product_id);
}

if ($stat->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>