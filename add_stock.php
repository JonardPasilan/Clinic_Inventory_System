<?php
include 'header.php';
include 'db.php';

$id = intval($_GET['id']);

$r = $conn->query("SELECT * FROM medicines WHERE id=$id");

if($r && $r->num_rows > 0){
    $row = $r->fetch_assoc();
} else {
    echo "No data found!";
    exit();
}
?>

<div class="container">
<h2>Add Stock</h2>

<form method="POST" action="update_stock.php">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <p><b><?php echo $row['name']; ?></b></p>

    Current Quantity: <?php echo $row['quantity']; ?><br><br>

    Add Quantity: <br>
    <input type="number" name="add_qty" required><br><br>

    <button name="add">Add</button>
</form>
</div>