<?php
require_once 'database.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->execute([$id]);
$employee = $stmt->fetch();

if (!$employee) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $rate_per_day = $_POST['rate_per_day'];
    $days_absent = $_POST['days_absent'];
    $total_work_days = $_POST['total_work_days'];
    $gross_pay = ($total_work_days - $days_absent) * $rate_per_day;
    
    $stmt = $pdo->prepare("UPDATE employees SET name=?, position=?, rate_per_day=?, days_absent=?, total_work_days=?, gross_pay=? WHERE id=?");
    
    if ($stmt->execute([$name, $position, $rate_per_day, $days_absent, $total_work_days, $gross_pay, $id])) {
        header("Location: index.php?msg=updated");
        exit();
    } else {
        $error = "Failed to update employee";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee - Payroll System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1> Edit Employee</h1>
        
        <div class="form-container">
            <?php if (isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Employee Name:</label>
                        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($employee['name']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="position">Position:</label>
                        <select id="position" name="position" required>
                            <option value="">Select Position</option>
                            <option value="Manager" <?php echo $employee['position'] == 'Manager' ? 'selected' : ''; ?>>Manager - ₱1,500/day</option>
                            <option value="Supervisor" <?php echo $employee['position'] == 'Supervisor' ? 'selected' : ''; ?>>Supervisor - ₱1,200/day</option>
                            <option value="Staff" <?php echo $employee['position'] == 'Staff' ? 'selected' : ''; ?>>Staff - ₱900/day</option>
                            <option value="Intern" <?php echo $employee['position'] == 'Intern' ? 'selected' : ''; ?>>Intern - ₱500/day</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="rate_per_day">Rate per Day (₱):</label>
                        <input type="number" id="rate_per_day" name="rate_per_day" step="0.01" required value="<?php echo $employee['rate_per_day']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="days_absent">Days Absent:</label>
                        <input type="number" id="days_absent" name="days_absent" required min="0" value="<?php echo $employee['days_absent']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="total_work_days">Total Work Days:</label>
                        <input type="number" id="total_work_days" name="total_work_days" required min="0" value="<?php echo $employee['total_work_days']; ?>">
                    </div>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-success"> Update Employee</button>
                    <a href="index.php" class="btn btn-secondary">⬅ Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Auto-update rate per day based on position
        document.getElementById('position').addEventListener('change', function() {
            const rates = {
                'Manager': 1500,
                'Supervisor': 1200,
                'Staff': 900,
                'Intern': 500
            };
            if (rates[this.value]) {
                document.getElementById('rate_per_day').value = rates[this.value];
            }
            updateGrossPay();
        });
        
        // Calculate gross pay in real-time
        function updateGrossPay() {
            const rate = parseFloat(document.getElementById('rate_per_day').value) || 0;
            const absent = parseInt(document.getElementById('days_absent').value) || 0;
            const workDays = parseInt(document.getElementById('total_work_days').value) || 0;
            const gross = (workDays - absent) * rate;
            
            let hint = document.getElementById('grossHint');
            if (!hint) {
                hint = document.createElement('div');
                hint.id = 'grossHint';
                hint.className = 'gross-hint';
                document.querySelector('.form-group:last-child').appendChild(hint);
            }
            hint.innerHTML = `<strong> Estimated Gross Pay: ₱${gross.toFixed(2)}</strong>`;
        }
        
        document.getElementById('rate_per_day').addEventListener('input', updateGrossPay);
        document.getElementById('days_absent').addEventListener('input', updateGrossPay);
        document.getElementById('total_work_days').addEventListener('input', updateGrossPay);
        updateGrossPay();
    </script>
</body>
</html>