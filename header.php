<!DOCTYPE html>
<html>
<head>
<style>

body {
    font-family: Arial, sans-serif;
    background: #f4f6f9;
    margin: 0;
}

/* TOP BAR */
.topbar {
    background: #2c3e50;
    color: white;
    padding: 15px;
    font-size: 20px;
}

/* HAMBURGER */
.menu-btn {
    cursor: pointer;
    font-size: 25px;
    margin-right: 15px;
}

/* SIDEBAR */
.sidebar {
    height: 100%;
    width: 0;
    position: fixed;
    background: #34495e;
    overflow-x: hidden;
    transition: 0.3s;
    padding-top: 60px;
}

/* SIDEBAR LINKS */
.sidebar a {
    padding: 12px;
    display: block;
    color: white;
    text-decoration: none;
    font-weight: bold;
}

.sidebar a:hover {
    background: #1abc9c;
}

/* CONTENT */
.container {
    width: 90%;
    margin: 20px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
}

/* BUTTON */
button {
    padding: 8px 15px;
    border: none;
    background: #1abc9c;
    color: white;
    border-radius: 5px;
}

button:hover {
    background: #16a085;
}

</style>
</head>

<body>

<div class="topbar">
    <span class="menu-btn" onclick="openMenu()">☰</span>
    Clinic System
</div>

<div id="sidebar" class="sidebar">
    <a href="index.php">Medicine Inventory</a>
    <a href="employees.php">Employees</a>
</div>

<script>
function openMenu() {
    let s = document.getElementById("sidebar");
    if(s.style.width === "200px"){
        s.style.width = "0";
    } else {
        s.style.width = "200px";
    }
}
</script>