<?php include 'db.php';
      include 'header.php'; ?>

<div class="nav" style="background:#2c3e50; padding:10px; text-align:center;">
    <a href="add.php" style="color:white; margin:10px;">Add Medicine</a>
    <a href="dispense.php" style="color:white; margin:10px;">Dispense</a>
    <a href="logs.php" style="color:white; margin:10px;">Logs</a>
</div>
<h2>Release History</h2>

<table border="1">
<tr>
    <th>Medicine</th>
    <th>Quantity</th>
    <th>Action</th>
    <th>Date</th>
</tr>


<?php
$r = $conn->query("
    SELECT logs.*, medicines.name, DATE(logs.date) as date_only
    FROM logs 
    JOIN medicines ON logs.medicine_id = medicines.id
    ORDER BY logs.id DESC
");

if($r && $r->num_rows > 0){
    while($row = $r->fetch_assoc()){
        echo "<tr>
            <td>{$row['name']}</td>
            <td>{$row['quantity']}</td>
            <td>{$row['action']}</td>
            <td>{$row['date_only']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No records</td></tr>";
}
?>

</table>