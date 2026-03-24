<?php
include 'db.php';

if(isset($_POST['delete'])){
    $id = $_POST['id'];

    // check muna kung may ID
    if(!empty($id)){
        $conn->query("DELETE FROM medicines WHERE id=$id");
    }
}

// balik sa index after delete
header("Location: index.php");
exit();
?>