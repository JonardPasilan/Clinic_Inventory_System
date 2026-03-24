<?php
include 'db.php';

if(isset($_POST['add'])){
    $id = $_POST['id'];
    $add = $_POST['add_qty'];

    $r = $conn->query("SELECT quantity FROM medicines WHERE id=$id");
    $row = $r->fetch_assoc();

    $new_qty = $row['quantity'] + $add;

    $conn->query("UPDATE medicines SET quantity=$new_qty WHERE id=$id");

    header("Location: index.php");
}
?>