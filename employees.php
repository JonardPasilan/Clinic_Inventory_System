<?php 
include 'header.php';
include 'db.php';

// Ensure the JSON-based health profile table exists.
$conn->query("
    CREATE TABLE IF NOT EXISTS employee_health_profiles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id INT NOT NULL UNIQUE,
        class_type VARCHAR(50) NULL,
        profile_data LONGTEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX (employee_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

function h($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function arr_get($arr, array $keys, $default = '') {
    $cur = $arr;
    foreach ($keys as $k) {
        if (!is_array($cur) || !array_key_exists($k, $cur)) return $default;
        $cur = $cur[$k];
    }
    return $cur ?? $default;
}

function post_search_like(string $s): string {
    // Basic cleanup for LIKE queries.
    return trim($s);
}

$search = isset($_GET['search']) ? post_search_like((string)$_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Stats
$totalEmployees = (int)($conn->query("SELECT COUNT(*) AS c FROM employees")->fetch_assoc()['c'] ?? 0);
$classCounts = ['Class A' => 0, 'Class B' => 0, 'Class C' => 0];
$classRes = $conn->query("SELECT class_type, COUNT(*) AS c FROM employee_health_profiles GROUP BY class_type");
if ($classRes) {
    while ($row = $classRes->fetch_assoc()) {
        $ct = (string)($row['class_type'] ?? '');
        if (isset($classCounts[$ct])) $classCounts[$ct] = (int)$row['c'];
    }
}

// Query employees (search + pagination)
if ($search !== '') {
    $like = '%' . $conn->real_escape_string($search) . '%';
    $countRes = $conn->query("SELECT COUNT(*) AS c FROM employees WHERE name LIKE '$like' OR department LIKE '$like' OR contact LIKE '$like'");
    $totalFiltered = (int)($countRes->fetch_assoc()['c'] ?? 0);
    $totalPages = max(1, (int)ceil($totalFiltered / $limit));

    $r = $conn->query("SELECT * FROM employees WHERE name LIKE '$like' OR department LIKE '$like' OR contact LIKE '$like' ORDER BY id DESC LIMIT $limit OFFSET $offset");
} else {
    $totalFiltered = $totalEmployees;
    $totalPages = max(1, (int)ceil($totalFiltered / $limit));
    $r = $conn->query("SELECT * FROM employees ORDER BY id DESC LIMIT $limit OFFSET $offset");
}
?>

<div class="container employees-page">
    <style>
        .page-title {
            display:flex;
            align-items:flex-end;
            justify-content:space-between;
            gap:16px;
            margin-bottom:16px;
        }
        .page-title h2 {
            margin: 0;
            color:#1f2d3d;
            font-size: 22px;
            font-weight: 900;
        }
        .add-profile-btn {
            background:#143f73;
            color:#fff;
            border:none;
            border-radius:10px;
            padding:10px 18px;
            font-weight:900;
            cursor:pointer;
            text-decoration:none;
            display:inline-block;
        }
        .stats {
            display:grid;
            grid-template-columns: repeat(4, minmax(200px, 1fr));
            gap:16px;
            margin-bottom:16px;
        }
        @media (max-width: 1100px) {
            .stats { grid-template-columns: repeat(2, minmax(200px, 1fr)); }
        }
        .stat-card {
            background:#fff;
            border-radius:14px;
            padding:16px;
            box-shadow:0 2px 10px rgba(0,0,0,0.04);
            border-left:6px solid #2c3e50;
        }
        .stat-card .kicker {
            color:#6b7280;
            font-weight:900;
            font-size: 12px;
            margin-bottom:6px;
        }
        .stat-card .value {
            font-size: 24px;
            font-weight: 1000;
            color:#111827;
        }
        .stat-card.total { border-left-color:#2c3e50; }
        .stat-card.a { border-left-color:#2ecc71; }
        .stat-card.b { border-left-color:#f39c12; }
        .stat-card.c { border-left-color:#e74c3c; }

        .controls {
            display:flex;
            gap:12px;
            align-items:center;
            justify-content:space-between;
            flex-wrap:wrap;
            margin-bottom:12px;
        }
        .search-wrap {
            flex: 1;
            min-width: 280px;
            display:flex;
            gap:10px;
        }
        .search-wrap input {
            flex:1;
            padding:10px 14px;
            border:1px solid #e5e7eb;
            border-radius:10px;
            outline:none;
        }
        .search-wrap button {
            padding:10px 16px;
            border:none;
            border-radius:10px;
            background:#1f4f87;
            color:#fff;
            font-weight:900;
            cursor:pointer;
        }

        .table-wrap {
            background:#fff;
            border-radius:14px;
            box-shadow:0 2px 10px rgba(0,0,0,0.04);
            overflow-x:auto;
        }
        table {
            width:100%;
            border-collapse:separate;
            border-spacing:0;
            min-width: 980px;
        }
        thead th {
            text-align:left;
            font-size:12px;
            padding:14px 12px;
            background:#1f4f87;
            color:#fff;
            font-weight:900;
            position:sticky;
            top:0;
            z-index:2;
        }
        a.emp-id-link {
            color:#1f4f87;
            font-weight:900;
            text-decoration:none;
            border-bottom:1px solid transparent;
        }
        a.emp-id-link:hover {
            text-decoration:underline;
            border-bottom-color:#1f4f87;
        }
        #deleteModal {
            display:none;
            position:fixed;
            inset:0;
            z-index:20000;
            width:100%;
            height:100%;
            background:rgba(0,0,0,0.5);
            justify-content:center;
            align-items:center;
            padding:20px;
            box-sizing:border-box;
        }
        #deleteModal.is-open {
            display:flex;
        }
        #deleteModal .delete-modal-box {
            position:relative;
            z-index:1;
            background:#fff;
            padding:24px;
            border-radius:12px;
            text-align:center;
            max-width:420px;
            width:100%;
            box-shadow:0 20px 50px rgba(0,0,0,0.25);
        }
        #deleteModal .delete-modal-box h3 {
            margin:0 0 10px 0;
            color:#1f2d3d;
        }
        #deleteModal .delete-modal-box p {
            margin:0 0 20px 0;
            color:#4b5563;
        }
        #deleteModal .delete-modal-actions {
            display:flex;
            gap:12px;
            justify-content:center;
            flex-wrap:wrap;
        }
        #deleteModal .delete-modal-actions button {
            min-width:100px;
        }
        tbody td {
            padding:12px;
            border-bottom:1px solid #eef2f7;
            color:#111827;
            font-size: 13px;
        }
        tbody tr:hover {
            background:#f8fafc;
        }
        .actions {
            display:flex;
            gap:8px;
            flex-wrap:wrap;
        }
        .btn-action {
            border:none;
            border-radius:8px;
            padding:8px 10px;
            font-weight:900;
            cursor:pointer;
            font-size: 12px;
            text-decoration:none;
            display:inline-block;
        }
        .btn-edit { background:#3498db; color:#fff; }
        .btn-view { background:#10b981; color:#fff; }
        .btn-del { background:#e74c3c; color:#fff; }

        .pagination {
            display:flex;
            gap:8px;
            align-items:center;
            justify-content:flex-end;
            margin-top:14px;
        }
        .page-link {
            border:1px solid #e5e7eb;
            background:#fff;
            padding:8px 12px;
            border-radius:10px;
            text-decoration:none;
            color:#1f2d3d;
            font-weight:900;
            font-size: 12px;
        }
        .page-link.active {
            background:#1f4f87;
            border-color:#1f4f87;
            color:#fff;
        }
        .muted-empty {
            text-align:center;
            padding: 30px 12px;
            color:#6b7280;
            font-weight: 900;
        }
    </style>

    <div class="page-title">
        <div>
            <h2>Employees</h2>
            <div style="color:#6b7280;font-weight:900;font-size:13px;margin-top:4px;">
                Total: <?php echo (int)$totalEmployees; ?> employees
            </div>
        </div>

        <a class="add-profile-btn" href="health.php?mode=add&id=0">
            + Add New Profile
        </a>
    </div>

    <div class="stats">
        <div class="stat-card total">
            <div class="kicker">Total Employees</div>
            <div class="value"><?php echo (int)$totalEmployees; ?></div>
        </div>
        <div class="stat-card a">
            <div class="kicker">Class A</div>
            <div class="value"><?php echo (int)$classCounts['Class A']; ?></div>
        </div>
        <div class="stat-card b">
            <div class="kicker">Class B</div>
            <div class="value"><?php echo (int)$classCounts['Class B']; ?></div>
        </div>
        <div class="stat-card c">
            <div class="kicker">Class C</div>
            <div class="value"><?php echo (int)$classCounts['Class C']; ?></div>
        </div>
    </div>

    <div class="controls">
        <form method="GET" class="search-wrap">
            <input type="text" name="search" placeholder="Search by name, id, department..." value="<?php echo h($search); ?>">
            <button type="submit">Search</button>
            <?php if ($search !== ''): ?>
                <a class="page-link" href="employees.php" style="margin-left:6px;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Emp ID</th>
                    <th>Name</th>
                    <th>Birthday</th>
                    <th>Contact No</th>
                    <th>Religion</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Occupation</th>
                    <th>Civil Status</th>
                    <th>Class</th>
                    <th style="min-width:170px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $employees = [];
                $ids = [];
                if ($r && $r->num_rows > 0) {
                    while ($row = $r->fetch_assoc()) {
                        $employees[] = $row;
                        $ids[] = (int)$row['id'];
                    }
                }

                $profileMap = [];
                if (count($ids) > 0) {
                    $placeholders = implode(',', array_fill(0, count($ids), '?'));
                    $types = str_repeat('i', count($ids));
                    $stmt = $conn->prepare("SELECT employee_id, class_type, profile_data FROM employee_health_profiles WHERE employee_id IN ($placeholders)");
                    $stmt->bind_param($types, ...$ids);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while ($pr = $res->fetch_assoc()) {
                        $profileMap[(int)$pr['employee_id']] = $pr;
                    }
                    $stmt->close();
                }

                if (count($employees) === 0) {
                    echo '<tr><td colspan="11" class="muted-empty">No employees found</td></tr>';
                } else {
                    foreach ($employees as $row) {
                        $eid = (int)$row['id'];
                        $empName = $row['name'] ?? '';
                        $birthday = $row['birthday'] ?? '';
                        $contact = $row['contact'] ?? '';
                        $gender = $row['sex'] ?? '';
                        $age = $row['age'] ?? '';
                        $occupation = $row['department'] ?? '';
                        $civilStatus = $row['civil_status'] ?? '';

                        $religion = '';
                        $classType = '';
                        if (isset($profileMap[$eid])) {
                            $classType = (string)($profileMap[$eid]['class_type'] ?? '');
                            $decoded = json_decode((string)($profileMap[$eid]['profile_data'] ?? ''), true);
                            if (is_array($decoded)) {
                                $religion = (string)arr_get($decoded, ['personal', 'religion'], '');
                                $occFromProfile = arr_get($decoded, ['personal', 'occupation'], '');
                                if ($occFromProfile !== '') $occupation = $occFromProfile;
                                $civilFromProfile = arr_get($decoded, ['personal', 'civil_status'], '');
                                if ($civilFromProfile !== '') $civilStatus = $civilFromProfile;
                            }
                        }

                        $empIdDisplay = 'EMP-' . str_pad((string)$eid, 5, '0', STR_PAD_LEFT);
                        echo '<tr>';
                        echo '<td><a class="emp-id-link" href="health.php?id=' . (int)$eid . '&mode=view">' . h($empIdDisplay) . '</a></td>';
                        echo '<td>' . h($empName) . '</td>';
                        echo '<td>' . h($birthday) . '</td>';
                        echo '<td>' . h($contact) . '</td>';
                        echo '<td>' . h($religion) . '</td>';
                        echo '<td>' . h($gender) . '</td>';
                        echo '<td>' . h($age) . '</td>';
                        echo '<td>' . h($occupation) . '</td>';
                        echo '<td>' . h($civilStatus) . '</td>';
                        echo '<td>' . h($classType) . '</td>';
                        echo '<td>';
                        echo '<div class="actions">';
                        echo '<a class="btn-action btn-edit" href="health.php?id=' . $eid . '&mode=edit">Edit</a>';
                        echo '<a class="btn-action btn-view" href="health.php?id=' . $eid . '&mode=view">View</a>';
                        echo '<button class="btn-action btn-del" type="button" onclick="confirmDelete(' . $eid . ')">Delete</button>';
                        echo '</div>';
                        echo '</td>';
                        echo '</tr>';
                    }
                }
            ?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <?php
            $queryBase = $search !== '' ? ('?search=' . urlencode($search) . '&') : '?';
            echo '<a class="page-link" href="employees.php' . ($search !== '' ? ('?search=' . urlencode($search) . '&page=1') : '?page=1') . '">First</a>';
            $prevPage = max(1, $page - 1);
            echo '<a class="page-link" href="employees.php' . ($search !== '' ? ('?search=' . urlencode($search) . '&page=' . $prevPage) : '?page=' . $prevPage) . '">← Prev</a>';
            for ($p = 1; $p <= $totalPages; $p++) {
                if ($p < $page - 3 || $p > $page + 3) continue;
                $active = $p === $page ? 'active' : '';
                echo '<a class="page-link ' . $active . '" href="employees.php' . ($search !== '' ? ('?search=' . urlencode($search) . '&page=' . $p) : '?page=' . $p) . '">' . $p . '</a>';
            }
            $nextPage = min($totalPages, $page + 1);
            echo '<a class="page-link" href="employees.php' . ($search !== '' ? ('?search=' . urlencode($search) . '&page=' . $nextPage) : '?page=' . $nextPage) . '">Next →</a>';
        ?>
    </div>
</div>

<!-- HIDDEN DELETE FORM -->
<form id="deleteForm" method="POST" action="delete_employee.php">
    <input type="hidden" name="id" id="deleteId">
    <input type="hidden" name="delete" value="1">
</form>

<!-- CUSTOM MODAL (z-index above sticky table header + layout chrome) -->
<div id="deleteModal" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle" onclick="if(event.target===this) closeModal();">
    <div class="delete-modal-box" onclick="event.stopPropagation();">
        <h3 id="deleteModalTitle">Delete Employee</h3>
        <p>Are you sure you want to delete this employee?</p>
        <div class="delete-modal-actions">
            <button type="button" onclick="deleteNow()">Delete</button>
            <button type="button" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
let deleteId = 0;

function confirmDelete(id){
    deleteId = id;
    document.getElementById("deleteModal").classList.add("is-open");
}

function closeModal(){
    document.getElementById("deleteModal").classList.remove("is-open");
}

function deleteNow(){
    document.getElementById("deleteId").value = deleteId;
    document.getElementById("deleteForm").submit();
}
</script>