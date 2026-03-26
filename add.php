<?php 
include 'db.php';
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medicine - Clinic Management System</title>
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

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
        }

        /* Button */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
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

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                margin: 20px auto;
            }
            
            .form-card {
                padding: 25px;
            }
            
            .nav a {
                margin: 0 5px;
                padding: 8px 12px;
                font-size: 14px;
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
            <h2>Add New Medicine</h2>
            <p>Enter the details of the medicine to add to inventory</p>
        </div>

        <?php
        if(isset($_POST['add'])){
            $n = mysqli_real_escape_string($conn, $_POST['name']);
            $l = mysqli_real_escape_string($conn, $_POST['Description']);
            $q = mysqli_real_escape_string($conn, $_POST['quantity']);
            $e = mysqli_real_escape_string($conn, $_POST['exp']);
            
            // Validation
            $errors = [];
            if(empty($n)) $errors[] = "Medicine name is required";
            if(empty($l)) $errors[] = "Description is required";
            if(empty($q) || $q < 0) $errors[] = "Valid quantity is required";
            if(empty($e)) $errors[] = "Expiration date is required";
            
            if(empty($errors)){
                $sql = "INSERT INTO medicines(name, label, quantity, expiration_date)
                        VALUES('$n','$l','$q','$e')";
                
                if($conn->query($sql)){
                    echo "<div class='alert alert-success' id='alertMessage'>
                            <span>✅</span>
                            <span>Medicine added successfully! Stock: $q units</span>
                            <span class='close' onclick='this.parentElement.style.display=\"none\"'>&times;</span>
                          </div>";
                    
                    // Clear form after successful submission
                    echo "<script>
                            setTimeout(function() {
                                document.getElementById('addForm').reset();
                            }, 1000);
                          </script>";
                } else {
                    echo "<div class='alert alert-error' id='alertMessage'>
                            <span>❌</span>
                            <span>Error: ".$conn->error."</span>
                            <span class='close' onclick='this.parentElement.style.display=\"none\"'>&times;</span>
                          </div>";
                }
            } else {
                echo "<div class='alert alert-error' id='alertMessage'>
                        <span>⚠️</span>
                        <span>Please fix the following errors:</span>
                        <span class='close' onclick='this.parentElement.style.display=\"none\"'>&times;</span>
                      </div>
                      <ul style='color:#721c24; margin-top:-10px; margin-bottom:15px; margin-left:20px;'>";
                foreach($errors as $error){
                    echo "<li style='margin-bottom:5px;'>$error</li>";
                }
                echo "</ul>";
            }
        }
        ?>

        <form method="POST" id="addForm">
            <div class="form-group">
                <label>Medicine Name <span class="required">*</span></label>
                <input type="text" name="name" placeholder="e.g., Paracetamol, Amoxicillin, etc." required>
            </div>

            <div class="form-group">
                <label>Description <span class="required">*</span></label>
                <input type="text" name="Description" placeholder="e.g., 500mg tablet, syrup, etc." required>
            </div>

            <div class="form-group">
                <label>Quantity <span class="required">*</span></label>
                <input type="number" name="quantity" min="0" placeholder="Enter number of units" required>
            </div>

            <div class="form-group">
                <label>Expiration Date <span class="required">*</span></label>
                <input type="date" name="exp" required>
            </div>

            <button type="submit" name="add" class="btn-submit">
                ➕ Add Medicine to Inventory
            </button>
        </form>

        <div class="info-box">
            <p>💡 <strong>Tip:</strong> Make sure to enter accurate expiration dates to avoid dispensing expired medicines.</p>
            <p style="margin-top: 10px;">📊 <a href="index.php">View Inventory →</a></p>
        </div>
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
    
    // Date validation - prevent past dates for expiration (optional)
    document.querySelector('input[type="date"]').min = new Date().toISOString().split('T')[0];
</script>

</body>
</html>