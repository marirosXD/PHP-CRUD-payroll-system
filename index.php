<?php
// index.php - Main page with table and actions
require_once 'database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
              
        <?php if(isset($_GET['msg'])): ?>
            <div class="alert success">
                <?php 
                    if($_GET['msg'] == 'created') echo "Employee added successfully!";
                    if($_GET['msg'] == 'updated') echo "Employee updated successfully!";
                    if($_GET['msg'] == 'deleted') echo "Employee deleted successfully!";
                ?>
            </div>
        <?php endif; ?>
        
        <div class="actions-bar">
            <a href="create.php" class="btn btn-primary"> Add New Employee</a>
        </div>

        <div class="table-container">
            <h2>Employee Payroll Records</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Rate/Day (₱)</th>
                        <th>Days Absent</th>
                        <th>Work Days</th>
                        <th>Gross Pay (₱)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM employees ORDER BY id DESC");
                    if($stmt->rowCount() > 0):
                        while ($row = $stmt->fetch()):
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['position']); ?></td>
                        <td>₱<?php echo number_format($row['rate_per_day'], 2); ?></td>
                        <td><?php echo $row['days_absent']; ?></td>
                        <td><?php echo $row['total_work_days']; ?></td>
                        <td class="total-pay">₱<?php echo number_format($row['gross_pay'], 2); ?></td>
                        <td class="action-buttons">
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="edit-btn"> Edit</a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this employee?')"> Delete</a>
                        </td>
                    </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">No records found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>