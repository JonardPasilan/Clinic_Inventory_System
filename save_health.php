<?php
include 'db.php';

if(isset($_POST['save'])){
    $eid = $_POST['employee_id'];
    $b = $_POST['blood_type'];
    $h = $_POST['height'];
    $w = $_POST['weight'];
    $a = $_POST['allergies'];
    $c = $_POST['conditions'];

    $conn->query("INSERT INTO employee_health 
    (employee_id, blood_type, height, weight, allergies, conditions)
    VALUES
    ('$eid','$b','$h','$w','$a','$c')");

    header("Location: employees.php");
}
?>