<?php 
include 'db.php'; 
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispense Medicine - Clinic Management System</title>
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
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 15px;
            padding: 35px;
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

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h2 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .form-header .icon {
            font-size: 50px;
            margin-bottom: 10px;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 14px;
        }

        label .required {
            color: #e74c3c;
            margin-left: 3px;
        }

        select, input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: white;
        }

        select:focus, input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
        }

        select {
            cursor: pointer;
        }

        /* Stock Info */
        .stock-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stock-info .icon {
            font-size: 24px;
        }

        .stock-info .text {
            flex: 1;
        }

        .stock-info .stock-value {
            font-weight: bold;
            font-size: 18px;
            color: #2c3e50;
        }

        .stock-warning {
            border-left-color: #f39c12;
            background: #fff8e7;
        }

        .stock-danger {
            border-left-color: #e74c3c;
            background: #ffeaea;
        }

        /* Button */
        .btn-dispense {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            border: none;
            color: white;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-dispense:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231,76,60,0.4);
        }

        .btn-dispense:active {
            transform: translateY(0);
        }

        .btn-dispense:disabled {
            background: #95a5a6;
            cursor: not-allowed;
            transform: none;
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        .alert .close {
            margin-left: auto;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
        }

        .alert .close:hover {
            opacity: 0.7;
        }

        /* Info Box */
        .info-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        .info-box p {
            color: #7f8c8d;
            font-size: 13px;
        }

        .info-box a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }

        .info-box a:hover {
            text-decoration: underline;
        }

        /* Quantity Input Styling */
        input[type="number"] {
            appearance: textfield;
           
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav { padding: 6px 8px; gap: 4px 6px; }
            .nav a { font-size: 12px; padding: 5px 10px; }
            .container {
                margin: 20px auto;
            }
            
            .form-card {
                padding: 25px;
            }
            
            .form-header h2 {
                font-size: 24px;
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
    <div class="form-card">
        <div class="form-header">
            <div class="icon">💊</div>
            <h2>Dispense Medicine</h2>
            <p>Record medicine dispensed to patients</p>
        </div>

        <?php
        if(isset($_POST['use'])){
            $id = intval($_POST['id'] ?? 0);
            $q = intval($_POST['qty'] ?? 0);
            
            // Get medicine details (include expiration for server-side rule)
            $r = $id > 0 ? $conn->query("SELECT name, label, quantity, expiration_date FROM medicines WHERE id=$id") : false;
            $row = ($r && $r->num_rows > 0) ? $r->fetch_assoc() : null;

            if(!$row){
                echo "<div class='alert alert-error' id='alertMessage'>
                        <span>❌</span>
                        <div>
                            <strong>Medicine not found</strong><br>
                            The selected medicine is missing or was removed.
                        </div>
                        <span class='close' onclick='this.parentElement.style.display=\"none\"'>&times;</span>
                      </div>";
            } else {
            $medicine_name = $row['name'] . " (" . $row['label'] . ")";
            $current_stock = (int)$row['quantity'];
            $today = date('Y-m-d');
            $is_expired = !empty($row['expiration_date']) && strtotime($row['expiration_date']) < strtotime($today);

            if($is_expired){
                echo "<div class='alert alert-error' id='alertMessage'>
                        <span>❌</span>
                        <div>
                            <strong>Cannot dispense expired medicine</strong><br>
                            {$medicine_name} is past its expiration date.
                        </div>
                        <span class='close' onclick='this.parentElement.style.display=\"none\"'>&times;</span>
                      </div>";
            } elseif($current_stock >= $q && $q > 0){
                $conn->query("UPDATE medicines SET quantity = quantity - $q WHERE id=$id");
                $conn->query("INSERT INTO logs(medicine_id, quantity, action) VALUES($id, $q, 'Released to patient')");
                
                $new_stock = $current_stock - $q;
                $stock_status = "";
                
                if($new_stock <= 5){
                    $stock_status = "⚠️ Low stock alert! Only $new_stock units remaining.";
                } else {
                    $stock_status = "✅ Remaining stock: $new_stock units";
                }
                
                echo "<div class='alert alert-success' id='alertMessage'>
                        <span>✅</span>
                        <div>
                            <strong>Successfully Dispensed!</strong><br>
                            {$q} unit(s) of {$medicine_name} released to patient.<br>
                            {$stock_status}
                        </div>
                        <span class='close' onclick='this.parentElement.style.display=\"none\"'>&times;</span>
                      </div>";
                
                // Clear quantity field
                echo "<script>
                        setTimeout(function() {
                            document.getElementById('qty').value = '';
                            location.reload();
                        }, 3000);
                      </script>";
            } else {
                if($q <= 0){
                    echo "<div class='alert alert-error' id='alertMessage'>
                            <span>⚠️</span>
                            <div>
                                <strong>Invalid Quantity!</strong><br>
                                Please enter a valid quantity (at least 1 unit).
                            </div>
                            <span class='close' onclick='this.parentElement.style.display=\"none\"'>&times;</span>
                          </div>";
                } else {
                    echo "<div class='alert alert-error' id='alertMessage'>
                            <span>❌</span>
                            <div>
                                <strong>Insufficient Stock!</strong><br>
                                Only {$current_stock} unit(s) of {$medicine_name} available.<br>
                                Cannot dispense {$q} units.
                            </div>
                            <span class='close' onclick='this.parentElement.style.display=\"none\"'>&times;</span>
                          </div>";
                }
            }
            }
        }
        ?>

        <form method="POST" id="dispenseForm">
            <div class="form-group">
                <label>Select Medicine <span class="required">*</span></label>
                <select name="id" id="medicineSelect" required onchange="updateStockInfo()">
                    <option value="">-- Select a medicine --</option>
                    <?php
                    $r = $conn->query("SELECT * FROM medicines ORDER BY name ASC");
                    if($r && $r->num_rows > 0){
                        while($row = $r->fetch_assoc()){
                            $stock_class = "";
                            $stock_warning = "";
                            if($row['quantity'] <= 0){
                                $stock_class = "style='color:#e74c3c;'";
                                $stock_warning = " (OUT OF STOCK)";
                            } elseif($row['quantity'] <= 5){
                                $stock_class = "style='color:#f39c12;'";
                                $stock_warning = " (LOW STOCK)";
                            }
                            $opt_name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                            $opt_label = htmlspecialchars($row['label'], ENT_QUOTES, 'UTF-8');
                            echo "<option value='{$row['id']}' data-stock='{$row['quantity']}' data-name='{$opt_name}' data-label='{$opt_label}' {$stock_class}>
                                    {$opt_name} ({$opt_label}) - Stock: {$row['quantity']} units{$stock_warning}
                                  </option>";
                        }
                    } else {
                        echo "<option value='' disabled>No medicines available</option>";
                    }
                    ?>
                </select>
            </div>

            <div id="stockInfo" class="stock-info" style="display: none;">
                <div class="icon">📦</div>
                <div class="text">
                    <strong>Current Stock:</strong>
                    <span id="stockValue" class="stock-value">0</span> units
                </div>
            </div>

            <div class="form-group">
                <label>Quantity to Dispense <span class="required">*</span></label>
                <input type="number" name="qty" id="qty" required min="1" placeholder="Enter number of units" oninput="validateQuantity()">
                <small id="qtyWarning" style="color:#e74c3c; display:none; margin-top:5px;"></small>
            </div>

            <button type="submit" name="use" id="dispenseBtn" class="btn-dispense">
                💊 Dispense Medicine
            </button>
        </form>

        <div class="info-box">
            <p>📋 <strong>Important:</strong> Always verify the medicine and dosage before dispensing to patients.</p>
            <p style="margin-top: 10px;">📊 <a href="logs.php">View Dispensing Logs →</a></p>
            <p style="margin-top: 5px;">🏥 <a href="index.php">Back to Inventory →</a></p>
        </div>
    </div>
</div>

<script>
    // Update stock info when medicine is selected
    function updateStockInfo() {
        const select = document.getElementById('medicineSelect');
        const selectedOption = select.options[select.selectedIndex];
        const stockInfo = document.getElementById('stockInfo');
        const stockValue = document.getElementById('stockValue');
        const qtyInput = document.getElementById('qty');
        const dispenseBtn = document.getElementById('dispenseBtn');
        
        if(select.value && selectedOption.dataset.stock !== undefined) {
            const stock = parseInt(selectedOption.dataset.stock);
            stockValue.textContent = stock;
            stockInfo.style.display = 'flex';
            
            // Add styling based on stock level
            if(stock <= 0) {
                stockInfo.className = 'stock-info stock-danger';
                stockValue.style.color = '#e74c3c';
                dispenseBtn.disabled = true;
                qtyInput.disabled = true;
                qtyInput.placeholder = 'Medicine out of stock';
            } else if(stock <= 5) {
                stockInfo.className = 'stock-info stock-warning';
                stockValue.style.color = '#f39c12';
                dispenseBtn.disabled = false;
                qtyInput.disabled = false;
            } else {
                stockInfo.className = 'stock-info';
                stockValue.style.color = '#2c3e50';
                dispenseBtn.disabled = false;
                qtyInput.disabled = false;
            }
            
            // Set max attribute for quantity input
            if(stock > 0) {
                qtyInput.max = stock;
                qtyInput.placeholder = `Enter quantity (max ${stock})`;
            }
            
            validateQuantity();
        } else {
            stockInfo.style.display = 'none';
            dispenseBtn.disabled = true;
            qtyInput.disabled = true;
            qtyInput.placeholder = 'Select a medicine first';
        }
    }
    
    // Validate quantity input
    function validateQuantity() {
        const select = document.getElementById('medicineSelect');
        const qtyInput = document.getElementById('qty');
        const qtyWarning = document.getElementById('qtyWarning');
        const dispenseBtn = document.getElementById('dispenseBtn');
        
        if(select.value && qtyInput.value) {
            const selectedOption = select.options[select.selectedIndex];
            const stock = parseInt(selectedOption.dataset.stock);
            const qty = parseInt(qtyInput.value);
            
            if(qty > stock) {
                qtyWarning.textContent = `⚠️ Not enough stock! Only ${stock} units available.`;
                qtyWarning.style.display = 'block';
                dispenseBtn.disabled = true;
            } else if(qty <= 0) {
                qtyWarning.textContent = `⚠️ Please enter a valid quantity (minimum 1).`;
                qtyWarning.style.display = 'block';
                dispenseBtn.disabled = true;
            } else {
                qtyWarning.style.display = 'none';
                dispenseBtn.disabled = false;
            }
        } else {
            qtyWarning.style.display = 'none';
        }
    }
    
    // Auto-hide alert messages after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 5000);
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateStockInfo();
    });
</script>

</body>
</html>