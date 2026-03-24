<?php 
include 'header.php';
include 'db.php';
?>

<div class="container">
<h2>Medicine Inventory</h2>

<form method="GET">
    <input type="text" name="search" placeholder="Search medicine...">
    <button type="submit">Search</button>
</form>

<br>
<table>
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





if(isset($_GET['search'])){
    $s = $_GET['search'];
    $r = $conn->query("SELECT * FROM medicines 
                       WHERE name LIKE '%$s%' 
                       OR label LIKE '%$s%'");
} else {
    $r = $conn->query("SELECT * FROM medicines");
}





if($r && $r->num_rows > 0){
    while($row = $r->fetch_assoc()){
        $exp = $row['expiration_date'];

        if($exp < $today){
            $status = "<span style='color:red;'>EXPIRED</span>";
        } else {
            $status = "<span style='color:green;'>OK</span>";
        }

        echo "<tr>
            <td>{$row['name']}</td>
            <td>{$row['label']}</td>
            <td>{$row['quantity']}</td>
            <td>{$exp}</td>
            <td>$status</td>
            <td>
                <a href='edit.php?id={$row['id']}'>
                    <button>Edit</button>
                </a>

                <form method='POST' action='delete.php' style='display:inline;' 
                      onsubmit=\"return confirm('Delete this item?');\">
                    <input type='hidden' name='id' value='{$row['id']}'>
                    <button type='submit' name='delete'>Delete</button>
                </form>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No data found</td></tr>";
}
?>

</table>
</div>