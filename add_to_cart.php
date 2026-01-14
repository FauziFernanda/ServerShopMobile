<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'dbconenct.php'; 

$user_id    = $_POST['user_id'];
$product_id = $_POST['product_id']; 
$quantity   = isset($_POST['quantity']) ? $_POST['quantity'] : 1; 

$checkCart = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
$result = $conn->query($checkCart);

if ($result->num_rows > 0) {
    $sql = "UPDATE cart SET quantity = quantity + $quantity 
            WHERE user_id = '$user_id' AND product_id = '$product_id'";
} else {
    $sql = "INSERT INTO cart (user_id, product_id, quantity) 
            VALUES ('$user_id', '$product_id', '$quantity')";
}

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Berhasil update/insert"]);
} else {
    echo json_encode(["success" => false, "message" => "Error DB: " . $conn->error]);
}
$conn->close();
?>