<?php include 'db.php';
      include 'header.php';?>

<style>
body{
    font-family: Arial;
    background: #f4f6f9;
}

.container{
    width: 400px;
    margin: 50px auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

h2{
    text-align: center;
    margin-bottom: 20px;
}

input{
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button{
    width: 100%;
    padding: 10px;
    background: #34495e;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover{
    background: #218838;
}

.success{
    background: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
    text-align: center;
}

.error{
    background: #f8d7da;
    color: #721c24;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
    text-align: center;
}
</style>


<div class="nav" style="background:#2c3e50; padding:10px; text-align:center;">
    <a href="add.php" style="color:white; margin:10px;">Add Medicine</a>
    <a href="dispense.php" style="color:white; margin:10px;">Dispense</a>
    <a href="logs.php" style="color:white; margin:10px;">Logs</a>
</div>

<div class="container">
    <h2>Add Medicine</h2>

    <form method="POST">
        Name:
        <input type="text" name="name" required>

        Description:
        <input type="text" name="Description" required>

        Quantity:
        <input type="number" name="quantity" required>

        Expiration:
        <input type="date" name="exp" required>

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
        echo "<div class='success'>✅ Added successfully!</div>";
    } else {
        echo "<div class='error'>❌ Error: ".$conn->error."</div>";
    }
}
?>
</div>