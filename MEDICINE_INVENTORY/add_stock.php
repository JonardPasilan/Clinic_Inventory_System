<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/header.php';

// Check if ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

$r = $conn->query("SELECT * FROM medicines WHERE id=$id");

if($r && $r->num_rows > 0){
    $row = $r->fetch_assoc();
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stock - Clinic Management System</title>
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

        /* Medicine Info Card */
        .medicine-info {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #e0e0e0;
        }

        .medicine-name {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }

        .medicine-name h3 {
            color: #2c3e50;
            font-size: 22px;
            margin-bottom: 5px;
        }

        .medicine-name .label {
            color: #7f8c8d;
            font-size: 14px;
        }

        .stock-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }

        .stock-item {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 8px;
        }

        .stock-item .label {
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 5px;
        }

        .stock-item .value {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }

        .stock-item .unit {
            font-size: 12px;
            color: #7f8c8d;
        }

        .stock-warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            border-radius: 8px;
            margin-top: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 25px;
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

        input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
        }

        input[type="number"] {
            appearance: textfield;
            -moz-appearance: textfield;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 0.5;
        }

        /* New Stock Preview */
        .stock-preview {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border: 2px dashed #e0e0e0;
            text-align: center;
        }

        .stock-preview h4 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .preview-value {
            font-size: 28px;
            font-weight: bold;
            color: #27ae60;
        }

        /* Buttons */
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }

        .btn-add {
            flex: 1;
            padding: 14px;
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            border: none;
            color: white;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39,174,96,0.4);
        }

        .btn-cancel {
            flex: 1;
            padding: 14px;
            background: #95a5a6;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
        }

        /* Quick Add Buttons */
        .quick-add {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .quick-btn {
            flex: 1;
            padding: 8px;
            background: #ecf0f1;
            border: 1px solid #d0d0d0;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .quick-btn:hover {
            background: #3498db;
            color: white;
            border-color: #3498db;
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

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            border-left: 4px solid #17a2b8;
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
            
            .button-group {
                flex-direction: column;
            }
            
            .stock-details {
                grid-template-columns: 1fr;
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
            <div class="icon">📦➕</div>
            <h2>Add Stock</h2>
            <p>Increase inventory quantity for this medicine</p>
        </div>

        <?php
        // Check for update status from URL
        if(isset($_GET['updated']) && $_GET['updated'] == 'success') {
            echo "<div class='alert alert-info' id='alertMessage'>
                    <span>✅</span>
                    <span>Stock added successfully!</span>
                    <span class='close' onclick='this.parentElement.style.display=\"none\"'>&times;</span>
                  </div>";
        }
        
        $current_qty = $row['quantity'];
        $expiry_date = $row['expiration_date'];
        $today = date("Y-m-d");
        
        // Check if medicine is expired
        if(strtotime($expiry_date) < strtotime($today)) {
            echo "<div class='alert alert-info' style='background:#ffeaea; border-left-color:#e74c3c; color:#c0392b;'>
                    <span>⚠️</span>
                    <span><strong>Warning:</strong> This medicine is already expired! Consider replacing it instead of adding stock.</span>
                  </div>";
        }
        ?>

        <div class="medicine-info">
            <div class="medicine-name">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <div class="label"><?php echo htmlspecialchars($row['label']); ?></div>
            </div>
            <div class="stock-details">
                <div class="stock-item">
                    <div class="label">Current Stock</div>
                    <div class="value" style="color: <?php echo ($current_qty <= 5) ? '#e67e22' : '#2c3e50'; ?>;">
                        <?php echo $current_qty; ?>
                    </div>
                    <div class="unit">units</div>
                </div>
                <div class="stock-item">
                    <div class="label">Expiration Date</div>
                    <div class="value" style="font-size: 18px; color: <?php echo (strtotime($expiry_date) < strtotime($today)) ? '#e74c3c' : '#2c3e50'; ?>;">
                        <?php echo date('M d, Y', strtotime($expiry_date)); ?>
                    </div>
                </div>
            </div>
            <?php if($current_qty <= 5): ?>
            <div class="stock-warning">
                <span>⚠️</span>
                <span><strong>Low Stock Alert!</strong> Current stock is low. Consider adding more units.</span>
            </div>
            <?php endif; ?>
        </div>

        <form method="POST" action="update_stock.php" id="addStockForm">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div class="form-group">
                <label>Add Quantity <span class="required">*</span></label>
                <input type="number" name="add_qty" id="add_qty" required min="1" placeholder="Enter quantity to add">
                <small style="color:#7f8c8d; display: block; margin-top: 5px;">Enter the number of units to add to current stock</small>
            </div>

            <div class="quick-add">
                <button type="button" class="quick-btn" onclick="setQuantity(10)">+10</button>
                <button type="button" class="quick-btn" onclick="setQuantity(25)">+25</button>
                <button type="button" class="quick-btn" onclick="setQuantity(50)">+50</button>
                <button type="button" class="quick-btn" onclick="setQuantity(100)">+100</button>
                <button type="button" class="quick-btn" onclick="setQuantity(500)">+500</button>
            </div>

            <div class="stock-preview" id="stockPreview" style="display: none;">
                <h4>📊 New Stock After Addition</h4>
                <div class="preview-value" id="previewValue">0</div>
                <div class="unit">units</div>
            </div>

            <div class="button-group">
                <button type="submit" name="add" class="btn-add">
                    ➕ Add Stock
                </button>
                <a href="index.php" class="btn-cancel">
                    ❌ Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-hide alert messages after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 5000);
    
    // Get elements
    const addQtyInput = document.getElementById('add_qty');
    const stockPreview = document.getElementById('stockPreview');
    const previewValue = document.getElementById('previewValue');
    const currentQty = <?php echo $current_qty; ?>;
    
    // Update preview when quantity changes
    function updatePreview() {
        let addQty = parseInt(addQtyInput.value);
        if(!isNaN(addQty) && addQty > 0) {
            let newStock = currentQty + addQty;
            previewValue.textContent = newStock;
            stockPreview.style.display = 'block';
            
            // Change color based on new stock level
            if(newStock <= 5) {
                previewValue.style.color = '#e67e22';
            } else {
                previewValue.style.color = '#27ae60';
            }
        } else {
            stockPreview.style.display = 'none';
        }
    }
    
    // Set quantity with quick buttons
    function setQuantity(qty) {
        addQtyInput.value = qty;
        updatePreview();
    }
    
    // Add event listener
    addQtyInput.addEventListener('input', updatePreview);
    
    // Validate form before submit
    const form = document.getElementById('addStockForm');
    form.addEventListener('submit', function(e) {
        let addQty = parseInt(addQtyInput.value);
        if(isNaN(addQty) || addQty <= 0) {
            e.preventDefault();
            alert('Please enter a valid quantity to add (minimum 1 unit).');
            addQtyInput.focus();
        }
    });
    
    // Warn before leaving if changes were made
    let formChanged = false;
    addQtyInput.addEventListener('change', function() {
        formChanged = true;
    });
    
    window.addEventListener('beforeunload', function(e) {
        if(formChanged && addQtyInput.value && addQtyInput.value > 0) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            return e.returnValue;
        }
    });
    
    form.addEventListener('submit', function() {
        formChanged = false;
    });
    
    // Initialize preview
    updatePreview();
</script>

</body>
</html>