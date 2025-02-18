<?php
session_start();
include "DB_W1.php"; 
if (!isset($_SESSION['stdid'])) {
    header("Location: index.php");
    exit();
}
?>

<?php
$sql = "SELECT * FROM tb_student";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📋 รายชื่อนิสิต</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 40px;
        }
        .table th, .table td {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">📋 รายชื่อนิสิต</h2>
    
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>รหัสนิสิต</th>
                <th>คำนำหน้า</th>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>ชั้นปี</th>
                <th>เกรดเฉลี่ย</th>
                <th>วันเกิด</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['stdid']); ?></td>
                    <td><?php echo htmlspecialchars($row['prefix']); ?></td>
                    <td><?php echo htmlspecialchars($row['fname']); ?></td>
                    <td><?php echo htmlspecialchars($row['lname']); ?></td>
                    <td><?php echo htmlspecialchars($row['class']); ?></td>
                    <td><?php echo htmlspecialchars($row['gpa']); ?></td>
                    <td><?php echo htmlspecialchars($row['brithday']); ?></td>
                    <td>
                        <a href="edit.php?stdid=<?php echo $row['stdid']; ?>" class="btn btn-warning btn-sm">✏️ แก้ไข</a>
                        <a href="delete.php?stdid=<?php echo $row['stdid']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?')">🗑️ ลบ</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="d-flex justify-content-between">
        <a href="add.php" class="btn btn-success">➕ เพิ่มข้อมูลนิสิต</a>
        <a href="logout.php" class="btn btn-danger">🚪 ออกจากระบบ</a> 
</div>

<?php $conn->close(); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
