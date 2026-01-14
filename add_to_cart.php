<?php
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
    $msg = "Jumlah barang berhasil ditambah";
} else {
    $sql = "INSERT INTO cart (user_id, product_id, quantity) 
            VALUES ('$user_id', '$product_id', '$quantity')";
    $msg = "Berhasil masuk keranjang";
}

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => $msg]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
}

$conn->close();
?>