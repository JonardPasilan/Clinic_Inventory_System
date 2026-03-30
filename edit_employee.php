<?php
include 'header.php';
include 'db.php';

$id = intval($_GET['id']);

$r = $conn->query("SELECT * FROM employees WHERE id=$id");

if($r && $r->num_rows > 0){
    $row = $r->fetch_assoc();
} else {
    echo "No data found";
    exit();
}
?>

<div class="container">
<h2>Edit Employee</h2>

<form method="POST" action="update_employee.php">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

Name:<br>
<input type="text" name="name" value="<?php echo $row['name']; ?>"><br><br>

Age:<br>
<input type="number" name="age" value="<?php echo $row['age']; ?>"><br><br>

Sex:<br>
<select name="sex">
    <option <?php if($row['sex']=="Male") echo "selected"; ?>>Male</option>
    <option <?php if($row['sex']=="Female") echo "selected"; ?>>Female</option>
</select><br><br>

Birthday:<br>
<input type="date" name="birthday" value="<?php echo $row['birthday']; ?>"><br><br>

Address:<br>
<input type="text" name="address" value="<?php echo $row['address']; ?>"><br><br>

Contact:<br>
<input type="text" name="contact" value="<?php echo $row['contact']; ?>"><br><br>

Department:<br>
<input type="text" name="department" value="<?php echo $row['department']; ?>"><br><br>

Civil Status:<br>
<input type="text" name="civil_status" value="<?php echo $row['civil_status']; ?>"><br><br>

<button type="submit" name="update">Update</button>

</form>
</div>