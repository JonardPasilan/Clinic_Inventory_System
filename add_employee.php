<?php include 'header.php'; ?>

<div class="container">
<h2>Add Employee</h2>

<form method="POST" action="insert_employee.php">

    Name:<br>
    <input type="text" name="name" required><br><br>

    Age:<br>
    <input type="number" name="age"><br><br>

    Sex:<br>
    <select name="sex">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select><br><br>

    Birthday:<br>
    <input type="date" name="birthday"><br><br>

    Address:<br>
    <input type="text" name="address"><br><br>

    Contact:<br>
    <input type="text" name="contact"><br><br>

    Department:<br>
    <input type="text" name="department"><br><br>

    Civil Status:<br>
    <input type="text" name="civil_status"><br><br>

    <button type="submit" name="save">Save</button>

</form>
</div>