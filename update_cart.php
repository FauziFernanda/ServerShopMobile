<?php
include_once 'dbconenct.php';

$id = $_POST['id'];
$type = $_POST['type'];

if ($type == 'add') {
    $sql = "UPDATE cart SET quantity = quantity + 1 WHERE id = $id";
} else if ($type == 'reduce') {
    $sql = "UPDATE cart SET quantity = quantity - 1 WHERE id = $id AND quantity > 1";
} else if ($type == 'delete_selected') {
    $sql = "DELETE FROM cart WHERE is_selected = 1";
} else if ($type == 'select') {
    $val = $_POST['value'];
    $sql = "UPDATE cart SET is_selected = $val WHERE id = $id";
}

if ($conn->query($sql)) {
    echo json_encode(["status" => "success"]);
}
