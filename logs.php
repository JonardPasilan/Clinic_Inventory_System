<?php 
include 'db.php';
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispense Logs - Clinic Management System</title>
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

        .nav {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            padding: 8px 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 999;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 6px 10px;
        }

        .nav a {
            color: white;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 6px;
            transition: background 0.2s ease, transform 0.2s ease;
            display: inline-block;
            white-space: nowrap;
        }

        .nav a:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-1px);
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Header Card */
        .header-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-content {
            text-align: center;
        }

        .header-content .icon {
            font-size: 50px;
            margin-bottom: 10px;
        }

        .header-content h2 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header-content p {
            color: #7f8c8d;
            font-size: 14px;
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
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
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

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .filter-form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1;
            min-width: 150px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 13px;
        }

        .filter-group select, 
        .filter-group input {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-filter {
            padding: 10px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102,126,234,0.4);
        }

        .btn-reset {
            padding: 10px 25px;
            background: #95a5a6;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
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

        /* Medicine Name Styling */
        .medicine-name {
            font-weight: 600;
            color: #2c3e50;
        }

        /* Quantity Badge */
        .quantity-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
        }

        .quantity-normal {
            background: #e3f2fd;
            color: #1976d2;
        }

        .quantity-low {
            background: #fff3e0;
            color: #f57c00;
        }

        .quantity-out {
            background: #ffebee;
            color: #c62828;
        }

        /* Action Badge */
        .action-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            background: #e8f5e9;
            color: #2e7d32;
            font-size: 12px;
            font-weight: 600;
        }

        /* Date Styling */
        .date-styling {
            font-family: monospace;
            font-size: 13px;
            color: #7f8c8d;
        }

        /* No Data Message */
        .no-data {
            text-align: center;
            padding: 60px;
            color: #7f8c8d;
        }

        .no-data .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        /* Export Button */
        .export-section {
            margin-bottom: 20px;
            text-align: right;
        }

        .btn-export {
            padding: 10px 20px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-export:hover {
            background: #229954;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav { padding: 6px 8px; gap: 4px 6px; }
            .nav a { font-size: 12px; padding: 5px 10px; }
            .container {
                margin: 20px auto;
            }
            
            .filter-group {
                min-width: 100%;
            }
            
            .filter-buttons {
                width: 100%;
            }
            
            .btn-filter, .btn-reset {
                flex: 1;
            }
            
            th, td {
                padding: 10px;
                font-size: 12px;
            }
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
        <div class="header-content">
            <div class="icon">📋</div>
            <h2>Dispense History Logs</h2>
            <p>Complete record of all medicines dispensed to patients</p>
        </div>
    </div>

    <?php
    // Get statistics
    $total_dispensed = $conn->query("SELECT SUM(quantity) as total FROM logs")->fetch_assoc()['total'];
    $total_transactions = $conn->query("SELECT COUNT(*) as total FROM logs")->fetch_assoc()['total'];
    $today_dispensed = $conn->query("SELECT SUM(quantity) as total FROM logs WHERE DATE(date) = CURDATE()")->fetch_assoc()['total'];
    
    // Get unique medicines for filter
    $medicines_list = $conn->query("SELECT DISTINCT medicines.id, medicines.name FROM logs JOIN medicines ON logs.medicine_id = medicines.id ORDER BY medicines.name");
    ?>

    <div class="stats">
        <div class="stat-card">
            <h3>Total Dispensed Items</h3>
            <div class="number"><?php echo $total_dispensed ? $total_dispensed : 0; ?></div>
        </div>
        <div class="stat-card">
            <h3>Total Transactions</h3>
            <div class="number"><?php echo $total_transactions; ?></div>
        </div>
        <div class="stat-card">
            <h3>Today's Dispensed</h3>
            <div class="number" style="color: #27ae60;"><?php echo $today_dispensed ? $today_dispensed : 0; ?></div>
        </div>
    </div>

    <div class="filter-section">
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label>🔍 Filter by Medicine</label>
                <select name="medicine_id">
                    <option value="">All Medicines</option>
                    <?php 
                    if($medicines_list && $medicines_list->num_rows > 0){
                        while($med = $medicines_list->fetch_assoc()){
                            $selected = (isset($_GET['medicine_id']) && $_GET['medicine_id'] == $med['id']) ? 'selected' : '';
                            echo "<option value='{$med['id']}' $selected>{$med['name']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label>📅 From Date</label>
                <input type="date" name="date_from" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : ''; ?>">
            </div>
            
            <div class="filter-group">
                <label>📅 To Date</label>
                <input type="date" name="date_to" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : ''; ?>">
            </div>
            
            <div class="filter-buttons">
                <button type="submit" name="filter" class="btn-filter">Apply Filter</button>
                <a href="logs.php" class="btn-reset" style="text-decoration: none; display: inline-block; text-align: center;">Reset</a>
            </div>
        </form>
    </div>

    <div class="export-section">
        <button onclick="exportToCSV()" class="btn-export">📊 Export to CSV</button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Medicine Name</th>
                    <th>Quantity</th>
                    <th>Action</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Build query with filters
            $query = "SELECT logs.*, medicines.name, medicines.label, DATE_FORMAT(logs.date, '%M %d, %Y %h:%i %p') as formatted_date 
                      FROM logs 
                      JOIN medicines ON logs.medicine_id = medicines.id 
                      WHERE 1=1";
            
            // Apply filters when parameters are present (works with bookmarks / shared URLs, not only "Apply Filter")
            if(isset($_GET['medicine_id']) && $_GET['medicine_id'] != ''){
                $med_id = mysqli_real_escape_string($conn, $_GET['medicine_id']);
                $query .= " AND logs.medicine_id = '$med_id'";
            }

            if(isset($_GET['date_from']) && $_GET['date_from'] != ''){
                $date_from = mysqli_real_escape_string($conn, $_GET['date_from']);
                $query .= " AND DATE(logs.date) >= '$date_from'";
            }

            if(isset($_GET['date_to']) && $_GET['date_to'] != ''){
                $date_to = mysqli_real_escape_string($conn, $_GET['date_to']);
                $query .= " AND DATE(logs.date) <= '$date_to'";
            }
            
            $query .= " ORDER BY logs.id DESC";
            
            $r = $conn->query($query);
            
            if($r && $r->num_rows > 0){
                while($row = $r->fetch_assoc()){
                    $quantity = $row['quantity'];
                    
                    // Determine quantity badge class
                    $quantity_class = 'quantity-normal';
                    if($quantity <= 5){
                        $quantity_class = 'quantity-low';
                    }
                    if($quantity <= 0){
                        $quantity_class = 'quantity-out';
                    }

                    $log_name = htmlspecialchars((string)$row['name'], ENT_QUOTES, 'UTF-8');
                    $log_label = htmlspecialchars((string)$row['label'], ENT_QUOTES, 'UTF-8');
                    $log_action = htmlspecialchars((string)$row['action'], ENT_QUOTES, 'UTF-8');
                    $log_when = htmlspecialchars((string)$row['formatted_date'], ENT_QUOTES, 'UTF-8');
                    
                    echo "<tr>
                        <td>
                            <div class='medicine-name'>{$log_name}</div>
                            <small style='color:#7f8c8d; font-size:11px;'>{$log_label}</small>
                        </td>
                        <td>
                            <span class='quantity-badge {$quantity_class}'>
                                {$quantity} unit(s)
                            </span>
                        </td>
                        <td>
                            <span class='action-badge'>
                                💊 {$log_action}
                            </span>
                        </td>
                        <td class='date-styling'>
                            📅 {$log_when}
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr>
                    <td colspan='4' class='no-data'>
                        <div class='icon'>📭</div>
                        <div>No dispense records found</div>
                        <small style='margin-top:10px; display:block;'>Try adjusting your filters or add new dispense records</small>
                    </td>
                </tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Export table data to CSV
    function exportToCSV() {
        const table = document.querySelector('table');
        let csv = [];
        
        // Get headers
        const headers = [];
        const ths = table.querySelectorAll('thead th');
        ths.forEach(th => {
            headers.push(th.innerText);
        });
        csv.push(headers.join(','));
        
        // Get data rows
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const rowData = [];
            const cells = row.querySelectorAll('td');
            cells.forEach(cell => {
                // Clean up the cell text (remove HTML tags, multiple spaces)
                let text = cell.innerText.replace(/\n/g, ' ').replace(/\s+/g, ' ').trim();
                // Wrap in quotes if contains comma
                if(text.includes(',')) {
                    text = '"' + text + '"';
                }
                rowData.push(text);
            });
            csv.push(rowData.join(','));
        });
        
        // Download CSV file
        const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `dispense_logs_${new Date().toISOString().slice(0,19).replace(/:/g, '-')}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }
    
    // Optional: Add a print button functionality
    function printLogs() {
        window.print();
    }
</script>

</body>
</html>