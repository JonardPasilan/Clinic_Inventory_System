<?php include 'db.php'; 
     include 'header.php';?>

<style>
body{
    font-family: Arial;
    background: #f4f6f9;
}

.container{
    width: 420px;
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

select, input{
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
    background: #0056b3;
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

<div class="container">
    <h2>Dispense Medicine</h2>

    <form method="POST">
        Medicine:
        <select name="id" required>
            <?php
            $r = $conn->query("SELECT * FROM medicines");
            while($row = $r->fetch_assoc()){
                echo "<option value='{$row['id']}'>
                        {$row['name']} ({$row['label']}) - Stock: {$row['quantity']}
                      </option>";
            }
            ?>
        </select>

        Quantity:
        <input type="number" name="qty" required min="1">

        <button name="use">Dispense</button>
    </form>

<?php
if(isset($_POST['use'])){
    $id = $_POST['id'];
    $q = $_POST['qty'];

    $r = $conn->query("SELECT quantity FROM medicines WHERE id=$id");
    $row = $r->fetch_assoc();

    if($row['quantity'] >= $q){
        $conn->query("UPDATE medicines SET quantity = quantity - $q WHERE id=$id");
        $conn->query("INSERT INTO logs(medicine_id,quantity,action)
                      VALUES($id,$q,'Released to patient')");

        echo "<div class='success'>✅ Successfully Released!</div>";
    } else {
        echo "<div class='error'>❌ Not enough stock!</div>";
    }
}
?>
</div>