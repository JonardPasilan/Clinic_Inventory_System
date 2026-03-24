<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f9;
    margin: 0;
}

/* NAVBAR */
.nav {
    background: #2c3e50;
    padding: 15px;
    text-align: center;
}

.nav a {
    color: white;
    margin: 10px;
    text-decoration: none;
    font-weight: bold;
}

.nav a:hover {
    color: #1abc9c;
}

/* CONTAINER */
.container {
    width: 90%;
    margin: 20px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

th {
    background: #34495e;
    color: white;
    padding: 10px;
}

td {
    padding: 10px;
    text-align: center;
}

tr:nth-child(even) {
    background: #f2f2f2;
}

/* BUTTON */
button {
    padding: 8px 15px;
    border: none;
    background: #1abc9c;
    color: white;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: #16a085;
}

/* INPUT */
input, select {
    padding: 8px;
    margin: 5px;
    width: 200px;
}
</style>

<div class="nav">
    <a href="index.php">Inventory</a>
    <a href="add.php">Add Medicine</a>
    <a href="dispense.php">Dispense Medicine</a>
    <a href="logs.php">Logs</a>
</div>