<?php
include_once 'dbconenct.php';

$username = $_POST['username'];
$password = $_POST['password'];

$stat = $conn->prepare("SELECT id, nama, password FROM users WHERE username = ?");
$stat->bind_param("s", $username);
$stat->execute();
$result = $stat->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        echo json_encode([
            "status" => "success",
            "user_id" => $user['id'],
            "nama" => $user['nama']
        ]);
    } else {
        echo json_encode(["status" => "wrong"]);
    }
} else {
    echo json_encode(["status" => "not_found"]);
}
?>