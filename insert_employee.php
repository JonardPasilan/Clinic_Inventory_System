<?php
include 'db.php';

if(isset($_POST['save'])){
    $n = $_POST['name'];
    $a = $_POST['age'];
    $s = $_POST['sex'];
    $b = $_POST['birthday'];
    $ad = $_POST['address'];
    $c = $_POST['contact'];
    $d = $_POST['department'];
    $cs = $_POST['civil_status'];

    $conn->query("INSERT INTO employees 
    (name, age, sex, birthday, address, contact, department, civil_status) 
    VALUES 
    ('$n','$a','$s','$b','$ad','$c','$d','$cs')");

    header("Location: employees.php");
}
?>