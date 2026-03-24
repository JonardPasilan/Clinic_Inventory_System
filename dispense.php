<?php include 'db.php'; 
     include 'header.php';?>
    

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
    </select><br><br>

    Quantity: <input type="number" name="qty" required><br><br>

    <button name="use">Use</button>
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
                      VALUES($id,$q,'Released   to patient')");

        echo "✅ Successfully Release!";
    } else {
        echo "❌ Not enough stock!";
    }
}
?>