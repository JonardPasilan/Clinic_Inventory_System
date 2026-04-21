<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/db.php';

function h($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

$id = intval($_GET['id'] ?? 0);
$mode = strtolower(trim((string)($_GET['mode'] ?? 'add')));
if (!in_array($mode, ['add', 'edit', 'view'], true)) $mode = 'add';

$employee = null;
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM employees WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $employee = ($res && $res->num_rows > 0) ? $res->fetch_assoc() : null;
    $stmt->close();
}

$isView = $mode === 'view';
?>

<div class="container" style="max-width:900px;padding:0;">
    <div style="background:#1f4f87;color:#fff;padding:16px 18px;border-radius:12px 12px 0 0;display:flex;align-items:center;justify-content:space-between;">
        <div>
            <h2 style="margin:0;color:#fff;font-size:22px;">Medical Consultation Form</h2>
            <div style="color:rgba(255,255,255,0.85);font-size:13px;margin-top:4px;">
                <?php echo $mode === 'add' ? 'New Consultation' : ($isView ? 'View Consultation' : 'Edit Consultation'); ?>
            </div>
        </div>
        <a href="employees.php" style="font-size:26px;line-height:1;color:#fff;text-decoration:none;font-weight:900;">&times;</a>
    </div>

    <style>
        .consult-form {
            background: #fff;
            padding: 24px;
            border-radius: 0 0 12px 12px;
        }
        .form-section {
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        .form-section:last-child {
            border-bottom: none;
        }
        .section-title {
            font-weight: 700;
            color: #1f2d3d;
            margin: 0 0 16px 0;
            font-size: 15px;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 14px;
        }
        .form-row.two-col {
            grid-template-columns: repeat(2, 1fr);
        }
        .form-row.one-col {
            grid-template-columns: 1fr;
        }
        @media (max-width: 768px) {
            .form-row, .form-row.two-col {
                grid-template-columns: 1fr;
            }
        }
        .field {
            display: flex;
            flex-direction: column;
        }
        .field label {
            font-size: 13px;
            color: #34495e;
            margin-bottom: 6px;
            font-weight: 600;
        }
        .field input, .field select, .field textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e3e6ea;
            border-radius: 8px;
            background: #fff;
            font-size: 14px;
            box-sizing: border-box;
        }
        .field textarea {
            min-height: 100px;
            resize: vertical;
        }
        .field input[readonly], .field input[disabled] {
            background: #f6f7f8;
            color: #6b7280;
        }
        .radio-group {
            display: flex;
            gap: 20px;
            align-items: center;
            padding-top: 8px;
        }
        .radio-group label {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0;
            font-weight: 500;
        }
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
        }
        .btn {
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .btn-primary {
            background: #1f4f87;
            color: white;
        }
        .btn-secondary {
            background: #e7edf3;
            color: #2c3e50;
        }
    </style>

    <form method="POST" action="save_consultation.php" class="consult-form">
        <input type="hidden" name="employee_id" value="<?php echo h($id); ?>">

        <!-- Personal Information -->
        <div class="form-section">
            <div class="section-title">Personal Information</div>
            
            <div class="form-row one-col">
                <div class="field">
                    <label>Name (Family Name, First Name, Middle Name)</label>
                    <input type="text" name="full_name" value="<?php echo h($employee['name'] ?? ''); ?>" required <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
            </div>

            <div class="form-row">
                <div class="field">
                    <label>Sex</label>
                    <select name="sex" <?php echo $isView ? 'disabled' : ''; ?>>
                        <option value="">-- select --</option>
                        <option value="Male" <?php echo (($employee['sex'] ?? '') === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo (($employee['sex'] ?? '') === 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
                <div class="field">
                    <label>Age</label>
                    <input type="number" name="age" value="<?php echo h($employee['age'] ?? ''); ?>" <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
                <div class="field">
                    <label>Birthdate</label>
                    <input type="date" name="birthdate" value="<?php echo h($employee['birthday'] ?? ''); ?>" <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
            </div>

            <div class="form-row">
                <div class="field">
                    <label>Civil Status</label>
                    <select name="civil_status" <?php echo $isView ? 'disabled' : ''; ?>>
                        <option value="">-- select --</option>
                        <?php
                        $civilOptions = ['Single', 'Married', 'Separated', 'Widowed'];
                        $currentCivil = $employee['civil_status'] ?? '';
                        foreach ($civilOptions as $opt) {
                            $sel = ($currentCivil === $opt) ? 'selected' : '';
                            echo '<option value="' . h($opt) . '" ' . $sel . '>' . h($opt) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="field">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" value="<?php echo h($employee['contact'] ?? ''); ?>" <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
                <div class="field">
                    <label>Office/Department</label>
                    <input type="text" name="office" value="<?php echo h($employee['department'] ?? ''); ?>" <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
            </div>

            <div class="form-row one-col">
                <div class="field">
                    <label>Address</label>
                    <textarea name="address" rows="2" <?php echo $isView ? 'disabled' : ''; ?>><?php echo h($employee['address'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Consultation Details -->
        <div class="form-section">
            <div class="section-title">Consultation Details</div>
            
            <div class="form-row two-col">
                <div class="field">
                    <label>Date</label>
                    <input type="date" name="consultation_date" value="<?php echo date('Y-m-d'); ?>" required <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
                <div class="field">
                    <label>Time</label>
                    <input type="time" name="consultation_time" value="<?php echo date('H:i'); ?>" required <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
            </div>
        </div>

        <!-- Vital Signs -->
        <div class="form-section">
            <div class="section-title">Vital Signs</div>
            
            <div class="form-row">
                <div class="field">
                    <label>Blood Pressure (mmHg)</label>
                    <input type="text" name="blood_pressure" placeholder="120/80" <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
                <div class="field">
                    <label>Heart Rate (bpm)</label>
                    <input type="number" name="heart_rate" placeholder="72" <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
                <div class="field">
                    <label>Respiratory Rate (bpm)</label>
                    <input type="number" name="respiratory_rate" placeholder="16" <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
            </div>

            <div class="form-row">
                <div class="field">
                    <label>O2 Saturation (%)</label>
                    <input type="number" name="o2_saturation" placeholder="98" step="0.1" <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
                <div class="field">
                    <label>Temperature (°C)</label>
                    <input type="number" name="temperature" placeholder="36.5" step="0.1" <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
                <div class="field"></div>
            </div>

            <div class="form-row">
                <div class="field">
                    <label>Height (cm)</label>
                    <input type="number" name="height" placeholder="170" step="0.1" <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
                <div class="field">
                    <label>Weight (kg)</label>
                    <input type="number" name="weight" placeholder="65" step="0.1" <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
                <div class="field"></div>
            </div>
        </div>

        <!-- Clinical Information -->
        <div class="form-section">
            <div class="section-title">Clinical Information</div>
            
            <div class="form-row one-col">
                <div class="field">
                    <label>Chief Complaint</label>
                    <textarea name="chief_complaint" rows="3" placeholder="Patient's main concern or reason for visit" <?php echo $isView ? 'disabled' : ''; ?>></textarea>
                </div>
            </div>

            <div class="form-row one-col">
                <div class="field">
                    <label>Diagnosis</label>
                    <textarea name="diagnosis" rows="3" placeholder="Medical diagnosis or assessment" <?php echo $isView ? 'disabled' : ''; ?>></textarea>
                </div>
            </div>

            <div class="form-row one-col">
                <div class="field">
                    <label>Notes / Treatment Plan</label>
                    <textarea name="notes" rows="4" placeholder="Additional notes, prescriptions, or treatment recommendations" <?php echo $isView ? 'disabled' : ''; ?>></textarea>
                </div>
            </div>
        </div>

        <!-- Medical Certificate -->
        <div class="form-section">
            <div class="section-title">Medical Certificate</div>
            
            <div class="form-row two-col">
                <div class="field">
                    <label>Issue Medical Certificate?</label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="medical_certificate" value="Yes" <?php echo $isView ? 'disabled' : ''; ?>>
                            <span>Yes</span>
                        </label>
                        <label>
                            <input type="radio" name="medical_certificate" value="No" checked <?php echo $isView ? 'disabled' : ''; ?>>
                            <span>No</span>
                        </label>
                    </div>
                </div>
                <div class="field">
                    <label>Number of Copies</label>
                    <input type="number" name="certificate_copies" value="1" min="1" max="10" <?php echo $isView ? 'disabled' : ''; ?>>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <?php if (!$isView): ?>
        <div class="form-actions">
            <a href="employees.php" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Consultation</button>
        </div>
        <?php else: ?>
        <div class="form-actions">
            <a href="employees.php" class="btn btn-secondary">Close</a>
        </div>
        <?php endif; ?>
    </form>
</div>
