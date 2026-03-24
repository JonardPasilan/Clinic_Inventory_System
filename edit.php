<?php
include 'header.php';
include 'db.php';

$id = $_GET['id'];

$r = $conn->query("SELECT * FROM medicines WHERE id=$id");
$row = $r->fetch_assoc();
?>

<div class="container">
<h2>Edit Medicine</h2>

<form method="POST" action="update.php">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    Name: <br>
    <input type="text" name="name" value="<?php echo $row['name']; ?>"><br><br>

    Description: <br>
    <input type="text" name="label" value="<?php echo $row['label']; ?>"><br><br>

    Quantity: <br>
    <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>"><br><br>

    Expiration: <br>
    <input type="date" name="exp" value="<?php echo $row['expiration_date']; ?>"><br><br>

    <button name="update">Update</button>
</form>
</div>