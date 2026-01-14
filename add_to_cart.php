<?php
include_once 'dbconenct.php';

$user_id = $_POST['user_id'];
$product_id = $_POST['product_id'];

$check = $conn->prepare("SELECT id FROM cart WHERE product_id = ? AND user_id = ?");
$check->bind_param("ii", $product_id, $user_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    $stat = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE product_id = ? AND user_id = ?");
} else {
    $stat = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
}

$stat->bind_param("ii", $product_id, $user_id);
if ($stat->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}
?>