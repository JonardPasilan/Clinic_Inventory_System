<?php
include 'db.php';

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $n = $_POST['name'];
    $a = $_POST['age'];
    $s = $_POST['sex'];
    $b = $_POST['birthday'];
    $ad = $_POST['address'];
    $c = $_POST['contact'];
    $d = $_POST['department'];
    $cs = $_POST['civil_status'];

    $conn->query("UPDATE employees SET 
        name='$n',
        age='$a',
        sex='$s',
        birthday='$b',
        address='$ad',
        contact='$c',
        department='$d',
        civil_status='$cs'
        WHERE id=$id
    ");

    header("Location: employees.php");
}
?>