<?php 
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Navigation Bar */
        .nav {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            padding: 15px 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .nav a:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Header Card */
        .header-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }

        .header-card h2 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header-card p {
            color: #7f8c8d;
            font-size: 14px;
        }

        /* Search Section */
        .search-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-form input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-form input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
        }

        .search-form button {
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .search-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102,126,234,0.4);
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 10px;
            overflow-x: auto;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            color: #2c3e50;
        }

        tr:hover {
            background: #f8f9fa;
        }

        /* Status Badges */
        .status-expired {
            background: #fee;
            color: #e74c3c;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-lowstock {
            background: #ffeaa7;
            color: #f39c12;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-ok {
            background: #d5f4e6;
            color: #27ae60;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: #3498db;
            color: white;
        }

        .btn-edit:hover {
            background: #2980b9;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
        }

        .btn-delete:hover {
            background: #c0392b;
            transform: translateY(-1px);
        }

        .btn-stock {
            background: #2ecc71;
            color: white;
        }

        .btn-stock:hover {
            background: #27ae60;
            transform: translateY(-1px);
        }

        /* No Data Message */
        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-size: 16px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav a {
                margin: 0 5px;
                padding: 8px 12px;
                font-size: 14px;
            }

            th, td {
                padding: 8px 10px;
                font-size: 12px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                text-align: center;
            }
        }

        /* Stats Cards */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>

<div class="nav">
    <a href="index.php">🏠 Dashboard</a>
    <a href="add.php">➕ Add Medicine</a>
    <a href="dispense.php">💊 Dispense</a>
    <a href="logs.php">📋 Logs</a>
</div>

<div class="container">
    <div class="header-card">
        <h2>🏥 Clinic Medicine Inventory System</h2>
        <p>Manage and track all medications efficiently</p>
    </div>

    <?php
    include 'db.php';
    
    // Get statistics
    $total_meds = $conn->query("SELECT COUNT(*) as total FROM medicines")->fetch_assoc()['total'];
    $expired_meds = $conn->query("SELECT COUNT(*) as total FROM medicines WHERE expiration_date < CURDATE()")->fetch_assoc()['total'];
    $low_stock = $conn->query("SELECT COUNT(*) as total FROM medicines WHERE quantity <= 5")->fetch_assoc()['total'];
    ?>

    <div class="stats">
        <div class="stat-card">
            <h3>Total Medicines</h3>
            <div class="number"><?php echo $total_meds; ?></div>
        </div>
        <div class="stat-card">
            <h3>Expired Medicines</h3>
            <div class="number" style="color: #e74c3c;"><?php echo $expired_meds; ?></div>
        </div>
        <div class="stat-card">
            <h3>Low Stock Items</h3>
            <div class="number" style="color: #f39c12;"><?php echo $low_stock; ?></div>
        </div>
    </div>

    <div class="search-section">
        <form method="GET" class="search-form">
            <input type="text" name="search" 
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
                placeholder="🔍 Search medicine by name or description...">
            <button type="submit">Search</button>
            <?php if(isset($_GET['search']) && $_GET['search'] != ''): ?>
                <a href="index.php" style="padding: 12px 30px; background: #95a5a6; color: white; text-decoration: none; border-radius: 8px;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                 <tr>
                    <th>Medicine Name</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Expiration Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                 </tr>
            </thead>
            <tbody>
            <?php
            $today = date("Y-m-d");

            // SEARCH
            if(isset($_GET['search']) && $_GET['search'] != ''){
                $s = mysqli_real_escape_string($conn, $_GET['search']);
                $r = $conn->query("SELECT * FROM medicines 
                                   WHERE name LIKE '%$s%' 
                                   OR label LIKE '%$s%'
                                   ORDER BY expiration_date ASC");
            } else {
                $r = $conn->query("SELECT * FROM medicines ORDER BY expiration_date ASC");
            }

            // CHECK DATA
            if($r && $r->num_rows > 0){
                while($row = $r->fetch_assoc()){
                    $exp = $row['expiration_date'];
                    $quantity = $row['quantity'];
                    
                    // STATUS
                    $status = "";
                    $is_expired = strtotime($exp) < strtotime($today);
                    $is_low_stock = $quantity <= 5;

                    if($is_expired){
                        $status .= "<span class='status-expired'>⚠️ EXPIRED</span> ";
                    }
                    if($is_low_stock){
                        $status .= "<span class='status-lowstock'>📉 LOW STOCK</span>";
                    }
                    if(!$is_expired && !$is_low_stock){
                        $status = "<span class='status-ok'>✓ OK</span>";
                    }
                    
                    // Row color for expired items
                    $row_class = $is_expired ? "style='background:#fff5f5;'" : "";
                    
                    echo "<tr $row_class>
                        <td><strong>{$row['name']}</strong></td>
                        <td>{$row['label']}</td>
                        <td>
                            <strong style='color: " . ($quantity <= 5 ? '#e67e22' : '#2c3e50') . ";'>
                                {$quantity}
                            </strong>
                        </td>
                        <td>" . date('M d, Y', strtotime($exp)) . "</td>
                        <td>$status</td>
                        <td class='action-buttons'>
                            <a href='edit.php?id=".$row['id']."' class='btn btn-edit'>✏️ Edit</a>
                            <form method='POST' action='delete.php' style='display:inline;' 
                                  onsubmit=\"return confirm('⚠️ Are you sure you want to delete {$row['name']}?');\">
                                <input type='hidden' name='id' value='".$row['id']."'>
                                <button type='submit' name='delete' class='btn btn-delete'>🗑️ Delete</button>
                            </form>
                            <a href='add_stock.php?id=".$row['id']."' class='btn btn-stock'>➕ Add Stock</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='no-data'>📭 No medicines found in inventory</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>