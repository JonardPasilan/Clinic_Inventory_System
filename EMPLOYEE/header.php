<!DOCTYPE html>
<html>
<head>
<style>

body {
    font-family: Arial, sans-serif;
    background: #f4f6f9;
    margin: 0;
}

/* When sidebar is open, shift in-flow content so it is not covered by the fixed drawer */
.topbar,
.container {
    transition: margin-left 0.3s ease;
}
body.sidebar-open .topbar,
body.sidebar-open .container {
    margin-left: 200px;
}

/* TOP BAR */
.topbar {
    background: #2c3e50;
    color: white;
    padding: 15px;
    font-size: 20px;
    position: relative;
    z-index: 1001;
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
    top: 0;
    left: 0;
    z-index: 1000;
    background: #34495e;
    overflow-x: hidden;
    transition: width 0.3s ease;
    padding-top: 60px;
    box-shadow: none;
}
.sidebar.open {
    box-shadow: 4px 0 18px rgba(0, 0, 0, 0.18);
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
    <a href="../MEDICINE_INVENTORY/index.php">Medicine Inventory</a>
    <a href="employees.php">Employees</a>
</div>

<script>
function openMenu() {
    var s = document.getElementById("sidebar");
    var open = (s.style.width === "200px");
    if (open) {
        s.style.width = "0";
        s.classList.remove("open");
        document.body.classList.remove("sidebar-open");
    } else {
        s.style.width = "200px";
        s.classList.add("open");
        document.body.classList.add("sidebar-open");
    }
}
</script>
