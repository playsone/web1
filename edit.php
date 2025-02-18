<?php
session_start();
include "DB_W1.php";

// ตรวจสอบว่า session 'stdid' มีค่าหรือไม่ (ถ้ายังไม่ล็อกอิน)
if (!isset($_SESSION['stdid'])) {
    echo "<script>alert('❌ คุณต้องล็อกอินก่อน!'); window.location='index.php';</script>";
    exit();
}

// ตรวจสอบว่า stdid ถูกส่งมาหรือไม่
if (isset($_GET['stdid'])) {
    $stdid = $_GET['stdid'];
    
    // ดึงข้อมูลนิสิตจากฐานข้อมูล
    $sql = "SELECT * FROM tb_student WHERE stdid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $stdid);
    $stmt->execute();
    $result = $stmt->get_result();
    

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<script>alert('ไม่พบข้อมูลนิสิต!'); window.location='view.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ไม่มีรหัสนิสิต!'); window.location='view.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stdid = trim($_POST['stdid']);
    $prefix = trim($_POST['prefix']);
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $class = trim($_POST['class']);
    $gpa = trim($_POST['gpa']);
    $brithday = trim($_POST['brithday']);
    
    // อัพเดตข้อมูลในฐานข้อมูล
    $update_sql = "UPDATE tb_student SET prefix = ?, fname = ?, lname = ?, class = ?, gpa = ?, brithday = ? WHERE stdid = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssss", $prefix, $fname, $lname, $class, $gpa, $brithday, $stdid);
    
    if ($stmt->execute()) {
        echo "<script>alert('อัพเดตข้อมูลสำเร็จ!'); window.location='view.php';</script>";
        exit();
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการอัพเดตข้อมูล!');</script>";
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลนิสิต</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">✏️ แก้ไขข้อมูลนิสิต</h2>

    <form method="post" action="edit.php?stdid=<?php echo $row['stdid']; ?>">
        <div class="mb-3">
            <label class="form-label">รหัสนิสิต</label>
            <input type="text" name="stdid" class="form-control" value="<?php echo htmlspecialchars($row['stdid']); ?>" readonly>
        </div>
        
        <div class="mb-3">
            <label class="form-label">คำนำหน้า</label>
            <input type="text" name="prefix" class="form-control" value="<?php echo htmlspecialchars($row['prefix']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">ชื่อ</label>
            <input type="text" name="fname" class="form-control" value="<?php echo htmlspecialchars($row['fname']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">นามสกุล</label>
            <input type="text" name="lname" class="form-control" value="<?php echo htmlspecialchars($row['lname']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">ชั้นปี</label>
            <input type="text" name="class" class="form-control" value="<?php echo htmlspecialchars($row['class']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">เกรดเฉลี่ย</label>
            <input type="text" name="gpa" class="form-control" value="<?php echo htmlspecialchars($row['gpa']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">วันเกิด</label>
            <input type="date" name="brithday" class="form-control" value="<?php echo htmlspecialchars($row['brithday']); ?>" required>
        </div>

        <button type="submit" class="btn btn-success w-100">บันทึกการแก้ไข</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
