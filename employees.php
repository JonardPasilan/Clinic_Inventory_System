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

<!-- SEARCH -->
<form method="GET">
    <input type="text" name="search" 
    placeholder="Search employee..."
    value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
    
    <button type="submit">Search</button>
</form>

<br>

<table border="1" cellpadding="10">
<tr>
    <th>Name</th>
    <th>Age</th>
    <th>Sex</th>
    <th>Department</th>
    <th>Action</th>
</tr>

<?php

// SEARCH LOGIC
if(isset($_GET['search']) && $_GET['search'] != ''){
    $s = $_GET['search'];

    $r = $conn->query("SELECT * FROM employees 
        WHERE name LIKE '%$s%' 
        OR department LIKE '%$s%'");
} else {
    $r = $conn->query("SELECT * FROM employees");
}

if($r && $r->num_rows > 0){
    while($row = $r->fetch_assoc()){
        echo "<tr>
            <td>{$row['name']}</td>
            <td>{$row['age']}</td>
            <td>{$row['sex']}</td>
            <td>{$row['department']}</td>
            <td>

                <a href='edit_employee.php?id=".$row['id']."'>
                    <button>Edit</button>
                </a>

                <button type='button' onclick='confirmDelete(".$row['id'].")'>
                    Delete
                </button>

            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No data</td></tr>";
}
?>

</table>
</div>

<!-- HIDDEN DELETE FORM -->
<form id="deleteForm" method="POST" action="delete_employee.php">
    <input type="hidden" name="id" id="deleteId">
    <input type="hidden" name="delete" value="1">
</form>

<!-- CUSTOM MODAL -->
<div id="deleteModal" style="
display:none;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.5);
justify-content:center;
align-items:center;
">

    <div style="
    background:white;
    padding:20px;
    border-radius:10px;
    text-align:center;
    ">

        <h3>Delete Employee</h3>
        <p>Are you sure you want to delete this employee?</p>

        <button onclick="deleteNow()">Delete</button>
        <button onclick="closeModal()">Cancel</button>

    </div>

</div>

<script>
let deleteId = 0;

function confirmDelete(id){
    deleteId = id;
    document.getElementById("deleteModal").style.display = "flex";
}

function closeModal(){
    document.getElementById("deleteModal").style.display = "none";
}

function deleteNow(){
    document.getElementById("deleteId").value = deleteId;
    document.getElementById("deleteForm").submit();
}
</script>