<?php include 'db.php';
      include 'header.php';?>


<h2>Add Medicine</h2>

<form method="POST">
    Name: <input type="text" name="name" required><br><br>
    Description: <input type="text" name="Description" required><br><br>
    Quantity: <input type="number" name="quantity" required><br><br>
    Expiration: <input type="date" name="exp" required><br><br>

    <button name="add">Add</button>
</form>

<?php
if(isset($_POST['add'])){
    $n = $_POST['name'];
    $l = $_POST['Description'];
    $q = $_POST['quantity'];
    $e = $_POST['exp'];

    $sql = "INSERT INTO medicines(name,label,quantity,expiration_date)
            VALUES('$n','$l','$q','$e')";

    if($conn->query($sql)){
        echo "✅ Added successfully!";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>