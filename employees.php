<?php 
include 'header.php';
include 'db.php';
?>

<div class="container">
<h2>Employees</h2>

<a href="add_employee.php">
    <button>Add Employee</button>
</a>

<br><br>

<table border="1" cellpadding="10">
<tr>
    <th>Name</th>
    <th>Age</th>
    <th>Sex</th>
    <th>Department</th>
</tr>

<?php
$r = $conn->query("SELECT * FROM employees");

if($r && $r->num_rows > 0){
    while($row = $r->fetch_assoc()){
        echo "<tr>
            <td>{$row['name']}</td>
            <td>{$row['age']}</td>
            <td>{$row['sex']}</td>
            <td>{$row['department']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No data</td></tr>";
}
?>

</table>
</div>