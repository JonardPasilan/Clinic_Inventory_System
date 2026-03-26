<?php 
include 'header.php';
?>

<div class="nav" style="background:#2c3e50; padding:10px; text-align:center;">
    <a href="add.php" style="color:white; margin:10px;">Add Medicine</a>
    <a href="dispense.php" style="color:white; margin:10px;">Dispense</a>
    <a href="logs.php" style="color:white; margin:10px;">Logs</a>
</div>

<?php
include 'db.php';
?>

<div class="container">
<h2>Medicine Inventory</h2>

<!-- SEARCH -->
<form method="GET">
    <input type="text" name="search" 
    value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" 
    placeholder="Search medicine...">
    <button type="submit">Search</button>
</form>

<br>

<table border="1" cellpadding="10">
<tr>
    <th>Name</th>
    <th>Description</th>
    <th>Qty</th>
    <th>Expiration</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php
$today = date("Y-m-d");

// SEARCH
if(isset($_GET['search']) && $_GET['search'] != ''){
    $s = $_GET['search'];
    $r = $conn->query("SELECT * FROM medicines 
                       WHERE name LIKE '%$s%' 
                       OR label LIKE '%$s%'");
} else {
    $r = $conn->query("SELECT * FROM medicines");
}

// CHECK DATA
if($r && $r->num_rows > 0){
    while($row = $r->fetch_assoc()){

        $exp = $row['expiration_date'];

        // STATUS (SHOW BOTH)
        $status = "";

        if(strtotime($exp) < strtotime($today)){
            $status .= "<span style='color:red;'>EXPIRED</span> ";
        }

        if($row['quantity'] <= 5){
            $status .= "<span style='color:orange;'>LOW STOCK</span>";
        }

        if($status == ""){
            $status = "<span style='color:green;'>OK</span>";
        }
       
    
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['label']}</td>
                <td>{$row['quantity']}</td>
                <td>{$exp}</td>
                <td>$status</td>
                <td>

                <a href='edit.php?id=".$row['id']."'>
                    <button>Edit</button>
                </a>

                <form method='POST' action='delete.php' style='display:inline;' 
                      onsubmit=\"return confirm('Delete this item?');\">
                    <input type='hidden' name='id' value='".$row['id']."'>
                    <button type='submit' name='delete'>Delete</button>
                </form>

                <a href='add_stock.php?id=".$row['id']."'>
                    <button>Add Stock</button>
                </a>

                </td>
             </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No data found</td></tr>";
}
?>

</table>
</div>