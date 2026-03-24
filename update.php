<?php
include 'db.php';

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $n = $_POST['name'];
    $l = $_POST['label'];
    $q = $_POST['quantity'];
    $e = $_POST['exp'];

    $conn->query("UPDATE medicines SET 
        name='$n',
        label='$l',
        quantity='$q',
        expiration_date='$e'
        WHERE id=$id
    ");

    header("Location: index.php");
}
?>