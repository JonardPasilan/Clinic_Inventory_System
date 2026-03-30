<?php
include 'db.php';

if(isset($_POST['delete'])){
    $id = intval($_POST['id']);

    $conn->query("DELETE FROM employees WHERE id=$id");

    header("Location: employees.php");
}
?>