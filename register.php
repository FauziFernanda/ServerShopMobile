<?php
include_once 'dbconenct.php';

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$check = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    echo json_encode(["status" => "exists"]);
} else {
    $stat = $conn->prepare("INSERT INTO users (nama, username, password) VALUES (?, ?, ?)");
    $stat->bind_param("sss", $nama, $username, $password);
    if ($stat->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}
