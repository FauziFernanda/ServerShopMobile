<?php
include_once 'dbconenct.php';

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if ($user_id) {
$sql = "SELECT c.id as cart_id, p.id as product_id, p.name, p.price, p.promo, p.images, c.quantity, c.is_selected
        FROM cart c 
        JOIN product_items p ON c.product_id = p.id 
        WHERE c.user_id = '$user_id'";

$result = $conn->query($sql);
$cart_list = array();

while ($row = $result->fetch_assoc()) {
    $cart_list[] = $row;
}

echo json_encode($cart_list);
} else {
    echo json_encode([]);
}
