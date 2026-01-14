<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'dbconenct.php';

$user_id    = $_POST['user_id'];
$product_id = $_POST['product_id']; 
$quantity   = isset($_POST['quantity']) ? $_POST['quantity'] : 1; 

// Cek apakah barang sudah ada
$checkCart = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
$result = $conn->query($checkCart);

if ($result->num_rows > 0) {
    // Update jumlah jika sudah ada
    $sql = "UPDATE cart SET quantity = quantity + $quantity 
            WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $msg = "Jumlah barang berhasil ditambah";
} else {
    // Insert baru jika belum ada
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