<?php
include 'header.php';
include 'db.php';

$id = intval($_GET['id']);
?>

<div class="container">
<h2>Employee Health Profile</h2>

<form method="POST" action="save_health.php">

<input type="hidden" name="employee_id" value="<?php echo $id; ?>">

Blood Type:<br>
<input type="text" name="blood_type"><br><br>

Height:<br>
<input type="text" name="height"><br><br>

Weight:<br>
<input type="text" name="weight"><br><br>

Allergies:<br>
<input type="text" name="allergies"><br><br>

Existing Conditions:<br>
<input type="text" name="conditions"><br><br>

<button type="submit" name="save">Save</button>

</form>
</div>